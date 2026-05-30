CREATE DATABASE IF NOT EXISTS `sistema-inventario-dinamico`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'tecnomarket_user'@'%'
    IDENTIFIED BY 'CAMBIA_ESTA_CLAVE_SEGURA';

GRANT SELECT, INSERT
    ON `sistema-inventario-dinamico`.*
    TO 'tecnomarket_user'@'%';

FLUSH PRIVILEGES;
