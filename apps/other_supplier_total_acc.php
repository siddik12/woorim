<?php

   // Instantiate the  HomeApp class
   $thisApp  = new other_supplier_total_acc();

   // Instanciate the user class
   //$thisUser = new User();
	$thisApp->userid = getFromSession('userid');
   //Including header
  	$thisUser = new User();

   //Including header
   	if(substr(getRequest('cmd'),0,4) != 'ajax'){
   		require_once(HEADER);
	}   
	if($thisUser->isAuthenticated()){

		if($user_group_id != 1){$thisUser->goHome();}
      $thisApp->run();
	}else{
      $thisUser->goABSHome();
	}
   //Including footer
   	if(substr(getRequest('cmd'),0,4) != 'ajax'){
	 
	 require_once(FOOTER); 
	}
	
?>
