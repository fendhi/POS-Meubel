<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Stok</h1>
    <p class="text-sm text-slate-600 mt-1">Stok masuk, keluar, atau penyesuaian.</p>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <h2 class="font-semibold text-brand-900">Update Stok</h2>
    <form method="post" class="mt-4 space-y-4">
      <div>
        <label class="text-sm text-slate-700">Produk</label>
        <select name="product_id" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" required>
          <option value="">-- pilih --</option>
          <?php foreach ($products as $p): ?>
            <option value="<?= (int)$p['id'] ?>"><?= e($p['name']) ?> (stok <?= (int)$p['stock'] ?> <?= e($p['unit']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="text-sm text-slate-700">Tanggal</label>
          <input type="date" name="movement_date" value="<?= e(date('Y-m-d')) ?>" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
        </div>
        <div>
          <label class="text-sm text-slate-700">Tipe</label>
          <select name="type" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2">
            <option value="in">Stok Masuk</option>
            <option value="out">Stok Keluar</option>
            <option value="adjust">Penyesuaian (+/-)</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
          <label class="text-sm text-slate-700">Qty</label>
          <input type="number" name="qty" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" placeholder="contoh: 5 (atau -2 untuk adjust)" required />
          <div class="text-xs text-slate-500 mt-1">Untuk <b>Keluar</b> masukkan angka positif (sistem mengurangi).</div>
        </div>
        <div>
          <label class="text-sm text-slate-700">Harga Modal (opsional)</label>
          <input type="number" step="0.01" min="0" name="cost_price" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" placeholder="update modal saat stok masuk" />
        </div>
      </div>

      <div>
        <label class="text-sm text-slate-700">Catatan (opsional)</label>
        <input name="note" class="mt-1 w-full rounded-xl border border-brand-100 focus:border-brand-400 focus:ring-brand-200 focus:ring-2 px-3 py-2" />
      </div>

      <button class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">Simpan</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl shadow-card border border-brand-100 p-5">
    <div class="flex items-center justify-between">
      <h2 class="font-semibold text-brand-900">Riwayat Stok (30 terakhir)</h2>
      <a href="?page=products" class="text-sm text-brand-800 hover:underline">Produk</a>
    </div>

    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Tanggal</th>
            <th class="text-left py-2">Produk</th>
            <th class="text-left py-2">Tipe</th>
            <th class="text-right py-2">Qty</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($movements as $m): ?>
          <tr class="border-t border-brand-100">
            <td class="py-2"><?= e($m['movement_date']) ?></td>
            <td class="py-2">
              <div class="font-medium text-slate-900"><?= e($m['product_name']) ?></div>
              <div class="text-xs text-slate-500"><?= e($m['note'] ?? '') ?></div>
            </td>
            <td class="py-2">
              <?php
                $badge = 'bg-brand-50 border-brand-100 text-brand-800';
                if ($m['type'] === 'out') $badge = 'bg-rose-50 border-rose-200 text-rose-800';
                if ($m['type'] === 'adjust') $badge = 'bg-amber-50 border-amber-200 text-amber-800';
              ?>
              <span class="px-2 py-1 rounded-lg border <?= $badge ?> text-xs">
                <?= $m['type'] === 'in' ? 'Masuk' : ($m['type'] === 'out' ? 'Keluar' : 'Adjust') ?>
              </span>
            </td>
            <td class="py-2 text-right font-medium"><?= (int)$m['qty'] ?> <?= e($m['unit']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$movements): ?>
          <tr><td class="py-4 text-slate-500" colspan="4">Belum ada riwayat stok.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
