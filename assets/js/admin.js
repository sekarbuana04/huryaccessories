// Admin Dashboard JavaScript
class AdminDashboard {
    constructor() {
        this.products = [];
        this.currentEditId = null;
        this.init();
    }

    init() {
        this.bindEvents();
        this.bindPriceFormatting();
        this.loadProducts();
        this.updateStats();
    }

    bindEvents() {
        // Product form submission
        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleProductSubmit();
            });
        }

        // Modal close events
        const productModal = document.getElementById('productModal');
        if (productModal) {
            productModal.addEventListener('click', (e) => {
                if (e.target.id === 'productModal') {
                    this.closeProductModal();
                }
            });
        }

        // User menu toggle
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.admin-user')) {
                this.closeUserMenu();
            }
        });
    }

    // Other methods would go here...
    loadProducts() {
        // Placeholder for load products functionality
    }

    updateStats() {
        // Placeholder for update stats functionality
    }

    bindPriceFormatting() {
        // Placeholder for price formatting
    }

    handleProductSubmit() {
        // Placeholder for product submit
    }

    closeProductModal() {
        // Placeholder for close modal
    }

    closeUserMenu() {
        // Placeholder for close user menu
    }
}

// Sidebar Navigation Class
class SidebarNavigation {
    constructor() {
        this.init();
    }
    
    init() {
        this.bindNavigation();
    }
    
    bindNavigation() {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = link.getAttribute('data-section');
                this.showSection(target);
                this.setActiveNav(link);
            });
        });
    }
    
    showSection(sectionId) {
        // Hide all sections immediately without any transition
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
            section.style.opacity = '0';
            section.style.visibility = 'hidden';
            section.style.transition = 'none';
        });
        
        // Show target section immediately without smooth scroll
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.style.transition = 'none';
            targetSection.style.display = 'block';
            targetSection.style.opacity = '1';
            targetSection.style.visibility = 'visible';
            targetSection.classList.add('active');
            
            // Refresh iframe to ensure proper loading
            const iframe = targetSection.querySelector('iframe');
            if (iframe) {
                const src = iframe.src;
                iframe.src = '';
                setTimeout(() => {
                    iframe.src = src;
                }, 50);
            }
        }
    }
    
    setActiveNav(activeLink) {
        // Remove active class from all nav items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to current nav item
        activeLink.parentElement.classList.add('active');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar navigation
    const sidebarNav = new SidebarNavigation();
    
    // Initialize dashboard if on dashboard page
    if (document.getElementById('productForm')) {
        const dashboard = new AdminDashboard();
    }
});

// Global functions
function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        userMenu.classList.toggle('show');
    }
}

function showProfile() {
    // Create and show profile modal
    const profileModal = createProfileModal();
    document.body.appendChild(profileModal);
    profileModal.style.display = 'flex';
}

function showSettings() {
    // Create and show settings modal
    const settingsModal = createSettingsModal();
    document.body.appendChild(settingsModal);
    settingsModal.style.display = 'flex';
}

