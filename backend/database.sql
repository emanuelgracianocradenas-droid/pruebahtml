CREATE DATABASE IF NOT EXISTS `prueba_html`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `prueba_html`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `apellido` VARCHAR(80) NOT NULL,
  `genero` VARCHAR(20) NOT NULL,
  `tipo_documento` VARCHAR(8) NOT NULL,
  `numero_documento` VARCHAR(30) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(25) NOT NULL,
  `correo` VARCHAR(254) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `creado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuarios_correo` (`correo`),
  UNIQUE KEY `uq_usuarios_documento` (`tipo_documento`, `numero_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
