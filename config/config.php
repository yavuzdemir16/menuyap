<?php
// Genel konfigürasyon ayarları
session_start();

// Site ayarları
define('SITE_URL', 'https://menuyap.online');
define('SITE_NAME', 'MenuYap');
define('SITE_EMAIL', 'info@menuyap.online');

// Dosya yolları
define('UPLOAD_PATH', 'uploads/');
define('LOGO_PATH', UPLOAD_PATH . 'logos/');
define('PRODUCT_PATH', UPLOAD_PATH . 'products/');

// E-posta ayarları
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');

// PayTR ayarları (gerçek değerlerle değiştirin)
define('PAYTR_MERCHANT_ID', 'your-merchant-id');
define('PAYTR_MERCHANT_KEY', 'your-merchant-key');
define('PAYTR_MERCHANT_SALT', 'your-merchant-salt');

// Güvenlik
define('JWT_SECRET', 'your-jwt-secret-key-here');
define('ENCRYPTION_KEY', 'your-encryption-key-here');

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Europe/Istanbul');

// Yardımcı fonksiyonlar
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateRandomCode($length = 6) {
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isSuperAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function formatPrice($price) {
    return number_format($price, 2, ',', '.') . ' ₺';
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'Az önce';
    if ($time < 3600) return floor($time/60) . ' dakika önce';
    if ($time < 86400) return floor($time/3600) . ' saat önce';
    if ($time < 2592000) return floor($time/86400) . ' gün önce';
    if ($time < 31536000) return floor($time/2592000) . ' ay önce';
    
    return floor($time/31536000) . ' yıl önce';
}
?>