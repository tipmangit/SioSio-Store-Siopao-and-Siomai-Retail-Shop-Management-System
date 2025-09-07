// Essential sorting function
function sortProductsByPrice(sectionId, order) {
    const section = document.getElementById(sectionId);
    if (!section) return false;
    const grid = section.querySelector('.flavors-grid');
    if (!grid) return false;
    const items = Array.from(grid.querySelectorAll('.product-item'));
    if (items.length === 0) return false;
    items.sort((a, b) => {
        const priceA = parseFloat(a.querySelector('.product-price').textContent.replace(/[₱\s,]/g, '')) || 0;
        const priceB = parseFloat(b.querySelector('.product-price').textContent.replace(/[₱\s,]/g, '')) || 0;
        return order === 'min-max' ? priceA - priceB : priceB - priceA;
    });
    grid.innerHTML = '';
    items.forEach(item => grid.appendChild(item));
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
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
        sortSelect.addEventListener('change', function() { sortBtn.click(); });
    }
});
