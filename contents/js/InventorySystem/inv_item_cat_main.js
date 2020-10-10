var mode = 'add';

function validate_4Category()
{
	valid = true;
     if ( document.getElementById('main_item_category_name').value == ""   )
        {
		        alert ( "Category name will not be empty" );
				main_item_category_name.focus();
                valid = false;
        }
        return valid;
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4CategoryAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var main_item_category_name = document.getElementById('main_item_category_name').value;	
	var cmd = "ajaxInsertCategory&main_item_category_name="+main_item_category_name;
	document.getElementById('main_item_category_name').value='';

	}
	if (mode == 'edit'){
	var main_item_category_id = document.getElementById('main_item_category_id').value;
	var main_item_category_name = document.getElementById('main_item_category_name').value;
	
	var cmd = "ajaxEditCategory&main_item_category_id="+main_item_category_id+"&main_item_category_name="+main_item_category_name;
	document.getElementById('main_item_category_id').value='';
	document.getElementById('main_item_category_name').value='';
	
	
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteCategory&main_item_category_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	ajaxCall('inv_item_cat_main',cmd,'CategoryDiv');

}

function ajaxCall4EditCatagory(main_item_category_id, main_item_category_name){
	document.getElementById('main_item_category_id').value = main_item_category_id;
	document.getElementById('main_item_category_name').value = main_item_category_name;
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteCategory(id){
	//alert(id);
	mode = 'del';
	document.getElementById('main_item_category_id').value='';
	document.getElementById('main_item_category_name').value='';
	ajaxCall4CategoryAddEdit(id);
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
