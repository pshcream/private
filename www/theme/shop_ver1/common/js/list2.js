$(function () {
    // 계약조건 선택
    var slParnets = $(".slt-box");
    var slTarget = $(".slt-box .title");
    var slTargetList = $(".slt-box .cont li");
    slTarget.on("click", function () {
      //alert('asfsd');
      $(this).toggleClass("on");
      $(this).siblings(".sort-box").removeClass("on");
      $(this)
        .parents(slParnets)
        .siblings(slParnets)
        .find(".title")
        .removeClass("on");
    });
    slTargetList.on("click", function () {
      $(this).siblings().removeClass("on");
      $(this).addClass("on");
    });
    $(slTargetList.children("a")).on("click", function () {
      $(this).parents(".cont").siblings("h2.first").addClass("on");
    });
  
    //목록 이외 클릭시 목록 닫힘
    $(document).on("mouseup", function (f) {
      if (slParnets.has(f.target).length === 0) {
        slTarget.parent(".title").removeClass("on");
      }
    });
  
    //range
    $(".range-item").on("input", function () {
      var val = $(this).val();
      $(this).css(
        "background",
        "linear-gradient(to right, #FDCB2A 0%, #FDCB2A " +
          val +
          "%, #f3f4f8 " +
          val +
          "%, #f3f4f8 100%)"
      );
    });
  
    //개인정보처리방침
    $(".snb-prv-btn").click(function () {
      $("body").addClass("on");
      $(".privacy-popup").show();
    });
  
    /* Wow Scroll Reveal Animation */
    wow = new WOW({
      boxClass: "wow",
      offset: 150,
      mobile: true,
    });
    wow.init();
  
    // 추천 slide
    var swiper = new Swiper(".about-slide-box", {
      slidesPerView: "auto",
      centeredSlides: true,
      autoplay: {
        delay: 3000,
      },
      spaceBetween: 20,
      breakpoints: {
        768: {
          //브라우저가 768보다 클 때
          spaceBetween: 0,
          scrollbar: {
            el: ".about-slide-box .swiper-scrollbar",
          },
        },
      },
    });
  
    // 비슷한 가격대 차량
    similarSlider = new Swiper(".similar-slider .swiper-container", {
      slidesPerView: "auto",
      simulateTouch: true,
      spaceBetween: 20,
    });
  
    // 차량데이터 가져오기
    urlParams = new URL(location.href).searchParams;
    const $id = urlParams.get("id");
    const $nation = urlParams.get("nation");
    const $modelNo = urlParams.get("modelNo");
    const $classifycode = urlParams.get("classifycode");
    getTrim($id);
    getPrice($id);
    getSimilar($nation, $classifycode, $modelNo);
  
    // 계약조건 선택
    $(".quick-cond-sel").on("click", ".select-list .select-item", function () {
      $(this).siblings().removeClass("on");
      $(this).addClass("on");
      getPrice($id);
    });
  
    // 비슷한가격대차량 상담신청 팝업 닫기
    $(".apply-bg .apply-box").on("click", ".apply-close", function (event) {
      event.stopPropagation();
      hide_apply();
    });
    $(".apply-bg .apply-box").on("click", function (event) {
      event.stopPropagation();
    });
    $(".apply-bg").on("click", function () {
      hide_apply();
    });
  
    // 비슷한가격대차량 상담신청 팝업 개인정보처리방침
    $(".similar-chk").click(function () {
      $("body").addClass("on");
      $(".privacy-popup").show();
    });
  });
  
  function getTrim(id) {
    $.ajax({
      type: "GET",
      url: g5_url + "/api/ajax.shopping_gettrim_list.php?id=" + id,
      success: function (data) {
        $(".main-tbox2").html(data);
        var model = $(".main-tbox2 .main-desc h2")[0].innerHTML;
        $("#list_submit #model").attr("value", model);
      },
      error: function () {},
    });
  }
  
  function getPrice(id) {
    var product = $(".select-list#type-list .select-item.on")[0].dataset.value;
    var period = $(".select-list#period-list .select-item.on")[0].dataset.value;
    var prepaid = $(".select-list#price-list .select-item.on")[0].dataset.value;
    var prodType = product + period + prepaid;
    $.ajax({
      type: "GET",
      url:
        g5_url +
        "/api/ajax.shopping_getprice_list.php?id=" +
        id +
        "&prodType=" +
        prodType +
        "&period=" +
        period,
      success: function (data) {
        $(".quick-cond-sel .price-list").html(data);
        var monthly = $("#item-monthly")[0].innerHTML;
        var totalPrice = $("#item-total")[0].innerHTML;
        $("#list_submit #monthly").attr("value", monthly);
        $("#list_submit #totalPrice").attr("value", totalPrice);
        $("#list_submit #product").attr("value", product);
        $("#list_submit #period").attr("value", period);
        $("#list_submit #prepaid").attr("value", prepaid);
      },
      error: function () {},
    });
  }
  
  function getSimilar(nation, classifycode, modelNo) {
    $.ajax({
      type: "GET",
      url:
        g5_url +
        "/api/ajax.shopping_getsimilar_list.php?nation=" +
        nation +
        "&classifycode=" +
        classifycode +
        "&modelNo=" +
        modelNo,
      success: function (data) {
        $(".similar-slider .swiper-container .swiper-wrapper").html(data);
      },
      error: function () {},
    });
  }
  
  // 비슷한가격대차량 상담신청 팝업 띄우기
  function similar_apply(model) {
    $(".apply-bg").show();
    $("body").css("overflow", "hidden");
    $("#similar_submit #model").attr("value", model);
  }
  
  // 비슷한가격대차량 상담신청 팝업 닫기
  function hide_apply() {
    $(".apply-bg").hide();
    $("body").css("overflow", "");
    $("#similar_submit #model").attr("value", "");
  }
  