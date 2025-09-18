<?php
// Test script untuk verifikasi login functionality
require_once 'database.php';

try {
    // Test koneksi database
    echo "Testing database connection...\n";
    $pdo = getDBConnection();
    echo "✓ Database connection successful\n\n";
    
    // Test query user
    echo "Testing user query...\n";
    $stmt = $pdo->prepare("SELECT id, username, password, full_name, role, is_active FROM users WHERE username = ? AND is_active = 1");
    $stmt->execute(['admin']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✓ User found: " . $user['username'] . " (" . $user['full_name'] . ")\n";
        echo "  Role: " . $user['role'] . "\n";
        echo "  Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n\n";
        
        // Test password verification
        echo "Testing password verification...\n";
        $testPassword = 'Adm1n#2025';
        
        if (password_verify($testPassword, $user['password'])) {
            echo "✓ Password verification successful for password: " . $testPassword . "\n";
        } else {
            echo "✗ Password verification failed for password: " . $testPassword . "\n";
            echo "  Stored hash: " . $user['password'] . "\n";
        }
    } else {
        echo "✗ User 'admin' not found or inactive\n";
    }
    
    echo "\n=== Test completed ===\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>