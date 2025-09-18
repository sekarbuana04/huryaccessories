// Gallery JavaScript

// Initialize gallery when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeGallery();
});

function initializeGallery() {
    // Add loading animation to images
    const images = document.querySelectorAll('.gallery-image img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Set initial opacity for loading effect
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
    
    // Add hover effects to gallery items
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initialize modal functionality
    initializeModal();
    
    // Add keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
}

function initializeModal() {
    const modal = document.getElementById('galleryModal');
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Prevent modal content clicks from closing modal
    const modalContent = modal.querySelector('.modal-content');
    modalContent.addEventListener('click', function(event) {
        event.stopPropagation();
    });
}

function handleKeyboardNavigation(event) {
    const modal = document.getElementById('galleryModal');
    
    // Close modal with Escape key
    if (event.key === 'Escape' && modal.style.display === 'block') {
        closeModal();
    }
}

// Modal functions (these are called from the PHP template)
function openModal(id) {
    const modal = document.getElementById('galleryModal');
    const item = galleryData.find(item => item.id == id);
    
    if (item) {
        // Update modal content
        document.getElementById('modalImage').src = item.image;
        document.getElementById('modalImage').alt = item.title;
        document.getElementById('modalTitle').textContent = item.title;
        document.getElementById('modalSubtitle').textContent = item.subtitle || '';
        document.getElementById('modalDescription').textContent = item.description || '';
        
        // Show modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
        
        // Add animation class
        modal.classList.add('modal-open');
        
        // Focus on modal for accessibility
        modal.focus();
    }
}

function closeModal() {
    const modal = document.getElementById('galleryModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
    modal.classList.remove('modal-open');
}

// Lazy loading for images
function initializeLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => imageObserver.observe(img));
    }
}

// Filter functionality (if needed in the future)
function filterGallery(category) {
    const items = document.querySelectorAll('.gallery-item');
    
    items.forEach(item => {
        const itemCategory = item.dataset.category;
        
        if (category === 'all' || itemCategory === category) {
            item.style.display = 'block';
            item.style.animation = 'fadeIn 0.5s ease';
        } else {
            item.style.display = 'none';
        }
    });
}

// Search functionality
function searchGallery(searchTerm) {
    const items = document.querySelectorAll('.gallery-item');
    const term = searchTerm.toLowerCase();
    
    items.forEach(item => {
        const title = item.querySelector('h3').textContent.toLowerCase();
        const subtitle = item.querySelector('.subtitle')?.textContent.toLowerCase() || '';
        
        if (title.includes(term) || subtitle.includes(term)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Utility function to show loading state
function showLoading() {
    const galleryGrid = document.querySelector('.gallery-grid');
    if (galleryGrid) {
        galleryGrid.innerHTML = `
            <div class="loading-state">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat galeri...</p>
            </div>
        `;
    }
}

// Utility function to show error state
function showError(message) {
    const galleryGrid = document.querySelector('.gallery-grid');
    if (galleryGrid) {
        galleryGrid.innerHTML = `
            <div class="error-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Terjadi Kesalahan</h3>
                <p>${message}</p>
                <button onclick="location.reload()" class="btn-retry">
                    <i class="fas fa-redo"></i> Coba Lagi
                </button>
            </div>
        `;
    }
}

// Add smooth scrolling for anchor links
function initializeSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Initialize all features when page loads
window.addEventListener('load', function() {
    initializeLazyLoading();
    initializeSmoothScrolling();
});

// Export functions for global use
window.openModal = openModal;
window.closeModal = closeModal;
window.filterGallery = filterGallery;
window.searchGallery = searchGallery;