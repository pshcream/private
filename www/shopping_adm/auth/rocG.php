<?php
include_once('./_common.php');

//수수료율(ROC) rate of commission

// 접근 권한 검사
if (!$member['mb_id']) {
	alert('로그인 하십시오.', G5_SHOPPING_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_SHOPPING_ADMIN_URL)));
}

if ($is_admin != 'super') {
	alert("잘못된 접근입니다.", G5_URL);
}

include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

include_once(G5_SHOPPING_ADMIN_PATH . '/functions.php');
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
					<h2>일반구매수수료 관리</h2>
					<ol class="breadcrumb">
						<li>
							<a href="/shopping_adm/">Home</a>
						</li>
						<li>
							<a href="javascript:void(0)">시스템 관리</a>
						</li>
						<li>
							<a href="javascript:void(0)">수수료</a>
						</li>
						<li class="active">
							<strong>일반구매</strong>
						</li>
					</ol>
				</div>
			</div>

			<br />

			<div class="wrapper wrapper-content animated fadeInRight ">
				<div class="row">
					<div class="col-lg-6">

						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5><i class="fa fa-key"></i> 렌터카 수수료율 <small>렌트견적 수수료율을 수정합니다.</small></h5>
							</div>
							<div class="ibox-content">
								<form id="frm_cf1" name="frm_cf1" action="roc_proc.php" onsubmit="return fn_ok1();" method="post" enctype="multipart/form-data" autocomplete="off">

									<div class="form-group">
										<label>일반구매 > 렌터카 *</label>
										<input type="text" class="form-control input-sm" id="cf_1" name="cf_1" value="<?php echo $config['cf_1']; ?>" tabindex="1" placeholder="수수료율을 입력해주세요.(ex. 3% -> 0.03)" />
									</div>

									<div class="form-group">
										<button type="button" class="btn btn-info btn-sm" onclick="fn_ok1();"><i class="fa fa-check"></i> 수정</button>
									</div>

								</form>
							</div>
						</div>
					</div>
					<div class="col-lg-6">

						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5><i class="fa fa-key"></i> 오토리스 수수료율 <small>오토리스견적 수수료율을 수정합니다.</small></h5>
							</div>
							<div class="ibox-content">
								<form id="frm_cf2" name="frm_cf2" action="roc_proc.php" onsubmit="return fn_ok2();" method="post" enctype="multipart/form-data" autocomplete="off">

									<div class="form-group">
										<label>일반구매 > 오토리스 *</label>
										<input type="text" class="form-control input-sm" id="cf_2" name="cf_2" value="<?php echo $config['cf_2']; ?>" tabindex="1" placeholder="수수료율을 입력해주세요.(ex. 3% -> 0.03)" />
									</div>

									<div class="form-group">
										<button type="button" class="btn btn-info btn-sm" onclick="fn_ok2();"><i class="fa fa-check"></i> 수정</button>
									</div>

								</form>
							</div>
						</div>
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
		$(document).ready(function() {
			$("#cf_1").focus();
		});

		function fn_ok1() {
			var cf_1 = $("#cf_1");

			if (cf_1.val() == "") {
				alert("렌터카 수수료율을 입력해주세요.");
				cf_1.focus();
				return false;
			}

			document.frm_cf1.target = "hd_frame";
			document.frm_cf1.method = "post";
			document.frm_cf1.action = "roc_proc.php";
			document.frm_cf1.submit();
		}

		function fn_ok2() {
			var cf_2 = $("#cf_2");

			if (cf_2.val() == "") {
				alert("오토리스 수수료율을 입력해주세요.");
				cf_2.focus();
				return false;
			}

			document.frm_cf2.target = "hd_frame";
			document.frm_cf2.method = "post";
			document.frm_cf2.action = "roc_proc.php";
			document.frm_cf2.submit();
		}
	</script>
	<iframe type="hiddenframe" id="hd_frame" name="hd_frame" style="display:none;"></iframe>
</body>

</html>