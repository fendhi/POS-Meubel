<div class="flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-semibold text-brand-900">Tambah Penjualan</h1>
    <p class="text-sm text-slate-600 mt-1">Masukkan item, qty, dan harga jual. HPP diambil dari harga modal produk.</p>
  </div>
  <a href="?page=sales" class="text-sm text-brand-800 hover:underline">Kembali</a>
</div>

<div class="mt-5 bg-white rounded-2xl shadow-card border border-brand-100 p-5">
  <form method="post" id="saleForm" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="text-sm text-slate-700">Tanggal</label>
        <input type="date" name="sale_date" value="<?= e(date('Y-m-d')) ?>" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" />
      </div>
      <div>
        <label class="text-sm text-slate-700">Customer (opsional)</label>
        <input name="customer_name" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" placeholder="Nama pembeli" />
      </div>
      <div>
        <label class="text-sm text-slate-700">Catatan (opsional)</label>
        <input name="note" class="mt-1 w-full rounded-xl border border-brand-100 px-3 py-2" placeholder="Keterangan" />
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm" id="itemsTable">
        <thead class="text-xs text-slate-500">
          <tr>
            <th class="text-left py-2">Produk</th>
            <th class="text-right py-2">Stok</th>
            <th class="text-right py-2">Qty</th>
            <th class="text-right py-2">Harga Jual</th>
            <th class="text-right py-2">Subtotal</th>
            <th class="text-right py-2">Aksi</th>
          </tr>
        </thead>
        <tbody id="itemsBody"></tbody>
        <tfoot>
          <tr class="border-t border-brand-100">
            <td class="py-3" colspan="4">
              <button type="button" id="addRow" class="px-3 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">+ Tambah Item</button>
            </td>
            <td class="py-3 text-right font-semibold text-slate-900" id="grandTotal">Rp 0</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="flex gap-2">
      <button class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">Simpan</button>
      <a href="?page=sales" class="px-4 py-2 rounded-xl bg-white border border-brand-100 hover:bg-brand-50 text-sm">Batal</a>
    </div>
  </form>
</div>

<script>
  const products = <?= json_encode(array_map(fn($p) => [
    'id' => (int)$p['id'],
    'name' => (string)$p['name'],
    'stock' => (int)$p['stock'],
    'unit' => (string)$p['unit'],
    'sell_price' => (float)$p['sell_price'],
  ], $products), JSON_UNESCAPED_UNICODE) ?>;

  const idr = new Intl.NumberFormat('id-ID');

  function makeProductOptions(selectedId) {
    return products.map(p => {
      const sel = selectedId === p.id ? 'selected' : '';
      const label = `${p.name} (stok ${p.stock} ${p.unit})`;
      return `<option value="${p.id}" ${sel}>${label}</option>`;
    }).join('');
  }

  function addRow(prefill) {
    const tr = document.createElement('tr');
    tr.className = 'border-t border-brand-100';

    tr.innerHTML = `
      <td class="py-2 pr-2">
        <select name="product_id[]" class="w-full rounded-xl border border-brand-100 px-3 py-2">
          <option value="">-- pilih --</option>
          ${makeProductOptions(prefill?.product_id || null)}
        </select>
      </td>
      <td class="py-2 text-right text-slate-600 stockCell">-</td>
      <td class="py-2 text-right">
        <input name="qty[]" type="number" min="1" value="${prefill?.qty || 1}" class="w-24 text-right rounded-xl border border-brand-100 px-3 py-2" />
      </td>
      <td class="py-2 text-right">
        <input name="sell_price[]" type="number" step="0.01" min="0" value="${prefill?.sell_price || 0}" class="w-36 text-right rounded-xl border border-brand-100 px-3 py-2" />
      </td>
      <td class="py-2 text-right font-medium subtotalCell">Rp 0</td>
      <td class="py-2 text-right">
        <button type="button" class="text-sm text-rose-700 hover:underline removeBtn">Hapus</button>
      </td>
    `;

    document.getElementById('itemsBody').appendChild(tr);

    const select = tr.querySelector('select');
    const qtyInput = tr.querySelector('input[name="qty[]"]');
    const priceInput = tr.querySelector('input[name="sell_price[]"]');
    const stockCell = tr.querySelector('.stockCell');

    function sync() {
      const pid = parseInt(select.value || '0', 10);
      const p = products.find(x => x.id === pid);
      if (p) {
        stockCell.textContent = `${p.stock} ${p.unit}`;
        if (parseFloat(priceInput.value || '0') <= 0) {
          priceInput.value = p.sell_price || 0;
        }
      } else {
        stockCell.textContent = '-';
      }
      recalc();
    }

    tr.querySelector('.removeBtn').addEventListener('click', () => {
      tr.remove();
      recalc();
    });

    select.addEventListener('change', sync);
    qtyInput.addEventListener('input', recalc);
    priceInput.addEventListener('input', recalc);

    sync();
  }

  function recalc() {
    let total = 0;
    document.querySelectorAll('#itemsBody tr').forEach(tr => {
      const qty = parseInt(tr.querySelector('input[name="qty[]"]').value || '0', 10);
      const price = parseFloat(tr.querySelector('input[name="sell_price[]"]').value || '0');
      const subtotal = Math.max(0, qty) * Math.max(0, price);
      tr.querySelector('.subtotalCell').textContent = 'Rp ' + idr.format(Math.round(subtotal));
      total += subtotal;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + idr.format(Math.round(total));
  }

  document.getElementById('addRow').addEventListener('click', () => addRow());

  // init with 3 rows
  addRow();
  addRow();
  addRow();
</script>
