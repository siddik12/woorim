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





function deleteItem(item_id){	

   var url_loc = "?app=item_distribute&cmd=delete&item_id="+item_id;

   window.location = url_loc;

}



function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}



function searchData() {

var searchq=(document.getElementById('searchq').value);

ajax.open('get', '?app=item_distribute&cmd=ajaxSearch&searchq='+searchq);

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

//============================= For Add Skin=======================

function searchClientList() {

	var searchq=(document.getElementById('searchq').value);

	ajax.open('get', '?app=item_distribute&cmd=ajaxSearchClientList&searchq='+searchq);

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

function addClientId(sub_item_category_id, ele_id,main_item_category_id, ele_id2,balance, ele_id3, sub_item_category_name, ele_lbl_id){
		 //alert(sub_item_category_id+' - '+ele_id+' - '+main_item_category_id+' - '+ele_id2+' - '+balance+' - '+ele_id3+' - '+sub_item_category_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=sub_item_category_id;
		 document.getElementById(ele_id2).value=main_item_category_id;
		 document.getElementById(ele_id3).value=balance;
		 document.getElementById(ele_lbl_id).value=sub_item_category_name;
}
//=============================================================================================