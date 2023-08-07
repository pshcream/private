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

$sql_where = "";

if (!isset($_REQUEST['nation']) || !$_REQUEST['nation']) {
    die(0);
} else {
    $nation = $_REQUEST['nation'];
    if ($nation == "KR") {
        $sql_where .= " AND (NATION='KR')";
    } else {
        $sql_where .= " AND (NATION!='KR')";
    }
}

if (!isset($_REQUEST['classifycode']) || !$_REQUEST['classifycode']) {
    die(0);
} else {
    $classifycode = $_REQUEST['classifycode'];
    $sql_where .= " AND (CLASSIFYCODE='" . $classifycode . "')";
}

if (!isset($_REQUEST['modelNo']) || !$_REQUEST['modelNo']) {
    die(0);
} else {
    $modelNo = $_REQUEST['modelNo'];
    $sql_where .= " AND (MODL_C_NO!=" . $modelNo . ")";
}

$list = array();

$qry = sql_query("SELECT * FROM (SELECT a.BRN_C_NO, a.BRN_C_NM, a.MODL_C_NO, a.DTL_TRIM_C_NO, a.MODL_C_NM, a.NATION, a.CLASSIFYCODE, b.R48M2, b.L48M2 FROM {$g5['shopping_cardb_table']} a LEFT JOIN {$g5['shopping_epdata_table']} b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE (R48M2 NOT IN(0)) AND (L48M2 NOT IN(0)) $sql_where ORDER BY R48M2 ASC) AS danawa GROUP BY MODL_C_NO ORDER BY R48M2 DESC");
while ($res = sql_fetch_array($qry)) array_push($list, $res);

foreach ($list as $item) {

    $brandNo = $item["BRN_C_NO"];
    $brandNm = $item["BRN_C_NM"];
    $modelNo = $item["MODL_C_NO"];
    $modelNm = $item["MODL_C_NM"];
    $trimNo = $item["DTL_TRIM_C_NO"];
    $classifycode = $item["CLASSIFYCODE"];
    $rentFee = (int)$item["R48M2"];
    $leaseFee = (int)$item["L48M2"];

    echo '
<div class="swiper-slide">
    <div class="img-box">
        <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
    </div>
    <div class="cont-box">
        <div class="model-box">
            <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
            <p>' . $modelNm . '</p>
        </div>
        <div class="price-box">
            <div class="price-row">
                <p class="title" id="rent">렌트</p>
                <p class="cont"><span>' . number_format($rentFee) . '</span>원</p>
            </div>
            <div class="price-row">
                <p class="title" id="lease">리스</p>
                <p class="cont"><span>' . number_format($leaseFee) . '</span>원</p>
            </div>
        </div>
        <div class="btn-box">
            <a class="estimate-btn" href="javascript:similar_apply(`' . $brandNm . ' ' . $modelNm . '`);">
                <p>견적받기</p>
            </a>
            <a class="detail-btn" href="/list?nation=' . $nation . '&id=' . $trimNo . '&modelNo=' . $modelNo . '&classifycode=' . $classifycode . '" target="_self">
                <p>자세히보기</p>
            </a>
        </div>
    </div>
</div>
';
}
