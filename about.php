<?php
// Set page title
$pageTitle = "Tentang Kami - Hury Asesoris";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="Tentang Hury Asesoris - Penyedia aksesoris premium dan elegan di Indonesia.">
    
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
    <link rel="stylesheet" href="assets/css/about.css">
        
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
                <li><a href="about.php" class="active">Tentang Kami</a></li>
                <li><a href="catalog.php">Katalog</a></li>
                <li><a href="gallery.php">Galeri</a></li>
                <li><a href="contact.php">Kontak</a></li>
                <li><a href="login.php" class="btn-login">Masuk</a></li>
            </ul>
        </div>
    </header>
    
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-overlay"></div>
        <h1 class="page-title">Tentang Kami</h1>
    </div>
    
    <!-- About Full Section -->
    <section class="about-full">
        <div class="container">
            <div class="about-section fade-up">
                <h2>Sejarah Kami</h2>
                <p>Hury Asesories berdiri sejak tahun 2016 dengan tujuan memperkenalkan Jenitri Kebumen kepada masyarakat luas. Jenitri, atau Rudraksha, merupakan komoditas unggulan Kebumen yang memasok lebih dari 70% kebutuhan dunia. Melalui aksesoris dan kerajinan, kami menghadirkan jenitri agar lebih dekat dengan kehidupan sehari-hari.</p>
                <p>Dalam perjalanannya, Hury Asesories meraih berbagai pencapaian penting. Tahun 2017, kami dinobatkan sebagai Usaha Mikro Terbaik dalam ajang Kebumen Business Forum. Lalu pada tahun 2019, kami terpilih mengikuti program Pendampingan Design Dispatch Service (DDS) dari Kementerian Perdagangan.</p>
                <p>Saat ini Hury Asesories memproduksi gelang, kalung, tasbih, mala, hingga souvenir khas Kebumen. Setiap produk dibuat dengan ketelitian tinggi menggunakan biji jenitri pilihan, sehingga memiliki nilai estetika sekaligus makna filosofis. Dengan semangat inovasi, kami berkomitmen melestarikan budaya lokal dan memperkenalkan jenitri Indonesia ke pasar global.</p>
            </div>
            
            <div class="about-image-full fade-in">
                <img src="assets/images/model.png" alt="Hury Asesoris Workshop">
            </div>
            
            <div class="about-section fade-up">
                <h2>Visi & Misi</h2>
                <h3 style="color: var(--gold); margin: 20px 0 10px;">Visi Kami</h3>
                <p>Menjadi brand aksesoris jenitri terkemuka yang mengangkat potensi lokal Kebumen hingga dikenal secara nasional dan internasional, dengan menjaga kualitas, keaslian, dan filosofi setiap produk.</p>
                
                <h3 style="color: var(--gold); margin: 20px 0 10px;">Misi Kami</h3>
                <ul style="margin-left: 20px; margin-bottom: 20px;">
                    <li>Mengolah biji jenitri menjadi aksesoris dan kerajinan berkualitas tinggi.</li>
                    <li>Mengedukasi masyarakat tentang manfaat dan filosofi jenitri.</li>
                    <li>Menghadirkan inovasi desain sesuai tren tanpa meninggalkan nilai tradisi.</li>
                    <li>Membawa kebanggaan bagi produk lokal Indonesia ke kancah global.</li>
                </ul>
            </div>
            
            <div class="about-section fade-up">
                <h2>Nilai-Nilai Kami</h2>
                <p>Di Hury Asesoris, kami berpegang pada nilai-nilai yang menjadi fondasi dari setiap aspek bisnis kami:</p>
                
                <div class="values-grid">
                    <div class="value-card fade-up">
                        <div class="value-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3 class="value-title">Kualitas Premium</h3>
                        <p>Kami menggunakan biji jenitri pilihan dan material terbaik untuk setiap produk. Proses perakitan dilakukan dengan ketelitian tinggi agar menghasilkan aksesoris dan kerajinan berkualitas.</p>
                    </div>
                    
                    <div class="value-card fade-up">
                        <div class="value-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3 class="value-title">Desain Bernilai Filosofis</h3>
                        <p>Setiap produk jenitri kami dirancang dengan memadukan keindahan, nilai tradisi, dan sentuhan modern. Hasilnya, aksesoris unik yang tidak hanya indah, tetapi juga sarat makna.</p>
                    </div>
                    
                    <div class="value-card fade-up">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Kepuasan Pelanggan</h3>
                        <p>Kami selalu mengutamakan pelayanan terbaik agar setiap pelanggan merasakan pengalaman berbelanja yang menyenangkan. Setiap karya kami dibuat untuk menghadirkan kepuasan dan nilai lebih bagi pemakainya.</p>
                    </div>
                    
                    <div class="value-card fade-up">
                        <div class="value-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h3 class="value-title">Inovasi Berkelanjutan</h3>
                        <p>Kami terus berinovasi dalam desain dan pengolahan jenitri agar tetap relevan mengikuti tren. Inovasi ini mendukung misi kami untuk memperkenalkan potensi lokal ke pasar nasional dan internasional.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-title fade-up">
                <h2>Tim Kami</h2>
            </div>
            
            <div class="team-grid">
                <div class="team-card fade-up">
                    <div class="team-img-container">
                        <img src="assets/images/model.png" alt="Haryati" class="team-img">
                    </div>
                    <div class="team-details">
                        <h3 class="team-name">Nanang Wahidin</h3>
                        <p class="team-position">Founder & CEO</p>
                        <p class="team-bio">Pendiri Hury Asesoris dengan 9 tahun pengalaman di industri perhiasan dan aksesoris.</p>
                        <div class="team-social">
                            <a href="#" class="team-social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="team-social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>            
            </div>
        </div>
    </section>
    
    <!-- Timeline Section -->
    <section class="timeline-section">
        <div class="container">
            <div class="section-title fade-up">
                <h2>Perjalanan Kami</h2>
            </div>
            
            <div class="timeline">
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2016</div>
                        <h3 class="timeline-title">Awal Mula</h3>
                        <p>Hury Asesories resmi berdiri dengan misi memperkenalkan potensi Jenitri Kebumen melalui produk aksesoris dan kerajinan.</p>
                    </div>
                </div>
                
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2017</div>
                        <h3 class="timeline-title">Penghargaan Usaha Mikro</h3>
                        <p>Meraih penghargaan sebagai Usaha Mikro Terbaik dalam ajang Kebumen Business Forum 2017.</p>
                    </div>
                </div>
                
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2018</div>
                        <h3 class="timeline-title">Ekspansi Pasar Lokal</h3>
                        <p>Mulai memperluas pemasaran melalui media sosial dan toko online.</p>
                    </div>
                </div>
                
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2019</div>
                        <h3 class="timeline-title">Pendampingan Desain</h3>
                        <p>Terpilih mengikuti program Design Dispatch Service (DDS) dari Kementerian Perdagangan.</p>
                    </div>
                </div>
                
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2020</div>
                        <h3 class="timeline-title">Inovasi Digital</h3>
                        <p>Memperkuat kehadiran digital dengan memaksimalkan platform media sosial dan e-commerce.</p>
                    </div>
                </div>
                
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <div class="timeline-date">2023</div>
                        <h3 class="timeline-title">Ekspansi dan Edukasi</h3>
                        <p>Terus mengembangkan pasar ke berbagai daerah sekaligus aktif mengedukasi masyarakat tentang filosofi dan manfaat jenitri.</p>
                    </div>
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
    
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>