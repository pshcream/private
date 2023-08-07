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

$url = "https://installment.rchadacort.com/v2_00/car/brand";
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

if (!isset($_REQUEST['nation']) || !$_REQUEST['nation']) {
    die(0);
} else {
    $nation = addslashes(clean_xss_tags(trim($_REQUEST['nation'])));
}

$brandList = array();

if ($nation == "KR") {
    foreach ($result["data"] as $item) {
        if ($item["nation"] == "KR") {
            array_push($brandList, $item);
        }
    }
} else {
    foreach ($result["data"] as $item) {
        if ($item["nation"] != "KR") {
            array_push($brandList, $item);
        }
    }
}

if (count($brandList) > 0) {
    estimateBrand($brandList);
}

function estimateBrand($brandList)
{
    for ($i = 0; $i < count($brandList); $i++) {
        $brandNo = $brandList[$i]["idx"];
        $brandNm = $brandList[$i]["name"];
        echo '
        <div class="brand-item" data-brandNo="' . $brandNo . '" data-brandNm="' . $brandNm . '">
            <div class="item-inner">
                <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
                <p>' . $brandNm . '</p>
            </div>
        </div>';
    }
}
