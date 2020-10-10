var mode = 'add';

function validate_4BillEntry()
{
	
     if ( document.getElementById('person_id').value == "" )
        {
		        alert( "Select SR Name" );
				person_id.focus();
                return false;
        } else if ( document.getElementById('customer_id').value == "" )
        {
		        alert( "Select Shop Name" );
				customer_id.focus();
                return false;
        }
		else if ( document.getElementById('dr_amount').value == "" )
        {
		        alert( "Enter Opening Dues amount" );
				dr_amount.focus();
                return false;
        }else if ( document.getElementById('paid_date').value == "" )
        {
		        alert( "Select Received Date" );
				paid_date.focus();
                return false;
        }
        else{

        return true;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4CollectEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var person_id = document.getElementById('person_id').value;	
	var customer_id = document.getElementById('customer_id').value;	
	var dr_amount = document.getElementById('dr_amount').value;	
	var particulars = document.getElementById('particulars').value;	
	var paid_date = document.getElementById('paid_date').value;	
	var project = document.getElementById('project').value;	
	var cmd = "ajaxInserOpeningDues&customer_id="+customer_id+"&person_id="+person_id+"&paid_date="+paid_date+"&dr_amount="+dr_amount+"&particulars="+particulars;
	document.getElementById('customer_id').value='';
	document.getElementById('person_id').value='';
	document.getElementById('dr_amount').value='';
	document.getElementById('project').value='';
	document.getElementById('dr_amount').value='0.00';

	}
	if (mode == "edit"){
	var whole_sales_accid = document.getElementById('whole_sales_accid').value;
	var person_id = document.getElementById('person_id').value;	
	var customer_id = document.getElementById('customer_id').value;	
	var dr_amount = document.getElementById('dr_amount').value;	
	var particulars = document.getElementById('particulars').value;	
	var paid_date = document.getElementById('paid_date').value;	
	var project = document.getElementById('project').value;	
	var cmd = "ajaxEditOpeningDues&whole_sales_accid="+whole_sales_accid+"&customer_id="+customer_id+"&person_id="+person_id+"&paid_date="+paid_date+"&dr_amount="+dr_amount+"&particulars="+particulars;
	document.getElementById('whole_sales_accid').value='';
	document.getElementById('customer_id').value='';
	document.getElementById('person_id').value='';
	document.getElementById('dr_amount').value='';
	document.getElementById('project').value='';
	document.getElementById('dr_amount').value='0.00';
	}

/*	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&itemtypeid="+id;
	}
*/	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('accounts_whole',cmd,'collection');

}

function ajaxCall4EditCollect(whole_sales_accid, customer_id, dr_amount,particulars,paid_date){
	document.getElementById('whole_sales_accid').value = whole_sales_accid;
	document.getElementById('customer_id').value = customer_id;
	document.getElementById('dr_amount').value = dr_amount;	
	document.getElementById('particulars').value = particulars;	
	document.getElementById('paid_date').value = paid_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteCollect(id){
	//alert(id);
	mode = 'del';
	ajaxCall4ItemTypeAddEdit(id);
}
function confirmChangeStatus()
{
    return confirm("Are you sure you want to change the status?");
}



function getSRId(person_id)
{ 

	//alert(person_id);
	  if(person_id!="")
	  {  var url = "?app=accounts_whole&cmd=ajaxSR&person_id="+person_id;
	  //	alert(url);
	  	  ajax.open("GET", url, true);
		  ajax.onreadystatechange = getSRIdReply4Cat;
		  ajax.send(null);
	  }
}


function getSRIdReply4Cat() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('GetSR').innerHTML=response;
	}
}


function loadSalsManId(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showElement(id);
	//alert(id);
	var ele_id = 'person_id';
	var ele_lbl_id = 'sls_name_lbl';
	var cmd='?app=accounts_whole&cmd=ajaxSalesManLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('accounts_whole',cmd,'SlsList')
}

function addSalsManId(person_id, ele_id, name, ele_lbl_id){
		 //alert(job_specialization_id+' - '+ele_id+' - '+job_specialization_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=person_id;
		 document.getElementById(ele_lbl_id).innerHTML=name;
}
