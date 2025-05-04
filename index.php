<?php
require_once 'includes/functions.php';

// Aktif sayfa kontrolü
$page = isset($_GET['page']) ? clean($_GET['page']) : 'home';

// Sayfa bilgisini al
$pageData = getPage($page);

// Sayfa bulunamadıysa ve home değilse, anasayfaya yönlendir
if (!$pageData && $page != 'home') {
    redirect(BASE_URL);
}

// Site ayarlarını al
$siteTitle = getSetting('site_title');
$siteDescription = getSetting('site_description');
$siteLogo = getSetting('site_logo');

// SEO meta bilgileri
$metaTitle = ($page == 'home') ? $siteTitle : (($pageData && $pageData['meta_title']) ? $pageData['meta_title'] : $pageData['title'] . ' - ' . $siteTitle);
$metaDescription = ($page == 'home') ? $siteDescription : (($pageData && $pageData['meta_description']) ? $pageData['meta_description'] : '');
$metaKeywords = ($pageData && $pageData['meta_keywords']) ? $pageData['meta_keywords'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Etiketleri -->
    <title><?php echo $metaTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="<?php echo $metaKeywords; ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo BASE_URL . ($page != 'home' ? '?page=' . $page : ''); ?>">
    
    <!-- Open Graph Meta Etiketleri -->
    <meta property="og:title" content="<?php echo $metaTitle; ?>">
    <meta property="og:description" content="<?php echo $metaDescription; ?>">
    <meta property="og:url" content="<?php echo BASE_URL . ($page != 'home' ? '?page=' . $page : ''); ?>">
    <meta property="og:type" content="website">
    <?php if (!empty($siteLogo)): ?>
    <meta property="og:image" content="<?php echo UPLOAD_URL . $siteLogo; ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    
    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>
<body class="index-page">
    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
            <a href="<?php echo BASE_URL; ?>" class="logo d-flex align-items-center">
                <?php if (!empty($siteLogo)): ?>
                <img src="<?php echo UPLOAD_URL . $siteLogo; ?>" alt="<?php echo $siteTitle; ?>">
                <?php else: ?>
                <h1 class="sitename"><?php echo $siteTitle; ?></h1>
                <?php endif; ?>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>" class="<?php echo ($page == 'home') ? 'active' : ''; ?>">Anasayfa</a></li>
                    <li><a href="<?php echo BASE_URL . '?page=products'; ?>" class="<?php echo ($page == 'products') ? 'active' : ''; ?>">Ürünler</a></li>
                    <li><a href="<?php echo BASE_URL . '?page=solutions'; ?>" class="<?php echo ($page == 'solutions') ? 'active' : ''; ?>">Çözümler</a></li>
                    <li><a href="<?php echo BASE_URL . '?page=contact'; ?>" class="<?php echo ($page == 'contact') ? 'active' : ''; ?>">İletişim</a></li>
                    <?php
                    // Diğer sayfaları menüye ekle
                    $pages = getAllPages();
                    foreach ($pages as $p) {
                        // Standart sayfaları hariç tut
                        if (!in_array($p['slug'], ['home', 'products', 'solutions', 'contact'])) {
                            echo '<li><a href="' . BASE_URL . '?page=' . $p['slug'] . '" class="' . (($page == $p['slug']) ? 'active' : '') . '">' . $p['title'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <?php
    // Sayfa içeriğini göster
    if ($page == 'home') {
        include 'templates/home.php';
    } elseif ($page == 'contact') {
        include 'templates/contact.php';
    } elseif ($page == 'products') {
        include 'templates/products.php';
    } elseif ($page == 'solutions') {
        include 'templates/solutions.php';
    } else {
        // Dinamik sayfa içeriği
        echo '<main id="main" class="main">';
        echo '<section class="inner-page section">';
        echo '<div class="container">';
        echo '<div class="section-title" data-aos="fade-up">';
        echo '<h2>' . $pageData['title'] . '</h2>';
        echo '</div>';
        echo '<div class="content" data-aos="fade-up" data-aos-delay="100">' . $pageData['content'] . '</div>';
        echo '</div>';
        echo '</section>';
        echo '</main>';
    }
    ?>

    <footer id="footer" class="footer">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="<?php echo BASE_URL; ?>" class="logo d-flex align-items-center">
                        <span class="sitename"><?php echo $siteTitle; ?></span>
                    </a>
                    <p><?php echo $siteDescription; ?></p>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Hızlı Bağlantılar</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Anasayfa</a></li>
                        <li><a href="<?php echo BASE_URL . '?page=products'; ?>">Ürünler</a></li>
                        <li><a href="<?php echo BASE_URL . '?page=solutions'; ?>">Çözümler</a></li>
                        <li><a href="<?php echo BASE_URL . '?page=contact'; ?>">İletişim</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Hizmetlerimiz</h4>
                    <ul>
                        <?php
                        $services = getServices();
                        foreach ($services as $service) {
                            echo '<li><a href="' . BASE_URL . '?page=solutions">' . $service['title'] . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>İletişim</h4>
                    <p><?php echo getSetting('contact_address'); ?></p>
                    <p class="mt-4"><strong>Telefon:</strong> <span><?php echo getSetting('contact_phone'); ?></span></p>
                    <p><strong>E-posta:</strong> <span><?php echo getSetting('contact_email'); ?></span></p>
                </div>
            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p><?php echo getSetting('footer_text'); ?></p>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
</html>