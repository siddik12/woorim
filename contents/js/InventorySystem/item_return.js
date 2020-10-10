function check(){

	 if( document.complfrm.quantity.value == "" || document.complfrm.damage_date.value == "" || document.complfrm.return_purpose.value == "")
        {
		        alert ( "Please enter item quantity, date and Return for" );
                return false;
        }

		return true;
		
}
