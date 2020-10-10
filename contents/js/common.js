// JavaScript Document
var ajax = ajaxFunction();
function ajaxFunction(){
	var ajaxRequest; 
	
	if (!ajaxRequest){
		if(window.XMLHttpRequest) {
			try{
				// Opera 8.0+, Firefox, Safari
				ajaxRequest = new XMLHttpRequest();
			} catch (e){
				// Internet Explorer Browsers
				ajaxRequest = false;
			}
		}
		else if(window.ActiveXObject) {
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
						ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
					} catch (e){
					ajaxRequest = false;
					}
			}
		}
	}
	return ajaxRequest;
}
function LTrim( value ) {
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
}

// Removes ending whitespaces
function RTrim( value ) {
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
}

// Removes leading and ending whitespaces
function trim( value ) {
	return LTrim(RTrim(value));
}

function changeInnerHTML(id, b4,afr){
	var ele = document.getElementById(id);
	if (ele.innerHTML == b4){ele.innerHTML = afr}else{ele.innerHTML = b4;}
}

function clearValue(id){document.getElementById(id).value="";}

function inputBoxLabelHide(id, val){
	id.value == val ? id.value = '' : null;
}
function inputBoxLabelShow(id, val){
	id.value == '' ? id.value = val : null;
}

function inputBoxLabelShowHide(id, val){
	if(id.value == ''){ id.value = val;}
	else if(id.value == val){ id.value = '';}
}

function showElement(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'visible';
}
function hideElement(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}
function showHideElement(id){
	var ele = document.getElementById(id);
	ele.style.visibility == 'visible' ? ele.style.visibility = 'hidden' : ele.style.visibility = 'visible' ;
}

function expandCollapse(eleid,hw,b4Size,afterSize){
	//alert(eleid+' '+hw+' '+b4Size+' '+afterSize);
	var sty = document.getElementById(eleid).style;
	//alert(sty.height);
	if(hw == 'h'){
		if(sty.height == b4Size){
			sty.height = '';
			//alert(sty.height);
		}else{
			sty.height = b4Size;
		}
	}else if(hw == 'w'){
		if(sty.width == b4Size){sty.width = afterSize;}else{sty.width = b4Size;}
	}
	//alert(eleid+' '+hw+' '+b4Size+' '+afterSize);
}
/*********************Character count************************************************/

function countLetter(textareanm , maxChar , spanName , MessageCaption , shouldShowSpan ){
	span_area = document.getElementById(spanName) ;
	txtara = document.getElementById(textareanm)   ;
	ev_v =  txtara.value ;
	tfVal = parseInt(ev_v.length) ; 
	maxChar = parseInt(maxChar) ; 
	
	if(tfVal != 0){
	window.status = tfVal ; 
	shouldShowSpan == 1 ? 
	span_area.innerHTML = "You wrote <b>"+tfVal+"</b> character(s). More than <b>1500</b> characters will be trancated." :  "" ;
	 
		 if(tfVal >= ( maxChar + 1 ) )  {
			alert("Please stop writing on "+MessageCaption +", you are crossing the "+maxChar+" letter limit !") ;
			txtara.focus() ;
		 }// if(tfVal >= ( maxChar + 1 ) ) 
		 if(tfVal > maxChar )
		 {
		 nb =  txtara.value.substr(0, maxChar ) ;
		 txtara.value =  nb ;
		 shouldShowSpan == 1 ? 
		 span_area.innerHTML ="You wrote <b>"+nb.length+"</b> character(s). More than <b>1500</b> characters will be trancated." 
		 :  "" ;
		  }//if(tfVal > maxChar )
	 
	}//if(tfVal != 0)
	else{
	 window.status = "" ;
	  shouldShowSpan == 1 ? span_area.innerHTML = "" : "" ;
	}//else of if(tfVal != 0)
}//end countLetter func.

function goBackPage(){history.go(-1);}
function href(url){	window.location = url;}
/**************************** Common AJAX CALL ******************************/

var ajaxResponseReceiver = '';

function ajaxCall(app, cmd, sink){
	document.getElementById('loaderImg').style.visibility='visible';
	ajaxResponseReceiver = sink;
	var url = '?app='+app+'&cmd='+cmd;
	ajax.open('get',url);
	ajax.onreadystatechange = ajaxResponse;
	ajax.send(null);
	
}

function ajaxResponse() {
	if(ajax.readyState == 4){
		document.getElementById(ajaxResponseReceiver).innerHTML=ajax.responseText;
		document.getElementById('loaderImg').style.visibility='hidden';
	}
}