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
//-------------------------------------------//

if (!isset($_REQUEST['nation']) || !$_REQUEST['nation']) {
    die(0);
} else {
    $nation = addslashes(clean_xss_tags(trim($_REQUEST['nation'])));
}

if (!isset($_REQUEST['brandNo']) || !$_REQUEST['brandNo']) {
    $brandNoP = "";
    $allClass = "logo-box-btn type-a on";
} else {
    $brandNoP = addslashes(clean_xss_tags(trim($_REQUEST['brandNo'])));
    $allClass = "logo-box-btn type-a";
}

if ($nation == "KR") {
    $sql_where = "WHERE nation='KR'";
    $qry = sql_query("SELECT DISTINCT brnNo, brnNm FROM {$g5['shopping_preorder_kr_table']} $sql_where");
} else {
    $sql_where = "WHERE orderType='IM'";
    $qry = sql_query("SELECT DISTINCT brandNo, brandNm FROM {$g5['shopping_preorder_im_table']} $sql_where");
}

$brandList = array();
while ($res = sql_fetch_array($qry)) array_push($brandList, $res);

echo '
<li>
    <button type="button" class="' . $allClass . '" id="brandNo-btn" data-brandNo="" data-nation="' . $nation . '">
        <p>ALL</p>
        <span>전체</span>
    </button>
</li>';

if ($nation == "KR") {
    foreach ($brandList as $item) {
        $brandNo = $item["brnNo"];
        $brandNm = $item["brnNm"];

        if ($brandNoP == $brandNo) {
            $btnClass = "logo-box-btn on";
        } else {
            $btnClass = "logo-box-btn";
        }
        echo '
<li>
    <button type="button" class="' . $btnClass . '" id="brandNo-btn" data-brandNo="' . $brandNo . '" data-nation="' . $nation . '">
        <p>
            <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
        </p>
        <span>' . $brandNm . '</span>
    </button>
</li>';
    }

    echo '
<li>
    <button type="button" class="logo-box-btn type-b" id="nation-btn" data-nation="FR">
        <p>수입차</p>
    </button>
</li>
    ';
} else {
    foreach ($brandList as $item) {
        $brandNo = $item["brandNo"];
        $brandNm = $item["brandNm"];

        if ($brandNoP == $brandNo) {
            $btnClass = "logo-box-btn on";
        } else {
            $btnClass = "logo-box-btn";
        }
        echo '
<li>
    <button type="button" class="' . $btnClass . '" id="brandNo-btn" data-brandNo="' . $brandNo . '" data-nation="' . $nation . '">
        <p>
            <img src="https://cdn.rchada.com/img/brand/' . $brandNo . '.png" alt="">
        </p>
        <span>' . $brandNm . '</span>
    </button>
</li>';
    }

    echo '
<li>
    <button type="button" class="logo-box-btn type-b" id="nation-btn" data-nation="KR">
        <p>국산차</p>
    </button>
</li>
';
}
