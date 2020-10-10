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
function ajaxCall4DSRcollEntry(id){
	//alert(mode);
	if(mode == 'add'){
	var slman_id = document.getElementById('slman_id').value;	
	var credit_colect = document.getElementById('credit_colect').value;	
	var credit = document.getElementById('credit').value;	
	var others = document.getElementById('others').value;	
	var prp = document.getElementById('prp').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInserCollection&slman_id="+slman_id+"&credit_colect="+credit_colect+"&receive_date="+receive_date+"&credit="+credit+"&others="+others+"&prp="+prp;
	document.getElementById('slman_id').value='';
	document.getElementById('credit_colect').value='0.00';
	document.getElementById('credit').value='0.00';
	document.getElementById('others').value='0.00';
	document.getElementById('prp').value='0.00';
	document.getElementById('receive_date').value='';

	}
	if (mode == "edit"){
	var sr_accid = document.getElementById('sr_accid').value;
	var slman_id = document.getElementById('slman_id').value;	
	var credit_colect = document.getElementById('credit_colect').value;	
	var credit = document.getElementById('credit').value;	
	var others = document.getElementById('others').value;	
	var prp = document.getElementById('prp').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditCollection&sr_accid="+sr_accid+"&slman_id="+slman_id+"&credit_colect="+credit_colect+"&receive_date="+receive_date+"&credit="+credit+"&others="+others+"&prp="+prp;
	document.getElementById('sr_accid').value='';
	document.getElementById('slman_id').value='';
	document.getElementById('credit_colect').value='0.00';
	document.getElementById('credit').value='0.00';
	document.getElementById('others').value='0.00';
	document.getElementById('prp').value='0.00';
	document.getElementById('receive_date').value='';
	}

/*	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&itemtypeid="+id;
	}
*/	mode = 'add';
	document.getElementById('mode').value=mode
	//alert(cmd);
	ajaxCall('dsr_collection',cmd,'collInfo');

}

function ajaxCall4EditDSRColl(sr_accid, slman_id, credit_colect, credit, others, prp, receive_date){
	document.getElementById('sr_accid').value = sr_accid;
	document.getElementById('slman_id').value = slman_id;
	document.getElementById('credit_colect').value = credit_colect;	
	document.getElementById('credit').value = credit;	
	document.getElementById('others').value = others;	
	document.getElementById('prp').value = prp;	
	document.getElementById('receive_date').value = receive_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
	//ajaxCall4BatchInfoAddEdit();
}
function confirmDelete()
{
    return confirm("Are you sure you want to delete this entry?");
}

