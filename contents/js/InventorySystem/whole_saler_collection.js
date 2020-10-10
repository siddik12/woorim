function loadPerson(){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showHideDiv();
	//alert(id);
	  
		var ele_id = 'customer_id';
		var ele_lbl_id = 'lbl_PersonName';
		var ele_lbl_id1 = 'Sabek_dues';
	   
	var url='index.php?app=accounts_whole&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id+'&ele_lbl_id1='+ele_lbl_id1;
	//alert(url);
	ajax.onreadystatechange=searchDataReply;
	ajax.open("GET",url,true);
	ajax.send(null);
}

function addPersonId(customer_id, ele_id, company_name, ele_lbl_id, Sabek_dues, ele_lbl_id1){
		// alert(customer_id+' - '+ele_id+' - '+company_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=customer_id;
		 document.getElementById(ele_lbl_id).innerHTML=company_name;
		 document.getElementById(ele_lbl_id1).value=Sabek_dues;
}

function searchDataPerson() {
	var ele_id = 'customer_id';
	var ele_lbl_id = 'lbl_PersonName';
	var ele_lbl_id1 = 'Sabek_dues';
	var searchq=(document.getElementById('search2').value);
	//alert(searchq);
	var url = 'index.php?app=accounts_whole&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id+'&ele_lbl_id1='+ele_lbl_id1+'&search='+searchq;
	//alert(url);
	ajax.open('get', url);
	ajax.onreadystatechange = searchDataReply;
	ajax.send(null);
}

function searchDataReply() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('PersonDropdown').innerHTML=response;
	}
}

function showHideDiv()
    {
		var PersonLookup = document.getElementById("PersonLookup");
		//alert(CommentatorLookUp.style.display);
        //var divstyle = new String();
        divstyle = PersonLookup.style.display;
        if(divstyle=="block")
        {
            PersonLookup.style.display = "none";
        }
        else
        {
            PersonLookup.style.display = "block";
        }
		document.getElementById('search2').value='';
		document.getElementById('search2').focus();
 		//document.getElementById('search').value='';
		//document.getElementById('search').focus();
   }


var mode = 'add';

function validate_4BillEntry()
{
	
     if ( document.getElementById('customer_id').value == "" )
        {
		        alert( "Select Customer" );
				customer_id.focus();
                return false;
        }
		else if ( document.getElementById('cr_amount').value == "" )
        {
		        alert( "Enter Collection amount" );
				cr_amount.focus();
                return false;
        }else if ( document.getElementById('paid_date').value == "" )
        {
		        alert( "Select Received Date" );
				paid_date.focus();
                return false;
        }else{
        	return true;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4CollectEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var person_id = document.getElementById('person_id').value;	
	var customer_id = document.getElementById('customer_id').value;	
	var cr_amount = document.getElementById('cr_amount').value;	
	var particulars = document.getElementById('particulars').value;	
	var pay_type = document.getElementById('pay_type').value;	
	var paid_date = document.getElementById('paid_date').value;
	var project = document.getElementById('project').value;	
	var cmd = "ajaxInserCollection&customer_id="+customer_id+"&person_id="+person_id+"&paid_date="+paid_date+"&cr_amount="+cr_amount+"&particulars="+particulars+"&pay_type="+pay_type+"&project="+project;
	document.getElementById('customer_id').value='';
	document.getElementById('person_id').value='';
	document.getElementById('project').value='';
	document.getElementById('cr_amount').value='';
	document.getElementById('cr_amount').value='0.00';

	}
	if (mode == "edit"){
	var whole_sales_accid = document.getElementById('whole_sales_accid').value;
	var person_id = document.getElementById('person_id').value;	
	var customer_id = document.getElementById('customer_id').value;	
	var cr_amount = document.getElementById('cr_amount').value;	
	var particulars = document.getElementById('particulars').value;	
	var pay_type = document.getElementById('pay_type').value;	
	var paid_date = document.getElementById('paid_date').value;
	var project = document.getElementById('project').value;	
	var cmd = "ajaxEditCollect&whole_sales_accid="+whole_sales_accid+"&customer_id="+customer_id+"&person_id="+person_id+"&paid_date="+paid_date+"&cr_amount="+cr_amount+"&particulars="+particulars+"&pay_type="+pay_type+"&project="+project;
	document.getElementById('whole_sales_accid').value='';
	document.getElementById('customer_id').value='';
	document.getElementById('person_id').value='';
	document.getElementById('project').value='';
	document.getElementById('cr_amount').value='';
	document.getElementById('cr_amount').value='0.00';
	}

/*	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&itemtypeid="+id;
	}
*/	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('accounts_whole',cmd,'collection');

}

function ajaxCall4EditCollect(whole_sales_accid, customer_id, cr_amount,particulars,pay_type,project,paid_date){
	document.getElementById('whole_sales_accid').value = whole_sales_accid;
	document.getElementById('customer_id').value = customer_id;
	document.getElementById('cr_amount').value = cr_amount;	
	document.getElementById('paid_date').value = paid_date;	
	document.getElementById('particulars').value = particulars;	
	document.getElementById('pay_type').value = pay_type;
	document.getElementById('project').value = project;
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
