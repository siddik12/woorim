function check(){

	 if( document.complfrm.quantity.value == "" || document.complfrm.supplier_id.value == "" )
        {
		        alert ( "Please enter item quantity and supplier" );
                return false;
        }

		return true;
		
}
