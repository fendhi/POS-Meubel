<div class="bg-white rounded-2xl shadow-card border border-brand-100 p-6">
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-xl font-semibold text-brand-800">Instalasi Database</h1>
      <p class="text-sm text-slate-600 mt-1">Membuat tabel untuk produk, stok, penjualan, pengeluaran, dan laporan.</p>
    </div>
    <div class="text-xs text-slate-500">
      <div>DB: <span class="font-medium"><?= e($dbName) ?></span></div>
      <div>Host: <?= e($dbHost) ?>:<?= e((string)$dbPort) ?></div>
    </div>
  </div>

  <div class="mt-5 rounded-xl bg-brand-50 border border-brand-100 p-4 text-sm text-slate-700">
    <div class="font-medium text-slate-800 mb-1">Prasyarat</div>
    <ul class="list-disc pl-5 space-y-1">
      <li>Database <span class="font-mono"><?= e($dbName) ?></span> sudah dibuat di MySQL (port <?= e((string)$dbPort) ?>).</li>
      <li>User MySQL sesuai konfigurasi di <span class="font-mono">src/config.php</span>.</li>
    </ul>
  </div>

  <form method="post" class="mt-6">
    <button class="px-4 py-2 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-sm shadow">
      Jalankan Instalasi
    </button>
    <a class="ml-2 text-sm text-brand-800 hover:underline" href="?page=login">Ke Login</a>
  </form>
</div>
