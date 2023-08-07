/*
//  inputbox 포커스 설정
$(document).ready(function(){
	$(".login input").focus(function(){
	$(this).css("background-color","#ccc");
	});
	$("table input").blur(function(){
	$(this).css("background-color","#fff");
	});
});
*/

/*


############### 기본 여기저기 사용될 함수 모음들.. ##################
작성자 : 장경훈
수정자 : 김준홍
수정일 : 꾸준히 쭉~
#####################################################################


// 숫자만 입력받는 놈 만들기
<input type="text" name="PR_CAPITAL" value="0" size="6" maxlength="6" style="IME-MODE:disabled;text-align:right;" onkeypress="javascript:OnlyNumber();" onFocus="javascript:FormatCharText(this)" onBlur="javascript:FormatNumberText(this)">

// 달력 컨트롤 넣기
<script language="javascript">DateControl(document.form1, "ODATE", 0, false);</script>


*/
//################################### 정규식 정의

var regNum =/^[0-9]+$/; 
var regNumMinus = /^[0-9_-]+$/; 
var regAlpha =/^[a-zA-Z]+$/;
var regHangul =/[가-힣]/; 
var regHangulEng =/[가-힣a-zA-Z]/; 
var regHangulOnly =/^[가-힣]*$/; 
var regNumEng = /[0-9a-zA-Z]+$/; 

var regMail =/^[_a-zA-Z0-9-]+@[._a-zA-Z0-9-]+.[a-zA-Z]+$/;
var regPhone =/^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/;
var regDomain =/^[.a-zA-Z0-9-]+.[a-zA-Z]+$/; 
var regHost =/^[a-zA-Z-]+$/; 

var regID = /^[a-zA-Z]{1}[a-zA-Z0-9_-]{5,15}$/;
var regPWD = /[0-9a-zA-Z]{5,10}$/; 

//###################################  익스플로러 버전체크
var isie6=(navigator.userAgent.toLowerCase().indexOf('msie 6')!=-1)? true : false;

