<?php
require_once '../config/database.php';
require_once 'auth.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $pdo = getDBConnection();
        
        switch ($_POST['action']) {
            case 'get_stats':
                // Get comprehensive statistics
                $stats = [];
                
                // Product statistics
                $productQuery = "SELECT 
                    COUNT(*) as total_products,
                    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured_products,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_products,
                    SUM(CASE WHEN stock_quantity < 10 THEN 1 ELSE 0 END) as low_stock_products,
                    SUM(stock_quantity) as total_stock,
                    AVG(CAST(REPLACE(price, 'Rp ', '') AS DECIMAL(10,2))) as avg_price
                    FROM products";
                $productStmt = $pdo->query($productQuery);
                $productStats = $productStmt->fetch();
                
                // Gallery statistics
                $galleryQuery = "SELECT 
                    COUNT(*) as total_galleries,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_galleries
                    FROM gallery";
                $galleryStmt = $pdo->query($galleryQuery);
                $galleryStats = $galleryStmt->fetch();
                
                // Recent activities
                $activityQuery = "SELECT * FROM admin_logs ORDER BY created_at DESC LIMIT 5";
                $activityStmt = $pdo->query($activityQuery);
                $recentActivities = $activityStmt->fetchAll();
                
                $stats = [
                    'products' => $productStats ?: [],
                    'galleries' => $galleryStats ?: [],
                    'categories' => ['total' => 7], // Fixed categories
                    'recent_activities' => $recentActivities ?: []
                ];
                
                echo json_encode(['success' => true, 'data' => $stats]);
                break;
                
            case 'get_recent_orders':
                // Simulate recent orders (you can implement actual orders table later)
                $recentOrders = [
                    ['id' => 1, 'customer' => 'John Doe', 'total' => 150000, 'status' => 'pending'],
                    ['id' => 2, 'customer' => 'Jane Smith', 'total' => 250000, 'status' => 'completed'],
                    ['id' => 3, 'customer' => 'Bob Johnson', 'total' => 75000, 'status' => 'processing']
                ];
                echo json_encode(['success' => true, 'data' => $recentOrders]);
                break;
                
            case 'get_top_products':
                $topProductsQuery = "SELECT name, price, stock_quantity, is_featured FROM products WHERE is_active = 1 ORDER BY is_featured DESC, stock_quantity DESC LIMIT 5";
                $stmt = $pdo->query($topProductsQuery);
                $topProducts = $stmt->fetchAll();
                echo json_encode(['success' => true, 'data' => $topProducts]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Control - Hury Accessories Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard_control.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        

        <div class="dashboard-container">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h1><i class="fas fa-chart-line"></i> Dashboard Overview</h1>
                <p>Pantau performa dan statistik toko Anda secara real-time</p>
            </div>
            


            <!-- Statistics Grid -->
            <div class="stats-grid" id="statsGrid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-box"></i></div>
                    </div>
                    <div class="stat-value" id="totalProducts">-</div>
                    <div class="stat-label">Total Produk</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                    </div>
                    <div class="stat-value" id="featuredProducts">-</div>
                    <div class="stat-label">Produk Unggulan</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-warehouse"></i></div>
                    </div>
                    <div class="stat-value" id="totalStock">-</div>
                    <div class="stat-label">Total Stok</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                    <div class="stat-value" id="lowStockProducts">-</div>
                    <div class="stat-label">Stok Rendah</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-tags"></i></div>
                    </div>
                    <div class="stat-value" id="totalCategories">7</div>
                    <div class="stat-label">Total Kategori</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-images"></i></div>
                    </div>
                    <div class="stat-value" id="totalGalleries">-</div>
                    <div class="stat-label">Total Galeri</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-eye"></i></div>
                    </div>
                    <div class="stat-value" id="activeGalleries">-</div>
                    <div class="stat-label">Galeri Aktif</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div class="stat-value" id="activeProducts">-</div>
                    <div class="stat-label">Produk Aktif</div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Recent Activities -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h2 class="section-title"><i class="fas fa-history"></i> Aktivitas Terbaru</h2>
                    </div>
                    <div id="recentActivities">
                        <div class="loading">
                            <i class="fas fa-spinner fa-spin"></i> Memuat aktivitas...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard_control.js"></script>
</body>
</html>