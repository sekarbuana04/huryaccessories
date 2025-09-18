<?php
require_once 'auth.php';
requireLogin();

// Get current user info
$currentUser = [
    'id' => $_SESSION['admin_id'],
    'username' => $_SESSION['admin_username'],
    'full_name' => $_SESSION['admin_full_name'],
    'role' => $_SESSION['admin_role']
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $pdo = getDBConnection();
        
        // Get form data
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo json_encode(['success' => false, 'message' => 'Semua field password harus diisi']);
            exit();
        }
        
        if (strlen($new_password) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password baru minimal 6 karakter']);
            exit();
        }
        
        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'Konfirmasi password tidak cocok']);
            exit();
        }
        
        // Get current user data for verification
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$currentUser['id']]);
        $userData = $stmt->fetch();
        
        if (!$userData) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit();
        }
        
        // Verify current password
        if (!password_verify($current_password, $userData['password'])) {
            echo json_encode(['success' => false, 'message' => 'Password saat ini salah']);
            exit();
        }
        
        // Check if new password is same as current
        if (password_verify($new_password, $userData['password'])) {
            echo json_encode(['success' => false, 'message' => 'Password baru harus berbeda dari password saat ini']);
            exit();
        }
        
        // Update password
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$hashedPassword, $currentUser['id']]);
        
        // Log the activity
        require_once '../config/database.php';
        logAdminActivity($currentUser['id'], 'CHANGE_PASSWORD', 'users', $currentUser['id'], 
            ['password' => '[HIDDEN]'], ['password' => '[HIDDEN]']);
        
        echo json_encode(['success' => true, 'message' => 'Password berhasil diubah']);
        
    } catch (Exception $e) {
        error_log("Password change error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - Admin Hury Asesoris</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/change_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="password-container">
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <div class="password-header">
            <h1><i class="fas fa-key"></i> Ubah Password</h1>
        </div>
        
        <div class="security-info">
            <i class="fas fa-shield-alt"></i>
            <strong>Tips Keamanan:</strong> Gunakan password yang kuat dengan minimal 6 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.
        </div>
        
        <form id="passwordForm">
            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" id="new_password" name="new_password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                    <i class="fas fa-eye"></i>
                </button>
                <div class="password-strength">
                    <div class="strength-text">Kekuatan Password: <span id="strength-text">-</span></div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strength-fill"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Ubah Password
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/change_password.js"></script>
</body>
</html>