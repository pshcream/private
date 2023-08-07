var price,ebox = '1',iBox,ctaxCalType;
var codes = new Array();
var keyStr = "ABCDEFGHIJKLMNQPORSTVUWXYZabcdefghjiklmnoqprstuvwxyz0123456789+/=";
var optCode = new Array();

$(function() {

	/*  =옵션 선택 : 견적  */
	$(document).on("click",".eChkItemList input",function(){
		//ebox = '1';
		var itms = $(this).val();
		var ckd = $(this).is(":checked");
		
		//items_check 계산하기위해 ebox 임시변환
		items_check(itms,ckd);	// 중복 제거, 의존 추가
		//calculate("itms",itms);
		
		//console.log("test");
		calculate();
		
		// 2018.06.07 verere@danawa.com TUIX, TUON 옵션 선택에 따른 출고장 변화 
		//if ($(this).parent().find("span.name").attr("title").indexOf("TUIX") != -1 || $(this).parent().find("span.name").attr("title").indexOf("TUON") != -1) {
		//	$("[name='SETRNSKD']").trigger("click");
		//}
	});
	
	$("#ext_color").on("change",function(){
		calculate();
	});
});

function calculate()
{
	//견적 계산 결과 저장 위한 임시 변수 정의 
	price = new Array();// 차량 가격 계산
	price.ctaxOld = setTrms.ctax;
	price.color = 0;
	
	/* 차량가격 계산 */
	calculate_price();
	
}
/* 차량 가격 계산  */
function calculate_price(){
	/*
		1. 기본가격 price.base
		2. 옵션가격 price.itms
	*/
	// 1. 기본 가격
	price.base = parseInt(setTrms.pric);
	
	// 2. 선택 품목
	items_total();	// 선택가격 itms (codes.itms, texts.itms)
	
	// 3. 외장색
	ext_color();
	
	// 6. 합계
	price.total = price.base+price.itms+price.color;
	setTrms.totalprice = price.total;
	
	$("#selectedOptions").val(price.itmsN);
	/*	주석처리
	$car_price.val(price.base);
	$opt_price.val(price.itms+price.color);
	$tot_price.val(price.total);
	
	$car_price_txt.val(addCommas(price.base));
	$opt_price_txt.val(addCommas(price.itms+price.color));
	$tot_price_txt.val(addCommas(price.total));*/
}

/* 외장색 */
function ext_color(){
	var colorPrice = $ext_color.find("option:selected").attr("data-price");
	colorPrice = parseInt(colorPrice);
			
	const gap = taxChangedPrice(price.ctaxOld,colorPrice);
			
	price.color = colorPrice + gap;
}

/* 선택사양 합계 계산 */
function items_total(){
	var Osum = 0;
	var Ocode = "";
	var Oname = "";
	var Oct = 0;
	var checkedOption = Array();
	
	//if(emod == 'appView') iBox = ".pChkItemList[ebox='P_"+ codes.code +"']";
	//else iBox = "#Optn_"+ebox+" .eChkItemList";
	iBox = "#Optn_"+ebox+" .eChkItemList";
	
	$(iBox+" input").each(function(){
		var code = $(this).val();
		if($(this).is(":checked")){
			//var tmpVal = $(this).attr("value").split("`");//dnw_deChar($(this).attr("value")).split("`"); 
			//var tmpVal2 = tmpVal[1].split(":");
			
			var tmpVal = $(this).attr("data-label");
			var tmpVal2 = $(this).attr("data-price");
			
						
			// 2015-08-27 개별소비세 인하 자동 반영 기능 추가 5.0 -> 3.5		// 개소세 혜택 적용/종료 선택 기능 추가 2015-11-24
			tmpVal2 = parseInt(tmpVal2);
			if(ctaxCalType && (price.ctaxOld=="5.0" || price.ctaxOld=="3.5")){
				gap  = taxChangedPrice(price.ctaxOld,tmpVal2);
				price.spDC += gap;
				if((price.ctaxOld=="5.0" && ctaxCalType=="O") || (price.ctaxOld=="3.5" && ctaxCalType=="X")){
					tmpVal2 += gap;
				}
			}
			
			//gap  = taxChangedPrice(price.ctaxOld,tmpVal2);
			//console.log(gap);
			
			/*
			if(price.ctaxOld=="5.0"){
				gap  = taxChangedPrice(price.ctaxOld,tmpVal2);
				tmpVal2 += gap;
				price.spDC += gap;
			}else if(price.ctaxOld=="3.5"){
				gap  = taxChangedPrice(price.ctaxOld,tmpVal2);
				price.spDC += gap;
			}
			*/
			
			Osum += parseInt(tmpVal2);
			
			if(Ocode){
				Ocode += ",";
				Oname += "!";
			}
			
			checkedOption.push(code);
			Ocode += code;
			Oname += code + "`" + parseInt(tmpVal2) + "`" + tmpVal;
			Oct++;
		}
	});
	optCode = checkedOption;
	price.itms = Osum;	// 옵션가격
	codes.itms = Ocode;	// 옵션코드
	price.itmsN = Oname;	// 옵션명칭 (코드`가격`명칭 ! ...)
	price.itmsC = Oct;	// 옵션갯수
}

