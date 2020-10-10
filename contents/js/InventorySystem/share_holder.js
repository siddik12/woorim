var mode = 'add';

function validate_4Category()
{
	valid = true;
     if ( document.getElementById('holder_name').value == ""   )
        {
		        alert ( "Category name will not be empty" );
				holder_name.focus();
                valid = false;
        }
        return valid;
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4CategoryAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var holder_name = document.getElementById('holder_name').value;	
	var cmd = "ajaxInsertCategory&holder_name="+holder_name;
	document.getElementById('holder_name').value='';

	}
	if (mode == 'edit'){
	var holder_id = document.getElementById('holder_id').value;
	var holder_name = document.getElementById('holder_name').value;
	
	var cmd = "ajaxEditCategory&holder_id="+holder_id+"&holder_name="+holder_name;
	document.getElementById('holder_id').value='';
	document.getElementById('holder_name').value='';
	
	
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteCategory&holder_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	ajaxCall('share_holder',cmd,'CategoryDiv');

}

function ajaxCall4EditCatagory(holder_id, holder_name){
	document.getElementById('holder_id').value = holder_id;
	document.getElementById('holder_name').value = holder_name;
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteCategory(id){
	//alert(id);
	mode = 'del';
	document.getElementById('holder_id').value='';
	document.getElementById('holder_name').value='';
	ajaxCall4CategoryAddEdit(id);
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
