<?php
declare(strict_types=1);

$configPath = __DIR__ . '/config.local.php';
if (!is_file($configPath)) {
    http_response_code(500);
    exit('Local configuration is missing. Copy config.example.php to config.local.php and update the database settings.');
}

$config = require $configPath;
if (!is_array($config)) {
    throw new RuntimeException('The local configuration file must return an array.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli(
        (string)($config['db_host'] ?? '127.0.0.1'),
        (string)($config['db_user'] ?? ''),
        (string)($config['db_password'] ?? ''),
        (string)($config['db_name'] ?? 'kadir_coaching')
    );
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    error_log('Database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check config.local.php and make sure the database is running.');
}

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_is_valid(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

$whatsappNumber = preg_replace('/\D+/', '', (string)($config['whatsapp_number'] ?? ''));
$instagramUrl = trim((string)($config['instagram_url'] ?? ''));
if ($instagramUrl !== '' && !filter_var($instagramUrl, FILTER_VALIDATE_URL)) {
    $instagramUrl = '';
}
