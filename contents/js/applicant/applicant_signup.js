RE_NUMBER   = new RegExp(/^[0-9]+$/);

RE_EMAIL    = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);

RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);



var httpCheckUid				= getHTTPObject();





function getHTTPObject()

{



  var xmlhttp;

  if (!xmlhttp )

  {



    if(window.XMLHttpRequest) 

    {

    	try {

			      xmlhttp = new XMLHttpRequest();

          } 

          catch(e) {

			               xmlhttp = false;

                   }

     }

     else if(window.ActiveXObject)

     {

       	try {

        	    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");

      	    }

            catch(E) {

				 try {

					   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

					 } catch(e) {

								  xmlhttp = false;

								}

					 }

     }



  }  

 

  return xmlhttp;



}



//============= Start chkEmailExistence for Signup ============

function getUserId(depono)

{ 

	//alert(catagory);

	  if(depono!="")

	  {  var url = "?app=client_profile&cmd=ajaxcheckemail&uid="+depono;

	  //	alert(url);

	  	  httpCheckUid.open("GET", url, true);

		  httpCheckUid.onreadystatechange = handleUserIdResponse;

		  httpCheckUid.send(null);

	  }

}



function handleUserIdResponse()

{

    if(httpCheckUid.readyState == 4)

    {       

 	   var EmployerTxt 	 = httpCheckUid.responseText;

	 // alert(EmployerTxt);

	   var useridArr 		 = EmployerTxt.split('######');

	  //alert(useridArr);

	   if(useridArr[0]==0){

	   		document.getElementById('uidmsg').innerHTML="<span style='color:#009900;font-weight:bold'>&#2472;&#2478;&#2509;&#2476;&#2480;&#2463;&#2494; &#2476;&#2509;&#2479;&#2476;&#2489;&#2494;&#2480; &#2453;&#2480;&#2468;&#2503; &#2474;&#2494;&#2480;&#2503;&#2472;</span>";

	   }else if(useridArr[0]==1){

		   	document.getElementById('uidmsg').innerHTML="<span style='color:#FF0000; font-weight:bold'>&#2447;&#2439; &#2488;&#2462;&#2509;&#2458;&#2527; &#2472;&#2478;&#2509;&#2476;&#2480;&#2463;&#2494; &#2438;&#2459;&#2503; &#2437;&#2472;&#2509;&#2479; &#2472;&#2478;&#2509;&#2476;&#2480; &#2470;&#2495;&#2472;</span>";

			document.frmapplicant_signup.depono.focus(); 

			document.frmapplicant_signup.depono.select(); 		



	   }

	}

	else{

		document.getElementById('uidmsg').innerHTML = "Checking Existence. Please Wait...";

		}

}

//=====End chkEmailExistence ======



function cancelEmployer()

{

	window.location=CANCEL_URL;

}



function Trim(TRIM_VALUE){

if(TRIM_VALUE.length < 1){

return"";

}

TRIM_VALUE = RTrim(TRIM_VALUE);

TRIM_VALUE = LTrim(TRIM_VALUE);

if(TRIM_VALUE==""){



return "";

}



else{

return TRIM_VALUE;

}

} //End Function



function RTrim(VALUE){

var w_space = String.fromCharCode(32);

var v_length = VALUE.length;

var strTemp = "";

if(v_length < 0){

return"";

}

var iTemp = v_length -1;



while(iTemp > -1){

if(VALUE.charAt(iTemp) == w_space){

}

else{

strTemp = VALUE.substring(0,iTemp +1);

break;

}

iTemp = iTemp-1;



} //End While

return strTemp;



} //End Function



function LTrim(VALUE){

var w_space = String.fromCharCode(32);

if(v_length < 1){

return"";

}

var v_length = VALUE.length;

var strTemp = "";



var iTemp = 0;



while(iTemp < v_length){

if(VALUE.charAt(iTemp) == w_space){

}

else{

strTemp = VALUE.substring(iTemp,v_length);

break;

}

iTemp = iTemp + 1;

} //End While

return strTemp;

} //End Function





function deleteClientProfile(client_id){	

   var url_loc = "index.php?app=client_profile&cmd=delete&client_id="+client_id;

   window.location = url_loc;

}



function updateClientProfile(client_id){

	var ul_loc = "?app=client_profile&cmd=edit&client_id="+client_id; 

	window.location = ul_loc;

}



function DisableClientProfile(client_id){

	var ul_loc = "?app=client_profile&cmd=Disable&client_id="+client_id; 

	window.location = ul_loc;

}





function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}



function searchData() {

var searchq=(document.getElementById('searchq').value);

ajax.open('get', '?app=client_profile&cmd=ajaxSearch&searchq='+searchq);

ajax.onreadystatechange = searchDataReply4Employee;

ajax.send(null);

}

function searchDataReply4Employee() {

if(ajax.readyState == 4){

	var response = ajax.responseText;

	//alert(ajax.responseText);

	document.getElementById('html').innerHTML=response;

}

}



function validate_form()

{

	valid = true;

     if ( document.Srchform.zone_id.value == ""  )

        {

		        alert ( "Please select zone" );

                valid = false;

        }

		

        return valid;

}

function passwordStrength(password)

{

	var desc = new Array();

	desc[0] = "Very Weak";

	desc[1] = "Weak";

	desc[2] = "Better";

	desc[3] = "Medium";

	desc[4] = "Strong";

	desc[5] = "Strongest";



	var score   = 0;



	//if password bigger than 6 give 1 point

	if (password.length > 6) score++;



	//if password has both lower and uppercase characters give 1 point	

	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;



	//if password has at least one number give 1 point

	if (password.match(/\d+/)) score++;



	//if password has at least one special caracther give 1 point

	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;



	//if password bigger than 9 give another 1 point

	if (password.length > 9) score++;



	 document.getElementById("passwordDescription").innerHTML = desc[score];

	 document.getElementById("passwordStrength").className = "strength" + score;

}







// ------------------------For Pending to disable Search---------------------------------------------------

function searchPending() {

	searchq=(document.getElementById('searchq').value);

	ajax.open('get', '?app=disable_enable&cmd=ajaxSearchPending&searchq='+searchq);

	ajax.onreadystatechange = searchPendingReply;

	ajax.send(null);

}



function searchPendingReply() {

	if(ajax.readyState == 4){

		var response = ajax.responseText;

		//alert(ajax.responseText);

		document.getElementById('html').innerHTML=response;

	}

}





// ------------------------For Disable to enable Search---------------------------------------------------

function searchDisable() {

	searchq=(document.getElementById('searchq').value);

	ajax.open('get', '?app=disable_enable&cmd=ajaxSearchDisable&searchq='+searchq);

	ajax.onreadystatechange = searchDisableReply;

	ajax.send(null);

}



function searchDisableReply() {

	if(ajax.readyState == 4){

		var response = ajax.responseText;

		//alert(ajax.responseText);

		document.getElementById('html').innerHTML=response;

	}

}





// ------------------------For Pending to enable Search---------------------------------------------------

function searchPendingToEnable() {

	searchq=(document.getElementById('searchq').value);

	ajax.open('get', '?app=disable_enable&cmd=ajaxSearchPendingToEnable&searchq='+searchq);

	ajax.onreadystatechange = searchPendingToEnableReply;

	ajax.send(null);

}



function searchPendingToEnableReply() {

	if(ajax.readyState == 4){

		var response = ajax.responseText;

		//alert(ajax.responseText);

		document.getElementById('html').innerHTML=response;

	}

}

