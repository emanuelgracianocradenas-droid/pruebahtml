<?php
declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../frontend/pages/login.php');
}

if (!verify_csrf()) {
    redirect('../frontend/pages/login.php?error=solicitud');
}

$email = strtolower(trim((string) ($_POST['correo'] ?? '')));
$password = (string) ($_POST['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    redirect('../frontend/pages/login.php?error=credenciales');
}

try {
    $statement = database()->prepare(
        'SELECT id, nombre, correo, password_hash FROM usuarios WHERE correo = :correo LIMIT 1'
    );
    $statement->execute([':correo' => $email]);
    $user = $statement->fetch();
} catch (PDOException $exception) {
    error_log('Inicio de sesión fallido: ' . $exception->getMessage());
    redirect('../frontend/pages/login.php?error=servidor');
}

if (!is_array($user) || !password_verify($password, $user['password_hash'])) {
    redirect('../frontend/pages/login.php?error=credenciales');
}

if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
    $rehash = database()->prepare('UPDATE usuarios SET password_hash = :password_hash WHERE id = :id');
    $rehash->execute([
        ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ':id' => $user['id'],
    ]);
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = (int) $user['id'];
$_SESSION['usuario_nombre'] = $user['nombre'];

redirect('../frontend/pages/index.html?estado=login_exitoso');
