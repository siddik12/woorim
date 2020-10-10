var mode = 'add';
function deleteZone(zone_id){	
   var url_loc = "index.php?app=zone&cmd=delete&zone_id="+zone_id;
   window.location = url_loc;
}
function updateZone(zone_id,zone_name){
	document.getElementById('zone_id').value = zone_id;
	document.getElementById('zone_name').value = zone_name;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}


function validate_form( )
{
	valid = true;
     if ( document.contact_form.zone_name.value == ""  )
        {
		        alert ( "Please entry Zone Name." );
                valid = false;
        }
		
        return valid;
}


function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
