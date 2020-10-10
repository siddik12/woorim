var mode = 'add';

/*----------------------------Update-------------------*/
function updateSupplierInfo(invest_id,holder_id,branch_id,main_item_category_id,invdate,expense)
	{
		//alert(expense);
	document.getElementById('invest_id').value = invest_id;
	document.getElementById('holder_id').value = holder_id;
	document.getElementById('branch_id').value = branch_id;
	document.getElementById('main_item_category_id').value = main_item_category_id;
	document.getElementById('invdate').value = invdate;
	document.getElementById('expense').value = expense;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form( )
{
	valid = true;
     if ( document.supplier_form.holder_id.value == ""   )
        {
		        alert ( "Share Holde Name will not be empty!" );
				holder_id.focus();
                valid = false;
        }
		else if ( document.supplier_form.branch_id.value == ""   )
        {
		        alert ( "Shop Name will not be empty!" );
				branch_id.focus();
                valid = false;
        }
		else if ( document.supplier_form.main_item_category_id.value == ""   )
        {
		        alert ( "Brand Name will not be empty!" );
				main_item_category_id.focus();
                valid = false;
        }else if ( document.supplier_form.expense.value == ""   )
        {
		        alert ( "expense Amount will not be empty!" );
				expense.focus();
                valid = false;
        }
		else if ( document.supplier_form.invdate.value == ""   )
        {
		        alert ( "Date will not be empty!" );
				invdate.focus();
                valid = false;
        }
        return valid;
}
