<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가;

//------------------------------------------------------------------------------
// 차딜 상수 모음 시작
//------------------------------------------------------------------------------
define('G5_CHADEAL_DIR',        'chadeal');
define('G5_CHADEAL_PATH',       G5_PATH . '/' . G5_CHADEAL_DIR);
define('G5_CHADEAL_URL',        G5_URL . '/' . G5_CHADEAL_DIR);

define('G5_CHADEAL_ADMIN_DIR',        'chadeal_adm');
define('G5_CHADEAL_ADMIN_PATH',       G5_PATH . '/' . G5_CHADEAL_ADMIN_DIR);
define('G5_CHADEAL_ADMIN_URL',        G5_URL . '/' . G5_CHADEAL_ADMIN_DIR);

// 테이블명
$g5['chadeal_prefix']                = 'chadeal_';

$g5['chadeal_preorderedlist_table']                  = $g5['chadeal_prefix'] . 'preordered_list'; //선구매 차량 리스트
$g5['chadeal_estimatelist_table']                    = $g5['chadeal_prefix'] . 'estimate_list'; //견적서 저장
$g5['chadeal_garagelist_table']                      = $g5['chadeal_prefix'] . 'garage_list'; //내차고 저장
$g5['chadeal_sheetlist_table']                      = $g5['chadeal_prefix'] . 'sheet_list'; //내차고 저장

// 게시판 테이블명
$g5['chadeal_application_table']                            = $g5['write_prefix'] . 'application'; //상담신청 저장
$g5['chadeal_financeimg_table']                             = $g5['write_prefix'] . 'finance_img'; //금융사 이미지 파일명

// 
function my_json_encode($arr)
{
    //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
    array_walk_recursive($arr, function (&$item, $key) {
        if (is_string($item)) $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
    });
    return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
}
function getEstDetail($estIdx, $sheetIdx, $submitMm, $submitRg, $wr_id)
{
    global $g5;

    // 1. 견적내기/내차고 일경우
    if ($estIdx && $sheetIdx) {
        $estRow = sql_fetch("SELECT * FROM {$g5['chadeal_estimatelist_table']} WHERE idx='$estIdx'");
        if (!$estRow) {
            return 0;
        }

        $sheetRow = sql_fetch("SELECT * FROM {$g5['chadeal_sheetlist_table']} WHERE idx='$sheetIdx'");
        if (!$sheetRow) {
            return 0;
        }

        $prodTypeEng = $sheetRow["stockType"];
        if ($prodTypeEng == "F") {
            $prodType = "일시불";
        } else if ($prodTypeEng == "I") {
            $prodType = "할부";
        } else if ($prodTypeEng == "R") {
            $prodType = "렌트";
        } else if ($prodTypeEng == "L") {
            $prodType = "리스";
        }
        $cost = $sheetRow["cost"];
        $tax = $sheetRow["tax"];
        $bond = $sheetRow["bond"];
        $cashback = $sheetRow["cashback"];
        $monprice = $sheetRow["monprice"];
        $period = $sheetRow["period"];
        $prepaid = $sheetRow["prepaid"];
        $deposit = $sheetRow["deposit"];
        $distance = $sheetRow["distance"];

        $brandNm = $estRow["brandNm"];
        $modelNm = $estRow["modelNm"];
        $linupNm = $estRow["linupNm"];
        $trimNm = $estRow["trimNm"];
        $optNmList = $estRow["optNm"];
        $optNmArr = explode('|', $optNmList);
        $optPriceList = $estRow["optPrice"];
        $optPriceArr = explode('|', $optPriceList);
        $optPrice = 0;
        foreach ($optPriceArr as $item) {
            $optPrice = $optPrice + (int)$item;
        }
        $carPrice = (int)$estRow["carPrice"];

        $info = "
구분 : $prodType";

        if ($prodTypeEng == "F") {
            $info .= "
캐시백 : $cashback
        ";
        } else if ($prodTypeEng == "I") {
            $info .= " 
견적조건 : $period / 선납금 : $prepaid
견적 : 월 $monprice 원    
       ";
        } else if ($prodTypeEng == "R" || $prodTypeEng == "L") {
            $info .= " 
견적조건 : $period / $distance / 
보증금 : $deposit / 선납금 : $prepaid
견적 : 월 $monprice 원
        ";
        }
        $info .= " 
제조사 : $brandNm
차종 : $modelNm
상세차종 : $linupNm $trimNm

옵션 :";
        for ($i = 0; $i < count($optNmArr); $i++) {
            $info .= "
· $optNmArr[$i] - " . number_format((int)$optPriceArr[$i]) . " 원";
        }
        $info .= "

등록비용 : $cost
취등록세 : $tax / 공채할인 : $bond

기본가 : " . number_format($carPrice) . "
옵션가 : " . number_format($optPrice) . "   

총 차량가 : " . number_format($carPrice + $optPrice) . "

지역 : " . $submitRg . "
추가문의사항 : " . $submitMm;
    }
    // 2.선출고인경우
    else if ($wr_id) {
        $preRow = sql_fetch("SELECT * FROM {$g5['chadeal_application_table']} WHERE wr_id='$wr_id'");
        if (!$preRow) {
            return 0;
        }

        $modelNm = $preRow["wr_3"];
        $linupNm = $preRow["wr_4"];
        $trimNm = $preRow["wr_5"];
        $colorNm = $preRow["wr_6"];
        $innerColorNm = $preRow["wr_7"];
        $optNmList = $preRow["wr_8"];
        $optNmArr = explode('|', $optNmList);

        $info = "
구분 : 선출고
차종 : $modelNm
상세차종 : $linupNm $trimNm
외장색 : $colorNm
내장색 : $innerColorNm

옵션 :";
        for ($i = 0; $i < count($optNmArr); $i++) {
            $info .= "
· $optNmArr[$i]";
        }
        $info .= "

지역 : " . $submitRg . "
추가문의사항 : " . $submitMm;
    }
    // 3. 일반상담인경우
    else {
        $info = "
구분 : 일반상담
지역 : " . $submitRg . "
추가문의사항 : " . $submitMm;
    }

    return $info;
}
// function getEstDetail($est)
// {
//     global $g5;

