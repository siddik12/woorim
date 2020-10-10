var mode = 'add';
/*----------------delete------------------*/
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
/*--------------------------End--------------------*/

/*----------------------------Update-------------------*/
function updateSupplierInfo(bid,type_name)
	{
	document.getElementById('bid').value = bid;
	document.getElementById('type_name').value = type_name;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form( )
{
	valid = true;
     if ( document.supplier_form.type_name.value == ""   )
        {
		        alert ( "Bill Type will not be empty!" );
				type_name.focus();
                valid = false;
        }
        return valid;
}