function taxChangedPrice(tax,price)
{
	tax = parseFloat(tax);
	price = parseInt(price);
	
	var price2 = 0;
	var gap = 0;
	
	if(tax == 5.0){
		taxB = 3.5;
		price2 = price * (1 + taxB * 1.3 / 100) / (1 + tax * 1.3 / 100 );
		price2 = number_round(price2,10000,'round');
		gap = price2 - price;
	}
	else if(tax == 3.5){
		taxB = 5.0;
		price2 = price * (1 + taxB * 1.3 / 100) / (1 + tax * 1.3 / 100 );
		price2 = number_round(price2,10000,'round');
		gap = price2 - price;
	}
	
	return gap;
}

/* 선택사양 중복/의존 체크해주는 기능, 아래 4개의 함수 병행 사용  2014-05-12 수정(확인필요)  */
function items_check(opt,ckd){
	//console.log("opt:"+opt);
	//console.log("ckd:"+ckd);
	
	iBox = "#Optn_"+ebox+" .eChkItemList";
		
	var tmpVal2 = $(iBox+" input[id='item_1"+opt+"']").attr("data-overlap");
	
	var tmp = "";
	if(tmpVal2 != "") {
		tmp = string_trim(tmpVal2);
	}
	//<input name="option" id="item_114390" class="form-check-input" type="checkbox" value="14390`640000:B`컨비니언스" data-overlap="B" code="14390">
	
	var Cstr = "";
	
	if(tmp) {	// 선택된 정보 수집..
		infoSel = "";
		infoOff = "";
		infoMy = "";
		Pckd = 0;
		Psel = 0;
		Poff = 0;
		Pstr = "";
		items_selOnoff(opt);
	}
	
	if(ckd && tmp) {	// 선택시
		infoMy = tmp;
		for(Cct = 0; Cct < tmp.length; Cct ++){
			Cstr = tmp.substring(Cct,Cct+1);
			if(Cstr.toUpperCase()==Cstr){	// 중복옵션 제거
				if(infoSel.indexOf(Cstr)>=0){
					items_selOffL(Cstr,opt);
				}
			}else{	// 부모선택 확인후 지정
				Pckd ++;
				if(infoSel.indexOf(Cstr.toUpperCase())>=0 || infoMy.indexOf(Cstr.toUpperCase())>=0){
					Psel ++;
				}else if(infoOff.indexOf(Cstr.toUpperCase())>=0){
					Poff ++;
					Pstr += Cstr;
				}
			}
		}
		if(Pckd && Psel==0 && Poff){
			for(Cct = 0; Cct < Pstr.length; Cct ++){
				Cstr = Pstr.substring(Cct,Cct+1);
				items_selOnP(Cstr,opt);
			}
		}
	} else if(tmp) {	// 해제시
		items_selOffC(tmp,opt);
	}
}
/* 선택사양 부모옵션 선택..  2014-05-12 수정(확인필요)  */
function items_selOnP(pmp,pxt){
	$(iBox+" input").each(function(){
		Pode = $(this).val();
		if(!$(this).is(":checked") && pxt.indexOf(Pode)<0 && Psel == 0){
			//var pmpVal = $(this).attr("value").split("`");//dnw_deChar($(this).attr("value")).split("`");
			//var pmpVal2 = pmpVal[1].split(":");
			
			var pmpVal2 = $(this).attr("data-overlap");
			if(pmpVal2!="" && pmpVal2 && pmpVal2.indexOf(pmp.toUpperCase())>=0){
				$(this).prop("checked",true);
				$(this).parent().addClass("sel");
				pmpVal2 = pmpVal2.replace(pmp.toUpperCase(),"");
				Psel ++;
				if(pmpVal2){
					for(Pct = 0; Pct < pmpVal2.length; Pct ++){
						Pstr = pmpVal2.substring(Pct,Pct+1);
						items_selOffL(Pstr,pxt+","+Pode);
					}
				}
			}
		}
	});
}
/* 선택사양 중복옵션 제거..  2014-05-12 수정(확인필요)  */
function items_selOffL(omp,oxt){
	$(iBox+" input").each(function(){
		Oode = $(this).val();
		if($(this).is(":checked") && oxt.indexOf(Oode)<0){
			//var ompVal = $(this).attr("value").split("`");//dnw_deChar($(this).attr("value")).split("`");
			//var ompVal2 = ompVal[1].split(":");
			
			var ompVal2 = $(this).attr("data-overlap");
			
			//if(ompVal2.length > 1 && ompVal2[1] && ompVal2[1].indexOf(omp)>=0){
			if(ompVal2 != "" && ompVal2 && ompVal2.indexOf(omp)>=0){
				$(this).prop("checked",false);
				$(this).parent().removeClass("sel");
				infoSel = infoSel.replace(ompVal2,"");
				ompVal2 = ompVal2.replace(omp,"");
				if(ompVal2){
					items_selOffC(ompVal2,oxt);
				}
			}
		}
	});
}

