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

if (!isset($_REQUEST['p_brandNm']) || !trim($_REQUEST['p_brandNm'])) {
    die(0);
}
if (!isset($_REQUEST['p_brandNo']) || !trim($_REQUEST['p_brandNo'])) {
    die(0);
}
if (!isset($_REQUEST['p_modelNm']) || !trim($_REQUEST['p_modelNm'])) {
    die(0);
}
if (!isset($_REQUEST['p_modelNo']) || !trim($_REQUEST['p_modelNo'])) {
    die(0);
}
if (!isset($_REQUEST['p_lineupNm']) || !trim($_REQUEST['p_lineupNm'])) {
    die(0);
}
if (!isset($_REQUEST['p_lineupNo']) || !trim($_REQUEST['p_lineupNo'])) {
    die(0);
}
if (!isset($_REQUEST['p_trimNm']) || !trim($_REQUEST['p_trimNm'])) {
    die(0);
}
if (!isset($_REQUEST['p_trimNo']) || !trim($_REQUEST['p_trimNo'])) {
    die(0);
}
if (!isset($_REQUEST['p_carPrice']) || !trim($_REQUEST['p_carPrice'])) {
    die(0);
}

$idx = strtotime(date("Y-m-d h:i:s"));
$brandNm = addslashes(clean_xss_tags(trim($_REQUEST['p_brandNm'])));
$brandNo = addslashes(clean_xss_tags(trim($_REQUEST['p_brandNo'])));
$modelNm = addslashes(clean_xss_tags(trim($_REQUEST['p_modelNm'])));
$modelNo = addslashes(clean_xss_tags(trim($_REQUEST['p_modelNo'])));
$lineupNm = addslashes(clean_xss_tags(trim($_REQUEST['p_lineupNm'])));
$lineupNo = addslashes(clean_xss_tags(trim($_REQUEST['p_lineupNo'])));
$trimNm = addslashes(clean_xss_tags(trim($_REQUEST['p_trimNm'])));
$trimNo = addslashes(clean_xss_tags(trim($_REQUEST['p_trimNo'])));
$carPrice = addslashes(clean_xss_tags(trim($_REQUEST['p_carPrice'])));
$optNm = addslashes(clean_xss_tags(trim($_REQUEST['p_optNm'])));
$optPrice = addslashes(clean_xss_tags(trim($_REQUEST['p_optPrice'])));
$optNm = substr($optNm, 0, -1);
$optPrice = substr($optPrice, 0, -1);

$sql = " insert into {$g5['chadeal_estimatelist_table']}
         set idx = '$idx',            
             brandNm = '$brandNm',
             brandNo = '$brandNo',            
             modelNm = '$modelNm',
             modelNo = '$modelNo',
             lineupNm = '$lineupNm',
             lineupNo = '$lineupNo',  
             trimNm = '$trimNm',
             trimNo = '$trimNo',
             carPrice = '$carPrice',
             optNm = '$optNm',
             optPrice = '$optPrice',  
             chadeal_datetime = '" . G5_TIME_YMDHIS . "' ";
sql_query($sql);

echo $idx;
