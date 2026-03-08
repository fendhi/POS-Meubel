<?php
declare(strict_types=1);

// Cancel/Delete sale (restock then delete)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['cancel_id'])) {
    require_post();
    $saleId = (int)$_POST['cancel_id'];

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $sStmt = $pdo->prepare('SELECT id, sale_date FROM sales WHERE id = ? FOR UPDATE');
        $sStmt->execute([$saleId]);
        $sale = $sStmt->fetch();
        if (!$sale) {
            throw new RuntimeException('Penjualan tidak ditemukan.');
        }

        $items = $pdo->prepare('SELECT product_id, qty, cost_price FROM sale_items WHERE sale_id = ?');
        $items->execute([$saleId]);
        $rows = $items->fetchAll();

        foreach ($rows as $it) {
            $pid = (int)$it['product_id'];
            $qty = (int)$it['qty'];

            $pStmt = $pdo->prepare('UPDATE products SET stock = stock + ? WHERE id = ?');
            $pStmt->execute([$qty, $pid]);

            $mStmt = $pdo->prepare("INSERT INTO stock_movements (product_id, movement_date, type, qty, cost_price, note) VALUES (?,?,?,?,?,?)");
            $mStmt->execute([$pid, (string)$sale['sale_date'], 'in', $qty, (float)$it['cost_price'], 'Batal sale #' . $saleId]);
        }

        $dStmt = $pdo->prepare('DELETE FROM sales WHERE id = ?');
        $dStmt->execute([$saleId]);

        $pdo->commit();
        flash_set('success', 'Penjualan dibatalkan dan stok dikembalikan.');
        redirect('?page=sales');
    } catch (Throwable $e) {
        $pdo->rollBack();
        flash_set('error', 'Gagal membatalkan penjualan: ' . $e->getMessage());
        redirect('?page=sales');
    }
}

$from = trim((string)($_GET['from'] ?? ''));
$to = trim((string)($_GET['to'] ?? ''));

$where = '1=1';
$params = [];

if ($from !== '' && $to !== '') {
    $where = 'sale_date BETWEEN ? AND ?';
    $params = [date_ymd($from), date_ymd($to)];
}

$stmt = db()->prepare("SELECT * FROM sales WHERE $where ORDER BY sale_date DESC, id DESC LIMIT 200");
$stmt->execute($params);
$sales = $stmt->fetchAll();

$sumStmt = db()->prepare("SELECT COALESCE(SUM(total),0) AS total_sales, COALESCE(SUM(total_cost),0) AS total_cost, COALESCE(SUM(gross_profit),0) AS gross_profit FROM sales WHERE $where");
$sumStmt->execute($params);
$sum = $sumStmt->fetch() ?: ['total_sales' => 0, 'total_cost' => 0, 'gross_profit' => 0];

render('sales', compact('sales', 'from', 'to', 'sum'));
