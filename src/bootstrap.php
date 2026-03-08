<?php
declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/db.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/auth.php';

// Default timezone (Indonesia)
date_default_timezone_set(APP_TIMEZONE);

// Auto-setup schema (no manual install page required)
if (!isset($_SESSION['autosetup_attempted'])) {
	$_SESSION['autosetup_attempted'] = true;
	try {
		if (!db_table_exists('users')) {
			$schemaFile = __DIR__ . '/sql/schema.sql';
			if (file_exists($schemaFile)) {
				$sql = file_get_contents($schemaFile);
				if ($sql !== false) {
					db()->exec($sql);
				}
			}
		}
	} catch (Throwable $e) {
		// Typical causes: database doesn't exist, wrong port/credentials, MySQL not running
		flash_set('error', 'Koneksi/auto-setup database gagal. Pastikan database "' . DB_NAME . '" sudah dibuat dan MySQL berjalan di port ' . DB_PORT . '. Detail: ' . $e->getMessage());
	}
}
