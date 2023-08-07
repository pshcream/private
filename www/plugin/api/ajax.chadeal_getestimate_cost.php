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

if (!isset($_REQUEST['trimNo']) || !$_REQUEST['trimNo']) {
    die(0);
} else {
    $trimNo = addslashes(clean_xss_tags(trim($_REQUEST['trimNo'])));
}

// 1. 취등록세
$url = "https://installment.rchadacort.com/v2_00/tax/person?trimIdx=" . $trimNo;
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

$tax = (int)$result["data"][0]["acquisition"];

// 2. 공채할인
$url = "https://installment.rchadacort.com/v2_00/tax/receivable?trimIdx=" . $trimNo . "&personIdx=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

$receivableVal = (int)$result["data"][0]["receivableVal"];
$receivableRate = (int)$result["data"][0]["receivableRate"];
$bond = (int)$receivableVal * $receivableRate / 100;

// 3. 등록비용
$cost = $tax + $bond;

echo '
<div class="cost-box">
    <span>등록비용</span>
    <strong id="r-cost">' . number_format($cost) . '원</strong>
</div>
<ul class="cis-list">
    <li class="dot">
        <p>취등록세</p>
        <strong id="r-tax">' . number_format($tax) . '원</strong>
    </li>
    <li class="dot">
        <p>공채할인</p>
        <strong id="r-bond">' . number_format($bond) . '원</strong>
    </li>
</ul>';
