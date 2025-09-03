// Master Siomai Website JavaScript

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

document.addEventListener('DOMContentLoaded', function() {
    // Fix header layout on page load
    fixHeaderPosition();
    
    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('.nav-link[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(0, 0, 0, 0.95)';
        } else {
            navbar.style.background = 'rgba(0, 0, 0, 0.8)';
        }
    });

    // Mobile menu toggle (if needed for future expansion)
    const createMobileMenu = () => {
        const navContainer = document.querySelector('.nav-container');
        const mobileMenuBtn = document.createElement('button');
        mobileMenuBtn.className = 'mobile-menu-btn';
        mobileMenuBtn.innerHTML = '☰';
        mobileMenuBtn.style.display = 'none';
        
        // Add mobile styles
        const style = document.createElement('style');
        style.textContent = `
            @media (max-width: 768px) {
                .mobile-menu-btn {
                    display: block !important;
                    background: none;
                    border: none;
                    color: white;
                    font-size: 1.5rem;
                    cursor: pointer;
                }
            }
        `;
        document.head.appendChild(style);
        
        navContainer.appendChild(mobileMenuBtn);
    };

    // Initialize mobile menu
    createMobileMenu();

    // Add animation to hero elements
    const heroElements = document.querySelectorAll('.hero-title, .hero-subtitle, .hero-tagline');
    
    heroElements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 500 + (index * 200));
    });

    // Dropdown menu functionality
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        
        dropdown.addEventListener('mouseenter', () => {
            menu.style.opacity = '1';
            menu.style.visibility = 'visible';
            menu.style.transform = 'translateY(0)';
        });
        
        dropdown.addEventListener('mouseleave', () => {
            menu.style.opacity = '0';
            menu.style.visibility = 'hidden';
            menu.style.transform = 'translateY(-10px)';
        });
    });

    // Update cart counter on page load
    updateCartCounter();
});

// Cart functionality for homepage
let cart = JSON.parse(localStorage.getItem('siosio-cart')) || [];

function updateCartCounter() {
    const cartCounter = document.querySelector('.cart-counter');
    if (cartCounter) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCounter.textContent = totalItems;
        cartCounter.style.display = totalItems > 0 ? 'inline' : 'none';
    }
}

// Ensure header is consistent when navigating back from products
window.addEventListener('focus', function() {
    fixHeaderPosition();
});

// Utility functions
const utils = {
    // Debounce function for scroll events
    debounce: function(func, wait, immediate) {
        let timeout;
        return function executedFunction() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    },

    // Check if element is in viewport
    isInViewport: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
};
