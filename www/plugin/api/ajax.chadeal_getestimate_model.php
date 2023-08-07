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

if (!isset($_REQUEST['brandNo']) || !$_REQUEST['brandNo']) {
    die(0);
} else {
    $brandNo = addslashes(clean_xss_tags(trim($_REQUEST['brandNo'])));
}

$url = "https://installment.rchadacort.com/v2_00/car/model/" . $brandNo;
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

$modelList = $result["data"];

if (count($modelList) > 0) {
    estimateModel($modelList, $brandNo);
} else {
    die(0);
}

function estimateModel($modelList, $brandNo)
{
    for ($i = 0; $i < count($modelList); $i++) {
        $modelNo = $modelList[$i]["idx"];
        $modelNm = $modelList[$i]["name"];
        echo '
        <div class="select-item" data-modelNo="' . $modelNo . '" data-modelNm="' . $modelNm . '">
            <div class="img-box">
                <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
            </div>
            <p>' . $modelNm . '</p>
        </div>';
    }
}
