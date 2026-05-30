<?php

require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'Catálogo TecnoMarket';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?></title>
    <meta name="description" content="Catálogo dinámico de productos desarrollado con PHP y MySQL.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="site-header">
        <nav class="nav container" aria-label="Navegación principal">
            <a class="brand" href="index.php" aria-label="Ir al inicio">
                <span>TecnoMarket</span>
            </a>
            <div class="nav-links">
                <a href="index.php"<?= is_active('index.php') ?>>Catálogo</a>
                <a href="contacto.php"<?= is_active('contacto.php') ?>>Contacto</a>
                <a href="checkout.php"<?= is_active('checkout.php') ?>>
                    Carrito <span class="cart-count" data-cart-count>0</span>
                </a>
            </div>
        </nav>
    </header>

    <main>
