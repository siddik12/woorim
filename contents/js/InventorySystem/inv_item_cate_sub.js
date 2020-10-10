var mode = 'add';

function validate_4Category()
{
	valid = true;
     if ( document.getElementById('sub_item_category_name').value == ""   )
        {
		        alert("Category name will not be empty" );
				sub_item_category_name.focus();
                valid = false;
        }
		else if (  document.getElementById('main_item_category_id').value == ""   )
        {
		        alert ( "Please select Main Item Category." );
				main_item_category_id.focus();
                valid = false;
        }
        return valid;
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4CategoryAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var sub_item_category_name = document.getElementById('sub_item_category_name').value;	
	var main_item_category_id = document.getElementById('main_item_category_id').value;
	var cmd = "ajaxInsertCategory&sub_item_category_name="+sub_item_category_name+"&main_item_category_id="+main_item_category_id;
	document.getElementById('sub_item_category_name').value='';
	document.getElementById('main_item_category_id').value='';

	}
	if (mode == 'edit'){
	var sub_item_category_id = document.getElementById('sub_item_category_id').value;
	var main_item_category_id = document.getElementById('main_item_category_id').value;
	var sub_item_category_name = document.getElementById('sub_item_category_name').value;
	
	var cmd = "ajaxEditCategory&sub_item_category_id="+sub_item_category_id+"&sub_item_category_name="+sub_item_category_name+"&main_item_category_id="+main_item_category_id;
	document.getElementById('sub_item_category_id').value='';
	document.getElementById('sub_item_category_name').value='';
	document.getElementById('main_item_category_id').value='';
	
	
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteCategory&sub_item_category_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	ajaxCall('inv_item_cat_sub',cmd,'CategoryDiv');

}

function ajaxCall4EditCatagory(sub_item_category_id,main_item_category_id, sub_item_category_name){
	document.getElementById('sub_item_category_id').value = sub_item_category_id;
	document.getElementById('main_item_category_id').value = main_item_category_id;
	document.getElementById('sub_item_category_name').value = sub_item_category_name;
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}

function ajaxCall4DeleteCategory(id){
	//alert(id);
	mode = 'del';
	document.getElementById('sub_item_category_id').value='';
	document.getElementById('sub_item_category_name').value='';
	ajaxCall4CategoryAddEdit(id);
}
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
