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

// 브랜드 번호
$brandList = array();
if (!isset($_REQUEST['brandNo']) || !$_REQUEST['brandNo']) {
    die(0);
} else {
    $brandNo = addslashes(clean_xss_tags(trim($_REQUEST['brandNo'])));
    foreach ($orderList as $item) {
        if ($item["brandNo"] == $brandNo) {
            array_push($brandList, $item);
        }
    }
}

// 브랜드번호/이름
$modelList = array();
for ($i = 0; $i < count($brandList); $i++) {
    $modelList[$i]['modelNo'] = $brandList[$i]['modelNo'];
    $modelList[$i]['modelNm'] = $brandList[$i]['modelNm'];
    $modelList[$i]['brandNo'] = $brandList[$i]['brandNo'];
    $modelList[$i]['brandNm'] = $brandList[$i]['brandNm'];
    $modelList[$i]['orderType'] = $brandList[$i]['orderType'];
}
$modelList = array_values(array_map("unserialize", array_unique(array_map("serialize", $modelList))));

function preorderModel($modelList, $orderType, $brandNo)
{
    for ($i = 0; $i < count($modelList); $i++) {
        $modelNo = $modelList[$i]["modelNo"];
        $modelNm = $modelList[$i]["modelNm"];
        $brandNm = $modelList[$i]["brandNm"];
        echo '
    <div class="model-item">
        <a href="/list/' . $orderType . '/' . $modelNo . '" target="_self" class="model-link">
          <div class="model-top">
            <div class="name-box">
              <p class="brand-name">' . $brandNm . '</p>
              <p class="car-name">' . $modelNm . '</p>
            </div>
            <div class="logo-box">
              <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt=""/>
            </div>
          </div>
          <div class="model-bottom">
            <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt=""/>
          </div>
        </a>
    </div>';
    }
}

if (count($modelList) > 0) {
    preorderModel($modelList, $orderType, $brandNo);
} else {
    die(0);
}