//###################################  Checkbox 전체선택
function CheckAll(This, Frm, Name) {
	for(var i=0;i<Frm.elements.length;i++) {
		if(Frm.elements[i].name == Name) {
			Frm.elements[i].checked = This.checked;
		}
	}
}
//###################################  Validation Check
function ValLen(obj, len, txt) {
	if(obj.value.length != len) {
		alert(txt + len + " 자리로 입력하셔야 합니다");
		obj.focus();
		return false;
	}
	else {
		return true;
	}
}
function ValNull(obj, txt) {
	if(obj.value.length == 0) {
		if(txt != "") {
			alert(txt + " 입력하세요");
			obj.focus();
		}
		return false;
	}
	else {
		return true;
	}
}
function ValHangulOnly(obj, txt, required) {
	if(required == true) {
		if(regHangulOnly.test(obj.value) == false) {
			//alert(txt + " 입력하세요\n\n한글만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(obj.value.length != 0 && regNum.test(obj.value) == false) {
			//alert(txt + " 에는 한글만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}
function ValEng(obj, txt, required) {
	if(required == true) {
		if(regAlpha.test(obj.value) == false) {
			alert(txt + " 입력하세요\n\n알파벳만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(obj.value.length != 0 && regNum.test(obj.value) == false) {
			alert(txt + " 에는 알파벳만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}
function ValNumber(obj, txt, required) {
	if(required == true) {
		if(regNum.test(obj.value) == false) {
			alert(txt + " 입력하세요\n\n숫자만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(obj.value.length != 0 && regNum.test(obj.value) == false) {
			alert(txt + " 에는 숫자만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}
function ValNumber_V2(obj, txt, required) {
	if(required == true) {
		if(regNum.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(obj.value.length != 0 && regNum.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}

// 입력된 값이 숫자인지 확인(문자 포함 x)
function ValNumber_V3(val, txt, required) {
	if(required == true) {
		if(regNum.test(val) == false) {
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(val.length != 0 && regNum.test(val) == false) {
			return false;
		}
		else {
			return true;
		}
	}
}

function ValNumLen(obj, txt, len) {
	if (ValNumber(obj, txt, true) == true) {
		if(obj.value.length != len) {
			alert(txt + " " +len + " 자리로 입력하세요");
			obj.value = obj.value.substring(0, len);
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
	else {
		return false;
	}
}

function ValNumMin(obj, txt, min) {
	if(ValNumber(obj, txt, true) == true) {
		if(Number(obj.value) <= min) {
			alert(txt + " 에는 " + min + " 보다 큰 숫자만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
	else {
		return false;
	}
}

function ValNumMax(obj, txt, max) {
	if(ValNumber(obj, txt, true) == true) {
		if(Number(obj.value) >= max) {
			alert(txt + " 에는 " + max + " 보다 작은 숫자만 입력이 가능합니다");
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
	else {
		return false;
	}
}

function ValByte(obj, len, txt) {
	if(obj.value.bytes() > len) {
		alert(txt + "의 길이가 너무 깁니다\n\n한글 " + len/2 + "자 / 영문 " + len + "자 이하로 입력하세요");
		// 정해진 바이트 길이만큼만 뽑아낸다..
		var tempStr = "";
		for (i=0;i<obj.value.length ;i++ ) {
			if (obj.value.substring(0, i).bytes() < len) {
				tempStr = obj.value.substring(0, i);
			}
			else {
				break;
			}
		}
		obj.value = tempStr;
		obj.focus();
		return false;
	}
	else {
		return true;
	}
}
// ValID(ID, 최소길이, 최대길이)
function ValID_V2(obj, min, max) {		
	if(obj.value.length != 0 && obj.value.length >= min && obj.value.length <= max) {
		if(regID.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
	else {
		obj.focus();
		obj.select();
		return false;
	}
}
function ValDomain_V2(obj, txt, required) {
	if(required == true) {
		if(regDomain.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}

function ValNumEng_V2(obj, txt, required) {
	if(required == true) {
		if(regNumEng.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}
function ValEmail_V2(obj, txt, required) {
	if(required == true) {
		if(regMail.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(obj.value.length != 0 && regMail.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}

function ValEmail_V3(val, txt, required) {
	if(required == true) {
		if(regMail.test(val) == false) {
			return false;
		}
		else {
			return true;
		}
	}

	else {
		if(val.length != 0 && regMail.test(val) == false) {
			return false;
		}
		else {
			return true;
		}
	}
}

function ValPhone_V2(obj, txt, required) {
	if(required == true) {
		if(regPhone.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}

function ValPhone_V3(val, txt, required) {
	if(required == true) {
		if(regPhone.test(val) == false) {
			return false;
		}
		else {
			return true;
		}
	}
}

function ValNumMinus_V2(obj, txt, required) {
	if(required == true) {
		if(regNumMinus.test(obj.value) == false) {
			obj.focus();
			obj.select();
			return false;
		}
		else {
			return true;
		}
	}
}
//################################### 이런 저런 체크들


//입력값의 바이트 길이를 리턴
function getByteLength(input) {
    var byteLength = 0;
    for (var inx = 0; inx < input.value.length; inx++) {
        var oneChar = escape(input.value.charAt(inx));
        if ( oneChar.length == 1 ) {
            byteLength ++;
        } else if (oneChar.indexOf("%u") != -1) {
            byteLength += 2;
        } else if (oneChar.indexOf("%") != -1) {
            byteLength += oneChar.length/3;
        }
    }
    return byteLength;
}

//입력값에 특정 문자(chars)가 있는지 체크
//ex) if (containsChars(form.name,"!,*&^%$#@~;")) {


function containsChars(input,chars) {
    for (var inx = 0; inx < input.value.length; inx++) {
       if (chars.indexOf(input.value.charAt(inx)) != -1)
           return true;
    }
    return false;
}


//입력값이 특정 문자(chars)만으로 되어있는지 체크
//특정 문자만 허용하려 할 때 사용
//ex) if (!containsCharsOnly(form.blood,"ABO")) {
function containsCharsOnly(input,chars) {
    for (var inx = 0; inx < input.value.length; inx++) {
       if (chars.indexOf(input.value.charAt(inx)) == -1)
           return false;
    }
    return true;
}

//선택된 라디오버튼이 있는지 체크
//선택된 체크박스가 있는지 체크

function hasChecked(input) {
    if (input.length > 1) {
        for (var inx = 0; inx < input.length; inx++) {
            if (input[inx].checked) return true;
        }
    } else {
        if (input.checked) return true;
    }
    return false;
}

//################################### 컨트롤 출력

/* ---------------------------------------------------------------------
   설명   : 날짜입력 (달력) 컨트롤
   사용법 : <script>DateControl(document.form1, "ODATE", 0, false);</script>
  --------------------------------------------------------------------- */
function DateControl(objForm, control_name, intDate, readonly) {	//(폼개체, 생성될 날짜텍스트 이름, 현재일에서의 +-, 수정가능 여부)
	var objDate = new Date();
	objDate.setDate(objDate.getDate()+(intDate));
	var strDate = objDate.getYear().toString() + ToLength((objDate.getMonth()+1).toString(),"0",2) + ToLength((objDate.getDate()).toString(),"0",2);
	document.write("<input type='text' name='"+control_name+"' size='8' maxlength='8' value='' ");
	if(readonly == true) {
		document.write(" readonly style='background:#E3E3E3'");
	}
	else {
		document.write(" class='calendar' ");
	}
	document.write(" onBlur='chkDate(this);'>");
	DateWrite(objForm, control_name, strDate);
}
function DateWrite(objForm, control_name, strDate) {	//(폼개체, 출력할 컨트롤의 이름, 출력할 날짜)
	if(strDate == "") {
		var objDate = new Date();
		var strDate = objDate.getYear().toString() + ToLength((objDate.getMonth()+1).toString(),"0",2) + ToLength((objDate.getDate()).toString(),"0",2);
	}
	document.write("<Script language='JavaScript'>"+objForm.name+"." + control_name + ".value='" + strDate + "';</Script>");
}
function chkDate(obj) {		// 날짜형태 확인 (yyyymmdd 형태)
	var input = obj.value.replace(/-/g,"");
	var inputYear = input.substr(0,4);
	var inputMonth = input.substr(4,2) - 1;
	var inputDate = input.substr(6,2);
	var resultDate = new Date(inputYear, inputMonth, inputDate);
	if ((resultDate.getFullYear() != inputYear || resultDate.getMonth() != inputMonth || resultDate.getDate() != inputDate) || input.length != 8) {
		alert("날짜 형식이 일치하지 않습니다");
		obj.focus();
		obj.select();
	}
}


//################################### 이미지 확장자 확인
function ValEmgExtCheck(This) {
	if((/(.jpg|.jpeg|.gif)$/i).test(This.value) == true) {
		return true;
	}
	else {
		return false;
	}

}

//################################### 쿠키 관련 함수
function getCookie(name) {
	var nameOfCookie = name + "=";
	var x = 0;

	while ( x <= document.cookie.length )
	{
		var y = (x+nameOfCookie.length);
		if ( document.cookie.substring( x, y ) == nameOfCookie ) {
			if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
				endOfCookie = document.cookie.length;
				return unescape( document.cookie.substring( y, endOfCookie ) );
		}
		x = document.cookie.indexOf( " ", x ) + 1;
		if ( x == 0 )
			break;
	}
	return "";
}


function setCookie( name, value, expiredays ) {
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );

	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}


//################################### 일반 함수
function OnlyEng() { // style="IME-MODE:disabled;" onKeyPress="OnlyEng();"
	if(! ((event.keyCode >= 97 && event.keyCode <= 122) || (event.keyCode >= 65 && event.keyCode <= 90))) {
		event.returnValue = false;
	}
}
function OnlyNumber() {	// style="IME-MODE:disabled;text-align:right;" onkeypress="onlyNumber();"
	if (! ((event.keyCode >= 48 && event.keyCode <= 57))) {
			event.returnValue = false;
	}
}
function ChangeLength(strValue, strChar, intTo) {
	var tempValue = strValue;
	if(strValue.length < intTo) {
		for (var i=strValue.length; i<intTo; i++) {
			tempValue = strChar + tempValue;
		}
	}
	return tempValue;
}


//################################### 숫자/문자 전환 함수
/*//////////////////////////////
//	숫자전환
*///////////////////////////////

function FormatNumber(numstr) {
  var numstr = String(numstr);
  var re0 = /(\d+)(\d{3})($|\..*)/;
  if (re0.test(numstr))
	return numstr.replace(
	  re0,
	  function(str,p1,p2,p3) { return FormatNumber(p1) + "," + p2 + p3; }
	);
  else
	return numstr;
}
/*//////////////////////////////
//	문자전환
*///////////////////////////////
function FormatChar(numstr) {
	var rValue = numstr;
	rValue = rValue.replaceStr(",","");
	rValue = rValue.replaceStr(" ","");
	return rValue;

}
/*///////////////////////////////
// 객체의 값을 변환하고 포커스
*////////////////////////////////
function FormatNumberText(objText) {		// onBlur="javascript:FormatNumberText(this)"
	objText.value = FormatNumber(objText.value);
}
function FormatCharText(objText) {		// onFocus="javascript:FormatCharText(this)"
	objText.value = FormatChar(objText.value);
	objText.select();
}

//################################### IE 패치에 따른 Flash 불러오기
function swfCall(url, width, height){

	document.write("<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' ");
	document.write("		codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' ");
	document.write("		width='"+width+"' height='"+height+"' align='middle'>");
	document.write("	<param name='allowScriptAccess' value='always' /> ");
	document.write("	<param name='movie'				value='"+url+"' /> ");
	document.write("	<param name='quality'			value='high' /> ");
	document.write("	<param name='wmode'				value='transparent'> ");
	document.write("	<embed src='"+url+"' quality='high' width='"+width+"' height='"+height+"' align='middle' ");
	document.write("		allowScriptAccess='sameDomain' type='application/x-shockwave-flash' ");
	document.write("		pluginspage='http://www.macromedia.com/go/getflashplayer' />");
	document.write("</object>");
}


//################################### 프로토타입 정의
Number.prototype.read = function() {
	if (isNaN(this) == true) return '';
	if (this == 0) return '영';
	var phonemic = ['','일','이','삼','사','오','육','칠','팔','구'];
	var unit = ['','','십','백','천','만','십만','백만','천만','억','십억','백억','천억','조','십조','백조'];

	var ret = '';
	var part = new Array();
	for (var x=0; x<String(this).length; x++) part[x] = String(this).substring(x,x+1);
	for (var i=0, cnt = String(this).length; cnt > 0; --cnt,++i) {
		p = phonemic[part[i]];
		p+= (p) ? (cnt>4 && phonemic[part[i+1]]) ? unit[cnt].substring(0,1) : unit[cnt] : '';
		ret+= p;
	}
	return ret;
}
String.prototype.cut = function(len) {
	var str = this;
	var l = 0;
	for (var i=0; i<str.length; i++) {
		l += (str.charCodeAt(i) > 128) ? 2 : 1;
		if (l > len) return str.substring(0,i) + "...";
	}
	return str;
}


String.prototype.bytes = function() {
	var str = this;
	var l = 0;
	for (var i=0; i<str.length; i++) l += (str.charCodeAt(i) > 128) ? 2 : 1;
		return l;
}

String.prototype.ltrim = function() {
	var str = this;
	if(str.substring(0,1) == " ") {
		str = str.substring(1, str.length);
	}
	return str;
}

String.prototype.rtrim = function() {
	var str = this;
	if(str.substring(str.length-1, str.length) == " ") {
		str = str.substring(0, str.length-1);
	}
	return str;
}

String.prototype.trim = function() {
	var str = this;
	return str.ltrim().rtrim();
}

String.prototype.replaceStr = function(str1, str2) {
	var str = this;
	while(true) {
		if(str.indexOf(str1) >= 0) {
			str = str.replace(str1, str2);
		}
		else {
			break;
		}
	}
	return str;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
// 사용법																							//
// 1. 사용하고자하는 파일에서 아래의 예1과 같이 본 파일을 불러온다.									//
// 예1 : <script language="JavaScript" src="파일 경로/check_jumin.js"></script>						//
// 2. 원하는 이벤트 발생시 호출될 수 있도록 아래의 예2와 같이 html 테그의 속성으로 넣어준다.		//
// 예2 : onBlur = "check_jumin(form name, jumin1 textfield name, jumin2 textfield name);"			//
//         onKeypress = "onlyNumber();"																//
//////////////////////////////////////////////////////////////////////////////////////////////////////

function CheckJumin(form, jumin1, jumin2){						// form의 이름과 주민등록번호를 입력하는 2개의 text 객체를 받아온다
	// 주민등록번호 체크을 위한 기본 변수 선언
	var jumin = jumin1.value + jumin2.value;						// 입력된 주민등록번호 가져오기
	var key = "234567892345";											// 주민번호 생성 key 값
	var days = 0;																// 입력된 월의 일수를 저장할 변수
	var sum = 0;																// 곱해서 더한 총합 
	var result = 0;																// 연산후 마지막 숫자가 들어갈 변수

	var year_next = jumin.substring(0, 2);							// 연도(뒤에 두자리)에 해당하는 두자리를 구함
	var month = jumin.substring(2, 4);								// 월에 해당하는 두자리를 구함
	var day = jumin.substring(4, 6);									// 일에 해당하는 두자리를 구함
	var sex = jumin.charAt(6);											// 성별에 해당하는 한자리를 구함

	var year_prev = (sex == "1" || sex == "2") ? "19" : "20";	// 연도(앞에 두자리)에 해당하는 두자리를 구함
	var year = year_prev + year_next;									// 연도에 해당하는 네자리를 구함

	// 길이가 13자인지 체크
	if (jumin.length != 13){
		alert("주민등록번호는 13자리이어야 합니다.\n\n다시 확인하시고 입력해 주세요");
		return false;
	}

	// 월에 해당하는 두자리의 적합성 검사
	if(month < 1 || month > 12){
		alert("주민등록번호중, 월에 해당하는 두자리가 잘못 입력되었습니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin1.value="";
		jumin1.focus();
		return false;
	}

	// 월에 따른 일에 해당하는 두자리의 적합성 검사(윤년체크 포함)
	if(month=="01" || month=="03" || month=="05" || month=="07" || month=="08" || month=="10" || month=="12")	{
		days = 31;
	}
	if(month=="04" || month=="06" || month=="09" || month=="11"){
		days = 30;
	}
	if(month=="02"){
		if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){					// 윤년일 경우의 2월의 일수를 구함
			days = 29;
		}else{
			days = 28;
		}
	}

	if(day > days){
		alert("주민등록번호중, 일에 해당하는 두자리가 범위보다 큽니다.\n\n다시 확인하시고 입력해 주세요.");
		return false;
	}

	// 성별 비트에 따라 성별 자동으로 설정하기
	// 1 : 1900 ~ 1999 태어난 남
	// 2 : 1900 ~ 1999 태어난 여
	// 3 : 2000 ~ 2099 태어난 남
	// 4 : 2000 ~ 2099 태어난 여
	// 5 : 1900 ~ 1999 태어난 외국인 남
	// 6 : 1900 ~ 1999 태어난 외국인 여
	// 7 : 2000 ~ 2099 태어난 외국인 남
	// 8 : 2000 ~ 2099 태어난 외국인 여
	// 9 : 1800 ~ 1899 태어난 남
	// 0 : 1800 ~ 1899 태어난 여
	if(sex == "1" || sex == "3" || sex == "5" || sex == "7" || sex =="9"){}				//form.sex.value="m";
	else if(sex == "2" || sex == "4" || sex == "6" || sex == "8" || sex == "0"){}		//form.sex.value="f";
	else{
		alert("주민등록번호중, 성별에 해당하는 한자리가 잘못 입력되었습니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin2.value="";
		jumin2.focus();
		return false;
	}

	// 주민등록번호 생성 알고리즘에 의한 유효성 체크
	for(i=0; i<12; i++){
		sum += jumin.charAt(i) * key.charAt(i);
	}
	
	result = (11 - (sum % 11)) % 10;

	if (jumin.charAt(12) != result){
		alert("유효하지 않는 주민번호입니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin1.value="";
		jumin2.value="";
		jumin1.focus();
		return false;
	}
	return true;
}

//////////////////////////////////////////////////////////////////////////////////
//-- CheckJumin_V2 : alert 대신 레이어 팝업을 사용하기 위한 주민번호체크 함수 --//
//////////////////////////////////////////////////////////////////////////////////
function CheckJumin_V2(form, jumin1, jumin2){						// form의 이름과 주민등록번호를 입력하는 2개의 text 객체를 받아온다
	// 주민등록번호 체크을 위한 기본 변수 선언
	var jumin = jumin1.value + jumin2.value;						// 입력된 주민등록번호 가져오기
	var key = "234567892345";											// 주민번호 생성 key 값
	var days = 0;																// 입력된 월의 일수를 저장할 변수
	var sum = 0;																// 곱해서 더한 총합 
	var result = 0;																// 연산후 마지막 숫자가 들어갈 변수

	var year_next = jumin.substring(0, 2);							// 연도(뒤에 두자리)에 해당하는 두자리를 구함
	var month = jumin.substring(2, 4);								// 월에 해당하는 두자리를 구함
	var day = jumin.substring(4, 6);									// 일에 해당하는 두자리를 구함
	var sex = jumin.charAt(6);											// 성별에 해당하는 한자리를 구함

	var year_prev = (sex == "1" || sex == "2") ? "19" : "20";	// 연도(앞에 두자리)에 해당하는 두자리를 구함
	var year = year_prev + year_next;									// 연도에 해당하는 네자리를 구함

	// 길이가 13자인지 체크
	if (jumin.length != 13){
		menu_popup_view("/popup/warning_simple.asp?str="+"주민등록번호는 13자리이어야 합니다.<br><br>다시 확인하시고 입력해 주세요.",298,148,150);
		return false;
	}

	// 월에 해당하는 두자리의 적합성 검사
	if(month < 1 || month > 12){
		menu_popup_view("/popup/warning_simple.asp?str="+"주민등록번호중, 월에 해당하는 두자리가 잘못 입력되었습니다.<br><br>다시 확인하시고 입력해 주세요.",298,148,150);
		jumin1.value="";
		jumin1.focus();
		return false;
	}

	// 월에 따른 일에 해당하는 두자리의 적합성 검사(윤년체크 포함)
	if(month=="01" || month=="03" || month=="05" || month=="07" || month=="08" || month=="10" || month=="12")	{
		days = 31;
	}
	if(month=="04" || month=="06" || month=="09" || month=="11"){
		days = 30;
	}
	if(month=="02"){
		if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){					// 윤년일 경우의 2월의 일수를 구함
			days = 29;
		}else{
			days = 28;
		}
	}

	if(day > days){
		menu_popup_view("/popup/warning_simple.asp?str="+"주민등록번호중, 일에 해당하는 두자리가 범위보다 큽니다.<br><br>다시 확인하시고 입력해 주세요.",298,148,150);
		return false;
	}

	// 성별 비트에 따라 성별 자동으로 설정하기
	// 1 : 1900 ~ 1999 태어난 남
	// 2 : 1900 ~ 1999 태어난 여
	// 3 : 2000 ~ 2099 태어난 남
	// 4 : 2000 ~ 2099 태어난 여
	// 5 : 1900 ~ 1999 태어난 외국인 남
	// 6 : 1900 ~ 1999 태어난 외국인 여
	// 7 : 2000 ~ 2099 태어난 외국인 남
	// 8 : 2000 ~ 2099 태어난 외국인 여
	// 9 : 1800 ~ 1899 태어난 남
	// 0 : 1800 ~ 1899 태어난 여
	if(sex == "1" || sex == "3" || sex == "5" || sex == "7" || sex =="9"){}				//form.sex.value="m";
	else if(sex == "2" || sex == "4" || sex == "6" || sex == "8" || sex == "0"){}		//form.sex.value="f";
	else{
		menu_popup_view("/popup/warning_simple.asp?str="+"주민등록번호중, 성별에 해당하는 한자리가 잘못 입력되었습니다.<br><br>다시 확인하시고 입력해 주세요.",298,148,150);
		jumin2.value="";
		jumin2.focus();
		return false;
	}

	// 주민등록번호 생성 알고리즘에 의한 유효성 체크
	for(i=0; i<12; i++){
		sum += jumin.charAt(i) * key.charAt(i);
	}
	
	result = (11 - (sum % 11)) % 10;

	if (jumin.charAt(12) != result){
		menu_popup_view("/popup/warning_simple.asp?str="+"유효하지 않는 주민번호입니다.<br><br>다시 확인하시고 입력해 주세요.",298,148,150);
		jumin1.value="";
		jumin2.value="";
		jumin1.focus();
		return false;
	}
	return true;
}



function CheckJumin_V3(form, jumin1, jumin2){						// form의 이름과 주민등록번호를 입력하는 2개의 text 객체를 받아온다
	// 주민등록번호 체크을 위한 기본 변수 선언
	var jumin = jumin1.value + jumin2.value;						// 입력된 주민등록번호 가져오기
	var key = "234567892345";											// 주민번호 생성 key 값
	var days = 0;																// 입력된 월의 일수를 저장할 변수
	var sum = 0;																// 곱해서 더한 총합 
	var result = 0;																// 연산후 마지막 숫자가 들어갈 변수

	var year_next = jumin.substring(0, 2);							// 연도(뒤에 두자리)에 해당하는 두자리를 구함
	var month = jumin.substring(2, 4);								// 월에 해당하는 두자리를 구함
	var day = jumin.substring(4, 6);									// 일에 해당하는 두자리를 구함
	var sex = jumin.charAt(6);											// 성별에 해당하는 한자리를 구함

	var year_prev = (sex == "1" || sex == "2") ? "19" : "20";	// 연도(앞에 두자리)에 해당하는 두자리를 구함
	var year = year_prev + year_next;									// 연도에 해당하는 네자리를 구함

	// 길이가 13자인지 체크
	if (jumin.length != 13){
		alert("주민등록번호는 13자리이어야 합니다.\n\n다시 확인하시고 입력해 주세요");
		return false;
	}

	// 월에 해당하는 두자리의 적합성 검사
	if(month < 1 || month > 12){
		alert("주민등록번호중, 월에 해당하는 두자리가 잘못 입력되었습니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin1.value="";
		return false;
	}

	// 월에 따른 일에 해당하는 두자리의 적합성 검사(윤년체크 포함)
	if(month=="01" || month=="03" || month=="05" || month=="07" || month=="08" || month=="10" || month=="12")	{
		days = 31;
	}
	if(month=="04" || month=="06" || month=="09" || month=="11"){
		days = 30;
	}
	if(month=="02"){
		if((year % 4 == 0 && year % 100 != 0) || year % 400 == 0){					// 윤년일 경우의 2월의 일수를 구함
			days = 29;
		}else{
			days = 28;
		}
	}

	if(day > days){
		alert("주민등록번호중, 일에 해당하는 두자리가 범위보다 큽니다.\n\n다시 확인하시고 입력해 주세요.");
		return false;
	}

	// 성별 비트에 따라 성별 자동으로 설정하기
	// 1 : 1900 ~ 1999 태어난 남
	// 2 : 1900 ~ 1999 태어난 여
	// 3 : 2000 ~ 2099 태어난 남
	// 4 : 2000 ~ 2099 태어난 여
	// 5 : 1900 ~ 1999 태어난 외국인 남
	// 6 : 1900 ~ 1999 태어난 외국인 여
	// 7 : 2000 ~ 2099 태어난 외국인 남
	// 8 : 2000 ~ 2099 태어난 외국인 여
	// 9 : 1800 ~ 1899 태어난 남
	// 0 : 1800 ~ 1899 태어난 여
	if(sex == "1" || sex == "3" || sex == "5" || sex == "7" || sex =="9"){}				//form.sex.value="m";
	else if(sex == "2" || sex == "4" || sex == "6" || sex == "8" || sex == "0"){}		//form.sex.value="f";
	else{
		alert("주민등록번호중, 성별에 해당하는 한자리가 잘못 입력되었습니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin2.value="";
		return false;
	}

	// 주민등록번호 생성 알고리즘에 의한 유효성 체크
	for(i=0; i<12; i++){
		sum += jumin.charAt(i) * key.charAt(i);
	}
	
	result = (11 - (sum % 11)) % 10;

	if (jumin.charAt(12) != result){
		alert("유효하지 않는 주민번호입니다.\n\n다시 확인하시고 입력해 주세요.");
		jumin1.value="";
		jumin2.value="";
		return false;
	}
	return true;
}


// png파일 익스6.0에서 깨지는 부분해결
function setPng24(obj) {
	obj.width=obj.height=1;
	obj.className=obj.className.replace(/\bpng24\b/i,'');
	obj.style.filter =
	"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
	obj.src='about:blank'; 
	return '';
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 윤년, 윤달 체크 함수	getDayOfMonth(), changeDay()																//
// 사용법																											//
// 1. 사용하고자하는 파일에서 아래의 예1과 같이 본 파일을 불러온다.													//
// 예1 : <script language="JavaScript" src="파일 경로/Common.js"></script>											//
// 2. 파라미터(년도, 월, 일)																						//
// 예2 : changeDay(objYear,objMonth,objDay); => changeDay(년도,월,일) 호출											//
//											 => changeDay() 내에서 윤달 체크 getDayOfMonth()						//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getDayOfMonth(objYear,objMonth) 
{  
	var arrMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);   
	var year = objYear.options[objYear.selectedIndex].value;  
	var month = objMonth.options[objMonth.selectedIndex].value;  
	if(month.substring(0,1) == '0'){  
	month = month.substring(1,2);  
	}  

	//윤년인가를 체크합니다. 
	if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) arrMonth[1] = "29";   
	return arrMonth[month-1];  
}  

//선택된 월에 의해 적정한 일수를 지정합니다. 
function changeDay(objYear,objMonth,objDay)
{  
	for(i= objDay.length; i>-1;i--) objDay.options[i] = null;  
    for(i=1;i<= getDayOfMonth(objYear,objMonth); i++)
	{  
		if(i <10){ i = "0" + i; }  
		//value = i + "일";  
		value = i;  
		text = i;  
		optDay = new Option( value, text);
		objDay.options[objDay.length] = optDay;  
	}   
	objDay.selectedIndex = 0;  
}

function Left(str, n){
	if (n <= 0)
	   return "";
	else if (n > String(str).length)
		return str;
	else
		return String(str).substring(0,n);
}

function Right(str, n){
	if (n <= 0)
		return "";
	else if (n > String(str).length)
		return str;
	else {
		var iLen = String(str).length;
		return String(str).substring(iLen, iLen - n);
	}
}

// 링크 점선 없애기

function bluring()
{
if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG")document.body.focus();
}