//     $row = sql_fetch("SELECT * FROM {$g5['pickcar_estimate_table']} WHERE wr_10='$est'");
//     if (!$row) {
//         return 0;
//     }

//     $prodType = ($row['ca_name'] == "렌트") ? "R" : "L";

//     //brn, modl, trim, dtl_trim
//     //304`제네시스|3656`G80|48880`2022년형 가솔린 터보 2.5(개별소비세 3.5% 적용)|75011`2WD A/T
//     $tmp = explode("|", $row['wr_1']);
//     $brn = explode("`", $tmp[0]);
//     $modl = explode("`", $tmp[1]);
//     $trim = explode("`", $tmp[2]);
//     $dtltrim = explode("`", $tmp[3]);

//     //$trim_name = str_replace("(개별소비세 3.5% 적용)", "", $row['TRIM_C_NM']);

//     //extcolor,intcolor,extrgb,intrgb
//     //008`마테호른 화이트(FT7)-무광`690000|C13`어반 브라운/바닐라 베이지 투톤|#e4e3e5|#5f5353/d7d2ce
//     $tmp = explode("|", $row['wr_2']);
//     $extcolor = explode("`", $tmp[0]);
//     $intcolor = explode("`", $tmp[1]);
//     $extrgb = $tmp[2];
//     $intrgb = $tmp[3];

//     //옵션
//     $options = explode("!", $row['wr_content']);

//     //baseprice, baseprice35
//     $tmp = explode("|", $row['wr_3']);
//     $baseprice = (int)$tmp[0];
//     $baseprice35 = (int)$tmp[1];

//     //optionsprice, optionsprice35
//     $tmp = explode("|", $row['wr_4']);
//     $optionsprice = (int)$tmp[0];
//     $optionsprice35 = (int)$tmp[1];

//     //colorprice, colorprice35
//     $tmp = explode("|", $row['wr_5']);
//     $colorprice = (int)$tmp[0];
//     $colorprice35 = (int)$tmp[1];

//     //totalprice, totalprice35
//     $tmp = explode("|", $row['wr_6']);
//     $totalprice = (int)$tmp[0];
//     $totalprice35 = (int)$tmp[1];

//     //payment, takeover
//     $tmp = explode("|", $row['wr_7']);
//     $payment = (int)$tmp[0];
//     $takeover = (int)$tmp[1];

//     //period, deposit, prepaid, dest
//     //48|0|0|서울
//     $tmp = explode("|", $row['wr_8']);
//     $period = (int)$tmp[0];
//     $deposit = (int)$tmp[1];
//     $prepaid = (int)$tmp[2];
//     $dest = $tmp[3];

