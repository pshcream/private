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

if (!isset($_REQUEST['trimCode']) || !$_REQUEST['trimCode']) {
    die(0);
}
if (!isset($_REQUEST['period']) || !$_REQUEST['period']) {
    die(0);
}
if (!isset($_REQUEST['deposit']) || !$_REQUEST['deposit']) {
    if ($_REQUEST['deposit'] != 0) {
        die(0);
    }
}
if (!isset($_REQUEST['prepaid']) || !$_REQUEST['prepaid']) {
    if ($_REQUEST['prepaid'] != 0) {
        die(0);
    }
}
if (!isset($_REQUEST['optionPrice']) || !$_REQUEST['optionPrice']) {
    if ($_REQUEST['optionPrice'] != 0) {
        die(0);
    }
}
if (!isset($_REQUEST['distance']) || !$_REQUEST['distance']) {
    die(0);
}


$trimCode = (int)addslashes(clean_xss_tags(trim($_REQUEST['trimCode'])));
$prodType = "R";
$fee = (float)$config['cf_1'];
$period = (int)addslashes(clean_xss_tags(trim($_REQUEST['period'])));
$deposit = (int)addslashes(clean_xss_tags(trim($_REQUEST['deposit'])));
$prepaid = (int)addslashes(clean_xss_tags(trim($_REQUEST['prepaid'])));
$optionPrice = (int)addslashes(clean_xss_tags(trim($_REQUEST['optionPrice'])));
$discount = 0;
$distance = addslashes(clean_xss_tags(trim($_REQUEST['distance'])));
$tint_f = "미포함";
$tint_s = "미포함";
$blackbox = "미포함";
$trans_arr = "서울";
$ins_age = "26세";
$ins_add = "1억";
$maintenance = "정비제외";
$em = "50만";

$data = array(
    'trimCode' => $trimCode,
    'prodType' => $prodType,
    'fee' => $fee,
    'period' => $period,
    'deposit' => $deposit,
    'prepaid' => $prepaid,
    'optionPrice' => $optionPrice,
    'discount' => $discount,
    'distance' => $distance,
    'tint_f' => $tint_f,
    'tint_s' => $tint_s,
    'blackbox' => $blackbox,
    'trans_arr' => $trans_arr,
    'ins_age' => $ins_age,
    'ins_add' => $ins_add,
    'maintenance' => $maintenance,
    'em' => $em,
);

// $url = "https://work.rchada.com/tior/api.php?trimCode=" . $trimCode . "&prodType=" . $prodType . "&fee=" . $fee . "&period=" . $period . "&deposit=" . $deposit . "&prepaid=" . $prepaid . "&optionPrice=" . $optionPrice . "&discount=" . $discount . "&distance=" . $distance . "&tint_f=" . $tint_f . "&tint_s=" . $tint_s . "&blackbox=" . $blackbox . "&trans_arr=" . $trans_arr . "&ins_age=" . $ins_age . "&ins_add=" . $ins_add . "&maintenance=" . $maintenance . "&em=" . $em;
$url = "http://work.rchada.com/tior/api.php?" . http_build_query($data);
$headers = array("accept: application/x-www-form-urlencoded");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);

