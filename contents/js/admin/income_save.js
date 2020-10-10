var mode = 'add';
function deleteIncome(dlygl_id){	
   var url_loc = "?app=finance&cmd=DeleteIncome&dlygl_id="+dlygl_id;
   window.location = url_loc;
}
function updateIncome(dlygl_id,chart_id,particulars,cr,expdate){
	document.getElementById('dlygl_id').value = dlygl_id;
	document.getElementById('chart_id').value = chart_id;
	document.getElementById('particulars').value = particulars;
	document.getElementById('cr').value = cr;
	document.getElementById('expdate').value = expdate;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
	
function validate_form( )
{
	valid = true;
     if ( document.contact_form.chart_id.value == ""  )
        {
		        alert ( "Please fill up  Income Acoount." );
				chart_id.focus();
                valid = false;
		}else if ( document.contact_form.cr.value == ""  ){
		        alert ( "Please fill Cr. Amount" );
				cr.focus();
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

	   
function clearValue(){
	var mode = 'add';
	document.getElementById('mode').value=mode;
	document.getElementById('dlygl_id').value="";
}