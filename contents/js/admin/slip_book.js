var mode = 'add';
function deleteBook(bookno){	
   var url_loc = "?app=slip_book&cmd=delete&bookno="+bookno;
   window.location = url_loc;
}
function updateBook(bookno,zone_id,bkname){
	document.getElementById('bookno').value = bookno;
	document.getElementById('zone_id').value = zone_id;
	document.getElementById('bkname').value = bkname;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}

function validate_form( )
{
	valid = true;
     if ( document.contact_form.bkname.value == ""  )
        {
		        alert ( "Please entry Book Name." );
                valid = false;
        }else if(document.contact_form.zone_id.value == ""){
		        alert ( "Please entry Zone Name." );
                valid = false;
		}
			return valid;
		
		
        
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
