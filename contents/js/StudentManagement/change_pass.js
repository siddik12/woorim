function validate_pass_frm()
{ 
	//var frm = document.forms["pass_form"];
	//valid = true;
     if ( document.pass_form.current_password.value == "")
        {
		        alert ( "Please enter current password." );
				document.pass_form.current_password.focus();
                return false;
        }
		if(document.pass_form.new_password.value == "" )
		{
			    alert ( "Please enter new password." );
				document.pass_form.new_password.focus();
                return false;
		}
		if(document.pass_form.confirm_password.value == "" )
		{
			    alert ( "Please enter confirm new password." );
				document.pass_form.confirm_password.focus();
                return false;
		}
		 if(pass_form.new_password.value != pass_form.confirm_password.value)
		{
			alert("New password & Confirm password don't match! Should it match.");
			document.pass_form.confirm_password.focus();
			return false;
		}
		else
		{
        return true;
		}
}// end 