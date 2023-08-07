<!-- 사이드바 -->
<div class="snb">
    <!-- 빠른상담 -->
    <div class="snb-consult">
        <div class="snb-title">
            <p>
                빠른상담 문의
            </p>
        </div>
        <div class="snb-cont">
            <form method="POST" name="snb_submit" id="snb_submit" class="" autocomplete="off">
                <input type="hidden" name="location" id="location" value="사이드바(빠른상담문의)">
                <div class="snb-input-box">
                    <div class="snb-cont-input">
                        <p>성함/업체명<span>*</span></p>
                        <input type="text" name="name" id="name">
                    </div>
                    <div class="snb-cont-input">
                        <p>연락처<span>*</span></p>
                        <input type="text" placeholder="ex) 01012345678" name="phone" id="phone" maxlength="13" oninput="autoHyphen(this)" />
                    </div>
                    <div class="snb-cont-input">
                        <p>차종</p>
                        <input type="text" placeholder="ex) 스포티지 하이브리드" name="model" id="model">
                    </div>
                    <div class="sub-extra-input">
                        <textarea cols="30" rows="10" placeholder="문의내용" name="memo" id="memo"></textarea>
                    </div>
                </div>
                <div class="snb-prv">
                    <div class="form-wrap">
                        <input type="radio" id="ag" name="ag" class="chk-control type-b" checked>
                        <label for="ag" class="snb-prv-btn chk-label type-b">(필수)<span>개인정보처리방침</span>[전문보기]</label>
                    </div>
                </div>
                <a href="javascript:apply_submit('snb_submit')" class="snb-consult-btn">상담 신청하기</a>
            </form>
        </div>
    </div>
    <!-- 대표번호 ~ 바로가기 -->
    <div class="snb-call-box">
        <ul>
            <li>
                <a href="tel:1588-1549" target="_self">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/icon-call.svg" alt="">
                    </i>
                    <p>
                        대표번호
                        <strong>1899-1549</strong>
                    </p>
                </a>
            </li>
            <li>
                <a href="http://pf.kakao.com/_YRMkd/chat" target="_blank">
                    <i>
                        <img src="<?php echo G5_THEME_URL; ?>/common/img/icon-talk.svg" alt="">
                    </i>
                    <p>
                        바로가기
                        <strong>카카오톡 상담</strong>
                    </p>
                </a>
            </li>
        </ul>
    </div>
    <!-- top -->
    <a href="#" class="btn-top">Top</a>
</div>
<!-- end -->