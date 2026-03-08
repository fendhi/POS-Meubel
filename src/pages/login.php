<?php
declare(strict_types=1);

if (auth_is_logged_in()) {
    redirect('?page=dashboard');
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if (auth_attempt($username, $password)) {
        redirect('?page=dashboard');
    }

    flash_set('error', 'Login gagal. Cek username/password.');
    redirect('?page=login');
}

render('login');
