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

// ################# //

if (!isset($_REQUEST['trimNo']) || !$_REQUEST['trimNo']) {
    die(0);
} else {
    $trimNo = addslashes(clean_xss_tags(trim($_REQUEST['trimNo'])));
}

if (!isset($_REQUEST['optNmArr']) || !$_REQUEST['optNmArr']) {
    die(0);
} else {
    $optNmArr = addslashes(clean_xss_tags(trim($_REQUEST['optNmArr'])));
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
$optNmList = explode("|", $optNmArr);

if (count($optionList) > 0) {

    for ($p = 0; $p < count($optNmList); $p++) {

        for ($i = 0; $i < count($optionList); $i++) {

            if ($optNmList[$p] == $optionList[$i]["name"]) {
                $optPrice = (string)$optionList[$i]["price"] . "|";
                echo $optPrice;
            }
        }
    }
} else {
    die(0);
}
