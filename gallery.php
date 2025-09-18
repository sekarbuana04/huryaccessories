<?php
// Set page title
$pageTitle = "Galeri - Hury Asesoris";

// Include database connection
require_once 'config/database.php';

try {
    $pdo = getDBConnection();
    
    // Fetch gallery items
    $stmt = $pdo->query("SELECT * FROM gallery WHERE is_active = 1 ORDER BY display_order ASC, created_at DESC");
    $galleryItems = $stmt->fetchAll();
} catch (Exception $e) {
    $galleryItems = [];
    $error = "Gagal memuat galeri: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="Galeri koleksi aksesoris dan kerajinan jenitri Hury Asesoris.">
    
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
    <link rel="stylesheet" href="assets/css/gallery.css">
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
                <li><a href="catalog.php">Katalog</a></li>
                <li><a href="gallery.php" class="active">Galeri</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="login.php" class="btn-login">Masuk</a></li>
            </ul>
        </div>
    </header>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-overlay"></div>
        <div class="container">
            <h1 class="page-title">Galeri Koleksi</h1>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <div class="gallery-intro fade-up">
                <p>Jelajahi koleksi visual terbaik dari Hury Asesoris yang menampilkan keindahan dan keunikan setiap produk aksesoris kami. Setiap foto dalam galeri ini merepresentasikan dedikasi kami terhadap kualitas, ketelitian dalam pembuatan, dan keanggunan desain yang menjadi ciri khas produk-produk Hury Asesoris.</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($galleryItems)): ?>
                <div class="gallery-grid">
                    <?php foreach ($galleryItems as $item): ?>
                        <div class="gallery-item" data-aos="fade-up">
                            <div class="gallery-image">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                     loading="lazy">
                                <div class="gallery-overlay">
                                    <div class="gallery-content">
                                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <?php if (!empty($item['subtitle'])): ?>
                                            <p class="subtitle"><?php echo htmlspecialchars($item['subtitle']); ?></p>
                                        <?php endif; ?>
                                        <button class="btn-view" onclick="openModal(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-gallery">
                    <i class="fas fa-images"></i>
                    <h3>Galeri Kosong</h3>
                    <p>Belum ada item di galeri. Silakan kembali lagi nanti.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="modal-body">
                <img id="modalImage" src="" alt="">
                <div class="modal-info">
                    <h3 id="modalTitle"></h3>
                    <p id="modalSubtitle"></p>
                    <p id="modalDescription"></p>
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
                        <li><a href="gallery.php">Galeri</a></li>
                        <li><a href="contact.php">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Kategori Produk</h4>
                    <ul class="footer-links">
                        <li><a href="catalog.php">Gelang</a></li>
                        <li><a href="catalog.php">Kalung</a></li>
                        <li><a href="catalog.php">Cincin</a></li>
                        <li><a href="catalog.php">Bros</a></li>
                        <li><a href="catalog.php">Anting</a></li>
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

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/gallery_page.js"></script>
    <script src="assets/js/gallery.js"></script>
    
    <script>
        // Initialize gallery data
        initGalleryData(<?php echo json_encode($galleryItems); ?>);
    </script>
</body>
</html>