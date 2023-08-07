<?php
include_once('./_common.php');

// 접근 권한 검사
if (!$member['mb_id']) {
    alert('로그인 하십시오.', G5_SHOPPING_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_SHOPPING_ADMIN_URL)));
}

if ($member['mb_level'] < 9) {
    alert("잘못된 접근입니다.", G5_URL);
}

include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

include_once(G5_SHOPPING_ADMIN_PATH . '/functions.php');

//날짜
//if(!isset($sSTARTDATE))
//	$sSTARTDATE = date("Y-m-d",strtotime("-1 month",time()));
if (!isset($sENDDATE))
    $sENDDATE = date("Y-m-d", time());

$page_size = 20;
$colspan = 14;
if ($page < 1) $page = 1;

$sql_group = "";
$sql_search = "";
//$sql_search .= " and wr_datetime>='$sSTARTDATE' and wr_datetime<='$sENDDATE 23:59:59' ";

//등록일자-시작
if (isset($sSTARTDATE) && strlen($sSTARTDATE)) {
    $sql_search .= " and wr_datetime>='$sSTARTDATE' ";
}

//등록일자-종료
if (isset($sENDDATE) && strlen($sENDDATE)) {
    $sql_search .= " and wr_datetime<='$sENDDATE 23:59:59' ";
}

//처리상태
if (isset($sSTATUS) && strlen($sSTATUS)) {
    $sql_search .= " and wr_9='" . str_replace('상담신청', '', $sSTATUS) . "' ";
}

//검색구분sGUBUN
if (isset($sKEYWORD) && strlen($sKEYWORD)) {
    switch ($sGUBUN) {
        case "usernm":
            $sql_search .= " and wr_1 like '%" . trim($sKEYWORD) . "%' ";
            break;
        case "phone":
            $sql_search .= " and wr_2 like '%" . trim($sKEYWORD) . "%' ";
            break;
        case "memo":
            $sql_search .= " and wr_content like '%" . trim($sKEYWORD) . "%' ";
            break;
    }
}

//고객명
$cus_name = strip_tags($cus_name);
$cus_name = get_search_string($cus_name); // 특수문자 제거
if (isset($cus_name) && $cus_name) {
    $sql_search .= " and (wr_name like '%$cus_name%')";
}

//진행상태
$progress = strip_tags($progress);
$progress = get_search_string($progress); // 특수문자 제거
if (isset($progress) && $progress) {
    $sql_search .= " and (lo_progress = '$progress')";
}

$total_res = sql_fetch("select count(*) as cnt from {$g5['shopping_application_table']} where 1 $sql_group $sql_search");
$total_count = $total_res['cnt'];

$total_page = (int)($total_count / $page_size) + ($total_count % $page_size == 0 ? 0 : 1);
$page_start = $page_size * ($page - 1);

//출고
$res = sql_fetch("select count(*) as cnt from {$g5['shopping_application_table']} where lo_cus_name!='' $sql_group $sql_search");
$cnt1 = $res['cnt'];

$res = sql_fetch("select count(*) as cnt from {$g5['shopping_application_table']} where lo_cus_name='' $sql_group $sql_search");
$nonpre_count = $res['cnt'];

$res = sql_fetch("select count(*) as cnt from {$g5['shopping_application_table']} where lo_expose='N' $sql_group $sql_search");
$hid_count = $res['cnt'];


//$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));
$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . "?bg_no=$bg_no&amp;lo_maker=$lo_maker&amp;lo_carname=$lo_carname&amp;stx=$stx&amp;page=");
$write_pages = chg_paging($write_pages);

