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

if (!isset($_REQUEST['lineupNo']) || !$_REQUEST['lineupNo']) {
    die(0);
} else {
    $lineupNo = addslashes(clean_xss_tags(trim($_REQUEST['lineupNo'])));
}

$url = "https://installment.rchadacort.com/v2_00/car/trim/" . $lineupNo;
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

$trimList = $result["data"];

if (count($trimList) > 0) {
    estimateTrim($trimList);
} else {
    die(0);
}

function estimateTrim($trimList)
{
    for ($i = 0; $i < count($trimList); $i++) {
        $trimNo = $trimList[$i]["idx"];
        $trimNm = $trimList[$i]["name"];
        $trimPrice = $trimList[$i]["price"];
        echo '
        <div class="select-item" data-trimNo="' . $trimNo . '" data-trimNm="' . $trimNm . '"  data-trimPrice="' . $trimPrice . '">   
            <div class="select-item-inner">
                <div class="check-area"></div>  
                <div class="txt-area">
                    <p class="item-name">' . $trimNm . '</p>
                    <p class="item-price"><span>' . number_format($trimPrice) . '</span>원</p>
                </div>   
            </div>
        </div>';
    }
}
