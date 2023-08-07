<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

switch (substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_PATH))) {
    case '/bbs/register.php':
    case '/bbs/register_form.php':
    case '/bbs/register_result.php':
    case '/plugin/social/register_member.php':
        include_once(G5_THEME_PATH . '/head.def.php');
        return;
        break;
}

include_once(G5_THEME_PATH . '/head.def.php');
include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

include_once(G5_THEME_PATH . '/functions.php');

$menu_data = get_menu_db(0, true);
get_active_menu($menu_data);

$g5['sidebar']['right'] = !defined('_INDEX_') && is_file(G5_THEME_PATH . '/sidebar.right.php') ? true : false;

if (defined('_INDEX_')) include G5_THEME_PATH . '/newwin.inc.php';
?>

<div class="container">

    <!-- start : header -->
    <header>
        <div class="header-inner">
            <a href="/" class="header-logo" target="_self">
                <img src="<?php echo G5_THEME_URL; ?>/common/img/logo.svg" alt="">
            </a>
            <div class="header-input">
                <input type="text" placeholder="캐스퍼" id="search-modelnm">
                <button>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/icon-search.svg" alt="">
                </button>
                <div class="search-list">
                </div>
            </div>
            <div class="header-tbox">
                <p>빠른 상담전화</p>
                <strong>1899-1549</strong>
            </div>
            <button type="button" class="header-btn">
                <i></i>
            </button>
        </div>
    </header>
    <div class="header-search-box">
        <div class="hsb-inner">
            <input type="text" placeholder="캐스퍼">
            <button type="button" class="hsb-btn"><i></i></button>
        </div>
    </div>
    <!-- end -->
    <!-- 빠른출고/국산차/수입차 메뉴 -->
    <div class="main-menu">
        <a href="/preorder?nation=KR&page=1&brandNo=" target="_self" data-nation="FO">
            <img src="<?php echo G5_THEME_URL; ?>/common/img/thunder.png" alt="">
            빠른출고
        </a>
        <a href="/?nation=KR&page=1&brandNo=" target="_self" data-nation="KR">국산차</a>
        <a href="/?nation=FR&page=1&brandNo=" target="_self" data-nation="FR">수입차</a>
    </div>
    <!-- end -->

    <?php if ($g5['sidebar']['right']) { ?>
        <!-- <div class="row">
            <div class="col-lg-9 mb-4"> -->
    <?php }
