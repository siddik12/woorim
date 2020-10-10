var mode = 'add';

function updateExpence(adjust_id,adjust_type_id,particular,dr,cr,entry_date){

	document.getElementById('adjust_id').value = adjust_id;

	document.getElementById('adjust_type_id').value = adjust_type_id;

	document.getElementById('particular').value = particular;
	
	document.getElementById('dr').value = dr;
	
	document.getElementById('cr').value = cr;

	document.getElementById('entry_date').value = entry_date;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{

	valid = true;

     if ( document.contact_form.adjust_type_id.value == ""  )

        {

		        alert ( "Please select adjustment type" );

				adjust_type_id.focus();

                valid = false;

		}else if ( document.contact_form.entry_date.value == ""  ){

		        alert ( "Please fill Date" );

				entry_date.focus();

                valid = false;

		}

        return valid;

}



function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}



 function isNumberKey(evt)

       {

          var charCode = (evt.which) ? evt.which : event.keyCode

          if (charCode != 46 && charCode > 31 

            && (charCode < 48 || charCode > 57))

             return false;



          return true;

       }

