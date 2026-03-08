<?php
declare(strict_types=1);

$mode = (string)($_GET['mode'] ?? 'daily');

$today = date('Y-m-d');
$date = date_ymd((string)($_GET['date'] ?? $today));
$weekRef = date_ymd((string)($_GET['week_ref'] ?? $today));
$month = (string)($_GET['month'] ?? date('Y-m'));
$from = (string)($_GET['from'] ?? '');
$to = (string)($_GET['to'] ?? '');

$start = $today;
$end = $today;
$title = '';

if ($mode === 'daily') {
    $start = $date;
    $end = $date;
    $title = 'Laporan Harian';
} elseif ($mode === 'weekly') {
    $start = start_of_week($weekRef);
    $end = end_of_week($weekRef);
    $title = 'Laporan Mingguan';
} elseif ($mode === 'monthly') {
    $start = date('Y-m-01', strtotime($month . '-01'));
    $end = date('Y-m-t', strtotime($month . '-01'));
    $title = 'Laporan Bulanan';
} elseif ($mode === 'custom' && $from !== '' && $to !== '') {
    $start = date_ymd($from);
    $end = date_ymd($to);
    $title = 'Laporan Periode';
} else {
    $mode = 'daily';
    $start = $today;
    $end = $today;
    $title = 'Laporan Harian';
}

$salesSumStmt = db()->prepare('SELECT COALESCE(SUM(total),0) AS total_sales, COALESCE(SUM(total_cost),0) AS total_cost, COALESCE(SUM(gross_profit),0) AS gross_profit, COUNT(*) AS sale_count FROM sales WHERE sale_date BETWEEN ? AND ?');
$salesSumStmt->execute([$start, $end]);
$salesSum = $salesSumStmt->fetch() ?: ['total_sales' => 0, 'total_cost' => 0, 'gross_profit' => 0, 'sale_count' => 0];

$expSumStmt = db()->prepare('SELECT COALESCE(SUM(amount),0) AS total_expenses FROM expenses WHERE expense_date BETWEEN ? AND ?');
$expSumStmt->execute([$start, $end]);
$totalExpenses = (float)($expSumStmt->fetchColumn() ?: 0);

$totalSales = (float)$salesSum['total_sales'];
$totalCost = (float)$salesSum['total_cost'];
$grossProfit = (float)$salesSum['gross_profit'];
$netProfit = $grossProfit - $totalExpenses;

$sales = db()->prepare('SELECT id, sale_date, customer_name, total, total_cost, gross_profit FROM sales WHERE sale_date BETWEEN ? AND ? ORDER BY sale_date DESC, id DESC');
$sales->execute([$start, $end]);
$salesRows = $sales->fetchAll();

$expenses = db()->prepare('SELECT expense_date, category, description, amount FROM expenses WHERE expense_date BETWEEN ? AND ? ORDER BY expense_date DESC, id DESC');
$expenses->execute([$start, $end]);
$expenseRows = $expenses->fetchAll();

$topProducts = db()->prepare('SELECT p.name, SUM(si.qty) AS qty, SUM(si.subtotal) AS revenue, SUM(si.cost_total) AS cost, SUM(si.profit) AS profit
  FROM sale_items si
  JOIN products p ON p.id = si.product_id
  JOIN sales s ON s.id = si.sale_id
  WHERE s.sale_date BETWEEN ? AND ?
  GROUP BY p.id
  ORDER BY profit DESC
  LIMIT 10');
$topProducts->execute([$start, $end]);
$topProductsRows = $topProducts->fetchAll();

render('reports', compact(
    'mode', 'title', 'start', 'end', 'date', 'weekRef', 'month', 'from', 'to',
    'totalSales', 'totalCost', 'grossProfit', 'totalExpenses', 'netProfit',
    'salesRows', 'expenseRows', 'topProductsRows'
));
