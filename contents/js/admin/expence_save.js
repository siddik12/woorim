var mode = 'add';

function updateExpence(dlygl_id,chart_id,particulars,dr,expdate){

	document.getElementById('dlygl_id').value = dlygl_id;

	document.getElementById('chart_id').value = chart_id;

	document.getElementById('particulars').value = particulars;
	document.getElementById('dr').value = dr;

	document.getElementById('expdate').value = expdate;

	 mode = 'edit';

	document.getElementById('mode').value = mode;

	}

	

function validate_form( )

{
     if ( document.contact_form.chart_id.value == ""  )

        {

		        alert ( "Please fill up  Expence Acoount." );

				chart_id.focus();

                return false;

		}else if ( document.contact_form.dr.value == ""  ){

		        alert ( "Please fill Dr. Amount" );

				dr.focus();

                return false;

		}else if ( document.contact_form.pay_type.value == "Bank"){
				
				if ( document.contact_form.bankid.value == ""){
					alert ( "Please select a bank" );
					return false;
				}else{
                	return true;
				}

		}else if ( document.contact_form.expdate.value == ""  ){

		        alert ( "Please fill Date" );

				expdate.focus();

                return false;

		}else{ 
		return true; 
		}

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

