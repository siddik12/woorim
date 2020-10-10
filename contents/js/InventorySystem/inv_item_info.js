var mode = 'add';

function validate_4ItemType()
{
	valid = true;
      if ( document.getElementById('item_name').value == "" )
        {
		        alert ( "Item name will not be empty" );
				item_name.focus();
                valid = false;
        }
		else if ( document.getElementById('sub_item_category_id').value == "" )
        {
		        alert ( "Item Category will not be empty" );
				sub_item_category_id.focus();
                valid = false;
        }
		else if ( document.getElementById('item_code').value == "" )
        {
		        alert ( "Item code will not be empty" );
				item_code.focus();
                valid = false;
        }
		else if ( document.getElementById('quantity').value == "" )
        {
		        alert ( "Item quantity will not be empty" );
				quantity.focus();
                valid = false;
        }
		else if ( document.getElementById('cost_price').value == "" )
        {
		        alert ( "Item cost price will not be empty" );
				cost_price.focus();
                valid = false;
        }
		else if ( document.getElementById('sales_price').value == "" )
        {
		        alert ( "Item sales price will not be empty" );
				sales_price.focus();
                valid = false;
        }
		else if ( document.getElementById('whole_sales_price').value == "" )
        {
		        alert ( "Item Whole sales price will not be empty" );
				whole_sales_price.focus();
                valid = false;
        }
		else if ( document.getElementById('supplier_ID').value == "" )
        {
		        alert ( "Supplier will not be empty" );
				supplier_ID.focus();
                valid = false;
        }
		else if ( document.getElementById('branch_id').value == "" )
        {
		        alert ( "Branch will not be empty" );
				branch_id.focus();
                valid = false;
        }
		else if ( document.getElementById('item_size').value == "" )
        {
		        alert ( "Item Size will not be empty" );
				item_size.focus();
                valid = false;
        }
		else if ( document.getElementById('challan_no').value == "" )
        {
		        alert ( "Challan no will not be empty" );
				challan_no.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Received date will not be empty" );
				receive_date.focus();
                valid = false;
        }
		else{

        return valid;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4ItemTypeAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var item_name = document.getElementById('item_name').value;	
	var item_code = document.getElementById('item_code').value;	
	var quantity = document.getElementById('quantity').value;	
	var cost_price = document.getElementById('cost_price').value;	
	var sales_price = document.getElementById('sales_price').value;	
	var whole_sales_price = document.getElementById('whole_sales_price').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var supplier_ID = document.getElementById('supplier_ID').value;	
	var branch_id = document.getElementById('branch_id').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var sub_item_category_id = document.getElementById('sub_item_category_id').value;	
	var item_size = document.getElementById('item_size').value;	
	var description = document.getElementById('description').value;	
	var cmd = "ajaxInsertItemInfo&item_name="+item_name+"&sub_item_category_id="+sub_item_category_id+"&item_size="+item_size+"&description="+description+"&item_code="+item_code+"&quantity="+quantity+"&cost_price="+cost_price+"&sales_price="+sales_price+"&whole_sales_price="+whole_sales_price+"&challan_no="+challan_no+"&supplier_ID="+supplier_ID+"&branch_id="+branch_id+"&receive_date="+receive_date;
	//document.getElementById('item_name').value ='';	
	//document.getElementById('sub_item_category_id').value='';	
	document.getElementById('item_size').value='';	
	document.getElementById('description').value='';	

	}
	if (mode == "edit"){
	var item_id = document.getElementById('item_id').value;	
	var item_name = document.getElementById('item_name').value;	
	var item_code = document.getElementById('item_code').value;	
	var quantity = document.getElementById('quantity').value;	
	var cost_price = document.getElementById('cost_price').value;	
	var sales_price = document.getElementById('sales_price').value;	
	var whole_sales_price = document.getElementById('whole_sales_price').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var supplier_ID = document.getElementById('supplier_ID').value;	
	var branch_id = document.getElementById('branch_id').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var sub_item_category_id = document.getElementById('sub_item_category_id').value;	
	var item_size = document.getElementById('item_size').value;	
	var description = document.getElementById('description').value;	
	var cmd = "ajaxEditItemInfo&item_name="+item_name+"&sub_item_category_id="+sub_item_category_id+"&item_size="+item_size+"&description="+description+"&item_code="+item_code+"&quantity="+quantity+"&cost_price="+cost_price+"&sales_price="+sales_price+"&whole_sales_price="+whole_sales_price+"&challan_no="+challan_no+"&supplier_ID="+supplier_ID+"&branch_id="+branch_id+"&receive_date="+receive_date+"&item_id="+item_id;
	document.getElementById('item_id').value='';	
	document.getElementById('item_name').value='';	
	document.getElementById('item_code').value ='';	
	document.getElementById('quantity').value ='';	
	document.getElementById('cost_price').value ='';	
	document.getElementById('sales_price').value ='';	
	document.getElementById('whole_sales_price').value ='';	
	document.getElementById('challan_no').value ='';	
	document.getElementById('supplier_ID').value ='';	
	document.getElementById('branch_id').value ='';	
	document.getElementById('receive_date').value ='';	
	document.getElementById('sub_item_category_id').value='';	
	document.getElementById('item_size').value='';	
	document.getElementById('description').value='';	
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&item_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	
	ajaxCall('inv_item_info',cmd,'ItemTypeDiv');

}

function ajaxCall4EditItem(item_id,sub_item_category_id, item_name,item_code,quantity,cost_price,sales_price,whole_sales_price,supplier_ID,branch_id,challan_no,receive_date, item_size, description){
	document.getElementById('item_id').value = item_id;
	document.getElementById('sub_item_category_id').value = sub_item_category_id;
	document.getElementById('item_name').value = item_name;
	document.getElementById('item_code').value = item_code;	
	document.getElementById('quantity').value = quantity;	
	document.getElementById('cost_price').value = cost_price;	
	document.getElementById('sales_price').value = sales_price;	
	document.getElementById('whole_sales_price').value = whole_sales_price;	
	document.getElementById('supplier_ID').value = supplier_ID;	
	document.getElementById('branch_id').value = branch_id;	
	document.getElementById('challan_no').value = challan_no;	
	document.getElementById('receive_date').value = receive_date;	
	document.getElementById('item_size').value = item_size;	
	document.getElementById('description').value = description;	
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteItem(id){
	//alert(id);
	mode = 'del';
	ajaxCall4ItemTypeAddEdit(id);
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
