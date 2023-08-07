<?php
include_once('./_common.php');

// 접근 권한 검사
if($member['mb_level']<9){
    alert("잘못된 접근입니다.",G5_URL);
}

$uid = isset($_POST['uid']) ? preg_replace('/[^0-9]/', '', $_POST['uid']) : 0;

$bo_table = "application";
$write_table = $g5['write_prefix'] . $bo_table;
$mb_id = "admin";
$wr_password = get_encrypt_string("rchada1234!@#$");
$wr_email = "";
$wr_homepage = "";

$secret = 'secret';
$html = '';
$mail = '';

$wr_subject = '상담사상담신청';

$wr_content = '';
if (isset($_POST['wr_content'])) {
    $wr_content = substr(trim($_POST['wr_content']),0,65536);
    $wr_content = preg_replace("#[\\\]+$#", "", $wr_content);
}

// 090710
if (substr_count($wr_content, '&#') > 50) {
    alert('내용에 올바르지 않은 코드가 다수 포함되어 있습니다.');
    exit;
}

$ca_name = addslashes(clean_xss_tags(trim($_REQUEST['ca_name'])));
$wr_1 = addslashes(clean_xss_tags(trim($_REQUEST['wr_1'])));//고객명
$wr_2 = addslashes(clean_xss_tags(trim($_REQUEST['wr_2'])));//연락처
$wr_3 = "상담사:".$member['mb_id'];//인입출처
$wr_4 = addslashes(clean_xss_tags(trim($_REQUEST['wr_4'])));//차종
$wr_5 = "";
$wr_6 = "";
$wr_7 = "";
$wr_8 = "";
$wr_9 = "";
$wr_10 = "";

if ($ca_name == '') {
    alert("견적구분을 선택해주세요.");
}

if ($wr_1 == '') {
    alert("고객명을 입력해주세요.");
}

if ($wr_2 == '') {
    alert("고객연락처를 입력해주세요.");
}

if ($wr_4 == '') {
    alert("상담차종을 입력해주세요.");
}
/*
for ($i=1; $i<=10; $i++) {
    $var = "wr_$i";
    $$var = "";
    if (isset($_POST['wr_'.$i]) && settype($_POST['wr_'.$i], 'string')) {
        $$var = trim($_POST['wr_'.$i]);
    }
}*/

run_event('write_update_before', $board, $wr_id, $w, $qstr);

//게시판 기본값
$wr_name = $wr_1;
$wr_num = get_next_num($write_table);
$wr_reply = '';

$wr_link1 = "";
$wr_link2 = "";

$wr_seo_title = exist_seo_title_recursive('bbs', generate_seo_title($wr_subject), $write_table, $wr_id);

$options = array($html,$secret,$mail);
$wr_option = implode(',', array_filter(array_map('trim', $options)));

if ($w == '') {
	
    $sql = " insert into $write_table
                set wr_num = '$wr_num',
                     wr_reply = '$wr_reply',
                     wr_comment = 0,
                     ca_name = '$ca_name',
                     wr_option = '$wr_option',
                     wr_subject = '$wr_subject',
                     wr_content = '$wr_content',
                     wr_seo_title = '$wr_seo_title',
                     wr_link1 = '$wr_link1',
                     wr_link2 = '$wr_link2',
                     wr_link1_hit = 0,
                     wr_link2_hit = 0,
                     wr_hit = 0,
                     wr_good = 0,
                     wr_nogood = 0,
                     mb_id = '{$mb_id}',
                     wr_password = '$wr_password',
                     wr_name = '$wr_name',
                     wr_email = '$wr_email',
                     wr_homepage = '$wr_homepage',
                     wr_datetime = '".G5_TIME_YMDHIS."',
                     wr_last = '".G5_TIME_YMDHIS."',
                     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
                     wr_1 = '$wr_1',
                     wr_2 = '$wr_2',
                     wr_3 = '$wr_3',
                     wr_4 = '$wr_4',
                     wr_5 = '$wr_5',
                     wr_6 = '$wr_6',
                     wr_7 = '$wr_7',
                     wr_8 = '$wr_8',
                     wr_9 = '$wr_9',
                     wr_10 = '$wr_10' ";
    sql_query($sql);

    $wr_id = sql_insert_id();

    // 부모 아이디에 UPDATE
    sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // 새글 INSERT
    sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$member['mb_id']}' ) ");

    // 게시글 1 증가
    sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");

    
}


// 자동저장된 레코드를 삭제한다.
sql_query(" delete from {$g5['autosave_table']} where as_uid = '{$uid}' ");
//------------------------------------------------------------------------------


delete_cache_latest($bo_table);

//$redirect_url = run_replace('write_update_move_url', short_url_clean(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id.$qstr), $board, $wr_id, $w, $qstr, $file_upload_msg);
//run_event('write_update_after', $board, $wr_id, $w, $qstr, $redirect_url);
$redirect_url = short_url_clean(G5_CHADEAL_ADMIN_URL.'/board/counsel.php');
run_event('write_update_after', $board, $wr_id, $w, $qstr, $redirect_url);

alert('입력되었습니다.', $redirect_url);
