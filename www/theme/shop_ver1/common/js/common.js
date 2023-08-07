$(function () {
  // mo

  // header
  $(".header-btn").click(function () {
    $(".header-search-box").slideToggle();
  });
  $(".hsb-btn").click(function () {
    $(".header-search-box").slideUp();
  });

  //로고 더보기
  $(".logo-box-more").click(function () {
    $(this).addClass("on");
    $(this).parents(".logo-box > ul").addClass("on");
  });

  // 차량구분탭 선택
  var urlParams = new URL(location.href).searchParams;
  var nationP = urlParams.get("nation");
  if (location.pathname == "/preorder") {
    $(".main-menu a[data-nation='FO']").addClass("on");
  } else if (nationP) {
    $(".main-menu a[data-nation='" + nationP + "']").addClass("on");
  } else {
    $(".main-menu a[data-nation='KR']").addClass("on");
  }

  // 사이드바 개인정보처리방침
  $(".snb-prv-btn").click(function () {
    $("body").addClass("on");
    $(".privacy-popup").show();
  });

  // 모델 검색버튼 클릭
  $(".header-input").on("keyup", function (event) {
    event.stopPropagation();
    var modelNm = $("input#search-modelnm")[0].value;
    searchModelnm(modelNm, nationP);
  });

  // 검색창 클릭시 검색창 닫기방지
  $(".header-input").on("click", function (event) {
    event.stopPropagation();
  });

  // 다른곳 클릭시 검색창 닫기
  $(".container").on("click", function () {
    $(".search-list").hide();
    $(".search-list").html("");
    $("#search-modelnm").attr("value", "");
  });

  // alert 확인 or 배경 클릭시 alert 닫기
  $(".alert-bg .alert-box").on("click", ".alert-btn", function (event) {
    event.stopPropagation();
    hideAlert();
  });
  $(".alert-bg .alert-box").on("click", function (event) {
    event.stopPropagation();
  });
  $(".alert-bg").on("click", function () {
    hideAlert();
  });
});

function searchModelnm(modelNm) {
  $.ajax({
    type: "GET",
    url: g5_url + "/api/ajax.shopping_searchmodel_list.php?modelNm=" + modelNm,
    success: function (data) {
      if (data != 0) {
        $(".search-list").show();
        $(".search-list").html(data);
      } else {
        $(".search-list").hide();
        $(".search-list").html("");
      }
    },
    error: function () {},
  });
}

function apply_submit($id) {
  if ($("#" + $id + " #name")[0].value == "") {
    showAlert("고객명을 입력해주세요");
    $("#" + $id + " #name").focus();
    return false;
  }

  if ($("#" + $id + " #phone")[0].value == "") {
    showAlert("연락처를 입력해주세요");
    $("#" + $id + " #phone").focus();
    return false;
  }

  if ($("#" + $id + " #model")[0].value == "") {
    showAlert("차종을 입력해주세요");
    $("#" + $id + " #model").focus();
    return false;
  }

  if ($("#" + $id + " #ag")[0].checked == false) {
    showAlert("개인(신용)정보 이용동의는 필수입니다.");
    $("#" + $id + " #ag").focus();
    return false;
  }

  $.ajax({
    url: g5_url + "/api/ajax.shopping_application_save.php",
    data: $("#" + $id).serialize(),
    type: "POST",
    async: false,
    cache: false,
    success: function (data) {
      if (data > 0) {
        showAlert("상담신청이 완료되었습니다");
        $("#" + $id + " #name").attr("value", "");
        $("#" + $id + " #phone").attr("value", "");
        $("#" + $id + " #ag").attr("checked", true);
        // 1.사이드바
        if ($id == "snb_submit") {
          $("#" + $id + " #model").attr("value", "");
          $("#" + $id + " #memo").attr("value", "");
        }
        // 2.하단상담신청
        if ($id == "main_submit") {
          $("#" + $id + " #model").attr("value", "");
          $("#" + $id + " #product1").attr("checked", true);
          $("#" + $id + " #product2").attr("checked", false);
        }
        // 3.상세페이지 상담신청
        if ($id == "list_submit") {
          $("#" + $id + " #memo").attr("value", "");
        }
        // 4.비슷한가격대차량 상담신청
        if ($id == "similar_submit") {
          $("#" + $id + " #model").attr("value", "");
          $("#" + $id + " #memo").attr("value", "");
          $(".apply-bg").hide();
        }
        //NAVER SCRIPT
        if (typeof wcs != "undefined") {
          var _nasa = {};
          _nasa["cnv"] = wcs.cnv("4", "0");
          wcs_do(_nasa);
        }
      }
    },
    error: function () {},
  });
}

// 전화번호 입력시 자동하이픈
function autoHyphen(target) {
  target.value = target.value
    .replace(/[^0-9]/g, "")
    .replace(/^(\d{0,3})(\d{0,4})(\d{0,4})$/g, "$1-$2-$3")
    .replace(/(\-{1,2})$/g, "");
}

// alert 팝업 띄우기
function showAlert(title) {
  $(".alert-bg").show();
  $("body").css("overflow", "hidden");
  $(".alert-title").html(title);
}

// alert 팝업 닫기
function hideAlert() {
  $(".alert-bg").hide();
  $("body").css("overflow", "");
  $(".alert-title").html("");
}
