<?php
include_once('./_common.php');

// 접근 권한 검사
if (!$member['mb_id']) {
    alert('로그인 하십시오.', G5_CHADEAL_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_CHADEAL_ADMIN_URL)));
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
//알차다
include_once(G5_CHADEAL_ADMIN_PATH . '/functions.php');


//게시판
$bo_table = "application";

$write = array();
$write_table = '';
if ($bo_table) {
    $board = get_board_db($bo_table, true);
    if (isset($board['bo_table']) && $board['bo_table']) {
        set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
        $gr_id = $board['gr_id'];
        $write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름

        if (isset($wr_id) && $wr_id) {
            $write = get_write($write_table, $wr_id);
        } else if (isset($wr_seo_title) && $wr_seo_title) {
            $write = get_content_by_field($write_table, 'bbs', 'wr_seo_title', generate_seo_title($wr_seo_title));
            if (isset($write['wr_id'])) {
                $wr_id = $write['wr_id'];
            }
        }
    }
}

$action_url = "counsel_write_update.php";

$is_category = false;
$category_option = '';
if ($board['bo_use_category']) {
    $ca_name = "";
    if (isset($write['ca_name']))
        $ca_name = $write['ca_name'];
    $category_option = get_category_option_rchada($bo_table, $ca_name);
    $is_category = true;
}

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>차딜 관리자 페이지</title>

    <link href="/chadeal_adm/css/bootstrap/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/chadeal_adm/css/bootstrap/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="/chadeal_adm/css/bootstrap/inspinia/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="/chadeal_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="/chadeal_adm/css/bootstrap/inspinia/css/animate.css" rel="stylesheet">
    <link href="/chadeal_adm/css/bootstrap/inspinia/css/style.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="/chadeal_adm/css/bootstrap/inspinia/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/jquery-2.1.1.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/bootstrap.min.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/inspinia.js"></script>
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/toastr/toastr.min.js"></script>

    <!-- Data picker -->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- Input Mask-->
    <script src="/chadeal_adm/css/bootstrap/inspinia/js/plugins/jasny/jasny-bootstrap.min.js"></script>



    <script src="/chadeal_adm/js/common.js"></script>
    <script src="/chadeal_adm/js/jquery.cookie.js"></script>

    <style type="text/css">
        .custom-contents p {
            margin: 0 !important;
        }
    </style>
</head>

<body>
    <div id="wrapper">

        <!-- NAV s -->
        <?php include_once(G5_CHADEAL_ADMIN_PATH . '/navi.inc.php'); ?>
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
                            <a href="<?php echo G5_CHADEAL_ADMIN_URL ?>/logout.php?url=<?php echo urlencode(correct_goto_url("/" . G5_CHADEAL_ADMIN_DIR)); ?>">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>



            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>상담신청 등록</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/manager/">Home</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">상담</a>
                        </li>
                        <li class="active">
                            <strong>상담신청 등록</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5><i class="fa fa-edit"></i> 상담신청 등록 <small>상담신청 게시물을 등록합니다.</small></h5>
                            </div>
                            <div class="ibox-content">
                                <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
                                    <input type="hidden" name="w" value="<?php echo $w ?>">
                                    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                                    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
                                    <input type="hidden" name="sca" value="<?php echo $sca ?>">
                                    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
                                    <input type="hidden" name="stx" value="<?php echo $stx ?>">
                                    <input type="hidden" name="spt" value="<?php echo $spt ?>">
                                    <input type="hidden" name="sst" value="<?php echo $sst ?>">
                                    <input type="hidden" name="sod" value="<?php echo $sod ?>">
                                    <input type="hidden" name="page" value="<?php echo $page ?>">


                                    <table class="table table-hover table-striped">
                                        <tr>
                                            <td class="text-center"><strong>견적구분</strong></td>
                                            <td>
                                                <div class="col-lg-3">
                                                    <select name="ca_name" id="ca_name" required class="form-control">
                                                        <option value="">분류를 선택하세요</option>
                                                        <?php echo $category_option ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><strong>차종</strong></td>
                                            <td>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" id="wr_4" name="wr_4" maxlength="200" placeholder="상담 차종을 입력해주세요." />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><strong>고객명</strong></td>
                                            <td>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" id="wr_1" name="wr_1" maxlength="200" placeholder="고객명을 입력해주세요." />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><strong>고객연락처</strong></td>
                                            <td>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" id="wr_2" name="wr_2" maxlength="200" placeholder="고객연락처를 입력해주세요." />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><strong>메모</strong></td>
                                            <td>
                                                <div class="col-lg-12">
                                                    <textarea class="form-control" id="wr_content" name="wr_content" style="resize: none; width: 100%; height: 500px"></textarea>
                                                </div>
                                            </td>
                                        </tr>
                                        <!--<tr>
										<td class="text-center"><strong>전시여부</strong></td>
										<td>
											<div class="col-lg-3">
												<select class="form-control" id="YN_DISPLAY" name="YN_DISPLAY">
													<option value="Y">예</option>
													<option value="N">아니오</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-center"><strong>TOP 공지</strong></td>
										<td>
											<div class="col-lg-3">
												<select class="form-control" id="NO_VIEWORDER" name="NO_VIEWORDER">
													<option value="100" >아니오</option>
													<option value="0" >예</option>
												</select>
											</div>
											<div class="col-lg-9">
												<span class="text-info">*공지글은 언제나 최상단에 위치합니다.</span>
											</div>
										</td>
									</tr>-->


                                    </table>

                                    <p class="text-center">
                                        <button type="button" class="btn btn-info" onclick="fn_save();"> <i class="fa fa-check"></i> 저장</button>
                                        <button type="button" class="btn" onclick="document.location.href='counsel.php'"> <i class="fa fa-list"></i> 목록</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer">
                <div>
                    <strong>Copyright</strong> CHADEAL
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function fn_save() {
            //var NM_TITLE = document.getElementById("NM_TITLE");
            //if (NM_TITLE.value == "") { alert("제목을 입력하세요"); NM_TITLE.focus(); return false; }
            if (document.getElementById("ca_name").value == "") {
                alert("견적구분을 선택해 주세요.");
                document.getElementById("ca_name").focus();
                return false;
            }

            if (document.getElementById("wr_4").value == "") {
                alert("상담차종을 입력해 주세요.");
                document.getElementById("wr_4").focus();
                return false;
            }

            if (document.getElementById("wr_1").value == "") {
                alert("고객명을 입력해 주세요.");
                document.getElementById("wr_1").focus();
                return false;
            }

            if (document.getElementById("wr_2").value == "") {
                alert("고객연락처를 입력해 주세요.");
                document.getElementById("wr_2").focus();
                return false;
            }
            /*
            		if (document.getElementById("NM_FIELD_<%=iloop%>").value == "")
            		{
            			alert("<%=ary_FIELDNM(iloop)%>를 등록해 주세요.");
            			document.getElementById("NM_FIELD_<%=iloop%>").focus();
            			return false;
            		}
            */
            document.fwrite.submit();
        }
    </script>
</body>

</html>