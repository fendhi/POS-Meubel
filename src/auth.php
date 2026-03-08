<?php
declare(strict_types=1);

function auth_is_ready(): bool
{
    return db_table_exists('users');
}

function auth_is_logged_in(): bool
{
    if (!auth_is_ready()) {
        // If not installed yet, force install
        return false;
    }
    return isset($_SESSION['user_id']);
}

function auth_user(): ?array
{
    if (!auth_is_logged_in()) {
        return null;
    }
    $stmt = db()->prepare('SELECT id, username, name FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function auth_attempt(string $username, string $password): bool
{
    $stmt = db()->prepare('SELECT id, username, password_hash, name FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!$user) {
        return false;
    }
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }
    $_SESSION['user_id'] = (int)$user['id'];
    return true;
}

function auth_logout(): void
{
    unset($_SESSION['user_id']);
}
