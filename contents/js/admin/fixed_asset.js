var mode = 'add';

function deleteCht(chart_id){	

   var url_loc = "?app=finance&cmd=delete&chart_id="+chart_id;

   window.location = url_loc;

}

function updateCht(fixed_asset_id,asset_name){

	document.getElementById('fixed_asset_id').value = fixed_asset_id;

	document.getElementById('asset_name').value = asset_name;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{

	valid = true;

     if ( document.contact_form.asset_name.value == ""  )

        {

		        alert ( "Please fill up asset name ." );

				asset_name.focus();

                valid = false;

		}

		

        return valid;

}



function confirmDelete()

{

    return confirm("Are you sure you wish to delete this entry?");

}