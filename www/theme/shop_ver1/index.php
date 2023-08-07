<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH . '/head.php');

if (is_file(G5_THEME_PATH . '/main.php')) {
    include G5_THEME_PATH . '/main.php';
} else {
?>
    <p>Hello World.</p>
<?php
}
include_once(G5_THEME_PATH . '/tail.php');
?>