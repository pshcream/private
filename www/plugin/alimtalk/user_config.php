<?php
	if(!defined('_GNUBOARD_')) exit; // 그누보드 개별 파일 실행 방지

	// API 서버 설정 (여긴 특별한 사유가 없는 이상 건드리지 마세요)
	$rchada['endpoint'] = 'https://sens.apigw.ntruss.com/alimtalk'; // SENS API Endpoint 주소
	$rchada['version'] = 'v2'; // SENS API Endpoint 버전 (기준 : 버전 2)

	// API 사용자 설정 (여기 값을 수정해서 사용하세요)
	$rchada['debug'] = false; // 디버그 모드 설정 = true, 디버그 모드 해제 - false
	$rchada['serviceId'] = 'ncp:kkobizmsg:kr:1080827:rchada'; // ncp: 로 시작하는 API키
	$rchada['plusFriendId'] = '@알차다워크'; // @를 포함한 플러스친구 아이디
	$rchada['countryCode'] = '82';
	
	//ncloud.com 포털 > 마이페이지 > 계정관리 > 인증키 관리 > API 인증키 관리
	//- 링크 : https://www.ncloud.com/mypage/manage/authkey	
	$rchada['apiKey'] = "";//v2 미사용
	$rchada['accessKey'] = "3egkyvYoZ7Arly6cBbdq";
	$rchada['secretKey'] = "xkkIGCm6rxB1QabvE16yDQnyUmG1esRWTfq1qqf4";

?>