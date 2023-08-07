<?php
include_once('./_common.php');

$url = isset($_GET['url']) ? strip_tags($_GET['url']) : '';
$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';

// url 체크
check_url_host($url);

// 이미 로그인 중이라면
if ($is_member) {
	if ($url)
		goto_url($url);
	else
		goto_url(G5_URL);
}

$login_url        = login_url($url);
$login_action_url = G5_HTTPS_BBS_URL . "/login_check.php";
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

<body class="gray-bg">

	<div class="middle-box text-center loginscreen animated fadeInDown">
		<div>
			<div>
				<h1 class="logo-name" style="font-size: 100px; margin-left: 0px;">
					<!-- logo -->
					<!--<img src="/shopping_adm/images/logo.png" />-->
					알차다쇼핑
				</h1>
			</div>

			<h3>관리자페이지</h3>

			<form class="m-t" role="form" name="frmLogin">
				<input type="hidden" name="url" value="<?php echo $login_url ?>">
				<div class="form-group">
					<input type="text" name="mb_id" id="login_id" required class="form-control frm_input required" size="20" maxLength="20" placeholder="아이디"><!--<input class="form-control" placeholder="ID" id="userid" name="userid" type="text" >-->
				</div>
				<div class="form-group">
					<input type="password" name="mb_password" id="login_pw" required class="form-control frm_input required" size="20" maxLength="20" placeholder="비밀번호"><!--<input class="form-control" placeholder="Password" id="password" name="password" type="password" value="" >-->
				</div>
				<div class="form-group text-left">
					<label class="checkbox-inline"> <input id="id_save" name="id_save" type="checkbox" checked="checked" /> 아이디 저장 </label>
				</div>
				<button type="button" class="btn btn-primary block full-width m-b" onclick="fn_logincheck()">Login</button>

			</form>
			<p class="m-t"> <small>&copy;RCHADA</small> </p>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			// 메뉴 활성화 쿠키 초기화
			$.removeCookie("admin_menu_code_form_0", {
				path: "/",
				domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
				secure: false
			});
			$.removeCookie("admin_menu_code_form_2", {
				path: "/",
				domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
				secure: false
			});

			//$.cookie("layer_popup_20150317_001", "close", { expires: 365, path: "/", domain: "<?php echo G5_COOKIE_DOMAIN; ?>", secure: false });

			if (($.cookie("admin_save_id") === undefined) || ($.cookie("admin_save_id") == "")) {
				$("#login_id").focus();
			}

			if (($.cookie("admin_save_id") !== undefined) && ($.cookie("admin_save_id") != "")) {
				$("#login_id").val($.cookie("admin_save_id"));
				$("#login_pw").focus();
			}

			$("#login_pw").keyup(function(e) {
				if (e.keyCode == 13) {
					fn_logincheck();
				}
			});
		});

		function fn_logincheck() {
			var id = $("#login_id");
			var pw = $("#login_pw");
			var id_save = $("#id_save");

			if ($.trim(id.val()) == "") {
				alert("아이디를 입력하세요.");
				id.focus();
				return false;
			}

			if ($.trim(pw.val()) == "") {
				alert("비밀번호를 입력하세요.");
				pw.focus();
				return false;
			}

			// 아이디 기억하기
			if (id_save.prop("checked") == true) {
				$.cookie("admin_save_id", id.val(), {
					expires: 365,
					path: "/",
					domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
					secure: false
				});
			} else {
				$.removeCookie("admin_save_id", {
					path: "/",
					domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
					secure: false
				});
			}

			document.frmLogin.method = "post";
			document.frmLogin.action = "<?php echo $login_action_url ?>"
			document.frmLogin.submit();
		}
	</script>
	<?php
	run_event('member_login_tail', $login_url, $login_action_url, $member_skin_path, $url);
	?>
</body>

</html>