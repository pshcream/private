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

$total_count = count($list);
$page_size = 12;
$total_page = (int)($total_count / $page_size) + ($total_count % $page_size == 0 ? 0 : 1);
$page_row = (int)($total_page / 10) + ($total_page % 10 == 0 ? 0 : 1);
$current_row = (int)($page / 10) + ($page % 10 == 0 ? 0 : 1);

if ($page < ($total_page + 1)) {

    echo '<ul class="car-item-box preorder-item-box">';

    for ($i = 0; $i < $page_size; $i++) {

        $itemNo = ((($page - 1) * $page_size) + $i);

        if ($itemNo < $total_count) {

            $item = $list[$itemNo];

            $brandNo = $item["brandNo"];
            $brandNm = $item["brandNm"];
            $modelNo = $item["modelNo"];
            $modelNm = $item["modelNm"];
            $maxPrice = number_format(floor($item["maxPrice"] / 10000));
            $minPrice = number_format(floor($item["minPrice"] / 10000));
            $count = $item["count"];

            echo '
                <li class="car-item">
                    <div class="car-item-inner">
                        <div class="item-left">
                            <div class="alert-row">
                                <img src="' . G5_THEME_URL . '/common/img/clock.png" alt="">
                                <p>마감임박</p>
                            </div>
                            <div class="car-row">
                                <div class="count">
                                    <p><b>' . $count . '대</b><br>남음</p>
                                </div>
                                <img src="https://cdn.rchada.com/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
                            </div>
                        </div>
                        <div class="item-right">
                            <div class="model-row">
                                <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
                                <p>' . $modelNm . '</p>
                            </div>
                            <div class="price-row">
                                <p class="name">차량가</p>
                                <p class="price">
                                    <span class="min">' . $minPrice . '</span> ~ <span class="max">' . $maxPrice . '</span> 만원
                                </p>
                            </div>
                            <div class="btn-row">
                                <div class="submit-btn" id="submit-btn-preorder" data-brandnm="' . $brandNm . '" data-modelNm="' . $modelNm . '" data-modelNo="' . $modelNo . '">
                                    <img src="' . G5_THEME_URL . '/common/img/thunder.png" alt="">
                                    <p>빠른상담</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            ';
        }
    }

    echo '</ul>';
    echo '<nav class="pg-wrap">';
    if ($page > 10) {
        echo '<a class="pg-page pg-prev-most" href="/preorder?nation=' . $nation . '&page=1&brandNo=' . $brandNoP . '#logo-box">맨처음</a>';
        echo '<a class="pg-page pg-prev" href="/preorder?nation=' . $nation . '&page=' . (($current_row - 1) * 10) . '&brandNo=' . $brandNoP . '#logo-box">이전</a>';
    }
    for ($i = 0; $i < 10; $i++) {
        $pageNo = $i + ($current_row - 1) * 10 + 1;
        if ($pageNo == $page) {
            echo '<a class="pg-page pg-current" href="/preorder?nation=' . $nation . '&page=' . $pageNo . '&brandNo=' . $brandNoP . '#logo-box">' . $pageNo . '</a>';
        } else if ($pageNo < ($total_page + 1)) {
            echo '<a class="pg-page" href="/preorder?nation=' . $nation . '&page=' . $pageNo . '&brandNo=' . $brandNoP . '#logo-box">' . $pageNo . '</a>';
        }
    }
    if ($page_row > $current_row) {
        echo '<a class="pg-page pg-next" href="/preorder?nation=' . $nation . '&page='  . ($current_row * 10 + 1) . '&brandNo=' . $brandNoP . '#logo-box">다음</a>';
        echo '<a class="pg-page pg-next-most" href="/preorder?nation=' . $nation . '&page=' . $total_page . '&brandNo=' . $brandNoP . '#logo-box">맨끝</a>';
    }
    echo '</nav>';
}
