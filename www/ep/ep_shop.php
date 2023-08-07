<?php
////////////////////////////////////////////////////////////////////////
//
//
//NH캐피탈(KR,KL,FL)
//
//http://newwork.rchada.com/ep/api/ep_nh.php?epMode=KR&forceFlag=Y
//http://newwork.rchada.com/ep/api/ep_nh.php?epMode=KL&forceFlag=Y
//http://newwork.rchada.com/ep/api/ep_nh.php?epMode=FL&forceFlag=Y
//
//
//
////////////////////////////////////////////////////////////////////////

include_once('./_common.php');
@include_once(G5_PLUGIN_PATH."/sms5/JSON.php");

if( !function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}
//echo http_response_code(300);exit;

/*
EP 버전 3.0

네이버지식쇼핑상품EP (Engine Page) 제작및연동가이드 (제휴사제공용)
http://join.shopping.naver.com/misc/download/ep_guide.nhn

Field                  Status  Notes
id                      필수    판매하는 상품의 유니크한 상품ID
title                  필수    실제 서비스에 반영될 상품명(Title)
price_pc                필수    상품가격(렌탈상품의경우, 계약기간동안 납부해야 하는 총 렌탈료)
link                    필수    상품URL
image_link              필수    해당 상품의 이미지URL
category_name1          필수    카테고리명(대분류)
category_name2          권장    카테고리명(중분류)
category_name3          권장    카테고리명(소분류)
naver_category          권장    네이버카테고리ID(렌탈관 : 100004628) 
 * 여가/생활편의 > 국내렌터카 > 장기렌터카 https://search.shopping.naver.com/search/category?catId=50007262
 * 여가/생활편의 > 국내렌터카(50007261)
 * 여가/생활편의 > 국내렌터카 > 장기렌터카(50007262)
 * 여가/생활편의 > 국내렌터카 > 단기렌터카(50007263)  
product_flag           필수    렌탈
brand                  권장    브랜드
maker                  권장    제조사
origin                  권장    원산지
event_words            권장    이벤트
point                  권장    포인트(ex. 네이버페이포인트^500)
rental_info            *필수     월렌탈료^개월수
search_tag             권장(100자)	띄어쓰기없이 |로 구분 10개까지
shipping               *필수    배송료  무료배송0
attribute              권장(500자)     상품에 속성정보가 있는 경우 ^로 구분하여 텍스트 입력
class                  필수(요약)  I (신규상품) / U (업데이트 상품) / D (품절상품)
update_time            필수(요약)  상품정보 생성 시각 yyyy-mm-dd hh:mm:ss
*/

//7월14일부터 
//price_pc 월 렌탈료(총 렌탈료아님)
//price_mobile 월 렌탈료(총 렌탈료아님)
//product_flag 렌탈(*렌탈상품의경우, 렌탈정보(rental_info) 필수입력/price_pc 월 렌탈료)
//rental_info ex) 총렌탈료^개월수

$COUNTRYCODE = array("CN"=>"중국","DE"=>"독일","FR"=>"프랑스","GB"=>"영국","IT"=>"이탈리아","JP"=>"일본","KR"=>"대한민국","SE"=>"스웨덴","US"=>"미국");
//경차/소형차/중형차/준대형차/대형차|SUV/RV|승합차|수입차|차종선택
$CLASSIFYCODE = array("P0"=>"경차","P1"=>"소형차","P2"=>"소형차","P3"=>"중형차","P4"=>"준대형차","P5"=>"대형차","S0"=>"수입차","R1"=>"SUV/RV","R2"=>"SUV/RV","R3"=>"SUV/RV","M0"=>"승합차","B0"=>"승합차","T0"=>"화물차");
//휘발유/경유/LPG/하이브리드/전기/수소/유종선택
$FUEL = array("01"=>"휘발유","02"=>"경유","03"=>"LPG","05"=>"하이브리드","06"=>"전기","08"=>"수소");

$ep_url = "https://shop.rchada.com/list/?utm_source=naver&utm_medium=cpc&utm_campaign=shop";
$ep_url_img = "https://cdn.rchada.com/img/model4/";

