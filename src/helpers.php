<?php
declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function money_idr(float|int $value): string
{
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}

function date_ymd(string $date): string
{
    return date('Y-m-d', strtotime($date));
}

function start_of_week(string $ymd): string
{
    $dt = new DateTime($ymd);
    // Monday as start of week
    $dt->modify('monday this week');
    return $dt->format('Y-m-d');
}

function end_of_week(string $ymd): string
{
    $dt = new DateTime($ymd);
    $dt->modify('sunday this week');
    return $dt->format('Y-m-d');
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/layout_top.php';
    require __DIR__ . '/views/' . $view . '.php';
    require __DIR__ . '/views/layout_bottom.php';
}

function require_post(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        exit('Method Not Allowed');
    }
}

function input(string $key, mixed $default = ''): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

function csv_download(string $filename, array $headers, array $rows): never
{
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');

    $out = fopen('php://output', 'w');
    // UTF-8 BOM for Excel
    fwrite($out, "\xEF\xBB\xBF");
    fputcsv($out, $headers);
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
    fclose($out);
    exit;
}
