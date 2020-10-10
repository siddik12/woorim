<?php
    /***********************************************************
    *  Filename: login.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the application file that is invoked to start the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/
	 
   // Create a login application object
   $thisApp = new LoginApp();

   // Run the application
   	$thisUser = new User();

   //Including header
   
   	if(substr(getRequest('cmd'),0,4) != 'ajax'){
   		//require_once(HEADER);
	}   
   	if($thisUser->isAuthenticated()){

		$thisUser->goHome();
	}else{
      	$thisApp->run();
      	//$thisUser->goABSHome();
	}
   //Including footer
   	if(substr(getRequest('cmd'),0,4) != 'ajax'){
	 //  require_once(FOOTER); 
	}
?>