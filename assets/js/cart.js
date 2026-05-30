(function () {
    const CART_KEY = 'tecnomarket_cart';
    const WHATSAPP_NUMBER = '593983987321';
    const currencyFormatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    const selectors = {
        count: '[data-cart-count]',
        toast: '[data-cart-toast]',
        addButton: '.add-to-cart',
        checkoutPage: '[data-checkout-page]',
        cartItems: '[data-cart-items]',
        cartEmpty: '[data-cart-empty]',
        cartSummary: '[data-cart-summary]',
        cartSubtotal: '[data-cart-subtotal]',
        cartTotal: '[data-cart-total]',
        clearCart: '[data-clear-cart]',
        checkoutForm: '[data-checkout-form]',
    };

    function readCart() {
        try {
            const cart = JSON.parse(localStorage.getItem(CART_KEY) || '[]');
            return Array.isArray(cart) ? cart : [];
        } catch (error) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartCount();
    }

    function getNormalizedCart() {
        return readCart()
            .filter((item) => item && item.id && item.name && Number(item.price) >= 0)
            .map((item) => {
                const stock = Math.max(0, Number(item.stock) || 0);
                const quantity = Math.min(Math.max(1, Number(item.quantity) || 1), stock || 1);

                return {
                    id: String(item.id),
                    name: String(item.name),
                    category: String(item.category || 'Producto'),
                    price: Number(item.price) || 0,
                    stock,
                    quantity,
                };
            });
    }

    function formatCurrency(value) {
        return currencyFormatter.format(Number(value) || 0);
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function cartTotal(cart) {
        return cart.reduce((total, item) => total + item.price * item.quantity, 0);
    }

    function cartCount(cart) {
        return cart.reduce((total, item) => total + item.quantity, 0);
    }

    function showToast(message) {
        const toast = document.querySelector(selectors.toast);

        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.classList.add('is-visible');
        window.clearTimeout(showToast.timeoutId);
        showToast.timeoutId = window.setTimeout(() => {
            toast.classList.remove('is-visible');
        }, 2600);
    }

    function updateCartCount() {
        const count = cartCount(getNormalizedCart());

        document.querySelectorAll(selectors.count).forEach((element) => {
            element.textContent = String(count);
            element.toggleAttribute('hidden', count === 0);
        });
    }

    function addProduct(product) {
        const cart = getNormalizedCart();
        const existing = cart.find((item) => item.id === product.id);

        if (product.stock <= 0) {
            showToast('Este producto no tiene stock disponible.');
            return;
        }

        if (existing) {
            if (existing.quantity >= existing.stock) {
                showToast('Ya agregaste todo el stock disponible.');
                return;
            }

            existing.quantity += 1;
        } else {
            cart.push({
                ...product,
                quantity: 1,
            });
        }

        saveCart(cart);
        renderCheckout();
        showToast('Producto agregado al carrito.');
    }

    function updateQuantity(productId, nextQuantity) {
        const cart = getNormalizedCart()
            .map((item) => {
                if (item.id !== productId) {
                    return item;
                }

                return {
                    ...item,
                    quantity: Math.min(Math.max(1, nextQuantity), item.stock || 1),
                };
            });

        saveCart(cart);
        renderCheckout();
    }

    function removeProduct(productId) {
        const cart = getNormalizedCart().filter((item) => item.id !== productId);
        saveCart(cart);
        renderCheckout();
        showToast('Producto eliminado del carrito.');
    }

    function clearCart() {
        saveCart([]);
        renderCheckout();
        showToast('Carrito vaciado.');
    }

    function renderCheckout() {
        const page = document.querySelector(selectors.checkoutPage);

        if (!page) {
            return;
        }

        const cart = getNormalizedCart();
        const list = page.querySelector(selectors.cartItems);
        const empty = page.querySelector(selectors.cartEmpty);
        const summary = page.querySelector(selectors.cartSummary);
        const subtotal = page.querySelector(selectors.cartSubtotal);
        const total = page.querySelector(selectors.cartTotal);
        const clearButton = page.querySelector(selectors.clearCart);

        if (!list || !empty || !summary || !subtotal || !total) {
            return;
        }

        if (clearButton) {
            clearButton.hidden = cart.length === 0;
        }

        if (cart.length === 0) {
            list.innerHTML = '';
            empty.hidden = false;
            summary.hidden = true;
            return;
        }

        empty.hidden = true;
        summary.hidden = false;
        list.innerHTML = cart.map((item) => `
            <article class="cart-item" data-cart-product="${escapeHtml(item.id)}">
                <div>
                    <span>${escapeHtml(item.category)}</span>
                    <h3>${escapeHtml(item.name)}</h3>
                    <p>${formatCurrency(item.price)} c/u</p>
                </div>
                <div class="quantity-control" aria-label="Cantidad de ${escapeHtml(item.name)}">
                    <button type="button" data-quantity-action="decrease" aria-label="Restar unidad">-</button>
                    <strong>${item.quantity}</strong>
                    <button type="button" data-quantity-action="increase" aria-label="Sumar unidad">+</button>
                </div>
                <strong class="cart-item-total">${formatCurrency(item.price * item.quantity)}</strong>
                <button class="cart-remove" type="button" data-remove-cart-item aria-label="Eliminar ${escapeHtml(item.name)}">Eliminar</button>
            </article>
        `).join('');

        const totalValue = cartTotal(cart);
        subtotal.textContent = formatCurrency(totalValue);
        total.textContent = formatCurrency(totalValue);
    }

    function buildWhatsAppMessage(form) {
        const cart = getNormalizedCart();
        const data = new FormData(form);
        const nombre = String(data.get('nombre') || '').trim();
        const correo = String(data.get('correo') || '').trim();
        const telefono = String(data.get('telefono') || '').trim();
        const direccion = String(data.get('direccion') || '').trim();
        const notas = String(data.get('notas') || '').trim();
        const lines = [
            'TecnoMarket - Nuevo pedido',
            '',
            'Cliente:',
            `Nombre: ${nombre}`,
            `Correo: ${correo}`,
            `Telefono: ${telefono}`,
            `Direccion: ${direccion}`,
            '',
            'Productos:',
            ...cart.map((item) => (
                `- ${item.quantity} x ${item.name} (${formatCurrency(item.price)} c/u) = ${formatCurrency(item.price * item.quantity)}`
            )),
            '',
            `Total estimado: ${formatCurrency(cartTotal(cart))}`,
        ];

        if (notas !== '') {
            lines.push('', `Notas: ${notas}`);
        }

        return lines.join('\n');
    }

    function bindCatalogButtons() {
        document.querySelectorAll(selectors.addButton).forEach((button) => {
            button.addEventListener('click', () => {
                addProduct({
                    id: String(button.dataset.productId || ''),
                    name: String(button.dataset.productName || ''),
                    category: String(button.dataset.productCategory || ''),
                    price: Number(button.dataset.productPrice || 0),
                    stock: Number(button.dataset.productStock || 0),
                });
            });
        });
    }

    function bindCheckoutActions() {
        const page = document.querySelector(selectors.checkoutPage);

        if (!page) {
            return;
        }

        page.addEventListener('click', (event) => {
            const target = event.target;

            if (!(target instanceof HTMLElement)) {
                return;
            }

            const item = target.closest('[data-cart-product]');
            const productId = item ? item.getAttribute('data-cart-product') : '';

            if (target.matches('[data-clear-cart]')) {
                clearCart();
                return;
            }

            if (!productId) {
                return;
            }

            const cartItem = getNormalizedCart().find((product) => product.id === productId);

            if (!cartItem) {
                return;
            }

            if (target.matches('[data-remove-cart-item]')) {
                removeProduct(productId);
                return;
            }

            if (target.matches('[data-quantity-action="increase"]')) {
                if (cartItem.quantity >= cartItem.stock) {
                    showToast('No hay más stock disponible para este producto.');
                    return;
                }

                updateQuantity(productId, cartItem.quantity + 1);
            }

            if (target.matches('[data-quantity-action="decrease"]')) {
                if (cartItem.quantity === 1) {
                    removeProduct(productId);
                    return;
                }

                updateQuantity(productId, cartItem.quantity - 1);
            }
        });

        const form = page.querySelector(selectors.checkoutForm);

        if (!form) {
            return;
        }

        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (getNormalizedCart().length === 0) {
                showToast('Agrega productos antes de enviar el pedido.');
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const message = buildWhatsAppMessage(form);
            const url = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank', 'noopener');
            showToast('Pedido preparado en WhatsApp.');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateCartCount();
        bindCatalogButtons();
        bindCheckoutActions();
        renderCheckout();
    });
}());
