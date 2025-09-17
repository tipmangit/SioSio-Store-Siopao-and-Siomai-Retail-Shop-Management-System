document.addEventListener('DOMContentLoaded', () => {
    const cartContainer = document.querySelector('.cart-container');
    if (!cartContainer) return;

    const formatCurrency = (n) => `₱${Number(n).toFixed(2)}`;

    // Recalculate summary (reads DOM values)
    const recalcAndRender = () => {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseFloat(item.dataset.price) || 0;
            const qty = Math.max(0, parseInt(item.querySelector('.qty-input').value) || 0);
            const lineTotal = price * qty;
            item.querySelector('.cart-price').textContent = formatCurrency(lineTotal);
            subtotal += lineTotal;
        });

        const delivery = subtotal > 0 ? 50 : 0;
        const total = subtotal + delivery;

        const subtotalEl = document.getElementById('subtotal');
        const deliveryEl = document.getElementById('delivery');
        const totalEl = document.getElementById('total');
        if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
        if (deliveryEl) deliveryEl.textContent = formatCurrency(delivery);
        if (totalEl) totalEl.textContent = formatCurrency(total);
    };

    // Send update to server and return parsed JSON
    const updateCartServer = (cart_id, quantity, remove = false) => {
        return fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `cart_id=${encodeURIComponent(cart_id)}&quantity=${encodeURIComponent(quantity)}&remove=${remove ? 1 : 0}`
        })
        .then(r => r.json())
        .catch(err => {
            console.error('updateCartServer error', err);
            return null;
        });
    };

    // Delegated click handler for + / - / remove
    cartContainer.addEventListener('click', async (e) => {
        const plusBtn = e.target.closest('.qty-btn.plus');
        const minusBtn = e.target.closest('.qty-btn.minus');
        const removeBtn = e.target.closest('.remove-btn');

        if (plusBtn || minusBtn) {
            const item = (plusBtn || minusBtn).closest('.cart-item');
            const input = item.querySelector('.qty-input');
            let qty = Math.max(1, parseInt(input.value) || 1);

            if (plusBtn) qty++;
            if (minusBtn) qty = Math.max(1, qty - 1);

            input.value = qty;

            // update DOM line total immediately
            const price = parseFloat(item.dataset.price) || 0;
            item.querySelector('.cart-price').textContent = formatCurrency(price * qty);

            // inform server
            const cart_id = item.dataset.cartId;
            const res = await updateCartServer(cart_id, qty, false);
            if (res && typeof res.cartCount !== 'undefined') {
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = res.cartCount;
            }

            recalcAndRender();
            return;
        }

        if (removeBtn) {
            const item = removeBtn.closest('.cart-item');
            const cart_id = item.dataset.cartId;

            // inform server to remove
            const res = await updateCartServer(cart_id, 0, true);
            if (res && typeof res.cartCount !== 'undefined') {
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = res.cartCount;
            }

            // remove from DOM and recalc
            item.remove();
            recalcAndRender();
            return;
        }
    });

    // Delegated change handler for qty inputs (typing)
    cartContainer.addEventListener('change', async (e) => {
        const input = e.target.closest('.qty-input');
        if (!input) return;

        const item = input.closest('.cart-item');
        let qty = Math.max(1, parseInt(input.value) || 1);
        input.value = qty;

        const price = parseFloat(item.dataset.price) || 0;
        item.querySelector('.cart-price').textContent = formatCurrency(price * qty);

        const cart_id = item.dataset.cartId;
        const res = await updateCartServer(cart_id, qty, false);
        if (res && typeof res.cartCount !== 'undefined') {
            const cartCountEl = document.getElementById('cart-count');
            if (cartCountEl) cartCountEl.textContent = res.cartCount;
        }

        recalcAndRender();
    });

    // initial render
    recalcAndRender();
});
