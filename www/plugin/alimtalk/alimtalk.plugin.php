<?php
/**
**  Project Name - ncloud-kakao-alim-api
**  Maker - 한빛가람( http://hanb.jp )
**  Date - 2018-11-17 (YYYY-mm-dd)
**  Project URL - https://github.com/HanbitGaram/ncloud-kakao-alim-api
**/
if(!defined('_GNUBOARD_')) exit; // 그누보드 개별 파일 실행 방지

// 변수 초기화
$rchada = array();

// 알림톡 설정 파일 로드
include_once(G5_PLUGIN_PATH.'/alimtalk/user_config.php');

// 반드시 탬플릿 컨텐츠와 버튼 내용은 SENS 등록 내용과 동일하게 작성해주세요.
function rchadaTalk($templateCode = '', $phoneNum = '', $content = '', $bo_table ='', $wr_id = '', $buttons = array()){
	global $rchada;

	// 휴대폰 번호에서 숫자를 제외한 모든 문자를 제거함.
	$phoneNum = preg_replace( '/[^0-9]/', '', $phoneNum );

	// 필수 값 검사. 값이 없으면 false를 리턴함.
	// 필수 값 = 플러스친구아이디, 탬플릿코드, 휴대폰번호, 컨텐츠명
	// 선택 값 = 버튼( 타입과 이름은 필수 )
	// ##1 ( 필수 값 검사 루틴 시작)
	if(
		// 기본 값 검사
		(
			!trim( $rchada['plusFriendId'] ) // 플러스친구 아이디가 없을 경우
			|| !trim( $templateCode ) // 탬플릿 코드가 없을 경우
			|| !trim( $phoneNum ) // 휴대폰 번호가 없을 경우
			// 휴대폰 번호가 10글자나 11글자가 아닌 경우 (01x-xxx-xxxx or 01x-xxxx-xxxx)
			|| ( 
				strlen( $phoneNum ) !== 10 // ex) 01x-xxx-xxxx
				&& strlen( $phoneNum ) !== 11 // ex) 01x-xxxx-xxxx
			)
			|| !trim( $content ) // 컨텐츠가 없을 경우
		)
		// 버튼 값이 있을 경우 검사.
		|| (
			( count( $buttons ) !== 0 && !trim( $buttons['type'] ) ) // 버튼 타입 지정이 되지 않았을 경우
			|| ( count( $buttons ) !== 0 && !trim( $buttons['name'] ) ) // 버튼 이름 지정이 되지 않았을 경우
			|| !is_array( $buttons ) // 버튼이 배열이 아닐 경우 (버튼은 반드시 배열로 넘겨야함)
		)
		// 버튼 타입이 웹 링크(WL) 일 경우, 검사
		|| (
			( $buttons['type'] == 'WL' && !trim( $buttons['linkMobile'] ) ) // 모바일 링크가 지정되지 않은 경우
			|| ( $buttons['type'] == 'WL' && !trim( $buttons['linkPc'] ) ) // PC 링크가 지정되지 않은 경우
		)
		// 버튼 타입이 앱 링크(AL) 일 경우, 검사
		|| (
			( $buttons['type'] == 'AL' && !trim( $buttons['schemeIos'] ) ) // iOS 스키마가 지정되지 않은 경우
			|| ( $buttons['type'] == 'AL' && !trim( $buttons['schemeAndroid'] ) ) // Android 스키마가 지정되지 않은 경우
		)
	){ 
		rchadaTalkDebug('필수 값이 입력되지 않았거나 형식에 맞지 않음.'); 
		return false;
	}
	// ##1 ( 필수 값 검사 루틴 종료)

	// 입력한 값을 바탕으로하여 데이터 작성.
	$postData = array(
		'plusFriendId' => $rchada['plusFriendId'],
		'templateCode' => $templateCode,
		'messages' => array(
			0 => array(
				'countryCode' => $rchada['countryCode'],
				'to' => $phoneNum,
				'content' => $content,
				'buttons' => array($buttons),
				'useSmsFailover' => false
			)
		)
	);

	// 버튼 json 인코딩.
	$postData = json_encode( $postData );
	
	// 타임 스탬프 지정 (Timeout : 5 minutes)
	$timestamp = round( microtime( true ) * 1000 );
	
	// 요청 URL 지정.
	$requestUrl = '/alimtalk/'.$rchada['version'].'/services/'.urlencode($rchada['serviceId']).'/messages';
	
	// HmacSHA256 으로 시그니쳐 생성.
	$message = "POST";
	$message .= " ";
	$message .= $requestUrl;
	$message .= "\n";
	$message .= $timestamp;
	$message .= "\n";
	$message .= $rchada['accessKey'];
	$signature = base64_encode(hash_hmac('sha256', $message, $rchada['secretKey'], true));
	
	// 인증용 헤더 생성.
	// 200 => 시그니쳐 오류, 400 => 데이터 입력 오류(데이터 확인 필수)
	$authHeader = array(
		'accept: application/json; charset=UTF-8',
		'Content-Type: application/json; charset=utf-8',
		'x-ncp-apigw-timestamp: '.$timestamp,
		'x-ncp-iam-access-key: '.$rchada['accessKey'],
		'x-ncp-apigw-signature-v2: '.$signature
	);
	
	// ncloud 서버에 알림톡 전송
	/*$curl = curl_init();
	$curlUrl = $rchada['endpoint'].'/'.$rchada['version'].'/services/'.urlencode($rchada['serviceId']).'/messages';
	curl_setopt( $curl, CURLOPT_URL, $curlUrl ); // 접속할 URL을 입력함.
	curl_setopt( $curl, CURLOPT_HEADER, 0 ); // Response 헤더 값 나오지 않게 설정함.
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 데이터 반환을 curl_exec를 통해서만 되게 처리함.
	curl_setopt( $curl, CURLOPT_HTTPHEADER, $authHeader ); // Request 헤더
	curl_setopt( $curl, CURLOPT_POST, 1 );
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'POST' );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $postData ); //http_build_query
	$exec = curl_exec( $curl );
	curl_close( $curl );
	*/
	
	$ch = curl_init();
	$curlUrl = $rchada['endpoint'].'/'.$rchada['version'].'/services/'.urlencode($rchada['serviceId']).'/messages';
	curl_setopt($ch, CURLOPT_URL, $curlUrl);
	curl_setopt($ch, CURLOPT_HEADER, false);//true
	curl_setopt($ch, CURLOPT_HTTPHEADER, $authHeader);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$exec = curl_exec($ch);
	curl_close( $ch );
	
	rchadaTalkDebug( $exec );
	
	// 서버에서 전달받은 JSON 값을 PHP 배열로 바꿈.
	$return = json_decode($exec, true);
	
	//DB입력
	
	
	// 만약, 서버에서 전달받은 상태가 성공이라면
	if( $return['messages'][0]['requestStatusCode'] == 'A000' ){ 
		//rchadaTalkDebug( '알림톡 전송 성공. 알림톡이 오지 않는 경우 탬플릿을 반드시 점검하기 바랍니다.' );
		alimtalkDB($templateCode,$content,$bo_table,$wr_id,"성공");
		//return true;
	}else{
		//rchadaTalkDebug( '알림톡 전송 실패.' );
		alimtalkDB($templateCode,$content,$bo_table,$wr_id,"실패");
	}
	
}

