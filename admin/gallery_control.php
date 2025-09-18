<?php
require_once '../config/database.php';
require_once 'auth.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $pdo = getDBConnection();
    
    try {
        switch ($_POST['action']) {
            case 'get_gallery':
                $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 12;
                $search = isset($_POST['search']) ? $_POST['search'] : '';
                $category = isset($_POST['category']) ? $_POST['category'] : '';
                $sort = isset($_POST['sort']) ? $_POST['sort'] : 'created_at';
                $order = isset($_POST['order']) ? $_POST['order'] : 'DESC';
                
                $offset = ($page - 1) * $limit;
                
                // Build WHERE clause
                $whereConditions = [];
                $params = [];
                
                if (!empty($search)) {
                    $whereConditions[] = "(title LIKE ? OR description LIKE ?)";
                    $params[] = "%$search%";
                    $params[] = "%$search%";
                }
                
                if (!empty($category)) {
                    $whereConditions[] = "category = ?";
                    $params[] = $category;
                }
                
                $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
                
                // Get total count
                $countQuery = "SELECT COUNT(*) FROM gallery $whereClause";
                $countStmt = $pdo->prepare($countQuery);
                $countStmt->execute($params);
                $total = $countStmt->fetchColumn();
                
                // Get gallery items
                $query = "SELECT * FROM gallery $whereClause ORDER BY $sort $order LIMIT ? OFFSET ?";
                $stmt = $pdo->prepare($query);
                $allParams = array_merge($params, [$limit, $offset]);
                $stmt->execute($allParams);
                $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'data' => $gallery,
                    'pagination' => [
                        'total' => $total,
                        'page' => $page,
                        'limit' => $limit,
                        'pages' => ceil($total / $limit)
                    ]
                ]);
                break;
                
            case 'get_item':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($item) {
                    echo json_encode(['success' => true, 'data' => $item]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
                }
                break;
                
            case 'add_item':
                $title = $_POST['title'];
                $description = $_POST['description'];
                // $category = $_POST['category']; // Gallery table doesn't have category column
                // $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Gallery table doesn't have is_featured column
                
                // Handle image upload
                $image = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/gallery/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $image = $fileName;
                    }
                }
                
                if (empty($image)) {
                    echo json_encode(['success' => false, 'message' => 'Gambar harus diupload']);
                    break;
                }
                
                $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image, created_at) VALUES (?, ?, ?, NOW())");
                
                if ($stmt->execute([$title, $description, $image])) {
                    $galleryId = $pdo->lastInsertId();
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'add_gallery', 'gallery', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $galleryId]);
                    
                    echo json_encode(['success' => true, 'message' => 'Item galeri berhasil ditambahkan']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan item galeri']);
                }
                break;
                
            case 'update_item':
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                // $category = $_POST['category']; // Gallery table doesn't have category column
                // $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Gallery table doesn't have is_featured column
                
                // Handle image upload
                $imageUpdate = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/gallery/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $imageUpdate = ", image = '$fileName'";
                    }
                }
                
                $query = "UPDATE gallery SET title = ?, description = ?, updated_at = NOW() $imageUpdate WHERE id = ?";
                $stmt = $pdo->prepare($query);
                
                if ($stmt->execute([$title, $description, $id])) {
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'update_gallery', 'gallery', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Item galeri berhasil diperbarui']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui item galeri']);
                }
                break;
                
            case 'delete_item':
                $id = $_POST['id'];
                
                // Get image filename to delete file
                $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
                $stmt->execute([$id]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $deleteStmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
                
                if ($deleteStmt->execute([$id])) {
                    // Delete image file
                    if ($item && $item['image']) {
                        $imagePath = '../assets/images/gallery/' . $item['image'];
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                    
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'delete_gallery', 'gallery', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Item galeri berhasil dihapus']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus item galeri']);
                }
                break;
                
            // toggle_featured case removed - gallery table doesn't have is_featured column
                
            case 'bulk_action':
                $action = $_POST['bulk_action'];
                $ids = json_decode($_POST['ids'], true);
                
                if (empty($ids)) {
                    echo json_encode(['success' => false, 'message' => 'Tidak ada item yang dipilih']);
                    break;
                }
                
                $placeholders = str_repeat('?,', count($ids) - 1) . '?';
                
                switch ($action) {
                    // feature/unfeature cases removed - gallery table doesn't have is_featured column
                    case 'delete':
                        // Get images to delete files
                        $selectStmt = $pdo->prepare("SELECT image FROM gallery WHERE id IN ($placeholders)");
                        $selectStmt->execute($ids);
                        $images = $selectStmt->fetchAll(PDO::FETCH_COLUMN);
                        
                        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id IN ($placeholders)");
                        
                        if ($stmt->execute($ids)) {
                            // Delete image files
                            foreach ($images as $image) {
                                if ($image) {
                                    $imagePath = '../assets/images/gallery/' . $image;
                                    if (file_exists($imagePath)) {
                                        unlink($imagePath);
                                    }
                                }
                            }
                        }
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
                        exit;
                }
                
                echo json_encode(['success' => true, 'message' => 'Aksi berhasil dilakukan']);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Control - Hury Accessories Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
