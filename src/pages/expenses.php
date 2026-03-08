<?php
declare(strict_types=1);

// Delete expense
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['delete_id'])) {
    require_post();
    $id = (int)$_POST['delete_id'];
    $stmt = db()->prepare('DELETE FROM expenses WHERE id = ?');
    $stmt->execute([$id]);
    flash_set('success', 'Pengeluaran dihapus.');
    redirect('?page=expenses');
}

$from = trim((string)($_GET['from'] ?? ''));
$to = trim((string)($_GET['to'] ?? ''));

$where = '1=1';
$params = [];

if ($from !== '' && $to !== '') {
    $where = 'expense_date BETWEEN ? AND ?';
    $params = [date_ymd($from), date_ymd($to)];
}

$stmt = db()->prepare("SELECT * FROM expenses WHERE $where ORDER BY expense_date DESC, id DESC");
$stmt->execute($params);
$expenses = $stmt->fetchAll();

$sumStmt = db()->prepare("SELECT COALESCE(SUM(amount),0) AS total_expenses FROM expenses WHERE $where");
$sumStmt->execute($params);
$total = (float)($sumStmt->fetchColumn() ?: 0);

render('expenses', compact('expenses', 'from', 'to', 'total'));
