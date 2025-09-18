<?php
// Set page title
$pageTitle = "Katalog Produk - Hury Asesoris";

// Include database connection
require_once 'config/database.php';

// Define product categories
$categories = [
    'all' => 'Semua',
    'gelang' => 'Gelang',
    'kalung' => 'Kalung',
    'cincin' => 'Cincin',
    'bros' => 'Bros',
    'anting' => 'Anting',
    'tasbih' => 'Tasbih',
    'mala' => 'Mala'
];

// Get products from database
$products = [];
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE is_active = 1 ORDER BY is_featured DESC, created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format price for display
    foreach ($products as &$product) {
        if (is_numeric($product['price'])) {
            $product['price'] = 'Rp ' . number_format($product['price'], 0, ',', '.');
        }
    }
} catch (Exception $e) {
    error_log("Error loading products: " . $e->getMessage());
    $products = [];
}

// Get active category from URL parameter
$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'all';

// Filter products by category
$filteredProducts = [];
if ($activeCategory == 'all') {
    $filteredProducts = $products;
} else {
    foreach ($products as $product) {
        if ($product['category'] == $activeCategory) {
            $filteredProducts[] = $product;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="Katalog produk Hury Asesoris - Temukan koleksi aksesoris premium dan elegan kami.">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="assets/css/catalog.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo-container">
                <img src="assets/images/hury.png" alt="Hury Asesoris" class="logo">
            </a>
            
            <button class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-menu">
                <li><a href="index.php#home">Beranda</a></li>
                <li><a href="about.php">Tentang Kami</a></li>
                <li><a href="catalog.php" class="active">Katalog</a></li>
                <li><a href="gallery.php">Galeri</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="login.php" class="btn-login">Masuk</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-overlay"></div>
        <h1 class="page-title">Katalog Produk</h1>
    </div>
    
    <!-- Catalog Full Section -->
    <section class="catalog-full">
        <div class="container">
            <div class="catalog-intro fade-up">
                <p>Temukan koleksi aksesoris premium Hury Asesoris yang dirancang dengan ketelitian tinggi dan menggunakan material berkualitas terbaik. Setiap produk kami memadukan keindahan, keanggunan, dan kualitas untuk melengkapi gaya Anda.</p>
            </div>
            
            <div class="filter-buttons fade-up">
                <?php foreach ($categories as $key => $value): ?>
                    <a href="?category=<?php echo $key; ?>" class="filter-btn <?php echo ($activeCategory == $key) ? 'active' : ''; ?>">
                        <?php echo $value; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div class="catalog-grid">
                <?php foreach ($filteredProducts as $product): ?>
                    <div class="product-card catalog-item <?php echo $product['category']; ?> fade-up">
                        <div class="product-img-container">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
                            <div class="product-overlay">
                                <a href="javascript:void(0)" class="btn btn-outline view-product" data-id="<?php echo $product['id']; ?>">Lihat Detail</a>
                            </div>
                        </div>
                        <div class="product-details">
                            <h3 class="product-title"><?php echo $product['name']; ?></h3>
                            <p class="product-price"><?php echo $product['price']; ?></p>
                            <p class="product-description"><?php echo $product['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($filteredProducts) == 0): ?>
                <div class="text-center" style="padding: 50px 0; text-align: center;">
                    <h3>Tidak ada produk yang ditemukan.</h3>
                    <p>Silakan pilih kategori lain atau lihat semua produk kami.</p>
                </div>
            <?php endif; ?>
            
            <!-- Pagination -->
            <?php if (count($filteredProducts) > 12): ?>
                <div class="pagination">
                    <button class="pagination-btn active">1</button>
                    <button class="pagination-btn">2</button>
                    <button class="pagination-btn">3</button>
                    <button class="pagination-btn"><i class="fas fa-ellipsis-h"></i></button>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Product Modal -->
    <div class="product-modal" id="productModal">
        <div class="modal-content">
            <div class="modal-close">
                <i class="fas fa-times"></i>
            </div>
            <div class="modal-body">
                <div class="modal-image">
                    <img src="" alt="Product Image" id="modalImage">
                </div>
                <div class="modal-details">
                    <h2 class="modal-title" id="modalTitle"></h2>
                    <p class="modal-price" id="modalPrice"></p>
                    <div class="modal-description" id="modalDescription"></div>
                    
                    <div class="modal-features">
                        <h4>Fitur & Spesifikasi</h4>
                        <ul class="feature-list">
                            <li><i class="fas fa-check-circle"></i> Material berkualitas premium</li>
                            <li><i class="fas fa-check-circle"></i> Desain eksklusif Hury Asesoris</li>
                            <li><i class="fas fa-check-circle"></i> Garansi keaslian material</li>
                            <li><i class="fas fa-check-circle"></i> Tersedia dalam berbagai ukuran</li>
                        </ul>
                    </div>
                    
                    <div class="modal-actions">
                        <a href="contact.php" class="btn">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <img src="assets/images/hury.png" alt="Hury Asesoris" class="footer-logo">
                    <p>Hury Asesories menghadirkan biji jenitri dari Kebumen menjadi aksesoris dan kerajinan berkualitas, memadukan nilai tradisi dengan desain modern.</p>
                </div>
                
                <div class="footer-col">
                    <h4>Tautan Cepat</h4>
                    <ul class="footer-links">
                        <li><a href="index.php#home">Beranda</a></li>
                        <li><a href="about.php">Tentang Kami</a></li>
                        <li><a href="catalog.php">Katalog</a></li>
                        <li><a href="contact.php">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Kategori Produk</h4>
                    <ul class="footer-links">
                        <li><a href="catalog.php?category=gelang">Gelang</a></li>
                        <li><a href="catalog.php?category=kalung">Kalung</a></li>
                        <li><a href="catalog.php?category=cincin">Cincin</a></li>
                        <li><a href="catalog.php?category=bros">Bros</a></li>
                        <li><a href="catalog.php?category=anting">Anting</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Bantuan</h4>
                    <ul class="footer-links">
                        <li><a href="#">Cara Pemesanan</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Hury Asesoris. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scroll Up Button -->
    <div class="scroll-up">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/catalog.js"></script>
    <script src="assets/js/catalog_page.js"></script>
    <script>
        // Initialize catalog data
        initCatalogData(<?php echo json_encode($products); ?>);
    </script>
</body>
</html>