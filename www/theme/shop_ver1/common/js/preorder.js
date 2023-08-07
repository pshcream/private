$(function () {
  // 페이지 구분
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
  getPreorderBrand(nationP, brandNoP);
  getPreorderModel(nationP, brandNoP, pageP);
  getPreorderBest(nationP, brandNoP);

  // 전체/브랜드버튼 클릭
  $(".logo-box ul").on("click", "#brandNo-btn", function () {
    $(".logo-box-btn").removeClass("on");
    $(this).addClass("on");
    var nation = $(this)[0].dataset.nation;
    var brandNo = $(this)[0].dataset.brandno;
    location.href =
      "/preorder?nation=" + nation + "&page=1&brandNo=" + brandNo + "#logo-box";
  });

  // 국산차/수입차 버튼 클릭
  $(".logo-box ul").on("click", "#nation-btn", function () {
    var nation = $(this)[0].dataset.nation;
    location.href = "/preorder?nation=" + nation + "&page=1&brandNo=#logo-box";
  });

  // 빠른상담 클릭
  $(".car-pg").on("click", ".submit-btn#submit-btn-preorder", function () {
    var brandNm = $(this)[0].dataset.brandnm;
    var modelNm = $(this)[0].dataset.modelnm;
    var modelNo = $(this)[0].dataset.modelno;

    addPreorderCount(brandNm, modelNm, modelNo);
  });

  // 베스트셀러 클릭
  $(".main-best ul").on("click", ".main-best-link", function () {
    var brandNm = $(this)[0].dataset.brandnm;
    var modelNm = $(this)[0].dataset.modelnm;
    var modelNo = $(this)[0].dataset.modelno;

    addPreorderCount(brandNm, modelNm, modelNo);
  });
});

function getPreorderBrand(nation, brandNo) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getbrand_preorder.php?nation=" +
      nation +
      "&brandNo=" +
      brandNo,
    success: function (data) {
      $("#logo-box ul").html(data);
    },
    error: function () {},
  });
}

function getPreorderModel(nation, brandNo, page) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getmodel_preorder.php?brandNo=" +
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

function getPreorderBest(nation, brandNo) {
  $.ajax({
    type: "GET",
    url:
      g5_url +
      "/api/ajax.shopping_getbest_preorder.php?nation=" +
      nation +
      "&brandNo=" +
      brandNo,
    success: function (data) {
      $(".main-best ul").html(data);
    },
    error: function () {},
  });
}

function addPreorderCount(brandNm, modelNm, modelNo) {
  $.ajax({
    type: "GET",
    url: g5_url + "/api/ajax.shopping_addcount_preorder.php?modelNo=" + modelNo,
    success: function (data) {
      if (data > 0) {
        $("html, body").animate({
          scrollTop: $(`.main-banner-tbox`).offset().top,
        });
        $("#main_submit #model").attr("value", brandNm + " " + modelNm);
      }
    },
    error: function () {},
  });
}
