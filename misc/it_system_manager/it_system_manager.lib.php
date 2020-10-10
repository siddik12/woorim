<?php

    /***********************************************************
    *  Filename: it_system_manager.lib.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This the librery file for the it system manager application
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/

    function deleteITSystemApplication($ID = null)
    {
       if ($ID)
       {
          $info['table'] = IT_SYSTEM_APP_TBL;
          $info['where'] = "id = $ID";
          $info['debug'] = false;

          $delete = delete($info);
       }

       if($delete)
       {
          return 1;
       }

       return 0;
    }

    function deleteSelectedITSystem($str = null)
    {
       if ($str)
       {
          $info['table'] = IT_SYSTEM_TBL;
          $info['debug'] = false;

          $IDArr = explode(',', $str);

          if (!empty($IDArr))
          {
             foreach ($IDArr as $ID)
             {
                if (!empty($ID))
                {
                   $info['where'] = "id = $ID";

                   $delete = delete($info);
                }
             }
          }
       }

       if($delete)
       {
          return 1;
       }

       return 0;
    }

    function deleteSingleITSystem($ID = null)
    {
       $info['table'] = IT_SYSTEM_TBL;
       $info['debug'] = false;
       $info['where'] = "id = $ID";

       $delete = delete($info);

       if($delete)
       {
          return $ID;
       }

       return 0;
    }
?>