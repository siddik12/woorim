var mode = 'add';

/*----------------------------Update-------------------*/
function updateCustomerInfo(customer_id,name,member_date,email,mobile)
	{
	document.getElementById('customer_id').value = customer_id;
	document.getElementById('name').value = name;
	document.getElementById('member_date').value = member_date;
	document.getElementById('email').value = email;
	document.getElementById('mobile').value = mobile;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form()
{
	//valid = true;
     if ( document.customer_form.name.value == "" && document.customer_form.mobile.value == "" && document.customer_form.member_date.value == "")
        {
		        alert ( "Customer Name, Mobile Number & Membership Date will not be empty!" );
                return false;
        }
		else{
        return true;
		}
		
}
