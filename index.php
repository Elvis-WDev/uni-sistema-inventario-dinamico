<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Catálogo de Productos | TecnoMarket';
$products = [];
$categories = [];
$dbError = null;
$search = trim((string) ($_GET['buscar'] ?? ''));
$selectedCategory = trim((string) ($_GET['categoria'] ?? ''));

try {
    $pdo = db();

    $categoryStatement = $pdo->query('SELECT DISTINCT categoria FROM productos ORDER BY categoria');
    $categories = $categoryStatement->fetchAll(PDO::FETCH_COLUMN);

    $sql = 'SELECT id, nombre, categoria, descripcion, precio, stock, imagen_url, destacado
            FROM productos
            WHERE 1 = 1';
    $params = [];

    if ($search !== '') {
        $sql .= ' AND (nombre LIKE :buscar OR descripcion LIKE :buscar)';
        $params['buscar'] = '%' . $search . '%';
    }

    if ($selectedCategory !== '') {
        $sql .= ' AND categoria = :categoria';
        $params['categoria'] = $selectedCategory;
    }

    $sql .= ' ORDER BY destacado DESC, nombre ASC';

    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    $products = $statement->fetchAll();
} catch (Throwable $exception) {
    error_log('Database connection error: ' . $exception->getMessage());
    $dbError = 'No se pudo conectar con la base de datos. Verifica las variables DB_HOST, DB_NAME, DB_USER y DB_PASS, e importa database/schema.sql.';

    if (app_debug_enabled()) {
        $config = db_connection_summary();
        $dbError .= ' Detalle: ' . $exception->getMessage()
            . ' | host=' . $config['host']
            . ' | port=' . $config['port']
            . ' | database=' . $config['database']
            . ' | user=' . $config['user']
            . ' | password_set=' . ($config['password_set'] ? 'yes' : 'no');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-content">
        <div class="hero-copy">
            <p class="eyebrow">Presentación personal</p>
            <h1>Elvis Michael</h1>
            <p>
                Soy un estudiante interesado en el desarrollo web, la tecnología y la creación
                de soluciones digitales útiles. Me gusta aprender con proyectos prácticos,
                explorar herramientas nuevas y convertir ideas en sistemas funcionales.
            </p>
            <div class="hero-actions">
                <a class="button primary" href="#catalogo">Ver productos</a>
                <a class="button light" href="contacto.php">Contactarme</a>
            </div>
        </div>
        <figure class="hero-photo">
            <img src="evidencias/me.jpeg" alt="Foto personal de Elvis Michael">
        </figure>
        <dl class="hero-stats" aria-label="Resumen personal">
            <div>
                <dt>Web</dt>
                <dd>desarrollo PHP</dd>
            </div>
            <div>
                <dt>Tech</dt>
                <dd>tecnología</dd>
            </div>
            <div>
                <dt>Crear</dt>
                <dd>proyectos prácticos</dd>
            </div>
        </dl>
    </div>
</section>

<section class="catalog-section" id="catalogo">
    <div class="container">
        <div class="section-heading">
            <div>
                <p class="eyebrow">Inventario actualizado</p>
                <h2>Catálogo de productos</h2>
            </div>
            <p>Encuentra equipos listos para comprar o solicitar información detallada.</p>
        </div>

        <form class="filter-bar" method="get" action="index.php" aria-label="Filtros del catálogo">
            <label>
                <span>Buscar</span>
                <input
                    type="search"
                    name="buscar"
                    placeholder="Laptop, cámara, teclado..."
                    value="<?= h($search) ?>"
                >
            </label>

            <label>
                <span>Categoría</span>
                <select name="categoria">
                    <option value="">Todas</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= h($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>>
                            <?= h($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <button class="button dark" type="submit">Filtrar</button>
            <a class="button ghost" href="index.php">Limpiar</a>
        </form>

        <?php if ($dbError !== null): ?>
            <div class="notice error" role="alert">
                <?= h($dbError) ?>
            </div>
        <?php elseif ($products === []): ?>
            <div class="notice">
                No se encontraron productos con los filtros actuales.
            </div>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <article class="product-card">
                        <img src="<?= h($product['imagen_url']) ?>" alt="<?= h($product['nombre']) ?>" loading="lazy">
                        <div class="product-body">
                            <div class="product-meta">
                                <span><?= h($product['categoria']) ?></span>
                                <?php if ((int) $product['destacado'] === 1): ?>
                                    <strong>Destacado</strong>
                                <?php endif; ?>
                            </div>
                            <h3><?= h($product['nombre']) ?></h3>
                            <p><?= h($product['descripcion']) ?></p>
                            <div class="product-footer">
                                <span class="price">$<?= number_format((float) $product['precio'], 2, '.', ',') ?></span>
                                <span class="stock"><?= (int) $product['stock'] ?> en stock</span>
                            </div>
                            <div class="product-actions">
                                <button
                                    class="product-link add-to-cart"
                                    type="button"
                                    data-product-id="<?= (int) $product['id'] ?>"
                                    data-product-name="<?= h($product['nombre']) ?>"
                                    data-product-category="<?= h($product['categoria']) ?>"
                                    data-product-price="<?= h(number_format((float) $product['precio'], 2, '.', '')) ?>"
                                    data-product-stock="<?= (int) $product['stock'] ?>"
                                    <?= (int) $product['stock'] === 0 ? 'disabled' : '' ?>
                                >
                                    Agregar al carrito
                                </button>
                                <a class="product-secondary" href="checkout.php">Ver carrito</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
