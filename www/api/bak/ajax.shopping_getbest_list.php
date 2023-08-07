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

if (!isset($_REQUEST['brandNo']) || !$_REQUEST['brandNo']) {
    $brandNoP = "";
    $sql_where = "";
} else {
    $brandNoP = addslashes(clean_xss_tags(trim($_REQUEST['brandNo'])));
    $sql_where = "AND (BRN_C_NO=" . $brandNoP . ")";
}

if (!isset($_REQUEST['nation']) || !$_REQUEST['nation']) {
    $nation = "KR";
    $sql_where .= "";
} else {
    $nation = addslashes(clean_xss_tags(trim($_REQUEST['nation'])));
    if ($nation == "KR") {
        $sql_where .= "AND (NATION='KR')";
    } else {
        $sql_where .= "AND (NATION!='KR')";
    }
}

$list = array();
$qry = sql_query("SELECT * FROM (SELECT * FROM (SELECT a.BRN_C_NO, a.BRN_C_NM, a.MODL_C_NO, a.MODL_C_NM, a.DTL_TRIM_C_NO, a.NATION, a.CLASSIFYCODE, b.R48M2, b.L48M2 FROM shopping_car_db a LEFT JOIN shopping_epdata_idx b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE (R48M2 NOT IN(0)) AND (L48M2 NOT IN(0)) $sql_where) AS c GROUP BY MODL_C_NO) AS d LEFT JOIN shopping_count AS e ON d.MODL_C_NO = e.MODL_C_NO ORDER BY EP_COUNT DESC, R48M2 DESC");
while ($res = sql_fetch_array($qry)) array_push($list, $res);

$modelList = array();
$modelQry = sql_query("SELECT * FROM (SELECT a.MODL_C_NO, a.MODL_C_NM FROM shopping_car_db a LEFT JOIN shopping_epdata_idx b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE (R48M2 NOT IN(0)) AND (L48M2 NOT IN(0)) $sql_where) AS c GROUP BY MODL_C_NO");
while ($modelRes = sql_fetch_array($modelQry)) array_push($modelList, $modelRes);

$newList = array();
foreach ($list as $item) {
    foreach ($modelList as $modelItem) {
        if ($item["MODL_C_NM"] == $modelItem["MODL_C_NM"]) {
            $item["MODL_C_NO"] = $modelItem["MODL_C_NO"];
        }
    }
    array_push($newList, $item);
}

for ($i = 0; $i < 3; $i++) {
    $item = $newList[$i];
    $brandNo = $item["BRN_C_NO"];
    $modelNo = $item["MODL_C_NO"];
    $modelNm = $item["MODL_C_NM"];
    $trimNo = $item["DTL_TRIM_C_NO"];
    $classifycode = $item["CLASSIFYCODE"];
    $monthlyFee = floor((int)$item["R48M2"] / 10000);

    if ($monthlyFee != 0) {
        echo '
<li>
    <div class="main-best-link" data-nation="' . $nation . '" data-id="' . $trimNo . '" data-modelNo="' . $modelNo . '" data-classifycode="' . $classifycode . '">
        <i class="img-box">
            <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="" title="">
        </i>
        <div class="txt">
            <strong>' . $modelNm . '</strong>
            <p><span>월 ' . $monthlyFee . '만원~</span></p>
        </div>
    </div>
</li>';
    }
}
