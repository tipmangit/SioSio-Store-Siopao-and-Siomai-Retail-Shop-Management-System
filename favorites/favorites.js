document.addEventListener('DOMContentLoaded', () => {
  console.log('favorites.js loaded');

  // Fetch saved favorites on page load
  fetch('../favorites/favorites_api.php')
    .then(res => res.json())
    .then(data => {
      const userFavorites = data.favorites || [];

      // Highlight hearts that are already favorites
      document.querySelectorAll('.favorite-btn').forEach(btn => {
        const productId = parseInt(btn.dataset.productId);
        if (userFavorites.includes(productId)) {
          btn.classList.add('active');
        }

        // Attach click handler for each button
        btn.addEventListener('click', () => {
          btn.classList.toggle('active');
          const productId = btn.dataset.productId;
          const action = btn.classList.contains('active') ? 'add' : 'remove';

          fetch('../favorites/favorites_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=${encodeURIComponent(action)}&product_id=${encodeURIComponent(productId)}`
          })
          .then(res => res.json())
          .then(data => console.log(data.message))
          .catch(err => {
            console.error(err);
            btn.classList.toggle('active'); // revert on failure
          });
        });
      });
    })
    .catch(err => console.error('Failed to load favorites:', err));
});
