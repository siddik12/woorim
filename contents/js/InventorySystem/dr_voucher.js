var mode = 'add';

function validate_4DR()
{
	valid = true;
     if ( document.getElementById('particulars').value == "" )
        {
		        alert ( "Enter Dr. Particular" );
				particulars.focus();
                valid = false;
        }
     else if ( document.getElementById('dr').value == "" )
        {
		        alert ( "Enter Dr. Amount" );
				cr.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Select Receive Date" );
				receive_date.focus();
                valid = false;
        }
        else{

        return valid;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4EnterDR(id){
	//alert(mode);
	if(mode == 'add'){
	var particulars = document.getElementById('particulars').value;	
	var dr = document.getElementById('dr').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInsertDrVoucher&particulars="+particulars+"&dr="+dr+"&receive_date="+receive_date;
	document.getElementById('particulars').value='';
	document.getElementById('dr').value='';
	document.getElementById('receive_date').value='';

	}
	if (mode == "edit"){
	var glid = document.getElementById('glid').value;
	var particulars = document.getElementById('particulars').value;
	var dr = document.getElementById('dr').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditCheck&glid="+glid+"&particulars="+particulars+"&dr="+dr+"&receive_date="+receive_date;
	document.getElementById('glid').value='';
	document.getElementById('particulars').value='';
	document.getElementById('dr').value='';
	document.getElementById('receive_date').value='';
	}
	mode = 'add';
	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'drVoucher');

}

function ajaxCall4EditDR(glid, particulars, dr, receive_date){
	document.getElementById('glid').value = glid;
	document.getElementById('particulars').value = particulars;
	document.getElementById('dr').value = dr;	
	document.getElementById('receive_date').value = receive_date;	
	mode = 'edit';
	document.getElementById('mode').value=mode
}
/*function ajaxCall4DeleteItemType(id){
	alert(id);
	mode = 'del';
	ajaxCall4ItemTypeAddEdit(id);
}
*/function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
