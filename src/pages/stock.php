<?php
declare(strict_types=1);

// Add stock movement
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    require_post();

    $productId = (int)($_POST['product_id'] ?? 0);
    $movementDate = date_ymd((string)($_POST['movement_date'] ?? date('Y-m-d')));
    $type = (string)($_POST['type'] ?? 'in');
    $qty = (int)($_POST['qty'] ?? 0);
    $costPrice = (float)($_POST['cost_price'] ?? 0);
    $note = trim((string)($_POST['note'] ?? ''));

    if ($productId <= 0 || $qty === 0) {
        flash_set('error', 'Produk dan qty wajib diisi (qty tidak boleh 0).');
        redirect('?page=stock');
    }

    if (!in_array($type, ['in', 'adjust', 'out'], true)) {
        flash_set('error', 'Tipe stok tidak valid.');
        redirect('?page=stock');
    }

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $pStmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = ? FOR UPDATE');
        $pStmt->execute([$productId]);
        $product = $pStmt->fetch();
        if (!$product) {
            throw new RuntimeException('Produk tidak ditemukan.');
        }

        $currentStock = (int)$product['stock'];
        $delta = $qty;

        if ($type === 'out') {
            $delta = -abs($qty);
        }

        $newStock = $currentStock + $delta;
        if ($newStock < 0) {
            throw new RuntimeException('Stok tidak cukup.');
        }

        $uStmt = $pdo->prepare('UPDATE products SET stock = stock + ?' . ($type === 'in' && $costPrice > 0 ? ', cost_price = ?' : '') . ' WHERE id = ?');
        if ($type === 'in' && $costPrice > 0) {
            $uStmt->execute([$delta, $costPrice, $productId]);
        } else {
            $uStmt->execute([$delta, $productId]);
        }

        $mStmt = $pdo->prepare('INSERT INTO stock_movements (product_id, movement_date, type, qty, cost_price, note) VALUES (?,?,?,?,?,?)');
        $mStmt->execute([$productId, $movementDate, $type, abs($qty), $costPrice, $note ?: null]);

        $pdo->commit();
        flash_set('success', 'Stok berhasil diperbarui.');
        redirect('?page=stock');
    } catch (Throwable $e) {
        $pdo->rollBack();
        flash_set('error', 'Gagal update stok: ' . $e->getMessage());
        redirect('?page=stock');
    }
}

$products = db()->query('SELECT id, name, stock, unit, cost_price FROM products ORDER BY name ASC')->fetchAll();
$movements = db()->query('SELECT sm.*, p.name AS product_name, p.unit FROM stock_movements sm JOIN products p ON p.id = sm.product_id ORDER BY sm.id DESC LIMIT 30')->fetchAll();

render('stock', compact('products', 'movements'));
