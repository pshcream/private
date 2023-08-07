<?php
include_once('./_common.php');
include_once(G5_THEME_PATH . '/head.php');
?>
<link rel="stylesheet" href="<?php echo G5_THEME_URL; ?>/common/css/list3.css?ver=<?php echo G5_CSS_VER; ?>">

<!-- 국산차 인기상품 -->
<main class="main-import">
    <div class="main-tbox">
        <h1>빠른견적</h1>
        <p>원하는 차량의 최저가! 알차다에서 찾아드립니다.</p>
    </div>
    <div class="main-tbox2">
    </div>
</main>
<!-- end -->

<!-- 계약조건 ~ 배너 -->
<section class="sec-quick">
    <div class="inner">
        <!-- 계약조건 -->
        <div class="quick-cond">
            <div class="quick-cond-left">
                <h3>계약조건 선택</h3>
                <div class="quick-cond-sel">
                    <p class="quick-cond-txt">상품</p>
                    <div class="select-list" id="type-list">
                        <div class="select-item on" data-value="R">
                            <p>렌트</p>
                        </div>
                        <div class="select-item" data-value="L">
                            <p>리스</p>
                        </div>
                    </div>
                </div>
                <div class="quick-cond-sel">
                    <p class="quick-cond-txt">계약기간</p>
                    <div class="select-list" id="period-list">
                        <div class="select-item" data-value="36">
                            <p>36개월</p>
                        </div>
                        <div class="select-item on" data-value="48">
                            <p>48개월</p>
                        </div>
                        <div class="select-item" data-value="60">
                            <p>60개월</p>
                        </div>
                    </div>
                </div>
                <div class="quick-cond-sel">
                    <p class="quick-cond-txt">초기비용</p>
                    <div class="select-list" id="price-list">
                        <div class="select-item" data-value="M1">
                            <p>0%</p>
                        </div>
                        <div class="select-item on" data-value="M2">
                            <p>선납금30%</p>
                        </div>
                        <div class="select-item" data-value="M3">
                            <p>보증금30%</p>
                        </div>
                    </div>
                </div>
                <div class="quick-cond-sel">
                    <div class="price-list">
                    </div>
                </div>
            </div>
            <div class="quick-cond-right">
                <form method="POST" name="list_submit" id="list_submit" class="" autocomplete="off">
                    <input type="hidden" name="location" id="location" value="상세페이지">
                    <input type="hidden" name="model" id="model" value="">
                    <input type="hidden" name="product" id="product" value="">
                    <input type="hidden" name="period" id="period" value="">
                    <input type="hidden" name="prepaid" id="prepaid" value="">
                    <input type="hidden" name="monthly" id="monthly" value="">
                    <input type="hidden" name="totalPrice" id="totalPrice" value="">
                    <h3>대한민국 최저가로 알아보세요!</h3>
                    <div class="scr-input">
                        <div class="scr-input-common scr-input-left">
                            <span>성함</span>
                            <input type="text" placeholder="ex) 홍길동" name="name" id="name">
                        </div>
                        <div class="scr-input-common scr-input-right">
                            <span>연락처</span>
                            <input type="text" placeholder="‘-’없이 숫자만 입력해주세요" name="phone" id="phone" oninput="autoHyphen(this)" />
                        </div>
                    </div>
                    <div class="scr-desc">
                        <div class="scr-desc-left">
                            <span>추가 문의사항</span>
                            <textarea name="memo" id="memo"></textarea>
                        </div>
                        <div class="scr-desc-right">
                            <div class="form-wrap">
                                <input type="radio" name="ag" id="ag" class="chk-control type-b" checked>
                                <label for="ag" class="snb-prv-btn chk-label type-b">개인정보처리방침 <span>[전문보기]</span></label>
                            </div>
                            <a href="javascript:apply_submit('list_submit')" class="scr-desc-fast scr-desc-btn">빠른 상담신청</a>
                            <a class="scr-desc-kakao scr-desc-btn" href="http://pf.kakao.com/_YRMkd/chat" target="_blank">카톡 상담하기</a>
                            <a class="scr-desc-call scr-desc-btn" href="tel:1899-1549" target="_self">상담전화 <strong>1899-1549</strong></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- 비슷한 가격대 차량 -->
    <div class="quick-similar">
        <div class="inner">
            <div class="similar-cont">
                <h2 class="similar-txt">
                    비슷한 가격대 <br>
                    차량
                </h2>
                <div class="similar-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner -->
    <div class="similar-banner">
        <div class="inner">
            <img src="<?php echo G5_THEME_URL; ?>/common/img/fast-banner.jpg" class="similar-banner-pc" alt="">
            <img src="<?php echo G5_THEME_URL; ?>/common/img/mo/fast-banner.jpg" class="similar-banner-mo" alt="">
        </div>
    </div>
