var mode = 'add';

function validate_4BillEntry()
{
	valid = true;
     if ( document.getElementById('slman_id').value == "" )
        {
		        alert( "Select SR Name" );
				slman_id.focus();
                valid = false;
        }
     else if ( document.getElementById('due_amount').value == "" )
        {
		        alert( "Enter Collection amount" );
				due_amount.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert( "Select Received Date" );
				receive_date.focus();
                valid = false;
        }
        else{

        return valid;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4SRCollectEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var slman_id = document.getElementById('slman_id').value;	
	var due_amount = document.getElementById('due_amount').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInserCollection&slman_id="+slman_id+"&receive_date="+receive_date+"&due_amount="+due_amount;
	document.getElementById('slman_id').value='';
	document.getElementById('due_amount').value='';
	document.getElementById('due_amount').value='0.00';

	}
	if (mode == "edit"){
	var accid = document.getElementById('accid').value;
	var slman_id = document.getElementById('slman_id').value;
	var due_amount = document.getElementById('due_amount').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditSRCollect&accid="+accid+"&slman_id="+slman_id+"&due_amount="+due_amount+"&receive_date="+receive_date;
	document.getElementById('accid').value='';
	document.getElementById('slman_id').value='';
	document.getElementById('due_amount').value='';
	document.getElementById('due_amount').value='0.00';
	}

/*	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&itemtypeid="+id;
	}
*/	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'billInfo');

}

function ajaxCall4EditSRCollect(accid, slman_id, due_amount, receive_date){
	document.getElementById('accid').value = accid;
	document.getElementById('slman_id').value = slman_id;
	document.getElementById('due_amount').value = due_amount;	
	document.getElementById('receive_date').value = receive_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteSRCollect(id){
	//alert(id);
	mode = 'del';
	ajaxCall4ItemTypeAddEdit(id);
}
function confirmChangeStatus()
{
    return confirm("Are you sure you want to change the status?");
}





//================== Pending bill add to Ledger =======================================
function ajaxCall4Addtoledger(id){
	//alert(mode);
	var bill_type = document.getElementById('bill_type').value;	
	var cr = document.getElementById('cr').value;	
	var particulars = document.getElementById('particulars').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxAddtoLedger&bill_type="+bill_type+"&cr="+cr+"&particulars="+particulars+"&receive_date="+receive_date;
/*	document.getElementById('bid').value='';
	document.getElementById('bill_amount').value='';
	document.getElementById('bill_isuu_date').value='';
*/

	alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'billInfo');

}
