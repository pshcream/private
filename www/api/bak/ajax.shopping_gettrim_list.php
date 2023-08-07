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

$row = sql_fetch("SELECT * FROM shopping_car_db a LEFT JOIN shopping_epdata_idx b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE a.DTL_TRIM_C_NO = $id");

$brandNo = $row["BRN_C_NO"];
$brandNm = $row["BRN_C_NM"];
$modelNo = $row["MODL_C_NO"];
$modelNm = $row["MODL_C_NM"];
$lineupNo = $row["TRIM_C_NO"];
$lineupNm = $row["TRIM_C_NM"];
$trimNo = $row["DTL_TRIM_C_NO"];
$trimNm = $row["DTL_TRIM_C_NM"];
$carPrice = $row["PRICE"];
$displace = $row["DISPLACE"];
$efficiency = $row["EFFICIENCYMIX"];

// 
$url = "https://installment.rchadacort.com/v2_00/car/info/" . $trimNo;
$apikey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWR4IjoiNTdiNzQxYjktNTY1YS00NGM2LThmMmEtZDJiYmI4Y2NmMDhlIiwiaWF0IjoxNjY2Njk1MTA4fQ.7isT1r2qJ0vhdfFqCKKsTwFavTwSlkshruaN8ZFMi0c";
$headers = array("accept: application/x-www-form-urlencoded", "Authorization: Bearer " . $apikey);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

$infoList = $result["info"];

$classifyName = $infoList["classifyName"];
$fuelName = $infoList["fuelName"];

echo '
<div class="main-desc">
    <h2>' . $brandNm . ' ' . $modelNm . '</h2>
    <span>' . $classifyName . '*' . $displace . 'cc*' . $efficiency . 'km/L*' . $fuelName . '</span>
    <table class="table">
        <tbody>
            <tr>
                <th>모델</th>
                <td>' . $lineupNm . '</td>
            </tr>
            <tr>
                <th>차종</th>
                <td>' . $trimNm . '</td>
            </tr>
            <tr>
                <th>차량가</th>
                <td><strong>' . number_format($carPrice) . '</strong>원</td>
            </tr>
        </tbody>
    </table>    
</div>
<div class="main-import-img">
        <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
</div>
';
