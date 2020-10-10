var mode = 'add';

function updateExpence(asset_id,fixed_asset_id,parti,dr,expdate){

	document.getElementById('asset_id').value = asset_id;

	document.getElementById('fixed_asset_id').value = fixed_asset_id;

	document.getElementById('parti').value = parti;
	document.getElementById('dr').value = dr;

	document.getElementById('expdate').value = expdate;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{

	valid = true;

     if ( document.contact_form.fixed_asset_id.value == ""  )

        {

		        alert ( "Please select fixed asset " );

				fixed_asset_id.focus();

                valid = false;

		}else if ( document.contact_form.dr.value == ""  ){

		        alert ( "Please fill Dr. Amount" );

				dr.focus();

                valid = false;

		}else if ( document.contact_form.expdate.value == ""  ){

		        alert ( "Please fill Date" );

				expdate.focus();

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

