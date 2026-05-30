-- Ejecutar con un usuario administrador de MySQL.
-- Reemplaza la contraseña antes de ejecutar este archivo.

CREATE DATABASE IF NOT EXISTS tecnomarket
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'tecnomarket_user'@'%'
    IDENTIFIED BY 'CAMBIA_ESTA_CLAVE_SEGURA';

GRANT SELECT, INSERT
    ON tecnomarket.*
    TO 'tecnomarket_user'@'%';

FLUSH PRIVILEGES;
