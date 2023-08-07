<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
switch (substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_PATH))) {
    case '/bbs/register.php':
    case '/bbs/register_form.php':
    case '/bbs/register_result.php':
    case '/plugin/social/register_member.php':
        include_once(G5_THEME_PATH . "/tail.sub.php");
        return;
        break;
}
?>
<?php if ($g5['sidebar']['right']) { ?>
    <!-- </div>

    <div class="col-lg-3">
        <?php @include G5_PATH . '/sidebar.right.php'; ?>
    </div> -->
<?php } ?>

<footer>
    <div class="inner">
        <a href="javascript:;" class="footer-logo">
            <img src="<?php echo G5_THEME_URL; ?>/common/img/logo-footer.svg" alt="">
        </a>
        <ul class="footer-desc">
            <li>
                <strong>상호명</strong> : 알차다 다이렉트
            </li>
            <li>
                <strong>사업자명</strong> : 주식회사 알차다
            </li>
            <li>
                <strong>사업자등록번호</strong> : 829-87-00474
            </li>
            <li>
                <strong>통신판매업신고번호</strong> : 2022-서울강서-2707
            </li>
            <li>
                <strong>대표자</strong> : 박상현
            </li>
            <li>
                <strong>주소</strong> : 서울특별시 강서구 마곡서로 56, 403호 404호 405호 406호(마곡동, 마곡에스비타워Ⅲ)
            </li>
            <li>
                <strong>대표번호</strong> : 1899-1549
            </li>
            <li>
                <strong>제안/제휴문의</strong> : rchada@rchada.com
            </li>
        </ul>
        <div class="policy">
                <a href="/term?no=2" target="_blank">개인정보취급방침</a>
        </div>
        <p class="copyright">
            Copyright © 주식회사 알차다 All rights reserved.
        </p>
    </div>
</footer>

<?php include_once(G5_THEME_PATH . "/popup.php"); ?>

</div>

<!-- start:레이어 팝업 -->
<div class="popup-wrap"></div>
<!-- //end:레이어 팝업 -->
<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>
<!-- } 하단 끝 -->
<?php include_once(G5_THEME_PATH . "/tail.sub.php"); ?>