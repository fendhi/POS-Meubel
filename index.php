<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/src/bootstrap.php';

$page = $_GET['page'] ?? 'dashboard';
$allowedPages = [
    'dashboard',
    'products',
    'product_form',
    'stock',
    'sales',
    'sale_form',
    'sale_view',
    'expenses',
    'expense_form',
    'reports',
    'export',
    'login',
    'logout',
    'install',
];

if (!in_array($page, $allowedPages, true)) {
    http_response_code(404);
    $page = 'dashboard';
}

// Auth gate (skip for install/login)
if (!in_array($page, ['login', 'install'], true) && !auth_is_logged_in()) {
    header('Location: ?page=login');
    exit;
}

require __DIR__ . '/src/pages/' . $page . '.php';
