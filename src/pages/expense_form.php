<?php
declare(strict_types=1);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$expense = null;

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM expenses WHERE id = ?');
    $stmt->execute([$id]);
    $expense = $stmt->fetch();
    if (!$expense) {
        flash_set('error', 'Data pengeluaran tidak ditemukan.');
        redirect('?page=expenses');
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $expenseDate = date_ymd((string)($_POST['expense_date'] ?? date('Y-m-d')));
    $category = trim((string)($_POST['category'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $amount = (float)($_POST['amount'] ?? 0);

    if ($description === '' || $amount <= 0) {
        flash_set('error', 'Deskripsi wajib dan nominal harus > 0.');
        redirect($id > 0 ? ('?page=expense_form&id=' . $id) : '?page=expense_form');
    }

    if ($id > 0) {
        $stmt = db()->prepare('UPDATE expenses SET expense_date=?, category=?, description=?, amount=? WHERE id=?');
        $stmt->execute([$expenseDate, $category ?: null, $description, $amount, $id]);
        flash_set('success', 'Pengeluaran diperbarui.');
    } else {
        $stmt = db()->prepare('INSERT INTO expenses (expense_date, category, description, amount) VALUES (?,?,?,?)');
        $stmt->execute([$expenseDate, $category ?: null, $description, $amount]);
        flash_set('success', 'Pengeluaran ditambahkan.');
    }

    redirect('?page=expenses');
}

render('expense_form', compact('id', 'expense'));
