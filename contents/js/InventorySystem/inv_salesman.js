var mode = 'add';

/*----------------------------Update-------------------*/
function updateSalesmanInfo(slman_id,name,contact_no)
	{
	document.getElementById('slman_id').value = slman_id;
	document.getElementById('name').value = name;
	document.getElementById('contact_no').value = contact_no;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form( )
{
	valid = true;
     if ( document.salesman_form.name.value == ""   )
        {
		        alert ( "Please enter name." );
				name.focus();
                valid = false;
        }
		else if ( document.salesman_form.contact_no.value == ""   )
        {
		        alert ( "Please write Contact No." );
				contact_no.focus();
                valid = false;
        }
        return valid;
}
