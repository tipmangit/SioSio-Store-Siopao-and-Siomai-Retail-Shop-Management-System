document.addEventListener('DOMContentLoaded', () => {
  console.log('favorites.js loaded');

  // Handle Add to Cart buttons
  document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const productId = this.dataset.productId;
      const originalText = this.innerHTML;
      
      // Show loading state
      this.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Adding...';
      this.disabled = true;
      
      fetch('../cart/add_to_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${encodeURIComponent(productId)}&quantity=1`
      })
      .then(res => res.json())
      .then(data => {
        console.log('Add to cart response:', data); // Debug logging
        if (data.success) {
          // Show success state
          this.innerHTML = '<i class="bi bi-check"></i> Added!';
          this.className = 'btn btn-success';
          
          // Update cart count if element exists
          const cartCount = document.getElementById('cart-count');
          if (cartCount && data.cart_count) {
            cartCount.textContent = data.cart_count;
          }
          
          // Reset after 2 seconds
          setTimeout(() => {
            this.innerHTML = originalText;
            this.className = 'btn btn-primary add-to-cart-btn';
            this.disabled = false;
          }, 2000);
        } else {
          console.error('Add to cart failed:', data.message);
          this.innerHTML = '<i class="bi bi-x"></i> Error';
          this.className = 'btn btn-danger';
          setTimeout(() => {
            this.innerHTML = originalText;
            this.className = 'btn btn-primary add-to-cart-btn';
            this.disabled = false;
          }, 2000);
        }
      })
      .catch(err => {
        console.error('Add to cart error:', err);
        this.innerHTML = '<i class="bi bi-x"></i> Error';
        this.className = 'btn btn-danger';
        setTimeout(() => {
          this.innerHTML = originalText;
          this.className = 'btn btn-primary add-to-cart-btn';
          this.disabled = false;
        }, 2000);
      });
    });
  });

  // Handle Remove from Favorites buttons
  document.querySelectorAll('.remove-favorite-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const productId = this.dataset.productId;
      const card = this.closest('.col-lg-4, .col-md-6');
      const originalText = this.innerHTML;
      
      // Show loading state
      this.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Removing...';
      this.disabled = true;
      
      fetch('../favorites/favorites_api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=remove&product_id=${encodeURIComponent(productId)}`
      })
      .then(res => res.json())
      .then(data => {
        console.log('Remove response:', data); // Debug logging
        if (data.success) {
          // Animate card removal
          card.style.transform = 'scale(0.8)';
          card.style.opacity = '0';
          card.style.transition = 'all 0.3s ease';
          
          setTimeout(() => {
            card.remove();
            
            // Check if no favorites left
            if (document.querySelectorAll('.product-card').length === 0) {
              location.reload(); // Reload to show empty state
            }
          }, 300);
        } else {
          console.error('Remove failed:', data.message);
          this.innerHTML = '<i class="bi bi-x"></i> Error';
          this.className = 'btn btn-outline-danger btn-sm remove-favorite-btn';
          setTimeout(() => {
            this.innerHTML = originalText;
            this.className = 'btn btn-danger btn-sm remove-favorite-btn';
            this.disabled = false;
          }, 2000);
        }
      })
      .catch(err => {
        console.error('Remove favorite error:', err);
        this.innerHTML = '<i class="bi bi-x"></i> Error';
        setTimeout(() => {
          this.innerHTML = originalText;
          this.disabled = false;
        }, 2000);
      });
    });
  });

  // Add spinning animation for loading states
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
});
