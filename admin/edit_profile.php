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
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $full_name = trim($_POST['full_name'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($username) || empty($full_name)) {
            echo json_encode(['success' => false, 'message' => 'Username dan nama lengkap harus diisi']);
            exit();
        }
        
        // Check if username already exists (except current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $currentUser['id']]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username sudah digunakan']);
            exit();
        }
        
        // Get current user data for verification and logging
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$currentUser['id']]);
        $userData = $stmt->fetch();
        
        if (!$userData) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit();
        }
        
        // If changing password, verify current password
        if (!empty($new_password)) {
            if (empty($current_password)) {
                echo json_encode(['success' => false, 'message' => 'Password saat ini harus diisi untuk mengubah password']);
                exit();
            }
            
            if (!password_verify($current_password, $userData['password'])) {
                echo json_encode(['success' => false, 'message' => 'Password saat ini salah']);
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
        }
        
        // Prepare update query
        $updateFields = [];
        $updateValues = [];
        $oldValues = [];
        $newValues = [];
        
        if ($username !== $userData['username']) {
            $updateFields[] = 'username = ?';
            $updateValues[] = $username;
            $oldValues['username'] = $userData['username'];
            $newValues['username'] = $username;
        }
        
        if ($email !== $userData['email']) {
            $updateFields[] = 'email = ?';
            $updateValues[] = $email;
            $oldValues['email'] = $userData['email'];
            $newValues['email'] = $email;
        }
        
        if ($full_name !== $userData['full_name']) {
            $updateFields[] = 'full_name = ?';
            $updateValues[] = $full_name;
            $oldValues['full_name'] = $userData['full_name'];
            $newValues['full_name'] = $full_name;
        }
        
        if (!empty($new_password)) {
            $updateFields[] = 'password = ?';
            $updateValues[] = password_hash($new_password, PASSWORD_DEFAULT);
            $oldValues['password'] = '[HIDDEN]';
            $newValues['password'] = '[HIDDEN]';
        }
        
        if (empty($updateFields)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan yang dilakukan']);
            exit();
        }
        
        // Add updated_at field
        $updateFields[] = 'updated_at = NOW()';
        $updateValues[] = $currentUser['id'];
        
        // Execute update
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateValues);
        
        // Update session data
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_full_name'] = $full_name;
        
        // Log activity
        logAdminActivity($currentUser['id'], 'UPDATE_PROFILE', 'users', $currentUser['id'], $oldValues, $newValues);
        
        echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        
    } catch (Exception $e) {
        error_log("Profile update error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
    }
    exit();
}

// Get current user data from database
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT username, email, full_name, role, created_at, last_login FROM users WHERE id = ?");
    $stmt->execute([$currentUser['id']]);
    $userProfile = $stmt->fetch();
} catch (Exception $e) {
    $userProfile = null;
    $error = "Gagal memuat data profil";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Admin Hury Asesoris</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/edit_profile_only.css">
</head>
<body>
    <a href="dashboard.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    
    <div class="profile-container">
        <div class="profile-header">
            <h1><i class="fas fa-user-edit"></i> Edit Profil</h1>
            <?php if ($userProfile): ?>
            <div class="profile-info">
                <p><strong>Role:</strong> <?php echo ucfirst($userProfile['role']); ?></p>
                <p><strong>Bergabung:</strong> <?php echo date('d/m/Y', strtotime($userProfile['created_at'])); ?></p>
                <?php if ($userProfile['last_login']): ?>
                <p><strong>Login Terakhir:</strong> <?php echo date('d/m/Y H:i', strtotime($userProfile['last_login'])); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="profile-form">
            <div id="alert-container"></div>
            
            <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <?php if ($userProfile): ?>
            <form id="profileForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userProfile['username']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="full_name">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userProfile['full_name']); ?>" required>
                </div>
                
                <div class="password-section">
                    <h3>Ubah Password</h3>
                    <p style="color: #666; margin-bottom: 20px;">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../assets/js/edit_profile.js"></script>
</body>
</html>