<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    flash_set('error', 'ID penjualan tidak valid.');
    redirect('?page=sales');
}

$stmt = db()->prepare('SELECT * FROM sales WHERE id = ?');
$stmt->execute([$id]);
$sale = $stmt->fetch();
if (!$sale) {
    flash_set('error', 'Penjualan tidak ditemukan.');
    redirect('?page=sales');
}

$itemsStmt = db()->prepare('SELECT si.*, p.name AS product_name, p.unit FROM sale_items si JOIN products p ON p.id = si.product_id WHERE si.sale_id = ?');
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll();

render('sale_view', compact('sale', 'items'));
