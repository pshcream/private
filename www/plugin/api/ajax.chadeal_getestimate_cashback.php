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

function arr_sort($array, $key, $sort)
{
    $keys = array();
    $vals = array();
    foreach ($array as $k => $v) {
        $i = $v[$key] . '.' . $k;
        $vals[$i] = $v;
        array_push($keys, $k);
    }
    unset($array);

    if ($sort == 'asc') {
        ksort($vals);
    } else {
        krsort($vals);
    }

    $ret = array_combine($keys, $vals);

    unset($keys);
    unset($vals);

    return $ret;
}

// ################# //

if (!isset($_REQUEST['trimNo']) || !$_REQUEST['trimNo']) {
    die(0);
} else {
    $trimNo = addslashes(clean_xss_tags(trim($_REQUEST['trimNo'])));
}

if (!isset($_REQUEST['price']) || !$_REQUEST['price']) {
    die(0);
} else {
    $price = addslashes(clean_xss_tags(trim($_REQUEST['price'])));
}

$url = "https://installment.rchadacort.com/v2_00/card/cashback?trimNo=" . $trimNo . "&price=" . $price;
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

$cashbackList = arr_sort($result, "cashback", "desc");

$chadeal_cashback = (int)$price / 1.1 * (float)$config['cf_3'];

// 이미지파일명 데이터
$qlist = array();
$qry = sql_query("select wr_subject, wr_content FROM {$g5['chadeal_financeimg_table']}");
while ($res = sql_fetch_array($qry)) array_push($qlist, $res);
// 

echo '<div class="ql-title"><p>카드사</p><p>캐시백</p></div>';

if (count($cashbackList) > 0) {
    if (count($cashbackList) > 5) {
        for ($i = 0; $i < count($cashbackList); $i++) {

            $financialNm = $cashbackList[$i]["companyNm"];
            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }
            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $cashback = (int)$cashbackList[$i]["cashback"];

            if ($i < 5) {
                echo '<div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="차딜혜택 ' . number_format($chadeal_cashback) . '원" data-cashback="' . number_format($cashback) . '">
                        <div class="ql-top">
                            <p class="table-list-img">
                                <img src="' . $imgurl . '" alt="">
                                <span>차딜혜택 <b>' . number_format($chadeal_cashback) . '</b>원</span>
                            </p>
                            <p class="table-list-price">' . number_format($cashback) . '</p>
                        </div>                           
                      </div>';
            } else if ($i == 5) {
                echo '<div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="차딜혜택 ' . number_format($chadeal_cashback) . '원" data-cashback="' . number_format($cashback) . '">
                        <div class="ql-top">
                            <p class="table-list-img">
                                <img src="' . $imgurl . '" alt="">
                                <span>차딜혜택 <b>' . number_format($chadeal_cashback) . '</b>원</span>
                            </p>
                            <p class="table-list-price">' . number_format($cashback) . '</p>
                        </div>                           
                      </div>
                      <div class="ql-more">
                        <p>더보기</p>
                      </div>
                      ';
            } else if ($i > 5) {
                echo '<div class="ql-slide" id="hidden" data-imgurl="' . $imgurl . '" data-financialnm="차딜혜택 ' . number_format($chadeal_cashback) . '원" data-cashback="' . number_format($cashback) . '">
                        <div class="ql-top">
                            <p class="table-list-img">
                                <img src="' . $imgurl . '" alt="">
                                <span>차딜혜택 <b>' . number_format($chadeal_cashback) . '</b>원</span>
                            </p>
                            <p class="table-list-price">' . number_format($cashback) . '</p>
                        </div>                           
                      </div>';
            }
        }
    } else {
        for ($i = 0; $i < count($cashbackList); $i++) {

            $financialNm = $cashbackList[$i]["companyNm"];
            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }
            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $cashback = (int)$cashbackList[$i]["cashback"];

            echo '<div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="차딜혜택 ' . number_format($chadeal_cashback) . '원' . number_format($cashback) . '">
                        <div class="ql-top">
                            <p class="table-list-img">
                                <img src="' . $imgurl . '" alt="">
                                <span>차딜혜택 <b>' . number_format($chadeal_cashback) . '</b>원</span>
                            </p>
                            <p class="table-list-price">' . number_format($cashback) . '</p>
                        </div>                           
                      </div>';
        }
    }
}
