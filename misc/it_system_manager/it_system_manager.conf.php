<?php

    /***********************************************************
    *  Filename: it_system_manager.conf.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the configuration file for the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/

   // include the user class
   require_once(USER_CLASS);
   require_once(DOCUMENT_CLASS);
   require_once(LOCAL_CLASS_DIR . '/ITSystem.class.php');


   /**#@+
   * Template PATH Constant
   */
   define('TEMPLATE_DIR',                  APP_CONTENTS_DIR     . '/' . CURRENT_APP_PREFIX);
   define('REL_TEMPLATE_DIR',              REL_APP_CONTENTS_DIR . '/' . CURRENT_APP_PREFIX);

   /**#@+
   * Template Constant
   */
   define('IT_SYS_MNG_EDITOR_TEMPLATE',    TEMPLATE_DIR . '/editor.html');
   define('IT_SYS_MNG_LIST_TEMPLATE',      TEMPLATE_DIR . '/list.html');

   /**#@+
   * Message Constant
   */
   define('IT_SYS_SAVE_SUCCESS_MSG',         2211);
   define('IT_SYS_UPDATE_SUCCESS_MSG',       2212);
   define('IT_SYS_DELETE_SUCCESS_MSG',       2213);
   define('IT_SYS_SAVE_ERROR_MSG',           2221);
   define('IT_SYS_UPDATE_ERROR_MSG',         2222);
   define('IT_SYS_DELETE_ERROR_MSG',         2223);
   define('IT_SYS_SEARCH_NO_RECORD_MSG',     2231);
?>