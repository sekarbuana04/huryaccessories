<?php
// Set page title
$pageTitle = "Kontak Kami - Hury Asesoris";

// Process form submission
$formSubmitted = false;
$formError = false;
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate form data
    if (empty($name) || empty($email) || empty($message)) {
        $formError = true;
        $errorMessage = 'Mohon lengkapi semua field yang wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = true;
        $errorMessage = 'Mohon masukkan alamat email yang valid.';
    } else {
        // Form is valid, process the submission
        // In a real application, you would send an email or save to database here
        $formSubmitted = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="Hubungi Hury Asesoris untuk informasi lebih lanjut tentang produk dan layanan kami.">
    
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
    <link rel="stylesheet" href="assets/css/contact.css">

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
                <li><a href="gallery.php">Galeri</a></li>
                <li><a href="contact.php" class="active">Kontak</a></li>
                <li><a href="login.php" class="btn-login">Masuk</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-overlay"></div>
        <h1 class="page-title">Hubungi Kami</h1>
    </div>
    
    <!-- Contact Full Section -->
    <section class="contact-full">
        <div class="container">
            <div class="contact-intro fade-up">
                <p>Kami senang mendengar dari Anda! Jika Anda memiliki pertanyaan tentang produk kami, ingin memesan, atau membutuhkan informasi lebih lanjut, jangan ragu untuk menghubungi kami melalui formulir di bawah ini atau menggunakan informasi kontak yang tersedia.</p>
            </div>
            
            <div class="contact-container">
                <div class="contact-info fade-left">
                    <h3>Informasi Kontak</h3>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Alamat</h4>
                                <p>Gang Asem No. 7<br>RT. 02 RW. 04, Kutosari<br>Kebumen, Jawa Tengah, 54317</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Telepon</h4>
                                <p>+62 813 8918 4960</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p>huryasesories@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <h4>Jam Operasional</h4>
                                <p>Senin - Minggu: 08:00 - 20:00</p>
                            </div>
                        </div>
                    </div>
                    
                    <h3>Media Sosial</h3>
                    <p>Ikuti kami di media sosial untuk mendapatkan update terbaru tentang produk, promo, dan event kami.</p>
                    
                    <div class="social-links">
                        <a href="https://www.instagram.com/hury_asesories" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@hury_asesories" class="social-link" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="https://www.facebook.com/share/174xu6tskN/" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/6281389184960" class="social-link" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="contact-form fade-right">
                    <h3>Kirim Pesan</h3>
                    
                    <?php if ($formSubmitted): ?>
                        <div class="form-success">
                            <p><strong>Terima kasih!</strong> Pesan Anda telah berhasil dikirim. Kami akan menghubungi Anda segera.</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($formError): ?>
                        <div class="form-error" style="margin-bottom: 20px;">
                            <p><strong>Error:</strong> <?php echo $errorMessage; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form id="contact-form" method="post" action="">
                        <div class="form-group">
                            <label for="name" class="form-label">Nama Lengkap <span class="required-field">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email <span class="required-field">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" id="subject" name="subject" class="form-control" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="form-label">Pesan <span class="required-field">*</span></label>
                            <textarea id="message" name="message" class="form-control" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Kirim Pesan</button>
                    </form>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="map-section fade-up">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4139819929835!2d109.6432009!3d-7.6641549!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7ab5f5d746ac85%3A0x971661d8a5b3a3d3!2sHURY%20Accessories%20Jenitri!5e0!3m2!1sid!2sid!4v1691839200000!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>
    
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
    <script src="assets/js/contact.js"></script>
</body>
</html>