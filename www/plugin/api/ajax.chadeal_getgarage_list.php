
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

if (!isset($_REQUEST['mb_id']) || !$_REQUEST['mb_id']) {
    die(0);
} else {
    $mb_id = addslashes(clean_xss_tags(trim($_REQUEST['mb_id'])));
}

$garageList = array();
$garageQry = sql_query("select * FROM {$g5['chadeal_garagelist_table']} WHERE mb_id = '{$mb_id}'");
while ($garageRes = sql_fetch_array($garageQry)) array_push($garageList, $garageRes);

$estimateList = array();
for ($i = 0; $i < count($garageList); $i++) {
    $est_idx = $garageList[$i]["est_idx"];
    $estimateQry = sql_query("select * FROM {$g5['chadeal_estimatelist_table']} WHERE idx = '{$est_idx}'");
    while ($estimateRes = sql_fetch_array($estimateQry)) array_push($estimateList, $estimateRes);
}

// 230208
$sheetList = array();
for ($i = 0; $i < count($garageList); $i++) {
    $sheet_idx = $garageList[$i]["sheet_idx"];
    if (!isset($sheet_idx) || !$sheet_idx) {
        $sheetRes = array();
        array_push($sheetList, $sheetRes);
    } else {
        $sheetQry = sql_query("select * FROM {$g5['chadeal_sheetlist_table']} WHERE idx = '{$sheet_idx}'");
        while ($sheetRes = sql_fetch_array($sheetQry)) array_push($sheetList, $sheetRes);
    }
}

if (count($garageList) > 0) {
    for ($i = 0; $i < count($garageList); $i++) {
        $sheet_idx = $garageList[$i]["sheet_idx"];
        if (!isset($sheet_idx) || !$sheet_idx) {
            showGarage($garageList, $garageList, $estimateList, $i);
        } else {
            showGarage($garageList, $sheetList, $estimateList, $i);
        }
    }
}

function showGarage($garageList, $sheetList, $estimateList, $i)
{
    $idx = $garageList[$i]["idx"];
    $stockTypeEng = $sheetList[$i]["stockType"];
    $cost = $sheetList[$i]["cost"];
    $tax = $sheetList[$i]["tax"];
    $bond = $sheetList[$i]["bond"];
    $cashback = $sheetList[$i]["cashback"];
    $period = $sheetList[$i]["period"];
    $prepaid = $sheetList[$i]["prepaid"];
    $rate = $sheetList[$i]["rate"];
    $monprice = $sheetList[$i]["monprice"];
    $deposit = $sheetList[$i]["deposit"];

    $brandNo = $estimateList[$i]["brandNo"];
    $modelNo = $estimateList[$i]["modelNo"];
    $modelNm = $estimateList[$i]["modelNm"];
    $lineupNm =  $estimateList[$i]["lineupNm"];
    $trimNm =  $estimateList[$i]["trimNm"];

    if ($stockTypeEng == "F") {
        $stockType = "일시불";
        $cilist = '
        <li class="dot">등록비용<strong>' . $cost . '</strong></li>
        <li class="dot">취득세<strong>' . $tax . '</strong></li>
        <li class="dot">공채할인<strong>' . $bond . '</strong></li>';
        $ciprice = '
        <span>캐시백</span>
        <p><strong>' . $cashback . '</strong>원~</p>';
    } else if ($stockTypeEng == "I") {
        $stockType = "할부";
        $cilist = '
        <li class="dot">할부기간<strong>' . $period . '</strong></li>
        <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
        <li class="dot">금리<strong>' . $rate . '</strong></li>';
        $ciprice = '
        <span>월 할부금(vat 포함)</span>
        <p><strong>' . $monprice . '</strong>원~</p>';
    } else if ($stockTypeEng == "R") {
        $stockType = "렌트";
        $cilist = '
        <li class="dot">계약기간<strong>' . $period . '</strong></li>
        <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
        <li class="dot">보증금<strong>' . $deposit . '</strong></li>';
        $ciprice = '
        <span>월 대여료(vat 포함)</span>
        <p><strong>' . $monprice . '</strong>원~</p>';
    } else if ($stockTypeEng == "L") {
        $stockType = "리스";
        $cilist = '
        <li class="dot">계약기간<strong>' . $period . '</strong></li>
        <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
        <li class="dot">보증금<strong>' . $deposit . '</strong></li>';
        $ciprice = '
        <span>월 리스료(vat 포함)</span>
        <p><strong>' . $monprice . '</strong>원~</p>';
    }

    if ($i < 6) {
        echo '
            <div class="car-item" data-idx="' . $idx . '">
                <div class="ci-title">
                    <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
                    <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
                </div>
                <p class="ci-car-img imgbox">
                    <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
                </p>
                <div class="ci-name">
                    <p class="imgbox">
                        <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
                    </p>
                    <strong>' . $modelNm . '</strong>
                </div>
                <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
                <ul class="ci-list">
                ' . $cilist . '
                </ul>
                <div class="ci-price">
                ' . $ciprice . '
                </div>
            </div>';
    } else if ($i > 5 && $i == count($garageList) - 1) {
        echo '
            <div class="car-item" id="hidden" data-idx="' . $idx . '">
                <div class="ci-title">
                    <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
                    <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
                </div>
                <p class="ci-car-img imgbox">
                    <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
                </p>
                <div class="ci-name">
                    <p class="imgbox">
                        <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
                    </p>
                    <strong>' . $modelNm . '</strong>
                </div>
                <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
                <ul class="ci-list">
                ' . $cilist . '
                </ul>
                <div class="ci-price">
                ' . $ciprice . '
                </div>
            </div>
            <button class="garage-more-btn">더보기</button>';
    } else {
        echo '
            <div class="car-item" id="hidden" data-idx="' . $idx . '">
                <div class="ci-title">
                    <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
                    <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
                </div>
                <p class="ci-car-img imgbox">
                    <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
                </p>
                <div class="ci-name">
                    <p class="imgbox">
                        <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
                    </p>
                    <strong>' . $modelNm . '</strong>
                </div>
                <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
                <ul class="ci-list">
                ' . $cilist . '
                </ul>
                <div class="ci-price">
                ' . $ciprice . '
                </div>
            </div>';
    }
}

