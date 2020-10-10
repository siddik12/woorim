
function validate_4Enterchk()
{
	valid = true;
     if ( document.getElementById('supplier_id').value == "" )
        {
		        alert ( "Please select Supplier" );
				supplier_id.focus();
                valid = false;
        }
     else if ( document.getElementById('pay_mode').value == "" )
        {
		        alert ( "select pay mode" );
                valid = false;
        }
     else if ( document.getElementById('cr').value == "" )
        {
		        alert ( "Enter amount" );
				cr.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Select Receive Date" );
				receive_date.focus();
                valid = false;
        }
        else{

        return valid;
		}
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
