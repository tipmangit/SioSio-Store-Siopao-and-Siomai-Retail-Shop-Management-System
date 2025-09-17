// Sorting function (unchanged)
function sortProductsByPrice(sectionId, order) {
    const section = document.getElementById(sectionId);
    if (!section) return false;
    const grid = section.querySelector('.row.g-4');
    if (!grid) return false;
    const items = Array.from(grid.querySelectorAll('.col-lg-4'));
    if (items.length === 0) return false;

    items.sort((a, b) => {
        const priceElementA = a.querySelector('.text-primary.fw-bold');
        const priceElementB = b.querySelector('.text-primary.fw-bold');
        const priceA = priceElementA ? parseFloat(priceElementA.textContent.replace(/[₱\s,]/g, '')) || 0 : 0;
        const priceB = priceElementB ? parseFloat(priceElementB.textContent.replace(/[₱\s,]/g, '')) || 0 : 0;
        return order === 'min-max' ? priceA - priceB : priceB - priceA;
    });

    grid.innerHTML = '';
    items.forEach(item => grid.appendChild(item));
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    // Sort controls (if present)
    const sortBtn = document.getElementById('sort-price-btn');
    const sortSelect = document.getElementById('price-sort');
    if (sortBtn && sortSelect) {
        sortBtn.addEventListener('click', function() {
            const order = sortSelect.value;
            const originalText = sortBtn.textContent;
            sortBtn.textContent = 'Sorting...';
            sortBtn.disabled = true;
            setTimeout(() => {
                sortProductsByPrice('siomai-section', order);
                sortProductsByPrice('siopao-section', order);
                sortBtn.textContent = originalText;
                sortBtn.disabled = false;
            }, 300);
        });
    }

    // --- Add to cart: SINGLE reliable handler ---
    const buttons = document.querySelectorAll(".add-to-cart-btn");
    if (!buttons || buttons.length === 0) return;

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const product = this.getAttribute("data-product");
            const price = this.getAttribute("data-price");

            // quick UI feedback (optional) - increment visually while waiting:
            const cartCountEl = document.getElementById("cart-count");
            const previousCount = cartCountEl ? parseInt(cartCountEl.textContent || '0') : 0;
            // don't overwrite with invalid value; wait for server response to be authoritative

            console.log("Adding product:", product, "Price:", price);

            fetch("../cart/add_to_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `product=${encodeURIComponent(product)}&price=${encodeURIComponent(price)}`
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error("Network response was not OK: " + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log("add_to_cart response:", data);
                // robustly pick whichever key exists
                const newCount = (typeof data.cartCount !== 'undefined') ? data.cartCount
                                : (typeof data.cart_count !== 'undefined') ? data.cart_count
                                : previousCount + 1;

                const el = document.getElementById("cart-count");
                if (el) el.textContent = newCount;
            })
            .catch(err => {
                console.error("Add to cart failed:", err);
                // optionally restore prior UI if you pre-incremented it
            });
        });
    });
});
