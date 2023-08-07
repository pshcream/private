<?php
include_once('../../common.php');
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

$firstorder_sql = "";
// 국적
if (isset($_REQUEST['nation']) && $_REQUEST['nation']) {
    $nation = addslashes(clean_xss_tags(trim($_REQUEST['nation'])));
    $firstorder_sql .= " nation = '$nation' AND";
}
// 브랜드번호
if (isset($_REQUEST['brnNo']) && $_REQUEST['brnNo']) {
    $brnNo = addslashes(clean_xss_tags(trim($_REQUEST['brnNo'])));
    $firstorder_sql .= " brnNo = '$brnNo' AND";
}
// 모델번호
if (isset($_REQUEST['modlNo']) && $_REQUEST['modlNo']) {
    $modlNo = addslashes(clean_xss_tags(trim($_REQUEST['modlNo'])));
    $firstorder_sql .= " modlNo = '$modlNo' AND";
}
// 라인업번호
if (isset($_REQUEST['trimNo']) && $_REQUEST['trimNo']) {
    $trimNo = addslashes(clean_xss_tags(trim($_REQUEST['trimNo'])));
    $firstorder_sql .= " trimNo = '$trimNo' AND";
}
// 트림번호
if (isset($_REQUEST['dtlTrimNo']) && $_REQUEST['dtlTrimNo']) {
    $dtlTrimNo = addslashes(clean_xss_tags(trim($_REQUEST['dtlTrimNo'])));
    $firstorder_sql .= " dtlTrimNo = '$dtlTrimNo' AND";
}
// 외장색번호
if (isset($_REQUEST['colorNo']) && $_REQUEST['colorNo']) {
    $colorNo = addslashes(clean_xss_tags(trim($_REQUEST['colorNo'])));
    $firstorder_sql .= " colorNo = '$colorNo' AND";
}
// 내장색번호
if (isset($_REQUEST['innerColorNo']) && $_REQUEST['innerColorNo']) {
    $innerColorNo = addslashes(clean_xss_tags(trim($_REQUEST['innerColorNo'])));
    $firstorder_sql .= " innerColorNo = '$innerColorNo' AND";
}

if ($firstorder_sql != "") {
    $firstorder_sql = substr($firstorder_sql, 0, -4);
    $firstorder_sql = "WHERE " . $firstorder_sql;
}

// 빠른출고 데이터
$qlist = array();
$qry = sql_query("select * FROM {$g5['chadeal_car_preordered_table']} $firstorder_sql");
while ($res = sql_fetch_array($qry)) array_push($qlist, $res);

// 브랜드명 검색
$brnNmList = array();
if (isset($_REQUEST['brnNm']) && $_REQUEST['brnNm']) {
    $brnNm = addslashes(clean_xss_tags(trim($_REQUEST['brnNm'])));
    foreach ($qlist as $item) {
        if ($item["brnNm"] == $brnNm) {
            array_push($brnNmList, $item);
        }
    }
} else {
    $brnNmList = $qlist;
}
// 모델명 검색
$modlNmList = array();
if (isset($_REQUEST['modlNm']) && $_REQUEST['modlNm']) {
    $modlNm = addslashes(clean_xss_tags(trim($_REQUEST['modlNm'])));
    foreach ($brnNmList as $item) {
        if ($item["modlNm"] == $modlNm) {
            array_push($modlNmList, $item);
        }
    }
} else {
    $modlNmList = $brnNmList;
}
// 라인업명 검색
$trimNmList = array();
if (isset($_REQUEST['trimNm']) && $_REQUEST['trimNm']) {
    $trimNm = addslashes(clean_xss_tags(trim($_REQUEST['trimNm'])));
    foreach ($modlNmList as $item) {
        if ($item["trimNm"] == $trimNm) {
            array_push($trimNmList, $item);
        }
    }
} else {
    $trimNmList = $modlNmList;
}
// 트림명 검색
$dtlTrimNmList = array();
if (isset($_REQUEST['dtlTrimNm']) && $_REQUEST['dtlTrimNm']) {
    $dtlTrimNm = addslashes(clean_xss_tags(trim($_REQUEST['dtlTrimNm'])));
    foreach ($trimNmList as $item) {
        if ($item["dtlTrimNm"] == $dtlTrimNm) {
            array_push($dtlTrimNmList, $item);
        }
    }
} else {
    $dtlTrimNmList = $trimNmList;
}
// 금융사명 검색
$capitalNmList = array();
if (isset($_REQUEST['capitalNm']) && $_REQUEST['capitalNm']) {
    $capitalNm = addslashes(clean_xss_tags(trim($_REQUEST['capitalNm'])));
    foreach ($dtlTrimNmList as $item) {
        if ($item["capitalNm"] == $capitalNm) {
            array_push($capitalNmList, $item);
        }
    }
} else {
    $capitalNmList = $dtlTrimNmList;
}
// 구매타입 검색
$stockTypeList = array();
if (isset($_REQUEST['stockType']) && $_REQUEST['stockType']) {
    $stockType = addslashes(clean_xss_tags(trim($_REQUEST['stockType'])));
    foreach ($capitalNmList as $item) {
        if ($item["stockType"] == $stockType) {
            array_push($stockTypeList, $item);
        }
    }
} else {
    $stockTypeList = $capitalNmList;
}

print_r($stockTypeList[2]);
