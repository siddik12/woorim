<?php
    /***********************************************************
    *  Filename: it_system_manager.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the application file that is invoked to start the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/

   //Include the main configuration file
   require_once($_SERVER['DOCUMENT_ROOT'] . '/app/common/conf/main.conf.php');
   require_once(LOCAL_CONFIG_DIR          . '/inova.conf.php');
   require_once(LOCAL_LIB_DIR             . '/inova.lib.php');
   require_once(AJAX_DIR                  . '/cpaint.inc.php');

   // Instanciate the ItSystemManager class
   $thisApp  = new ItSystemManager();

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