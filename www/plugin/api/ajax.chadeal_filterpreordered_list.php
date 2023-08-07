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

// 모델 번호
$modelList = array();
if (!isset($_REQUEST['modelNo']) || !$_REQUEST['modelNo']) {
    die(0);
} else {
    $modelNo = addslashes(clean_xss_tags(trim($_REQUEST['modelNo'])));
    foreach ($orderList as $item) {
        if ($item["modelNo"] == $modelNo) {
            array_push($modelList, $item);
        }
    }
}

// 가격 오름차순 내림차순
$priceList = array();
if (!isset($_REQUEST['priceOrder']) || !$_REQUEST['priceOrder']) {
    $priceOrder = "asc";
} else {
    $priceOrder = addslashes(clean_xss_tags(trim($_REQUEST['priceOrder'])));
}
$priceList = arr_sort($modelList, 'carPrice', $priceOrder);

// // 유종(라인업 이름)
// $fuelList = array();
// if (!isset($_REQUEST['fuelType']) || !$_REQUEST['fuelType']) {
//     $fuelList = $priceList;
// } else {
//     $fuelType = addslashes(clean_xss_tags(trim($_REQUEST['fuelType'])));
//     foreach ($priceList as $item) {
//         if (strpos($item["lineupNm"], $fuelType) == true) {
//             array_push($fuelList, $item);
//         }
//     }
// }

// 라인업명
$lineupList = array();
if (!isset($_REQUEST['lineupNm']) || !$_REQUEST['lineupNm']) {
    $lineupList = $priceList;
} else {
    for ($i = 0; $i < count($_REQUEST['lineupNm']); $i++) {
        $lineupNm = addslashes(clean_xss_tags(trim($_REQUEST['lineupNm'][$i])));
        foreach ($priceList as $item) {
            if (strpos($item["lineupNm"], $lineupNm) !== false) {
                array_push($lineupList, $item);
            }
            // print_r(strpos($item["lineupNm"], $lineupNm));
        }
    }
    $lineupList = array_map("unserialize", array_unique(array_map("serialize", $lineupList)));
}

// 트림명
$trimList = array();
if (!isset($_REQUEST['trimNm']) || !$_REQUEST['trimNm']) {
    $trimList = $lineupList;
} else {
    for ($i = 0; $i < count($_REQUEST['trimNm']); $i++) {
        $trimNm = addslashes(clean_xss_tags(trim($_REQUEST['trimNm'][$i])));
        foreach ($lineupList as $item) {
            if (strpos($item["trimNm"], $trimNm) !== false) {
                array_push($trimList, $item);
            }
            // print_r(strpos($item["trimNm"], $trimNm));
        }
    }
    $trimList = array_map("unserialize", array_unique(array_map("serialize", $trimList)));
}

// 외장색 색상값
$colorList = array();
if (!isset($_REQUEST['colorRgbCd']) || !$_REQUEST['colorRgbCd']) {
    $colorList = $trimList;
} else {
    for ($i = 0; $i < count($_REQUEST['colorRgbCd']); $i++) {
        $colorRgbCd = addslashes(clean_xss_tags(trim($_REQUEST['colorRgbCd'][$i])));
        foreach ($trimList as $item) {
            if ($item["colorRgbCd"] == $colorRgbCd) {
                array_push($colorList, $item);
            }
        }
    }
    $colorList = array_map("unserialize", array_unique(array_map("serialize", $colorList)));
}



// 구매방식 이름
$stockList = array();
if (!isset($_REQUEST['stockType']) || !$_REQUEST['stockType']) {
    $stockList = $colorList;
} else {
    for ($i = 0; $i < count($_REQUEST['stockType']); $i++) {
        $stockType = addslashes(clean_xss_tags(trim($_REQUEST['stockType'][$i])));
        foreach ($colorList as $item) {
            if ($item["stockType"] == $stockType) {
                array_push($stockList, $item);
            }
        }
    }
    $stockList = array_map("unserialize", array_unique(array_map("serialize", $stockList)));
}

die(json_encode($stockList));
