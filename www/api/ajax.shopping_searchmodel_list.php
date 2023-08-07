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

$sql_where = "";
if (!isset($_REQUEST['modelNm']) || !$_REQUEST['modelNm']) {
    die(0);
} else {
    $modelNm = addslashes(clean_xss_tags(trim($_REQUEST['modelNm'])));
    $sql_where .= "AND (MODL_C_NM LIKE '%" . $modelNm . "%')";
}

$list = array();
$qry = sql_query("SELECT * FROM (SELECT a.BRN_C_NO, a.BRN_C_NM, a.MODL_C_NO, a.MODL_C_NM, a.DTL_TRIM_C_NO, a.NATION, a.CLASSIFYCODE, b.R48M2, b.L48M2 FROM {$g5['shopping_cardb_table']} a LEFT JOIN {$g5['shopping_epdata_table']} b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE (R48M2 NOT IN(0)) AND (L48M2 NOT IN(0)) $sql_where ORDER BY R48M2 ASC) AS danawa GROUP BY MODL_C_NO ORDER BY R48M2 DESC");
while ($res = sql_fetch_array($qry)) array_push($list, $res);

foreach ($list as $item) {
    $brandNo = $item["BRN_C_NO"];
    $modelNo = $item["MODL_C_NO"];
    $modelNm = $item["MODL_C_NM"];
    $trimNo = $item["DTL_TRIM_C_NO"];
    $classifycode = $item["CLASSIFYCODE"];

    echo '
    <a href="/list?id=' . $trimNo . '&nation=KR&modelNo=' . $modelNo . 'classifycode=' . $classifycode . '" target="_self">
    <div class="search-left">
        <img src="' . G5_THEME_URL . '/common/img/search_question.png" alt="">
        <p>
            <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
            <span>' . $modelNm . '</span>            
        </p>
    </div>
    <div class="search-right">
        <img src="' . G5_THEME_URL . '/common/img/search_arrow.png" alt="">
    </div>
</a>
    ';
}
