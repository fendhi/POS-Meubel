<?php
declare(strict_types=1);

// Installer: create DB schema if not exists
// Note: Requires you to create database mabel_pos first (or adjust DB_NAME)

$schemaFile = __DIR__ . '/../sql/schema.sql';

if (!file_exists($schemaFile)) {
    http_response_code(500);
    exit('Schema file missing: ' . $schemaFile);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    try {
        $sql = file_get_contents($schemaFile);
        db()->exec($sql);
        flash_set('success', 'Instalasi berhasil. Login dengan admin / admin.');
        redirect('?page=login');
    } catch (Throwable $e) {
        flash_set('error', 'Instalasi gagal: ' . $e->getMessage());
        redirect('?page=install');
    }
}

render('install', [
    'dbName' => DB_NAME,
    'dbHost' => DB_HOST,
    'dbPort' => DB_PORT,
]);
