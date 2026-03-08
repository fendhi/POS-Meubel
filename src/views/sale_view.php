<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Detail Penjualan</h1>
    <p class="text-sm text-slate-600 mt-1">#<?= (int)$sale['id'] ?> • <?= e($sale['sale_date']) ?> • <?= e($sale['customer_name'] ?: '-') ?></p>
  </div>
  <div class="flex gap-2">
    <a href="?page=sales" class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Kembali</a>
    <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">Print</button>
  </div>
</div>

<div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="lg:col-span-2 bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Produk</th>
            <th class="text-right py-2">Qty</th>
            <th class="text-right py-2">Harga</th>
            <th class="text-right py-2">Subtotal</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $it): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2">
              <div class="font-medium text-slate-900"><?= e($it['product_name']) ?></div>
            </td>
            <td class="py-2 text-right"><?= (int)$it['qty'] ?> <?= e($it['unit']) ?></td>
            <td class="py-2 text-right"><?= money_idr((float)$it['sell_price']) ?></td>
            <td class="py-2 text-right font-medium text-slate-900"><?= money_idr((float)$it['subtotal']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if (!empty($sale['note'])): ?>
      <div class="mt-4 text-sm text-slate-700">
        <div class="text-xs text-slate-500">Catatan</div>
        <div class="mt-1"><?= e($sale['note']) ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <h2 class="font-semibold text-brand-900">Ringkasan</h2>
    <div class="mt-4 space-y-3 text-sm">
      <div class="flex items-center justify-between">
        <div class="text-slate-600">Total</div>
        <div class="font-semibold text-slate-900"><?= money_idr((float)$sale['total']) ?></div>
      </div>
      <div class="flex items-center justify-between">
        <div class="text-slate-600">HPP</div>
        <div class="font-semibold text-slate-900"><?= money_idr((float)$sale['total_cost']) ?></div>
      </div>
      <div class="flex items-center justify-between">
        <div class="text-slate-600">Laba Kotor</div>
        <div class="font-semibold <?= (float)$sale['gross_profit'] >= 0 ? 'text-emerald-700' : 'text-rose-700' ?>"><?= money_idr((float)$sale['gross_profit']) ?></div>
      </div>
    </div>
    <div class="mt-4 pt-4 border-t border-brand-100 text-xs text-slate-500">HPP diambil dari harga modal produk saat transaksi disimpan.</div>
  </div>
</div>

<style>
@media print {
  aside, header, button { display: none !important; }
  main { padding: 0 !important; }
  .shadow-card { box-shadow: none !important; }
  body { background: white !important; }
}
</style>
