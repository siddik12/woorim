var mode = 'add';

function deleteCht(chart_id){	

   var url_loc = "?app=finance&cmd=delete&chart_id="+chart_id;

   window.location = url_loc;

}

function updateCht(chart_id,account_name){

	document.getElementById('chart_id').value = chart_id;

	document.getElementById('account_name').value = account_name;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{

	valid = true;

     if ( document.contact_form.account_name.value == ""  )

        {

		        alert ( "Please fill up expence title." );

				account_name.focus();

                valid = false;

		}

		

        return valid;

}



function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}