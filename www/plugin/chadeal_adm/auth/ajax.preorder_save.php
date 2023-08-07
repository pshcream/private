<?php
include_once('./_common.php');

//권한체크
if(!$uid||!$member['mb_id']||$is_admin != 'super'){
	die('0');
}

if (!($w == '' || $w == 'u')) {
	die('0');//alert('w 값이 제대로 넘어오지 않았습니다.');
}

$uid = isset($_POST['uid']) ? preg_replace('/[^0-9]/', '', $_POST['uid']) : 0;

//JSON타입 변환
$tmp = stripslashes($_REQUEST['setTrms']);
$_REQUEST['setTrms'] = json_decode($tmp, true);
//var_dump($_REQUEST);exit;

//트림번호 확인
if (!isset($_REQUEST['dtl_trim'])||!$_REQUEST['dtl_trim'])
	die('0');

//차량정보
$data = array();
$data['nh_nation'] = clean_xss_tags(trim($_REQUEST['setTrms']['nation']));
$data['nh_brn'] = clean_xss_tags(trim($_REQUEST['setTrms']['brnNO']));
$data['nh_modl'] = clean_xss_tags(trim($_REQUEST['setTrms']['modlNO']));
$data['nh_trim'] = clean_xss_tags(trim($_REQUEST['setTrms']['trimNO']));
$data['nh_dtltrim'] = clean_xss_tags(trim($_REQUEST['setTrms']['dtltrimNO']));

//API차량정보 저장
$carinfo_id = getCarDNWInfo($data);

//car_info ID값 체크
if(!$carinfo_id)
	die('0');

$data['brnNM'] = clean_xss_tags(trim($_REQUEST['setTrms']['brnNM']));
$data['modlNM'] = clean_xss_tags(trim($_REQUEST['setTrms']['modlNM']));
$data['trimNM'] = clean_xss_tags(trim($_REQUEST['setTrms']['trimNM']));
$data['dtltrimNM'] = clean_xss_tags(trim($_REQUEST['setTrms']['dtltrimNM']));
$data['totalprice'] = (int)clean_xss_tags(trim($_REQUEST['setTrms']['totalprice']));
$data['optNmArr'] = clean_xss_tags(trim($_REQUEST['selectedOptions']));
$data['colorNm'] = clean_xss_tags(trim($_REQUEST['ext_color']));
$data['innColorNm'] = clean_xss_tags(trim($_REQUEST['int_color']));
$data['releaseType'] = clean_xss_tags(trim($_REQUEST['releaseType']));//출고구분
$data['specialExcise'] = clean_xss_tags(trim($_REQUEST['specialExcise']));//개소세
$data['stockType'] = clean_xss_tags(trim($_REQUEST['stockType']));//재고타입
$data['orderDateNm'] = clean_xss_tags(trim($_REQUEST['orderDateNm']));//출고타입
$data['orderCSN'] = clean_xss_tags(trim($_REQUEST['orderCSN']));//계약번호(차량번호)
$data['orderDate'] = clean_xss_tags(trim($_REQUEST['orderDate']));//출고일
$data['subsidy'] = (int)clean_xss_tags(trim($_REQUEST['subsidy']));//보조금
$data['discount'] = (int)clean_xss_tags(trim($_REQUEST['discount']));//할인
$data['consignmentFee'] = (int)clean_xss_tags(trim($_REQUEST['consignmentFee']));//탁송료
$data['orderAmount'] = (int)clean_xss_tags(trim($_REQUEST['orderAmount']));//출고대수


$sql = " insert into {$g5['nh_car_preordered_table']}
                set carinfo_id = '$carinfo_id',
                	 nation = '{$data['nh_nation']}',
                	 brnNo = '{$data['nh_brn']}',
                	 brnNm = '{$data['brnNM']}',
                     modlNo = '{$data['nh_modl']}',
                     modlNm = '{$data['modlNM']}',
                     trimNo = '{$data['nh_trim']}',
                     trimNm = '{$data['trimNM']}',
                     dtlTrimNo = '{$data['nh_dtltrim']}',
                     dtlTrimNm = '{$data['dtltrimNM']}',
                     pretaxPrice = '{$data['totalprice']}',
                     optNmArr = '{$data['optNmArr']}',
                     colorNm = '{$data['colorNm']}',
                     innColorNm = '{$data['innColorNm']}',
                     releaseType = '{$data['releaseType']}',
                     specialExcise = '{$data['specialExcise']}',
                     stockType = '{$data['stockType']}',
                     orderDateNm = '{$data['orderDateNm']}',
                     orderCSN = '{$data['orderCSN']}',
                     orderDate = '{$data['orderDate']}',
                     subsidy = '{$data['subsidy']}',
                     discount = '{$data['discount']}',
                     consignmentFee = '{$data['consignmentFee']}',
                     orderAmount = '{$data['orderAmount']}',
                     nh_datetime = '".G5_TIME_YMDHIS."' ";
sql_query($sql);
$wr_id = sql_insert_id();
unset($data);
echo $wr_id;
?>