<?php
require_once 'config/config.php';
$page_title = 'Sıkça Sorulan Sorular';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Sıkça Sorulan Sorular</h1>
                <p class="lead text-muted">Merak ettiğiniz soruların cevapları</p>
            </div>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                            MenuYap nasıl çalışır?
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            MenuYap ile restoranınız için dijital menü oluşturursunuz. Müşterileriniz QR kodu okutarak 
                            telefonlarından menünüzü görüntüleyebilir. Ürünlerinizi kategorilere ayırabilir, fotoğraf 
                            ekleyebilir ve fiyatlarını belirleyebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                            QR kod nasıl oluşturulur?
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Restoran panelinizde "QR Oluştur" bölümüne giderek menünüz için özel QR kod oluşturabilirsiniz. 
                            Bu QR kodu PNG formatında indirebilir ve masalarınıza yerleştirebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                            Kaç ürün ekleyebilirim?
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Tüm paketlerimizde sınırsız ürün ekleme imkanı bulunmaktadır. İstediğiniz kadar kategori 
                            oluşturabilir ve her kategoriye istediğiniz kadar ürün ekleyebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                            Menü tasarımını özelleştirebilir miyim?
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Evet! Menü tasarımı bölümünden yazı tipi, renkler, arkaplan ve diğer görsel öğeleri 
                            özelleştirebilirsiniz. Restoranınızın kimliğine uygun bir tasarım oluşturabilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5">
                            Ödeme nasıl yapılır?
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            PayTR güvenli ödeme sistemi ile kredi kartı veya banka kartı ile ödeme yapabilirsiniz. 
                            Tüm ödemeler SSL ile şifrelenir ve güvenli bir şekilde işlenir.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6">
                            Üyelik süresi nasıl uzatılır?
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Restoran panelinizden "Paket Yenile" bölümüne giderek mevcut paketinizi uzatabilir veya 
                            farklı bir pakete geçiş yapabilirsiniz. Ödeme sonrası süreniz otomatik olarak uzatılır.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7">
                            Mobil uyumlu mu?
                        </button>
                    </h2>
                    <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Evet! Tüm menülerimiz mobil cihazlarda mükemmel görünüm sağlar. Müşterileriniz telefon, 
                            tablet veya bilgisayardan kolayca menünüzü görüntüleyebilir.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq8">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8">
                            İstatistikleri görebilir miyim?
                        </button>
                    </h2>
                    <div id="collapse8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Restoran panelinizde detaylı istatistikler bulunur. Menü görüntüleme sayıları, 
                            en çok görüntülenen ürünler ve ziyaretçi analizlerini takip edebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq9">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9">
                            Destek hizmeti var mı?
                        </button>
                    </h2>
                    <div id="collapse9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            7/24 online destek hizmeti sunuyoruz. İletişim sayfasından bize ulaşabilir, 
                            e-posta gönderebilir veya canlı destek hattımızdan yardım alabilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq10">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10">
                            Verilerim güvende mi?
                        </button>
                    </h2>
                    <div id="collapse10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Tüm verileriniz SSL şifreleme ile korunur ve güvenli sunucularda saklanır. 
                            Gizlilik politikamıza uygun olarak verileriniz üçüncü şahıslarla paylaşılmaz.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <div class="card bg-light">
                    <div class="card-body p-4">
                        <h4>Sorunuz burada yok mu?</h4>
                        <p class="text-muted">Bizimle iletişime geçin, size yardımcı olmaktan mutluluk duyarız.</p>
                        <a href="contact.php" class="btn btn-primary">İletişime Geçin</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>