//메뉴타이틀
//$tit = get_active_menu_name($menu_data);

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>알차다쇼핑 관리자 페이지</title>

    <link href="/shopping_adm/css/bootstrap/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/shopping_adm/css/bootstrap/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="/shopping_adm/css/bootstrap/inspinia/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/shopping_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="/shopping_adm/css/bootstrap/inspinia/css/animate.css" rel="stylesheet">
    <link href="/shopping_adm/css/bootstrap/inspinia/css/style.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="/shopping_adm/css/bootstrap/inspinia/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/bootstrap.min.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/inspinia.js"></script>
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/toastr/toastr.min.js"></script>

    <!-- Data picker -->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- Input Mask-->
    <script src="/shopping_adm/css/bootstrap/inspinia/js/plugins/jasny/jasny-bootstrap.min.js"></script>

    <script src="/shopping_adm/js/common.js"></script>
    <script src="/shopping_adm/js/jquery.cookie.js"></script>

    <style type="text/css">
        .custom-contents p {
            margin: 0 !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <!-- NAV s -->
        <?php include_once(G5_SHOPPING_ADMIN_PATH . '/navi.inc.php'); ?>
        <!-- NAV e -->

        <div id="page-wrapper" class="gray-bg dashbard-1">

            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">안녕하세요. <?php echo $member['mb_nick'] ?>(<?php echo $member['mb_id']; ?>) 님.</span>
                        </li>
                        <li>
                            <a href="<?php echo G5_SHOPPING_ADMIN_URL ?>/logout.php?url=<?php echo urlencode(correct_goto_url("/" . G5_SHOPPING_ADMIN_DIR)); ?>">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>



            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>상담신청 관리</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/shopping_adm/">Home</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">상담</a>
                        </li>
                        <li class="active">
                            <strong>상담신청</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <br />

            <div class="wrapper wrapper-content animated fadeInRight ">
                <div class="row">
                    <div class="col-lg-12 ">
                        <form name="search_form" id="search_form" method="post" onsubmit="return fn_Search();">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-search"></i> 검색 <small>게시글을 검색합니다.</small></h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="ibox-content m-b-sm border-bottom">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label" for="date_added">등록일자 - 시작</label>
                                                <div class="input-group date">
                                                    <input id="sSTARTDATE" name="sSTARTDATE" type="text" class="form-control text-center" value="<?php echo $sSTARTDATE; ?>" data-mask="9999-99-99" />
                                                    <span class="input-group-addon" id="btnStartDate"><i class="fa fa-calendar"></i></span>
                                                    <span class="input-group-addon" id="btnStartDateRemove"><i class="fa fa-refresh"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label" for="date_modified">등록일자 - 종료</label>
                                                <div class="input-group date">
                                                    <input id="sENDDATE" name="sENDDATE" type="text" class="form-control text-center" value="<?php echo $sENDDATE; ?>" data-mask="9999-99-99" />
                                                    <span class="input-group-addon" id="btnEndDate"><i class="fa fa-calendar"></i></span>
                                                    <span class="input-group-addon" id="btnEndDateRemove"><i class="fa fa-refresh"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label" for="amount">처리상태</label>
                                                <select name="sSTATUS" id="sSTATUS" class="form-control">
                                                    <option value="" <?php echo ($sSTATUS == "") ? "selected" : ""; ?>>전체</option>
                                                    <option value="상담신청" <?php echo ($sSTATUS == "상담신청") ? "selected" : ""; ?>>상담신청</option>
                                                    <option value="상담취소" <?php echo ($sSTATUS == "상담취소") ? "selected" : ""; ?>>상담취소</option>
                                                    <option value="상담중" <?php echo ($sSTATUS == "상담중") ? "selected" : ""; ?>>상담중</option>
                                                    <option value="상담완료" <?php echo ($sSTATUS == "상담완료") ? "selected" : ""; ?>>상담완료</option>
                                                    <option value="계약완료" <?php echo ($sSTATUS == "계약완료") ? "selected" : ""; ?>>계약완료</option>
                                                    <option value="출고완료" <?php echo ($sSTATUS == "출고완료") ? "selected" : ""; ?>>출고완료</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label" for="sGUBUN">구분</label>
                                                <select name="sGUBUN" id="sGUBUN" class="form-control">
                                                    <option value="usernm" <?php echo ($sGUBUN == "usernm") ? "selected" : ""; ?>>이름</option>
                                                    <option value="phone" <?php echo ($sGUBUN == "phone") ? "selected" : ""; ?>>연락처</option>
                                                    <option value="memo" <?php echo ($sGUBUN == "memo") ? "selected" : ""; ?>>메모</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label" for="sKEYWORD">검색어</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="sKEYWORD" name="sKEYWORD" value="<?php echo $sKEYWORD; ?>" placeholder="검색어를 입력해주세요." onkeypress="fn_chkent();" />
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn"> <i class="fa fa-search"></i><!--조회--></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label class="control-label" for="">&nbsp;</label>
                                                <div class="input-group">
                                                    <button type="button" class="btn btn-info" onclick="fn_regist();"> <i class="fa fa-plus"></i> 등록</button>
                                                    <button class="btn btn-danger" type="button" onclick="download()">다운로드</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="frm_BoardDetail" name="frm_BoardDetail">

                            <input type="hidden" id="COUNSEL_GUBUN" name="COUNSEL_GUBUN" value="" /> <!--처리결과(state)/메모(memo) 구분-->
                            <input type="hidden" id="CD_BOARDID" name="CD_BOARDID" value="" /> <!--처리결과/메모 수정 게시글 아이디-->

                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-list"></i> 리스트 <small> <strong class="text-info"><?php echo number_format($total_count); ?></strong> 건의 데이터가 존재합니다.</small></h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link">
                                            <i class="fa fa-chevron-up"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-user">
                                            <!-- <li><a href="javascript:;" id="btnToggleBoardCounselGPSInfo" data-openstatus="open"><i class="fa fa-map-marker"></i> 신청 GPS 좌표 닫기</a></li> -->

                                            <!--
											<li><a href="#">Config option 2</a></li>
											-->
                                        </ul>
                                        <!--
										<a class="close-link">
											<i class="fa fa-times"></i>
										</a>
										-->
                                    </div>
                                </div>
                                <div class="ibox-content no-padding">

                                    <div class="table-responsive">

                                        <table class="table table-hover table-striped">
                                            <colgroup>
                                                <col width="50px" />
                                                <col width="100px" />
                                                <col width="110px" />
                                                <col width="110px" />
                                                <col width="110px" />
                                                <col width="110px" />
                                                <col width="150px" />
                                                <col width="150px" />
                                                <col width="*" />
                                                <!-- <col width="*" /> -->
                                                <col width="100px" />
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">등록일</th>
                                                    <th class="text-center">인입경로</th>
                                                    <th class="text-center">이름</th>
                                                    <th class="text-center">연락처</th>
                                                    <th class="text-center">차종</th>
                                                    <th class="text-center">문의내용</th>
                                                    <!-- <th class="text-center">연락처</th> -->
                                                    <th class="text-center">견적정보</th>
                                                    <th class="text-center">메모</th>
                                                    <th class="text-center">처리상황/저장</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!$total_count) { ?>
                                                    <tr>
                                                        <td colspan="<?php echo $colspan ?>" class="text-center">데이터가 없습니다.</td>
                                                    </tr>
                                                <?php
                                                }
                                                $sql = "select * from {$g5['shopping_application_table']} where 1 $sql_group $sql_search order by wr_id desc limit $page_start, $page_size";
                                                $qry = sql_query($sql);

                                                $j = $total_count - (($page - 1) * $page_size);
                                                for ($i = 0; $res = sql_fetch_array($qry); $i++) {
                                                    $wtime = explode(" ", $res['wr_datetime']);

                                                    $content = get_text($res['wr_content'], 0);

                                                    $estSheet = "";
                                                    $estRes = "";
                                                    $cartype = $res['wr_4'];

                                                    if ($res['wr_6']) {
                                                        $estRes = getEstDetail($res['wr_id']);

                                                        // if (is_array($estRes)) {
                                                        //     $estSheet = "<a href='/estimate/{$res['wr_10']}' target='_blank'>견적서확인</a>";
                                                        //     $cartype = $estRes[1];
                                                        // }
                                                    }
                                                ?>
                                                    <tr>
                                                        <td class="text-center" rowspan="1" style="border-right: 1px solid #e7eaec;"><input type="hidden" name="list_c_no[]" value="<?php echo $res['wr_id'] ?>"><?php echo $j ?></td>
                                                        <td class="text-center">
                                                            <?php echo $wtime[0]; ?><br />
                                                            <?php echo $wtime[1]; ?>
                                                        </td>
                                                        <td class="text-center"><?php echo $res['wr_1']; ?><br>
                                                            <span style="color:#fff;"><?php echo $res['wr_link1']; ?></span></td>
                                                        <td class="text-center"><?php echo $res['wr_2']; ?></td>
                                                        <td class="text-center"><?php echo $res['wr_3']; ?></td>
                                                        <td class="text-center"><?php echo $res['wr_4']; ?></td>
                                                        <td class="text-center"><?php echo $res['wr_5']; ?></td>
                                                        <td class="no-padding">
                                                            <!-- <?php echo (get_text($res['wr_7']) == "") ? "<br/>" : get_text($res['wr_7']); ?> -->
                                                            <textarea class="form-control custom-contents " style="min-width:200px; width: 100%; height: 120px; resize: none; background: #fff;" readonly="readonly"><?php echo get_text($estRes); ?></textarea>
                                                        </td>
                                                        <td class="text-center ">
                                                            <!-- <br /> -->
                                                            <!--<textarea name="NM_MEMO_<?php echo $res['wr_id'] ?>" class="form-control no-padding" style="min-width:200px; width: 100%; height: 120px; resize: none;"><?php echo $content; ?></textarea>-->
                                                            <textarea name="NM_MEMO[<?php echo $i ?>]" class="form-control no-padding" style="min-width:200px; width: 100%; height: 120px; resize: none;"><?php echo $content; ?></textarea>
                                                        </td>
                                                        <td class="text-center ">
                                                            <!--<select class="form-control no-padding" name="NM_STATE_<?php echo $res['wr_id'] ?>" onChange="fn_counsel('state','<?php echo $res['wr_id'] ?>');" style="margin-bottom: 5px;">-->
                                                            <select class="form-control no-padding" name="NM_STATE[<?php echo $i ?>]" onChange="fn_counsel('state','<?php echo $i ?>');" style="margin-bottom: 5px;">
                                                                <option value="" <?php echo ($res['wr_9'] == "") ? "selected" : ""; ?>>상담신청</option>
                                                                <option value="상담취소" <?php echo ($res['wr_9'] == "상담취소") ? "selected" : ""; ?>>상담취소</option>
                                                                <option value="상담중" <?php echo ($res['wr_9'] == "상담중") ? "selected" : ""; ?>>상담중</option>
                                                                <option value="상담완료" <?php echo ($res['wr_9'] == "상담완료") ? "selected" : ""; ?>>상담완료</option>
                                                                <option value="계약완료" <?php echo ($res['wr_9'] == "계약완료") ? "selected" : ""; ?>>계약완료</option>
                                                                <option value="출고완료" <?php echo ($res['wr_9'] == "출고완료") ? "selected" : ""; ?>>출고완료</option>
                                                            </select>
                                                            <button type="button" class="btn btn-sm btn-info" style="width:100%;" onclick="fn_counsel('memo','<?php echo $i ?>');"><i class="fa fa-check"></i> 메모 저장</button>
                                                            <button type="button" class="btn btn-sm btn-danger" style="width:100%;" onclick="fn_del('<?php echo $res['wr_id'] ?>')"><i class="fa fa-trash-o"></i> 삭제</button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $j--;
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="<?php echo $colspan ?>" class="text-center">
                                                        <?php echo $write_pages;  ?>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="footer">
                <div>
                    <strong>Copyright</strong> RCAHDA
                </div>
            </div>
        </div>
    </div>




    <script type="text/javascript">
        function download() {
            (function($) {
                if (!document.getElementById("fileupload_fr")) {
                    var i = document.createElement('iframe');
                    i.setAttribute('id', 'fileupload_fr');
                    i.setAttribute('name', 'fileupload_fr');
                    i.style.display = 'none';
                    document.body.appendChild(i);
                }
                fileupload_fr.location.href = './counsel_excel.php';
            })(jQuery);
        }

        // 달력 컨트롤 가져오기
        $(document).ready(function() {
            $('#sSTARTDATE').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: 'yyyy-mm-dd',
                keyboardNavigation: true,
                language: 'ko'
            });

            $('#sENDDATE').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format: 'yyyy-mm-dd',
                keyboardNavigation: true,
                language: 'ko'
            });

            $('#btnStartDate').click(function() {
                $('#sSTARTDATE').focus();
            });

            $('#btnStartDateRemove').click(function() {
                $('#sSTARTDATE').val("");
            });

            $('#btnEndDate').click(function() {
                $('#sENDDATE').focus();
            });

            $('#btnEndDateRemove').click(function() {
                $('#sENDDATE').val("");
            });

        });

        /**
         * name : fn_Search
         * parameter : 없음
         * description : 조회
         */
        function fn_Search() {
            var sgubun = document.getElementById("sGUBUN").value;
            var skeyword = document.getElementById("sKEYWORD").value;
            var sSTARTDATE = document.getElementById("sSTARTDATE");
            var sENDDATE = document.getElementById("sENDDATE");
            var sSTATUS = document.getElementById("sSTATUS");

            document.location.href = "counsel.php?sGUBUN=" + sgubun + "&sKEYWORD=" + skeyword + "&sSTARTDATE=" + sSTARTDATE.value + "&sENDDATE=" + sENDDATE.value + "&sSTATUS=" + sSTATUS.value;
        }

        /**
         * name : fn_changeDisplay
         * parameter : CD_BOARDID, YN_DISPLAY
         * description : 전시여부 변경
         */
        function fn_changeDisplay(CD_BOARDID, YN_DISPLAY) {
            var hd_frame = document.getElementById("hd_frame");
            hd_frame.src = "BoardDetail_display_proc.asp?CD_BOARDID=" + CD_BOARDID + "&YN_DISPLAY=" + YN_DISPLAY;
        }

        /**
         * name : fn_addZero
         * parameter : int형 숫자
         * description : 숫자앞 '0' 추가	
         */
        function fn_addZero(val) {
            if (val < 10) {
                return "0" + val;
            } else {
                return "" + val;
            }
        }

        /**
         * name : fn_chkent
         * parameter : 없음
         * description : 엔터키 입력시 액션
         */
        function fn_chkent() {
            if (event.keyCode == 13) {
                fn_Search();
            }
        }

        /**
         *	처리결과, 메모 등록/수정
         */
        function fn_counsel(gubun, BOARDID) {
            var COUNSEL_GUBUN = document.getElementById("COUNSEL_GUBUN");
            var CD_BOARDID = document.getElementById("CD_BOARDID");

            COUNSEL_GUBUN.value = gubun;
            CD_BOARDID.value = BOARDID;

            document.frm_BoardDetail.target = "hd_frame";
            document.frm_BoardDetail.method = "post";
            document.frm_BoardDetail.action = "counsel_update.php";
            document.frm_BoardDetail.submit();
        }

        $(document).ready(function() {


            //aryChartDeviceType, aryChartMediaType, aryChartCounselRatioType

        });

        function fn_del(CD_BOARDID) {
            if (confirm("삭제하시겠습니까?")) {

                var del_CD_BOARDID = document.getElementById("del_CD_BOARDID");
                del_CD_BOARDID.value = CD_BOARDID;

                document.frmDel.target = "hd_frame";
                document.frmDel.method = "post";
                document.frmDel.action = "counsel_del_proc.php";
                document.frmDel.submit();
            }
        }

        function fn_regist() {
            document.location.href = "/shopping_adm/board/counsel_write.php";
        }
    </script>

    <form name="frmDel">
        <input type="hidden" id="del_CD_BOARDID" name="del_CD_BOARDID" value="" />
    </form>

    <iframe type="hiddenframe" id="hd_frame" name="hd_frame" style="display:none;"></iframe>
</body>

</html>