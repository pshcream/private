<?php
include_once('./_common.php');
include_once(G5_CHADEAL_ADMIN_PATH.'/functions.php');

// 접근 권한 검사
if ($is_admin != 'super')
	alert("잘못된 접근입니다.",G5_URL);

$nh_id = addslashes(clean_xss_tags($_POST['del_CD_BOARDID']));

$sql = "select * from {$g5['nh_car_preordered_table']} where nh_id = '$nh_id' ";
$row = sql_fetch($sql);
if (!$row['nh_id']) {
    alert("선구매 내역이 존재하지 않습니다.\\n삭제되었을 수 있습니다.");
}

sql_query(" delete from {$g5['nh_car_preordered_table']} where nh_id = '$nh_id' ");

echo "<script type='text/javascript'>	";
echo "	alert('삭제 되었습니다.');	";
echo "	parent.document.location.reload();	";
echo "</script>	";