//     //age, insu, im, distance, mt
//     //26세|1억|30만|2만km|Self
//     $tmp = explode("|", $row['wr_9']);
//     $age = $tmp[0];
//     $insu = $tmp[1];
//     $im = $tmp[2];
//     $distance = $tmp[3];
//     $mt = $tmp[4];

//     unset($tmp);

//     //월 선납대여료
//     $pe = $totalprice * $prepaid / 100;
//     $sunnap_month = floor($pe / $period);
//     //표준렌탈료
//     $stdcost = $payment + $sunnap_month;
//     //보증금
//     $de = $totalprice * $deposit / 100;

//     $type = ($prodType == "R") ? "장기렌트" : "오토리스";
//     $tname = str_replace('(개별소비세 3.5% 적용)', '', $trim[1]);
//     $tname = str_replace('(개별소비세 3.5% 기준)', '', $tname);


//     $info = "
// 구분 : $type
// 견적조건 : $period 개월 / $distance / $dest / $mt / 
//                   보증금 : $deposit % / 선납금 : $prepaid %
// 견적 : 월 " . number_format($payment) . "원 / 만기인수가 : " . number_format($takeover) . "원

// 제조사 : $brn[1]
// 차종 : $modl[1]
// 상세차종 : $tname
// 외장색 : $extcolor[1](" . number_format($extcolor[2]) . " 원)
// 내장색 : $intcolor[1]

// 옵션 :";
//     foreach ($options as $sel) {
//         $opt = explode("`", $sel);
//         $info .= "
// · $opt[2] - " . number_format($opt[1]) . " 원";
//     }

//     $info .= "

// 기본가 : " . number_format($baseprice) . "
// 옵션가 : " . number_format($optionsprice + $extcolor[2]) . "

// 총 차량가 : " . number_format($totalprice);

//     return array($type, $modl[1], $info);
// }

//제조사 납기(기본 현대)
function getPod()
{
    $data = array('brnNo' => '303');
    $url = "http://work.rchada.com/api/rchadaApiPOD.php?" . http_build_query($data);
    $headers = array("accept: application/buyis.v1+json;charset=UTF-8");
    //$headers = array("accept: application/x-www-form-urlencoded;charset=UTF-8;");

    //HEADER있는거
    //$url = "http://installment.rchadacort.com/firstorder/order?companyNo=%5B5%5D".$brandNo;
    //$apikey="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWR4IjoiNTdiNzQxYjktNTY1YS00NGM2LThmMmEtZDJiYmI4Y2NmMDhlIiwiaWF0IjoxNjY2Njk1MTA4fQ.7isT1r2qJ0vhdfFqCKKsTwFavTwSlkshruaN8ZFMi0c";
    //$headers = array("accept: application/x-www-form-urlencoded","Authorization: Bearer ".$apikey);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response, true);

    //print_r($result);

    for ($i = 0; $i < $result[1]; $i++) {
        //print_r($result[0][$i]);
        $imgurl = G5_THEME_URL . "/common/modl_img/" . $result[0][$i]['po_brnNo'] . "_" . $result[0][$i]['po_modlNo'] . ".png";

        //print_r(json_decode($result[0][$i]['po_memo'], true));
        $memo_list = json_decode($result[0][$i]['po_memo'], true);
        $memo = "";
        //echo count($memo_list);
        //echo is_array($memo_list);
        //echo "<br>";
        /*if(is_array($memo_list)){
			foreach($memo_list as $key => $value){
				$memo .= $value."<br/>";
			}
		}*/

        if (count($memo_list) > 0) {
            $memo = "<ol>";
            foreach ($memo_list as $key => $value) {
                $memo .= "<li>" . $value . "</li>";
            }
            $memo .= "</ol>";
        }

        echo "
<li>
	<div class=\"menufact-item\">
		<div class=\"menufact-front\">
			<div class=\"mf-left\">
				<img src=\"" . $imgurl . "\" alt=\"\" />
				<p>{$result[0][$i]['po_modlNm']}</p>
				<span>{$result[0][$i]['po_period']}</span>
			</div>
    		<div class=\"mf-right\">
      			<i class=\"mf-arrow-icon\"></i>
			</div>
		</div>
		<div class=\"menufact-inner\">
			<table class=\"mi-table\">
				<tr>
					<th>평균예상납기<br>(근무일기준)</th>
					<td>{$result[0][$i]['po_period']}</td>
				</tr>
				<tr>
					<th>전월대비</th>
					<td>{$result[0][$i]['po_contrast']}</td>
				</tr>
				<tr>
					<th>차량가 변경 예상일</th>
					<td>{$result[0][$i]['po_chgdate']}</td>
				</tr>
				<tr>
					<th>비고</th>
					<td>{$memo}</td>
				</tr>
			</table>
			<div class=\"mi-bottom\">
				<a href=\"/estimate/rent/{$result[0][$i]['po_brnNo']}/{$result[0][$i]['po_modlNo']}\">렌트 견적 만들기</a>
				<a href=\"/estimate/lease/{$result[0][$i]['po_brnNo']}/{$result[0][$i]['po_modlNo']}\">리스 견적 만들기</a>
			</div>
		</div>
	</div>
</li>
		";
    } //for end
}