// if (count($garageList) > 0) {

//     for ($i = 0; $i < count($garageList); $i++) {
//         $idx = $garageList[$i]["idx"];
//         $cost = $garageList[$i]["cost"];
//         $tax = $garageList[$i]["tax"];
//         $bond = $garageList[$i]["bond"];
//         $cashback = $garageList[$i]["cashback"];
//         $period = $garageList[$i]["period"];
//         $prepaid = $garageList[$i]["prepaid"];
//         $rate = $garageList[$i]["rate"];
//         $monprice = $garageList[$i]["monprice"];
//         $deposit = $garageList[$i]["deposit"];

//         if ($stockTypeEng == "F") {
//             $stockType = "일시불";
//             $cilist = '
//         <li class="dot">등록비용<strong>' . $cost . '</strong></li>
//         <li class="dot">취득세<strong>' . $tax . '</strong></li>
//         <li class="dot">공채할인<strong>' . $bond . '</strong></li>';
//             $ciprice = '
//         <span>캐시백</span>
//         <p><strong>' . $cashback . '</strong>원~</p>';
//         } else if ($stockTypeEng == "I") {
//             $stockType = "할부";
//             $cilist = '
//         <li class="dot">할부기간<strong>' . $period . '</strong></li>
//         <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
//         <li class="dot">금리<strong>' . $rate . '</strong></li>';
//             $ciprice = '
//         <span>월 할부금(vat 포함)</span>
//         <p><strong>' . $monprice . '</strong>원~</p>';
//         } else if ($stockTypeEng == "R") {
//             $stockType = "렌트";
//             $cilist = '
//         <li class="dot">계약기간<strong>' . $period . '</strong></li>
//         <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
//         <li class="dot">보증금<strong>' . $deposit . '</strong></li>';
//             $ciprice = '
//         <span>월 대여료(vat 포함)</span>
//         <p><strong>' . $monprice . '</strong>원~</p>';
//         } else if ($stockTypeEng == "R") {
//             $stockType = "리스";
//             $cilist = '
//         <li class="dot">계약기간<strong>' . $period . '</strong></li>
//         <li class="dot">선납금<strong>' . $prepaid . '</strong></li>
//         <li class="dot">보증금<strong>' . $deposit . '</strong></li>';
//             $ciprice = '
//         <span>월 리스료(vat 포함)</span>
//         <p><strong>' . $monprice . '</strong>원~</p>';
//         }

//         if ($i < 6) {
//             echo '
//             <div class="car-item" data-idx="' . $idx . '">
//                 <div class="ci-title">
//                     <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
//                     <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
//                 </div>
//                 <p class="ci-car-img imgbox">
//                     <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
//                 </p>
//                 <div class="ci-name">
//                     <p class="imgbox">
//                         <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
//                     </p>
//                     <strong>' . $modelNm . '</strong>
//                 </div>
//                 <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
//                 <ul class="ci-list">
//                 ' . $cilist . '
//                 </ul>
//                 <div class="ci-price">
//                 ' . $ciprice . '
//                 </div>
//             </div>';
//         } else if ($i > 5 && $i == count($garageList) - 1) {
//             echo '
//             <div class="car-item" id="hidden" data-idx="' . $idx . '">
//                 <div class="ci-title">
//                     <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
//                     <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
//                 </div>
//                 <p class="ci-car-img imgbox">
//                     <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
//                 </p>
//                 <div class="ci-name">
//                     <p class="imgbox">
//                         <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
//                     </p>
//                     <strong>' . $modelNm . '</strong>
//                 </div>
//                 <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
//                 <ul class="ci-list">
//                 ' . $cilist . '
//                 </ul>
//                 <div class="ci-price">
//                 ' . $ciprice . '
//                 </div>
//             </div>
//             <button class="garage-more-btn">더보기</button>';
//         } else {
//             echo '
//             <div class="car-item" id="hidden" data-idx="' . $idx . '">
//                 <div class="ci-title">
//                     <span class="state" id="' . $stockTypeEng . '">' . $stockType . '</span>
//                     <button class="ci-btn"><img src="' . G5_THEME_URL . '/common/img/ci-close.svg" alt=""></button>
//                 </div>
//                 <p class="ci-car-img imgbox">
//                     <img src="' . G5_THEME_URL . '/common/img/model/' . $brandNo . '_' . $modelNo . '.png" alt="">
//                 </p>
//                 <div class="ci-name">
//                     <p class="imgbox">
//                         <img src="' . G5_THEME_URL . '/common/img/brand/logo_' . $brandNo . '.png" alt="">
//                     </p>
//                     <strong>' . $modelNm . '</strong>
//                 </div>
//                 <p class="ci-desc">' . $lineupNm . ' ' . $trimNm  . '</p>
//                 <ul class="ci-list">
//                 ' . $cilist . '
//                 </ul>
//                 <div class="ci-price">
//                 ' . $ciprice . '
//                 </div>
//             </div>';
//         }
//     }
// }
