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

if (!isset($_REQUEST['page']) || !$_REQUEST['page']) {
    $page = 1;
} else {
    $page = (int)addslashes(clean_xss_tags(trim($_REQUEST['page'])));
}

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

$total_count = count($newList);
$page_size = 16;
$total_page = (int)($total_count / $page_size) + ($total_count % $page_size == 0 ? 0 : 1);
$page_row = (int)($total_page / 10) + ($total_page % 10 == 0 ? 0 : 1);
$current_row = (int)($page / 10) + ($page % 10 == 0 ? 0 : 1);

if ($page < ($total_page + 1)) {
    echo '<ul class="car-item-box">';

    for ($i = 0; $i < $page_size; $i++) {
        $itemNo = ((($page - 1) * $page_size) + $i);

        if ($itemNo < $total_count) {
            $item = $newList[$itemNo];

            $brandNo = $item["BRN_C_NO"];
            $brandNm = $item["BRN_C_NM"];
            $modelNo = $item["MODL_C_NO"];
            $modelNm = $item["MODL_C_NM"];
            $trimNo = $item["DTL_TRIM_C_NO"];
            $classifycode = $item["CLASSIFYCODE"];
            $rentFee = (int)$item["R48M2"];
            $leaseFee = (int)$item["L48M2"];

            echo '
    <li class="car-item">
        <p class="img-box">
            <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
        </p>
        <div class="txt">
            <i class="logo-icon">
                <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
            </i>
            <p>' . $modelNm . '</p>
        </div>
        <div class="tbox">
            <table class="car-item-table table">
                <tbody>
                    <tr>
                        <th class="color1">렌트</th>
                        <td>' . number_format($rentFee) . '원</td>
                    </tr>
                    <tr>
                        <th class="color2">리스</th>
                        <td>' . number_format($leaseFee) . '원</td>
                    </tr>
                </tbody>
            </table>
            <div class="car-item-btnbox">
                <button type="button" class="cib-btn" id="cib-btn-estimate" data-brandnm="' . $brandNm . '" data-modelnm="' . $modelNm . '">견적받기</button>
                <div class="cib-btn type-a" id="cib-btn-detail" data-nation="' . $nation . '" data-id="' . $trimNo . '" data-modelNo="' . $modelNo . '" data-classifycode="' . $classifycode . '">자세히보기</div>
            </div>
        </div>
    </li>';
        }
    }

    echo '</ul>';
    echo '<nav class="pg-wrap">';
    if ($page > 10) {
        echo '<a class="pg-page pg-prev-most" href="/?nation=' . $nation . '&page=1&brandNo=' . $brandNoP . '#logo-box">맨처음</a>';
        echo '<a class="pg-page pg-prev" href="/?nation=' . $nation . '&page=' . (($current_row - 1) * 10) . '&brandNo=' . $brandNoP . '#logo-box">이전</a>';
    }
    for ($i = 0; $i < 10; $i++) {
        $pageNo = $i + ($current_row - 1) * 10 + 1;
        if ($pageNo == $page) {
            echo '<a class="pg-page pg-current" href="/?nation=' . $nation . '&page=' . $pageNo . '&brandNo=' . $brandNoP . '#logo-box">' . $pageNo . '</a>';
        } else if ($pageNo < ($total_page + 1)) {
            echo '<a class="pg-page" href="/?nation=' . $nation . '&page=' . $pageNo . '&brandNo=' . $brandNoP . '#logo-box">' . $pageNo . '</a>';
        }
    }
    if ($page_row > $current_row) {
        echo '<a class="pg-page pg-next" href="/?nation=' . $nation . '&page='  . ($current_row * 10 + 1) . '&brandNo=' . $brandNoP . '#logo-box">다음</a>';
        echo '<a class="pg-page pg-next-most" href="/?nation=' . $nation . '&page=' . $total_page . '&brandNo=' . $brandNoP . '#logo-box">맨끝</a>';
    }
    echo '</nav>';
}
