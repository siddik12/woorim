function validate_4Enterchk()
{
	
     if ( document.getElementById('bkash_acc').value == "" )
        {
		        alert ( "Please enter bKash Acc" );
				bkash_acc.focus();
                return false;
        }
     else if ( document.getElementById('trans_type').value == "" )
        {
		        alert ( "select Transaction Type" );
                return false;
        }
     else if ( document.getElementById('trans_amount').value == "" )
        {
		        alert ( "Enter amount" );
				trans_amount.focus();
                return false;
        }
		else if ( document.getElementById('trans_date').value == "" )
        {
		        alert ( "Select Date" );
				trans_date.focus();
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
