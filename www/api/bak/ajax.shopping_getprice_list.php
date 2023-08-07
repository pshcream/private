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

if (!isset($_REQUEST['id']) || !$_REQUEST['id']) {
    die(0);
} else {
    $id = addslashes(clean_xss_tags(trim($_REQUEST['id'])));
}

if (!isset($_REQUEST['period']) || !$_REQUEST['period']) {
    die(0);
} else {
    $period = (int)addslashes(clean_xss_tags(trim($_REQUEST['period'])));
}

if (!isset($_REQUEST['prodType']) || !$_REQUEST['prodType']) {
    die(0);
} else {
    $prodType = addslashes(clean_xss_tags(trim($_REQUEST['prodType'])));
}

$row = sql_fetch("SELECT * FROM shopping_car_db a LEFT JOIN shopping_epdata_idx b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE a.DTL_TRIM_C_NO = $id");

$monthlyPrice = (int)$row["$prodType"];
$totalPrice = $monthlyPrice * $period;

echo '
<div class="price-item">
    <p class="txt1">월 가격</p>
    <p class="txt2">월 <span id="item-monthly">' . number_format($monthlyPrice) . '</span>원</p>
</div>
<div class="price-item">
    <p class="txt1">월 가격 x 개월</p>
    <p class="txt2"><span id="item-total">' . number_format($totalPrice) . '</span>원</p>
</div>
';
