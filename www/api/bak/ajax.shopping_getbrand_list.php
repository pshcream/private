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

// $url = "https://installment.rchadacort.com/v2_00/car/brand";
// $apikey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWR4IjoiMTdmZDZhMTEtOGU0ZS00Y2EwLWFiZWUtMTRkYzQ5YTY1MzMyIiwiaWF0IjoxNjg0MTE1NTUyfQ.3n-dFJv3scKHTn9Kb0nY2aLbFpUrEZRk4Vo8sjbgwOQ";
// $headers = array("accept: application/x-www-form-urlencoded", "Authorization: Bearer " . $apikey);

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// $response = curl_exec($ch);
// curl_close($ch);
// $result = json_decode($response, true);

// $brandList = array();
// if ($nation == "KR") {
//     foreach ($result["data"] as $item) {
//         if ($item["nation"] == "KR") {
//             array_push($brandList, $item);
//         }
//     }
// } else if ($nation == "FR") {
//     foreach ($result["data"] as $item) {
//         if ($item["nation"] != "KR") {
//             array_push($brandList, $item);
//         }
//     }
// }

// 
if ($nation == "KR") {
    $sql_where .= "AND (NATION='KR')";
} else {
    $sql_where .= "AND (NATION!='KR')";
}
$brandList = array();
$qry = sql_query("SELECT * FROM (SELECT a.BRN_C_NO, a.BRN_C_NM, a.NATION FROM shopping_car_db a LEFT JOIN shopping_epdata_idx b ON a.DTL_TRIM_C_NO = b.DTL_TRIM_C_NO WHERE (R48M2 NOT IN(0)) AND (L48M2 NOT IN(0)) $sql_where ORDER BY R48M2 ASC) AS danawa GROUP BY BRN_C_NO");
while ($res = sql_fetch_array($qry)) array_push($brandList, $res);
// 

echo '
<li>
    <button type="button" class="' . $allClass . '" id="brandNo-btn" data-brandNo="" data-nation="' . $nation . '">
        <p>ALL</p>
        <span>전체</span>
    </button>
</li>
<li>
    <button type="button" class="logo-box-btn type-a type-fast" id="preorder-btn" data-nation="' . $nation . '">
        <p>
            <i class="icon"></i>
        </p>
        <span>빠른출고</span>
    </button>
</li>';

foreach ($brandList as $item) {
    // $brandNo = $item["idx"];
    // $brandNm = $item["name"];
    $brandNo = $item["BRN_C_NO"];
    $brandNm = $item["BRN_C_NM"];

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

if ($nation == "KR") {
    echo '<li>
    <button type="button" class="logo-box-btn type-b" id="nation-btn" data-nation="FR">
        <p>수입차</p>
    </button>
</li>
';
} else if ($nation == "FR") {
    echo '<li>
    <button type="button" class="logo-box-btn type-b" id="nation-btn" data-nation="KR">
        <p>국산차</p>
    </button>
</li>
';
}
