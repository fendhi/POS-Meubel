<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Pengeluaran</h1>
    <p class="text-sm text-slate-600 mt-1">Catat biaya operasional untuk laporan laba-rugi.</p>
  </div>
  <a href="?page=expense_form" class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">+ Tambah Pengeluaran</a>
</div>

<div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-brand-100 p-4">
    <form class="grid grid-cols-1 md:grid-cols-3 gap-3" method="get">
      <input type="hidden" name="page" value="expenses" />
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
        <a class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm" href="?page=expenses">Reset</a>
      </div>
    </form>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Tanggal</th>
            <th class="text-left py-2">Kategori</th>
            <th class="text-left py-2">Deskripsi</th>
            <th class="text-right py-2">Nominal</th>
            <th class="text-right py-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($expenses as $ex): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2"><?= e($ex['expense_date']) ?></td>
            <td class="py-2"><?= e($ex['category'] ?: '-') ?></td>
            <td class="py-2">
              <div class="font-medium text-slate-900"><?= e($ex['description']) ?></div>
            </td>
            <td class="py-2 text-right font-medium text-slate-900"><?= money_idr((float)$ex['amount']) ?></td>
            <td class="py-2 text-right whitespace-nowrap">
              <a class="text-sm text-brand-800 hover:underline" href="?page=expense_form&id=<?= (int)$ex['id'] ?>">Edit</a>
              <form class="inline" method="post" onsubmit="return confirm('Hapus pengeluaran ini?')">
                <input type="hidden" name="delete_id" value="<?= (int)$ex['id'] ?>" />
                <button class="ml-3 text-sm text-rose-700 hover:underline">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$expenses): ?>
          <tr><td class="py-4 text-slate-500" colspan="5">Belum ada data.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <h2 class="font-semibold text-brand-900">Total</h2>
    <div class="mt-2 text-2xl font-semibold text-rose-700"><?= money_idr($total) ?></div>
    <div class="text-xs text-slate-500 mt-1">Sesuai filter tanggal (jika dipakai).</div>
  </div>
</div>
