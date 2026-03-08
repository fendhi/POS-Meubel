<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Penjualan</h1>
    <p class="text-sm text-slate-600 mt-1">Transaksi pemasukan, otomatis menghitung HPP dan laba kotor.</p>
  </div>
  <a href="?page=sale_form" class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">+ Tambah Penjualan</a>
</div>

<div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-brand-100 p-4">
    <form class="grid grid-cols-1 md:grid-cols-3 gap-3" method="get">
      <input type="hidden" name="page" value="sales" />
      <div>
        <label class="text-xs text-slate-500">Dari</label>
        <input type="date" name="from" value="<?= e($from) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
      </div>
      <div>
        <label class="text-xs text-slate-500">Sampai</label>
        <input type="date" name="to" value="<?= e($to) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
      </div>
      <div class="flex items-end gap-2">
        <button class="w-full px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Filter</button>
        <a class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm" href="?page=sales">Reset</a>
      </div>
    </form>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Tanggal</th>
            <th class="text-left py-2">Customer</th>
            <th class="text-right py-2">Total</th>
            <th class="text-right py-2">Laba Kotor</th>
            <th class="text-right py-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($sales as $s): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2">
              <a class="text-brand-800 hover:underline" href="?page=sale_view&id=<?= (int)$s['id'] ?>"><?= e($s['sale_date']) ?></a>
            </td>
            <td class="py-2"><?= e($s['customer_name'] ?: '-') ?></td>
            <td class="py-2 text-right font-medium text-slate-900"><?= money_idr((float)$s['total']) ?></td>
            <td class="py-2 text-right <?= (float)$s['gross_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?> font-medium"><?= money_idr((float)$s['gross_profit']) ?></td>
            <td class="py-2 text-right whitespace-nowrap">
              <a class="text-sm text-brand-800 hover:underline" href="?page=sale_view&id=<?= (int)$s['id'] ?>">Detail</a>
              <form class="inline" method="post" onsubmit="return confirm('Batalkan penjualan ini? Stok akan dikembalikan.')">
                <input type="hidden" name="cancel_id" value="<?= (int)$s['id'] ?>" />
                <button class="ml-3 text-sm text-rose-700 hover:underline">Batal</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$sales): ?>
          <tr><td class="py-4 text-slate-500" colspan="5">Belum ada data.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <h2 class="font-semibold text-brand-900">Ringkasan</h2>
    <div class="mt-4 space-y-3 text-sm">
      <div class="flex items-center justify-between">
        <div class="text-slate-600">Total Penjualan</div>
        <div class="font-semibold text-slate-900"><?= money_idr((float)$sum['total_sales']) ?></div>
      </div>
      <div class="flex items-center justify-between">
        <div class="text-slate-600">HPP</div>
        <div class="font-semibold text-slate-900"><?= money_idr((float)$sum['total_cost']) ?></div>
      </div>
      <div class="flex items-center justify-between">
        <div class="text-slate-600">Laba Kotor</div>
        <div class="font-semibold <?= (float)$sum['gross_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr((float)$sum['gross_profit']) ?></div>
      </div>
      <div class="pt-3 border-t border-brand-100 text-xs text-slate-500">Sesuai filter tanggal (jika dipakai).</div>
    </div>
  </div>
</div>
