<?php
// Script untuk menghasilkan hash password

// Fungsi untuk menghasilkan hash password
function generatePasswordHash($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Hash untuk Adm1n#2025
$adminPassword = 'Adm1n#2025';
$adminHash = generatePasswordHash($adminPassword);

// Hash untuk Hur7#2025
$huryPassword = 'Hur7#2025';
$huryHash = generatePasswordHash($huryPassword);

// Tampilkan hasil
echo "<h2>Hasil Hash Password</h2>";
echo "<p><strong>Admin Password:</strong> $adminPassword</p>";
echo "<p><strong>Admin Hash:</strong> $adminHash</p>";
echo "<p><strong>Hury Password:</strong> $huryPassword</p>";
echo "<p><strong>Hury Hash:</strong> $huryHash</p>";

// Verifikasi
echo "<h3>Verifikasi</h3>";
echo "<p>Admin: " . (password_verify($adminPassword, $adminHash) ? "Berhasil" : "Gagal") . "</p>";
echo "<p>Hury: " . (password_verify($huryPassword, $huryHash) ? "Berhasil" : "Gagal") . "</p>";

// Hash yang sudah ada di database
echo "<h3>Hash yang Sudah Ada di Database</h3>";
echo "<p><strong>Admin Hash di Database:</strong> \$2y\$10\$L9sRG/rHY6AlidpZ9fRSlObk0VuKFpdg.v3Zv3LpcbH0VEDK1or6.</p>";
echo "<p>Verifikasi dengan hash database: " . (password_verify($adminPassword, '$2y$10$L9sRG/rHY6AlidpZ9fRSlObk0VuKFpdg.v3Zv3LpcbH0VEDK1or6.') ? "Berhasil" : "Gagal") . "</p>";
?>