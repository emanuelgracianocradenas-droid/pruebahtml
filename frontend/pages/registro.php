<?php
declare(strict_types=1);

require __DIR__ . '/../../backend/bootstrap.php';

$old = old_registration();
$error = (string) ($_GET['error'] ?? '');
$errors = [
    'validacion' => 'Revisa los campos: la contraseña debe tener entre 8 y 72 caracteres y las contraseñas deben coincidir.',
    'duplicado' => 'Ya existe una cuenta con ese correo o documento.',
    'solicitud' => 'La solicitud expiró. Vuelve a intentarlo.',
    'servidor' => 'No fue posible guardar el registro. Inténtalo de nuevo en unos minutos.',
];
$message = $errors[$error] ?? '';
$value = static fn (string $key): string => escape_html($old[$key] ?? '');
$selected = static fn (string $key, string $option): string => ($old[$key] ?? '') === $option ? ' selected' : '';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="referrer" content="same-origin">
  <title>Crear cuenta | Panadería Dulce Aroma</title>
  <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="auth-page">
  <main class="auth-shell">
    <a class="auth-back" href="../../index.html">← Volver a Panadería Dulce Aroma</a>
    <section class="auth-card" aria-labelledby="registro-titulo">
      <header class="auth-card__header">
        <h1 id="registro-titulo">Crea tu cuenta</h1>
        <p>Regístrate para disfrutar la experiencia de Panadería Dulce Aroma. Tus datos se almacenan de forma segura.</p>
      </header>
      <form class="auth-form" method="post" action="../../backend/registro.php" autocomplete="on">
        <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
        <?php if ($message !== ''): ?>
          <div class="auth-alert auth-alert--error" role="alert"><?= escape_html($message) ?></div>
        <?php endif; ?>
        <div class="auth-grid">
          <div class="auth-field">
            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" type="text" value="<?= $value('nombre') ?>" maxlength="80" autocomplete="given-name" required>
          </div>
          <div class="auth-field">
            <label for="apellido">Apellido</label>
            <input id="apellido" name="apellido" type="text" value="<?= $value('apellido') ?>" maxlength="80" autocomplete="family-name" required>
          </div>
          <div class="auth-field">
            <label for="genero">Género</label>
            <select id="genero" name="genero" required>
              <option value="">Selecciona una opción</option>
              <option value="masculino"<?= $selected('genero', 'masculino') ?>>Masculino</option>
              <option value="femenino"<?= $selected('genero', 'femenino') ?>>Femenino</option>
              <option value="otro"<?= $selected('genero', 'otro') ?>>Otro</option>
              <option value="prefiero_no_decir"<?= $selected('genero', 'prefiero_no_decir') ?>>Prefiero no decirlo</option>
            </select>
          </div>
          <div class="auth-field">
            <label for="tipo_documento">Tipo de documento</label>
            <select id="tipo_documento" name="tipo_documento" required>
              <option value="">Selecciona una opción</option>
              <option value="CC"<?= $selected('tipo_documento', 'CC') ?>>Cédula de ciudadanía</option>
              <option value="TI"<?= $selected('tipo_documento', 'TI') ?>>Tarjeta de identidad</option>
              <option value="CE"<?= $selected('tipo_documento', 'CE') ?>>Cédula de extranjería</option>
              <option value="PA"<?= $selected('tipo_documento', 'PA') ?>>Pasaporte</option>
            </select>
          </div>
          <div class="auth-field">
            <label for="numero_documento">Número de documento</label>
            <input id="numero_documento" name="numero_documento" type="text" value="<?= $value('numero_documento') ?>" minlength="5" maxlength="30" pattern="[A-Za-z0-9-]{5,30}" autocomplete="off" required>
          </div>
          <div class="auth-field">
            <label for="telefono">Teléfono</label>
            <input id="telefono" name="telefono" type="tel" value="<?= $value('telefono') ?>" minlength="7" maxlength="25" pattern="[0-9+() -]{7,25}" autocomplete="tel" required>
          </div>
          <div class="auth-field auth-field--full">
            <label for="direccion">Dirección</label>
            <input id="direccion" name="direccion" type="text" value="<?= $value('direccion') ?>" maxlength="255" autocomplete="street-address" required>
          </div>
          <div class="auth-field auth-field--full">
            <label for="correo">Correo electrónico</label>
            <input id="correo" name="correo" type="email" value="<?= $value('correo') ?>" maxlength="254" autocomplete="email" required>
          </div>
          <div class="auth-field">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
            <p class="auth-help">Entre 8 y 72 caracteres.</p>
          </div>
          <div class="auth-field">
            <label for="confirmar_password">Confirmar contraseña</label>
            <input id="confirmar_password" name="confirmar_password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
          </div>
        </div>
        <button class="auth-submit" type="submit">Crear mi cuenta</button>
      </form>
      <p class="auth-footer">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </section>
  </main>
</body>
</html>
