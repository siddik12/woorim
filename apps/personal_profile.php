<?php
    /***********************************************************
    *  Filename: home.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the application file that is invoked to start the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/

   // Instantiate the  HomeApp class
   $thisApp  = new personal_profile();

   // Instanciate the user class
  $thisUser = new User();

   //Including header
   if(substr(getRequest('cmd'),0,4) != 'ajax'){
   	require_once(HEADER);
	}   
   if($thisUser->isAuthenticated()){

		//$thisUser->goHome();
      $thisApp->run();
	}else{
      $thisUser->goABSHome();
	}
   //Including footer
   if(substr(getRequest('cmd'),0,4) != 'ajax'){
	   require_once(FOOTER); 
	}
?>
