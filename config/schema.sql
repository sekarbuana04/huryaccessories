-- Create database
CREATE DATABASE IF NOT EXISTS huryaccessories_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE huryaccessories_db;

-- Create users table for admin authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Create products table for catalog management
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price VARCHAR(50) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    category ENUM('gelang', 'kalung', 'cincin', 'bros', 'anting', 'tasbih', 'mala') NOT NULL,
    is_featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    stock_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default admin user
-- Password: Adm1n#2025 (hashed with password_hash)
INSERT INTO users (username, password, email, full_name, role) VALUES 
('admin', '$2y$10$L9sRG/rHY6AlidpZ9fRSlObk0VuKFpdg.v3Zv3LpcbH0VEDK1or6.', 'admin@huryaccessories.com', 'Administrator', 'admin');

-- Insert sample products (based on existing catalog)
INSERT INTO products (name, price, description, image, category, is_featured, stock_quantity) VALUES
('Gelang Pandu', 'Rp 15.000', 'Gelang jenitri sederhana dengan desain minimalis, cocok dipakai sehari-hari.', 'assets/images/gelang1.png', 'gelang', TRUE, 50),
('Gelang Archie', 'Rp 20.000', 'Gelang jenitri dengan model tali yang kuat dan nyaman.', 'assets/images/gelang2.png', 'gelang', FALSE, 30),
('Kalung Kebayan', 'Rp 110.000', 'Kalung jenitri klasik dengan desain tradisional yang penuh karakter.', 'assets/images/rosario1.jpg', 'kalung', TRUE, 20),
('Kalung Choker Mutiara', 'Rp 1.650.000', 'Kalung choker dengan rangkaian mutiara air tawar pilihan.', 'assets/images/rosario2.jpg', 'kalung', TRUE, 5),
('Cincin Tunangan Berlian', 'Rp 4.500.000', 'Cincin tunangan dengan berlian 0.7 karat kualitas VVS.', 'assets/images/tasbih1.png', 'cincin', TRUE, 3),
('Cincin Ruby Merah', 'Rp 2.850.000', 'Cincin emas dengan batu ruby merah yang elegan.', 'assets/images/tasbih2.png', 'cincin', FALSE, 8),
('Bros Bunga Kristal', 'Rp 850.000', 'Bros berbentuk bunga dengan kristal Swarovski.', 'assets/images/bros1.png', 'bros', FALSE, 15),
('Bros Kupu-kupu', 'Rp 750.000', 'Bros berbentuk kupu-kupu dengan detail yang menawan.', 'assets/images/bros2.png', 'bros', FALSE, 12),
('Anting Gantung Berlian', 'Rp 2.250.000', 'Anting gantung dengan aksen berlian yang elegan.', 'assets/images/mala1.png', 'anting', FALSE, 10),
('Anting Studs Mutiara', 'Rp 1.150.000', 'Anting studs dengan mutiara Tahiti hitam yang eksotis.', 'assets/images/mala2.png', 'anting', FALSE, 18);

-- Create gallery table for image gallery management
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    image VARCHAR(255) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert sample gallery items
INSERT INTO gallery (title, subtitle, image, description, display_order) VALUES
('Koleksi Gelang Jenitri', 'Gelang tradisional dengan sentuhan modern', 'assets/images/gelang1.png', 'Koleksi gelang jenitri dengan berbagai model dan ukuran', 1),
('Kalung Rosario Klasik', 'Kalung dengan desain timeless', 'assets/images/rosario1.jpg', 'Kalung rosario dengan bahan berkualitas tinggi', 2),
('Tasbih Premium', 'Tasbih untuk kebutuhan spiritual', 'assets/images/tasbih1.png', 'Tasbih dengan bahan pilihan dan finishing sempurna', 3),
('Bros Eksklusif', 'Bros dengan desain unik dan elegan', 'assets/images/bros1.png', 'Koleksi bros dengan berbagai motif dan bahan premium', 4);

-- Create admin activity log table
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create indexes for better performance
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_products_featured ON products(is_featured);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_gallery_active ON gallery(is_active);
CREATE INDEX idx_gallery_order ON gallery(display_order);
CREATE INDEX idx_admin_logs_user_id ON admin_logs(user_id);
CREATE INDEX idx_admin_logs_created_at ON admin_logs(created_at);

-- Note: The default admin password is 'Adm1n#2025'
-- You can change it after first login through the admin panel