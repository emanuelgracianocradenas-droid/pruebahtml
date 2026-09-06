<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../frontend/pages/registro.php');
}

if (!verify_csrf()) {
    redirect('../frontend/pages/registro.php?error=solicitud');
}

$data = [
    'nombre' => trim((string) ($_POST['nombre'] ?? '')),
    'apellido' => trim((string) ($_POST['apellido'] ?? '')),
    'genero' => trim((string) ($_POST['genero'] ?? '')),
    'tipo_documento' => trim((string) ($_POST['tipo_documento'] ?? '')),
    'numero_documento' => trim((string) ($_POST['numero_documento'] ?? '')),
    'direccion' => trim((string) ($_POST['direccion'] ?? '')),
    'telefono' => trim((string) ($_POST['telefono'] ?? '')),
    'correo' => strtolower(trim((string) ($_POST['correo'] ?? ''))),
    'password' => (string) ($_POST['password'] ?? ''),
    'confirmar_password' => (string) ($_POST['confirmar_password'] ?? ''),
];

save_old_registration($data);

$validGeneros = ['masculino', 'femenino', 'otro', 'prefiero_no_decir'];
$validDocumentos = ['CC', 'TI', 'CE', 'PA'];
$isText = static fn (string $value, int $max): bool => $value !== '' && mb_strlen($value) <= $max;

$isValid = $isText($data['nombre'], 80)
    && $isText($data['apellido'], 80)
    && in_array($data['genero'], $validGeneros, true)
    && in_array($data['tipo_documento'], $validDocumentos, true)
    && preg_match('/^[A-Za-z0-9-]{5,30}$/', $data['numero_documento']) === 1
    && $isText($data['direccion'], 255)
    && preg_match('/^[0-9+() -]{7,25}$/', $data['telefono']) === 1
    && filter_var($data['correo'], FILTER_VALIDATE_EMAIL)
    && mb_strlen($data['correo']) <= 254
    && strlen($data['password']) >= 8
    && strlen($data['password']) <= 72
    && hash_equals($data['password'], $data['confirmar_password']);

if (!$isValid) {
    redirect('../frontend/pages/registro.php?error=validacion');
}

try {
    $statement = database()->prepare(
        'INSERT INTO usuarios
        (nombre, apellido, genero, tipo_documento, numero_documento, direccion, telefono, correo, password_hash)
        VALUES
        (:nombre, :apellido, :genero, :tipo_documento, :numero_documento, :direccion, :telefono, :correo, :password_hash)'
    );
    $statement->execute([
        ':nombre' => $data['nombre'],
        ':apellido' => $data['apellido'],
        ':genero' => $data['genero'],
        ':tipo_documento' => $data['tipo_documento'],
        ':numero_documento' => $data['numero_documento'],
        ':direccion' => $data['direccion'],
        ':telefono' => $data['telefono'],
        ':correo' => $data['correo'],
        ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
    ]);
} catch (PDOException $exception) {
    $mysqlCode = $exception->errorInfo[1] ?? null;
    if ($mysqlCode === 1062) {
        redirect('../frontend/pages/registro.php?error=duplicado');
    }

    error_log('Registro de usuario fallido: ' . $exception->getMessage());
    redirect('../frontend/pages/registro.php?error=servidor');
}

unset($_SESSION['registro_anterior']);
redirect('../frontend/pages/index.html?estado=registro_exitoso');
