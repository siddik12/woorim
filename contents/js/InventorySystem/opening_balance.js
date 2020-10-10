var mode = 'add';

function validate_4Enterchk()
{
	valid = true;
     if ( document.getElementById('particulars').value == "" )
        {
		        alert ( "Enter Particular" );
				particulars.focus();
                valid = false;
        }
     else if ( document.getElementById('dr').value == "" )
        {
		        alert ( "Enter amount" );
				dr.focus();
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
function ajaxCall4EnterCheck(id){
	//alert(mode);
	if(mode == 'add'){
	var particulars = document.getElementById('particulars').value;	
	var dr = document.getElementById('dr').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInsertOpeningB&particulars="+particulars+"&dr="+dr+"&receive_date="+receive_date;
	document.getElementById('particulars').value='';
	document.getElementById('dr').value='';
	document.getElementById('receive_date').value='';

	}
	if (mode == "edit"){
	var glid = document.getElementById('glid').value;
	var particulars = document.getElementById('particulars').value;
	var dr = document.getElementById('dr').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditOpeningB&glid="+glid+"&particulars="+particulars+"&dr="+dr+"&receive_date="+receive_date;
	document.getElementById('glid').value='';
	document.getElementById('particulars').value='';
	document.getElementById('dr').value='';
	document.getElementById('receive_date').value='';
	}
	mode = 'add';
	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'chkInfo');

}

function ajaxCall4EditOpeningB(glid, particulars, dr, receive_date){
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
