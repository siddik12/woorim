<?php
    /***********************************************************
    *  Filename: .php
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
   $thisApp  = new HomeApp();

   // Instanciate the user class
   //$thisUser = new User();

   //Including header
   	require_once(HEADER);
   

      $thisApp->run();
   //Including footer
	   require_once(FOOTER); 
?>
