var mode = 'add';

function updateCht(adjust_type_id,adjust_name){

	document.getElementById('adjust_type_id').value = adjust_type_id;

	document.getElementById('adjust_name').value = adjust_name;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{

	valid = true;

     if ( document.contact_form.adjust_name.value == ""  )

        {

		        alert ( "Please fill up asset name ." );

				adjust_name.focus();

                valid = false;

		}

		

        return valid;

}



function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}