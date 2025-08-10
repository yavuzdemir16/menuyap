<?php
require_once 'config/config.php';
require_once 'config/database.php';

$page_title = 'QR Menü Oluşturma Platformu';

// Paketleri getir
$db = getDB();
$stmt = $db->query("SELECT * FROM packages WHERE is_active = 1 ORDER BY duration_days ASC");
$packages = $stmt->fetchAll();

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="fade-in">Restoranınız İçin <span class="text-warning">QR Menü</span> Oluşturun</h1>
                <p class="lead fade-in">Müşterileriniz telefonlarıyla QR kodu okutarak menünüzü görüntüleyebilsin. Hızlı, güvenli ve modern çözüm.</p>
                <div class="fade-in">
                    <a href="register.php" class="btn btn-warning btn-lg me-3">Hemen Başla</a>
                    <a href="#packages" class="btn btn-outline-light btn-lg">Paketleri Gör</a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="assets/images/hero-qr.png" alt="QR Menü" class="img-fluid fade-in" style="max-width: 400px;">
            </div>
        </div>
    </div>
</section>

<!-- Özellikler -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Neden MenuYap?</h2>
                <p class="lead text-muted">Modern restoranlar için tasarlanmış özellikler</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="stat-icon primary mx-auto">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h5 class="card-title">QR Kod Oluşturma</h5>
                        <p class="card-text">Menünüz için özel QR kod oluşturun ve masalarınıza yerleştirin.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="stat-icon success mx-auto">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="card-title">Mobil Uyumlu</h5>
                        <p class="card-text">Tüm cihazlarda mükemmel görünüm. Müşterileriniz kolayca erişebilir.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="stat-icon info mx-auto">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">İstatistikler</h5>
                        <p class="card-text">Menü görüntüleme sayıları ve müşteri davranışlarını takip edin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Paketler -->
<section id="packages" class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Paketlerimiz</h2>
                <p class="lead text-muted">Size uygun paketi seçin ve hemen başlayın</p>
            </div>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($packages as $index => $package): ?>
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card <?php echo $index === 1 ? 'featured' : ''; ?>">
                    <h4 class="fw-bold"><?php echo $package['name']; ?></h4>
                    <div class="price">
                        <?php echo formatPrice($package['price']); ?>
                        <span class="price-period">/ <?php echo $package['duration_days']; ?> gün</span>
                    </div>
                    <ul class="features-list">
                        <?php 
                        $features = explode(',', $package['features']);
                        foreach ($features as $feature): 
                        ?>
                        <li><?php echo trim($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <form action="payment.php" method="POST">
                        <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                        <button type="submit" class="btn btn-primary btn-lg w-100">Satın Al</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- İstatistikler -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h3 class="counter text-primary" data-target="1250">0</h3>
                    <p class="text-muted">Aktif Restoran</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h3 class="counter text-success" data-target="25000">0</h3>
                    <p class="text-muted">QR Tarama</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h3 class="counter text-warning" data-target="5000">0</h3>
                    <p class="text-muted">Ürün</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="dashboard-card">
                    <h3 class="counter text-info" data-target="98">0</h3>
                    <p class="text-muted">% Memnuniyet</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-6 fw-bold mb-4">Hemen Başlayın!</h2>
                <p class="lead mb-4">30 saniyede kayıt olun, 5 dakikada menünüzü oluşturun.</p>
                <a href="register.php" class="btn btn-warning btn-lg">Ücretsiz Deneyin</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>