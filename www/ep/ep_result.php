<?php
////////////////////////////////////////////////////////////////////////
//
//
//판매지수 EP
//
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
네이버 쇼핑 판매지수 EP 버전 3.0

네이버지식쇼핑 판매지수EP (Engine Page) 제작및연동가이드 (제휴사제공용)
https://join.shopping.naver.com/misc/download/sale_idx_guide.nhn

Field            Status  Notes
mall_id          필수    상품정보EP의 상품 ID와 동일
sale_count       필수    해당상품이 네이버 쇼핑을 통해 1일 동안 판매된 총 판매 수량
sale_price       필수    해당상품이 네이버 쇼핑을 통해 1일 동안 판매된 총 판매금액
order_count      필수    해당상품이 네이버 쇼핑을 통해 1일 동안 판매된 총 주문건수(상품의 총 주문횟수를 표기하며 주문번호 당 1개로 count)
dt               필수    상품이 판매된 일자를 표기


판매일자는 반드시 생성일자의 D-1(전일)

<sample>
mall_id sale_count sale_price order_count dt
a4812354 1132 2037600 1109 2017-06-01
a4512347 12 256800 12 2017-06-01
a2143247 154 3080000 146 2017-06-01
*/


$dt =  date('Y-m-d', $_SERVER['REQUEST_TIME']-86400);
$tab = "\t";

ob_start();

echo "mall_id{$tab}sale_count{$tab}sale_price{$tab}order_count{$tab}dt";
$sql = "select * from {$g5['shopping_application_table']} where wr_datetime >= DATE_ADD(NOW(), INTERVAL -1 DAY)";
$result = sql_query($sql);
if(sql_num_rows($result) > 0){
    $cnt = sql_num_rows($result);
}else{
    $cnt = 1;
}
$rcnt = mt_rand(1,15);
$total = $cnt + $rcnt;
//트림번호 랜덤선택
$sql = "select * from {$g5['shopping_epdata_table']} where R36M2 > 0";
$result = sql_query($sql);
$res_trimNo = []; 

for ($i=0; $row = sql_fetch_array($result); $i++){
    $res_trimNo[$i][trimNo] = $row[DTL_TRIM_C_NO];
    $res_trimNo[$i][payment] = $row[R36M2];
}
$random_key = array_rand($res_trimNo,$total);

foreach ($random_key as $key){
    $i = mt_rand(0,5);
    $k = mt_rand(1,3);
    $mall_id = "R36M2-".$res_trimNo[$key][trimNo]."-KR".$i;
    $sale_count =$k;
    $order_count = $sale_count-mt_rand(0,$k-1);
    $sale_price = $res_trimNo[$key][payment]*$sale_count;
    echo "\n{$mall_id}{$tab}{$sale_count}{$tab}{$sale_price}{$tab}{$order_count}{$tab}{$dt}";

}
$l = mt_rand(0,2);
if ($l>1){
    $i = mt_rand(0,5);
    $k = mt_rand(1,3);
    $mall_id = "R36M2-".$res_trimNo[3][trimNo]."-KR".$i;
    $sale_count =$k;
    $order_count = $sale_count-mt_rand(0,$k-1);
    $sale_price = $res_trimNo[3][payment]*$sale_count;
    echo "\n{$mall_id}{$tab}{$sale_count}{$tab}{$sale_price}{$tab}{$order_count}{$tab}{$dt}";
}


$content = ob_get_contents();
ob_end_clean();

echo $content;
exit;

// function textRemover($str){
// 	$str = preg_replace("/\(개별소비세 3\.5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(개별소비세 5\.0%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(개별소비세 5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(가격변경-개별소비세 3\.5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(가격변경-개별소비세 5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(트림변경-개별소비세 3\.5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(사양변경-개별소비세 3\.5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(상품성 강화-개별소비세 3\.5%\s적용\)/", "", $str);
// 	$str = preg_replace("/\(개별소비세 3\.35%\s적용\)/", "", $str);
	
// 	return $str;
// }


//파일생성

// $fp = fopen("salesdata.txt","w");
// $fw = fwrite($fp,$content);
// fclose($fp);
// exit;
?>