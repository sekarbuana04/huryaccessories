let currentPage = 1;
let currentLimit = 12;
let isEditing = false;

// Load gallery
async function loadGallery(page = 1) {
    currentPage = page;
    
    const formData = new FormData();
    formData.append('action', 'get_gallery');
    formData.append('page', page);
    formData.append('limit', currentLimit);
    formData.append('search', document.getElementById('searchInput').value);
    formData.append('category', document.getElementById('categoryFilter').value);
    formData.append('sort', document.getElementById('sortBy').value);
    formData.append('order', document.getElementById('sortOrder').value);
    
    try {
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayGallery(result.data);
            displayPagination(result.pagination);
        } else {
            console.error('Error loading gallery:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function displayGallery(items) {
    const container = document.getElementById('galleryItems');
    
    if (items.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h3>Belum ada gambar</h3>
                <p>Tambahkan gambar pertama untuk memulai galeri</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = items.map(item => `
        <div class="gallery-item">
            <input type="checkbox" class="item-checkbox" value="${item.id}">
            <img src="../assets/images/gallery/${item.image}" alt="${item.title}" class="item-image" onclick="previewImage('${item.image}', '${item.title}')">
            <div class="item-content">
                <h3 class="item-title">${item.title}</h3>
                ${item.description ? `<p class="item-description">${item.description}</p>` : ''}
                <div class="item-meta">
                    <span class="item-category">${item.category}</span>
                    ${item.is_featured == 1 ? '<span class="featured-badge">Unggulan</span>' : ''}
                </div>
                <div class="item-actions">
                    <button class="btn btn-secondary btn-sm" onclick="editItem(${item.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="toggleFeatured(${item.id})">
                        <i class="fas fa-star"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteItem(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function displayPagination(pagination) {
    const container = document.getElementById('pagination');
    const { page, pages, total } = pagination;
    
    let html = `<span>Total: ${total} gambar</span>`;
    
    if (pages > 1) {
        html += `
            <button ${page <= 1 ? 'disabled' : ''} onclick="loadGallery(${page - 1})">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        for (let i = Math.max(1, page - 2); i <= Math.min(pages, page + 2); i++) {
            html += `
                <button class="${i === page ? 'active' : ''}" onclick="loadGallery(${i})">
                    ${i}
                </button>
            `;
        }
        
        html += `
            <button ${page >= pages ? 'disabled' : ''} onclick="loadGallery(${page + 1})">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }
    
    container.innerHTML = html;
}

// Modal functions
function openAddModal() {
    isEditing = false;
    document.getElementById('modalTitle').textContent = 'Tambah Gambar';
    document.getElementById('itemForm').reset();
    document.getElementById('itemId').value = '';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('itemModal').classList.add('show');
}

async function editItem(id) {
    isEditing = true;
    document.getElementById('modalTitle').textContent = 'Edit Gambar';
    
    try {
        const formData = new FormData();
        formData.append('action', 'get_item');
        formData.append('id', id);
        
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemTitle').value = item.title;
            document.getElementById('itemDescription').value = item.description;
            document.getElementById('itemCategory').value = item.category;
            document.getElementById('itemFeatured').checked = item.is_featured == 1;
            
            // Show current image
            document.getElementById('imagePreview').innerHTML = `
                <img src="../assets/images/gallery/${item.image}" class="image-preview" alt="Current image">
                <p style="font-size: 0.9em; color: #718096; margin-top: 5px;">Gambar saat ini (biarkan kosong jika tidak ingin mengubah)</p>
            `;
            
            document.getElementById('itemModal').classList.add('show');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data item');
    }
}

function closeModal() {
    document.getElementById('itemModal').classList.remove('show');
}

function previewImage(imageName, title) {
    document.getElementById('imageModalTitle').textContent = title;
    document.getElementById('imageModalImg').src = `../assets/images/gallery/${imageName}`;
    document.getElementById('imageModal').classList.add('show');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.remove('show');
}

// Form submission
document.getElementById('itemForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', isEditing ? 'update_item' : 'add_item');
    
    try {
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeModal();
            loadGallery(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
});

// Image preview on file select
document.getElementById('itemImage').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = `
                <img src="${e.target.result}" class="image-preview" alt="Preview">
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Delete item
async function deleteItem(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'delete_item');
        formData.append('id', id);
        
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadGallery(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus item');
    }
}

// Toggle featured
async function toggleFeatured(id) {
    try {
        const formData = new FormData();
        formData.append('action', 'toggle_featured');
        formData.append('id', id);
        
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadGallery(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
}

// Bulk actions
function applyBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    
    if (!action) {
        alert('Pilih aksi yang akan dilakukan');
        return;
    }
    
    if (checkboxes.length === 0) {
        alert('Pilih minimal satu item');
        return;
    }
    
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (confirm(`Terapkan aksi "${action}" pada ${ids.length} item yang dipilih?`)) {
        performBulkAction(action, ids);
    }
}

async function performBulkAction(action, ids) {
    try {
        const formData = new FormData();
        formData.append('action', 'bulk_action');
        formData.append('bulk_action', action);
        formData.append('ids', JSON.stringify(ids));
        
        const response = await fetch('gallery_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadGallery(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
}

// Search and filter functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadGallery(1);
    }, 500);
});

document.getElementById('categoryFilter').addEventListener('change', () => loadGallery(1));
document.getElementById('sortBy').addEventListener('change', () => loadGallery(1));
document.getElementById('sortOrder').addEventListener('change', () => loadGallery(1));

function refreshGallery() {
    loadGallery(currentPage);
}

// Close modals when clicking outside
document.getElementById('itemModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadGallery(1);
});