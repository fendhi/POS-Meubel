<?php
$isEdit = ($id ?? 0) > 0;
$p = $product ?? ['sku'=>'','name'=>'','category'=>'','unit'=>'pcs','cost_price'=>0,'sell_price'=>0];
?>

<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900"><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h1>
    <p class="text-sm text-slate-600 mt-1">Simpan harga modal dan harga jual untuk perhitungan laba.</p>
  </div>
  <a href="?page=products" class="text-sm text-brand-800 hover:underline">Kembali</a>
</div>

<div class="mt-5 bg-white rounded-2xl shadow-card border border-brand-100 p-5">
  <form method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="text-sm text-slate-700">SKU (opsional)</label>
      <input name="sku" value="<?= e((string)$p['sku']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
    </div>
    <div>
      <label class="text-sm text-slate-700">Kategori (opsional)</label>
      <input name="category" value="<?= e((string)$p['category']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
    </div>

    <div class="md:col-span-2">
      <label class="text-sm text-slate-700">Nama Produk</label>
      <input name="name" value="<?= e((string)$p['name']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" required />
    </div>

    <div>
      <label class="text-sm text-slate-700">Satuan</label>
      <input name="unit" value="<?= e((string)$p['unit']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" placeholder="pcs" />
    </div>
    <div></div>

    <div>
      <label class="text-sm text-slate-700">Harga Modal (HPP per unit)</label>
      <input type="number" step="0.01" min="0" name="cost_price" value="<?= e((string)$p['cost_price']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
    </div>
    <div>
      <label class="text-sm text-slate-700">Harga Jual</label>
      <input type="number" step="0.01" min="0" name="sell_price" value="<?= e((string)$p['sell_price']) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
    </div>

    <div class="md:col-span-2 flex gap-2 mt-2">
      <button class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">Simpan</button>
      <a href="?page=products" class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Batal</a>
    </div>
  </form>
</div>