/* 선택사양 자식옵션 제거..  2014-05-12 수정(확인필요) */
function items_selOffC(dmp,dxt){
	//console.log(dmp,dxt);
	for(Dct = 0; Dct < dmp.length; Dct ++){
		Dstr = dmp.substring(Dct,Dct+1);
		if(Dstr.toUpperCase()==Dstr){	// 중복옵션 제거
			if(infoMy.indexOf(Dstr)<0){
				Dstr = Dstr.toLowerCase();
				if(infoSel.indexOf(Dstr)>=0){
					$(iBox+" input").each(function(){
					//$("[id ^= 'CKITMSQ"+ebox+"J']").each(function(){
						Dode = $(this).val();
						if($(this).is(":checked") && dxt.indexOf(Dode)<0){
							//var dmpVal = $(this).attr("value").split("`");//dnw_deChar($(this).attr("value")).split("`");
							//var dmpVal2 = dmpVal[1].split(":");
							var dmpVal2 = $(this).attr("data-overlap");
							
							if(dmpVal2!="" && dmpVal2 && dmpVal2.indexOf(Dstr)>=0){
								dmpVal2 = dmpVal2.replace(Dstr,"");
								var CkdCt = 0;
								if(dmpVal2){
									for(Dct2 = 0; Dct2 < dmpVal2.length; Dct2 ++){
										Dstr2 = dmpVal2.substring(Dct2,Dct2+1);
										if(Dstr2.toLowerCase()==Dstr2){
											if(infoSel.indexOf(Dstr2.toUpperCase())>=0 || infoMy.indexOf(Dstr2.toUpperCase())>=0){
												CkdCt ++;
											}
										}
									}
								}
								if(CkdCt==0){
									$(this).prop("checked",false);
									$(this).parent().removeClass("sel");
									infoSel = infoSel.replace(dmpVal2,"");
								}
							}
						}
					});
				}
			}
		}
	}
}

/* 선택사양 선택/비선택 구분.. 2014-05-12 수정(확인필요) */
function items_selOnoff(Sopt){
	$(iBox+" input").each(function(){
		code = $(this).val();
		if(Sopt!=code){
			//var cmpVal = $(this).val().split("`");//dnw_deChar($(this).val()).split("`");
			//var cmpVal2 = cmpVal[1].split(":");
			/*
			iBox = "#Optn_"+ebox+" .eChkItemList";
		
			var tmpVal2 = $(iBox+" input[id='item_1"+opt+"']").attr("data-overlap");
			
			var tmp = "";
			if(tmpVal2 != "") {
				tmp = string_trim(tmpVal2);
			}
			*/
			
			var cmpVal2 = $(this).attr("data-overlap");
			
			if(cmpVal2 !="" && cmpVal2){
				var cmp = string_trim(cmpVal2);
				if($(this).is(":checked")){
					infoSel += cmp;
				}else{
					infoOff += cmp;
				}
			}
		}
	});
}