function alimtalkDB($templateCode,$content,$bo_table,$wr_id,$result){
	$wr_subject = $bo_table."/".$wr_id."/".$templateCode."/".G5_TIME_YMDHIS;
	
	$mb_id = 'admin';
	$sql = " select mb_password,mb_name FROM {$g5['member_table']} WHERE mb_id = '$mb_id' ";
	$row = sql_fetch($sql);
	
	$wr_password = $row['mb_password'];
	$wr_name = $row['mb_name'];
	
	$bo_table = "kakao_alimtalk";
	$write_table = "g5_write_".$bo_table;
	
	$wr_num = get_next_num($write_table);
	$wr_reply = '';
	$ca_name = $result;
		
	$sql = " insert into $write_table
	                set wr_num = '$wr_num',
	                     wr_reply = '$wr_reply',
	                     wr_comment = 0,
	                     ca_name = '$ca_name',
	                     wr_option = 'html1,secret',
	                     wr_subject = '$wr_subject',
	                     wr_content = '$content',
	                     wr_link1_hit = 0,
	                     wr_link2_hit = 0,
	                     wr_hit = 0,
	                     wr_good = 0,
	                     wr_nogood = 0,
	                     mb_id = '$mb_id',
	                     wr_password = '$wr_password',
	                     wr_name = '$wr_name',
	                     wr_datetime = '".G5_TIME_YMDHIS."',
	                     wr_last = '".G5_TIME_YMDHIS."',
	                     wr_ip = '{$_SERVER['REMOTE_ADDR']}' ";
	sql_query($sql);
	
	$wr_id = sql_insert_id();
		
	// 새글 INSERT
	sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$mb_id}' ) ");
	
	// 게시글 1 증가
	sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");
}
	

// 화면에 디버그 메시지 표시 (최고관리자가 아니라면 표시하지 않음)
function rchadaTalkDebug($msg){
	global $rchada, $is_admin;
	if( $rchada['debug'] !== true || $is_admin !== 'super' ){ return false; }

	echo '<div style="font-size:12px; font-family:\'Apple SD Gothic Neo\',\'NanumGothic\',\'Nanum Gothic\'\'Dotum\',\'Gulim\'; display:block; font-weight:normal;">[DEBUG] - ';
	echo $msg;
	echo '</div>';
}
?>