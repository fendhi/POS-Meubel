<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Produk</h1>
    <p class="text-sm text-slate-600 mt-1">Kelola produk, harga modal, harga jual, dan stok.</p>
  </div>
  <a href="?page=product_form" class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">+ Tambah Produk</a>
</div>

<div class="mt-5 bg-white rounded-2xl shadow-card border border-brand-100 p-4">
  <form class="flex gap-2" method="get">
    <input type="hidden" name="page" value="products" />
    <input name="q" value="<?= e($q) ?>" class="flex-1 rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2 text-sm" placeholder="Cari nama / SKU..." />
    <button class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Cari</button>
  </form>

  <div class="mt-4 overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="text-xs text-slate-500">
        <tr>
          <th class="text-left py-2">Produk</th>
          <th class="text-right py-2">Modal</th>
          <th class="text-right py-2">Jual</th>
          <th class="text-right py-2">Stok</th>
          <th class="text-right py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($products as $p): ?>
        <tr class="border-t border-brand-100">
          <td class="py-2">
            <div class="font-medium text-slate-900"><?= e($p['name']) ?></div>
            <div class="text-xs text-slate-500"><?= e($p['sku'] ?: '-') ?> • <?= e($p['category'] ?: 'Tanpa kategori') ?></div>
          </td>
          <td class="py-2 text-right"><?= money_idr((float)$p['cost_price']) ?></td>
          <td class="py-2 text-right font-medium"><?= money_idr((float)$p['sell_price']) ?></td>
          <td class="py-2 text-right">
            <span class="px-2 py-1 rounded-lg border border-brand-100 bg-brand-50 text-brand-800"><?= (int)$p['stock'] ?> <?= e($p['unit']) ?></span>
          </td>
          <td class="py-2 text-right whitespace-nowrap">
            <a class="text-sm text-brand-800 hover:underline" href="?page=product_form&id=<?= (int)$p['id'] ?>">Edit</a>
            <form class="inline" method="post" onsubmit="return confirm('Hapus produk ini?')">
              <input type="hidden" name="delete_id" value="<?= (int)$p['id'] ?>" />
              <button class="ml-3 text-sm text-rose-700 hover:underline">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$products): ?>
        <tr><td class="py-4 text-slate-500" colspan="5">Belum ada data.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