/* 배열로 만들기 */
function dnw_array(str,arr){
	eval(arr +'= new Array()');
	str = dnw_deChar(str);
	var strArr = str.split("#");
	var strCt = strArr.length;
	for(ct=0;ct<strCt;ct++){
		var strSet = strArr[ct].split("^");
		eval(arr+'[strSet[0]] = strSet[1]');
	}
}

function dnw_deChar2(str){
	str = JXG.decompress(str);
	str = dnw_decode(str);
	str = decodeURIComponent(str);
	str = str.replace(/&nbsp;/gi," ");
	return str;
}

function dnw_deChar(str){
	str = dnw_decode(str);
	str = decodeURIComponent(str);
	str = str.replace(/&nbsp;/gi," ");
	return str;
}

function dnw_decode(input){
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;
   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));
      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;
      output = output + String.fromCharCode(chr1);
      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);
   return output;
}

function string_trim(str){
	return str?.replace(/^\s+|\s+$/g,"");
}

function dnw_enChar(str){
	str = encodeURIComponent(str);
	str = dnw_encode(str);
	return str;
}

function dnw_encode(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;
   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);
      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;
      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } else if (isNaN(chr3)) {
         enc4 = 64;
      }
      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);
   return output;
}

function number_change(data, object) {
	var data = parseInt(data);
    var $obj = $("#" + object);
    if (isNaN(data)) {
        $obj.text(data);
        return;
    }
    var addStr = "";
    if (data < 0) {
        data = Math.abs(data);
        addStr = "-";
    }
    var thisNo = number_filter($obj.text());
    thisNo = Math.abs(thisNo);
    if (data == thisNo) {
        $obj.text(addStr + number_format(data));
        return;
    } else if (data > thisNo && Math.abs(data - thisNo) < 10) var step = 1;
    else if (data < thisNo && Math.abs(data - thisNo) < 10) var step = -1;
    else var step = parseInt((data - thisNo) / 10);
    var step2 = Math.abs(step) + "";
    var len = step2.length;
    if (len < 2) len = 0;
    var len2 = Math.pow(10, len - 2);
    step = number_cut(step, len2, "round");
    var spinning = setInterval(function() {
        $obj.text(function() {
            thisNo += step;
            if (step > 0 && thisNo >= data) {
                thisNo = data;
                if (thisNo == 0) addStr = "";
                clearInterval(spinning);
                return addStr + number_format(thisNo);
            } else if (step < 0 && thisNo <= data) {
                thisNo = data;
                if (thisNo == 0) addStr = "";
                clearInterval(spinning);
                return addStr + number_format(thisNo);
            } else {
                if (thisNo == 0) addStr = "";
                return addStr + number_format(thisNo);
            }
        });
    }, 40);
}

function number_format(num) {
    if (num == "" || num == 0) return 0;
    num = Math.round(num);
    var str = "" + num + "";
    var rtn = "";
    for (ilk = 0; ilk < str.length; ilk++) {
        if (ilk > 0 && (ilk % 3) == 0 && str.charAt(str.length - ilk - 1) != "-") {
            rtn = str.charAt(str.length - ilk - 1) + "," + rtn;
        } else {
            rtn = str.charAt(str.length - ilk - 1) + rtn;
        }
    }
    return rtn;
}

function number_filter(data) {
    data = data.replace(/[^0-9.-]/g, "");
    return data;
}

function number_cut(data, step, type) {
    if (type == 'floor') return Math.floor(data / step) * step;
    else if (type == 'ceil') return Math.ceil(data / step) * step;
    else return Math.round(data / step) * step;
}

function number_round(num,dis,way)
{
	if(way == "floor") return Math.floor(num / dis ) * dis;
	else if(way == "ceil") return Math.ceil(num / dis ) * dis;
	else return Math.round(num / dis ) * dis;
}
function delCommas(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	x1 = x1.replace(/[^\d]+/g, "");
	return x1 + x2;
}
function addCommas(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}