$tab = "\t";
$category_name1 = "여가/생활편의";//"렌탈관";//
$category_name2 = "국내렌터카";//"자동차렌탈";//
$category_name3 = "장기렌터카";//"자동차";//
$naver_category = "50007262";//여가/생활편의 > 국내렌터카 > 장기렌터카
$product_flag = "렌탈";

ob_start();

echo "id{$tab}title{$tab}price_pc{$tab}link{$tab}image_link{$tab}category_name1{$tab}category_name2{$tab}category_name3{$tab}naver_category{$tab}product_flag{$tab}brand{$tab}maker{$tab}origin{$tab}event_words{$tab}point{$tab}rental_info{$tab}search_tag{$tab}shipping{$tab}attribute{$tab}update_time";

$sql =" SELECT B.*,A.* FROM {$g5['shopping_epdata_table']} B LEFT JOIN {$g5['shopping_cardb_table']} A ON A.DTL_TRIM_C_NO=B.DTL_TRIM_C_NO WHERE B.R36M2 > 0 ";
$result = sql_query($sql);
// var_dump(sql_fetch_array($result));
//IUD I(신차),U, A

for ($i=0; $row=sql_fetch_array($result); $i++){
	$price_pc = $row['R36M2'];//7월14일 변경예정
	$link = $ep_url."&id=".$row['DTL_TRIM_C_NO']."&nation=".$row['NATION']."&modelNo=".$row['MODL_C_NO']."&classifycode=".$row['CLASSIFYCODE'];//http://shop.rchada.com/list?id=77263&nation=KR&modelNo=4016&classifycode=P5
	$image_link = $ep_url_img.$row['BRN_C_NO']."_".$row['MODL_C_NO'].".png";
	$period = 36;
	if ($row['BRN_C_NM']=="미니"){
        $brand = "미니쿠퍼";
    }else if ($row['BRN_C_NM']=="KG모빌리티"){
        $brand = "쌍용";
    }else if($row['BRN_C_NM']=="르노코리아"){
        $brand = "르노";
    }else if($row['BRN_C_NM']=="랜드로버"){
        $brand = "랜드로바";
    }else {
        $brand = $row['BRN_C_NM'];
    }
	$maker = $brand;
	$origin = $COUNTRYCODE[$row['NATION']];
	// $event_words = "틴팅+블랙박스+코일매트 무료, 7일내 즉출가능한 특판차량 대기중, ".$FUEL[$row['FUEL']].", ".$person.", ".$period_str;//PC100자,모바일50자 - 썬팅, 블박, 코일매트 무료, 7일내 즉출, 특판차량 대기
	$event_words = "틴팅+블랙박스+코일매트 무료, 7일내 즉출가능한 특판차량 대기중";//PC100자,모바일50자 - 썬팅, 블박, 코일매트 무료, 7일내 즉출, 특판차량 대기
	$point = "";//네이버페이포인트^500
	$rental_info = $row['R36M2']*$period."^".$period;//총비용^개월수 //7월14일 변경예정
	$search_tag = "장기렌트|장기렌터카|리스|수입차리스|자동차리스|오토리스|신차장기렌트카|신차장기렌트";//권장(100자)	띄어쓰기없이 |로 구분 10개까지
	$shipping = 0;//0 - 무료배송
	$attribute = $row['MODL_C_NM']."장기렌트^장기렌터카^".$period."개월^만26세이상^운전경력1년이상^".$FUEL[$row['FUEL']]."^".$CLASSIFYCODE[$row['CLASSIFYCODE']];//XX개월^만26세이상^운전경력1년이상^연료(휘발유/경유/LPG/하이브리드/전기/수소/유종선택)^차량종류(경차/소형차/중형차/준대형차/대형차|SUV/RV|승합차|수입차|차종선택)
	// $class = ($row['IUD']=="I")?"I":"U";//I (신규상품) / U (업데이트 상품) / D (품절상품)
	$update_time = $row['EP_DATETIME'];//yyyy-mm-dd hh:mm:ss
    $lineup=textRemover_l($row['MODL_C_NM']);
    $trim = textRemover_t($row['TRIM_C_NM']);
    $d_trim = textRemover_d($row['DTL_TRIM_C_NM']);
    $person = $row['PERSONS']."인승";
    $period_str = ($period/12)."년";
    $fuel_str = $FUEL[$row['FUEL']];

    $title_keyword_R = ["장기렌트", "신차장기렌트카", "리스", "장기렌터카", "신차장기렌트", "신차장기렌터카"];
    $rcnt = count($title_keyword_R);
	$title_keyword_L = ["개인리스", "리스", "자동차리스","오토리스","장기렌트","장기렌터카"];//["리스", "자동차리스", "오토리스"];
    $lcnt = count($title_keyword_L);
    for ($i=0; $i<$rcnt; $i++){
        //이벤트워드에 개월, 유종, 인승 넣고 타이틀에 빼면 1등
        //차량명 하이브리드,lgp 장기렌트 키워드 확보
        if ($row['NATION']=="KR"){
            $link1= $link."&keyword=".urlencode($title_keyword_R[$i]);
            $id = "R36M2-".$row['DTL_TRIM_C_NO']."-KR".$i;//(영문,숫자,하이픈-,언더스코어_,공백" ")
            $title = $lineup.$title_keyword_R[$i]." ".$trim." ".$d_trim." ".$fuel_str." ".$person." ".$period_str;
        }else{
            $link1= $link."&keyword=".urlencode($title_keyword_L[$i]);
            $id = "R36M2-".$row['DTL_TRIM_C_NO']."-KR".$i;//(영문,숫자,하이픈-,언더스코어_,공백" ")
            $title = $brand." ".$lineup.$title_keyword_L[$i]." ".$trim." ".$d_trim." ".$fuel_str." ".$person." ".$period_str;
        }
        echo "\n{$id}{$tab}{$title}{$tab}{$price_pc}{$tab}{$link1}{$tab}{$image_link}{$tab}{$category_name1}{$tab}{$category_name2}{$tab}{$category_name3}{$tab}{$naver_category}{$tab}{$product_flag}{$tab}{$brand}{$tab}{$maker}{$tab}{$origin}{$tab}{$event_words}{$tab}{$point}{$tab}{$rental_info}{$tab}{$search_tag}{$tab}{$shipping}{$tab}{$attribute}{$tab}{$update_time}";
    }
    for ($i=0; $i<$rcnt; $i++){
        $j = $i + 6;
        if ($fuel_str==="하이브리드"){
            $link1= $link."&keyword=".urlencode($title_keyword_R[$i]);
            $id = "R36M2-".$row['DTL_TRIM_C_NO']."-KR".$j;//(영문,숫자,하이픈-,언더스코어_,공백" ")
            $title = $lineup." ".$fuel_str." ".$title_keyword_R[$i]." ".$trim." ".$d_trim." ".$person." ".$period_str;
            echo "\n{$id}{$tab}{$title}{$tab}{$price_pc}{$tab}{$link1}{$tab}{$image_link}{$tab}{$category_name1}{$tab}{$category_name2}{$tab}{$category_name3}{$tab}{$naver_category}{$tab}{$product_flag}{$tab}{$brand}{$tab}{$maker}{$tab}{$origin}{$tab}{$event_words}{$tab}{$point}{$tab}{$rental_info}{$tab}{$search_tag}{$tab}{$shipping}{$tab}{$attribute}{$tab}{$update_time}";
        }
    }


    
}
$content = ob_get_contents();
ob_end_clean();

