# Autenticación de Panadería Dulce Aroma

El registro y el inicio de sesión usan PHP 8 y MySQL de XAMPP. No requieren que Node.js esté ejecutándose.

## Puesta en marcha

1. En el panel de XAMPP, inicia **Apache** y **MySQL**.
2. La base de datos es `prueba_html`. Su estructura está en `database.sql`.
   Si instalas el proyecto de nuevo, impórtalo desde phpMyAdmin con la opción **Importar**.
3. Abre `http://localhost/prueba_html/frontend/pages/index.html`.

## Flujo

- **Registrarme** abre `registro.php`. Al completarse, el usuario se inserta en `prueba_html.usuarios` y se vuelve a la portada.
- **Iniciar sesión** valida el correo y la contraseña y también devuelve a la portada.
- Las URLs antiguas `registro.html`, `login.html` e `inicioycierre.html` redirigen a los formularios activos, para no dejar rutas rotas.

## Configuración local

Las credenciales de desarrollo se centralizan en `bootstrap.php`:

```php
const DB_HOST = '127.0.0.1';
const DB_NAME = 'prueba_html';
const DB_USER = 'root';
const DB_PASSWORD = '';
```

XAMPP usa normalmente `root` sin contraseña en un entorno local. Si le asignas una contraseña a MySQL, actualiza únicamente `DB_PASSWORD`.

## Protecciones incluidas

- Consultas preparadas PDO contra inyección SQL.
- Contraseñas con `password_hash()` y comprobación con `password_verify()`.
- Token CSRF en ambos formularios.
- Restricciones de formato tanto en navegador como en servidor.
- Índices únicos para evitar correos o documentos duplicados.
- Sesiones con cookie `HttpOnly`, `SameSite=Lax` y regeneración de ID al iniciar sesión.
