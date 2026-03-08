<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        flash_set('error', 'Produk tidak ditemukan.');
        redirect('?page=products');
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $sku = trim((string)($_POST['sku'] ?? ''));
    $name = trim((string)($_POST['name'] ?? ''));
    $category = trim((string)($_POST['category'] ?? ''));
    $unit = trim((string)($_POST['unit'] ?? 'pcs'));
    $cost = (float)($_POST['cost_price'] ?? 0);
    $sell = (float)($_POST['sell_price'] ?? 0);

    if ($name === '') {
        flash_set('error', 'Nama produk wajib diisi.');
        redirect($id > 0 ? ('?page=product_form&id=' . $id) : '?page=product_form');
    }

    if ($cost < 0 || $sell < 0) {
        flash_set('error', 'Harga tidak boleh negatif.');
        redirect($id > 0 ? ('?page=product_form&id=' . $id) : '?page=product_form');
    }

    if ($id > 0) {
        $stmt = db()->prepare('UPDATE products SET sku=?, name=?, category=?, unit=?, cost_price=?, sell_price=? WHERE id=?');
        $stmt->execute([$sku ?: null, $name, $category ?: null, $unit ?: 'pcs', $cost, $sell, $id]);
        flash_set('success', 'Produk berhasil diperbarui.');
    } else {
        $stmt = db()->prepare('INSERT INTO products (sku, name, category, unit, cost_price, sell_price, stock) VALUES (?,?,?,?,?,?,0)');
        $stmt->execute([$sku ?: null, $name, $category ?: null, $unit ?: 'pcs', $cost, $sell]);
        flash_set('success', 'Produk berhasil ditambahkan.');
    }

    redirect('?page=products');
}

render('product_form', compact('id', 'product'));
