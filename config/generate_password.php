<?php
// Script to generate correct password hash for admin user

// Password yang akan di-hash
$password = 'Adm1n#2025';

// Generate hash menggunakan password_hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Password: " . $password . "\n";
echo "Hash: " . $hashedPassword . "\n";
echo "\n";
echo "SQL untuk update password:\n";
echo "UPDATE users SET password = '" . $hashedPassword . "' WHERE username = 'admin';\n";

// Test verifikasi
if (password_verify($password, $hashedPassword)) {
    echo "\nVerifikasi berhasil! Hash password sudah benar.\n";
} else {
    echo "\nVerifikasi gagal! Ada masalah dengan hash password.\n";
}
?>