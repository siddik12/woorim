// JavaScript Document
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


function check()    {

chosen = ""
var len = document.complfrm.person_id.length

	for (i = 0; i <len; i++) {
		if (document.complfrm.person_id[i].checked) {
			var chosen = document.complfrm.person_id[i].value
		}
	}

	if (document.complfrm.customer_id.value==''){
		alert("Please select customer");
		return false;
	} else if (chosen == "") {
		alert("No Salesman selected");
		return false;
	}
	else {
	return true;
	}
}


//============= Start chkEmailExistence for Signup ============
function getUserId()
{    
	
		var itemcode = document.getElementById('itemcode').value
	//alert(itemcode);
	  if(itemcode)
	  {  var url = "?app=inv_item_sales_whole&cmd=ajaxcheckExistingItem&itemcode="+itemcode;
	  	
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
	   		document.getElementById('uidmsg').innerHTML="<span style='color:#009900;font-weight:bold'>You can sale it</span>";
	   }else if(useridArr[0]==1){
		   	document.getElementById('uidmsg').innerHTML="<span style='color:#FF0000; font-weight:bold'>Already Soled, Try Another</span>";
			document.getElementById('itemcode').value= "";
			//document.getElementById('itemcode').focus(); 
		//	document.getElementById('itemcode').select(); 		

	   }
	}
	else{
		document.getElementById('uidmsg').innerHTML = "Checking Existence.Wait...";
		}
}
//=====End chkEmailExistence ======

function validate_4ItemIssue()
{
	valid = true;
     if ( document.getElementById('itemcode').value == "" )
        {
		        alert ( "Please enter item code" );
				itemcode.focus();
                valid = false;
        }
        return valid;

}

//ajax add edit delete Batch Info---------------------------------------------------------------
/*function ajaxCall4ItemAddEdit(){
	
	var salesprice = document.getElementById('salesprice').value;	
	var sales_id = document.getElementById('sales_id').value;	
	var discount_percent = document.getElementById('discount_percent').value;	
	
	var cmd = "?app=inv_item_sales&cmd=ajaxEditItemSales&sales_id="+sales_id+"&discount_percent="+discount_percent+"&salesprice="+salesprice;
alert (cmd);
	window.location.href=cmd;

}
*/
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}


function loadSalsManId(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showElement(id);
	//alert(id);
	var ele_id = 'customer_id';
	var ele_lbl_id = 'sls_name_lbl';
	var cmd='index.php?app=inv_item_sales_whole&cmd=ajaxSalesManLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item_sales_whole',cmd,'SlsList')
}

function addSalsManId(customer_id, ele_id, name, ele_lbl_id){
		 //alert(job_specialization_id+' - '+ele_id+' - '+job_specialization_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=customer_id;
		 document.getElementById(ele_lbl_id).innerHTML=name;
}

function DiscountChng(){
 var amount=1*(document.getElementById("amount").value);
 var totaldiscount=1*(document.getElementById("totaldiscount").value);
 
 document.getElementById("total_amount").value=(amount-totaldiscount);
 document.getElementById("paid_amount").value=(amount-totaldiscount);
}


function getModelId(main_item_category_id)
{ 

	//alert(main_item_category_id);
	  if(main_item_category_id!="")
	  {  var url = "?app=inv_item_sales_whole&cmd=ajaxModel&main_item_category_id="+main_item_category_id;
	  	//alert(url);
	  	  ajax.open("GET", url, true);
		  ajax.onreadystatechange = getModelIdReply4Cat;
		  ajax.send(null);
	  }
}


function getModelIdReply4Cat() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('catModel').innerHTML=response;
	}
}
