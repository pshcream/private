<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;
include_once(G5_PLUGIN_PATH . "/sms5/JSON.php");

//------------------------------------------------------------------------------
// 알차다쇼핑 상수 모음 시작
//------------------------------------------------------------------------------
define('G5_SHOPPING_DIR',        'shopping');
define('G5_SHOPPING_PATH',       G5_PATH . '/' . G5_SHOPPING_DIR);
define('G5_SHOPPING_URL',        G5_URL . '/' . G5_SHOPPING_DIR);

define('G5_SHOPPING_ADMIN_DIR',        'shopping_adm');
define('G5_SHOPPING_ADMIN_PATH',       G5_PATH . '/' . G5_SHOPPING_ADMIN_DIR);
define('G5_SHOPPING_ADMIN_URL',        G5_URL . '/' . G5_SHOPPING_ADMIN_DIR);

define('G5_SHOPPING_ADMIN_BOARD_DIR',        'board');
define('G5_SHOPPING_ADMIN_BOARD_PATH',       G5_SHOPPING_ADMIN_PATH . '/' . G5_SHOPPING_ADMIN_BOARD_DIR);
define('G5_SHOPPING_ADMIN_BOARD_URL',        G5_SHOPPING_ADMIN_URL . '/' . G5_SHOPPING_ADMIN_BOARD_DIR);

define('G5_SHOPPING_ADMIN_AUTH_DIR',        'auth');
define('G5_SHOPPING_ADMIN_AUTH_PATH',       G5_SHOPPING_ADMIN_PATH . '/' . G5_SHOPPING_ADMIN_AUTH_DIR);
define('G5_SHOPPING_ADMIN_AUTH_URL',        G5_SHOPPING_ADMIN_URL . '/' . G5_SHOPPING_ADMIN_AUTH_DIR);

// 테이블명
$g5['shopping_prefix']                             = 'shopping_';
$g5['shopping_preorder_kr_table']                  = $g5['shopping_prefix'] . 'car_preordered'; //선구매 차량 리스트(국산차)
$g5['shopping_preorder_im_table']                  = $g5['shopping_prefix'] . 'preordered_list'; //선구매 차량 리스트(수입차)
$g5['shopping_cardb_table']                        = $g5['shopping_prefix'] . 'car_db'; //차량 리스트
$g5['shopping_epdata_table']                       = $g5['shopping_prefix'] . 'epdata_idx'; //차량 리스트

// 게시판 테이블명
$g5['shopping_application_table']                  = $g5['write_prefix'] . 'application'; //상담신청 저장

function getEstDetail($wr_id)
{
    global $g5;

    $row = sql_fetch("SELECT * FROM {$g5['shopping_application_table']} WHERE wr_id='$wr_id'");
    if (!$row) {
        return 0;
    }

    if ($row["wr_6"] == "R") {
        $product = "렌트";
    } else if ($row["wr_6"] == "L") {
        $product = "리스";
    }

    $info = "
상품 : $product";

    if ($row["wr_7"]) {
        $period = $row["wr_7"];
        if ($row["wr_8"] == "M1") {
            $perpaid = "0%";
        } else if ($row["wr_8"] == "M2") {
            $perpaid = "선납금30%";
        } else if ($row["wr_8"] == "M3") {
            $perpaid = "보증금30%";
        }
        $monthly = $row["wr_9"];
        $totalPrice = $row["wr_10"];

        $info .= "
계약기간 : $period 개월
초기비용 : $perpaid

월가격 : 월 $monthly 원
월가격 x 개월 : $totalPrice 원
    ";
    }

    return $info;
}