</section>
<!-- end -->

<!-- 장기렌트/리스란~ -->
<section class="sec-about">
    <div class="inner">
        <div class="about-title-box">
            <div class="atb-txt">
                <h1 class="fade wow">장기렌트/리스란?</h1>
                <p class="fade wow">
                    장기렌트란 차량을 렌터카 회사에서 임대하여 매달 임대료를 주면서 이용하는 차량 구매 방법입니다. 만기 시 차량을 인수하거나 반납할 수 있습니다.
                </p>
            </div>
            <div class="atb-circle">
                <div class="circle-item fade wow">
                    <strong>렌트회사</strong>
                    <p>
                        계약기간 동안 차량대여
                        보험 및 관리서비스 제공
                    </p>
                </div>
                <div class="circle-item type-a fade wow">
                    <strong>고객</strong>
                    <p>
                        월 이용료 납부 <br>
                        계약기간 종료 후 <br>
                        차량 반납/인수 결정
                    </p>
                </div>
            </div>
        </div>
        <div class="about-text">
            <p class="wow fade">
                심사,<span class="top-dot">계</span><span class="top-dot">약</span><span class="top-dot">절</span><span class="top-dot">차</span>가 까다로울까요?
            </p>
        </div>
        <div class="about-tbox wow fade">
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>01</strong>
                    <p>
                        <span>1:1 맞춤상담</span>
                        문의주신 차종의 전문 상담사가
                        최적의 견적서 제시
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon1.svg" alt="">
                    </i>
                </div>
            </div>
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>02</strong>
                    <p>
                        <span>서류심사</span>
                        장기렌트와 리스진행 <br>
                        승인을 위한 서류심사
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon2.svg" alt="">
                    </i>
                </div>
            </div>
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>03</strong>
                    <p>
                        <span>계약체결</span>
                        렌트 담당자가 직접방문 <br>
                        및 전자약정 진행
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon3.svg" alt="">
                    </i>
                </div>
            </div>
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>04</strong>
                    <p>
                        <span>차량 출고 및 인도</span>
                        출고 관련 업무 진행 <br>
                        고객님께 안전하게 배송
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon4.svg" alt="">
                    </i>
                </div>
            </div>
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>05</strong>
                    <p>
                        <span>꾸준한 사후관리</span>
                        인도 후 끝이 아닌 차량<br>
                        정비 등 확실한 사후 서비스
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon5.svg" alt="">
                    </i>
                </div>
            </div>
            <div class="about-tbox-item">
                <div class="ati-left">
                    <strong>06</strong>
                    <p>
                        <span>렌트/리스 기간 종료</span>
                        계약기간 종료 후<br>
                        재계약 or 반납 or 인수 선택
                    </p>
                </div>
                <div class="ati-right">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/about-icon6.svg" alt="">
                    </i>
                </div>
            </div>
        </div>
    </div>
    <div class="about-slide-cont">
        <p>
            장기렌트/리스 이런분께 <span class="top-dot">추</span><span class="top-dot">천</span> 드립니다.
        </p>
        <div class="about-slide-box swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide about-slide">
                    <span>01</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon1.svg" alt="">
                    </i>
                    <p>
                        <strong>초기비용 최소화</strong>
                        20대 직장인으로써
                        높은 보험료와 초기비용이 부담되시는 분
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>02</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon2.svg" alt="">
                    </i>
                    <p>
                        <strong>보험료 할증 NO!</strong>
                        인생 첫차이거나
                        사고율이 높아 보험료
                        부담이 되시는 분
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>03</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon3.svg" alt="">
                    </i>
                    <p>
                        <strong>신용등급 변동 NO!</strong>
                        저신용 및 신용등급이
                        걱정되시는 분

                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>04</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon4.svg" alt="">
                    </i>
                    <p>
                        <strong>자산인정 NO!</strong>
                        재산세 및 건보료
                        오르는게
                        걱정되시는 분
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>05</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon5.svg" alt="">
                    </i>
                    <p>
                        <strong>비용절약 OK!</strong>
                        영업용이나
                        장거리운행을
                        많이 하시는 분
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>06</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon6.svg" alt="">
                    </i>
                    <p>
                        <strong>세금감면 OK!</strong>
                        사업자 또는 법인으로써 세금혜택을
                        받고자 하시는 분
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>07</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon7.svg" alt="">
                    </i>
                    <p>
                        <strong>즉시출고 OK!</strong>
                        계약 후
                        7일이내 출고
                    </p>
                </div>
                <div class="swiper-slide about-slide">
                    <span>08</span>
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/slide-icon8.svg" alt="">
                    </i>
                    <p>
                        <strong>중고차 처리 부담NO!</strong>
                        차를 2~3년 이내로
                        자주 바꾸시는 분
                    </p>
                </div>
            </div>
            <div class="about-slide-scrollbar swiper-scrollbar"></div>
        </div>
    </div>
