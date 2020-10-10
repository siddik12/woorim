var mode = 'add';

function validate_4ItemType()
{
	//valid = true;
		 if ( document.getElementById('sub_item_category_id').value == "" )
        {
		        alert ( "Item Category will not be empty" );
				sub_item_category_id.focus();
                return false;
        }
		
		else if ( document.getElementById('quantity').value == "" )
        {
		        alert ( "Item quantity will not be empty" );
				quantity.focus();
                return false;
        }
		else if ( document.getElementById('cost_price').value == "" )
        {
		        alert ( "Item cost price will not be empty" );
				cost_price.focus();
               return false;
        }
/*		else if ( document.getElementById('cost_exp').value == "" )
        {
		        alert ( "Item expense will not be empty" );
				cost_exp.focus();
                valid = false;
        }
*/		else if ( document.getElementById('sales_price').value == "" )
        {
		        alert ( "Item sales price will not be empty" );
				sales_price.focus();
                return false;
        }
		else if ( document.getElementById('whole_sales_price').value == "" )
        {
		        alert ( "Item Whole sales price will not be empty" );
				whole_sales_price.focus();
                return false;
        }
		else if ( document.getElementById('supplier_id').value == "" )
        {
		        alert ( "Supplier will not be empty" );
				supplier_id.focus();
                return false;
        }
		else if ( document.getElementById('item_size').value == "" )
        {
		        alert ( "Barcode will not be empty" );
				item_size.focus();
                return false;
        }
		else if ( document.getElementById('challan_no').value == "" )
        {
		        alert ( "Item challan no will not be empty" );
				challan_no.focus();
                return false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Received date will not be empty" );
				receive_date.focus();
               return false;
        }
		else if ( document.getElementById('branch_id').value == "" )
        {
		        alert ( "Branch date will not be empty" );
				branch_id.focus();
                return false;
        }
		else{

        return true;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4ItemTypeAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var main_item_category_id = document.getElementById('main_item_category_id').value;	
	var quantity = document.getElementById('quantity').value;	
	var cost_price = document.getElementById('cost_price').value;	
	var cost_exp = document.getElementById('cost_exp').value;	
	var sales_price = document.getElementById('sales_price').value;	
	var whole_sales_price = document.getElementById('whole_sales_price').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var supplier_id = document.getElementById('supplier_id').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var sub_item_category_id = document.getElementById('sub_item_category_id').value;	
	var item_size = document.getElementById('item_size').value;	
	var branch_id = document.getElementById('branch_id').value;	
	var cmd = "ajaxInsertItemInfo&sub_item_category_id="+sub_item_category_id+"&item_size="+item_size+"&branch_id="+branch_id+"&main_item_category_id="+main_item_category_id+"&quantity="+quantity+"&cost_price="+cost_price+"&cost_exp="+cost_exp+"&sales_price="+sales_price+"&whole_sales_price="+whole_sales_price+"&challan_no="+challan_no+"&supplier_id="+supplier_id+"&receive_date="+receive_date;
	document.getElementById('item_size').value ='';	
	//document.getElementById('sub_item_category_id').value='';	
	//document.getElementById('sub_item_category_id').value='';	
	//document.getElementById('item_size').value='';	
	//document.getElementById('description').value='';	

	}
	if (mode == "edit"){
	var ware_house_id = document.getElementById('ware_house_id').value;	
	var sub_item_category_id = document.getElementById('sub_item_category_id').value;	
	var main_item_category_id = document.getElementById('main_item_category_id').value;	
	var quantity = document.getElementById('quantity').value;	
	var cost_price = document.getElementById('cost_price').value;	
	var cost_exp = document.getElementById('cost_exp').value;	
	var sales_price = document.getElementById('sales_price').value;	
	var whole_sales_price = document.getElementById('whole_sales_price').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var supplier_id = document.getElementById('supplier_id').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var item_size = document.getElementById('item_size').value;	
	var branch_id = document.getElementById('branch_id').value;	
	var cmd = "ajaxEditItemInfo&sub_item_category_id="+sub_item_category_id+"&item_size="+item_size+"&branch_id="+branch_id+"&main_item_category_id="+main_item_category_id+"&quantity="+quantity+"&cost_price="+cost_price+"&cost_exp="+cost_exp+"&sales_price="+sales_price+"&whole_sales_price="+whole_sales_price+"&challan_no="+challan_no+"&supplier_id="+supplier_id+"&receive_date="+receive_date+"&ware_house_id="+ware_house_id;
	document.getElementById('ware_house_id').value='';	
	document.getElementById('main_item_category_id').value ='';	
	document.getElementById('quantity').value ='';	
	document.getElementById('cost_price').value ='';	
	//document.getElementById('cost_exp').value ='';	
	document.getElementById('sales_price').value ='';	
	document.getElementById('whole_sales_price').value ='';	
	document.getElementById('challan_no').value ='';	
	document.getElementById('supplier_id').value ='';	
	document.getElementById('receive_date').value ='';	
	document.getElementById('sub_item_category_id').value='';	
	document.getElementById('item_size').value='';	
	//document.getElementById('description').value='';	
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteItemInfo&ware_house_id="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	
	ajaxCall('godown_entry',cmd,'ItemTypeDiv');

}

function ajaxCall4EditItem(ware_house_id,sub_item_category_id, main_item_category_id,quantity,cost_price,cost_exp,sales_price,whole_sales_price,supplier_id,challan_no,receive_date){
	document.getElementById('ware_house_id').value = ware_house_id;
	document.getElementById('sub_item_category_id').value = sub_item_category_id;
	document.getElementById('main_item_category_id').value = main_item_category_id;	
	document.getElementById('quantity').value = quantity;	
	document.getElementById('cost_price').value = cost_price;	
	document.getElementById('cost_exp').value = cost_exp;	
	document.getElementById('sales_price').value = sales_price;	
	document.getElementById('whole_sales_price').value = whole_sales_price;	
	document.getElementById('supplier_id').value = supplier_id;	
	document.getElementById('challan_no').value = challan_no;	
	document.getElementById('receive_date').value = receive_date;	
	//document.getElementById('item_size').value = item_size;	
	//document.getElementById('branch_id').value = branch_id;	
	//mode = 'edit';
	//document.getElementById('mode').value = mode;
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
