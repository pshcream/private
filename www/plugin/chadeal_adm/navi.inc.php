<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="nav-header">
				<div class="dropdown profile-element">
					<!--<span>
								<img alt="image" class="img-circle" src="img/profile_small.jpg" />
                            </span>
							-->
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $member['mb_nick']; ?>님</strong>
							</span> <span class="text-muted text-xs block"><?php echo $member['mb_id']; ?> <b class="caret"></b></span> </span> </a>
					<ul class="dropdown-menu animated fadeInRight m-t-xs">
						<!--
                                <li><a href="profile.html">Profile</a></li>
                                <li><a href="contacts.html">Contacts</a></li>
                                <li><a href="mailbox.html">Mailbox</a></li>
                                <li class="divider"></li>
								-->
						<li><a href="<?php echo G5_CHADEAL_ADMIN_URL ?>/logout.php?url=<?php echo urlencode(correct_goto_url("/" . G5_CHADEAL_ADMIN_DIR)); ?>">Logout</a></li>
					</ul>
				</div>
				<div class="logo-element">
					Admin
				</div>
			</li>
			<li class="manager-app-menu-level-0" data-codeform0="RESERVE">
				<a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">상담</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse " style="">

					<li class="manager-app-menu-level-1">
						<a href="javascript:void(0);"><span class="text-info">상담</span></a>
					</li>

					<li class="manager-app-menu-level-2" data-codeform0="RESERVE" data-codeform2="RESERVE_BOARD">
						<a href="/chadeal_adm/board/counsel.php" target="_self"><i class="fa fa-list"></i> 상담신청</a>
					</li>

				</ul>
			</li>
			<?php if ($is_admin == "super") { ?>
				<!-- <li class="manager-app-menu-level-0" data-codeform0="PREORDER">
                        <a href="#"><i class="fa fa-car"></i> <span class="nav-label">선구매</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse" style="">
					
							<li class="manager-app-menu-level-1">
								<a href="javascript:void(0);"><span class="text-info">선구매</span></a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="PREORDER" data-codeform2="PREORDER_LIST">
								<a href="/chadeal_adm/auth/preorder.php" target="_self"><i class="fa fa-list"></i>선구매리스트</a>
							</li>
					
						</ul>
					</li> -->
			<?php } ?>
			<?php if ($is_admin == "super") { ?>
				<li class="manager-app-menu-level-0" data-codeform0="SYSTEM">
					<a href="#"><i class="fa fa-cogs"></i> <span class="nav-label">시스템 관리 </span><span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse" style=""><!--in-->

						<li class="manager-app-menu-level-1">
							<a href="javascript:void(0);"><span class="text-info">수수료</span></a>
						</li>

						<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_ROCG">
							<a href="/chadeal_adm/auth/rocG.php" target="_self"><i class="fa fa-calculator"></i>일반구매</a>
						</li>
						<!-- 							
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_ROCP">
								<a href="/chadeal_adm/auth/rocP.php" target="_self"><i class="fa fa-money"></i>선구매</a>
							</li> -->
						<?php /*
							<li class="manager-app-menu-level-1">
								<a href="javascript:void(0);"><span class="text-info">사용자관리</span></a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_ACCOUNT">
								<a href="/manager/auth/UserList.asp" target="_self"><i class="fa fa-user"></i> 접속계정 관리</a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_PASS">
								<a href="/manager/auth/UserInfo.asp" target="_self"><i class="fa fa-key"></i> 비밀번호 수정</a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_AUTH">
								<a href="/manager/auth/UserAuth.asp" target="_self"><i class="fa fa-user"></i> 사용자별 권한관리</a>
							</li>
					
							<li class="manager-app-menu-level-1">
								<a href="javascript:void(0);"><span class="text-info">코드 관리</span></a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_CODE_MAST">
								<a href="/manager/code/codeMast.asp" target="_self"><i class="fa fa-list"></i> 코드 마스터</a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="SYSTEM" data-codeform2="SYS_CODE_DETAIL">
								<a href="/manager/code/codeDetail.asp" target="_self"><i class="fa fa-list"></i> 코드 디테일</a>
							</li>
							*/ ?>
					</ul>
				</li>
			<?php } ?>
			<!--
					<li class="manager-app-menu-level-0" data-codeform0="<%=menu_CD_FORM_0%>">
                        <a href="#"><i class="fa <%=menu_CD_EDITION_0%>"></i> <span class="nav-label"><%=menu_NM_FORM_0%> </span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
					
							<li class="manager-app-menu-level-1">
								<a href="javascript:void(0);"><span class="text-info"><%=menu_NM_FORM_1%></span></a>
							</li>
					
							<li class="manager-app-menu-level-2" data-codeform0="<%=menu_CD_FORM_0%>" data-codeform2="<%=menu_CD_FORM_2%>">
								<a href="<%=menu_NM_URL_2%>" target="_<%=menu_CD_TARGET_2%>"><i class="fa <%=menu_CD_EDITION_2%>"></i> <%=menu_NM_FORM_2%></a>
							</li>
					
						</ul>
					</li>
					-->

		</ul>

	</div>
</nav>

<script type="text/javascript">
	$(document).ready(function() {
		$("li.manager-app-menu-level-2").click(function() {
			//alert($(this).data("codeform0"));
			//$(this).data("codeform0");
			//$(this).data("codeform2");
			$.cookie("admin_menu_code_form_0", $(this).data("codeform0"), {
				expires: 1,
				path: "/",
				domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
				secure: false
			});
			$.cookie("admin_menu_code_form_2", $(this).data("codeform2"), {
				expires: 1,
				path: "/",
				domain: "<?php echo G5_COOKIE_DOMAIN; ?>",
				secure: false
			});
		});
	});

	$(window).load(function() {

		$("li.manager-app-menu-level-0").removeClass("active");
		$("li.manager-app-menu-level-2").removeClass("active");

		var admin_menu_code_form_0 = "";
		var admin_menu_code_form_2 = "";

		if (($.cookie("admin_menu_code_form_0") !== undefined) && ($.cookie("admin_menu_code_form_0") != "")) {
			admin_menu_code_form_0 = $.cookie("admin_menu_code_form_0");
		}

		if (($.cookie("admin_menu_code_form_2") !== undefined) && ($.cookie("admin_menu_code_form_2") != "")) {
			admin_menu_code_form_2 = $.cookie("admin_menu_code_form_2");
		}

		if (admin_menu_code_form_0 != "" && admin_menu_code_form_2 != "") {

			$("li.manager-app-menu-level-2").each(function() {
				if ($(this).data("codeform0") == admin_menu_code_form_0 && $(this).data("codeform2") == admin_menu_code_form_2) {
					$(this).addClass("active");
					$(this).parent("ul").prev("a").click();

					//if ($(this).find("a").attr("href").indexOf("<%=menu_active_check_url%>") == -1) {
					//	document.location.href=$(this).find("a").attr("href");
					//}

					return false; // break;
				}
			});

		}
	});
</script>