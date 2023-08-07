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

$url = "https://installment.rchadacort.com/v2_00/car/info/" . $trimNo;
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

$optionList = $result["options"];
$colorList = $result["colors"];

if (count($optionList) > 0) {
    estimateOption($optionList);
} else {
    die(0);
}

if (count($colorList) > 0) {
    estimateColor($colorList);
} else {
    die(0);
}

function estimateOption($optionList)
{
    for ($i = 0; $i < count($optionList); $i++) {
        $optionIdx = $optionList[$i]["idx"];
        $optionNm = $optionList[$i]["name"];
        $optionPrice = $optionList[$i]["price"];
        $restriction = $optionList[$i]["restriction"];
        echo '
        <div class="select-item" data-optionIdx="' . $optionIdx . '" data-optionNm="' . $optionNm . '" data-optionPrice="' . $optionPrice . '" data-restriction="' . $restriction . '">   
            <div class="select-item-inner">
                <div class="check-area"></div>  
                <div class="txt-area">
                    <p class="item-name">' . $optionNm . '</p>
                    <p class="item-price"><span>' . number_format($optionPrice) . '</span>원</p>
                </div>   
            </div>       
        </div>';
    }
}

function estimateColor($colorList)
{
    for ($i = 0; $i < count($colorList); $i++) {
        $optionIdx = $colorList[$i]["idx"];
        $optionNm = $colorList[$i]["name"];
        $optionNm = str_replace('-유료', '', $optionNm);
        $optionPrice = (int)$colorList[$i]["price"];

        if ($optionPrice > 0) {
            echo '
            <div class="select-item" id="color-item" data-optionIdx="' . $optionIdx . '" data-optionNm="' . $optionNm . '" data-optionPrice="' . $optionPrice . '" data-restriction="">   
                <div class="select-item-inner">
                    <div class="check-area"></div>  
                    <div class="txt-area">
                        <p class="item-name">' . $optionNm . '</p>
                        <p class="item-price"><span>' . number_format($optionPrice) . '</span>원</p>
                    </div>   
                </div>       
            </div>';
        }
    }
}
