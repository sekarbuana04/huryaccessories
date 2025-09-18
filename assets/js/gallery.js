// Gallery modal functions
let galleryData = [];

// Initialize gallery data
function initGalleryData(data) {
    galleryData = data;
}

function openModal(id) {
    const item = galleryData.find(item => item.id == id);
    if (item) {
        document.getElementById('modalImage').src = item.image;
        document.getElementById('modalImage').alt = item.title;
        document.getElementById('modalTitle').textContent = item.title;
        document.getElementById('modalSubtitle').textContent = item.subtitle || '';
        document.getElementById('modalDescription').textContent = item.description || '';
        document.getElementById('galleryModal').style.display = 'block';
    }
}

function closeModal() {
    document.getElementById('galleryModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('galleryModal');
    if (event.target == modal) {
        closeModal();
    }
}