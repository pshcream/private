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

if (!isset($_REQUEST['orderType']) || !$_REQUEST['orderType']) {
    die(0);
} else {
    $orderType = addslashes(clean_xss_tags(trim($_REQUEST['orderType'])));
}
// 빠른출고 데이터 
$qlistOld = array();
$qryOld = sql_query("select * FROM {$g5['chadeal_preorderedlist_table']} WHERE orderType = '{$orderType}' LIMIT 1");
while ($resOld = sql_fetch_array($qryOld)) array_push($qlistOld, $resOld);

// 기존 데이터 날짜 확인
$timeData = strtotime(date('Y-m-d', strtotime($qlistOld[0]['chadeal_datetime'])));
$timeNow = strtotime(date("Y-m-d"));

if ($timeNow > $timeData || count($qlistOld) == 0) {
    $url = "https://installment.rchadacort.com/firstorder/order?orderType=" . $orderType;
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

    if ($result[1] > 0) {
        for ($i = 0; $i < $result[1]; $i++) {
            // for ($i = 7; $i < 8; $i++) {
            $idx = $result[0][$i]['idx'];

            //이전날짜 삭제
            sql_query("DELETE FROM {$g5['chadeal_preorderedlist_table']} WHERE orderType = '{$orderType}' AND DATE_FORMAT(chadeal_datetime, '%Y-%m-%d') < '" . G5_TIME_YMD . "'");

            $sql = "select * FROM {$g5['chadeal_preorderedlist_table']} where idx='$idx' ";
            $row = sql_fetch($sql);

            if (!$row['chadeal_id']) {
                $orderType = $result[0][$i]['orderType'];
                $brandNo = $result[0][$i]['brandNo'];
                $brandNm = $result[0][$i]['brandNm'];
                $modelNo = $result[0][$i]['modelNo'];
                $modelNm = $result[0][$i]['modelNm'];
                $lineupNo = $result[0][$i]['lineupNo'];
                $lineupNm = $result[0][$i]['lineupNm'];
                $trimNo = $result[0][$i]['trimNo'];
                $trimNm = $result[0][$i]['trimNm'];
                $carPrice = $result[0][$i]['carPrice'];
                $optNoArr = json_encode($result[0][$i]['optNoArr']);
                $optNmArr = my_json_encode($result[0][$i]['optNmArr']);
                $optDesc = $result[0][$i]['optDesc'];
                $colorNo = $result[0][$i]['colorNo'];
                $colorNm = $result[0][$i]['colorNm'];
                $innerColorNm = $result[0][$i]['innerColorNm'];
                $colorRgbCd = $result[0][$i]['colorRgbCd'];
                $stockType = $result[0][$i]['stockType'];
                $company = $result[0][$i]['company']['name'];
                $motorsNm = $result[0][$i]['motorsNm'];

                $sql = " insert into {$g5['chadeal_preorderedlist_table']}
    					set idx = '$idx',
    						orderType = '$orderType',
    						brandNo = '$brandNo',
    						brandNm = '$brandNm',
    						modelNo = '$modelNo',
    						modelNm = '$modelNm',
    						lineupNo = '$lineupNo',
    						lineupNm = '$lineupNm',
    						trimNo = '$trimNo',
    						trimNm = '$trimNm',
    						carPrice = '$carPrice',
    						optNoArr = '$optNoArr',
    						optNmArr = '$optNmArr',
    						optDesc = '$optDesc',
    						colorNo = '$colorNo',
    						colorNm = '$colorNm',
    						innerColorNm = '$innerColorNm',
    						colorRgbCd = '$colorRgbCd',
    						stockType = '$stockType',
    						company = '$company',
    						motorsNm = '$motorsNm',
    						chadeal_datetime = '" . G5_TIME_YMDHIS . "' ";
                sql_query($sql);
            }
        }
        die(0);
    } else {
        die(0);
    }
} else {
    die(0);
}
