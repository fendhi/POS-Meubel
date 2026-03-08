<?php
declare(strict_types=1);

// ====== APP ======
const APP_NAME = 'Mabel POS';
const APP_TIMEZONE = 'Asia/Jakarta';

// Base path when hosted under /mabel. If you host in root vhost, set ''
const APP_BASE_PATH = '/mabel';

// ====== DB (XAMPP MySQL port 3308) ======
const DB_HOST = '127.0.0.1';
const DB_PORT = 3308;
const DB_NAME = 'mebel';
const DB_USER = 'root';
const DB_PASS = '';

// ====== EXPORT ======
// If you install dompdf via composer, set this true (auto-detect also exists)
const ENABLE_PDF_EXPORT = true;
