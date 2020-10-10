var mode = 'add';

function validate_4Enterchk()
{
	valid = true;
     if ( document.getElementById('challan_no').value == "" )
        {
		        alert ( "Enter Challan no" );
				challan_no.focus();
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
	var challan_no = document.getElementById('challan_no').value;	
	var cmd = "ajaxInsertChallan&challan_no="+challan_no;
	document.getElementById('challan_no').value='';

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
	ajaxCall('accounts',cmd,'challanInfo');

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
