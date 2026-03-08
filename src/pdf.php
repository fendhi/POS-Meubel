<?php
declare(strict_types=1);

function pdf_can_use_dompdf(): bool
{
    if (!ENABLE_PDF_EXPORT) {
        return false;
    }
    $autoload = __DIR__ . '/../vendor/autoload.php';
    return file_exists($autoload);
}

function pdf_download_dompdf(string $filename, string $html): never
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    require_once $autoload;

  $class = 'Dompdf\\Dompdf';
  if (!class_exists($class)) {
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }

  $dompdf = new $class([
        'isRemoteEnabled' => true,
    ]);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream($filename, ['Attachment' => true]);
    exit;
}

function pdf_render_report_html(array $data): string
{
    $start = (string)$data['start'];
    $end = (string)$data['end'];

    $totalSales = (float)$data['totalSales'];
    $totalCost = (float)$data['totalCost'];
    $grossProfit = (float)$data['grossProfit'];
    $totalExpenses = (float)$data['totalExpenses'];
    $netProfit = (float)$data['netProfit'];
    $profit = max($netProfit, 0);
    $loss = max(-$netProfit, 0);

    $salesRows = $data['salesRows'] ?? [];
    $expenseRows = $data['expenseRows'] ?? [];

    ob_start();
    ?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Laporan <?= htmlspecialchars($start) ?> - <?= htmlspecialchars($end) ?></title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #111827; }
    h1 { font-size: 18px; margin: 0 0 4px; }
    .muted { color: #6B7280; }
    .grid { display: table; width: 100%; margin-top: 14px; }
    .card { display: table-cell; border: 1px solid #E5E7EB; padding: 10px; border-radius: 8px; }
    .cards { display: table; width: 100%; border-spacing: 10px; }
    .value { font-size: 16px; font-weight: bold; margin-top: 4px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border-top: 1px solid #E5E7EB; padding: 6px; text-align: left; }
    th { color: #6B7280; font-size: 11px; }
    .right { text-align: right; }
    .pos { color: #047857; font-weight: bold; }
    .neg { color: #B91C1C; font-weight: bold; }
  </style>
</head>
<body>
  <h1>Laporan Keuangan</h1>
  <div class="muted">Periode: <?= htmlspecialchars($start) ?> s/d <?= htmlspecialchars($end) ?></div>

  <div class="cards">
    <div class="card">
      <div class="muted">Pemasukan</div>
      <div class="value"><?= htmlspecialchars(money_idr($totalSales)) ?></div>
    </div>
    <div class="card">
      <div class="muted">HPP</div>
      <div class="value"><?= htmlspecialchars(money_idr($totalCost)) ?></div>
    </div>
    <div class="card">
      <div class="muted">Laba Kotor (Penjualan − HPP)</div>
      <div class="value <?= $grossProfit >= 0 ? 'pos' : 'neg' ?>"><?= htmlspecialchars(money_idr($grossProfit)) ?></div>
    </div>
    <div class="card">
      <div class="muted">Pengeluaran</div>
      <div class="value"><?= htmlspecialchars(money_idr($totalExpenses)) ?></div>
    </div>
    <div class="card">
      <div class="muted">Keuntungan Bersih</div>
      <div class="value pos"><?= htmlspecialchars(money_idr($profit)) ?></div>
      <div class="muted" style="margin-top:6px;">Kerugian Bersih</div>
      <div class="value neg"><?= htmlspecialchars(money_idr($loss)) ?></div>
      <div class="muted" style="margin-top:8px; font-size: 11px;">Rumus: Laba Kotor + (−Pengeluaran)</div>
    </div>
  </div>

  <h2 style="margin: 16px 0 6px; font-size: 14px;">Penjualan</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Tanggal</th>
        <th>Customer</th>
        <th class="right">Total</th>
        <th class="right">HPP</th>
        <th class="right">Laba Kotor</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($salesRows as $s): ?>
      <tr>
        <td><?= (int)$s['id'] ?></td>
        <td><?= htmlspecialchars((string)$s['sale_date']) ?></td>
        <td><?= htmlspecialchars((string)($s['customer_name'] ?: '-')) ?></td>
        <td class="right"><?= htmlspecialchars(money_idr((float)$s['total'])) ?></td>
        <td class="right"><?= htmlspecialchars(money_idr((float)$s['total_cost'])) ?></td>
        <td class="right"><?= htmlspecialchars(money_idr((float)$s['gross_profit'])) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$salesRows): ?>
      <tr><td colspan="6" class="muted">Tidak ada data penjualan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <h2 style="margin: 16px 0 6px; font-size: 14px;">Pengeluaran</h2>
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Deskripsi</th>
        <th class="right">Nominal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($expenseRows as $ex): ?>
      <tr>
        <td><?= htmlspecialchars((string)$ex['expense_date']) ?></td>
        <td><?= htmlspecialchars((string)($ex['category'] ?: '-')) ?></td>
        <td><?= htmlspecialchars((string)$ex['description']) ?></td>
        <td class="right"><?= htmlspecialchars(money_idr((float)$ex['amount'])) ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$expenseRows): ?>
      <tr><td colspan="4" class="muted">Tidak ada data pengeluaran.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
    <?php
    return (string)ob_get_clean();
}
