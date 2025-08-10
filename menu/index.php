<?php
require_once '../config/config.php';
require_once '../config/database.php';

// URL'den custom_url al
$custom_url = $_GET['url'] ?? '';

if (empty($custom_url)) {
    http_response_code(404);
    die('Menü bulunamadı');
}

$db = getDB();

// Kullanıcı ve menü bilgilerini getir
$stmt = $db->prepare("
    SELECT u.id, u.restaurant_name, u.logo_path, u.status, u.subscription_end,
           ms.font_family, ms.font_color, ms.background_type, ms.background_color, 
           ms.gradient_start, ms.gradient_end, ms.header_color, ms.card_color
    FROM users u
    LEFT JOIN menu_settings ms ON u.id = ms.user_id
    WHERE u.custom_url = ? AND u.status = 'active'
");
$stmt->execute([$custom_url]);
$restaurant = $stmt->fetch();

if (!$restaurant) {
    http_response_code(404);
    die('Menü bulunamadı');
}

// Abonelik kontrolü
if ($restaurant['subscription_end'] && strtotime($restaurant['subscription_end']) < time()) {
    die('Bu menünün süresi dolmuş');
}

$user_id = $restaurant['id'];

// Tıklama kaydı
$stmt = $db->prepare("
    INSERT INTO menu_clicks (user_id, ip_address, user_agent) 
    VALUES (?, ?, ?)
");
$stmt->execute([$user_id, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

// Kategorileri ve ürünleri getir
$stmt = $db->prepare("
    SELECT * FROM categories 
    WHERE user_id = ? AND is_active = 1 
    ORDER BY sort_order ASC, name ASC
");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();

$menu_data = [];
foreach ($categories as $category) {
    $stmt = $db->prepare("
        SELECT * FROM products 
        WHERE user_id = ? AND category_id = ? AND is_available = 1 
        ORDER BY sort_order ASC, name ASC
    ");
    $stmt->execute([$user_id, $category['id']]);
    $products = $stmt->fetchAll();
    
    if (!empty($products)) {
        $menu_data[] = [
            'category' => $category,
            'products' => $products
        ];
    }
}

// Sosyal medya hesapları
$stmt = $db->prepare("
    SELECT platform, url FROM social_media 
    WHERE user_id = ? AND is_active = 1
");
$stmt->execute([$user_id]);
$social_media = $stmt->fetchAll();

// CSS değişkenleri
$css_vars = [
    '--font-family' => $restaurant['font_family'] ?? 'Arial',
    '--font-color' => $restaurant['font_color'] ?? '#000000',
    '--background-color' => $restaurant['background_color'] ?? '#ffffff',
    '--header-color' => $restaurant['header_color'] ?? '#333333',
    '--card-color' => $restaurant['card_color'] ?? '#ffffff'
];

if ($restaurant['background_type'] === 'gradient') {
    $css_vars['--background'] = 'linear-gradient(135deg, ' . 
        ($restaurant['gradient_start'] ?? '#ffffff') . ', ' . 
        ($restaurant['gradient_end'] ?? '#f0f0f0') . ')';
} else {
    $css_vars['--background'] = $restaurant['background_color'] ?? '#ffffff';
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant['restaurant_name']); ?> - Menü</title>
    <meta name="description" content="<?php echo htmlspecialchars($restaurant['restaurant_name']); ?> dijital menüsü">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            <?php foreach ($css_vars as $key => $value): ?>
            <?php echo $key; ?>: <?php echo $value; ?>;
            <?php endforeach; ?>
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-family), -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--font-color);
            background: var(--background);
            line-height: 1.6;
        }
        
        .qr-menu {
            max-width: 500px;
            margin: 0 auto;
            background: var(--card-color);
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .menu-header {
            text-align: center;
            padding: 2rem 1rem;
            background: var(--header-color);
            color: white;
            position: relative;
        }
        
        .menu-logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.3);
            display: block;
        }
        
        .restaurant-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .menu-content {
            padding: 1rem;
        }
        
        .category-section {
            margin-bottom: 2rem;
        }
        
        .category-title {
            font-size: 1.4rem;
            font-weight: bold;
            color: var(--header-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--header-color);
        }
        
        .product-card {
            display: flex;
            padding: 1rem;
            margin-bottom: 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--header-color);
        }
        
        .product-description {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }
        
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .product-price {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--header-color);
        }
        
        .product-rating {
            color: #fbbf24;
            font-size: 0.9rem;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
        }
        
        .social-links a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: transform 0.2s ease;
        }
        
        .social-links a:hover {
            transform: scale(1.1);
        }
        
        .social-links .facebook { background: #1877f2; }
        .social-links .instagram { background: #e4405f; }
        .social-links .twitter { background: #1da1f2; }
        .social-links .whatsapp { background: #25d366; }
        .social-links .website { background: #6b7280; }
        
        .menu-footer {
            text-align: center;
            padding: 1rem;
            background: var(--header-color);
            color: white;
            font-size: 0.8rem;
        }
        
        .no-image {
            width: 80px;
            height: 80px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #9ca3af;
        }
        
        @media (max-width: 480px) {
            .qr-menu {
                margin: 0;
                box-shadow: none;
            }
            
            .menu-header {
                padding: 1.5rem 1rem;
            }
            
            .restaurant-name {
                font-size: 1.5rem;
            }
            
            .product-card {
                padding: 0.75rem;
            }
            
            .product-image, .no-image {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="qr-menu">
        <!-- Header -->
        <div class="menu-header">
            <?php if ($restaurant['logo_path']): ?>
                <img src="../<?php echo $restaurant['logo_path']; ?>" alt="Logo" class="menu-logo">
            <?php else: ?>
                <div class="menu-logo" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-utensils" style="font-size: 2rem;"></i>
                </div>
            <?php endif; ?>
            
            <h1 class="restaurant-name"><?php echo htmlspecialchars($restaurant['restaurant_name']); ?></h1>
            
            <!-- Sosyal Medya -->
            <?php if (!empty($social_media)): ?>
            <div class="social-links">
                <?php foreach ($social_media as $social): ?>
                <a href="<?php echo htmlspecialchars($social['url']); ?>" target="_blank" class="<?php echo $social['platform']; ?>">
                    <i class="fab fa-<?php echo $social['platform']; ?>"></i>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Menu Content -->
        <div class="menu-content">
            <?php if (empty($menu_data)): ?>
                <div style="text-align: center; padding: 2rem; color: #666;">
                    <i class="fas fa-utensils" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Henüz menü eklenmemiş</p>
                </div>
            <?php else: ?>
                <?php foreach ($menu_data as $section): ?>
                <div class="category-section">
                    <h2 class="category-title"><?php echo htmlspecialchars($section['category']['name']); ?></h2>
                    
                    <?php foreach ($section['products'] as $product): ?>
                    <div class="product-card">
                        <?php if ($product['image_path']): ?>
                            <img src="../<?php echo $product['image_path']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            
                            <?php if ($product['description']): ?>
                            <div class="product-description"><?php echo htmlspecialchars($product['description']); ?></div>
                            <?php endif; ?>
                            
                            <div class="product-footer">
                                <div class="product-price"><?php echo formatPrice($product['price']); ?></div>
                                
                                <?php if ($product['rating'] > 0): ?>
                                <div class="product-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?php echo $i <= $product['rating'] ? '' : '-o'; ?>"></i>
                                    <?php endfor; ?>
                                    <span>(<?php echo $product['rating']; ?>)</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Footer -->
        <div class="menu-footer">
            <p>Bu menü <strong>MenuYap</strong> ile oluşturulmuştur</p>
            <p><small>menuyap.online</small></p>
        </div>
    </div>
</body>
</html>