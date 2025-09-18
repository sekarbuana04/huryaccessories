<?php
require_once '../config/database.php';
require_once 'auth.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $pdo = getDBConnection();
        switch ($_POST['action']) {
            case 'get_products':
                $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
                $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;
                $search = isset($_POST['search']) ? $_POST['search'] : '';
                $category = isset($_POST['category']) ? $_POST['category'] : '';
                $status = isset($_POST['status']) ? $_POST['status'] : '';
                $sort = isset($_POST['sort']) ? $_POST['sort'] : 'created_at';
                $order = isset($_POST['order']) ? $_POST['order'] : 'DESC';
                
                $offset = ($page - 1) * $limit;
                
                // Build WHERE clause
                $whereConditions = [];
                $params = [];
                
                if (!empty($search)) {
                    $whereConditions[] = "(name LIKE ? OR description LIKE ?)";
                    $params[] = "%$search%";
                    $params[] = "%$search%";
                }
                
                if (!empty($category)) {
                    $whereConditions[] = "category = ?";
                    $params[] = $category;
                }
                
                if ($status !== '') {
                    $whereConditions[] = "is_active = ?";
                    $params[] = $status;
                }
                
                $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
                
                // Get total count
                $countQuery = "SELECT COUNT(*) as total FROM products $whereClause";
                $countStmt = $pdo->prepare($countQuery);
                $countStmt->execute($params);
                $total = $countStmt->fetchColumn();
                
                // Get products
                $query = "SELECT * FROM products $whereClause ORDER BY $sort $order LIMIT ? OFFSET ?";
                $stmt = $pdo->prepare($query);
                $allParams = array_merge($params, [$limit, $offset]);
                $stmt->execute($allParams);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode([
                    'success' => true,
                    'data' => $products,
                    'pagination' => [
                        'total' => $total,
                        'page' => $page,
                        'limit' => $limit,
                        'pages' => ceil($total / $limit)
                    ]
                ]);
                break;
                
            case 'get_product':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    echo json_encode(['success' => true, 'data' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
                }
                break;
                
            case 'add_product':
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $stock_quantity = $_POST['stock'];
                $category = $_POST['category'];
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Handle image upload
                $image = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $image = $fileName;
                    }
                }
                
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock_quantity, category, image, is_featured, is_active, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                
                if ($stmt->execute([$name, $description, $price, $stock_quantity, $category, $image, $is_featured, $is_active])) {
                    $productId = $pdo->lastInsertId();
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'add_product', 'products', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $productId]);
                    
                    echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan produk']);
                }
                break;
                
            case 'update_product':
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $stock_quantity = $_POST['stock'];
                $category = $_POST['category'];
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Handle image upload
                $imageUpdate = '';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = '../assets/images/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $imageUpdate = ", image = '$fileName'";
                    }
                }
                
                if ($imageUpdate) {
                    $query = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, category = ?, is_featured = ?, is_active = ?, image = ?, updated_at = NOW() WHERE id = ?";
                    $stmt = $pdo->prepare($query);
                    $params = [$name, $description, $price, $stock_quantity, $category, $is_featured, $is_active, $fileName, $id];
                } else {
                    $query = "UPDATE products SET name = ?, description = ?, price = ?, stock_quantity = ?, category = ?, is_featured = ?, is_active = ?, updated_at = NOW() WHERE id = ?";
                    $stmt = $pdo->prepare($query);
                    $params = [$name, $description, $price, $stock_quantity, $category, $is_featured, $is_active, $id];
                }
                
                if ($stmt->execute($params)) {
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'update_product', 'products', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Produk berhasil diperbarui']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui produk']);
                }
                break;
                
            case 'delete_product':
                $id = $_POST['id'];
                
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                
                if ($stmt->execute([$id])) {
                    // Log activity
                    $logStmt = $pdo->prepare("INSERT INTO admin_logs (user_id, action, table_name, record_id, created_at) VALUES (?, 'delete_product', 'products', ?, NOW())");
                    $logStmt->execute([$_SESSION['admin_id'], $id]);
                    
                    echo json_encode(['success' => true, 'message' => 'Produk berhasil dihapus']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus produk']);
                }
                break;
                
            case 'toggle_featured':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE products SET is_featured = NOT is_featured WHERE id = ?");
                
                if ($stmt->execute([$id])) {
                    echo json_encode(['success' => true, 'message' => 'Status unggulan berhasil diubah']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengubah status unggulan']);
                }
                break;
                
            case 'toggle_active':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("UPDATE products SET is_active = NOT is_active WHERE id = ?");
                
                if ($stmt->execute([$id])) {
                    echo json_encode(['success' => true, 'message' => 'Status aktif berhasil diubah']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengubah status aktif']);
                }
                break;
                
            case 'bulk_action':
                $action = $_POST['bulk_action'];
                $ids = json_decode($_POST['ids'], true);
                
                if (empty($ids)) {
                    echo json_encode(['success' => false, 'message' => 'Tidak ada produk yang dipilih']);
                    break;
                }
                
                $placeholders = str_repeat('?,', count($ids) - 1) . '?';
                
                switch ($action) {
                    case 'activate':
                        $stmt = $pdo->prepare("UPDATE products SET is_active = 1 WHERE id IN ($placeholders)");
                        break;
                    case 'deactivate':
                        $stmt = $pdo->prepare("UPDATE products SET is_active = 0 WHERE id IN ($placeholders)");
                        break;
                    case 'feature':
                        $stmt = $pdo->prepare("UPDATE products SET is_featured = 1 WHERE id IN ($placeholders)");
                        break;
                    case 'unfeature':
                        $stmt = $pdo->prepare("UPDATE products SET is_featured = 0 WHERE id IN ($placeholders)");
                        break;
                    case 'delete':
                        $stmt = $pdo->prepare("DELETE FROM products WHERE id IN ($placeholders)");
                        break;
                    default:
                        echo json_encode(['success' => false, 'message' => 'Aksi tidak valid']);
                        exit;
                }
                
                if ($stmt->execute($ids)) {
                    echo json_encode(['success' => true, 'message' => 'Aksi berhasil dilakukan']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal melakukan aksi']);
                }
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
    <title>Catalog Control - Hury Accessories Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/catalog_control.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        

        <div class="catalog-container">
            <!-- Catalog Header -->
            <div class="catalog-header">
                <h1><i class="fas fa-shopping-bag"></i> Manajemen Katalog Produk</h1>
                <p>Kelola semua produk dalam katalog toko Anda</p>
            </div>

            <!-- Controls -->
            <div class="catalog-controls">
                <div class="controls-row">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Cari produk...">
                        <i class="fas fa-search"></i>
                    </div>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </button>
                    <button class="btn btn-secondary" onclick="refreshProducts()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                
                <div class="filter-row">
                    <select id="categoryFilter" class="filter-select">
                        <option value="">Semua Kategori</option>
                        <option value="gelang">Gelang</option>
                        <option value="bros">Bros</option>
                        <option value="mala">Mala</option>
                        <option value="rosario">Rosario</option>
                        <option value="tasbih">Tasbih</option>
                        <option value="kalung">Kalung</option>
                        <option value="cincin">Cincin</option>
                    </select>
                    
                    <select id="statusFilter" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                    
                    <select id="sortBy" class="filter-select">
                        <option value="created_at">Tanggal Dibuat</option>
                        <option value="name">Nama</option>
                        <option value="price">Harga</option>
                        <option value="stock">Stok</option>
                    </select>
                    
                    <select id="sortOrder" class="filter-select">
                        <option value="DESC">Terbaru</option>
                        <option value="ASC">Terlama</option>
                    </select>
                </div>
            </div>

            <!-- Products Table -->
            <div class="products-table">
                <div class="table-header">
                    <h2 class="table-title">Daftar Produk</h2>
                    <div class="bulk-actions">
                        <select id="bulkAction" class="bulk-select">
                            <option value="">Aksi Massal</option>
                            <option value="activate">Aktifkan</option>
                            <option value="deactivate">Nonaktifkan</option>
                            <option value="feature">Jadikan Unggulan</option>
                            <option value="unfeature">Hapus Unggulan</option>
                            <option value="delete">Hapus</option>
                        </select>
                        <button class="btn btn-secondary btn-sm" onclick="applyBulkAction()">
                            <i class="fas fa-check"></i> Terapkan
                        </button>
                    </div>
                </div>
                
                <div class="table-container">
                    <table id="productsTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Unggulan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <tr>
                                <td colspan="9" class="loading">
                                    <i class="fas fa-spinner fa-spin"></i> Memuat produk...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination" id="pagination">
                    <!-- Pagination will be generated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Tambah Produk</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="id">
                
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" id="productName" name="name" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea id="productDescription" name="description" class="form-input form-textarea"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kategori *</label>
                    <select id="productCategory" name="category" class="form-input" required>
                        <option value="">Pilih Kategori</option>
                        <option value="gelang">Gelang</option>
                        <option value="bros">Bros</option>
                        <option value="mala">Mala</option>
                        <option value="rosario">Rosario</option>
                        <option value="tasbih">Tasbih</option>
                        <option value="kalung">Kalung</option>
                        <option value="cincin">Cincin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Harga (Rp) *</label>
                    <input type="number" id="productPrice" name="price" class="form-input" min="0" step="1000" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Stok *</label>
                    <input type="number" id="productStock" name="stock" class="form-input" min="0" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gambar Produk</label>
                    <input type="file" id="productImage" name="image" class="form-input" accept="image/*">
                </div>
                
                <div class="form-group">
                    <div class="form-checkbox">
                        <input type="checkbox" id="productFeatured" name="is_featured">
                        <label for="productFeatured">Produk Unggulan</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-checkbox">
                        <input type="checkbox" id="productActive" name="is_active" checked>
                        <label for="productActive">Aktif</label>
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

    <script src="../assets/js/catalog_control.js"></script>
</body>
</html>