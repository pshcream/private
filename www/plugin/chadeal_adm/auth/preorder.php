<?php
include_once('./_common.php');

// 접근 권한 검사
if (!$member['mb_id']) {
	alert('로그인 하십시오.', G5_NH_ADMIN_URL . '/login.php?url=' . urlencode(correct_goto_url(G5_NH_ADMIN_URL)));
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

include_once(G5_CHADEAL_ADMIN_PATH . '/functions.php');

//리스트
$page_size = 20;
$colspan = 12;
if ($page < 1) $page = 1;

$sql_group = "";
$sql_search = "";

$total_res = sql_fetch("select count(*) as cnt from {$g5['nh_car_preordered_table']} where 1 $sql_group $sql_search");
$total_count = $total_res['cnt'];

$total_page = (int)($total_count / $page_size) + ($total_count % $page_size == 0 ? 0 : 1);
$page_start = $page_size * ($page - 1);

$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . "?page=");
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
	<title>차딜 관리자 페이지</title>

	<link href="/nh_adm/css/bootstrap/inspinia/css/bootstrap.min.css" rel="stylesheet">
	<link href="/nh_adm/css/bootstrap/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

	<!-- Toastr style -->
	<link href="/nh_adm/css/bootstrap/inspinia/css/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!-- Gritter -->
	<link href="/nh_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

	<link href="/nh_adm/css/bootstrap/inspinia/css/animate.css" rel="stylesheet">
	<link href="/nh_adm/css/bootstrap/inspinia/css/style.css" rel="stylesheet">

	<!-- Sweet Alert -->
	<link href="/nh_adm/css/bootstrap/inspinia/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

	<!-- Mainly scripts -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/jquery-2.1.1.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/bootstrap.min.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

	<!-- Flot -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.spline.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.resize.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/flot/jquery.flot.pie.js"></script>

	<!-- Peity -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/peity/jquery.peity.min.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/demo/peity-demo.js"></script>

	<!-- Custom and plugin javascript -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/inspinia.js"></script>
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/pace/pace.min.js"></script>

	<!-- jQuery UI -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/jquery-ui/jquery-ui.min.js"></script>

	<!-- GITTER -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/gritter/jquery.gritter.min.js"></script>

	<!-- Sparkline -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/sparkline/jquery.sparkline.min.js"></script>

	<!-- Sparkline demo data  -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/demo/sparkline-demo.js"></script>

	<!-- ChartJS-->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/chartJs/Chart.min.js"></script>

	<!-- Toastr -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/toastr/toastr.min.js"></script>

	<!-- Data picker -->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/datapicker/bootstrap-datepicker.js"></script>

	<!-- Input Mask-->
	<script src="/nh_adm/css/bootstrap/inspinia/js/plugins/jasny/jasny-bootstrap.min.js"></script>



	<script src="/nh_adm/js/common.js"></script>
	<script src="/nh_adm/js/jquery.cookie.js"></script>
	<script src="/nh_adm/js/rchada.js"></script>

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
							<a href="<?php echo G5_NH_ADMIN_URL ?>/logout.php?url=<?php echo urlencode(correct_goto_url("/" . G5_NH_ADMIN_DIR)); ?>">
								<i class="fa fa-sign-out"></i> Log out
							</a>
						</li>
					</ul>
				</nav>
			</div>



			<div class="row wrapper border-bottom white-bg page-heading">
				<div class="col-lg-10">
					<h2>선구매리스트 관리</h2>
					<ol class="breadcrumb">
						<li>
							<a href="/nh_adm/">Home</a>
						</li>
						<li>
							<a href="javascript:void(0)">선구매</a>
						</li>
						<li class="active">
							<strong>선구매리스트</strong>
						</li>
					</ol>
				</div>
			</div>

			<br />

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">

						<form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
							<input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
							<input type="hidden" name="w" value="<?php echo $w ?>">
							<input type="hidden" name="nh_id" value="<?php echo $nh_id ?>">
							<input type="hidden" name="selectedOptions" id="selectedOptions" value="" />
							<input type="hidden" name="setTrms" id="setTrms" value="" />
							<input type="hidden" name="price" id="price" value="" />

							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5><i class="fa fa-pencil"></i> 등록 <small>선구매차량을 등록 합니다.</small></h5>
									<div class="ibox-tools">
										<a class="collapse-link">
											<i class="fa fa-chevron-up"></i>
										</a>
									</div>
								</div>
								<div class="ibox-content m-b-sm border-bottom">
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="amount">브랜드</label>
												<select name="brn" id="brn" class="form-control">
													<option value="">브랜드</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="amount">모델</label>
												<select name="modl" id="modl" class="form-control">
													<option value="">모델</option>
												</select>
											</div>
										</div>
										<div class="col-sm-5">
											<div class="form-group">
												<label class="control-label" for="amount">라인업</label>
												<select name="trim" id="trim" class="form-control">
													<option value="">라인업</option>
												</select>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="amount">상세트림</label>
												<select name="dtl_trim" id="dtl_trim" class="form-control">
													<option value="">상세트림</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-6" id="Optn_1">
											<label class="control-label" for="">옵션</label>
											<div class="list-group checkbox-radios eChkItemList" id="options"></div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="sGUBUN">외장색상</label>
												<select name="ext_color" id="ext_color" class="form-control">
													<option value="">외장색상</option>
												</select>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="sGUBUN">내장색상</label>
												<select name="int_color" id="int_color" class="form-control">
													<option value="">내장색상</option>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="releaseType">출고구분</label>
												<select name="releaseType" id="releaseType" class="form-control">
													<option value="">출고구분</option>
													<option value="특판">특판</option>
													<option value="대리점">대리점</option>
												</select>
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="specialExcise">개소세</label>
												<select name="specialExcise" id="specialExcise" class="form-control">
													<option value="">개소세</option>
													<option value="0.05">5%</option>
													<option value="0.035">3.5%</option>
												</select>
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="stockType">재고타입</label>
												<select name="stockType" id="stockType" class="form-control">
													<option value="">재고타입</option>
													<option value="렌트">렌트</option>
													<option value="리스">리스</option>
													<option value="렌트리스">렌트리스</option>
												</select>
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="orderDateNm">출고타입</label>
												<select name="orderDateNm" id="orderDateNm" class="form-control">
													<option value="">출고타입</option>
													<option value="출고예정">출고예정</option>
													<option value="선출고">선출고</option>
													<option value="선등록">선등록</option>
												</select>
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="orderCSN">계약번호</label>
												<input type="text" class="form-control input-sm" id="orderCSN" name="orderCSN" value="" placeholder="or 차량번호" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="orderDate">출고(예정)일</label>
												<input id="orderDate" name="orderDate" type="text" class="form-control text-center" value="" data-mask="9999-99-99" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="subsidy">전기차보조금</label>
												<input type="text" class="form-control input-sm" id="subsidy" name="subsidy" value="" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="discount">할인</label>
												<input type="text" class="form-control input-sm" id="discount" name="discount" value="" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="consignmentFee">탁송료</label>
												<input type="text" class="form-control input-sm" id="consignmentFee" name="consignmentFee" value="" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="consignmentFee">출고대수</label>
												<input type="text" class="form-control input-sm" id="orderAmount" name="orderAmount" value="" />
											</div>
										</div>
										<div class="col-sm-1">
											<div class="form-group">
												<label class="control-label" for="">&nbsp;</label>
												<div class="input-group">
													<button type="button" class="btn btn-success btn-sm" onclick="fn_save();"><i class="fa fa-plus"></i> 추가</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>

						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5><i class="fa fa-file"></i> 선구매리스트 <small>선구매 차량의 정보를 관리합니다.</small></h5>
							</div>
							<div class="ibox-content">
								<div class="table-responsive">

									<table class="table table-striped table-hover">
										<thead>
											<tr>
												<th class="text-center">브랜드<br />출고구분</th>
												<th class="text-center">모델</th>
												<th class="text-center">라인업</th>
												<th class="text-center">상세트림</th>
												<th class="text-center">옵션</th>
												<th class="text-center">외장색상</th>
												<th class="text-center">내장색상</th>
												<th class="text-center">총차량가<br />개소세율</th>
												<th class="text-center">등록/출고<br />예정일</th>
												<th class="text-center">재고타입</th>
												<th class="text-center">출고대수</th>
												<th class="text-center">관리</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!$total_count) { ?>
												<tr>
													<td class="text-center" colspan="<?php echo $colspan ?>">데이터가 존재하지 않습니다.</td>
												</tr>
											<?php
											}

											$sql = "select * from {$g5['nh_car_preordered_table']} where 1 $sql_group $sql_search order by nh_id desc limit $page_start, $page_size";
											$qry = sql_query($sql);
											$no = 1;
											while ($res = sql_fetch_array($qry)) {
												$colorNm = explode("`", $res['colorNm']);
												$innColorNm = explode("`", $res['innColorNm']);

												//개소세5% 색상가
												if ($res['lo_car_price'] > 85910000) {
													$ext_color_price = $colorNm[2];
												} else {
													$ext_color_price = getUpCarPrice($colorNm[2]);
												}

												//옵션
												$options = explode("!", $res['optNmArr']);
												$tmp = "";
												$tmpArray = array();
												$tmpPriceArray = array();
												$i = 0;
												foreach ($options as $sel) {
													$t = explode("`", $sel);
													$tmpArray[] = $t[2];
													$tmpPriceArray[] = array($t[1], $t[2]);

													if ($i > 0)
														$tmp .= ", " . $t[2];
													else
														$tmp .= $t[2];

													$i++;
												}
											?>
												<tr>
													<td class="text-center"><?php echo $res['brnNm']; ?><br /><?php echo $res['releaseType']; ?></td>
													<td class="text-center"><?php echo $res['modlNm']; ?></td>
													<td class="text-center"><?php echo $res['trimNm']; ?></td>
													<td class="text-center"><?php echo $res['dtlTrimNm']; ?></td>
													<td class="text-center">
														<?php
														if (strlen($tmpPriceArray[0][1]) > 0) {
															foreach ($tmpPriceArray as $sel) {
														?>
																<span class="badge"><?php echo $sel[1]; ?></span>
														<?php
															}
														}
														?>
													</td>
													<td class="text-center"><?php echo $colorNm[1]; ?><!--<br /><?php echo number_format($colorNm[2]); ?>--></td>
													<td class="text-center"><?php echo $innColorNm[1]; ?></td>
													<td class="text-center"><?php echo number_format($res['pretaxPrice']); ?><br />(<?php echo ($res['specialExcise'] * 100); ?>%)</td>
													<td class="text-center"><?php echo $res['orderDate']; ?><br /><?php echo $res['orderDateNm']; ?></td>
													<td class="text-center"><?php echo $res['stockType']; ?></td>
													<td class="text-center"><?php echo number_format($res['orderAmount']); ?></td>
													<td class="text-center">
														<?php /*<button type="button" class="btn btn-info btn-sm" onclick="" disabled><i class="fa fa-edit"></i> 수정</button>*/ ?>
														<button type="button" class="btn btn-danger btn-sm" onclick="fn_del('<?php echo $res['nh_id'] ?>')"><i class="fa fa-trash"></i> 삭제</button>
													</td>
												</tr>
												<tr>
													<td class="" colspan="<?php echo $colspan ?>">
														<?php echo ($res['orderCSN'] != "") ? "[" . $res['orderCSN'] . "]" : ""; ?>
														▶ 할인 : <?php echo number_format($res['discount']); ?> <?php echo ($res['subsidy'] > 0) ? " | 전기차 보조금 : " . number_format($res['subsidy']) : ""; ?>
													</td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-center" colspan="<?php echo $colspan ?>">
													<?php echo $write_pages;  ?>
												</td>
											</tr>
										</tfoot>
									</table>
								</div><!-- /.table-responsive -->
							</div>
						</div>

					</div>

				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->

			<div class="footer">
				<div>
					<strong>Copyright</strong> CHADEAL
				</div>
			</div>
		</div>
	</div>




	<script type="text/javascript">
		var setTrms = new Array();
		setTrms.ctax = 3.5;
		var $brn, $modl, $trim, $dtl_trim, $ext_color, $int_color, $options, $car_price, $opt_price, $tot_price, $car_price_txt, $opt_price_txt, $tot_price_txt;
		jQuery(function($) {
			$brn = $("#brn");
			$modl = $("#modl");
			$trim = $("#trim");
			$dtl_trim = $("#dtl_trim");
			$ext_color = $("#ext_color");
			$int_color = $("#int_color");
			$options = $("#options");
			$car_price = $("#car_price");
			$opt_price = $("#opt_price");
			$tot_price = $("#tot_price");
			$car_price_txt = $("#car_price_txt");
			$opt_price_txt = $("#opt_price_txt");
			$tot_price_txt = $("#tot_price_txt");

			//출고일
			$('#orderDate').datepicker({
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				autoclose: true,
				format: 'yyyy-mm-dd',
				keyboardNavigation: true,
				language: 'ko'
			});

			getBRN();

			$brn.on("change", function(e) {
				const $this = $(this);
				const _brn = $this.val();
				if (_brn == "") {
					alert("브랜드를 선택해주세요.");
					return false;
				}
				setTrms.brnNO = _brn;
				setTrms.brnNM = $this.find("option:selected").text();
				setTrms.nation = $this.find("option:selected").attr("data-nation");
				resetModl();
				resetTrim();
				resetDtlTrim();
				resetOptCol();
				resetPrice();
				chgBRN(_brn);
			});
			$modl.on("change", function(e) {
				const $this = $(this);
				const _model = $this.val();
				if (_model == "") {
					alert("모델을 선택해주세요.");
					return false;
				}
				setTrms.modlNO = _model;
				setTrms.modlNM = $this.find("option:selected").text();
				resetTrim();
				resetDtlTrim();
				resetOptCol();
				resetPrice();
				chgMODL(_model);
			});

			$trim.on("change", function(e) {
				const $this = $(this);
				const _trim = $this.val();
				if (_trim == "") {
					alert("라인업을 선택해주세요.");
					return false;
				}
				setTrms.trimNO = _trim;
				setTrms.trimNM = $this.find("option:selected").text();
				resetDtlTrim();
				resetOptCol();
				resetPrice();
				chgTRIM(_trim);
			});

			$dtl_trim.on("change", function(e) {
				const $this = $(this);
				const _dtl_trim = $this.val();
				if (_dtl_trim == "") {
					alert("상세트림을 선택해주세요.");
					return false;
				}
				setTrms.dtltrimNO = _dtl_trim;
				setTrms.dtltrimNM = $this.find("option:selected").text();
				resetOptCol();
				resetPrice2();
				chgDTLTRIM(_dtl_trim);
			});
		});

		function getBRN(selected) {
			jQuery.ajax({
				type: "GET",
				url: "https://installment.rchadacort.com/v1_00/car/brand",
				contentType: "application/json; charset=UTF-8",
				dataType: 'json',
				success: function(msg) {
					for (var i = 0; i < msg.count; i++) {
						if (selected == msg.data[i].idx) {
							$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("data-nation", msg.data[i] && msg.data[i].nation).attr("selected", "selected").appendTo($brn);
						} else {
							$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("data-nation", msg.data[i] && msg.data[i].nation).appendTo($brn);
						}
					}
				},
			}).fail(function() {
				alert("정상적인 방법으로 이용해주세요.");
			});
		}

		function chgBRN(_brn, selected = false) {
			jQuery.ajax({
				type: "GET",
				url: "https://installment.rchadacort.com/v1_00/car/model/" + _brn,
				contentType: "application/json; charset=UTF-8",
				dataType: 'json',
				success: function(msg) {
					for (var i = 0; i < msg.count; i++) {
						if (!filterModl(msg.data[i].idx)) {
							if (selected == msg.data[i].idx) {
								$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("selected", "selected").appendTo($modl);
							} else {
								$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).appendTo($modl);
							}
						}

					}
					if (selected) {
						chgMODL(selected, '');
					}
				},
			}).fail(function() {
				alert("정상적인 방법으로 이용해주세요.");
			});
		}

		function chgMODL(_model, selected = false) {
			jQuery.ajax({
				type: "GET",
				url: "https://installment.rchadacort.com/v1_00/car/lineup/" + _model,
				contentType: "application/json; charset=UTF-8",
				dataType: 'json',
				success: function(msg) {
					for (var i = 0; i < msg.data.length; i++) {
						if (!filterTrim(msg.data[i].name)) {
							if (selected == msg.data[i].idx) {
								$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("title", msg.data[i] && msg.data[i].name).attr("selected", "selected").appendTo($trim);
							} else {
								$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("title", msg.data[i] && msg.data[i].name).appendTo($trim);
							}
						}
					}
					if (selected) {
						chgTRIM(selected, '<?php echo $row['lo_dtl_trim']; ?>');
					}
				},
			}).fail(function() {
				alert("정상적인 방법으로 이용해주세요.");
			});
		}

		function chgTRIM(_trim, selected = false) {
			jQuery.ajax({
				type: "GET",
				url: "https://installment.rchadacort.com/v1_00/car/trim/" + _trim,
				contentType: "application/json; charset=UTF-8",
				dataType: 'json',
				success: function(msg) {

					for (var i = 0; i < msg.data.length; i++) {
						if (selected == msg.data[i].idx) {
							$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("data-price", msg.data[i] && msg.data[i].pretaxprice).attr("selected", "selected").appendTo($dtl_trim);
						} else {
							$('<option>').text(msg.data[i] && msg.data[i].name).val(msg.data[i] && msg.data[i].idx).attr("data-price", msg.data[i] && msg.data[i].pretaxprice).appendTo($dtl_trim);
						}
					}
					if (selected) {
						chgDTLTRIM(selected, '<?php echo $row['lo_options']; ?>', '<?php echo $row['lo_ext_color']; ?>', '<?php echo $row['lo_int_color']; ?>');
					}
				},
			}).fail(function() {
				alert("정상적인 방법으로 이용해주세요.");
			});
		}

		function chgDTLTRIM(_dtl_trim, selectedOpions = false, selectedColor = false, selectedInnerColor = false) {
			let optCode = new Array();
			let divOpt = new Array();

			if (selectedOpions) {
				divOpt = selectedOpions.split("!");

				for (let i = 0; i <= divOpt.length - 1; i++) {
					let tmp = divOpt[i].split("`");

					optCode.push(tmp[0]);
				}
			}


			//차량가
			setTrms.pric = $dtl_trim.find("option:selected").attr("data-price");
			$car_price.val(setTrms.pric);
			$car_price_txt.val(addCommas(setTrms.pric));

			jQuery.ajax({
				type: "GET",
				url: "https://installment.rchadacort.com/v1_01/car/info/" + _dtl_trim,
				contentType: "application/json; charset=UTF-8",
				dataType: 'json',
				success: function(msg) {
					for (let i = 0; i < msg.options.length; i++) {
						if (!filterOption(msg.options[i].name.replace('&amp;', '&'))) {
							if (msg.options[i] && msg.options[i].status >= 1) {
								$('<label>').text("　" + msg.options[i] && msg.options[i].name.replace('&amp;', '&')).attr("class", "list-group-item").appendTo($options);
								if (optCode.length > 0) {
									for (let opt of optCode) {
										if (opt == msg.options[i].idx) {
											$('<input>').attr({
												"type": "checkbox",
												"name": "options",
												"id": "item_1" + (msg.options[i] && msg.options[i].idx),
												"class": "form-check-input me-1"
											}).val(msg.options[i] && msg.options[i].idx).attr("data-price", msg.options[i] && msg.options[i].pretaxprice).attr("data-overlap", msg.options[i] && msg.options[i].restriction).attr("data-label", msg.options[i] && msg.options[i].name.replace('&amp;', '&')).attr("checked", "checked").prependTo($(".list-group-item").eq(i));
										} else {
											$('<input>').attr({
												"type": "checkbox",
												"name": "options",
												"id": "item_1" + (msg.options[i] && msg.options[i].idx),
												"class": "form-check-input me-1"
											}).val(msg.options[i] && msg.options[i].idx).attr("data-price", msg.options[i] && msg.options[i].pretaxprice).attr("data-overlap", msg.options[i] && msg.options[i].restriction).attr("data-label", msg.options[i] && msg.options[i].name.replace('&amp;', '&')).prependTo($(".list-group-item").eq(i));
										}
									}
								} else {
									$('<input>').attr({
										"type": "checkbox",
										"name": "options",
										"id": "item_1" + (msg.options[i] && msg.options[i].idx),
										"class": "form-check-input me-1"
									}).val(msg.options[i] && msg.options[i].idx).attr("data-price", msg.options[i] && msg.options[i].pretaxprice).attr("data-overlap", msg.options[i] && msg.options[i].restriction).attr("data-label", msg.options[i] && msg.options[i].name.replace('&amp;', '&')).prependTo($(".list-group-item").eq(i));
								}


								//$('<label>').text(msg.options[i]&&msg.options[i].name.replace('&amp;', '&')).attr({"class":"form-check-label","for":"item_1"+(msg.options[i]&&msg.options[i].idx)}).appendTo($(".list-group-item").eq(i));
							}
						}
					}

					//외장색상colors
					for (let i = 0; i < msg.colors.length; i++) {
						if (selectedColor == msg.colors[i].idx + "`" + msg.colors[i].name + "`" + msg.colors[i].price) {

							if (msg.colors[i] && msg.colors[i].price > 0) {
								$('<option>').text(msg.colors[i] && msg.colors[i].name + "(" + msg.colors[i] && msg.colors[i].price + ")").val(msg.colors[i] && (msg.colors[i].idx + "`" + msg.colors[i].name + "`" + msg.colors[i].price)).attr("data-price", msg.colors[i] && msg.colors[i].price).attr("selected", "selected").appendTo($ext_color);
							} else {
								$('<option>').text(msg.colors[i] && msg.colors[i].name).val(msg.colors[i] && (msg.colors[i].idx + "`" + msg.colors[i].name + "`" + msg.colors[i].price)).attr("data-price", msg.colors[i] && msg.colors[i].price).attr("selected", "selected").appendTo($ext_color);
							}

						} else {
							if (msg.colors[i] && msg.colors[i].price > 0) {
								$('<option>').text(msg.colors[i] && (msg.colors[i].name + "(" + number_format(msg.colors[i].price) + ")")).val(msg.colors[i] && (msg.colors[i].idx + "`" + msg.colors[i].name + "`" + msg.colors[i].price)).attr("data-price", msg.colors[i] && msg.colors[i].price).appendTo($ext_color);
							} else {
								$('<option>').text(msg.colors[i] && msg.colors[i].name).val(msg.colors[i] && (msg.colors[i].idx + "`" + msg.colors[i].name + "`" + msg.colors[i].price)).attr("data-price", msg.colors[i] && msg.colors[i].price).appendTo($ext_color);
							}

						}
					}

					//내장색상innerColors
					for (let i = 0; i < msg.innerColors.length; i++) {
						if (selectedInnerColor == msg.innerColors[i].idx + "`" + msg.innerColors[i].name) {
							$('<option>').text(msg.innerColors[i] && msg.innerColors[i].name).val(msg.innerColors[i] && (msg.innerColors[i].idx + "`" + msg.innerColors[i].name)).attr("selected", "selected").appendTo($int_color);
						} else {
							$('<option>').text(msg.innerColors[i] && msg.innerColors[i].name).val(msg.innerColors[i] && (msg.innerColors[i].idx + "`" + msg.innerColors[i].name)).appendTo($int_color);
						}
					}
				},
			}).fail(function() {
				alert("정상적인 방법으로 이용해주세요.");
			});
		}

		function filterModl(modl) {

			var params = {
				modl: modl
			};
			var result = false;
			return result; //임의
			$.ajax({
				url: "<?php echo G5_LOTTE_URL; ?>/ajax.modl_filter.php",
				data: params,
				type: "POST",
				async: false,
				cache: false,
				success: function(data) {
					if (data > 0)
						result = true;
				}
			});

			return result;
		}

		function filterTrim(trim) {
			var result = false;
			var filter_list = []; //["카고","캠퍼","킨더","특장차"];

			for (var i = 0; i < filter_list.length; i++) {
				if (trim.indexOf(filter_list[i]) > 0) {
					result = true;
				}
			}

			return result;
		}

		function filterOption(options) {
			var result = false;
			var filter_list = []; //["트레일러","히치"];

			for (var i = 0; i < filter_list.length; i++) {
				if (options.indexOf(filter_list[i]) > 0) {
					result = true;
				}
			}

			return result;
		}

		function resetModl() {
			$modl.empty();
			$('<option>').text("모델").val("").appendTo($modl);
		}

		function resetTrim() {
			$trim.empty();
			$('<option>').text("라인업").val("").appendTo($trim);
		}

		function resetDtlTrim() {
			$dtl_trim.empty();
			$('<option>').text("상세트림").val("").appendTo($dtl_trim);
		}

		function resetOptCol() {
			$options.empty();
			$ext_color.empty();
			$int_color.empty(); //$int_color.val("");
			$('<option>').text("외장색상").val("").attr("data-price", 0).appendTo($ext_color);
			$('<option>').text("내장색상").val("").appendTo($int_color);
		}

		function resetPrice() {
			$car_price.val(0);
			$car_price_txt.val(0);
			$opt_price.val(0);
			$opt_price_txt.val(0);
			$tot_price.val(0);
			$tot_price_txt.val(0);
		}

		function resetPrice2() {
			$opt_price.val(0);
			$opt_price_txt.val(0);
			$tot_price.val(0);
			$tot_price_txt.val(0);
		}

		function fn_save() {
			const jsonString = JSON.stringify(Object.assign({}, setTrms));
			const jsonObj = JSON.parse(jsonString);
			$("#setTrms").val(jsonString);

			if (document.getElementById("brn").value == "") {
				alert("브랜드를 선택해 주세요.");
				document.getElementById("brn").focus();
				return false;
			}

			if (document.getElementById("modl").value == "") {
				alert("모델을 선택해 주세요.");
				document.getElementById("modl").focus();
				return false;
			}

			if (document.getElementById("trim").value == "") {
				alert("라인업을 선택해 주세요.");
				document.getElementById("trim").focus();
				return false;
			}
			if (document.getElementById("dtl_trim").value == "") {
				alert("상세트림을 선택해 주세요.");
				document.getElementById("dtl_trim").focus();
				return false;
			}
			if (document.getElementById("ext_color").value == "") {
				alert("외장색상을 선택해 주세요.");
				document.getElementById("ext_color").focus();
				return false;
			}
			if (document.getElementById("int_color").value == "") {
				alert("내장색상을 선택해 주세요.");
				document.getElementById("int_color").focus();
				return false;
			}
			if (document.getElementById("releaseType").value == "") {
				alert("출고구분을 선택해 주세요.");
				document.getElementById("releaseType").focus();
				return false;
			}
			if (document.getElementById("specialExcise").value == "") {
				alert("개소세율을 선택해 주세요.");
				document.getElementById("specialExcise").focus();
				return false;
			}
			if (document.getElementById("stockType").value == "") {
				alert("재고타입을 선택해 주세요.");
				document.getElementById("stockType").focus();
				return false;
			}
			if (document.getElementById("orderDateNm").value == "") {
				alert("출고타입을 선택해 주세요.");
				document.getElementById("orderDateNm").focus();
				return false;
			}
			if (document.getElementById("orderAmount").value == "") {
				alert("출고대수를 입력해 주세요.");
				document.getElementById("orderAmount").focus();
				return false;
			}

			$.ajax({
				url: "<?php echo G5_NH_ADMIN_AUTH_URL; ?>/ajax.preorder_save.php",
				data: $("#fwrite").serialize(),
				//data : jsonObj,
				type: "POST",
				async: false,
				cache: false,
				success: function(data) {
					//console.log(data);
					if (data > 0) {
						alert("선구매차량 등록이 완료되었습니다.");
						//reset
						document.location.replace("<?php echo G5_NH_ADMIN_AUTH_URL; ?>/preorder.php");
					}
				}
			}); //ajax
			return false;

			//document.fwrite.submit();
			//var NM_TITLE = document.getElementById("NM_TITLE");
			//if (NM_TITLE.value == "") { alert("제목을 입력하세요"); NM_TITLE.focus(); return false; }


		}

		function fn_del(CD_BOARDID) {
			if (confirm("삭제하시겠습니까?")) {

				var del_CD_BOARDID = document.getElementById("del_CD_BOARDID");
				del_CD_BOARDID.value = CD_BOARDID;

				document.frmDel.target = "hd_frame";
				document.frmDel.method = "post";
				document.frmDel.action = "preorder_del_proc.php";
				document.frmDel.submit();
			}
		}
	</script>

	<form name="frmDel">
		<input type="hidden" id="del_CD_BOARDID" name="del_CD_BOARDID" value="" />
	</form>

	<iframe type="hiddenframe" id="hd_frame" name="hd_frame" style="display:none;"></iframe>
</body>

</html>