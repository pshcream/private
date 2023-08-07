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
    $trimNo = (int)addslashes(clean_xss_tags(trim($_REQUEST['trimNo'])));
}

if (!isset($_REQUEST['price']) || !$_REQUEST['price']) {
    die(0);
} else {
    $price = (int)addslashes(clean_xss_tags(trim($_REQUEST['price'])));
}

if (!isset($_REQUEST['period']) || !$_REQUEST['period']) {
    die(0);
} else {
    $period = (int)addslashes(clean_xss_tags(trim($_REQUEST['period'])));
}

if (!isset($_REQUEST['deposit']) || !$_REQUEST['deposit']) {
    die(0);
} else {
    $deposit = round(addslashes(clean_xss_tags(trim($_REQUEST['deposit']))), 2);
}

$url = "https://installment.rchadacort.com/installment/bank?trimNo=" . $trimNo . "&price=" . $price . "&period=" . $period . "&deposit=" . $deposit;
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

// 이미지파일명 데이터
$qlist = array();
$qry = sql_query("select wr_subject, wr_content FROM {$g5['chadeal_financeimg_table']}");
while ($res = sql_fetch_array($qry)) array_push($qlist, $res);
// 

$installmentList = arr_sort($result, "monPrice", "asc");

echo '<div class="ql-title"><p>취급사</p><p>월 할부금</p></div>';

if (count($installmentList) > 0) {
    if (count($installmentList) > 5) {
        for ($i = 0; $i < count($installmentList); $i++) {

            $financialNm = $installmentList[$i]["financialNm"];

            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }

            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $rate = $installmentList[$i]["rate"];
            $monPrice = (int)$installmentList[$i]["monPrice"];
            $interestTotal = (int)$installmentList[$i]["interestTotal"];

            if ($i < 5) {
                echo '
            <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '" data-monprice="' . number_format($monPrice) . '" data-rate="' . $rate . '%" data-interestTotal="' . number_format($interestTotal) . '">
                <div class="ql-top">
                    <p class="table-list-img">
                        <img src="' . $imgurl . '" alt="">
                        <span>' . $financialNm . '</span>
                    </p>
                    <p class="table-list-price">
                    ' . number_format($monPrice) . ' <i class="tlp-arrow"></i>
                    </p>
                </div>
                <div class="ql-bottom">
                    <ul>
                        <li>
                            <span>금리</span>
                            <p><strong>' . $rate . ' </strong>%</p>
                        </li>
                        <li>
                            <span>총이자</span>
                            <p><strong>' . number_format($interestTotal) . ' </strong>원</p>
                        </li>
                    </ul>
                </div>
            </div>';
            } else if ($i == 5) {
                echo '
            <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '" data-monprice="' . number_format($monPrice) . '" data-rate="' . $rate . '%" data-interestTotal="' . number_format($interestTotal) . '">
                <div class="ql-top">
                    <p class="table-list-img">
                        <img src="' . $imgurl . '" alt="">
                        <span>' . $financialNm . '</span>
                    </p>
                    <p class="table-list-price">
                    ' . number_format($monPrice) . ' <i class="tlp-arrow"></i>
                    </p>
                </div>
                <div class="ql-bottom">
                    <ul>
                        <li>
                            <span>금리</span>
                            <p><strong>' . $rate . ' </strong>%</p>
                        </li>
                        <li>
                            <span>총이자</span>
                            <p><strong>' . number_format($interestTotal) . ' </strong>원</p>
                        </li>
                    </ul>
                </div>
            </div>
                  <div class="ql-more">
                    <p>더보기</p>
                  </div>
                  ';
            } else if ($i > 5) {
                echo '
            <div class="ql-slide" id="hidden" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '" data-monprice="' . number_format($monPrice) . '" data-rate="' . $rate . '%" data-interestTotal="' . number_format($interestTotal) . '">
                <div class="ql-top">
                    <p class="table-list-img">
                        <img src="' . $imgurl . '" alt="">
                        <span>' . $financialNm . '</span>
                    </p>
                    <p class="table-list-price">
                    ' . number_format($monPrice) . ' <i class="tlp-arrow"></i>
                    </p>
                </div>
                <div class="ql-bottom">
                    <ul>
                        <li>
                            <span>금리</span>
                            <p><strong>' . $rate . ' </strong>%</p>
                        </li>
                        <li>
                            <span>총이자</span>
                            <p><strong>' . number_format($interestTotal) . ' </strong>원</p>
                        </li>
                    </ul>
                </div>
            </div>';
            }
        }
    } else {
        for ($i = 0; $i < count($installmentList); $i++) {

            $financialNm = $installmentList[$i]["financialNm"];

            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }

            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $rate = $installmentList[$i]["rate"];
            $monPrice = (int)$installmentList[$i]["monPrice"];
            $interestTotal = (int)$installmentList[$i]["interestTotal"];

            echo '
            <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '" data-monprice="' . number_format($monPrice) . '" data-rate="' . $rate . '%" data-interestTotal="' . number_format($interestTotal) . '">
                <div class="ql-top">
                    <p class="table-list-img">
                        <img src="' . $imgurl . '" alt="">
                        <span>' . $financialNm . '</span>
                    </p>
                    <p class="table-list-price">
                    ' . number_format($monPrice) . ' <i class="tlp-arrow"></i>
                    </p>
                </div>
                <div class="ql-bottom">
                    <ul>
                        <li>
                            <span>금리</span>
                            <p><strong>' . $rate . ' </strong>%</p>
                        </li>
                        <li>
                            <span>총이자</span>
                            <p><strong>' . number_format($interestTotal) . ' </strong>원</p>
                        </li>
                    </ul>
                </div>
            </div>';
        }
    }
}
