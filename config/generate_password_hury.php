<?php
// Script to generate correct password hash for Hury user

// Password yang akan di-hash
$password = 'Hur7#2025';

// Generate hash menggunakan password_hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hashedPassword . "\n";
echo "\n";
echo "SQL untuk update password:\n";
echo "UPDATE users SET password = '" . $hashedPassword . "' WHERE username = 'hury';\n";

// Test verifikasi
if (password_verify($password, $hashedPassword)) {
    echo "\nVerifikasi berhasil! Hash password sudah benar.\n";
} else {
    echo "\nVerifikasi gagal! Ada masalah dengan hash password.\n";
}
?>