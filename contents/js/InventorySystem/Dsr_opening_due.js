var mode = 'add';

function validate_4BillEntry()
{
	valid = true;
     if ( document.getElementById('slman_id').value == "" )
        {
		        alert( "Select DSR Name" );
				slman_id.focus();
                valid = false;
        }
/*     else if ( document.getElementById('receive_amount').value == "" )
        {
		        alert( "Enter Receive amount" );
				receive_amount.focus();
                valid = false;
        }
*/		else if ( document.getElementById('receive_date').value == "" )
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
function ajaxCall4DSROpDueEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var slman_id = document.getElementById('slman_id').value;	
	var opening_dues = document.getElementById('opening_dues').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInserOpenDues&slman_id="+slman_id+"&opening_dues="+opening_dues+"&receive_date="+receive_date;
	document.getElementById('slman_id').value='';
	document.getElementById('opening_dues').value='0.00';
	document.getElementById('receive_date').value='';

	}
	if (mode == "edit"){
	var accid = document.getElementById('accid').value;
	var slman_id = document.getElementById('slman_id').value;	
	var opening_dues = document.getElementById('opening_dues').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditOpenDues&accid="+accid+"&slman_id="+slman_id+"&opening_dues="+opening_dues+"&receive_date="+receive_date;
	document.getElementById('accid').value='';
	document.getElementById('slman_id').value='';
	document.getElementById('opening_dues').value='0.00';
	document.getElementById('receive_date').value='';
	}

/*	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&itemtypeid="+id;
	}
*/	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('dsr_collection',cmd,'OpDueInfo');

}

function ajaxCall4EditDSROpDue(accid, slman_id, opening_dues, receive_date){
	document.getElementById('accid').value = accid;
	document.getElementById('slman_id').value = slman_id;
	document.getElementById('opening_dues').value = opening_dues;	
	document.getElementById('receive_date').value = receive_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
	//ajaxCall4BatchInfoAddEdit();
}
function confirmDelete()
{
    return confirm("Are you sure you want to delete this entry?");
}

