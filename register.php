<?php
require_once 'config/config.php';
require_once 'config/database.php';

$page_title = 'Kayıt Ol';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $restaurant_name = sanitize($_POST['restaurant_name']);
    $custom_url = sanitize($_POST['custom_url']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validasyon
    if (empty($username)) $errors[] = 'Kullanıcı adı gerekli';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli e-posta gerekli';
    if (empty($phone)) $errors[] = 'Telefon numarası gerekli';
    if (empty($restaurant_name)) $errors[] = 'Restoran adı gerekli';
    if (empty($custom_url)) $errors[] = 'Özel URL gerekli';
    if (strlen($password) < 6) $errors[] = 'Şifre en az 6 karakter olmalı';
    if ($password !== $confirm_password) $errors[] = 'Şifreler eşleşmiyor';
    
    // URL kontrolü
    if (!preg_match('/^[a-zA-Z0-9-]+$/', $custom_url)) {
        $errors[] = 'URL sadece harf, rakam ve tire içerebilir';
    }
    
    if (empty($errors)) {
        $db = getDB();
        
        // Kullanıcı adı ve e-posta kontrolü
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR custom_url = ?");
        $stmt->execute([$username, $email, $custom_url]);
        
        if ($stmt->fetch()) {
            $errors[] = 'Bu kullanıcı adı, e-posta veya URL zaten kullanımda';
        } else {
            // Doğrulama kodu oluştur
            $verification_code = generateRandomCode();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Kullanıcıyı kaydet
            $stmt = $db->prepare("
                INSERT INTO users (username, email, phone, restaurant_name, custom_url, password, verification_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([$username, $email, $phone, $restaurant_name, $custom_url, $hashed_password, $verification_code])) {
                $user_id = $db->lastInsertId();
                
                // Varsayılan menü ayarları
                $stmt = $db->prepare("INSERT INTO menu_settings (user_id) VALUES (?)");
                $stmt->execute([$user_id]);
                
                // E-posta gönder (gerçek uygulamada)
                // sendVerificationEmail($email, $verification_code);
                
                $_SESSION['temp_user_id'] = $user_id;
                $_SESSION['success'] = 'Kayıt başarılı! Doğrulama kodu: ' . $verification_code;
                redirect('verify.php');
            } else {
                $errors[] = 'Kayıt sırasında hata oluştu';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h2 class="mb-0">Kayıt Ol</h2>
                    <p class="text-muted mt-2">QR menünüzü oluşturmaya başlayın</p>
                </div>
                <div class="card-body p-5">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Kullanıcı Adı</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo $_POST['username'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Kullanıcı adı gerekli</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Geçerli e-posta gerekli</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefon</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo $_POST['phone'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Telefon numarası gerekli</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="restaurant_name" class="form-label">Restoran Adı</label>
                                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" 
                                       value="<?php echo $_POST['restaurant_name'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Restoran adı gerekli</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="custom_url" class="form-label">Özel URL</label>
                            <div class="input-group">
                                <span class="input-group-text">menuyap.online/</span>
                                <input type="text" class="form-control" id="custom_url" name="custom_url" 
                                       value="<?php echo $_POST['custom_url'] ?? ''; ?>" required
                                       pattern="[a-zA-Z0-9-]+" title="Sadece harf, rakam ve tire kullanın">
                                <div class="invalid-feedback">Geçerli URL gerekli</div>
                            </div>
                            <div class="form-text">Sadece harf, rakam ve tire (-) kullanabilirsiniz</div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Şifre</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       minlength="6" required>
                                <div class="invalid-feedback">En az 6 karakter gerekli</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Şifre Tekrar</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       minlength="6" required>
                                <div class="invalid-feedback">Şifreler eşleşmiyor</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    <a href="terms.php" target="_blank">Kullanım şartlarını</a> ve 
                                    <a href="privacy.php" target="_blank">gizlilik politikasını</a> kabul ediyorum
                                </label>
                                <div class="invalid-feedback">Şartları kabul etmelisiniz</div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-loading">
                            Kayıt Ol
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p>Zaten hesabınız var mı? <a href="login.php">Giriş yapın</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// URL slug oluşturma
document.getElementById('restaurant_name').addEventListener('input', function() {
    const customUrl = document.getElementById('custom_url');
    if (!customUrl.value) {
        customUrl.value = generateSlug(this.value);
    }
});

// Şifre eşleşme kontrolü
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Şifreler eşleşmiyor');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include 'includes/footer.php'; ?>