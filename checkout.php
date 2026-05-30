<?php

declare(strict_types=1);

$pageTitle = 'Carrito y Checkout | TecnoMarket';

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero checkout-hero">
    <div class="container narrow">
        <p class="eyebrow">Checkout por WhatsApp</p>
        <h1>Finaliza tu compra</h1>
        <p>Revisa los productos, completa tus datos y envía el pedido directo por WhatsApp.</p>
    </div>
</section>

<section class="checkout-section" data-checkout-page>
    <div class="container checkout-grid">
        <section class="cart-panel" aria-labelledby="cart-title">
            <div class="panel-heading">
                <div>
                    <p class="eyebrow">Tu selección</p>
                    <h2 id="cart-title">Carrito</h2>
                </div>
                <button class="button ghost small" type="button" data-clear-cart>Vaciar</button>
            </div>

            <div class="cart-empty" data-cart-empty>
                <h3>Tu carrito está vacío</h3>
                <p>Agrega productos desde el catálogo para preparar el pedido.</p>
                <a class="button primary" href="index.php#catalogo">Ir al catálogo</a>
            </div>

            <div class="cart-list" data-cart-items></div>

            <div class="cart-summary" data-cart-summary hidden>
                <div>
                    <span>Subtotal</span>
                    <strong data-cart-subtotal>$0.00</strong>
                </div>
                <div>
                    <span>Total</span>
                    <strong data-cart-total>$0.00</strong>
                </div>
                <small>El valor final se confirma por WhatsApp según disponibilidad y entrega.</small>
            </div>
        </section>

        <section class="checkout-panel" aria-labelledby="checkout-title">
            <p class="eyebrow">Datos del cliente</p>
            <h2 id="checkout-title">Enviar pedido</h2>

            <form class="checkout-form" data-checkout-form>
                <label>
                    <span>Nombre completo</span>
                    <input type="text" name="nombre" required minlength="2" maxlength="80" autocomplete="name">
                </label>

                <label>
                    <span>Correo</span>
                    <input type="email" name="correo" required maxlength="120" autocomplete="email">
                </label>

                <label>
                    <span>Teléfono</span>
                    <input type="tel" name="telefono" required minlength="7" maxlength="20" autocomplete="tel">
                </label>

                <label>
                    <span>Dirección de entrega</span>
                    <textarea name="direccion" required minlength="8" maxlength="240" rows="4" autocomplete="street-address"></textarea>
                </label>

                <label>
                    <span>Notas opcionales</span>
                    <textarea name="notas" maxlength="300" rows="3" placeholder="Referencia, horario de entrega o detalle adicional"></textarea>
                </label>

                <button class="button primary whatsapp-button" type="submit">Enviar pedido por WhatsApp</button>
                <p class="checkout-help">Se abrirá WhatsApp con el detalle del pedido listo para enviar.</p>
            </form>
        </section>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
