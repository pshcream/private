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

if (!function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

// 빠른출고 데이터
$qlist = array();
$qry = sql_query("select * FROM {$g5['chadeal_preorderedlist_table']}");
while ($res = sql_fetch_array($qry)) array_push($qlist, $res);

// 국산-수입
$orderList = array();
if (!isset($_REQUEST['orderType']) || !$_REQUEST['orderType']) {
    die(0);
} else {
    $orderType = addslashes(clean_xss_tags(trim($_REQUEST['orderType'])));
    foreach ($qlist as $item) {
        if ($item["orderType"] == $orderType) {
            array_push($orderList, $item);
        }
    }
}

// 브랜드번호/이름
$brandList = array();
for ($i = 0; $i < count($orderList); $i++) {
    $brandList[$i]['brandNo'] = $orderList[$i]['brandNo'];
    $brandList[$i]['brandNm'] = $orderList[$i]['brandNm'];
}
$brandList = array_values(array_map("unserialize", array_unique(array_map("serialize", $brandList))));

function preorderBrand($brandList, $orderType)
{
    for ($i = 0; $i < count($brandList); $i++) {
        $imgurl = G5_THEME_URL . "/common/img/brand/logo_" . $brandList[$i]["brandNo"] . ".png";
        if ($i == 0) {
            echo '<div class="swiper-slide on" data-brandno="' . $brandList[$i]["brandNo"] . '" data-ordertype="' . $orderType . '">
            <div class="logo-circle">
            <img src="' . $imgurl . '" alt="">
            </div>
            <p>' . $brandList[$i]["brandNm"] . '</p>
            </div>';
        } else {
            echo '<div class="swiper-slide" data-brandno="' . $brandList[$i]["brandNo"] . '" data-ordertype="' . $orderType . '">
            <div class="logo-circle">
            <img src="' . $imgurl . '" alt="">
            </div>
            <p>' . $brandList[$i]["brandNm"] . '</p>
            </div>';
        }
    }
}

if (count($brandList) > 0) {
    preorderBrand($brandList, $orderType);
} else {
    die(0);
}
