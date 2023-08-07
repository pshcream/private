$(function () {
  $(".logo-box ul").on("click", ".logo-box-btn", function () {
    $(".logo-box-btn").removeClass("on");
    $(this).addClass("on");
  });

  //상품 더보기
  $(".car-item-more").click(function () {
    $(this).addClass("on");
    $(this).prev().addClass("on");
  });

  // 페이지 구분탭 선택
  urlParams = new URL(location.href).searchParams;
  nationP = urlParams.get("nation");
  pageP = urlParams.get("page");
  brandNoP = urlParams.get("brandNo");
  if (nationP == null) {
    nationP = "KR";
  }
  if (pageP == null) {
    pageP = 1;
  }
  if (brandNoP == null) {
    brandNoP = "";
  }
  $(".main-tbox p").html("영업사원 수수료 0%, 직접 내서 더 싸다!");
  getBrand(nationP, brandNoP);
  getModel(nationP, brandNoP, pageP);
  getBest(nationP, brandNoP);
  if (nationP == "FR") {
    $(".main-tbox h1").html("수입차 인기상품");
  } else {
    $(".main-tbox h1").html("국산차 인기상품");
  }

  // 국산차/수입차 버튼 클릭
  $(".logo-box ul").on("click", "#nation-btn", function () {
    var nation = $(this)[0].dataset.nation;
    location.href = "/?nation=" + nation + "&page=1&brandNo=#logo-box";
  });

  // 빠른출고 버튼 클릭
  $(".logo-box ul").on("click", "#preorder-btn", function () {
    var nation = $(this)[0].dataset.nation;
    location.href = "/preorder?nation=" + nation + "&page=1&brandNo=#logo-box";
  });

  // 브랜드버튼 클릭
  $(".logo-box ul").on("click", "#brandNo-btn", function () {
    var nation = $(this)[0].dataset.nation;
    var brandNo = $(this)[0].dataset.brandno;
    location.href =
      "/?nation=" + nation + "&page=1&brandNo=" + brandNo + "#logo-box";
  });

  // 견적받기 클릭
  $(".car-pg").on("click", "button.cib-btn#cib-btn-estimate", function () {
    var brandNm = $(this)[0].dataset.brandnm;
    var modelNm = $(this)[0].dataset.modelnm;
    var trimNo = $(this)[0].dataset.id;

    $("html, body").animate({
      scrollTop: $(`.main-banner-tbox`).offset().top,
    });
    $("#main_submit #model").attr("value", brandNm + " " + modelNm);
    $("#main_submit #trimNo").attr("value", trimNo);
  });

  // 자세히보기 클릭
  $(".car-pg").on("click", ".cib-btn#cib-btn-detail", function () {
    var nation = $(this)[0].dataset.nation;
    var id = $(this)[0].dataset.id;
    var modelno = $(this)[0].dataset.modelno;
    var classifycode = $(this)[0].dataset.classifycode;

    addCount(id, nation, modelno, classifycode);
  });

  // 베스트셀러 클릭
  $(".main-best ul").on("click", ".main-best-link", function () {
    var nation = $(this)[0].dataset.nation;
    var id = $(this)[0].dataset.id;
    var modelno = $(this)[0].dataset.modelno;
    var classifycode = $(this)[0].dataset.classifycode;

    addCount(id, nation, modelno, classifycode);
  });
});

function getBrand(nation, brandNo) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getbrand_list.php?nation=" +
      nation +
      "&brandNo=" +
      brandNo,
    success: function (data) {
      $("#logo-box ul").html(data);
    },
    error: function () {},
  });
}

function getModel(nation, brandNo, page) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getmodel_list.php?brandNo=" +
      brandNo +
      "&page=" +
      page +
      "&nation=" +
      nation,
    success: function (data) {
      $(".car-pg").html(data);
    },
    error: function () {},
  });
}

function getBest(nation, brandNo) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getbest_list.php?nation=" +
      nation +
      "&brandNo=" +
      brandNo,
    success: function (data) {
      $(".main-best ul").html(data);
    },
    error: function () {},
  });
}

function addCount(trimNo, nation, modelNo, classifycode) {
  $.ajax({
    type: "GET",
    url: g5_url + "/api/ajax.shopping_addcount_list.php?modelNo=" + modelNo,
    success: function (data) {
      if (data > 0) {
        location.href =
          "/list?id=" +
          trimNo +
          "&nation=" +
          nation +
          "&modelNo=" +
          modelNo +
          "&classifycode=" +
          classifycode;
      }
    },
    error: function () {},
  });
}
