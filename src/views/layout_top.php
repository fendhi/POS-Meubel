<?php
$flash = flash_get();
$user = auth_user();
$loggedIn = auth_is_logged_in();

$currentPage = (string)($_GET['page'] ?? 'dashboard');

function nav_link(string $page, string $label, string $currentPage): string
{
  $active = $page === $currentPage;
  $base = 'block px-3 py-2 rounded-lg text-slate-700 transition border border-transparent';
  $hover = 'hover:bg-brand-50 hover:border-brand-100';
  $activeCls = 'bg-brand-50 border-brand-200 text-brand-900 font-medium';
  $cls = $base . ' ' . $hover . ' ' . ($active ? $activeCls : '');
  return '<a class="' . $cls . '" href="?page=' . htmlspecialchars($page, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
}

$navItems = [
  ['dashboard', 'Dashboard'],
  ['products', 'Produk'],
  ['stock', 'Stok'],
  ['sales', 'Penjualan'],
  ['expenses', 'Pengeluaran'],
  ['reports', 'Laporan'],
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e(APP_NAME) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#f9f5f0',
              100: '#f2e8dc',
              200: '#e6d1b9',
              300: '#d6b38d',
              400: '#c49362',
              500: '#a87445',
              600: '#8b5a2b',
              700: '#6f4520',
              800: '#553516',
              900: '#3c240c'
            }
          },
          boxShadow: {
            card: '0 10px 30px rgba(60,36,12,0.10)'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-brand-50 text-slate-900">
<?php if ($loggedIn): ?>
<div class="min-h-screen flex">
  <!-- Mobile overlay -->
  <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 hidden z-40 md:hidden"></div>

  <!-- Mobile drawer sidebar -->
  <aside id="sidebarMobile" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-brand-100 z-50 transform -translate-x-full transition-transform duration-200 md:hidden">
    <div class="p-5 flex items-start justify-between gap-3">
      <div>
        <div class="text-xl font-semibold text-brand-800">Mabel POS</div>
        <div class="text-xs text-slate-500 mt-1">Meubel & Perabot Rumah Tangga</div>
      </div>
      <button type="button" id="sidebarCloseMobile" class="p-2 rounded-lg hover:bg-brand-50 text-slate-600" aria-label="Tutup">
        ✕
      </button>
    </div>
    <nav class="px-3 pb-6 text-sm space-y-1">
      <?php foreach ($navItems as [$p, $label]): ?>
        <?= nav_link($p, $label, $currentPage) ?>
      <?php endforeach; ?>
      <div class="mt-4 border-t border-brand-100 pt-3">
        <a class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-brand-50 hover:border-brand-100 border border-transparent transition" href="?page=logout">Logout</a>
      </div>
    </nav>
  </aside>

  <!-- Desktop sidebar -->
  <aside id="sidebarDesktop" class="w-64 hidden md:flex flex-col bg-white border-r border-brand-100">
    <div class="p-5">
      <div class="text-xl font-semibold text-brand-800">Mabel POS</div>
      <div class="text-xs text-slate-500 mt-1">Meubel & Perabot Rumah Tangga</div>
    </div>
    <nav class="px-3 pb-6 text-sm space-y-1">
      <?php foreach ($navItems as [$p, $label]): ?>
        <?= nav_link($p, $label, $currentPage) ?>
      <?php endforeach; ?>
      <div class="mt-4 border-t border-brand-100 pt-3">
        <a class="block px-3 py-2 rounded-lg text-slate-700 hover:bg-brand-50 hover:border-brand-100 border border-transparent transition" href="?page=logout">Logout</a>
      </div>
    </nav>
  </aside>

  <main class="flex-1">
    <header class="bg-white border-b border-brand-100">
      <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button type="button" id="sidebarToggle" class="p-2 rounded-xl border border-brand-100 hover:bg-brand-50" aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-brand-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div class="text-lg font-semibold text-brand-800">Mabel POS</div>
        </div>
        <div class="text-sm text-slate-600"><?= $user ? ('Halo, <span class=\"font-medium\">' . e($user['name']) . '</span>') : '' ?></div>
      </div>
    </header>

    <div class="max-w-6xl mx-auto px-4 py-6">
      <?php if ($flash): ?>
        <div class="mb-5 rounded-xl border px-4 py-3 <?= $flash['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900' ?>">
          <?= e($flash['message']) ?>
        </div>
      <?php endif; ?>
<?php else: ?>
<div class="min-h-screen flex items-center justify-center px-4 py-10">
  <div class="w-full">
    <?php if ($flash): ?>
      <div class="max-w-md mx-auto mb-5 rounded-xl border px-4 py-3 <?= $flash['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-rose-50 border-rose-200 text-rose-900' ?>">
        <?= e($flash['message']) ?>
      </div>
    <?php endif; ?>
<?php endif; ?>
