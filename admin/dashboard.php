<?php
require_once 'auth.php';
requireLogin();

// Get current user info
$currentUser = [
    'id' => $_SESSION['admin_id'],
    'username' => $_SESSION['admin_username'],
    'full_name' => $_SESSION['admin_full_name'],
    'role' => $_SESSION['admin_role']
];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $pdo = getDBConnection();
        
        switch ($_POST['action']) {
            case 'get_products':
                $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                $products = $stmt->fetchAll();
                echo json_encode(['success' => true, 'data' => $products]);
                break;
                
            case 'get_product':
                $id = intval($_POST['id']);
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                echo json_encode(['success' => true, 'data' => $product]);
                break;
                
            case 'add_product':
                $name = trim($_POST['name']);
                $price = trim($_POST['price']);
                $description = trim($_POST['description']);
                $image = trim($_POST['image']);
                $category = $_POST['category'];
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $stock_quantity = intval($_POST['stock_quantity']);
                
                $stmt = $pdo->prepare("
                    INSERT INTO products (name, price, description, image, category, is_featured, stock_quantity, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$name, $price, $description, $image, $category, $is_featured, $stock_quantity, $currentUser['id']]);
                
                $productId = $pdo->lastInsertId();
                logAdminActivity($currentUser['id'], 'CREATE', 'products', $productId, null, [
                    'name' => $name, 'price' => $price, 'category' => $category
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
                break;
                
            case 'update_product':
                $id = intval($_POST['id']);
                $name = trim($_POST['name']);
                $price = trim($_POST['price']);
                $description = trim($_POST['description']);
                $image = trim($_POST['image']);
                $category = $_POST['category'];
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $stock_quantity = intval($_POST['stock_quantity']);
                
                // Get old values for logging
                $oldStmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $oldStmt->execute([$id]);
                $oldProduct = $oldStmt->fetch();
                
                $stmt = $pdo->prepare("
                    UPDATE products SET name = ?, price = ?, description = ?, image = ?, category = ?, 
                    is_featured = ?, stock_quantity = ?, updated_at = NOW() WHERE id = ?
                ");
                $stmt->execute([$name, $price, $description, $image, $category, $is_featured, $stock_quantity, $id]);
                
                logAdminActivity($currentUser['id'], 'UPDATE', 'products', $id, $oldProduct, [
                    'name' => $name, 'price' => $price, 'category' => $category
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Produk berhasil diperbarui']);
                break;
                
            case 'delete_product':
                $id = intval($_POST['id']);
                
                // Get product info for logging
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                
                $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $deleteStmt->execute([$id]);
                
                logAdminActivity($currentUser['id'], 'DELETE', 'products', $id, $product, null);
                
                echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus']);
                break;
                
            case 'get_galleries':
                $stmt = $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, created_at DESC");
                $galleries = $stmt->fetchAll();
                echo json_encode(['success' => true, 'data' => $galleries]);
                break;
                
            case 'get_stats':
                // Get product statistics
                $productStats = $pdo->query("SELECT 
                    COUNT(*) as total_products,
                    COUNT(CASE WHEN is_featured = 1 THEN 1 END) as featured_products,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_products,
                    COUNT(CASE WHEN stock_quantity <= 5 THEN 1 END) as low_stock_products,
                    COALESCE(SUM(stock_quantity), 0) as total_stock
                    FROM products")->fetch();
                
                // Get gallery statistics
                $galleryStats = $pdo->query("SELECT 
                    COUNT(*) as total_galleries,
                    COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_galleries
                    FROM gallery")->fetch();
                
                // Get category count
                $categoryCount = 7; // Fixed number of categories
                
                $stats = [
                    'total_products' => $productStats['total_products'],
                    'featured_products' => $productStats['featured_products'],
                    'active_products' => $productStats['active_products'],
                    'low_stock_products' => $productStats['low_stock_products'],
                    'total_stock' => $productStats['total_stock'],
                    'total_galleries' => $galleryStats['total_galleries'],
                    'active_galleries' => $galleryStats['active_galleries'],
                    'total_categories' => $categoryCount
                ];
                
                echo json_encode(['success' => true, 'data' => $stats]);
                break;
                
            case 'get_gallery':
                $id = intval($_POST['id']);
                $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                $gallery = $stmt->fetch();
                echo json_encode(['success' => true, 'data' => $gallery]);
                break;
                
            case 'add_gallery':
                $title = trim($_POST['title']);
                $subtitle = trim($_POST['subtitle']);
                $image = trim($_POST['image']);
                $description = trim($_POST['description']);
                $display_order = intval($_POST['sort_order']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                $stmt = $pdo->prepare("
                    INSERT INTO gallery (title, subtitle, image, description, display_order, is_active, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$title, $subtitle, $image, $description, $display_order, $is_active]);
                
                $galleryId = $pdo->lastInsertId();
                logAdminActivity($currentUser['id'], 'CREATE', 'gallery', $galleryId, null, [
                    'title' => $title, 'subtitle' => $subtitle
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Galeri berhasil ditambahkan']);
                break;
                
            case 'update_gallery':
                $id = intval($_POST['id']);
                $title = trim($_POST['title']);
                $subtitle = trim($_POST['subtitle']);
                $image = trim($_POST['image']);
                $description = trim($_POST['description']);
                $display_order = intval($_POST['sort_order']);
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Get old values for logging
                $oldStmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
                $oldStmt->execute([$id]);
                $oldGallery = $oldStmt->fetch();
                
                $stmt = $pdo->prepare("
                    UPDATE gallery SET title = ?, subtitle = ?, image = ?, description = ?, 
                    display_order = ?, is_active = ? WHERE id = ?
                ");
                $stmt->execute([$title, $subtitle, $image, $description, $display_order, $is_active, $id]);
                
                logAdminActivity($currentUser['id'], 'UPDATE', 'gallery', $id, $oldGallery, [
                    'title' => $title, 'subtitle' => $subtitle
                ]);
                
                echo json_encode(['success' => true, 'message' => 'Galeri berhasil diperbarui']);
                break;
                
            case 'delete_gallery':
                $id = intval($_POST['id']);
                
                // Get gallery info for logging
                $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                $gallery = $stmt->fetch();
                
                $deleteStmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
                $deleteStmt->execute([$id]);
                
                logAdminActivity($currentUser['id'], 'DELETE', 'gallery', $id, $gallery, null);
                
                echo json_encode(['success' => true, 'message' => 'Galeri berhasil dihapus']);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Action tidak valid']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/admin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="admin-body">
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="admin-nav">
            <div class="admin-logo">
                <img src="../assets/images/hury.png" alt="Logo">
            </div>
            
            <div class="admin-user">
                <div class="user-info">
                    <span class="user-name"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    <span class="user-role"><?php echo ucfirst($currentUser['role']); ?></span>
                </div>
                <div class="user-actions">
                    <button class="btn-user-menu" onclick="toggleUserMenu()">
                        <i class="fas fa-user-circle"></i>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <a href="#" onclick="showProfile()"><i class="fas fa-user"></i> Profil</a>
                        <a href="#" onclick="showSettings()"><i class="fas fa-cog"></i> Pengaturan</a>
                        <hr>
                        <a href="auth.php?action=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-tachometer-alt"></i> Admin Panel</h3>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="#dashboard" class="nav-link" data-section="dashboard-section">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#catalog" class="nav-link" data-section="catalog-section">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Katalog</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#gallery" class="nav-link" data-section="gallery-section">
                        <i class="fas fa-images"></i>
                        <span>Galeri</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Admin Main Content -->
    <main class="admin-main">
        <div class="admin-container">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="content-section active">
                <iframe src="dashboard_control.php" 
                        style="width: 100%; height: calc(100vh - 120px); border: none; border-radius: 10px;"
                        frameborder="0"
                        id="dashboardFrame">
                </iframe>
            </div>
            
            <!-- Catalog Section -->
            <div id="catalog-section" class="content-section">
                <iframe src="catalog_control.php" 
                        style="width: 100%; height: calc(100vh - 120px); border: none; border-radius: 10px;"
                        frameborder="0"
                        id="catalogFrame">
                </iframe>
            </div>
            
            <!-- Gallery Section -->
            <div id="gallery-section" class="content-section">
                <iframe src="gallery_control.php" 
                        style="width: 100%; height: calc(100vh - 120px); border: none; border-radius: 10px;"
                        frameborder="0"
                        id="galleryFrame">
                </iframe>
            </div>
        </div>
    </main>
    

    
    <!-- Scripts -->
    <script src="../assets/js/admin.js?v=<?php echo time(); ?>"></script>
    <script>
        // Load settings when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadSettings();
        });
    </script>
</body>
</html>