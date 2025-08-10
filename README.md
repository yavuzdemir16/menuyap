# QR Menü Oluşturma Sistemi

Modern restoranlar için geliştirilmiş QR menü oluşturma platformu.

## Özellikler

### Anasayfa
- Modern ve responsive tasarım
- Paket seçimi ve ödeme sistemi
- Hakkımızda, SSS ve İletişim sayfaları
- PayTR ödeme entegrasyonu

### Restoran Paneli
- Dashboard ve istatistikler
- Kategori yönetimi
- Ürün yönetimi (fotoğraf, fiyat, açıklama)
- Menü tasarım özelleştirme
- QR kod oluşturma
- Sosyal medya entegrasyonu
- Aktivite logları

### Süper Admin Paneli
- Tüm restoranları yönetme
- Kullanıcı süre ekleme/blokla
- Sistem istatistikleri
- Ödeme takibi

### QR Menü
- Mobil uyumlu tasarım
- Özelleştirilebilir renkler ve fontlar
- Kategori bazlı ürün listeleme
- Sosyal medya linkleri
- Tıklama istatistikleri

## Kurulum

### Gereksinimler
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- SSL sertifikası (önerilen)

### Adımlar

1. **Dosyaları yükleyin**
   ```bash
   # Tüm dosyaları web sunucunuzun root dizinine yükleyin
   ```

2. **Veritabanını oluşturun**
   ```sql
   # database.sql dosyasını MySQL'de çalıştırın
   mysql -u root -p < database.sql
   ```

3. **Konfigürasyon**
   ```php
   // config/config.php dosyasını düzenleyin
   // Veritabanı bilgileri
   // PayTR API bilgileri
   // E-posta ayarları
   ```

4. **Klasör izinleri**
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/logos/
   chmod 755 uploads/products/
   ```

5. **SSL ve .htaccess**
   - SSL sertifikası kurun
   - .htaccess dosyasında HTTPS yönlendirmeyi aktif edin

## Kullanım

### Restoran Kaydı
1. Ana sayfadan "Kayıt Ol" tıklayın
2. Bilgileri doldurun ve özel URL seçin
3. E-posta doğrulaması yapın
4. Paket satın alın

### Menü Oluşturma
1. Restoran paneline giriş yapın
2. Kategoriler oluşturun
3. Ürünleri ekleyin (fotoğraf, fiyat, açıklama)
4. Menü tasarımını özelleştirin
5. QR kod oluşturun

### QR Menü Erişimi
- `menuyap.online/[ozel-url]` formatında erişim
- QR kod ile direkt erişim

## API Entegrasyonu

### PayTR Ödeme
```php
// config/config.php içinde
define('PAYTR_MERCHANT_ID', 'your-id');
define('PAYTR_MERCHANT_KEY', 'your-key');
define('PAYTR_MERCHANT_SALT', 'your-salt');
```

### E-posta Ayarları
```php
// SMTP ayarları
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'your-email');
define('SMTP_PASSWORD', 'your-password');
```

## Güvenlik

- SQL Injection koruması (PDO prepared statements)
- XSS koruması (htmlspecialchars)
- CSRF koruması
- SSL şifreleme
- Dosya upload güvenliği
- Session güvenliği

## Performans

- Gzip sıkıştırma
- Browser cache
- Optimized SQL queries
- CDN desteği (Bootstrap, FontAwesome)

## Destek

- 7/24 online destek
- E-posta: info@menuyap.online
- Telefon: +90 (555) 123 45 67

## Lisans

Bu proje özel lisans altındadır. Ticari kullanım için izin gereklidir.

## Güncellemeler

### v1.0.0
- İlk sürüm
- Temel özellikler
- PayTR entegrasyonu

### Gelecek Sürümler
- Mobil uygulama
- Sipariş sistemi
- Çoklu dil desteği
- API geliştirme
- Gelişmiş raporlama

## Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun
3. Commit yapın
4. Pull request gönderin

## İletişim

- Web: https://menuyap.online
- E-posta: info@menuyap.online
- GitHub: https://github.com/menuyap/qr-menu-system

---

**MenuYap** - Bir enucuz.site iştirakidir.