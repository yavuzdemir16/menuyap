<?php
require_once 'config/config.php';
require_once 'config/database.php';

if (isLoggedIn()) {
    $db = getDB();
    
    // Çıkış aktivitesi kaydet
    $stmt = $db->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address) 
        VALUES (?, 'logout', 'Kullanıcı çıkış yaptı', ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR']]);
}

// Session'ı temizle
session_destroy();

// Ana sayfaya yönlendir
redirect('index.php');
?>