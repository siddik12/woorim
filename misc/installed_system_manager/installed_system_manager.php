<?php

   /***********************************************************
    *  Filename: installed_system_manager.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the application file that is invoked to start the installed system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/


   // Include the main configuration file
   
   require_once($_SERVER['DOCUMENT_ROOT'] .'/app/common/conf/main.conf.php');
   require_once(LOCAL_CLASS_DIR           .'/InstalledSystem.class.php');
   require_once(LOCAL_CONFIG_DIR.'/inova.conf.php');
   require_once(DOCUMENT_CLASS);
   require_once(USER_CLASS);
   require_once(AJAX_DIR                  .'/cpaint.inc.php');
  
   
   $thisApp  = new InstalledSystemManager();

   // Instanciate the user class
   $thisUser = new User();

   // Checks the user authentication
   if($thisUser->isAuthenticated())
   {
      $thisApp->run();
   }
   else
   {
      $thisUser->goLogin();
   }
?>