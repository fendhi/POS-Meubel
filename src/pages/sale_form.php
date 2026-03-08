<?php
declare(strict_types=1);

$products = db()->query('SELECT id, name, stock, unit, cost_price, sell_price FROM products ORDER BY name ASC')->fetchAll();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    require_post();

    $saleDate = date_ymd((string)($_POST['sale_date'] ?? date('Y-m-d')));
    $customerName = trim((string)($_POST['customer_name'] ?? ''));
    $note = trim((string)($_POST['note'] ?? ''));

    $productIds = $_POST['product_id'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $sellPrices = $_POST['sell_price'] ?? [];

    $items = [];
    for ($i = 0; $i < count($productIds); $i++) {
        $pid = (int)$productIds[$i];
        $qty = (int)($qtys[$i] ?? 0);
        $sp = (float)($sellPrices[$i] ?? 0);

        if ($pid <= 0 || $qty <= 0) {
            continue;
        }
        $items[] = ['product_id' => $pid, 'qty' => $qty, 'sell_price' => $sp];
    }

    if (!$items) {
        flash_set('error', 'Minimal 1 item penjualan harus diisi.');
        redirect('?page=sale_form');
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $total = 0.0;
        $totalCost = 0.0;
        $saleItemsInsert = [];

        foreach ($items as $it) {
            $pStmt = $pdo->prepare('SELECT id, name, stock, cost_price, sell_price FROM products WHERE id = ? FOR UPDATE');
            $pStmt->execute([$it['product_id']]);
            $p = $pStmt->fetch();
            if (!$p) {
                throw new RuntimeException('Produk tidak ditemukan.');
            }

            $qty = (int)$it['qty'];
            $stock = (int)$p['stock'];
            if ($stock < $qty) {
                throw new RuntimeException('Stok tidak cukup untuk produk: ' . $p['name']);
            }

            $costPrice = (float)$p['cost_price'];
            $sellPrice = (float)$it['sell_price'];
            if ($sellPrice <= 0) {
                $sellPrice = (float)$p['sell_price'];
            }
            if ($sellPrice <= 0) {
                throw new RuntimeException('Harga jual belum diisi untuk produk: ' . $p['name']);
            }

            $subtotal = $qty * $sellPrice;
            $costTotal = $qty * $costPrice;
            $profit = $subtotal - $costTotal;

            $total += $subtotal;
            $totalCost += $costTotal;

            $saleItemsInsert[] = [
                'product_id' => (int)$p['id'],
                'qty' => $qty,
                'sell_price' => $sellPrice,
                'cost_price' => $costPrice,
                'subtotal' => $subtotal,
                'cost_total' => $costTotal,
                'profit' => $profit,
                'product_name' => (string)$p['name'],
            ];
        }

        $grossProfit = $total - $totalCost;

        $sIns = $pdo->prepare('INSERT INTO sales (sale_date, customer_name, note, total, total_cost, gross_profit) VALUES (?,?,?,?,?,?)');
        $sIns->execute([$saleDate, $customerName ?: null, $note ?: null, $total, $totalCost, $grossProfit]);
        $saleId = (int)$pdo->lastInsertId();

        $iIns = $pdo->prepare('INSERT INTO sale_items (sale_id, product_id, qty, sell_price, cost_price, subtotal, cost_total, profit) VALUES (?,?,?,?,?,?,?,?)');
        $uStock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');
        $mOut = $pdo->prepare("INSERT INTO stock_movements (product_id, movement_date, type, qty, cost_price, note) VALUES (?,?,?,?,?,?)");

        foreach ($saleItemsInsert as $row) {
            $iIns->execute([$saleId, $row['product_id'], $row['qty'], $row['sell_price'], $row['cost_price'], $row['subtotal'], $row['cost_total'], $row['profit']]);
            $uStock->execute([$row['qty'], $row['product_id']]);
            $mOut->execute([$row['product_id'], $saleDate, 'out', $row['qty'], $row['cost_price'], 'Sale #' . $saleId]);
        }

        $pdo->commit();
        flash_set('success', 'Penjualan tersimpan.');
        redirect('?page=sale_view&id=' . $saleId);
    } catch (Throwable $e) {
        $pdo->rollBack();
        flash_set('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
        redirect('?page=sale_form');
    }
}

render('sale_form', compact('products'));
