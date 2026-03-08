<?php
declare(strict_types=1);

// Delete product
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['delete_id'])) {
    require_post();
    $id = (int)$_POST['delete_id'];

    try {
        $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        flash_set('success', 'Produk berhasil dihapus.');
    } catch (Throwable $e) {
        flash_set('error', 'Gagal menghapus produk. Pastikan tidak digunakan di transaksi.');
    }

    redirect('?page=products');
}

$q = trim((string)($_GET['q'] ?? ''));

if ($q !== '') {
    $stmt = db()->prepare('SELECT * FROM products WHERE name LIKE ? OR sku LIKE ? ORDER BY id DESC');
    $like = '%' . $q . '%';
    $stmt->execute([$like, $like]);
    $products = $stmt->fetchAll();
} else {
    $products = db()->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
}

render('products', compact('products', 'q'));
