<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Function to verify login credentials
function verifyLogin($username, $password) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("SELECT id, username, password, full_name, role, is_active FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            // Log login activity
            logAdminActivity($user['id'], 'LOGIN', 'users', $user['id'], null, null);
            
            return $user;
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}

// Function to create user session
function createUserSession($user, $rememberMe = false) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_full_name'] = $user['full_name'];
    $_SESSION['admin_role'] = $user['role'];
    $_SESSION['login_time'] = time();
    
    // Set remember me cookie if requested
    if ($rememberMe) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        
        setcookie('remember_token', $token, $expiry, '/', '', false, true);
        
        // Store token in database
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, remember_token_expires = FROM_UNIXTIME(?) WHERE id = ?");
            $stmt->execute([$token, $expiry, $user['id']]);
        } catch (Exception $e) {
            error_log("Remember token error: " . $e->getMessage());
        }
    }
}

// Function to check if user is logged in
function isLoggedIn() {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return true;
    }
    
    // Check remember me token
    if (isset($_COOKIE['remember_token'])) {
        return validateRememberToken($_COOKIE['remember_token']);
    }
    
    return false;
}

// Function to validate remember me token
function validateRememberToken($token) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, username, full_name, role FROM users WHERE remember_token = ? AND remember_token_expires > NOW() AND is_active = 1");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            createUserSession($user, false);
            return true;
        }
    } catch (Exception $e) {
        error_log("Remember token validation error: " . $e->getMessage());
    }
    
    return false;
}

// Function to logout user
function logout() {
    if (isset($_SESSION['admin_id'])) {
        logAdminActivity($_SESSION['admin_id'], 'LOGOUT', 'users', $_SESSION['admin_id'], null, null);
    }
    
    // Clear remember token
    if (isset($_COOKIE['remember_token'])) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("UPDATE users SET remember_token = NULL, remember_token_expires = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
        } catch (Exception $e) {
            error_log("Logout token clear error: " . $e->getMessage());
        }
        
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    }
    
    // Destroy session
    session_destroy();
    session_start();
}

// Function to log admin activities
function logAdminActivity($userId, $action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("
            INSERT INTO admin_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $action,
            $tableName,
            $recordId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        error_log("Activity log error: " . $e->getMessage());
    }
}

// Function to require admin login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

// Handle AJAX login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    header('Content-Type: application/json');
    
    try {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']) && $_POST['remember_me'] === '1';
        
        if (empty($username) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'Username dan password harus diisi'
            ]);
            exit();
        }
        
        $user = verifyLogin($username, $password);
        
        if ($user) {
            createUserSession($user, $rememberMe);
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil! Selamat datang, ' . $user['full_name'],
                'redirect' => 'admin/dashboard.php'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Username atau password salah'
            ]);
        }
    } catch (Exception $e) {
        error_log("Login process error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
        ]);
    }
    exit();
}

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header('Location: ../index.php');
    exit();
}
?>