<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
} else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | " . $config['cf_title'];
}

$g5['title'] = strip_tags($g5['title']);
$g5_head_title = strip_tags($g5_head_title);

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
    $g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/' . G5_ADMIN_DIR . '/') || $is_admin == 'super') $g5['lo_url'] = '';


//FromWhere
$fromWhere = "";
if (isset($_REQUEST['fw'])) {
    $fromWhere = addslashes(clean_xss_tags(trim($_REQUEST['fw'])));
    set_session("fromWhere", $fromWhere);
}

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!DOCTYPE html>
<html lang="ko-KR" prefix="og: http://ogp.me/ns#">

<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5NW4ZH3');</script>
    <!-- End Google Tag Manager -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BRLGZ13851"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BRLGZ13851');
    </script>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="naver-site-verification" content="1e7c28f18351f306674395d2c0674d174dee5574" />
    <meta name="google-site-verification" content="PFOFDrqNBjryuv4OnwKvkg_4N_Vrm1Gqph4T9rdR4OQ" />
	<!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/apple-icon.png">
    <!-- 메타정보 -->
    <title></title>
    <meta property="og:site_name" content="">
    <meta property="og:title" content="">
    <meta name="twitter:title" content="">
    <meta itemprop="name" content="">
    <!--  -->
    <meta name="description" content="">
    <meta name="description" content="">
    <meta property="og:description" content="">
    <meta name="twitter:description" content="">
    <meta itemprop="description" content="">

    <!--  -->
    <?php
    $canonical = $_SERVER['REQUEST_URI'];
    echo '<link rel="canonical" href="' . 'https://' . $_SERVER['HTTP_HOST'] . $canonical . '">' . PHP_EOL;
    echo '<meta property="og:url" content="' . 'https://' . $_SERVER['HTTP_HOST'] . $canonical . '">' . PHP_EOL;
    echo '<meta name="twitter:url" content="' . 'https://' . $_SERVER['HTTP_HOST'] . $canonical . '">' . PHP_EOL;
    echo '<meta itemprop="url" content="' . 'https://' . $_SERVER['HTTP_HOST'] . $canonical . '">' . PHP_EOL;
    ?>

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <!-- css -->
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/common/css/common.css?ver=<?php echo G5_CSS_VER; ?>">
    <link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/common/css/popup.css?ver=<?php echo G5_CSS_VER; ?>">

    <!-- GnuBoard5 -->
    <?php if ($config['cf_add_meta']) echo $config['cf_add_meta'] . PHP_EOL; ?>
    <title><?php echo $g5_head_title; ?></title>

    <script>
        var g5_url = "<?php echo G5_URL ?>";
        var g5_theme_url = "<?php echo G5_THEME_URL ?>";
        var g5_bbs_url = "<?php echo G5_BBS_URL ?>";
        var g5_is_member = "<?php echo isset($is_member) ? $is_member : ''; ?>";
        var g5_is_admin = "<?php echo isset($is_admin) ? $is_admin : ''; ?>";
        var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
        var g5_bo_table = "<?php echo isset($bo_table) ? $bo_table : ''; ?>";
        var g5_sca = "<?php echo isset($sca) ? $sca : ''; ?>";
        var g5_editor = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor']) ? $config['cf_editor'] : ''; ?>";
        var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
        <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
            var g5_theme_shop_url = "<?php echo G5_THEME_SHOP_URL; ?>";
            var g5_shop_url = "<?php echo G5_SHOP_URL; ?>";
        <?php } ?>
        <?php if (defined('G5_IS_ADMIN')) { ?>
            var g5_admin_url = "<?php echo G5_ADMIN_URL; ?>";
        <?php } ?>
    </script>

    <?php
    add_javascript('<script src="' . G5_JS_URL . '/jquery-1.12.4.min.js"></script>', 0);
    add_javascript('<script src="' . G5_JS_URL . '/jquery-migrate-1.4.1.min.js"></script>', 0);
    if (defined('_SHOP_')) {
        if (!G5_IS_MOBILE) {
            add_javascript('<script src="' . G5_JS_URL . '/jquery.shop.menu.js?ver=' . G5_JS_VER . '"></script>', 0);
        }
    } else {
        add_javascript('<script src="' . G5_JS_URL . '/jquery.menu.js?ver=' . G5_JS_VER . '"></script>', 0);
    }
    add_javascript('<script src="' . G5_JS_URL . '/common.js?ver=' . G5_JS_VER . '"></script>', 0);
    add_javascript('<script src="' . G5_JS_URL . '/wrest.js?ver=' . G5_JS_VER . '"></script>', 0);
    add_javascript('<script src="' . G5_JS_URL . '/placeholders.min.js"></script>', 0);
    add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/font-awesome/css/font-awesome.min.css">', 0);

    if (G5_IS_MOBILE) {
        add_javascript('<script src="' . G5_JS_URL . '/modernizr.custom.70111.js"></script>', 1); // overflow scroll 감지
    }
    if (!defined('G5_IS_ADMIN'))
        echo $config['cf_add_script'];
    ?>



    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- notosanskr -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- nanumpen -->
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&display=swap" rel="stylesheet">
</head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NW4ZH3"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->