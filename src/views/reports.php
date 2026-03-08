<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Laporan</h1>
    <p class="text-sm text-slate-600 mt-1">Harian, mingguan, bulanan, atau periode custom. Export ke Excel (CSV) dan PDF.</p>
  </div>
  <div class="flex gap-2">
    <a class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm" href="?page=export&format=csv&start=<?= e($start) ?>&end=<?= e($end) ?>">Export CSV</a>
    <a class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow" href="?page=export&format=pdf&start=<?= e($start) ?>&end=<?= e($end) ?>">Export PDF</a>
  </div>
</div>

<div class="mt-5 bg-white rounded-2xl shadow-card border border-brand-100 p-4">
  <form method="get" class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">
    <input type="hidden" name="page" value="reports" />

    <div class="lg:col-span-2">
      <label class="text-xs text-slate-500">Mode</label>
      <select name="mode" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" onchange="this.form.submit()">
        <option value="daily" <?= $mode==='daily'?'selected':'' ?>>Harian</option>
        <option value="weekly" <?= $mode==='weekly'?'selected':'' ?>>Mingguan</option>
        <option value="monthly" <?= $mode==='monthly'?'selected':'' ?>>Bulanan</option>
        <option value="custom" <?= $mode==='custom'?'selected':'' ?>>Custom</option>
      </select>
    </div>

    <div class="lg:col-span-3 <?= $mode==='daily'?'':'hidden' ?>">
      <label class="text-xs text-slate-500">Tanggal</label>
      <input type="date" name="date" value="<?= e($date) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
    </div>

    <div class="lg:col-span-3 <?= $mode==='weekly'?'':'hidden' ?>">
      <label class="text-xs text-slate-500">Referensi Minggu</label>
      <input type="date" name="week_ref" value="<?= e($weekRef) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
    </div>

    <div class="lg:col-span-3 <?= $mode==='monthly'?'':'hidden' ?>">
      <label class="text-xs text-slate-500">Bulan</label>
      <input type="month" name="month" value="<?= e($month) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
    </div>

    <div class="lg:col-span-2 <?= $mode==='custom'?'':'hidden' ?>">
      <label class="text-xs text-slate-500">Dari</label>
      <input type="date" name="from" value="<?= e($from) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
    </div>

    <div class="lg:col-span-2 <?= $mode==='custom'?'':'hidden' ?>">
      <label class="text-xs text-slate-500">Sampai</label>
      <input type="date" name="to" value="<?= e($to) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
    </div>

    <div class="lg:col-span-2">
      <button class="w-full px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Tampilkan</button>
    </div>

    <div class="lg:col-span-12 text-xs text-slate-500">Periode: <span class="font-medium text-slate-700"><?= e($start) ?></span> s/d <span class="font-medium text-slate-700"><?= e($end) ?></span></div>
  </form>
</div>

<?php $profit = max($netProfit, 0); $loss = max(-$netProfit, 0); ?>

<div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-5">
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">Pemasukan (Penjualan)</div>
    <div class="mt-2 text-xl font-semibold text-slate-900"><?= money_idr($totalSales) ?></div>
  </div>
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">HPP</div>
    <div class="mt-2 text-xl font-semibold text-slate-900"><?= money_idr($totalCost) ?></div>
  </div>
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">Laba Kotor (Penjualan − HPP)</div>
    <div class="mt-2 text-xl font-semibold <?= $grossProfit >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr($grossProfit) ?></div>
  </div>
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">Pengeluaran</div>
    <div class="mt-2 text-xl font-semibold text-rose-700"><?= money_idr($totalExpenses) ?></div>
  </div>
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">Keuntungan Bersih (setelah pengeluaran)</div>
    <div class="mt-2 text-xl font-semibold text-emerald-700"><?= money_idr($profit) ?></div>
  </div>
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="text-xs text-slate-500">Kerugian Bersih</div>
    <div class="mt-2 text-xl font-semibold text-rose-700"><?= money_idr($loss) ?></div>
  </div>
</div>

<div class="mt-3 text-xs text-slate-500">
  Rumus bersih: <span class="font-medium text-slate-700">Laba Kotor + (−Pengeluaran)</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-5">
  <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-brand-900">Ringkasan Penjualan</h2>
      <div class="text-xs text-slate-500"><?= count($salesRows) ?> transaksi</div>
    </div>
    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Tanggal</th>
            <th class="text-left py-2">Customer</th>
            <th class="text-right py-2">Total</th>
            <th class="text-right py-2">HPP</th>
            <th class="text-right py-2">Laba Kotor</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($salesRows as $s): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2"><a class="text-brand-800 hover:underline" href="?page=sale_view&id=<?= (int)$s['id'] ?>"><?= e($s['sale_date']) ?></a></td>
            <td class="py-2"><?= e($s['customer_name'] ?: '-') ?></td>
            <td class="py-2 text-right font-medium text-slate-900"><?= money_idr((float)$s['total']) ?></td>
            <td class="py-2 text-right"><?= money_idr((float)$s['total_cost']) ?></td>
            <td class="py-2 text-right font-medium <?= (float)$s['gross_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr((float)$s['gross_profit']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$salesRows): ?>
          <tr><td class="py-4 text-slate-500" colspan="5">Tidak ada penjualan di periode ini.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <h2 class="font-semibold text-brand-900">Top Produk (Profit)</h2>
    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Produk</th>
            <th class="text-right py-2">Profit</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($topProductsRows as $r): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2">
              <div class="font-medium text-slate-900"><?= e($r['name']) ?></div>
              <div class="text-xs text-slate-500"><?= (int)$r['qty'] ?> terjual</div>
            </td>
            <td class="py-2 text-right font-medium <?= (float)$r['profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr((float)$r['profit']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$topProductsRows): ?>
          <tr><td class="py-4 text-slate-500" colspan="2">Belum ada data.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-6">
      <h3 class="font-semibold text-brand-900">Pengeluaran</h3>
      <div class="mt-3 overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="text-xs text-slate-500">
            <tr>
              <th class="text-left py-2">Tanggal</th>
              <th class="text-left py-2">Deskripsi</th>
              <th class="text-right py-2">Nominal</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($expenseRows as $ex): ?>
            <tr class="border-t border-brand-100">
              <td class="py-2"><?= e($ex['expense_date']) ?></td>
              <td class="py-2">
                <div class="font-medium text-slate-900"><?= e($ex['description']) ?></div>
                <div class="text-xs text-slate-500"><?= e($ex['category'] ?: '-') ?></div>
              </td>
              <td class="py-2 text-right font-medium text-rose-700"><?= money_idr((float)$ex['amount']) ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$expenseRows): ?>
            <tr><td class="py-4 text-slate-500" colspan="3">Tidak ada pengeluaran di periode ini.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
