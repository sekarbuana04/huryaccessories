// Product Modal Functionality for Catalog Page
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('productModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalPrice = document.getElementById('modalPrice');
    const modalDescription = document.getElementById('modalDescription');
    const modalClose = document.querySelector('.modal-close');
    const viewButtons = document.querySelectorAll('.view-product');
    
    // Get products data from global variable (set by PHP)
    const products = window.catalogProducts || [];
    
    // Open modal when view button is clicked
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = parseInt(this.getAttribute('data-id'));
            const product = products.find(p => p.id === productId);
            
            if (product) {
                modalImage.src = product.image;
                modalImage.alt = product.name;
                modalTitle.textContent = product.name;
                modalPrice.textContent = product.price;
                modalDescription.textContent = product.description;
                
                // Show modal
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
        });
    });
    
    // Close modal when close button is clicked
    modalClose.addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto'; // Enable scrolling
    });
    
    // Close modal when clicking outside the content
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto'; // Enable scrolling
        }
    });
});