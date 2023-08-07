<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<?php if ($is_admin == 'super') {  ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime() - $begin_time; ?><br></div> --><?php }  ?>

<?php run_event('tail_sub'); ?>

<script src="<?php echo G5_THEME_URL; ?>/common/js/common.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_THEME_URL; ?>/common/js/wow.min.js?ver=<?php echo G5_JS_VER; ?>"></script>
<!-- 공통 적용 스크립트 , 모든 페이지에 노출되도록 설치. 단 전환페이지 설정값보다 항상 하단에 위치해야함 --> 
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"> </script> 
<script type="text/javascript"> 
if (!wcs_add) var wcs_add={};
wcs_add["wa"] = "s_53d3b89bf459";
if (!_nasa) var _nasa={};
if(window.wcs){
wcs.inflow();
wcs_do(_nasa);
}
</script>

</body>

</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다.
