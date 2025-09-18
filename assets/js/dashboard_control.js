// Dashboard Control JavaScript

// Load dashboard data from server
async function loadDashboardData() {
    try {
        const response = await fetch('dashboard_control.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get_stats'
        });
        
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            
            // Update statistics
            document.getElementById('totalProducts').textContent = data.products.total_products || 0;
            document.getElementById('featuredProducts').textContent = data.products.featured_products || 0;
            document.getElementById('totalStock').textContent = data.products.total_stock || 0;
            document.getElementById('lowStockProducts').textContent = data.products.low_stock_products || 0;
            document.getElementById('totalGalleries').textContent = data.galleries.total_galleries || 0;
            document.getElementById('activeGalleries').textContent = data.galleries.active_galleries || 0;
            document.getElementById('activeProducts').textContent = data.products.active_products || 0;
            
            // Update recent activities
            updateRecentActivities(data.recent_activities);
        } else {
            console.error('Error loading dashboard data:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function updateRecentActivities(activities) {
    const container = document.getElementById('recentActivities');
    
    if (!activities || activities.length === 0) {
        container.innerHTML = '<div class="loading">Tidak ada aktivitas terbaru</div>';
        return;
    }
    
    const html = activities.map(activity => {
        const icon = getActivityIcon(activity.action);
        const timeAgo = getTimeAgo(activity.created_at);
        
        return `
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-${icon}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">${activity.action}</div>
                    <div class="activity-time">${timeAgo}</div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = html;
}

function getActivityIcon(action) {
    const icons = {
        'add_product': 'plus',
        'update_product': 'edit',
        'delete_product': 'trash',
        'add_gallery': 'image',
        'update_gallery': 'edit',
        'delete_gallery': 'trash'
    };
    return icons[action] || 'cog';
}

function getTimeAgo(timestamp) {
    const now = new Date();
    const time = new Date(timestamp);
    const diff = Math.floor((now - time) / 1000);
    
    if (diff < 60) return 'Baru saja';
    if (diff < 3600) return Math.floor(diff / 60) + ' menit yang lalu';
    if (diff < 86400) return Math.floor(diff / 3600) + ' jam yang lalu';
    return Math.floor(diff / 86400) + ' hari yang lalu';
}

// Auto refresh every 30 seconds
setInterval(loadDashboardData, 30000);

// Load data on page load
document.addEventListener('DOMContentLoaded', loadDashboardData);