<?php
include_once('./_common.php');
// 접근 권한 검사
if (!$member['mb_id']) {
    alert('로그인 하십시오.', G5_SHOPPING_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_SHOPPING_ADMIN_URL)));
}

if ($member['mb_level'] < 9) {
    goto_url(G5_URL);
}

include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

$total_res = sql_fetch("select count(*) as cnt from {$g5['shopping_application_table']} ");
$total_count = $total_res['cnt'];
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
                    <h2>Dashboard</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/shopping_adm/">Home</a>
                        </li>
                        <li class="active">
                            <strong>Dashboard</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>견적신청</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content ibox-heading">
                                <h3><i class="fa fa-envelope-o"></i> 신규 견적신청</h3>
                                <small><i class="fa fa-tim"></i> 최근 10개의 견적신청글을 조회합니다.</small>
                            </div>
                            <div class="ibox-content">
                                <div class="feed-activity-list">
                                    <?php if (!$total_count) { ?>
                                        <div class="text-center">최근 견적신청이 없습니다.</div>
                                    <?php
                                    }
                                    $sql = "select * from {$g5['shopping_application_table']} where 1 order by wr_id desc limit 0, 10";
                                    $qry = sql_query($sql);
                                    for ($i = 0; $res = sql_fetch_array($qry); $i++) {
                                    ?>
                                        <div class="feed-element">

                                            <div>
                                                <small class="pull-right text-navy"><?php echo $res['wr_datetime']; ?></small>
                                                <strong class="text-navy"><?php echo get_text($res['wr_1']); ?></strong>
                                                <div>
                                                    <i class="fa fa-mobile"></i> : <?php echo preg_replace('/([0-9]+)-([0-9]+)-([0-9]{4})/', '${1}-****-$3', hyphen_hp_number(get_text($res['wr_2']))); ?>
                                                </div>
                                                <small class="pull-right text-muted"><?php echo $res['ca_name']; ?></small>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--
					<div class="col-lg-6">
					</div>
					-->

                </div>

            </div>

            <div class="footer">
                <div>
                    <strong>Copyright</strong> RCAHDA
                </div>
            </div>
        </div>
    </div>
</body>

</html>