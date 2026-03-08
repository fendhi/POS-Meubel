<?php
declare(strict_types=1);

function period_summary(string $start, string $end): array
{
    $salesStmt = db()->prepare('SELECT COALESCE(SUM(total),0) AS total_sales, COALESCE(SUM(total_cost),0) AS total_cost, COALESCE(SUM(gross_profit),0) AS gross_profit, COUNT(*) AS sale_count FROM sales WHERE sale_date BETWEEN ? AND ?');
    $salesStmt->execute([$start, $end]);
    $sales = $salesStmt->fetch() ?: ['total_sales' => 0, 'total_cost' => 0, 'gross_profit' => 0, 'sale_count' => 0];

    $expStmt = db()->prepare('SELECT COALESCE(SUM(amount),0) AS total_expenses FROM expenses WHERE expense_date BETWEEN ? AND ?');
    $expStmt->execute([$start, $end]);
    $exp = $expStmt->fetch() ?: ['total_expenses' => 0];

    $grossProfit = (float)$sales['gross_profit'];
    $expenses = (float)$exp['total_expenses'];

    return [
        'total_sales' => (float)$sales['total_sales'],
        'total_cost' => (float)$sales['total_cost'],
        'gross_profit' => $grossProfit,
        'total_expenses' => $expenses,
        'net_profit' => $grossProfit - $expenses,
        'sale_count' => (int)$sales['sale_count'],
    ];
}

$today = date('Y-m-d');
$weekStart = start_of_week($today);
$weekEnd = end_of_week($today);
$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');

$sumToday = period_summary($today, $today);
$sumWeek = period_summary($weekStart, $weekEnd);
$sumMonth = period_summary($monthStart, $monthEnd);

$lowStock = db()->query('SELECT id, name, stock, unit FROM products ORDER BY stock ASC, name ASC LIMIT 8')->fetchAll();
$recentSales = db()->query('SELECT id, sale_date, customer_name, total, gross_profit FROM sales ORDER BY id DESC LIMIT 8')->fetchAll();

render('dashboard', compact('today', 'weekStart', 'weekEnd', 'monthStart', 'monthEnd', 'sumToday', 'sumWeek', 'sumMonth', 'lowStock', 'recentSales'));
