# Hury Asesoris - Company Profile Website

## Deskripsi

Website company profile untuk Hury Asesoris dengan desain elegan, premium, dan dinamis. Website ini menampilkan informasi perusahaan, katalog produk, dan kontak dalam tampilan yang menarik dengan animasi dan efek interaktif.

## Fitur

- **Landing Page dengan Dynamic Hero Section**
  - Header dengan logo dan menu navigasi
  - Slider/banner promo otomatis dengan efek transisi
  - Section highlight produk unggulan dengan animasi scroll

- **Halaman Tentang Kami**
  - Sejarah singkat perusahaan
  - Visi dan misi
  - Keunggulan perusahaan
  - Timeline perjalanan perusahaan

- **Katalog Produk**
  - Produk dikelompokkan berdasarkan kategori
  - Grid responsif dengan hover effect
  - Filter kategori produk
  - Modal detail produk

- **Halaman Kontak**
  - Informasi kontak lengkap
  - Media sosial dengan efek hover
  - Formulir kontak dengan validasi
  - Peta lokasi

- **Fitur Tambahan**
  - Smooth scrolling
  - Efek parallax
  - Animasi CSS dan JavaScript/GSAP
  - Tombol Scroll Up
  - Desain responsif (mobile-first)

## Teknologi yang Digunakan

- HTML5
- CSS3
- JavaScript
- PHP
- Font Awesome (ikon)
- Google Fonts
- Intersection Observer API (untuk animasi scroll)

## Struktur Folder

```
/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       ├── logo.svg
│       ├── favicon.svg
│       └── ...
├── index.php
├── about.php
├── catalog.php
├── contact.php
├── 404.php
├── .htaccess
└── README.md
```

## Cara Penggunaan

1. Pastikan server web dengan dukungan PHP telah terpasang (seperti Apache, Nginx, atau XAMPP)
2. Salin semua file ke direktori root web server
3. Akses website melalui browser dengan URL sesuai konfigurasi server

## Kustomisasi

### Menambahkan Produk Baru

Untuk menambahkan produk baru, edit file `catalog.php` dan tambahkan data produk pada array `$products`:

```php
$products[] = [
    'id' => '[id_unik]',
    'name' => 'Nama Produk',
    'price' => 'Harga Produk',
    'description' => 'Deskripsi produk...',
    'image' => 'assets/images/products/nama-gambar.jpg',
    'category' => 'kategori'
];
```

### Mengubah Warna Tema

Untuk mengubah warna tema, edit variabel CSS di file `assets/css/style.css`:

```css
:root {
    --gold: #B88A53;
    --black: #000000;
    --white: #FFFFFF;
    /* ... */
}
```

## Kredit

- Font: [Google Fonts](https://fonts.google.com/) (Playfair Display & Open Sans)
- Ikon: [Font Awesome](https://fontawesome.com/)

## Lisensi

Hak Cipta © 2025 Hury Asesoris. Semua hak dilindungi.
