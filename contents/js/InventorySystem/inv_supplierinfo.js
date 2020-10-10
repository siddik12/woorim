var mode = 'add';

/*----------------------------Update-------------------*/
function updateSupplierInfo(supplier_ID,company_name,contact_person,address,email,mobile,acc_no)
	{
	document.getElementById('supplier_ID').value = supplier_ID;
	document.getElementById('company_name').value = company_name;
	document.getElementById('contact_person').value = contact_person;
	document.getElementById('address').value = address;
	document.getElementById('email').value = email;
	document.getElementById('mobile').value = mobile;
	document.getElementById('acc_no').value = acc_no;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form( )
{
	valid = true;
     if ( document.supplier_form.company_name.value == ""   )
        {
		        alert ( "Company Name will not be empty!" );
				company_name.focus();
                valid = false;
        }
		else if ( document.supplier_form.address.value == ""   )
        {
		        alert ( "Address will not be empty!" );
				address.focus();
                valid = false;
        }
		else if ( document.supplier_form.mobile.value == ""   )
        {
		        alert ( "Mobile Number will not be empty!" );
				mobile.focus();
                valid = false;
        }
		
        return valid;
}
