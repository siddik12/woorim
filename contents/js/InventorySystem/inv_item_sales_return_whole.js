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

 if (document.complfrm.customer_id.value==''){
		alert("Please select customer");
		return false;
	}else if (document.complfrm.total_amount.value==''){
		alert("Please Enter an Item Code");
		return false;
	}else if (document.complfrm.adjust_amount.value==''){
		alert("Please Enter adjusted amount");
		return false;
	}else if(document.complfrm.pay_type.value=='Bank'){
		if(document.complfrm.bankid.value==''){
			alert("Please select Bank");
			return false;
		}else{
		return true;	
		}
		
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
	  {  var url = "?app=inv_item_sales_return_whole&cmd=ajaxcheckExistingItem&itemcode="+itemcode;
	  	
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
	var cmd='index.php?app=inv_item_sales_return_whole&cmd=ajaxSalesManLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
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
 var adjust_amount=1*(document.getElementById("adjust_amount").value);
 
 document.getElementById("total_amount").value=(amount-totaldiscount);
 document.getElementById("paid_amount").value=(amount-totaldiscount-adjust_amount);
}
