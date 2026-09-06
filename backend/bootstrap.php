<?php
declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'prueba_html';
const DB_USER = 'root';
const DB_PASSWORD = '';

/** Start the application session with browser-safe defaults. */
function start_app_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (int) ($_SERVER['SERVER_PORT'] ?? 80) === 443;

    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttps,
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

function database(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function csrf_token(): string
{
    start_app_session();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): bool
{
    start_app_session();
    $submitted = $_POST['csrf_token'] ?? '';

    return is_string($submitted)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submitted);
}

function redirect(string $location): never
{
    header('Location: ' . $location, true, 303);
    exit;
}

function save_old_registration(array $values): void
{
    start_app_session();
    unset($values['password'], $values['confirmar_password'], $values['csrf_token']);
    $_SESSION['registro_anterior'] = $values;
}

function old_registration(): array
{
    start_app_session();
    $values = $_SESSION['registro_anterior'] ?? [];
    unset($_SESSION['registro_anterior']);

    return is_array($values) ? $values : [];
}

function escape_html(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
