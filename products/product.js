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
    // Create notification container
    createNotificationContainer();
    
    // Initialize favorites state
    initializeFavorites();
    
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

    // --- Favorites functionality ---
    const favoriteButtons = document.querySelectorAll(".favorite-btn");
    favoriteButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Check if user is logged in
            if (!window.isLoggedIn) {
                showNotification('Please log in to add favorites!', 'error');
                return;
            }
            
            const productId = this.getAttribute("data-product-id");
            const productName = this.getAttribute("data-product");
            const heartIcon = this.querySelector('i');
            const isFavorited = heartIcon.classList.contains('bi-heart-fill');
            
            // Show loading state
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="bi bi-arrow-repeat spin"></i>';
            this.disabled = true;
            
            const action = isFavorited ? 'remove' : 'add';
            
            fetch("../favorites/favorites_api.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=${action}&product_id=${encodeURIComponent(productId)}`
            })
            .then(res => res.json())
            .then(data => {
                console.log("Favorites response:", data);
                if (data.success) {
                    if (action === 'add') {
                        heartIcon.classList.remove('bi-heart');
                        heartIcon.classList.add('bi-heart-fill');
                        this.classList.remove('btn-outline-danger');
                        this.classList.add('btn-danger');
                        showNotification(`${productName} added to favorites!`, 'success');
                    } else {
                        heartIcon.classList.remove('bi-heart-fill');
                        heartIcon.classList.add('bi-heart');
                        this.classList.remove('btn-danger');
                        this.classList.add('btn-outline-danger');
                        showNotification(`${productName} removed from favorites!`, 'info');
                    }
                } else {
                    showNotification('Failed to update favorites. Please try again.', 'error');
                }
                
                // Restore button state
                this.innerHTML = originalHtml;
                this.disabled = false;
            })
            .catch(err => {
                console.error("Favorites error:", err);
                showNotification('Something went wrong. Please try again.', 'error');
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });

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

// Notification System
function createNotificationContainer() {
    if (document.getElementById('notification-container')) return;
    
    const container = document.createElement('div');
    container.id = 'notification-container';
    container.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        max-width: 300px;
    `;
    document.body.appendChild(container);
}

function showNotification(message, type = 'success') {
    const container = document.getElementById('notification-container');
    if (!container) return;
    
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show mb-2`;
    notification.style.cssText = `
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        border-radius: 8px;
    `;
    
    const icon = type === 'success' ? 'bi-check-circle' : type === 'error' ? 'bi-x-circle' : 'bi-info-circle';
    
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi ${icon} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 150);
        }
    }, 4000);
}

// Initialize favorites state based on server data
function initializeFavorites() {
    // Get favorites from PHP (already loaded in page)
    const favoriteButtons = document.querySelectorAll(".favorite-btn");
    
    favoriteButtons.forEach(button => {
        const productId = parseInt(button.getAttribute("data-product-id"));
        const heartIcon = button.querySelector('i');
        
        // Check if this product is in favorites (from PHP data)
        if (window.userFavorites && window.userFavorites.includes(productId)) {
            heartIcon.classList.remove('bi-heart');
            heartIcon.classList.add('bi-heart-fill');
            button.classList.remove('btn-outline-danger');
            button.classList.add('btn-danger');
        }
    });
}

// Add spinning animation CSS
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
