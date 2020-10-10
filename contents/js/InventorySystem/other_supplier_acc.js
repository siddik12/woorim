function validate_4Enterchk()
{
	
     if ( document.getElementById('supplier_id').value == "" )
        {
		        alert ( "Please select Supplier" );
				supplier_id.focus();
                return false;
        }
     else if ( document.getElementById('pay_mode').value == "" )
        {
		        alert ( "select pay mode" );
                return false;
        }
     else if ( document.getElementById('paid_amount').value == "" )
        {
		        alert ( "Enter amount" );
				paid_amount.focus();
                return false;
        }
		else if ( document.getElementById('transaction_date').value == "" )
        {
		        alert ( "Select Date" );
				transaction_date.focus();
                return false;
        }
        else{

        return true;
		}
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
