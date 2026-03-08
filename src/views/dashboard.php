<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Dashboard</h1>
  </div>
  <div class="flex gap-2">
    <a href="?page=sale_form" class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">+ Penjualan</a>
    <a href="?page=expense_form" class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-brand-800 text-sm">+ Pengeluaran</a>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
  <?php
    $cards = [
      ['title' => 'Hari Ini', 'range' => e($today), 'sum' => $sumToday],
      ['title' => 'Minggu Ini', 'range' => e($weekStart) . ' — ' . e($weekEnd), 'sum' => $sumWeek],
      ['title' => 'Bulan Ini', 'range' => e($monthStart) . ' — ' . e($monthEnd), 'sum' => $sumMonth],
    ];
  ?>
  <?php foreach ($cards as $c): $s = $c['sum']; ?>
    <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
      <div class="flex items-start justify-between">
        <div>
          <div class="text-sm text-slate-500"><?= e($c['title']) ?></div>
          <div class="text-xs text-slate-400 mt-0.5"><?= $c['range'] ?></div>
        </div>
        <div class="text-xs px-2 py-1 rounded-lg bg-brand-50 text-brand-800 border border-brand-100"><?= (int)$s['sale_count'] ?> transaksi</div>
      </div>

      <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
        <div class="rounded-xl bg-brand-50 border border-brand-100 p-3">
          <div class="text-xs text-slate-500">Pemasukan (Penjualan)</div>
          <div class="font-semibold text-brand-900 mt-1"><?= money_idr($s['total_sales']) ?></div>
        </div>
        <div class="rounded-xl bg-white border border-brand-100 p-3">
          <div class="text-xs text-slate-500">HPP (Modal Terjual)</div>
          <div class="font-semibold text-slate-900 mt-1"><?= money_idr($s['total_cost']) ?></div>
        </div>
        <div class="rounded-xl bg-white border border-brand-100 p-3">
          <div class="text-xs text-slate-500">Laba Kotor (Penjualan − HPP)</div>
          <div class="font-semibold mt-1 <?= (float)$s['gross_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr((float)$s['gross_profit']) ?></div>
        </div>
        <div class="rounded-xl bg-brand-50 border border-brand-100 p-3">
          <div class="text-xs text-slate-500">Pengeluaran</div>
          <div class="font-semibold text-brand-900 mt-1"><?= money_idr($s['total_expenses']) ?></div>
        </div>
        <div class="rounded-xl bg-white border border-brand-100 p-3">
          <?php $net = (float)$s['net_profit']; $profit = max($net, 0); $loss = max(-$net, 0); ?>
          <div class="text-xs text-slate-500">Keuntungan Bersih (setelah pengeluaran)</div>
          <div class="font-semibold mt-1 text-emerald-700"><?= money_idr($profit) ?></div>
          <div class="mt-3 text-xs text-slate-500">Kerugian Bersih</div>
          <div class="font-semibold mt-1 text-rose-700"><?= money_idr($loss) ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-brand-900">Stok Terendah</h2>
      <a href="?page=products" class="text-sm text-brand-800 hover:underline">Kelola produk</a>
    </div>
    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Produk</th>
            <th class="text-right py-2">Stok</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($lowStock as $p): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2">
              <div class="font-medium text-slate-900"><?= e($p['name']) ?></div>
            </td>
            <td class="py-2 text-right">
              <span class="px-2 py-1 rounded-lg border border-brand-100 bg-brand-50 text-brand-800"><?= (int)$p['stock'] ?> <?= e($p['unit']) ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$lowStock): ?>
          <tr><td class="py-3 text-slate-500" colspan="2">Belum ada produk.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-brand-900">Penjualan Terbaru</h2>
      <a href="?page=sales" class="text-sm text-brand-800 hover:underline">Lihat semua</a>
    </div>
    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Tanggal</th>
            <th class="text-left py-2">Customer</th>
            <th class="text-right py-2">Total</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($recentSales as $s): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2"><?= e($s['sale_date']) ?></td>
            <td class="py-2"><?= e($s['customer_name'] ?: '-') ?></td>
            <td class="py-2 text-right font-medium text-slate-900"><?= money_idr((float)$s['total']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$recentSales): ?>
          <tr><td class="py-3 text-slate-500" colspan="3">Belum ada penjualan.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
