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

if (!isset($_REQUEST['modelNo']) || !$_REQUEST['modelNo']) {
    die(0);
} else {
    $modelNo = addslashes(clean_xss_tags(trim($_REQUEST['modelNo'])));
}

$url = "https://installment.rchadacort.com/v2_00/car/lineup/" . $modelNo;
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

$lineupList = $result["data"];

if (count($lineupList) > 0) {
    estimateLineup($lineupList);
} else {
    die(0);
}

function estimateLineup($lineupList)
{
    for ($i = 0; $i < count($lineupList); $i++) {
        $lineupNo = $lineupList[$i]["idx"];
        $lineupNm = $lineupList[$i]["name"];
        echo '
        <div class="select-item" data-lineupNo="' . $lineupNo . '" data-lineupNm="' . $lineupNm . '">  
            <div class="select-item-inner">
                <div class="check-area"></div>  
                <div class="txt-area">
                    <p class="item-name">' . $lineupNm . '</p>
                </div>     
            </div>                  
        </div>';
    }
}
