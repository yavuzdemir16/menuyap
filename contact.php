<?php
require_once 'config/config.php';
require_once 'config/database.php';

$page_title = 'İletişim';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);
    
    $errors = [];
    
    if (empty($name)) $errors[] = 'Ad soyad gerekli';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Geçerli e-posta gerekli';
    if (empty($subject)) $errors[] = 'Konu gerekli';
    if (empty($message)) $errors[] = 'Mesaj gerekli';
    
    if (empty($errors)) {
        // E-posta gönderme işlemi burada yapılacak
        // mail() fonksiyonu veya PHPMailer kullanılabilir
        
        $success = 'Mesajınız başarıyla gönderildi. En kısa sürede size dönüş yapacağız.';
    }
}

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">İletişim</h1>
                <p class="lead text-muted">Bizimle iletişime geçin</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon primary mx-auto mb-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5>E-posta</h5>
                            <p class="text-muted">info@menuyap.online</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon success mx-auto mb-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5>Telefon</h5>
                            <p class="text-muted">+90 (555) 123 45 67</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon info mx-auto mb-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>Çalışma Saatleri</h5>
                            <p class="text-muted">7/24 Online Destek</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h3 class="mb-0">Bize Mesaj Gönderin</h3>
                </div>
                <div class="card-body p-5">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
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
                                <label for="name" class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo $_POST['name'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Ad soyad gerekli</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-posta</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo $_POST['email'] ?? ''; ?>" required>
                                <div class="invalid-feedback">Geçerli e-posta gerekli</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label">Konu</label>
                            <select class="form-control" id="subject" name="subject" required>
                                <option value="">Konu seçin</option>
                                <option value="Genel Bilgi" <?php echo ($_POST['subject'] ?? '') === 'Genel Bilgi' ? 'selected' : ''; ?>>Genel Bilgi</option>
                                <option value="Teknik Destek" <?php echo ($_POST['subject'] ?? '') === 'Teknik Destek' ? 'selected' : ''; ?>>Teknik Destek</option>
                                <option value="Faturalama" <?php echo ($_POST['subject'] ?? '') === 'Faturalama' ? 'selected' : ''; ?>>Faturalama</option>
                                <option value="Özellik Talebi" <?php echo ($_POST['subject'] ?? '') === 'Özellik Talebi' ? 'selected' : ''; ?>>Özellik Talebi</option>
                                <option value="Diğer" <?php echo ($_POST['subject'] ?? '') === 'Diğer' ? 'selected' : ''; ?>>Diğer</option>
                            </select>
                            <div class="invalid-feedback">Konu seçimi gerekli</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Mesajınız</label>
                            <textarea class="form-control" id="message" name="message" rows="6" 
                                      required><?php echo $_POST['message'] ?? ''; ?></textarea>
                            <div class="invalid-feedback">Mesaj gerekli</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 btn-loading">
                            Mesaj Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>