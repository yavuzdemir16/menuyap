<?php
require_once '../config/config.php';
require_once '../config/database.php';

if (!isLoggedIn() || isSuperAdmin()) {
    redirect('../login.php');
}

$page_title = 'Restoran Paneli';
$user_id = $_SESSION['user_id'];
$db = getDB();

// İstatistikleri getir
$stats = [];

// Toplam ürün sayısı
$stmt = $db->prepare("SELECT COUNT(*) as count FROM products WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats['products'] = $stmt->fetch()['count'];

// Toplam kategori sayısı
$stmt = $db->prepare("SELECT COUNT(*) as count FROM categories WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats['categories'] = $stmt->fetch()['count'];

// Bu ay tıklama sayısı
$stmt = $db->prepare("
    SELECT COUNT(*) as count 
    FROM menu_clicks 
    WHERE user_id = ? AND MONTH(clicked_at) = MONTH(CURRENT_DATE())
");
$stmt->execute([$user_id]);
$stats['clicks'] = $stmt->fetch()['count'];

// Kullanıcı bilgileri
$stmt = $db->prepare("
    SELECT username, email, restaurant_name, custom_url, subscription_end, package_type 
    FROM users WHERE id = ?
");
$stmt->execute([$user_id]);
$user_info = $stmt->fetch();

// Son aktiviteler
$stmt = $db->prepare("
    SELECT action, description, created_at 
    FROM activity_logs 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->execute([$user_id]);
$activities = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h5 class="mb-0"><?php echo $user_info['restaurant_name']; ?></h5>
                    <small><?php echo $user_info['username']; ?></small>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="categories.php"><i class="fas fa-list"></i> Kategoriler</a></li>
                    <li><a href="products.php"><i class="fas fa-utensils"></i> Ürünler</a></li>
                    <li><a href="menu-design.php"><i class="fas fa-palette"></i> Menü Tasarımı</a></li>
                    <li><a href="social-media.php"><i class="fas fa-share-alt"></i> Sosyal Medya</a></li>
                    <li><a href="qr-generator.php"><i class="fas fa-qrcode"></i> QR Oluştur</a></li>
                    <li><a href="statistics.php"><i class="fas fa-chart-bar"></i> İstatistikler</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> Ayarlar</a></li>
                    <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Çıkış</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Welcome Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">Hoşgeldiniz, <?php echo $user_info['restaurant_name']; ?>!</h4>
                            <p class="text-muted mb-0">
                                Menü URL'niz: 
                                <a href="../menu/<?php echo $user_info['custom_url']; ?>" target="_blank" class="fw-bold">
                                    menuyap.online/<?php echo $user_info['custom_url']; ?>
                                </a>
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-<?php echo strtotime($user_info['subscription_end']) > time() ? 'success' : 'danger'; ?> fs-6">
                                <?php 
                                if ($user_info['subscription_end']) {
                                    $days_left = ceil((strtotime($user_info['subscription_end']) - time()) / 86400);
                                    echo $days_left > 0 ? $days_left . ' gün kaldı' : 'Süresi dolmuş';
                                } else {
                                    echo 'Paket yok';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="mb-1"><?php echo $stats['products']; ?></h3>
                        <p class="text-muted mb-0">Toplam Ürün</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="stat-icon success">
                            <i class="fas fa-list"></i>
                        </div>
                        <h3 class="mb-1"><?php echo $stats['categories']; ?></h3>
                        <p class="text-muted mb-0">Kategori</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="stat-icon info">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="mb-1"><?php echo $stats['clicks']; ?></h3>
                        <p class="text-muted mb-0">Bu Ay Görüntüleme</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="mb-1">4.8</h3>
                        <p class="text-muted mb-0">Ortalama Puan</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Quick Actions -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Hızlı İşlemler</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="products.php?action=add" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Yeni Ürün Ekle
                                </a>
                                <a href="categories.php?action=add" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Yeni Kategori Ekle
                                </a>
                                <a href="qr-generator.php" class="btn btn-info">
                                    <i class="fas fa-qrcode"></i> QR Kod Oluştur
                                </a>
                                <a href="../menu/<?php echo $user_info['custom_url']; ?>" target="_blank" class="btn btn-warning">
                                    <i class="fas fa-external-link-alt"></i> Menüyü Görüntüle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Son Aktiviteler</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($activities)): ?>
                                <p class="text-muted">Henüz aktivite yok</p>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($activities as $activity): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?php echo $activity['action']; ?></strong>
                                                <p class="mb-0 text-muted small"><?php echo $activity['description']; ?></p>
                                            </div>
                                            <small class="text-muted"><?php echo timeAgo($activity['created_at']); ?></small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>