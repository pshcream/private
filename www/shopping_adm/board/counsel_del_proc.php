<?php
include_once('./_common.php');
include_once(G5_SHOPPING_ADMIN_PATH.'/functions.php');

// 접근 권한 검사
if($member['mb_level']<9){
    alert("잘못된 접근입니다.",G5_URL);
}

//게시판
$bo_table = "application";
$write_table = $g5['write_prefix'] . $bo_table;
$wr_id = addslashes(clean_xss_tags($_POST['del_CD_BOARDID']));
$wr = get_write($write_table, $wr_id);
	
if (!$wr['wr_id']) {
    alert("상담신청 내역이 존재하지 않습니다.\\n삭제되었거나 이동하였을 수 있습니다.");
}

//$wr_num = get_next_num($write_table);
//$wr_reply = '';
//$bo_table = "application_bak";
//$move_write_table = $g5['nh_application_bak_table'];

//삭제시 게시판 복사
$move_bo_table = "application_bak";
$move_write_table = $g5['write_prefix'] . $move_bo_table;

// 취약점 18-0075 참고
$sql = "select * from {$g5['board_table']} where bo_table = '$move_bo_table' ";
$move_board = sql_fetch($sql);
// 존재시 복사
if($move_board['bo_table']){
	
	$next_wr_num = get_next_num($move_write_table);
	
	$sql = " insert into $move_write_table
                set wr_num = '$next_wr_num',
                     wr_reply = '{$wr['wr_reply']}',
                     wr_is_comment = '{$wr['wr_is_comment']}',
                     wr_comment = '{$wr['wr_comment']}',
                     wr_comment_reply = '{$wr['wr_comment_reply']}',
                     ca_name = '".addslashes($wr['ca_name'])."',
                     wr_option = '{$wr['wr_option']}',
                     wr_subject = '".addslashes($wr['wr_subject'])."',
                     wr_content = '".addslashes($wr['wr_content'])."',
                     wr_link1 = '".addslashes($wr['wr_link1'])."',
                     wr_link2 = '".addslashes($wr['wr_link2'])."',
                     wr_link1_hit = '{$wr['wr_link1_hit']}',
                     wr_link2_hit = '{$wr['wr_link2_hit']}',
                     wr_hit = '{$wr['wr_hit']}',
                     wr_good = '{$wr['wr_good']}',
                     wr_nogood = '{$wr['wr_nogood']}',
                     mb_id = '{$wr['mb_id']}',
                     wr_password = '{$wr['wr_password']}',
                     wr_name = '".addslashes($wr['wr_name'])."',
                     wr_email = '".addslashes($wr['wr_email'])."',
                     wr_homepage = '".addslashes($wr['wr_homepage'])."',
                     wr_datetime = '{$wr['wr_datetime']}',
                     wr_file = '{$wr['wr_file']}',
                     wr_last = '{$wr['wr_last']}',
                     wr_ip = '{$wr['wr_ip']}',
                     wr_1 = '".addslashes($wr['wr_1'])."',
                     wr_2 = '".addslashes($wr['wr_2'])."',
                     wr_3 = '".addslashes($wr['wr_3'])."',
                     wr_4 = '".addslashes($wr['wr_4'])."',
                     wr_5 = '".addslashes($wr['wr_5'])."',
                     wr_6 = '".addslashes($wr['wr_6'])."',
                     wr_7 = '".addslashes($wr['wr_7'])."',
                     wr_8 = '".addslashes($wr['wr_8'])."',
                     wr_9 = '".addslashes($wr['wr_9'])."',
                     wr_10 = '".addslashes($wr['wr_10'])."' ";
    sql_query($sql);
	
	$insert_id = sql_insert_id();
	
	// 부모 아이디에 UPDATE
	sql_query(" update $move_write_table set wr_parent = '$insert_id' where wr_id = '$insert_id' ");
	
	// 새글 INSERT
	sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$move_bo_table}', '{$insert_id}', '{$insert_id}', '".G5_TIME_YMDHIS."', 'admin' ) ");
	
	// 게시글 1 증가
	sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$move_bo_table}'");
	
	delete_cache_latest($move_bo_table);
}

//sql_query(" delete from {$bo_table} where wr_id = '{$wr['wr_id']}' ");
// 게시글과 댓글 삭제
sql_query(" delete from $write_table where wr_parent = '{$wr['wr_id']}' ");
// 최근게시물 삭제
sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$wr['wr_id']}' ");
// 스크랩 삭제
sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$wr['wr_id']}' ");
// 게시글 1 감소
sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - 1 where bo_table = '$bo_table' ");

delete_cache_latest($bo_table);

 // 글을 수정한 경우에는 제목이 달라질수도 있으니 static variable 를 새로고침합니다.
$write = get_write( $write_table, $wr['wr_id'], false);

if (!$write['wr_id']) {
	parent_alert_reload("삭제 되었습니다.");
}else{
	parent_alert("에러가 발생되어 정상적으로 처리되지 않았습니다.");
}
