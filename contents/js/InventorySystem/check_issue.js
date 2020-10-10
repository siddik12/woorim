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
     else if ( document.getElementById('cr').value == "" )
        {
		        alert ( "Enter amount" );
				cr.focus();
                valid = false;
        }
     else if ( document.getElementById('supplier_ID').value == "" )
        {
		        alert ( "Please select Supplier" );
				supplier_ID.focus();
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
	var cr = document.getElementById('cr').value;	
	var supplier_ID = document.getElementById('supplier_ID').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxInsertCheck&particulars="+particulars+"&cr="+cr+"&supplier_ID="+supplier_ID+"&receive_date="+receive_date;
	document.getElementById('particulars').value='';
	document.getElementById('cr').value='';
	document.getElementById('receive_date').value='';

	}
	if (mode == "edit"){
	var glid = document.getElementById('glid').value;
	var particulars = document.getElementById('particulars').value;
	var cr = document.getElementById('cr').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var cmd = "ajaxEditCheck&glid="+glid+"&particulars="+particulars+"&cr="+cr+"&receive_date="+receive_date;
	document.getElementById('glid').value='';
	document.getElementById('particulars').value='';
	document.getElementById('cr').value='';
	document.getElementById('receive_date').value='';
	}
	mode = 'add';
	//alert(cmd);
	ajaxCall('accounts',cmd,'chkInfo');

}

function ajaxCall4EditCheck(glid, particulars, cr, receive_date){
	document.getElementById('glid').value = glid;
	document.getElementById('particulars').value = particulars;
	document.getElementById('cr').value = cr;	
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
