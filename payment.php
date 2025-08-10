<?php
require_once 'config/config.php';
require_once 'config/database.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$page_title = 'Ödeme';
$user_id = $_SESSION['user_id'];

if (!isset($_POST['package_id'])) {
    redirect('index.php');
}

$package_id = (int)$_POST['package_id'];
$db = getDB();

// Paket bilgilerini getir
$stmt = $db->prepare("SELECT * FROM packages WHERE id = ? AND is_active = 1");
$stmt->execute([$package_id]);
$package = $stmt->fetch();

if (!$package) {
    redirect('index.php');
}

// Kullanıcı bilgilerini getir
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// PayTR ödeme formu hazırlama
$merchant_id = PAYTR_MERCHANT_ID;
$merchant_key = PAYTR_MERCHANT_KEY;
$merchant_salt = PAYTR_MERCHANT_SALT;

$email = $user['email'];
$payment_amount = $package['price'] * 100; // Kuruş cinsinden
$merchant_oid = "ORDER_" . $user_id . "_" . time();
$user_name = $user['username'];
$user_address = "Türkiye";
$user_phone = $user['phone'];

$user_basket = base64_encode(json_encode([
    [$package['name'], $package['price'], 1]
]));

$paytr_token = base64_encode(hash_hmac('sha256', $merchant_id . $user['ip'] . $merchant_oid . $email . $payment_amount . $user_basket . 'N' . 'N' . 'TR', $merchant_key, true));

$post_vals = [
    'merchant_id' => $merchant_id,
    'user_ip' => $_SERVER['REMOTE_ADDR'],
    'merchant_oid' => $merchant_oid,
    'email' => $email,
    'payment_amount' => $payment_amount,
    'paytr_token' => $paytr_token,
    'user_basket' => $user_basket,
    'debug_on' => 1,
    'no_installment' => 'N',
    'max_installment' => 'N',
    'user_name' => $user_name,
    'user_address' => $user_address,
    'user_phone' => $user_phone,
    'merchant_ok_url' => SITE_URL . '/payment-success.php',
    'merchant_fail_url' => SITE_URL . '/payment-fail.php',
    'timeout_limit' => 30,
    'currency' => 'TL',
    'test_mode' => 1 // Canlıya alırken 0 yapın
];

// Ödeme kaydı oluştur
$stmt = $db->prepare("
    INSERT INTO payments (user_id, package_id, amount, transaction_id, status) 
    VALUES (?, ?, ?, ?, 'pending')
");
$stmt->execute([$user_id, $package_id, $package['price'], $merchant_oid]);

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h2 class="mb-0">Ödeme</h2>
                    <p class="text-muted mt-2">Güvenli ödeme sayfası</p>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Sipariş Özeti</h4>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5><?php echo $package['name']; ?></h5>
                                    <p class="text-muted"><?php echo $package['duration_days']; ?> günlük paket</p>
                                    <ul class="list-unstyled">
                                        <?php 
                                        $features = explode(',', $package['features']);
                                        foreach ($features as $feature): 
                                        ?>
                                        <li><i class="fas fa-check text-success me-2"></i><?php echo trim($feature); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <strong>Toplam:</strong>
                                        <strong class="text-primary"><?php echo formatPrice($package['price']); ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Ödeme Bilgileri</h4>
                            <div class="alert alert-info">
                                <i class="fas fa-shield-alt"></i>
                                Ödemeniz PayTR güvenli ödeme sistemi ile işlenir
                            </div>
                            
                            <form action="https://www.paytr.com/odeme/guvenli/<?php echo $merchant_id; ?>" method="post" id="paymentForm">
                                <?php foreach ($post_vals as $key => $value): ?>
                                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
                                <?php endforeach; ?>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-credit-card"></i>
                                        <?php echo formatPrice($package['price']); ?> Öde
                                    </button>
                                    <a href="index.php" class="btn btn-outline-secondary">İptal</a>
                                </div>
                            </form>
                            
                            <div class="mt-4">
                                <small class="text-muted">
                                    <i class="fas fa-lock"></i>
                                    Bu sayfa SSL ile korunmaktadır. Kart bilgileriniz güvenle şifrelenir.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Ödeme formunu otomatik gönder (isteğe bağlı)
// document.getElementById('paymentForm').submit();
</script>

<?php include 'includes/footer.php'; ?>