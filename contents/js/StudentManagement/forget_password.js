function showHideDivforPasswd()
    {
		var tbl = document.getElementById("drop_down_tbl");
		//alert(CommentatorLookUp.style.display);
        //var divstyle = new String();
        divstyle = tbl.style.display;
        if(divstyle=="block")
        {
            tbl.style.display = "none";
        }
        else
        {
            tbl.style.display = "block";
        }
    }
function validate_form()
{
	var valid = true;

     if ( document.login.loginid.value == ""  )
        {
		        alert ( "Please enter your user ID" );
				loginid.focus();
                valid = false;
        }
		return valid;
}