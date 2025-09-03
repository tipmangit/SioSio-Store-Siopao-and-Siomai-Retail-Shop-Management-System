// Cart functionality
let cart = JSON.parse(localStorage.getItem('siosio-cart')) || [];

// Fix header positioning issues
function fixHeaderPosition() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.style.position = 'fixed';
        navbar.style.top = '0';
        navbar.style.left = '0';
        navbar.style.right = '0';
        navbar.style.zIndex = '1000';
        navbar.style.width = '100%';
    }
    
    const navContainer = document.querySelector('.nav-container');
    if (navContainer) {
        navContainer.style.display = 'flex';
        navContainer.style.justifyContent = 'space-between';
        navContainer.style.alignItems = 'center';
        navContainer.style.position = 'relative';
    }
    
    const navCenter = document.querySelector('.nav-center');
    if (navCenter) {
        navCenter.style.position = 'absolute';
        navCenter.style.left = '50%';
        navCenter.style.top = '50%';
        navCenter.style.transform = 'translate(-50%, -50%)';
        navCenter.style.zIndex = '10';
        navCenter.style.whiteSpace = 'nowrap';
    }
    
    const navLeft = document.querySelector('.nav-left');
    if (navLeft) {
        navLeft.style.flex = '1';
        navLeft.style.display = 'flex';
        navLeft.style.justifyContent = 'flex-start';
    }
    
    const navRight = document.querySelector('.nav-right');
    if (navRight) {
        navRight.style.flex = '1';
        navRight.style.display = 'flex';
        navRight.style.justifyContent = 'flex-end';
    }
}

// Add to cart function
function addToCart(productName, price) {
    const existingItem = cart.find(item => item.name === productName);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: productName,
            price: parseFloat(price),
            quantity: 1
        });
    }
    
    localStorage.setItem('siosio-cart', JSON.stringify(cart));
    updateCartDisplay();
    showCartNotification(productName);
}

// Update cart display (cart icon badge)
function updateCartDisplay() {
    const cartLink = document.querySelector('.cart-link');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    // Remove existing badge
    const existingBadge = cartLink.querySelector('.cart-badge');
    if (existingBadge) {
        existingBadge.remove();
    }
    
    // Add new badge if there are items
    if (totalItems > 0) {
        const badge = document.createElement('span');
        badge.className = 'cart-badge';
        badge.textContent = totalItems;
        badge.style.cssText = `
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        `;
        cartLink.style.position = 'relative';
        cartLink.appendChild(badge);
    }
}

// Show cart notification
function showCartNotification(productName) {
    // Remove existing notification
    const existingNotification = document.querySelector('.cart-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <strong>Added to Cart!</strong><br>
        ${productName}
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Fix header positioning immediately
    fixHeaderPosition();
    
    // Add click event to all "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productName = this.getAttribute('data-product');
            const price = this.getAttribute('data-price');
            
            // Add loading state
            const originalText = this.textContent;
            this.textContent = 'Adding...';
            this.disabled = true;
            
            // Simulate loading time
            setTimeout(() => {
                addToCart(productName, price);
                this.textContent = originalText;
                this.disabled = false;
            }, 500);
        });
    });
    
    // Update cart display on page load
    updateCartDisplay();
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Cart utility functions
function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

function getCartItemCount() {
    return cart.reduce((count, item) => count + item.quantity, 0);
}

function clearCart() {
    cart = [];
    localStorage.removeItem('siosio-cart');
    updateCartDisplay();
}

// Export cart data for other pages
window.SioSioCart = {
    items: cart,
    add: addToCart,
    total: getCartTotal,
    count: getCartItemCount,
    clear: clearCart
};
