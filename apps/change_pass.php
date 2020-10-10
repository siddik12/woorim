<?php
    /***********************************************************
    *  Filename: change_pass.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the application file that is invoked to start the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/
	require_once(HEADER);
   // Create a login application object
   $thisApp = new Change_Pass();

   // Run the application
   $thisApp->run();
   require_once(FOOTER); 
?>