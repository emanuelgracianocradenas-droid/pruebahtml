<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

start_app_session();
$authenticated = isset($_SESSION['usuario_id'], $_SESSION['usuario_nombre']);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

echo json_encode([
    'authenticated' => $authenticated,
    'nombre' => $authenticated ? (string) $_SESSION['usuario_nombre'] : null,
    'csrf_token' => $authenticated ? csrf_token() : null,
], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