function getCarDNWInfo($data)
{
    global $g5;

    if (!is_array($data))
        return 0;

    $dtltrimNO = (int)$data['pickcar_dtltrim'];

    $row = sql_fetch("SELECT * FROM {$g5['pickcar_car_info_table']} WHERE pickcar_dtltrim='$dtltrimNO'");
    if (!$row) {
        $url = "http://installment.rchadacort.com/v2_00/car/info/" . $dtltrimNO;
        $apikey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWR4IjoiNTdiNzQxYjktNTY1YS00NGM2LThmMmEtZDJiYmI4Y2NmMDhlIiwiaWF0IjoxNjY2Njk1MTA4fQ.7isT1r2qJ0vhdfFqCKKsTwFavTwSlkshruaN8ZFMi0c";
        //$apikey="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWR4IjoiNTdiNzQxYjktNTY1YS00NGM2LThmMmEtZDJiYmI4Y2NmMDhlIiwiaWF0IjoxNjY4NTcyMTMyfQ.dJW-BXEG6iHhZGCoILfirdGGbe08XQz95NWvUp2iaNk";
        $headers = array("accept: application/x-www-form-urlencoded", "Authorization: Bearer " . $apikey);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);
        //echo $response;exit;
        //var_dump($result);exit;

        $pickcar_nation = $data['pickcar_nation'];
        $pickcar_brn = $data['pickcar_brn'];
        $pickcar_modl = $data['pickcar_modl'];
        $pickcar_trim = $data['pickcar_trim'];
        $pickcar_dtltrim = $data['pickcar_dtltrim'];
        $pickcar_info = json_encode($result['info'], JSON_UNESCAPED_UNICODE);
        $pickcar_options = json_encode($result['options'], JSON_UNESCAPED_UNICODE);
        $pickcar_colors = json_encode($result['colors'], JSON_UNESCAPED_UNICODE);
        $pickcar_innercolors = json_encode($result['innerColors'], JSON_UNESCAPED_UNICODE);

        $sql = " insert into {$g5['pickcar_car_info_table']}
		                set pickcar_nation = '$pickcar_nation',
		                	 pickcar_brn = '$pickcar_brn',
		                	 pickcar_modl = '$pickcar_modl',
		                     pickcar_trim = '$pickcar_trim',
		                     pickcar_dtltrim = '$pickcar_dtltrim',
		                     pickcar_info = '$pickcar_info',
		                     pickcar_options = '$pickcar_options',
		                     pickcar_colors = '$pickcar_colors',
		                     pickcar_innercolors = '$pickcar_innercolors',
		                     pickcar_datetime = '" . G5_TIME_YMDHIS . "' ";
        sql_query($sql);

        $pickcar_id = sql_insert_id();

        return $pickcar_id;
    } else {
        return $row['pickcar_id'];
    }
}

function getUpCarPrice($cp)
{
    $ict_ori_rate = 0.05; //개소세율(5%)
    $ict_rate = 0.035; //개소세율

    $taxfree = $cp / ((1 + $ict_rate * 1.3) * 1.1); //공장도가격
    $ori_tax1 = $taxfree * $ict_ori_rate; //개소세(5%)
    $ori_tax2 = $ori_tax1 * 0.3; //교육세(5%)
    $ori_vat = ($taxfree + $ori_tax1 + $ori_tax2) * 0.1; //부가세(5%)
    $chg_price = $taxfree + $ori_tax1 + $ori_tax2 + $ori_vat;

    if (($cp % 1000 == 0) || ($cp >= 23000000)) {
        $chg_price = round($chg_price, -4);
    }

    return $chg_price;
}
