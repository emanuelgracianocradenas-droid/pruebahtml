<?php
declare(strict_types=1);

require __DIR__ . '/../../backend/bootstrap.php';

$error = (string) ($_GET['error'] ?? '');
$errors = [
    'credenciales' => 'El correo o la contraseña no son correctos.',
    'solicitud' => 'La solicitud expiró. Vuelve a intentarlo.',
    'servidor' => 'No fue posible iniciar sesión. Inténtalo de nuevo en unos minutos.',
];
$message = $errors[$error] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="referrer" content="same-origin">
  <title>Iniciar sesión | Panadería Dulce Aroma</title>
  <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="auth-page">
  <main class="auth-shell" style="max-width: 540px;">
    <a class="auth-back" href="../../index.html">← Volver a Panadería Dulce Aroma</a>
    <section class="auth-card" aria-labelledby="login-titulo">
      <header class="auth-card__header">
        <h1 id="login-titulo">Bienvenido de nuevo</h1>
        <p>Ingresa con el correo y la contraseña de tu cuenta.</p>
      </header>
      <form class="auth-form" method="post" action="../../backend/login.php" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
        <?php if ($message !== ''): ?>
          <div class="auth-alert auth-alert--error" role="alert"><?= escape_html($message) ?></div>
        <?php endif; ?>
        <div class="auth-grid">
          <div class="auth-field auth-field--full">
            <label for="correo">Correo electrónico</label>
            <input id="correo" name="correo" type="email" maxlength="254" autocomplete="email" autofocus required>
          </div>
          <div class="auth-field auth-field--full">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
          </div>
        </div>
        <button class="auth-submit" type="submit">Iniciar sesión</button>
      </form>
      <p class="auth-footer">¿Aún no tienes una cuenta? <a href="registro.php">Regístrate</a></p>
    </section>
  </main>
</body>
</html>
