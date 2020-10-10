<?php
    
	require_once(HEADER);
   // Create a login application object
   $thisApp = new forgot_passwd();

   // Run the application
   $thisApp->run();
   require_once(FOOTER);
?>