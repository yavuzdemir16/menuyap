<?php
require_once 'config/config.php';
require_once 'config/database.php';

$page_title = 'Hesap Doğrulama';

if (!isset($_SESSION['temp_user_id'])) {
    redirect('register.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_code = sanitize($_POST['verification_code']);
    
    if (!empty($verification_code)) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, username, email, restaurant_name 
            FROM users 
            WHERE id = ? AND verification_code = ? AND is_verified = 0
        ");
        $stmt->execute([$_SESSION['temp_user_id'], $verification_code]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Hesabı doğrula
            $stmt = $db->prepare("
                UPDATE users 
                SET is_verified = 1, status = 'active', verification_code = NULL 
                WHERE id = ?
            ");
            $stmt->execute([$user['id']]);
            
            // Session'a kullanıcı bilgilerini kaydet
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'restaurant';
            
            // Temp user id'yi temizle
            unset($_SESSION['temp_user_id']);
            
            // Aktivite kaydı
            $stmt = $db->prepare("
                INSERT INTO activity_logs (user_id, action, description, ip_address) 
                VALUES (?, 'account_verified', 'Hesap doğrulandı', ?)
            ");
            $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR']]);
            
            $_SESSION['success'] = 'Hesabınız başarıyla doğrulandı! Hoşgeldiniz.';
            redirect('restaurant/dashboard.php');
        } else {
            $error = 'Doğrulama kodu hatalı veya geçersiz';
        }
    } else {
        $error = 'Doğrulama kodu gerekli';
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h2 class="mb-0">Hesap Doğrulama</h2>
                    <p class="text-muted mt-2">E-postanıza gönderilen kodu girin</p>
                </div>
                <div class="card-body p-5">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        E-postanıza 6 haneli doğrulama kodu gönderildi. Lütfen kontrol edin.
                    </div>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="verification_code" class="form-label">Doğrulama Kodu</label>
                            <input type="text" class="form-control text-center" id="verification_code" 
                                   name="verification_code" maxlength="6" pattern="[0-9]{6}" 
                                   style="font-size: 1.5rem; letter-spacing: 0.5rem;" required>
                            <div class="invalid-feedback">6 haneli doğrulama kodu gerekli</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-loading">
                            Hesabı Doğrula
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="text-muted">Kod gelmedi mi?</p>
                        <button class="btn btn-outline-primary" onclick="resendCode()">
                            Tekrar Gönder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Doğrulama kodu input'una otomatik odaklanma
document.getElementById('verification_code').focus();

// Sadece rakam girişine izin ver
document.getElementById('verification_code').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Kod tekrar gönderme
function resendCode() {
    // AJAX ile kod tekrar gönderme işlemi
    showNotification('Doğrulama kodu tekrar gönderildi', 'success');
}
</script>

<?php include 'includes/footer.php'; ?>