</section>
<!-- end -->

<!-- 왜? ~ 제휴 파트너사 -->
<section class="sec-why">
    <div class="inner">
        <h1 class="why-title wow fade">왜? 알차다일까?</h1>
        <ul class="why-icon-box wow fade">
            <li>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-icon1.svg" alt="">
                </i>
                <strong>전화상담 NO!</strong>
                <p>
                    클릭한번으로 <br>
                    견적비교 가능합니다.
                </p>
            </li>
            <li>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-icon2.svg" alt="">
                </i>
                <strong>실시간 비교견적</strong>
                <p>
                    20개 금융사 견적을 <br>
                    한번에 보여드립니다.
                </p>
            </li>
            <li>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-icon3.svg" alt="">
                </i>
                <strong>신차빠른출고</strong>
                <p>
                    7일 이내 빠른출고 <br>
                    해드립니다.
                </p>
            </li>
            <li>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-icon4.svg" alt="">
                </i>
                <strong>출고 후 관리</strong>
                <p>
                    3년간 3회 순회정비 서비스를 <br>
                    제공해 드립니다.
                </p>
            </li>
        </ul>
    </div>
    <div class="why-cont">
        <div class="why-bnr wow fade">
            <div class="why-bnr-inner">
                <div class="why-bnr-txt">
                    <strong>
                        영업사원 <br>
                        전화상담 없이!
                    </strong>
                    <p>
                        부담스러운 전화상담없이 내가 원하는 시간에, <br>
                        원하는 조건으로 클릭 한번으로 비교 가능합니다.
                    </p>
                </div>
                <div class="why-bnr-img type-a">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-img1.png" alt="">
                </div>
            </div>
        </div>
        <div class="why-bnr type-a wow fade">
            <div class="why-bnr-inner">
                <div class="why-bnr-img">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-img2.png" class="why-img-pc" alt="">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/mo/quick/why-img2.jpg" class="why-img-mo" alt="">
                </div>
                <div class="why-bnr-txt">
                    <strong>
                        클릭 하나로 <br>
                        실시간 비교 견적!
                    </strong>
                    <p>
                        20개 금융사 제휴를 통해 <br>
                        실시간으로 최저가를 찾아드립니다.
                    </p>
                </div>
            </div>
        </div>
        <div class="why-bnr wow fade">
            <div class="why-bnr-inner">
                <div class="why-bnr-txt">
                    <strong>
                        전기차? 하이브리드? <br>
                        차종 상관없이 빠른 출고!
                    </strong>
                    <p>
                        알차다만의 프로모션을 통해 출고기간이 <br>
                        6개월 이상인 차량도 7일 이내 받아 볼 수 있습니다.
                    </p>
                </div>
                <div class="why-bnr-img type-b">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-img3.png" alt="">
                </div>
            </div>
        </div>
        <div class="why-bnr type-b wow fade">
            <div class="why-bnr-inner">
                <div class="why-bnr-img">
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/why-img4.jpg" alt="">
                </div>
                <div class="why-bnr-txt">
                    <strong>
                        계약하면 끝!? 출고 후 <br>
                        꼼꼼한 사후관리까지!
                    </strong>
                    <p>
                        3년간 3회 순회정비 서비스를 제공해 드립니다.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="partners-cont wow fade">
        <div class="inner">
            <h1>제휴 파트너사</h1>
            <p class="txt">
                알차다와 제휴를 맺은 렌터카 브랜드에서 가격비교를 진행해 고객님이 원하는 차량을 <br>
                가장 저렴하고 합리적인 가격으로 알아보실 수 있습니다.
            </p>
            <p class="img-box">
                <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/partners-img.jpg" class="partners-img-pc" alt="">
                <img src="<?php echo G5_THEME_URL; ?>/common/img/mo/quick/partners-img.jpg" class="partners-img-mo" alt="">
            </p>
        </div>
    </div>
