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
        
        // Get current user data for logging
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$currentUser['id']]);
        $userData = $stmt->fetch();
        
        if (!$userData) {
            echo json_encode(['success' => false, 'message' => 'User tidak ditemukan']);
            exit();
        }
        
        // Prepare update data
        $updateFields = [];
        $updateValues = [];
        $oldValues = [];
        $newValues = [];
        
        // Check what fields are being updated
        if ($username !== $userData['username']) {
            $updateFields[] = 'username = ?';
            $updateValues[] = $username;
            $oldValues['username'] = $userData['username'];
            $newValues['username'] = $username;
        }
        
        if ($email !== ($userData['email'] ?? '')) {
            $updateFields[] = 'email = ?';
            $updateValues[] = $email;
            $oldValues['email'] = $userData['email'] ?? '';
            $newValues['email'] = $email;
        }
        
        if ($full_name !== $userData['full_name']) {
            $updateFields[] = 'full_name = ?';
            $updateValues[] = $full_name;
            $oldValues['full_name'] = $userData['full_name'];
            $newValues['full_name'] = $full_name;
        }
        
        if (empty($updateFields)) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan yang dilakukan']);
            exit();
        }
        
        // Update user data
        $updateFields[] = 'updated_at = NOW()';
        $updateValues[] = $currentUser['id'];
        
        $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateValues);
        
        // Update session data
        $_SESSION['admin_username'] = $username;
        $_SESSION['admin_full_name'] = $full_name;
        
        // Log the activity
        require_once '../config/database.php';
        logAdminActivity($currentUser['id'], 'UPDATE_PROFILE', 'users', $currentUser['id'], $oldValues, $newValues);
        
        echo json_encode(['success' => true, 'message' => 'Profil berhasil diperbarui']);
        
    } catch (Exception $e) {
        error_log("Profile update error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
    }
    exit();
}

// Get user profile data for display
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$currentUser['id']]);
    $userProfile = $stmt->fetch();
} catch (Exception $e) {
    $userProfile = null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Admin Hury Asesoris</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/edit_profile_only.css">
</head>
<body>
    <div class="profile-container">
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        
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
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
            <?php else: ?>
            <p style="text-align: center; color: #e74c3c;">Data profil tidak dapat dimuat.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/edit_profile_only.js"></script>
</body>
</html>