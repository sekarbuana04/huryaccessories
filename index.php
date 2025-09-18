<?php
// Set page title
$pageTitle = "Hury Asesoris - Aksesoris & Kerajinan Jenitri Kebumen";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="Hury Asesoris menyediakan aksesoris elegan dan premium untuk melengkapi gaya Anda.">
    
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
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="login.php" class="btn-login">Masuk</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-slider">
            <div class="slide active" style="background-image: url('assets/images/gelang1.png');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1 class="slide-title">Tampil Eksotis dengan Aksesoris <span>Jenitri</span> Kebumen</h1>
                    <p class="slide-description">Hury Asesories menghadirkan gelang, kalung, dan tasbih jenitri berkualitas dari Kebumen.</p>
                    <a href="catalog.php" class="btn">Lihat Produk Jenitri</a>
                </div>
            </div>
            
            <div class="slide" style="background-image: url('assets/images/rosario1.jpg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1 class="slide-title">Koleksi <span>Jenitri</span> & Souvenir Terbaru</h1>
                    <p class="slide-description">Temukan kalung, gelang, dan souvenir jenitri dengan desain modern, dipadukan manik kayu, logam, hingga mutiara air tawar.</p>
                    <a href="catalog.php" class="btn">Lihat Koleksi Baru</a>
                </div>
            </div>
            
            <div class="slide" style="background-image: url('assets/images/tasbih1.png');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1 class="slide-title">Hadiah Unik <span>Jenitri</span> untuk Orang Tersayang</h1>
                    <p class="slide-description">Souvenir khas Kebumen dari jenitri, tersedia dalam berbagai pilihan</p>
                    <a href="catalog.php" class="btn">Lihat Souvenir</a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <div class="section-title fade-up">
                <h2>Produk Unggulan</h2>
            </div>
            
            <div class="products-grid">
                <!-- Product 1 -->
                <div class="product-card">
                    <div class="product-img-container">
                        <img src="assets/images/gelang1.png" alt="Gelang Emas Premium" class="product-img">
                        <div class="product-overlay">
                            <a href="#" class="btn btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Gelang Pandu</h3>
                        <p class="product-price">Rp 15.000</p>
                        <p class="product-description">Gelang jenitri sederhana dengan desain praktis, cocok dipakai sehari-hari.</p>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="product-card">
                    <div class="product-img-container">
                        <img src="assets/images/rosario1.jpg" alt="Kalung Mutiara Klasik" class="product-img">
                        <div class="product-overlay">
                            <a href="#" class="btn btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Kalung Kebayan</h3>
                        <p class="product-price">Rp 110.000</p>
                        <p class="product-description">Kalung jenitri dengan desain klasik, elegan, dan bernuansa etnik khas Kebumen.</p>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="product-card">
                    <div class="product-img-container">
                        <img src="assets/images/bros1.png" alt="Cincin Berlian Solitaire" class="product-img">
                        <div class="product-overlay">
                            <a href="#" class="btn btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Mandalika Drop</h3>
                        <p class="product-price">Rp 10.000 - 25.000</p>
                        <p class="product-description">Bros sederhana dengan perpaduan jenitri dan mutiara air tawar, cocok sebagai aksesoris tambahan yang elegan.</p>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="product-card">
                    <div class="product-img-container">
                        <img src="assets/images/tasbih1.png" alt="Bros Elegan" class="product-img">
                        <div class="product-overlay">
                            <a href="#" class="btn btn-outline">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Tasbih</h3>
                        <p class="product-price">Rp 60.000</p>
                        <p class="product-description">Tasbih jenitri 99 butir dengan kualitas tinggi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="about">
        <div class="container about-container">
            <div class="about-content">
                <h2>Tentang Kami</h2>
                <p>Hury Asesories berdiri sejak tahun 2016 dengan tujuan memperkenalkan Jenitri Kebumen kepada masyarakat luas. Jenitri, atau Rudraksha, merupakan komoditas unggulan Kebumen yang memasok lebih dari 70% kebutuhan dunia. Melalui aksesoris dan kerajinan, kami menghadirkan jenitri agar lebih dekat dengan kehidupan sehari-hari.</p>
                <p>Dalam perjalanannya, Hury Asesories meraih berbagai pencapaian penting. Tahun 2017, kami dinobatkan sebagai Usaha Mikro Terbaik dalam ajang Kebumen Business Forum. Lalu pada tahun 2019, kami terpilih mengikuti program Pendampingan Design Dispatch Service (DDS) dari Kementerian Perdagangan.</p>
                <p>Saat ini Hury Asesories memproduksi gelang, kalung, tasbih, mala, hingga souvenir khas Kebumen. Setiap produk dibuat dengan ketelitian tinggi menggunakan biji jenitri pilihan, sehingga memiliki nilai estetika sekaligus makna filosofis. Dengan semangat inovasi, kami berkomitmen melestarikan budaya lokal dan memperkenalkan jenitri Indonesia ke pasar global.</p>
            </div>
            
            <div class="about-image">
                <img src="assets/images/model.png" alt="Hury Asesoris Workshop">
            </div>
        </div>
    </section>
        
    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-title fade-up">
                <h2>Hubungi Kami</h2>
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
                    <div class="social-links">
                        <a href="https://www.instagram.com/hury_asesories" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@hury_asesories" class="social-link">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="#https://www.facebook.com/share/174xu6tskN/" class="social-link">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://wa.me/6281389184960" class="social-link">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="contact-form fade-right">
                    <form id="contact-form">
                        <div class="form-group">
                            <input type="text" id="name" class="form-control" placeholder="Nama Lengkap">
                            <div id="name-error" class="form-error">Nama tidak boleh kosong</div>
                        </div>
                        
                        <div class="form-group">
                            <input type="email" id="email" class="form-control" placeholder="Email">
                            <div id="email-error" class="form-error">Email tidak valid</div>
                        </div>
                        
                        <div class="form-group">
                            <input type="text" id="subject" class="form-control" placeholder="Subjek">
                        </div>
                        
                        <div class="form-group">
                            <textarea id="message" class="form-control" placeholder="Pesan"></textarea>
                            <div id="message-error" class="form-error">Pesan tidak boleh kosong</div>
                        </div>
                        
                        <button type="submit" class="btn">Kirim Pesan</button>
                    </form>
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
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#catalog">Katalog</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h4>Kategori Produk</h4>
                    <ul class="footer-links">
                        <li><a href="#catalog">Gelang</a></li>
                        <li><a href="#catalog">Kalung</a></li>
                        <li><a href="#catalog">Cincin</a></li>
                        <li><a href="#catalog">Bros</a></li>
                        <li><a href="#catalog">Anting</a></li>
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
</body>
</html>