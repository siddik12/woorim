<?php

   /***********************************************************
    *  Filename: it_system_manager.class.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This file implements all the it system application functionalities
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/


class ItSystemManager extends DefaultApplication
{
   /**
   * Constructor
   * @return true
   */

   function run()
   {
      $cmd = getUserField('cmd');

      switch ($cmd)
      {
         case 'edit'    : $screen = $this->showEditor($msg); break;
         case 'add'     : $screen = $this->addITSystem();    break;
         case 'delete'  : $screen = $this->deleteITSystem(); break;
         case 'list'    : $screen = $this->showList($msg);   break;
         default        : $screen = $this->showList($msg);   break;
      }

      // Set the current navigation item
      $this->setNavigation('ITSystem');

      echo $this->displayScreen($screen);

      return true;
   }

   /**
   * Shows the listing/search page for IT System
   * @param none
   * @return none
   */

   function showList($msg = null)
   {
      $cmd = getUserField('cmd');

      if ($cmd == 'list')
      {
         $category       = getUserField('category');
         $serial_number  = getUserField('serial_number');
         $number_or_name = getUserField('number_or_name');
         $keyword        = getUserField('keyword');
         $start_date     = getUserField('start_date');
         $end_date       = getUserField('end_date');
         $sort           = getUserField('sort');
      }

      $data = array();
      $data['category']       = $category;
      $data['category_list']  = getCategoryList(IT_PART);
      $data['serial_number']  = $serial_number;
      $data['number_or_name'] = $number_or_name;
      $data['keyword']        = $keyword;
      $data['start_date']     = $start_date;
      $data['end_date']       = $end_date;
      $data['sort']           = $sort;

      if ($start_date)
         $start_date = date('Y-m-d', strtotime($start_date));

      if ($end_date)
         $end_date   = date('Y-m-d', strtotime($end_date));

      if ($category)
         $filterClause .= " and ITSYS.category_id = '$category' ";

      if ($serial_number)
         $filterClause .= " and ITSYS.serial_number = '$serial_number' ";

      if ($number_or_name)
         $filterClause .= " and (CUST.customer_number Like '%$number_or_name%' or CUST.company Like '%$number_or_name%') ";

      if ($keyword)
         $filterClause .= " and ITSYS.description Like '%$keyword%' ";

      if ($start_date && $end_date)
         $filterClause .= " and ITSYS.assembled_date >= '$start_date' and ITSYS.assembled_date <= '$end_date'";
      else if ($start_date)
         $filterClause .= " and ITSYS.assembled_date >= '$start_date' ";
      else if ($end_date)
         $filterClause .= " and ITSYS.assembled_date <= '$end_date' ";

      if ($sort == 'serial_number')
         $orderClause = " Order By ITSYS.serial_number ASC";
      else
         $orderClause = " Order By ITSYS.assembled_date DESC";

      $info           = array();
      $info['table']  = IT_SYSTEM_TBL . ' as ITSYS, ' .
                        CUSTOMER_TBL  . ' as CUST, '  .
                        USER_TBL      . ' as USER ';
      $info['fields'] = array ('ITSYS.id as id',
                               'ITSYS.assembled_date as assembled_date',
                               'CUST.customer_number as customer_number',
                               'CUST.company as customer_name',
                               'ITSYS.serial_number as serial_number',
                               'ITSYS.status as status',
                               'USER.first_name as first_name',
                               'USER.last_name as last_name',
                               'USER.username as username'
                              );
      $info['where']  = ' ITSYS.customer_id = CUST.id ' .
                        ' and ITSYS.assembled_by_uid = USER.uid ' .
                        $filterClause . $orderClause;
      $info['debug']  = false;

      $data['list']   = select($info);

      if (empty($data['list']))
      {
         $msg = $this->getMessage(NO_MATCH_FOUND);
      }

      $data['message'] = $msg;

      return createPage(IT_SYS_MNG_LIST_TEMPLATE, $data);
   }


   /**
   * Shows editor for IT system
   * @paran null
   * @return none
   */
   function showEditor($msg = null)
   {
      $ID = getUserField('id');

      if ($ID)
      {
         $ItSystem = new ITSystem($ID);
         $data = array_merge(array(), $ItSystem);
      }

      $data['customer_list'] = getCustomerList();
      $data['category_list'] = getCategoryList(IT_PART);
      $data['status_list']   = getEnumFieldValues(IT_SYSTEM_TBL, 'status');
      $data['user_list']     = getUserList();
      $data['os_list']       = getDropDownList(OS);

      //dumpvar($data);

      $data['message'] = $msg;

      return createPage(IT_SYS_MNG_EDITOR_TEMPLATE, $data);
   }

   /**
   * Adds/Updates IT system
   * @paran null
   * @return none
   */
   function addITSystem()
   {
      $ID = getUserField('id');

      if ($ID)
      {
          $thisITSystem = new ITSystem();

          if ($thisITSystem->update($ID))
          {
             $msg = $this->getMessage(IT_SYS_UPDATE_SUCCESS_MSG);
          }
          else
          {
             $msg = $this->getMessage(IT_SYS_UPDATE_ERROR_MSG);
          }

          return $this->showEditor($msg);
      }
      else
      {
          $thisITSystem = new ITSystem();

          if ($thisITSystem->add())
          {
             $msg = $this->getMessage(IT_SYS_SAVE_SUCCESS_MSG);
          }
          else
          {
             $msg = $this->getMessage(IT_SYS_SAVE_ERROR_MSG);
          }

          setUserField('cmd', '');

          return $this->showList($msg);
      }
   }

   /**
   * Deletes IT system
   * @paran null
   * @return none
   */
   function deleteITSystem()
   {
      $ID = getUserField('id');
      $thisITSystem = new ITSystem();

      $ok = $thisITSystem->delete($ID);

      if($ok)
      {
         $msg = $this->getMessage(IT_SYS_DELETE_SUCCESS_MSG);
      }
      else
      {
         $msg = $this->getMessage(IT_SYS_DELETE_ERROR_MSG);
      }

      setUserField('id',  '');
      setUserField('cmd', '');

      return $this->showList($msg);
   }

}
?>