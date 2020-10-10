var mode = 'add';

/*----------------------------Update-------------------*/
function updateCustomerInfo(customer_id,store_name,name,address,email,district_id,upzila,viber,mobile)
	{
	document.getElementById('store_name').value = store_name;
	document.getElementById('customer_id').value = customer_id;
	document.getElementById('name').value = name;
	document.getElementById('address').value = address;
	document.getElementById('email').value = email;
	document.getElementById('district_id').value = district_id;
	document.getElementById('upzila').value = upzila;
	document.getElementById('viber').value = viber;
	document.getElementById('mobile').value = mobile;
	var mode = 'edit';
	
	document.getElementById('mode').value = mode;
	}
/*------------End----------------------*/



function validate_form( )
{
	//valid = true;
     if ( document.customer_form2.store_name.value == "" )
        {
		        alert ( "Shop name will not be empty!" );
				store_name.focus();
                return false;
        }
		else if ( document.customer_form2.district_id.value == ""   )
        {
		        alert ( "District will not be empty!" );
				district_id.focus();
                return false;
        }
		else if ( document.customer_form2.mobile.value == ""   )
        {
		        alert ( "Mobile Number will not be empty!" );
				mobile.focus();
                return false;
        }else{
		
       	 return true;
		}
}
