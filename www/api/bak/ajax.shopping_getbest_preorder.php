<?php
include_once('./_common.php');
@include_once(G5_PLUGIN_PATH . "/sms5/JSON.php");
// 
if (!function_exists('json_encode')) {
    function json_encode($data)
    {
        $json = new Services_JSON();
        return ($json->encode($data));
    }
}
// 
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
// 
function arr_sort($array, $key, $sort)
{
    $keys = array();
    $vals = array();
    foreach ($array as $k => $v) {
        $i = $v[$key] . '.' . $k;
        $vals[$i] = $v;
        array_push($keys, $k);
    }
    unset($array);

    if ($sort == 'asc') {
        ksort($vals);
    } else {
        krsort($vals);
    }

    $ret = array_combine($keys, $vals);

    unset($keys);
    unset($vals);

    return $ret;
}
// --------------------------------------------------------- //

if (!isset($_REQUEST['nation']) || !$_REQUEST['nation']) {
    $nation = "KR";
} else {
    $nation = addslashes(clean_xss_tags(trim($_REQUEST['nation'])));
}

if ($nation == "KR") {
    $sql_where = "WHERE nation='KR'";
    $sql_order = "ORDER BY PRE_COUNT DESC, pretaxPrice DESC";
} else {
    $sql_where = "WHERE orderType='IM'";
    $sql_order = "ORDER BY PRE_COUNT DESC, carPrice DESC";
}

if (!isset($_REQUEST['brandNo']) || !$_REQUEST['brandNo']) {
    $brandNoP = "";
} else {
    $brandNoP = addslashes(clean_xss_tags(trim($_REQUEST['brandNo'])));
    if ($nation == "KR") {
        $sql_where .= "AND brnNo=$brandNoP";
    } else {
        $sql_where .= "AND brandNo=$brandNoP";
    }
}

$modelList = array();
if ($nation == "KR") {
    $modelQry = sql_query("SELECT DISTINCT brnNo, brnNm, modlNo, modlNm FROM (SELECT DISTINCT brnNo, brnNm, modlNo, modlNm, pretaxPrice FROM {$g5['shopping_preorder_kr_table']} $sql_where) AS kr LEFT JOIN shopping_count AS cnt ON kr.modlNo = cnt.MODL_C_NO $sql_order");
} else {
    $modelQry = sql_query("SELECT DISTINCT brandNo, brandNm, modelNo, modelNm FROM (SELECT DISTINCT brandNo, brandNm, modelNo, modelNm, carPrice FROM {$g5['shopping_preorder_im_table']} $sql_where) AS im LEFT JOIN shopping_count AS cnt ON im.modelNo = cnt.MODL_C_NO $sql_order");
}
while ($modelRes = sql_fetch_array($modelQry)) array_push($modelList, $modelRes);

$list = array();
foreach ($modelList as $item) {
    if ($nation == "KR") {
        // 모델, 브랜드
        $brandNo = $item["brnNo"];
        $brandNm = $item["brnNm"];
        $modelNo = $item["modlNo"];
        $modelNm = $item["modlNm"];
        // 최저가, 최고가, 전체대수
        $itemRow = sql_fetch("SELECT DISTINCT MAX(pretaxPrice) AS maxPrice, MIN(pretaxPrice) AS minPrice, SUM(orderAmount) AS count FROM {$g5['shopping_preorder_kr_table']} WHERE modlNo = {$modelNo}");
        $maxPrice = $itemRow["maxPrice"];
        $minPrice = $itemRow["minPrice"];
        $count = $itemRow["count"];
        // $item 설정
        $item["brandNo"] = $brandNo;
        $item["brandNm"] = $brandNm;
        $item["modelNo"] = $modelNo;
        $item["modelNm"] = $modelNm;
        $item["maxPrice"] = $maxPrice;
        $item["minPrice"] = $minPrice;
        $item["count"] = $count;
    } else {
        // 모델, 브랜드
        $brandNo = $item["brandNo"];
        $brandNm = $item["brandNm"];
        $modelNo = $item["modelNo"];
        $modelNm = $item["modelNm"];
        // 최저가, 최고가, 전체대수
        $itemRow = sql_fetch("SELECT DISTINCT MAX(carPrice) AS maxPrice, MIN(carPrice) AS minPrice, COUNT(*) AS count FROM {$g5['shopping_preorder_im_table']} WHERE modelNo = {$modelNo}");
        $maxPrice = $itemRow["maxPrice"];
        $minPrice = $itemRow["minPrice"];
        $count = $itemRow["count"];
        // $item 설정
        $item["brandNo"] = $brandNo;
        $item["brandNm"] = $brandNm;
        $item["modelNo"] = $modelNo;
        $item["modelNm"] = $modelNm;
        $item["maxPrice"] = $maxPrice;
        $item["minPrice"] = $minPrice;
        $item["count"] = $count;
    }
    array_push($list, $item);
}

for ($i = 0; $i < 3; $i++) {

    $item = $list[$i];

    $brandNo = $item["brandNo"];
    $brandNm = $item["brandNm"];
    $modelNo = $item["modelNo"];
    $modelNm = $item["modelNm"];
    $maxPrice = floor((int)$item["maxPrice"] / 10000);
    $minPrice = floor((int)$item["minPrice"] / 10000);

    if ($maxPrice != 0) {
        echo '
<li>
    <a class="main-best-link" data-brandnm="' . $brandNm . '" data-modelnm="' . $modelNm . '" data-modelno="' . $modelNo . '">
        <i class="img-box">
            <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="" title="">
        </i>
        <div class="txt">
            <strong>' . $modelNm . '</strong>
            <p><span>' . number_format($minPrice) . '~' . number_format($maxPrice) . '만원</span></p>
        </div>
    </a>
</li>';
    }
}