</section>
<!-- end -->

<!-- 특별한 혜택 -->
<div class="sec-spec">
    <div class="inner">
        <h1 class="why-title wow fade">알차다만의 특별한 혜택</h1>
        <div class="spec-item-box">
            <div class="spec-item">
                <strong>운전자 보험</strong>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/special-icon1.svg" alt="">
                </i>
                <p>
                    출고일부터 3년간 삼성화재 <br>
                    운전자 보험 기본 무료제공
                </p>
            </div>
            <div class="spec-item">
                <strong>썬팅 기본장착</strong>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/special-icon2.svg" alt="">
                </i>
                <p>
                    전면 / 측후면 장착
                </p>
            </div>
            <div class="spec-item">
                <strong>블랙박스 기본장착</strong>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/special-icon3.svg" alt="">
                </i>
                <p>
                    2채널 블랙박스
                </p>
            </div>
            <div class="spec-item">
                <strong>순회정비 (공통)</strong>
                <i>
                    <img src="<?php echo G5_THEME_URL; ?>/common/img/quick/special-icon4.svg" alt="">
                </i>
                <p>
                    3년간 3회 엔진오일, 에어컨 <br>
                    필터 교체(일부차종 제외)
                </p>
            </div>
        </div>
    </div>
</div>
<!-- end -->

<div class="apply-bg">
    <div class="apply-box">
        <div class="apply-inner">
            <div class="apply-close"></div>
            <div class="apply-title">
                <p>빠른상담 문의</p>
            </div>
            <div class="apply-cont">
                <form method="POST" name="similar_submit" id="similar_submit" class="" autocomplete="off">
                    <input type="hidden" id="location" name="location" value="비슷한가격대차량">
                    <input type="hidden" id="model" name="model" value="">
                    <div class="similar-input">
                        <p>성함/업체명*</p>
                        <input type="text" id="name" name="name">
                    </div>
                    <div class="similar-input">
                        <p>연락처*</p>
                        <input type="text" id="phone" name="phone" placeholder="ex) 01012345678" oninput="autoHyphen(this)" />
                    </div>
                    <div class="similar-memo">
                        <textarea name="memo" id="memo" cols="30" rows="10" placeholder="문의내용"></textarea>
                    </div>
                    <div class="similar-chk">
                        <input class="similar-ag-input" type="radio" id="ag" name="ag" checked>
                        <label class="similar-ag-label" for="ag">(필수)<span>개인정보처리방침</span>[전문보기]</label>
                    </div>
                    <a href="javascript:apply_submit('similar_submit')" class="similar-btn">
                        <p>상담 신청하기</p>
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo G5_THEME_URL; ?>/common/js/list3.js?ver=<?php echo G5_JS_VER; ?>"></script>
<?php include_once(G5_THEME_PATH . '/tail.php'); ?>