function createProfileModal() {
    const modal = document.createElement('div');
    modal.className = 'profile-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user"></i> Profil Admin</h3>
                <button class="close-btn" onclick="closeModal(this)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="profile-info">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="profile-details">
                        <h4>Administrator</h4>
                        <p>Username: admin</p>
                        <p>Role: Super Admin</p>
                        <p>Last Login: ${new Date().toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn btn-primary" onclick="editProfile()">Edit Profil</button>
                    <button class="btn btn-secondary" onclick="changePassword()">Ubah Password</button>
                </div>
            </div>
        </div>
    `;
    return modal;
}

function createSettingsModal() {
    const modal = document.createElement('div');
    modal.className = 'settings-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-cog"></i> Pengaturan</h3>
                <button class="close-btn" onclick="closeModal(this)">&times;</button>
            </div>
            <div class="modal-body">
                <div class="settings-section">
                    <h4>Pengaturan Umum</h4>
                    <div class="setting-item">
                        <label>Tema</label>
                        <select id="themeSelect">
                            <option value="Light">Light</option>
                            <option value="Dark">Dark</option>
                        </select>
                    </div>
                    <div class="setting-item">
                        <label>Bahasa</label>
                        <select id="languageSelect">
                            <option value="Indonesia">Indonesia</option>
                            <option value="English">English</option>
                        </select>
                    </div>
                </div>
                <div class="settings-actions">
                    <button class="btn btn-primary" onclick="saveSettings()">Simpan</button>
                    <button class="btn btn-secondary" onclick="closeModal(this)">Batal</button>
                </div>
            </div>
        </div>
    `;
    
    // Load current settings after modal is created
    setTimeout(() => {
        const savedTheme = localStorage.getItem('theme') || 'Light';
        const savedLanguage = localStorage.getItem('language') || 'Indonesia';
        
        const themeSelect = document.getElementById('themeSelect');
        const languageSelect = document.getElementById('languageSelect');
        
        if (themeSelect) themeSelect.value = savedTheme;
        if (languageSelect) languageSelect.value = savedLanguage;
    }, 10);
    
    return modal;
}

function closeModal(element) {
    const modal = element.closest('.profile-modal, .settings-modal');
    if (modal) {
        modal.remove();
    }
}

function editProfile() {
    // Close the profile modal first
    const modal = document.querySelector('.profile-modal');
    if (modal) modal.remove();
    
    // Redirect to edit profile page
    window.location.href = 'edit_profile_only.php';
}

function changePassword() {
    // Close the profile modal first
    const modal = document.querySelector('.profile-modal');
    if (modal) modal.remove();
    
    // Redirect to change password page
    window.location.href = 'change_password.php';
}

function saveSettings() {
    const themeSelect = document.getElementById('themeSelect');
    const languageSelect = document.getElementById('languageSelect');
    
    const selectedTheme = themeSelect.value;
    const selectedLanguage = languageSelect.value;
    
    // Apply theme
    applyTheme(selectedTheme);
    
    // Apply language
    applyLanguage(selectedLanguage);
    
    // Save to localStorage
    localStorage.setItem('theme', selectedTheme);
    localStorage.setItem('language', selectedLanguage);
    
    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Pengaturan berhasil disimpan',
        timer: 2000,
        showConfirmButton: false
    });
    
    if (modal) modal.remove();
}

function applyTheme(theme) {
    const body = document.body;
    if (theme === 'Dark') {
        body.classList.add('dark-theme');
    } else {
        body.classList.remove('dark-theme');
    }
}

function applyLanguage(language) {
    // Store language preference
    document.documentElement.lang = language === 'English' ? 'en' : 'id';
    
    // You can add more language switching logic here
    // For now, we'll just store the preference
}

function loadSettings() {
    // Load saved settings on page load
    const savedTheme = localStorage.getItem('theme') || 'Light';
    const savedLanguage = localStorage.getItem('language') || 'Indonesia';
    
    applyTheme(savedTheme);
    applyLanguage(savedLanguage);
}

function showNotification(type, title, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-header">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
                <strong>${title}</strong>
            </div>
            <div class="notification-message">${message}</div>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add styles if not exists
    if (!document.querySelector('#notification-styles')) {
        const styles = document.createElement('style');
        styles.id = 'notification-styles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                padding: 16px;
                max-width: 400px;
                z-index: 10001;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid;
            }
            .notification-success { border-left-color: #28a745; }
            .notification-warning { border-left-color: #ffc107; }
            .notification-header {
                display: flex;
                align-items: center;
                margin-bottom: 8px;
                font-weight: 600;
            }
            .notification-header i {
                margin-right: 8px;
                color: inherit;
            }
            .notification-success .notification-header { color: #28a745; }
            .notification-warning .notification-header { color: #ffc107; }
            .notification-message {
                color: #666;
                font-size: 14px;
            }
            .notification-close {
                position: absolute;
                top: 8px;
                right: 8px;
                background: none;
                border: none;
                font-size: 18px;
                cursor: pointer;
                color: #999;
            }
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

function showLoginError(message) {
    showNotification('warning', 'Login Error', message);
}

function showLoginSuccess(username) {
    showNotification('success', 'Login Berhasil', `Selamat datang, ${username}! Anda berhasil masuk ke sistem.`);
}