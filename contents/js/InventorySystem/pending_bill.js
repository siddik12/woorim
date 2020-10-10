var mode = 'add';

function validate_4BillEntry()
{
	valid = true;
     if ( document.getElementById('bid').value == "" )
        {
		        alert ( "Enter Bill Type" );
				bid.focus();
                valid = false;
        }
     else if ( document.getElementById('month_id').value == "" )
        {
		        alert ( "select Month" );
				month_id.focus();
                valid = false;
        }
     else if ( document.getElementById('bill_amount').value == "" )
        {
		        alert ( "Enter amount" );
				bill_amount.focus();
                valid = false;
        }
		else if ( document.getElementById('bill_isuu_date').value == "" )
        {
		        alert ( "Select Submit Date" );
				bill_isuu_date.focus();
                valid = false;
        }
        else{

        return valid;
		}
}

function validate_4Billpending()
{
	valid = true;
     if ( document.getElementById('cr').value == "" )
        {
		        alert ( "Enter Issue Amount" );
				cr.focus();
                valid = false;
        }
     else if ( document.getElementById('particulars').value == "" )
        {
		        alert ( "Enter Tracking id" );
				particulars.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Select Issue Date" );
				receive_date.focus();
                valid = false;
        }
        else{

        return valid;
		}
}


//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4BillEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var bid = document.getElementById('bid').value;	
	var month_id = document.getElementById('month_id').value;	
	var bill_amount = document.getElementById('bill_amount').value;	
	var bill_isuu_date = document.getElementById('bill_isuu_date').value;	
	var cmd = "ajaxInsertBill&bid="+bid+"&month_id="+month_id+"&bill_amount="+bill_amount+"&bill_isuu_date="+bill_isuu_date;
	document.getElementById('bid').value='';
	document.getElementById('month_id').value='';
	document.getElementById('bill_amount').value='';
	document.getElementById('bill_isuu_date').value='';

	}
	if (mode == "edit"){
	var pending_id2 = document.getElementById('pending_id2').value;
	var bid = document.getElementById('bid').value;
	var month_id = document.getElementById('month_id').value;
	var bill_amount = document.getElementById('bill_amount').value;	
	var bill_isuu_date = document.getElementById('bill_isuu_date').value;	
	var cmd = "ajaxEditPendingBill&pending_id2="+pending_id2+"&bid="+bid+"&month_id="+month_id+"&bill_amount="+bill_amount+"&bill_isuu_date="+bill_isuu_date;
	document.getElementById('pending_id2').value='';
	document.getElementById('month_id').value='';
	document.getElementById('bid').value='';
	document.getElementById('bill_amount').value='';
	document.getElementById('bill_isuu_date').value='';
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteBill&pending_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'billInfo');

}

function ajaxCall4EditBill(pending_id, bid, month_id, bill_amount, bill_isuu_date){
	document.getElementById('pending_id2').value = pending_id;
	document.getElementById('bid').value = bid;
	document.getElementById('month_id').value = month_id;
	document.getElementById('bill_amount').value = bill_amount;
	document.getElementById('bill_isuu_date').value = bill_isuu_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteBill(id){
	//alert(id);
	mode = 'del';
	//alert(mode);
	ajaxCall4BillEntry(id);
}



function confirmChangeStatus()
{
    return confirm("Are you sure you want to change the status?");
}

function confirmDelete()
{
    return confirm("Are you sure you want to delete?");
}




//================== Pending bill add to Ledger =======================================
function ajaxCall4Addtoledger(id){
	//alert(mode);
	var pending_id1 = document.getElementById('pending_id1').value;	
	var bill_type = document.getElementById('bill_type').value;	
	var cr = document.getElementById('cr').value;	
	var particulars = document.getElementById('particulars').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxAddtoLedger&bill_type="+bill_type+"&cr="+cr+"&particulars="+particulars+"&pending_id1="+pending_id1+"&receive_date="+receive_date;
/*	document.getElementById('bid').value='';
	document.getElementById('bill_amount').value='';
	document.getElementById('bill_isuu_date').value='';
*/

	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'billInfo');

}
