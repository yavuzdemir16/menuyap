<?php
require_once 'config/config.php';
require_once 'config/database.php';

$page_title = 'Giriş Yap';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT id, username, email, password, role, status, is_verified 
            FROM users 
            WHERE (username = ? OR email = ?) AND status != 'blocked'
        ");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_verified']) {
                $error = 'Hesabınız henüz doğrulanmamış. Lütfen e-postanızı kontrol edin.';
            } elseif ($user['status'] === 'inactive') {
                $error = 'Hesabınız aktif değil. Lütfen yönetici ile iletişime geçin.';
            } else {
                // Giriş başarılı
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Aktivite kaydı
                $stmt = $db->prepare("
                    INSERT INTO activity_logs (user_id, action, description, ip_address) 
                    VALUES (?, 'login', 'Kullanıcı giriş yaptı', ?)
                ");
                $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR']]);
                
                // Yönlendirme
                if ($user['role'] === 'super_admin') {
                    redirect('super-admin/dashboard.php');
                } else {
                    redirect('restaurant/dashboard.php');
                }
            }
        } else {
            $error = 'Kullanıcı adı veya şifre hatalı';
        }
    } else {
        $error = 'Tüm alanları doldurun';
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h2 class="mb-0">Giriş Yap</h2>
                    <p class="text-muted mt-2">Hesabınıza erişin</p>
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
                    
                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Kullanıcı Adı veya E-posta</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo $_POST['username'] ?? ''; ?>" required>
                            <div class="invalid-feedback">Bu alan gerekli</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">Şifre gerekli</div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Beni hatırla
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-loading">
                            Giriş Yap
                        </button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p><a href="forgot-password.php">Şifremi unuttum</a></p>
                        <p>Hesabınız yok mu? <a href="register.php">Kayıt olun</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>