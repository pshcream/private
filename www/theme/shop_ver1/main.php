<link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/common/css/main.css?ver=<?php echo G5_CSS_VER; ?>">

<!-- 국산차 인기상품 -->
<main>
    <div class="main-tbox">
        <h1></h1>
        <p></p>
    </div>
    <div class="main-best">
        <i class="main-best-icon">
            <img src="<?php echo G5_THEME_URL; ?>/common/img/main-icon1.svg" alt="">
        </i>
        <ul></ul>
    </div>
</main>
<div class="logo-box" id="logo-box">
    <ul></ul>
</div>
<!-- end -->
<!-- 상품 리스트 -->
<section class="sec-main-car">
    <div class="sec-inner main-inner">
        <div class="car-pg"></div>
        <!-- 모바일에서만 노출되는 더보기 버튼 -->
        <button type="button" class="car-item-more">더보기</button>
        <!-- 배너 -->
        <div class="sec-main-banner">
            <div class="in">
                <p class="main-banner-img">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/main-img2.png" alt="">
                </p>
                <div class="main-banner-tbox">
                    <form method="POST" name="main_submit" id="main_submit" class="" autocomplete="off">
                        <input type="hidden" name="location" id="location" value="하단상담신청(메인페이지)">
                        <input type="hidden" name="trimNo" id="trimNo" value="">
                        <div class="main-banner-title">
                            <h1>원하는 차량이 없으신가요?</h1>
                            <p>상담 가능한 연락처를 남겨주시면 상담원이 직접 원하시는 차량 검색을 도와드립니다.</p>
                            <div class="main-radio-box">
                                <div class="form-wrap">
                                    <input type="radio" name="product" id="product1" class="chk-control" value="R" checked="">
                                    <label for="product1" class="chk-label">장기렌트</label>
                                </div>
                                <div class="form-wrap">
                                    <input type="radio" name="product" id="product2" value="L" class="chk-control">
                                    <label for="product2" class="chk-label">리스</label>
                                </div>
                            </div>
                        </div>
                        <div class="main-input-box">
                            <div class="mib-box">
                                <p>고객명</p>
                                <input type="text" class="input" id="name" name="name" placeholder="법인은 법인명, 개인 및 사업자는 성함을 적어주세요">
                            </div>
                            <div class="mib-box">
                                <p>휴대전화번호</p>
                                <input type="text" class="input" id="phone" name="phone" placeholder="-없이 숫자만 입력해주세요" oninput="autoHyphen(this)" />
                            </div>
                        </div>
                        <div class="main-input-box main-input-box2">
                            <div class="mib-box">
                                <p>차종</p>
                                <input type="text" class="input" id="model" name="model" placeholder="ex) 스포티지 하이브리드">
                            </div>
                        </div>
                        <div class="main-banner-btn">
                            <div class="form-wrap">
                                <input type="checkbox" name="ag" id="ag" class="ag-control chk-control" required checked>
                                <label for="ag" class="ag-label chk-label"><span>(필수)</span> 개인(신용)정보 이용동의</label>
                            </div>
                            <div class="mbb-box">
                                <a href="javascript:apply_submit('main_submit')" class="mbb-quick-btn">빠른상담신청</a>
                                <a href="http://pf.kakao.com/_YRMkd/chat" target="_blank" class="mbb-talk-btn">카톡 상담하기</a>
                                <a href="tel:1588-1549" target="_self" class="mbb-call-btn">상담전화 1899-1549</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include_once(G5_THEME_PATH . '/side.php'); ?>
</section>
<!-- end -->

<script src="<?php echo G5_THEME_URL; ?>/common/js/main.js?ver=<?php echo G5_JS_VER; ?>"></script>