if (count($result) > 0) {

    $rentList = arr_sort($result, "payment", "asc");
    // 이미지파일명 데이터
    $qlist = array();
    $qry = sql_query("select wr_subject, wr_content FROM {$g5['chadeal_financeimg_table']}");
    while ($res = sql_fetch_array($qry)) array_push($qlist, $res);

    echo '<div class="ql-title"><p>취급사</p><p>월 대여료</p></div>';

    if (count($rentList) > 5) {
        for ($i = 0; $i < count($rentList); $i++) {

            $financialNm = $rentList[$i]["name"];

            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }

            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $payment = (int)$rentList[$i]["payment"];
            $deposit = (int)$rentList[$i]["deposit"];
            $pre_exp = (int)$rentList[$i]["pre_exp"];
            $takeover = (int)$rentList[$i]["takeover"];
            $totalAmount = (int)$rentList[$i]["totalAmount"];
            $em = $rentList[$i]["em"];
            $distance = $rentList[$i]["distance"];
            $ins = $rentList[$i]["ins"];
            $penalty = $rentList[$i]["penalty"];

            if ($i < 5) {
                echo '
                <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '"  data-monprice="' . number_format($payment) . '" data-deposit="' . number_format($deposit) . '" data-preexp="' . number_format($pre_exp) . '" data-takeover="' . number_format($takeover) . '" data-totalAmount="' . number_format($totalAmount) . '" data-em="' . $em . '" data-distance="' . $distance . '" data-ins="' . $ins . '" data-penalty="' . $penalty . '">
                    <div class="ql-top">
                        <p class="table-list-img">
                            <img src="' . $imgurl . '" alt="">
                            <span>' . $financialNm . '</span>
                        </p>
                        <p class="table-list-price">
                        ' . number_format($payment) . ' <i class="tlp-arrow"></i>
                        </p>
                    </div>
                    <div class="ql-bottom">
                        <ul>
                            <li>
                                <span>보증금</span>
                                <p><strong>' . number_format($deposit) . '</strong>원</p>
                            </li>
                            <li>
                                <span>선납금</span>
                                <p><strong>' . number_format($pre_exp) . '</strong>원</p>
                            </li>
                            <li>
                                <span>만기인수가</span>
                                <p><strong>' . number_format($takeover) . '</strong>원</p>
                            </li>
                            <li>
                                <span>인수시 총비용</span>
                                <p><strong>' . number_format($totalAmount) . '</strong>원</p>
                            </li>
                            <li>
                                <span>면책금</span>
                                <p><strong>' . $em . '</strong></p>
                            </li>
                            <li>
                                <span>운행거리</span>
                                <p><strong>' . $distance . '</strong></p>
                            </li>
                            <li>
                                <span>대물</span>
                                <p><strong>' . $ins . '</strong></p>
                            </li>
                            <li>
                                <span>위약금</span>
                                <p><strong>' . $penalty . '</strong></p>
                            </li>
                        </ul>
                    </div>
                </div>';
            } else if ($i == 5) {
                echo '
                <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '"  data-monprice="' . number_format($payment) . '" data-deposit="' . number_format($deposit) . '" data-preexp="' . number_format($pre_exp) . '" data-takeover="' . number_format($takeover) . '" data-totalAmount="' . number_format($totalAmount) . '" data-em="' . $em . '" data-distance="' . $distance . '" data-ins="' . $ins . '" data-penalty="' . $penalty . '">
                    <div class="ql-top">
                        <p class="table-list-img">
                            <img src="' . $imgurl . '" alt="">
                            <span>' . $financialNm . '</span>
                        </p>
                        <p class="table-list-price">
                        ' . number_format($payment) . ' <i class="tlp-arrow"></i>
                        </p>
                    </div>
                    <div class="ql-bottom">
                        <ul>
                            <li>
                                <span>보증금</span>
                                <p><strong>' . number_format($deposit) . '</strong>원</p>
                            </li>
                            <li>
                                <span>선납금</span>
                                <p><strong>' . number_format($pre_exp) . '</strong>원</p>
                            </li>
                            <li>
                                <span>만기인수가</span>
                                <p><strong>' . number_format($takeover) . '</strong>원</p>
                            </li>
                            <li>
                                <span>인수시 총비용</span>
                                <p><strong>' . number_format($totalAmount) . '</strong>원</p>
                            </li>
                            <li>
                                <span>면책금</span>
                                <p><strong>' . $em . '</strong></p>
                            </li>
                            <li>
                                <span>운행거리</span>
                                <p><strong>' . $distance . '</strong></p>
                            </li>
                            <li>
                                <span>대물</span>
                                <p><strong>' . $ins . '</strong></p>
                            </li>
                            <li>
                                <span>위약금</span>
                                <p><strong>' . $penalty . '</strong></p>
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
                <div class="ql-slide" id="hidden" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '"  data-monprice="' . number_format($payment) . '" data-deposit="' . number_format($deposit) . '" data-preexp="' . number_format($pre_exp) . '" data-takeover="' . number_format($takeover) . '" data-totalAmount="' . number_format($totalAmount) . '" data-em="' . $em . '" data-distance="' . $distance . '" data-ins="' . $ins . '" data-penalty="' . $penalty . '">
                    <div class="ql-top">
                        <p class="table-list-img">
                            <img src="' . $imgurl . '" alt="">
                            <span>' . $financialNm . '</span>
                        </p>
                        <p class="table-list-price">
                        ' . number_format($payment) . ' <i class="tlp-arrow"></i>
                        </p>
                    </div>
                    <div class="ql-bottom">
                        <ul>
                            <li>
                                <span>보증금</span>
                                <p><strong>' . number_format($deposit) . '</strong>원</p>
                            </li>
                            <li>
                                <span>선납금</span>
                                <p><strong>' . number_format($pre_exp) . '</strong>원</p>
                            </li>
                            <li>
                                <span>만기인수가</span>
                                <p><strong>' . number_format($takeover) . '</strong>원</p>
                            </li>
                            <li>
                                <span>인수시 총비용</span>
                                <p><strong>' . number_format($totalAmount) . '</strong>원</p>
                            </li>
                            <li>
                                <span>면책금</span>
                                <p><strong>' . $em . '</strong></p>
                            </li>
                            <li>
                                <span>운행거리</span>
                                <p><strong>' . $distance . '</strong></p>
                            </li>
                            <li>
                                <span>대물</span>
                                <p><strong>' . $ins . '</strong></p>
                            </li>
                            <li>
                                <span>위약금</span>
                                <p><strong>' . $penalty . '</strong></p>
                            </li>
                        </ul>
                    </div>
                </div>';
            }
        }
    } else {
        for ($i = 0; $i < count($rentList); $i++) {

            $financialNm = $rentList[$i]["name"];

            for ($q = 0; $q < count($qlist); $q++) {
                if (strpos($financialNm, $qlist[$q]["wr_subject"]) !== false) {
                    $imgNm = $qlist[$q]["wr_content"];
                    $financialNm = str_replace($qlist[$q]["wr_subject"], '', $financialNm);
                }
            }

            $imgurl = G5_THEME_URL . '/common/img/finance/' . $imgNm . '.png';
            $payment = (int)$rentList[$i]["payment"];
            $deposit = (int)$rentList[$i]["deposit"];
            $pre_exp = (int)$rentList[$i]["pre_exp"];
            $takeover = (int)$rentList[$i]["takeover"];
            $totalAmount = (int)$rentList[$i]["totalAmount"];
            $em = $rentList[$i]["em"];
            $distance = $rentList[$i]["distance"];
            $ins = $rentList[$i]["ins"];
            $penalty = $rentList[$i]["penalty"];
            echo '
        <div class="ql-slide" data-imgurl="' . $imgurl . '" data-financialnm="' . $financialNm . '"  data-monprice="' . number_format($payment) . '" data-deposit="' . number_format($deposit) . '" data-preexp="' . number_format($pre_exp) . '" data-takeover="' . number_format($takeover) . '" data-totalAmount="' . number_format($totalAmount) . '" data-em="' . $em . '" data-distance="' . $distance . '" data-ins="' . $ins . '" data-penalty="' . $penalty . '">
            <div class="ql-top">
                <p class="table-list-img">
                    <img src="' . $imgurl . '" alt="">
                    <span>' . $financialNm . '</span>
                </p>
                <p class="table-list-price">
                ' . number_format($payment) . ' <i class="tlp-arrow"></i>
                </p>
            </div>
            <div class="ql-bottom">
                <ul>
                    <li>
                        <span>보증금</span>
                        <p><strong>' . number_format($deposit) . '</strong>원</p>
                    </li>
                    <li>
                        <span>선납금</span>
                        <p><strong>' . number_format($pre_exp) . '</strong>원</p>
                    </li>
                    <li>
                        <span>만기인수가</span>
                        <p><strong>' . number_format($takeover) . '</strong>원</p>
                    </li>
                    <li>
                        <span>인수시 총비용</span>
                        <p><strong>' . number_format($totalAmount) . '</strong>원</p>
                    </li>
                    <li>
                        <span>면책금</span>
                        <p><strong>' . $em . '</strong></p>
                    </li>
                    <li>
                        <span>운행거리</span>
                        <p><strong>' . $distance . '</strong></p>
                    </li>
                    <li>
                        <span>대물</span>
                        <p><strong>' . $ins . '</strong></p>
                    </li>
                    <li>
                        <span>위약금</span>
                        <p><strong>' . $penalty . '</strong></p>
                    </li>
                </ul>
            </div>
        </div>';
        }
    }
} else {
    die(0);
}
