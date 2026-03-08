<?php
declare(strict_types=1);

$format = (string)($_GET['format'] ?? 'csv');
$start = date_ymd((string)($_GET['start'] ?? date('Y-m-d')));
$end = date_ymd((string)($_GET['end'] ?? date('Y-m-d')));

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

$salesStmt = db()->prepare('SELECT id, sale_date, customer_name, total, total_cost, gross_profit FROM sales WHERE sale_date BETWEEN ? AND ? ORDER BY sale_date ASC, id ASC');
$salesStmt->execute([$start, $end]);
$salesRows = $salesStmt->fetchAll();

$expStmt = db()->prepare('SELECT expense_date, category, description, amount FROM expenses WHERE expense_date BETWEEN ? AND ? ORDER BY expense_date ASC, id ASC');
$expStmt->execute([$start, $end]);
$expenseRows = $expStmt->fetchAll();

if ($format === 'csv') {
    $profit = max($netProfit, 0);
    $loss = max(-$netProfit, 0);

    $headers = ['Periode', 'Pemasukan', 'HPP', 'Laba Kotor', 'Pengeluaran', 'Keuntungan Bersih', 'Kerugian Bersih'];
    $rows = [[
        $start . ' s/d ' . $end,
        $totalSales,
        $totalCost,
        $grossProfit,
        $totalExpenses,
        $profit,
        $loss,
    ]];

    $rows[] = [];
    $rows[] = ['Penjualan'];
    $rows[] = ['ID', 'Tanggal', 'Customer', 'Total', 'HPP', 'Laba Kotor'];
    foreach ($salesRows as $s) {
        $rows[] = [(int)$s['id'], (string)$s['sale_date'], (string)($s['customer_name'] ?: ''), (float)$s['total'], (float)$s['total_cost'], (float)$s['gross_profit']];
    }

    $rows[] = [];
    $rows[] = ['Pengeluaran'];
    $rows[] = ['Tanggal', 'Kategori', 'Deskripsi', 'Nominal'];
    foreach ($expenseRows as $ex) {
        $rows[] = [(string)$ex['expense_date'], (string)($ex['category'] ?: ''), (string)$ex['description'], (float)$ex['amount']];
    }

    $filename = 'laporan_' . $start . '_' . $end . '.csv';
    csv_download($filename, $headers, $rows);
}

if ($format === 'pdf') {
    require __DIR__ . '/../pdf.php';

    $html = pdf_render_report_html([
        'start' => $start,
        'end' => $end,
        'totalSales' => $totalSales,
        'totalCost' => $totalCost,
        'grossProfit' => $grossProfit,
        'totalExpenses' => $totalExpenses,
        'netProfit' => $netProfit,
        'salesRows' => $salesRows,
        'expenseRows' => $expenseRows,
    ]);

    if (pdf_can_use_dompdf()) {
        pdf_download_dompdf('laporan_' . $start . '_' . $end . '.pdf', $html);
    }

    // Fallback: show printable HTML (user can Save as PDF)
    header('Content-Type: text/html; charset=UTF-8');
    echo $html;
    exit;
}

flash_set('error', 'Format export tidak didukung.');
redirect('?page=reports');
