var mode = 'add';
function deleteProb(problemid){	
   var url_loc = "?app=problem&cmd=delete&problemid="+problemid;
   window.location = url_loc;
}
function updateProb(problemid,problem_name){
	document.getElementById('problemid').value = problemid;
	document.getElementById('problem_name').value = problem_name;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}

function validate_form( )
{
	valid = true;
     if ( document.contact_form.problem_name.value == ""  )
        {
		        alert ( "Please entry problem Name." );
                valid = false;
        }
		
        return valid;
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
