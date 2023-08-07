<?php
include_once("./_common.php");

if (!function_exists('alert_just')) {
    // 경고메세지를 경고창으로
    function alert_just($msg = '', $url = '')
    {
        global $g5;

        if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

        //header("Content-Type: text/html; charset=$g5[charset]");
        echo "<meta charset=\"utf-8\">";
        echo "<script language='javascript'>alert('$msg');";
        echo "</script>";
        exit;
    }
}

if (!function_exists('utf2euc')) {
    function utf2euc($str)
    {
        return iconv("UTF-8", "cp949//IGNORE", $str);
    }
}
if (!function_exists('is_ie')) {
    function is_ie()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}

// 접근 권한 검사
if (!$member['mb_id']) {
    alert('로그인 하십시오.', G5_SHOPPING_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_SHOPPING_ADMIN_URL)));
}

if ($member['mb_level'] < 9) {
    alert("잘못된 접근입니다.", G5_URL);
}

$sql = " select count(*) as cnt from {$g5['shopping_application_table']} ";
$total = sql_fetch($sql);

if (!$total['cnt']) alert_just('데이터가 없습니다.');

$qry = sql_query("select *,IFNULL(NULLIF(wr_9, ''),'상담신청') as wr_11 from {$g5['shopping_application_table']} order by wr_id");

/*================================================================================
php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
=================================================================================*/

include_once(G5_LIB_PATH . '/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
include_once(G5_LIB_PATH . '/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

$fname = tempnam(G5_DATA_PATH, "tmp.xls");
$workbook = new writeexcel_workbook($fname);
$worksheet = $workbook->addworksheet();

$num2_format = &$workbook->addformat(array(num_format => '\0#'));

// Put Excel data
$data = array('관리번호', '견적구분', '이름', '연락처', '차종', '견적서', '견적정보', '메모', '처리상태', '인입경로', '파라미터', '등록일');
$data = array_map('iconv_euckr', $data);

$col = 0;
foreach ($data as $cell) {
    $worksheet->write(0, $col++, $cell);
}

for ($i = 1; $res = sql_fetch_array($qry); $i++) {
    $res = array_map('iconv_euckr', $res);

    $content = get_text($res['wr_content'], 0);

    ///estimate/2022092310113066
    $estSheet = "";
    $estRes = "";
    $cartype = $res['wr_4'];

    if ($res['wr_10']) {
        //$estRes = sql_fetch("select * from {$g5['nh_estimate_table']} where wr_10='{$res['wr_10']}'");
        $estRes = array_map('iconv_euckr', getEstDetail($res['wr_10']));

        if (is_array($estRes)) {
            $estSheet = G5_URL . "/estimate/" . $res['wr_10'];
            $cartype = $estRes[1];
        }
    }


    $worksheet->write($i, 0, trim($res['wr_id']));
    $worksheet->write($i, 1, trim($res['ca_name']));
    $worksheet->write($i, 2, trim($res['wr_1']));
    $worksheet->write($i, 3, hyphen_hp_number(get_text($res['wr_2'])), $num2_format);
    $worksheet->write($i, 4, $cartype);
    $worksheet->write($i, 5, $estSheet);
    $worksheet->write($i, 6, get_text($estRes[2]));
    $worksheet->write($i, 7, get_text($res['wr_content'], 0));
    $worksheet->write($i, 8, trim($res['wr_11']));
    $worksheet->write($i, 9, trim($res['wr_3']));
    $worksheet->write($i, 10, trim($res['wr_8']));
    $worksheet->write($i, 11, trim($res['wr_datetime']));
}

$workbook->close();

$filename = "NH캐피탈_상담DB-" . date("ymd", time()) . ".xls";
if (is_ie()) $filename = utf2euc($filename);
//vnd.ms-excel
//header("Content-Type: application/vnd.ms-excel; charset=utf-8; name=".$filename);
//header("Content-Type: application/x-msexcel; name=".$filename);

header("Content-Type: application/x-msexcel; charset=utf-8; name=" . $filename);
header("Content-Disposition: inline; filename=" . $filename);
$fh = fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

exit;