echo $content;
exit;

function textRemover_l($str){
    //특수단어변경1
	$str = preg_replace("/^더 뉴\s|디 올 뉴\s/", "", $str); // 그랜저, 코나
	$str = preg_replace("/디 엣지/", "", $str); // 쏘나타
	$str = preg_replace("/Series/", "시리즈", $str); // BMW
	$str = preg_replace("/\-Class/", "클래스", $str); // 벤츠
	$str = preg_replace("/Electrified/", "", $str);
	$str = preg_replace("/The|The New|New|New/", "", $str);
	return $str;
}
function textRemover_t($str){
	$str = preg_replace("/\(개별소비세 3\.5%\s적용\)/", "", $str);
	$str = preg_replace("/\(개별소비세 5\.0%\s적용\)/", "", $str);
	$str = preg_replace("/\(개별소비세 5%\s적용\)/", "", $str);
	$str = preg_replace("/\(가격변경-개별소비세 3\.5%\s적용\)/", "", $str);
	$str = preg_replace("/\(가격변경-개별소비세 5%\s적용\)/", "", $str);
	$str = preg_replace("/\(트림변경-개별소비세 3\.5%\s적용\)/", "", $str);
	$str = preg_replace("/\(사양변경-개별소비세 3\.5%\s적용\)/", "", $str);
	$str = preg_replace("/\(상품성 강화-개별소비세 3\.5%\s적용\)/", "", $str);
	$str = preg_replace("/\(개별소비세 3\.35%\s적용\)/", "", $str);
	$str = preg_replace("/2023년형/", "", $str);
	$str = preg_replace("/2022년형/", "", $str);
    $str = preg_replace("/\(/", "", $str);
	$str = preg_replace("/\)/", "", $str);
	// $str = preg_replace("/\(9인승\)/", "9인승", $str);
	// $str = preg_replace("/\(7인승\)/", "7인승", $str);
	// $str = preg_replace("/\(6인승\)/", "6인승", $str);
	// $str = preg_replace("/\(5인승\)/", "5인승", $str);
	// $str = preg_replace("/\(4인승\)/", "4인승", $str);
	// $str = preg_replace("/\(3인승\)/", "3인승", $str);
	// $str = preg_replace("/\(11인승\)/", "11인승", $str);
	$str = preg_replace("/7\/9인승/", "", $str);
	$str = preg_replace("/9\/11인승/", "", $str);
	$str = preg_replace("/3인승|4인승|5인승|6인승|7인승|8인승|9인승|11인승|12인승/", "", $str);
	$str = preg_replace("/Electrified/", "", $str);
	$str = preg_replace("/터보/", "", $str);
	$str = preg_replace("/N 라인/", "N라인", $str);
	$str = preg_replace("/The|The New|New|New/", "", $str);
	$str = preg_replace("/가솔린|디젤|하이브리드|LPG/", "", $str);
	$str = preg_replace("/렌터카\/장애인용\/택시/", "", $str);
	return $str;
}
function textRemover_d($str){
	// $str = preg_replace("/\(9인승\)/", "9인승", $str);
	// $str = preg_replace("/\(7인승\)/", "7인승", $str);
	// $str = preg_replace("/\(6인승\)/", "6인승", $str);
	// $str = preg_replace("/\(5인승\)/", "5인승", $str);
	// $str = preg_replace("/\(4인승\)/", "4인승", $str);
	// $str = preg_replace("/\(3인승\)/", "3인승", $str);
	// $str = preg_replace("/\(11인승\)/", "11인승", $str);
	$str = preg_replace("/\(/", "", $str);
	$str = preg_replace("/\)/", "", $str);
    $str = preg_replace("/3인승|4인승|5인승|6인승|7인승|8인승|9인승|11인승|12인승/", "", $str);
	return $str;
}


//파일생성
// $filedate = date("YmdHis");
// $filepath = "../data/shop/";
// $epfile = $filepath.'RCHADA_EP.txt';


// $data = "{$res['BRN_C_NM']}^{$res['MODL_C_NM']}^{$res['TRIM_C_NM']}^{$res['DTL_TRIM_C_NM']} {$res['TM']}/T^{$res['BRN_C_NO']}^{$res['MODL_C_NO']}^{$res['TRIM_C_NO']}^{$res['DTL_TRIM_C_NO']}^{$prodType}^{$key}^{$jogun}^{$v[2]}^{$v[3]}^0^Y^{$agreedDistance}^Y^{$additionalExplanation}^" . G5_TIME_YMD . "^{$v[9]}^{$rcode}\n";
// fileWrite($file,$data);

// $qry = sql_query($qryKR);
// while ($res = sql_fetch_array($qry)){}

// // 종료
// fclose($file);
// exit;

// function fileWrite($file,$data){
// 	fwrite($file, $data);
// }
?>