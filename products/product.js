// Essential sorting function
function sortProductsByPrice(sectionId, order) {
    const section = document.getElementById(sectionId);
    if (!section) return false;
    
    // Look for the Bootstrap row container instead of .flavors-grid
    const grid = section.querySelector('.row.g-4');
    if (!grid) return false;
    
    // Get all product items (the individual column containers)
    const items = Array.from(grid.querySelectorAll('.col-lg-4'));
    if (items.length === 0) return false;
    
    items.sort((a, b) => {
        // Look for the price in the Bootstrap structure: p.text-primary.fw-bold
        const priceElementA = a.querySelector('.text-primary.fw-bold');
        const priceElementB = b.querySelector('.text-primary.fw-bold');
        
        const priceA = priceElementA ? parseFloat(priceElementA.textContent.replace(/[₱\s,]/g, '')) || 0 : 0;
        const priceB = priceElementB ? parseFloat(priceElementB.textContent.replace(/[₱\s,]/g, '')) || 0 : 0;
        
        return order === 'min-max' ? priceA - priceB : priceB - priceA;
    });
    
    // Clear and re-append sorted items
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
        // Removed auto-sort on dropdown change - user must click Sort button
    }
});
