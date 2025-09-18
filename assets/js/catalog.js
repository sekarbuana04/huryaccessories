// Catalog products data
let catalogProducts = [];

// Initialize catalog data
function initCatalogData(products) {
    catalogProducts = products;
}

// Product modal functionality
function openProductModal(productId) {
    const product = catalogProducts.find(p => p.id == productId);
    if (product) {
        document.getElementById('modalImage').src = product.image;
        document.getElementById('modalImage').alt = product.name;
        document.getElementById('modalTitle').textContent = product.name;
        document.getElementById('modalPrice').textContent = product.price;
        document.getElementById('modalDescription').innerHTML = product.description;
        document.getElementById('productModal').style.display = 'block';
    }
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize catalog data if available
    if (typeof window.catalogProducts !== 'undefined') {
        initCatalogData(window.catalogProducts);
    }
    
    // Add event listeners for view product buttons
    const viewButtons = document.querySelectorAll('.view-product');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            openProductModal(productId);
        });
    });
    
    // Add event listener for modal close button
    const closeButton = document.querySelector('.modal-close');
    if (closeButton) {
        closeButton.addEventListener('click', closeProductModal);
    }
    
    // Close modal when clicking outside
    const modal = document.getElementById('productModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeProductModal();
            }
        });
    }
});