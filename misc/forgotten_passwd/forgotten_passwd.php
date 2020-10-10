<?php

   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   require_once(USER_CLASS);
   
   $thisApp = new ForgottonPasswordApp();
   
     
   $thisApp->run();
   
?>