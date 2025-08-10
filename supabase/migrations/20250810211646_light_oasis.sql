-- QR Menü Sistemi Veritabanı
-- Tüm tabloları ve ilişkileri içerir

CREATE DATABASE IF NOT EXISTS qr_menu_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qr_menu_system;

-- Kullanıcılar tablosu
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    restaurant_name VARCHAR(100),
    custom_url VARCHAR(50) UNIQUE,
    logo_path VARCHAR(255),
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'inactive',
    role ENUM('restaurant', 'super_admin') DEFAULT 'restaurant',
    verification_code VARCHAR(6),
    is_verified BOOLEAN DEFAULT FALSE,
    subscription_start DATE,
    subscription_end DATE,
    package_type ENUM('30', '180', '360') DEFAULT '30',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Paketler tablosu
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    duration_days INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    features TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ödemeler tablosu
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    package_id INT,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    transaction_id VARCHAR(100),
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id)
);

-- Kategoriler tablosu
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ürünler tablosu
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(255),
    rating DECIMAL(2,1) DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Menü tasarım ayarları
CREATE TABLE menu_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    font_family VARCHAR(50) DEFAULT 'Arial',
    font_color VARCHAR(7) DEFAULT '#000000',
    background_type ENUM('color', 'gradient') DEFAULT 'color',
    background_color VARCHAR(7) DEFAULT '#ffffff',
    gradient_start VARCHAR(7) DEFAULT '#ffffff',
    gradient_end VARCHAR(7) DEFAULT '#f0f0f0',
    header_color VARCHAR(7) DEFAULT '#333333',
    card_color VARCHAR(7) DEFAULT '#ffffff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sosyal medya hesapları
CREATE TABLE social_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    platform ENUM('facebook', 'instagram', 'twitter', 'whatsapp', 'website') NOT NULL,
    url VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- QR Menü tıklamaları
CREATE TABLE menu_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Aktivite logları
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- E-posta şablonları
CREATE TABLE email_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    subject VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sistem ayarları
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Varsayılan paketleri ekle
INSERT INTO packages (name, duration_days, price, features) VALUES
('Aylık Paket', 30, 99.00, 'Sınırsız ürün, QR kod, Temel destek'),
('6 Aylık Paket', 180, 499.00, 'Sınırsız ürün, QR kod, Öncelikli destek, İstatistikler'),
('Yıllık Paket', 360, 899.00, 'Sınırsız ürün, QR kod, Premium destek, Gelişmiş istatistikler, Özel tasarım');

-- Varsayılan süper admin kullanıcısı
INSERT INTO users (username, email, password, role, is_verified, status) VALUES
('superadmin', 'admin@menuyap.online', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', TRUE, 'active');

-- E-posta şablonları
INSERT INTO email_templates (name, subject, body) VALUES
('welcome', 'Hoşgeldiniz - MenuYap', 'Merhaba {name}, MenuYap ailesine hoşgeldiniz! Doğrulama kodunuz: {code}'),
('expiry_warning', 'Üyelik Süreniz Bitiyor', 'Merhaba {name}, üyelik süreniz {days} gün içinde bitecek.'),
('expired', 'Üyeliğiniz Sona Erdi', 'Merhaba {name}, üyeliğiniz sona ermiştir. Yenilemek için giriş yapın.');

-- Sistem ayarları
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', 'MenuYap', 'Site adı'),
('site_email', 'info@menuyap.online', 'Site e-posta adresi'),
('paytr_merchant_id', '', 'PayTR Merchant ID'),
('paytr_merchant_key', '', 'PayTR Merchant Key'),
('paytr_merchant_salt', '', 'PayTR Merchant Salt');