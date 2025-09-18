let currentPage = 1;
let currentLimit = 10;
let isEditing = false;

// Load products
async function loadProducts(page = 1) {
    currentPage = page;
    
    const formData = new FormData();
    formData.append('action', 'get_products');
    formData.append('page', page);
    formData.append('limit', currentLimit);
    formData.append('search', document.getElementById('searchInput').value);
    formData.append('category', document.getElementById('categoryFilter').value);
    formData.append('status', document.getElementById('statusFilter').value);
    formData.append('sort', document.getElementById('sortBy').value);
    formData.append('order', document.getElementById('sortOrder').value);
    
    try {
        const response = await fetch('catalog_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            displayProducts(result.data);
            displayPagination(result.pagination);
        } else {
            console.error('Error loading products:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function displayProducts(products) {
    const tbody = document.getElementById('productsTableBody');
    
    if (products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" class="loading">Tidak ada produk ditemukan</td></tr>';
        return;
    }
    
    tbody.innerHTML = products.map(product => `
        <tr>
            <td><input type="checkbox" class="product-checkbox" value="${product.id}"></td>
            <td>
                ${product.image ? 
                    `<img src="../assets/images/${product.image}" alt="${product.name}" class="product-image">` : 
                    '<div class="product-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image"></i></div>'
                }
            </td>
            <td class="product-name">${product.name}</td>
            <td>${product.category}</td>
            <td class="product-price">Rp ${parseInt(product.price).toLocaleString('id-ID')}</td>
            <td>${product.stock_quantity}</td>
            <td>
                <span class="status-badge ${product.is_active == 1 ? 'status-active' : 'status-inactive'}">
                    ${product.is_active == 1 ? 'Aktif' : 'Tidak Aktif'}
                </span>
            </td>
            <td>
                ${product.is_featured == 1 ? '<span class="status-badge status-featured">Unggulan</span>' : '-'}
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn btn-secondary btn-sm" onclick="editProduct(${product.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="toggleFeatured(${product.id})">
                        <i class="fas fa-star"></i>
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="toggleActive(${product.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function displayPagination(pagination) {
    const container = document.getElementById('pagination');
    const { page, pages, total } = pagination;
    
    let html = `<span>Total: ${total} produk</span>`;
    
    if (pages > 1) {
        html += `
            <button ${page <= 1 ? 'disabled' : ''} onclick="loadProducts(${page - 1})">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
        
        for (let i = Math.max(1, page - 2); i <= Math.min(pages, page + 2); i++) {
            html += `
                <button class="${i === page ? 'active' : ''}" onclick="loadProducts(${i})">
                    ${i}
                </button>
            `;
        }
        
        html += `
            <button ${page >= pages ? 'disabled' : ''} onclick="loadProducts(${page + 1})">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }
    
    container.innerHTML = html;
}

// Modal functions
function openAddModal() {
    isEditing = false;
    document.getElementById('modalTitle').textContent = 'Tambah Produk';
    document.getElementById('productForm').reset();
    document.getElementById('productId').value = '';
    document.getElementById('productActive').checked = true;
    document.getElementById('productModal').classList.add('show');
}

async function editProduct(id) {
    isEditing = true;
    document.getElementById('modalTitle').textContent = 'Edit Produk';
    
    try {
        const formData = new FormData();
        formData.append('action', 'get_product');
        formData.append('id', id);
        
        const response = await fetch('catalog_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const product = result.data;
            document.getElementById('productId').value = product.id;
            document.getElementById('productName').value = product.name;
            document.getElementById('productDescription').value = product.description;
            document.getElementById('productCategory').value = product.category;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productStock').value = product.stock_quantity;
            document.getElementById('productFeatured').checked = product.is_featured == 1;
            document.getElementById('productActive').checked = product.is_active == 1;
            document.getElementById('productModal').classList.add('show');
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data produk');
    }
}

function closeModal() {
    document.getElementById('productModal').classList.remove('show');
}

// Form submission
document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', isEditing ? 'update_product' : 'add_product');
    
    try {
        const response = await fetch('catalog_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            closeModal();
            loadProducts(currentPage);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan produk');
    }
});

// Product actions
async function toggleFeatured(id) {
    if (confirm('Ubah status unggulan produk ini?')) {
        await performAction('toggle_featured', { id });
    }
}

async function toggleActive(id) {
    if (confirm('Ubah status aktif produk ini?')) {
        await performAction('toggle_active', { id });
    }
}

async function deleteProduct(id) {
    if (confirm('Hapus produk ini? Tindakan ini tidak dapat dibatalkan.')) {
        await performAction('delete_product', { id });
    }
}

async function performAction(action, data) {
    try {
        const formData = new FormData();
        formData.append('action', action);
        Object.keys(data).forEach(key => {
            formData.append(key, data[key]);
        });
        
        const response = await fetch('catalog_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadProducts(currentPage);
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
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    
    if (!action) {
        alert('Pilih aksi yang akan dilakukan');
        return;
    }
    
    if (checkboxes.length === 0) {
        alert('Pilih minimal satu produk');
        return;
    }
    
    const ids = Array.from(checkboxes).map(cb => cb.value);
    
    if (confirm(`Terapkan aksi "${action}" pada ${ids.length} produk yang dipilih?`)) {
        performBulkAction(action, ids);
    }
}

async function performBulkAction(action, ids) {
    try {
        const formData = new FormData();
        formData.append('action', 'bulk_action');
        formData.append('bulk_action', action);
        formData.append('ids', JSON.stringify(ids));
        
        const response = await fetch('catalog_control.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadProducts(currentPage);
            document.getElementById('selectAll').checked = false;
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Search and filter functionality
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadProducts(1);
    }, 500);
});

document.getElementById('categoryFilter').addEventListener('change', () => loadProducts(1));
document.getElementById('statusFilter').addEventListener('change', () => loadProducts(1));
document.getElementById('sortBy').addEventListener('change', () => loadProducts(1));
document.getElementById('sortOrder').addEventListener('change', () => loadProducts(1));

function refreshProducts() {
    loadProducts(currentPage);
}

// Close modal when clicking outside
document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadProducts(1);
});