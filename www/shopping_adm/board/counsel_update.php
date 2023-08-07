<?php
include_once('./_common.php');
include_once(G5_SHOPPING_ADMIN_PATH . '/functions.php');

// 접근 권한 검사
if ($member['mb_level'] < 9) {
	alert("잘못된 접근입니다.", G5_URL);
}

//게시판
$write_table = $g5['shopping_application_table'];

//$count = count($_POST['list_c_no']);
//alert($count);//7





$COUNSEL_GUBUN = addslashes(clean_xss_tags($_POST['COUNSEL_GUBUN']));
$CD_BOARDID = addslashes(clean_xss_tags($_POST['CD_BOARDID']));
//$NM_MEMO = addslashes(clean_xss_tags($_POST['NM_MEMO_$CD_BOARDID']));
//$NM_STATE = addslashes(clean_xss_tags($_POST['NM_STATE_.$CD_BOARDID']));
//alert($COUNSEL_GUBUN);
//alert($_POST['CD_BOARDID']);//7

//alert($_POST['NM_MEMO'][0]);
/*
for ($i=0; $i<$count; $i++)
{
	alert("test".$CD_BOARDID);
    $k     = $_POST['list_c_no'][$i];
	alert($k);
	
}
*/


if ($CD_BOARDID >= 0) {
	$wr_id = $_POST['list_c_no'][$CD_BOARDID];
	$wr = get_write($write_table, $wr_id);

	if (!$wr['wr_id']) {
		alert("상담신청 내역이 존재하지 않습니다.\\n삭제되었거나 이동하였을 수 있습니다.");
	}

	switch ($COUNSEL_GUBUN) {
		case "memo":
			chgMemo($wr_id, $CD_BOARDID);
			break;
		case "state":
			chgState($wr_id, $CD_BOARDID);
			break;
		default:
			parent_alert("옳바른 방법으로 이용해 주세요."); //error_alert("옳바른 방법으로 이용해 주세요.");
			break;
	}
}

function chgState($wr_id, $CD_BOARDID)
{
	global $write_table, $_POST;

	$wr_9 = "";
	if (isset($_POST['NM_STATE'][$CD_BOARDID])) {
		$wr_9 = addslashes(clean_xss_tags($_POST['NM_STATE'][$CD_BOARDID]));
	}

	$sql = " update {$write_table}
                set wr_9 = '{$wr_9}'
              where wr_id = '{$wr_id}' ";
	sql_query($sql);

	parent_alert_reload("상태값이 변경 되었습니다.");
}


function chgMemo($wr_id, $CD_BOARDID)
{
	global $write_table, $_POST;

	$wr_content = '';
	if (isset($_POST['NM_MEMO'][$CD_BOARDID])) {
		$wr_content = substr(trim($_POST['NM_MEMO'][$CD_BOARDID]), 0, 65536);
		$wr_content = preg_replace("#[\\\]+$#", "", $wr_content);
	}

	$sql = " update {$write_table}
                set wr_content = '{$wr_content}'
              where wr_id = '{$wr_id}' ";
	sql_query($sql);

	parent_alert_reload("메모가 등록되었습니다.");
}
