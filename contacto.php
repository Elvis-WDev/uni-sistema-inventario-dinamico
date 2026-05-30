<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Contacto | TecnoMarket';
$errors = [];
$sent = false;
$old = [
    'nombre' => '',
    'correo' => '',
    'mensaje' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nombre'] = trim((string) ($_POST['nombre'] ?? ''));
    $old['correo'] = trim((string) ($_POST['correo'] ?? ''));
    $old['mensaje'] = trim((string) ($_POST['mensaje'] ?? ''));

    if (text_length($old['nombre']) < 2 || text_length($old['nombre']) > 80) {
        $errors['nombre'] = 'El nombre debe tener entre 2 y 80 caracteres.';
    }

    if (!filter_var($old['correo'], FILTER_VALIDATE_EMAIL) || text_length($old['correo']) > 120) {
        $errors['correo'] = 'Ingresa un correo electrónico válido.';
    }

    if (text_length($old['mensaje']) < 10 || text_length($old['mensaje']) > 1000) {
        $errors['mensaje'] = 'El mensaje debe tener entre 10 y 1000 caracteres.';
    }

    if ($errors === []) {
        try {
            $statement = db()->prepare(
                'INSERT INTO contactos (nombre, correo, mensaje) VALUES (:nombre, :correo, :mensaje)'
            );
            $statement->execute([
                'nombre' => $old['nombre'],
                'correo' => $old['correo'],
                'mensaje' => $old['mensaje'],
            ]);

            $sent = true;
            $old = ['nombre' => '', 'correo' => '', 'mensaje' => ''];
        } catch (Throwable $exception) {
            error_log('Database insert error: ' . $exception->getMessage());
            $errors['general'] = 'No se pudo guardar el mensaje. Verifica las variables de conexión con MySQL.';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container narrow">
        <p class="eyebrow">Atención personalizada</p>
        <h1>Solicita información sobre un producto</h1>
        <p>Cuéntanos qué equipo necesitas y te responderemos con disponibilidad, precio y recomendaciones.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container contact-grid">
        <form class="contact-form" method="post" action="contacto.php">
            <?php if ($sent): ?>
                <div class="notice success" role="status">
                    Mensaje enviado correctamente. Tus datos fueron registrados en MySQL.
                </div>
            <?php endif; ?>

            <?php if (isset($errors['general'])): ?>
                <div class="notice error" role="alert">
                    <?= h($errors['general']) ?>
                </div>
            <?php endif; ?>

            <label>
                <span>Nombre</span>
                <input
                    type="text"
                    name="nombre"
                    value="<?= h($old['nombre']) ?>"
                    required
                    minlength="2"
                    maxlength="80"
                    autocomplete="name"
                    <?= isset($errors['nombre']) ? 'aria-describedby="error-nombre"' : '' ?>
                >
                <?php if (isset($errors['nombre'])): ?>
                    <small class="field-error" id="error-nombre"><?= h($errors['nombre']) ?></small>
                <?php endif; ?>
            </label>

            <label>
                <span>Correo</span>
                <input
                    type="email"
                    name="correo"
                    value="<?= h($old['correo']) ?>"
                    required
                    maxlength="120"
                    autocomplete="email"
                    <?= isset($errors['correo']) ? 'aria-describedby="error-correo"' : '' ?>
                >
                <?php if (isset($errors['correo'])): ?>
                    <small class="field-error" id="error-correo"><?= h($errors['correo']) ?></small>
                <?php endif; ?>
            </label>

            <label>
                <span>Mensaje</span>
                <textarea
                    name="mensaje"
                    required
                    minlength="10"
                    maxlength="1000"
                    rows="7"
                    <?= isset($errors['mensaje']) ? 'aria-describedby="error-mensaje"' : '' ?>
                ><?= h($old['mensaje']) ?></textarea>
                <?php if (isset($errors['mensaje'])): ?>
                    <small class="field-error" id="error-mensaje"><?= h($errors['mensaje']) ?></small>
                <?php endif; ?>
            </label>

            <button class="button primary" type="submit">Enviar mensaje</button>
        </form>

        <aside class="contact-info" aria-label="Información de contacto">
            <h2>Atención al cliente</h2>
            <p>
                Usa este formulario para consultar disponibilidad, características o precios.
                Cada solicitud queda registrada para seguimiento.
            </p>
            <dl>
                <div>
                    <dt>Correo</dt>
                    <dd>ventas@tecnomarket.demo</dd>
                </div>
                <div>
                    <dt>Horario</dt>
                    <dd>Lunes a viernes, 09:00 - 18:00</dd>
                </div>
                <div>
                    <dt>Seguimiento</dt>
                    <dd>Respuesta personalizada por correo</dd>
                </div>
            </dl>
        </aside>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
