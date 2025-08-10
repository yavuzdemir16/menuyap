<?php
require_once 'config/config.php';
$page_title = 'Hakkımızda';
include 'includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold">Hakkımızda</h1>
                <p class="lead text-muted">MenuYap ile tanışın</p>
            </div>
            
            <div class="card shadow-lg border-0 mb-5">
                <div class="card-body p-5">
                    <h2 class="h3 mb-4">MenuYap Nedir?</h2>
                    <p class="mb-4">
                        MenuYap, restoranlar için dijital menü oluşturma platformudur. QR kod teknolojisi ile 
                        müşterileriniz telefonlarından kolayca menünüzü görüntüleyebilir, ürünlerinizi inceleyebilir 
                        ve sipariş verebilir.
                    </p>
                    
                    <h3 class="h4 mb-3">Misyonumuz</h3>
                    <p class="mb-4">
                        Restoran işletmecilerinin dijital dönüşümünde yanlarında olmak ve müşteri deneyimini 
                        en üst seviyeye çıkarmak için modern, kullanıcı dostu çözümler sunmak.
                    </p>
                    
                    <h3 class="h4 mb-3">Vizyonumuz</h3>
                    <p class="mb-4">
                        Türkiye'nin en çok tercih edilen dijital menü platformu olmak ve restoran sektöründe 
                        teknolojik dönüşümün öncüsü olmak.
                    </p>
                </div>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon primary mx-auto mb-3">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <h5>Hızlı Kurulum</h5>
                            <p class="text-muted">5 dakikada menünüzü oluşturun ve yayınlayın</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon success mx-auto mb-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5>Güvenli</h5>
                            <p class="text-muted">Verileriniz SSL ile korunur ve güvende tutulur</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 text-center border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="stat-icon info mx-auto mb-3">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h5>7/24 Destek</h5>
                            <p class="text-muted">Her zaman yanınızdayız, sorularınızı yanıtlıyoruz</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card bg-primary text-white">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-3">Neden Bizi Seçmelisiniz?</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Kolay kullanım</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Uygun fiyatlar</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Mobil uyumlu</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Hızlı destek</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Özelleştirilebilir tasarım</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Detaylı istatistikler</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> QR kod oluşturma</li>
                                <li class="mb-2"><i class="fas fa-check me-2"></i> Sosyal medya entegrasyonu</li>
                            </ul>
                        </div>
                    </div>
                    <a href="register.php" class="btn btn-warning btn-lg mt-3">Hemen Başlayın</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>