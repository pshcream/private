<?php
include_once('./_common.php');
@include_once(G5_PLUGIN_PATH . "/sms5/JSON.php");

if (!function_exists('json_encode')) {
    function json_encode($data)
    {
        $json = new Services_JSON();
        return ($json->encode($data));
    }
}
// --------------------------------------------------------- //

if (!isset($_REQUEST['location']) || !trim($_REQUEST['location'])) {
    die(0);
} else {
    $location = addslashes(clean_xss_tags(trim($_REQUEST['location'])));
}
if (!isset($_REQUEST['name']) || !trim($_REQUEST['name'])) {
    die(0);
} else {
    $name = addslashes(clean_xss_tags(trim($_REQUEST['name'])));
}
if (!isset($_REQUEST['phone']) || !trim($_REQUEST['phone'])) {
    die(0);
} else {
    $phone = addslashes(clean_xss_tags(trim($_REQUEST['phone'])));
}
if (!isset($_REQUEST['model']) || !trim($_REQUEST['model'])) {
    die(0);
} else {
    $model = addslashes(clean_xss_tags(trim($_REQUEST['model'])));
}

$memo = addslashes(clean_xss_tags(trim($_REQUEST['memo'])));
$product = addslashes(clean_xss_tags(trim($_REQUEST['product'])));
$period = addslashes(clean_xss_tags(trim($_REQUEST['period'])));
$prepaid = addslashes(clean_xss_tags(trim($_REQUEST['prepaid'])));
$monthly = addslashes(clean_xss_tags(trim($_REQUEST['monthly'])));
$totalPrice = addslashes(clean_xss_tags(trim($_REQUEST['totalPrice'])));

//게시판 기본값
$bo_table = "application";
$mb_id = "admin";
$wr_name = $ca_name;
$wr_password = get_encrypt_string("fpvjfjf00@");
$wr_email = "";
$wr_homepage = "";

$wr_num = get_next_num($g5['shopping_application_table']);
$wr_reply = '';

$wr_content = "";
$wr_subject = "";
$wr_link1 = "";
$wr_link2 = "";

$ca_name = $name;
$wr_1 = $location;
$wr_2 = $name;
$wr_3 = $phone;
$wr_4 = $model;
$wr_5 = $memo;
$wr_6 = $product;
$wr_7 = $period;
$wr_8 = $prepaid;
$wr_9 = $monthly;
$wr_10 = $totalPrice;

$sql = "insert into {$g5['shopping_application_table']}
set wr_num = '$wr_num',
    wr_reply = '$wr_reply',
    wr_is_comment = 0,
    wr_comment = 0,
    ca_name = '$ca_name',
    wr_option = '',
    wr_subject = '$wr_subject',
    wr_content = '$wr_content',
    wr_seo_title = '$wr_seo_title',
    wr_link1 = '$wr_link1',
    wr_link2 = '$wr_link2',
    wr_link1_hit = 0,
    wr_link2_hit = 0,
    wr_hit = 0,
    wr_good = 0,
    wr_nogood = 0,
    mb_id = '$mb_id',
    wr_password = '$wr_password',
    wr_name = '$wr_name',
    wr_email = '$wr_email',
    wr_homepage = '$wr_homepage',
    wr_datetime = '" . G5_TIME_YMDHIS . "',
    wr_last = '" . G5_TIME_YMDHIS . "',
    wr_ip = '{$_SERVER['REMOTE_ADDR']}',
    wr_1 = '$wr_1',
    wr_2 ='$wr_2',
    wr_3 = '$wr_3',
    wr_4 = '$wr_4',
    wr_5 = '$wr_5',
    wr_6 = '$wr_6',
    wr_7 ='$wr_7',
    wr_8 = '$wr_8',
    wr_9 = '$wr_9',
    wr_10 = '$wr_10' ";
sql_query($sql);

$wr_id = sql_insert_id();

// 부모 아이디에 UPDATE
sql_query("update {$g5['shopping_application_table']} set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

// 새글 INSERT
sql_query("insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '" . G5_TIME_YMDHIS . "', '{$mb_id}' ) ");

// 게시글 1 증가
sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");

echo $wr_id;
