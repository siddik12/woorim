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

function getUserId(employee_tmp_id)

{ 

	//alert(catagory);

	  if(employee_tmp_id!="")

	  {  var url = "?app=employee&cmd=ajaxcheckemail&uid="+employee_tmp_id;

	  	//alert(url);

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

	   //alert(EmployerTxt);

	   var useridArr 		 = EmployerTxt.split('######');

	   //alert(useridArr);

	   if(useridArr[0]==0){

	   		document.getElementById('uidmsg').innerHTML="<span style='color:#009900;font-weight:bold'>Available</span>";

	   }else if(useridArr[0]==1){

		   	document.getElementById('uidmsg').innerHTML="<span style='color:#FF0000; font-weight:bold'>Not Available</span>";

			document.frmapplicant_signup.employee_tmp_id.focus(); 

			document.frmapplicant_signup.employee_tmp_id.select(); 		



	   }

	}

	else{

		document.getElementById('uidmsg').innerHTML = "Checking Existence. Please Wait...";

		}

}

//=====End chkEmailExistence ======







function deleteEmployeeProfile(employee_id){	

   var url_loc = "index.php?app=employee&cmd=delete&employee_id="+employee_id;

   window.location = url_loc;

}



function updateEmployeeProfile(employee_id){

	var ul_loc = "?app=employee&cmd=edit&employee_id="+employee_id; 

	window.location = ul_loc;

}





function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}



function searchData() {

searchq=(document.getElementById('searchq').value);

ajax.open('get', '?app=employee&cmd=ajaxSearch&searchq='+searchq);

ajax.onreadystatechange = searchDataReply4Teacher;

ajax.send(null);

}

function searchDataReply4Teacher() {

if(ajax.readyState == 4){

	var response = ajax.responseText;

	//alert(ajax.responseText);

	document.getElementById('html').innerHTML=response;

}

}



function addClientId(person_id, ele_id, person_name, ele_lbl_id){

		// alert(employer_id+' - '+ele_id+' - '+company_name+' - '+ele_lbl_id);

		 document.getElementById(ele_id).value=person_id;

		 document.getElementById(ele_lbl_id).value=person_name;

}