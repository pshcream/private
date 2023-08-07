<?php
header('Access-Control-Allow-Origin: *');
include_once('./_common.php');
@include_once("../plugin/sms5/JSON.php");

// 
if (!function_exists('json_encode')) {
    function json_encode($data)
    {
        $json = new Services_JSON();
        return ($json->encode($data));
    }
}
if (!isset($_REQUEST['submitNm']) || !trim($_REQUEST['submitNm']))
    die(0);

if (!isset($_REQUEST['submitNb']) || !trim($_REQUEST['submitNb']))
    die(0);

// 
$submitNm = addslashes(clean_xss_tags(trim($_REQUEST['submitNm'])));
$submitNb = addslashes(clean_xss_tags(trim($_REQUEST['submitNb'])));
$submitRg = addslashes(clean_xss_tags(trim($_REQUEST['submitRg'])));
$submitMm = addslashes(clean_xss_tags(trim($_REQUEST['submitMm'])));
$location = addslashes(clean_xss_tags(trim($_REQUEST['location'])));

//게시판 기본값
$bo_table = "application";
$mb_id = "admin";
$wr_name = $submitNm;
$wr_password = get_encrypt_string("fpvjfjf00@");
$wr_email = "";
$wr_homepage = "";

$wr_num = get_next_num($g5['chadeal_application_table']);
$wr_reply = '';

$wr_content = $submitNb;
$wr_subject = $location;
$wr_link1 = "";
$wr_link2 = "";


$ca_name = "";
$wr_1 = $submitRg;
$wr_10 = $submitMm;

if (!isset($_REQUEST['dataNum'])) {
    $wr_9 = "일반";
    $sql = "insert into {$g5['chadeal_application_table']}
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
        wr_2 ='',
        wr_3 = '',
        wr_4 = '',
        wr_5 = '',
        wr_6 = '',
        wr_7 ='',
        wr_8 = '',
        wr_9 = '$wr_9',
        wr_10 = '$wr_10' ";
    sql_query($sql);
} else {
    $dataNum = addslashes(clean_xss_tags(trim($_REQUEST['dataNum'])));
    if (isset($_REQUEST['estIdx'])) {
        $estIdx = addslashes(clean_xss_tags(trim($_REQUEST['estIdx'])));
        $wr_link1 = $estIdx;
    }
    if (isset($_REQUEST['sheetIdx'])) {
        $sheetIdx = addslashes(clean_xss_tags(trim($_REQUEST['sheetIdx'])));
        $wr_link2 = $sheetIdx;
    }
    for ($i = 0; $i < $dataNum; $i++) {
        $companyNm = addslashes(clean_xss_tags(trim($_REQUEST['companyNm' . $i])));
        $modelNm = addslashes(clean_xss_tags(trim($_REQUEST['modelNm' . $i])));
        $lineupNm = addslashes(clean_xss_tags(trim($_REQUEST['lineupNm' . $i])));
        $trimNm = addslashes(clean_xss_tags(trim($_REQUEST['trimNm' . $i])));
        $colorNm = addslashes(clean_xss_tags(trim($_REQUEST['colorNm' . $i])));
        $innerColorNm = addslashes(clean_xss_tags(trim($_REQUEST['innerColorNm' . $i])));
        $optNmArr = addslashes(clean_xss_tags(trim($_REQUEST['optNmArr' . $i])));
        $orderDate = addslashes(clean_xss_tags(trim($_REQUEST['orderDate' . $i])));

        $wr_2 = $companyNm;
        $wr_3 = $modelNm;
        $wr_4 = $lineupNm;
        $wr_5 = $trimNm;
        $wr_6 = $colorNm;
        $wr_7 = $innerColorNm;
        $wr_8 = $optNmArr;
        $wr_9 = $orderDate;

        $sql = "insert into {$g5['chadeal_application_table']}
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
                         wr_2 = '$wr_2',
                         wr_3 = '$wr_3',
                         wr_4 = '$wr_4',
                         wr_5 = '$wr_5',
                         wr_6 = '$wr_6',
                         wr_7 = '$wr_7',
                         wr_8 = '$wr_8',
                         wr_9 = '$wr_9',
                         wr_10 = '$wr_10' ";
        sql_query($sql);
    }
}

$wr_id = sql_insert_id();

// 부모 아이디에 UPDATE
sql_query("update {$g5['chadeal_application_table']} set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

// 새글 INSERT
sql_query("insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '" . G5_TIME_YMDHIS . "', '{$mb_id}' ) ");

// 게시글 1 증가
sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");

echo $wr_id;
?>

<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/kas/static/kp.js"></script>
<script type="text/javascript">
    kakaoPixel(3475785087929699795).pageView();
    kakaoPixel(3475785087929699795).signUp();
</script>