<?php
include_once('./_common.php');

// 접근 권한 검사
if ($is_admin != 'super')
	alert("잘못된 접근입니다.",G5_URL);

if(isset($_REQUEST['cf_1'])){
	$cf = "cf_1 = '{$_REQUEST['cf_1']}'";
}else if(isset($_REQUEST['cf_2'])){
	$cf = "cf_2 = '{$_REQUEST['cf_2']}'";
}else if(isset($_REQUEST['cf_3'])){
	$cf = "cf_3 = '{$_REQUEST['cf_3']}'";
}else if(isset($_REQUEST['cf_4'])){
	$cf = "cf_4 = '{$_REQUEST['cf_4']}'";
}

/*
 * cf_1 = '{$_POST['cf_1']}',
                cf_2 = '{$_POST['cf_2']}',
                cf_3 = '{$_POST['cf_3']}',
                cf_4 = '{$_POST['cf_4']}',
 */

$sql = " update {$g5['config_table']} set {$cf} ";
sql_query($sql);

update_rewrite_rules();

echo "<script type='text/javascript'>	";
echo "	alert('수정 되었습니다.');	";
echo "	parent.document.location.reload();	";
echo "</script>	";