<link rel="stylesheet" href="../assets/css/gallery_control.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <div class="admin-container">
        

        <div class="gallery-container">
            <!-- Gallery Header -->
            <div class="gallery-header">
                <h1><i class="fas fa-camera"></i> Manajemen Galeri</h1>
                <p>Kelola koleksi foto dan gambar untuk galeri website</p>
            </div>

            <!-- Controls -->
            <div class="gallery-controls">
                <div class="controls-row">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Cari gambar...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Tambah Gambar
                    </button>
                    <button class="btn btn-secondary" onclick="refreshGallery()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                
                <div class="filter-row">
                    <select id="categoryFilter" class="filter-select">
                        <option value="">Semua Kategori</option>
                        <option value="produk">Produk</option>
                        <option value="proses">Proses Pembuatan</option>
                        <option value="event">Event</option>
                        <option value="testimoni">Testimoni</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    
                    <select id="sortBy" class="filter-select">
                        <option value="created_at">Tanggal Upload</option>
                        <option value="title">Judul</option>
                        <option value="category">Kategori</option>
                    </select>
                    
                    <select id="sortOrder" class="filter-select">
                        <option value="DESC">Terbaru</option>
                        <option value="ASC">Terlama</option>
                    </select>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid">
                <div class="grid-header">
                    <h2 class="grid-title">Koleksi Galeri</h2>
                    <div class="bulk-actions">
                        <select id="bulkAction" class="bulk-select">
                            <option value="">Aksi Massal</option>
                            <option value="feature">Jadikan Unggulan</option>
                            <option value="unfeature">Hapus Unggulan</option>
                            <option value="delete">Hapus</option>
                        </select>
                        <button class="btn btn-secondary btn-sm" onclick="applyBulkAction()">
                            <i class="fas fa-check"></i> Terapkan
                        </button>
                    </div>
                </div>
                
                <div class="gallery-items" id="galleryItems">
                    <div class="loading">
                        <i class="fas fa-spinner fa-spin"></i> Memuat galeri...
                    </div>
                </div>
                
                <div class="pagination" id="pagination">
                    <!-- Pagination will be generated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Item Modal -->
    <div id="itemModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Tambah Gambar</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="itemForm" enctype="multipart/form-data">
                <input type="hidden" id="itemId" name="id">
                
                <div class="form-group">
                    <label class="form-label">Judul *</label>
                    <input type="text" id="itemTitle" name="title" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea id="itemDescription" name="description" class="form-input form-textarea"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select id="itemCategory" name="category" class="form-input" required>
                        <option value="">Pilih Kategori</option>
                        <option value="produk">Produk</option>
                        <option value="proses">Proses Pembuatan</option>
                        <option value="event">Event</option>
                        <option value="testimoni">Testimoni</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gambar *</label>
                    <input type="file" id="itemImage" name="image" class="form-input" accept="image/*">
                    <div id="imagePreview"></div>
                </div>
                
                <div class="form-group">
                    <div class="form-checkbox">
                        <input type="checkbox" id="itemFeatured" name="is_featured">
                        <label for="itemFeatured">Gambar Unggulan</label>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content" style="max-width: 90%; max-height: 90%;">
            <div class="modal-header">
                <h2 class="modal-title" id="imageModalTitle">Preview Gambar</h2>
                <button class="close-btn" onclick="closeImageModal()">&times;</button>
            </div>
            <div style="text-align: center;">
                <img id="imageModalImg" style="max-width: 100%; max-height: 70vh; border-radius: 8px;">
            </div>
        </div>
    </div>

    <script src="../assets/js/gallery_control.js"></script>
</body>
</html>