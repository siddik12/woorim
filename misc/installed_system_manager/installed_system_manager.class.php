<?php

   /***********************************************************
    *  Filename: installed_system_manager.class.php
    *
    *  Author  : php@DGITEL.com
    *
    *  Version : $Id$
    *
    *  Purpose : This file implements all the installed system application functionalities
    *
    *  Copyright (c) 2006 by DGITEL (php@DGITEL.com)
    ***********************************************************/


class InstalledSystemManager extends DefaultApplication
{

   var $is_temp_uploaded='';   
   var $uploadDir = "/projects/atlas/inova/htdocs/documents/";
   var $tempUploadDir = "/projects/atlas/inova/htdocs/documents/temp_files/";
   
   
   function run()
   {       
        
        $cmd = getUserField('cmd');
        
        switch ($cmd)
        {
             case 'showAll'              : $this->showAllInstalledSystem();
                                           break;
             case 'find'                 : $this->findInstalledSystem();
                                           break;                              
             case 'showAddPage'          : $this->showAddPage();
                                           break;
             case 'add'                  : $this->add();
                                           break;
             case 'addDoc'               : $this->addDoc();
                                           break;
             case 'delDoc'               : $this->delDoc();
                                           break;
             case 'showEditPage'         : $this->showEditPage();
                                           break;
             case 'edit'                 : $this->edit();
                                           break;
             case 'delete'               : $this->delete();
                                           break;
             case 'deleteAllSelected'    : $this->deleteAllSelected();
                                           break;                              
             case 'showByCategory'       : $this->showByCategory();
                                           break;             
                                           
             case 'findFileById'         : $this->findFileById();
                                           break;
             default                     : $this->showAllInstalledSystem();
                                           break;
        }
        
        return true;
   }
   
   /* Shows all installed systems 
   * @param none
   * @return none
   */
   
   function showAllInstalledSystem()
   {
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'category_type=\'Installed System\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $categories[] = $row->name;
           }
       }
        
       
       $data['categories'] = $categories;
       
       $info['table'] = INSTALLED_SYSTEM_TBL;
       $info['where'] = '1';
       $result        = select($info); 
       
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
              $params['id']       = $row->id;
              $installedSystems[] = new InstalledSystem($params);
           }
       }
       
       if(count($installedSystems))
       {
          sort($installedSystems);
          reset($installedSystems);
       }
       
       
       $data['installed_systems']     = $installedSystems;
       $data['has_delete_permission'] = $this->hasDeletePermission();
       $data['has_add_permission']    = $this->hasAddPermission();
       $data['has_edit_permission']   = $this->hasEditPermission();
       $data['is_super_user']         = $this->isSuperUser();
       $data['current_category']      = "All";
       $data['find_any']              = true;
       
       $screen=createPage(INSTALLED_SYSTEM_MANAGER_MAIN_TEMPLATE, $data);
       echo $this->displayScreen($screen);

   }
   
   /*
   * Shows a blank form for adding a new insalled system
   * @param none
   * @return none
   */
   
   function showAddPage()
   {
       
       //Unset Temp File       
       unset($_SESSION['temp_file']);
       
       // Getting categories       
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'category_type=\'Installed System\'';
       $info['debug'] = false;
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $categories[] = $row->name;
           }
       }
       
       $data['categories'] = $categories;
       
       // Getting customer companies
       $info['table'] = CUSTOMER_TBL;
       $info['where'] = '1 order by company';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $companies[] = $row->company . ' (' . $row->customer_number . ')';
           }
       }
       
       $data['companies'] = $companies;
       
       // Getting vendors
       $info['table'] = DROPDOWN_TBL;
       $info['where'] = 'menu_type=\'Vendor\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $vendors[] = $row->name;
           }
       }
       
       $data['vendors'] = $vendors;
       
       // Getting vendors
       $info['table'] = DROPDOWN_TBL;
       $info['where'] = 'menu_type=\'Model\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $models[] = $row->name;
           }
       }
       
       $data['models'] = $models;
       
       $statuses[] = 'Active';
       $statuses[] = 'Inactive';
       
       $data['statuses']   = $statuses;
       $data['operation']  = 'add';
       $data['status_msg'] = 'Add a new installed system.';
       
       
       $data['is_super_user'] = $this->isSuperUser();
       
       
       $screen=createPage(INSTALLED_SYSTEM_MANAGER_ADD_EDIT_TEMPLATE, $data);
       echo $this->displayScreen($screen);
   }
   
   /*
   * Shows a form for editing an insalled system
   * @param none
   * @return none
   */
   
   function showEditPage()
   {
       
       // Getting categories
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'category_type=\'Installed System\'';
       $info['debug'] = false;

       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $categories[] = $row->name;
           }
       }
       
       $data['categories'] = $categories;
       
       // Getting customer companies
       $info['table'] = CUSTOMER_TBL;
       $info['where'] = '1 order by company';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $companies[] = $row->company . ' (' . $row->customer_number . ')';
           }
       }
       
       $data['companies'] = $companies;
       

       // Getting vendors
       $info['table'] = DROPDOWN_TBL;
       $info['where'] = 'menu_type=\'Vendor\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $vendors[] = $row->name;
           }
       }
       
       $data['vendors'] = $vendors;
       
       // Getting models
       $info['table'] = DROPDOWN_TBL;
       $info['where'] = 'menu_type=\'Model\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $models[] = $row->name;
           }
       }
       
       $data['models'] = $models;
       
       $statuses[] = 'Active';
       $statuses[] = 'Inactive';
       
       
       $data['statuses']   = $statuses;
       $data['status_msg'] = 'Updating installed system.';
       
       
       $data['is_super_user'] = $this->isSuperUser();
       
       
       $params['id']             = getUserField('installed_system_id');
       $data['installed_system'] = new InstalledSystem($params);
       
       $data['current_status']   = $data['installed_system']->getStatus();
       $data['current_model']    = $data['installed_system']->getModel();
       $data['current_vendor']   = $data['installed_system']->getVendor();
       $data['service_contract_document'] = $data['installed_system']->getServiceContractDoc();       
       $data['current_company']  = $data['installed_system']->getCustomerCompany() . ' (' . $data['installed_system']->getCustomerNumber() . ')';
       $data['operation']        = 'edit';
       
       $categoryId    = $data['installed_system']->getCategoryId();
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'id=' . $categoryId;
       $result        = select($info);
       
       $data['current_category'] = $result[0]->name;
       
       
       
       $screen=createPage(INSTALLED_SYSTEM_MANAGER_ADD_EDIT_TEMPLATE, $data);
       echo $this->displayScreen($screen);
       
   }
   
   /**
   * Deletes an installed system
   * @param none
   * @return none
   */
  
   function delete()
   {
       $installedSystem   = new InstalledSystem();
       $installedSystemID = getUserField('installedSystemID');
       
       $installedSystem->setId($installedSystemID);
       $isSuccessful    = $installedSystem->delete();
       
       if(isSuccessful)
       {
           $current_doc_file = trim($this->findServiceDoc($installedSystemID));
  	       
  	       if(!empty($current_doc_file))
  	       {
  	          $delResult = unlink($this->uploadDir.$current_doc_file);  
           }
           else
           {
           	  $delResult = true; 
           }
           
           if($delResult)
           {
              echo 'DELETE_INSTALLED_SYSTEM,SUCCESS,' . $installedSystemID;
           }
           else
           {
           	  echo 'DELETE_INSTALLED_SYSTEM,FAILED, ';
           }   
       }
       else
       {
           echo 'DELETE_INSTALLED_SYSTEM,FAILED, ';
       }
   }
   
   
   /**
   * Deletes all selected installed systems
   * @param none
   * @return none
   */
  
   function deleteAllSelected()
   {
    
       $installedSystemIDs = getUserField('installedSystemIDs');
       $installedSystemIDs = explode(",", $installedSystemIDs);
              
       
       $selectedCnt = 0;
       $delCnt = 0;
       
       foreach($installedSystemIDs as $i=>$v)
       {
          if(is_numeric($v))
          {
          	 $selectedCnt++;
          	 
          	 if($this->deleteInstalledSystem(trim($v)))
          	 {
          	 	  $delCnt++;
          	 } 
          }	
       }
     
      if($delCnt == $selectedCnt)
      {      	
      	echo 'DELETE_ALL_INSTALLED_SYSTEM,SUCCESS';
      }
      else
      {
         echo 'DELETE_ALL_INSTALLED_SYSTEM,FAILED';
      }
       
       /*
       $length             = strlen($installedSystemIDs);
       
       if($length > 1)
       {
          $installedSystemIDs = substr($installedSystemIDs,0,$length-1);
          $installedSystemIDs = split(',',$installedSystemIDs);
       }
       
       foreach($installedSystemIDs as $id)
       {
           $installedSystem = new InstalledSystem();
           $installedSystem->setId($id);
           
           
           if($isSuccessful)
           $deletedCount++;
       }
       
       if($deletedCount == count($installedSystemIDs)) 
       {
           echo 'DELETE_ALL_INSTALLED_SYSTEM,SUCCESS, ';
       }
       else
       {
           echo 'DELETE_ALL_INSTALLED_SYSTEM,FAILED, ';
       }
       */
   }

   function deleteInstalledSystem($id)
   {
      
      
      $info = array();
      $info['table'] = INSTALLED_SYSTEM_TBL;
      $info['where'] = "id=$id";
      $info['debug'] = false;
      
      $res = select($info);
      
      $file_to_delete = "";
      
      if(count($res))
      {
      	  foreach($res as $i=>$v)
      	  {
      	  	 $file_to_delete = $v->service_contract_document;
      	  	 break;
      	  }
      }
      
      $file_to_delete = trim($file_to_delete);
      
      if(!empty($file_to_delete))
      {
         $file_res = unlink($this->uploadDir.$file_to_delete);      
      }
      else
      {
      	 $file_res = true;
      }
      
      return (delete($info) && $file_res); 	
   }
   
   /**
   * Shows installed systems of a given category
   * @params none
   * @ return none
   */
   
   function showByCategory()
   {
       $category                 = getUserField('category');
       $data['current_category'] = $category;
       
       if($category == 'All')
       {
           $this->showAllInstalledSystem();
           return true;
       }

      
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'name=\'' . $category . '\' AND category_type=\'Installed System\'';
       $result        = select($info);
       
       $categoryID    = $result[0]->id;
       
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'category_type=\'Installed System\'';
       $result        = select($info); 
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
               $categories[] = $row->name;
           }
       }
       
       $data['categories'] = $categories;
       
       $info['table'] = INSTALLED_SYSTEM_TBL;
       $info['where'] = 'category_id=' . $categoryID;
       $result        = select($info); 
       
       
       if(!empty($result))
       {
           foreach($result as $row)
           {
              $params['id']       = $row->id;
              $installedSystems[] = new InstalledSystem($params);
           }
       }
       
       $data['installed_systems']     = $installedSystems;
       $data['has_delete_permission'] = $this->hasDeletePermission();
       $data['has_add_permission']    = $this->hasAddPermission();
       $data['has_edit_permission']   = $this->hasEditPermission();
       $data['is_super_user']         = $this->isSuperUser();
       $data['find_any']              = true;
       
       
       $screen=createPage(INSTALLED_SYSTEM_MANAGER_MAIN_TEMPLATE, $data);
       echo $this->displayScreen($screen);
       
   }     
   
   
   /*
   * Finds installed systems with the supplied information
   * @params none
   * @return none
   */
   function findInstalledSystem()
   {
   	$find_system_id       = getUserField('find_system_id');
   	$find_customer_number = getUserField('find_customer_number');
   	$find_serial_number   = getUserField('find_serial_number');
   	$find_it_system_id    = getUserField('find_it_system_id');
   	$find_method          = getUserField('find_method');
   	$find_category        = getUserField('find_category');
   	 
   	$info['table'] = CUSTOMER_TBL . ',' . INSTALLED_SYSTEM_TBL;
   	
   	
   	$info['where'] = 'installed_system.customer_id = customer.id AND ( ';
   	
   	$orInsert = 0; //Checks whether 'OR' can be added to the query
   	
   	if(!empty($find_system_id))
   	{
   	    $info['where'] = $info['where'] . 'installed_system.id = ' . $find_system_id ;
   	    $orInsert = 1;
   	}
 
   	if(!empty($find_serial_number))
   	{
   	    if($orInsert)
   	    {
   	       $info['where'] = $info['where'] . ' OR installed_system.serial_number = \'' . $find_serial_number . '\'';   	       
   	    }
   	    else
   	    {
   	    	  $info['where'] = $info['where'] . ' installed_system.serial_number = \'' . $find_serial_number . '\'';   	    	  
   	        $orInsert = 1;
   	    }
   	}
   	
   	if(!empty($find_it_system_id))
   	{
   	    if($orInsert)
   	    {
   	        $info['where'] = $info['where'] . ' OR installed_system.it_system_id = ' . $find_it_system_id ;
   	    }
   	    else
   	    {
   	    	  $info['where'] = $info['where'] . ' installed_system.it_system_id = ' . $find_it_system_id ;
   	    	  $orInsert = 1;
   	    }
   	}
   	
   	if(!empty($find_customer_number))
   	{
   	    if($orInsert)
   	    {
   	       $info['where'] = $info['where'] . ' OR customer.customer_number = \'' .  $find_customer_number . '\'';
   	    }
   	    else
   	    {
   	    	  $info['where'] = $info['where'] . ' customer.customer_number = \'' .  $find_customer_number . '\'';
   	    	  $orInsert = 1;
   	    }
   	}
   	
   	
   	if(!empty($find_category))
   	{
   	    $data['current_category'] = $find_category;
   	    
   	    if(trim($find_category)!="All")
   	    {
   	    	 $find_category = preg_replace("/(.+)([0-9]+)/","\\2", $find_category);
   	    }
   	       	       	    
   	    
   	    if($orInsert)
   	    {
   	        if(trim($find_category)!="All")
   	        {
   	           $info['where'] = $info['where'] . ' OR installed_system.category_id = \'' .  $find_category . '\'';
   	        }
   	    }
   	    else
   	    {
   	    	  if(trim($find_category)!="All")
   	    	  {
   	    	  	
   	    	      $info['where'] = $info['where'] . ' installed_system.category_id = \'' .  $find_category . '\'';
   	    	  }

   	    }
   	}
   	
   	if($find_method == "All")
   	{
   		  $info['where'] = str_replace(' OR ',' AND ',$info['where']);
   	}
   	
   	$info['where'] = $info['where'] . ')';
   	
   	if(!$orInsert && trim($find_category)=="All")
   	{
   		     	   
   	   $info['where'] = str_replace('AND ( )', '', $info['where']);
   	    	  	 
   	}
   	
   	$info['debug'] = false;
   	$result = select($info);
   	
   	if(!empty($result))
   	{
   		  foreach($result as $row)
   		  {
   		  	  $params['id']       = $row->id;
            $installedSystems[] = new InstalledSystem($params);
   		  }
   	}
   	
   	$data['installed_systems']     = $installedSystems;
    $data['has_delete_permission'] = $this->hasDeletePermission();
    $data['has_add_permission']    = $this->hasAddPermission();
    $data['has_edit_permission']   = $this->hasEditPermission();
    $data['is_super_user']         = $this->isSuperUser();
   // $data['current_category']      = null;
    
    if($find_method == "All")
    {
    	  $data['find_all'] = true;
    	  $data['find_any'] = false;
    }
    else
    {
    	  $data['find_all'] = false;
    	  $data['find_any'] = true;
    }
    
    $data['find_system_id']       = $find_system_id;      
    $data['find_customer_number'] = $find_customer_number;
    $data['find_serial_number']   = $find_serial_number;  
    $data['find_it_sys_id']       = $find_it_system_id;   
    	                      
    $info['table'] = CATEGORY_TBL;
    $info['where'] = 'category_type=\'Installed System\'';
    $result        = select($info); 
    
    if(!empty($result))
    {
        foreach($result as $row)
        {
            $categories[] = $row->name;
        }
    }
    
    $data['categories'] = $categories;
    
    $screen=createPage(INSTALLED_SYSTEM_MANAGER_MAIN_TEMPLATE, $data);
    echo $this->displayScreen($screen);
   	
   }
   
   
   /**
   * Determines whether this user has delete permission or not
   * @params none
   * @return true/false
   */    
   function  hasDeletePermission()
   {
       $attributes['uid'] = getFromSession('uid');
       $thisUser          = new User($attributes);
       $userType          = $thisUser->getUserType();
       
       if($userType == 'Unpreviledged')
       {
           return false;
       }
       else
       {
           return true;
       }
   }
   
   /**
   * Determines whether this user has add permission or not
   * @params none
   * @return true/false
   */
   function  hasAddPermission()
   {
       $attributes['uid'] = getFromSession('uid');
       $thisUser          = new User($attributes);
       $userType          = $thisUser->getUserType();
       
       if($userType == 'Unpreviledged')
       {
           return false;
       }
       else
       {
           return true;
       }
   }
   
   /**
   * Determines whether this user has update permission or not
   * @params none
   * @return true/false
   */
   function  hasEditPermission()
   {
       $attributes['uid'] = getFromSession('uid');
       $thisUser          = new User($attributes);
       $userType          = $thisUser->getUserType();
       
       if($userType == 'Unpreviledged')
       {
           return false;
       }
       else
       {
           return true;
       }
   }
   
   
   /**
   * Determines whether this user is a super user or not
   * @params none
   * @return true/false
   */
   function  isSuperUser()
   {
       $attributes['uid'] = getFromSession('uid');
       $thisUser          = new User($attributes);
       $userType          = $thisUser->getUserType();
       
       if($userType == 'Super User')
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   /*
   * Updating an installed system
   * @param none
   * @return none
   */
   function edit()
   {
   	   $installedSystem = new InstalledSystem();
   	      	   
   	   $installedSystem->setId(getUserField('installed_system_id'));
   	   $installedSystem->setSerialNumber(getUserField('serial_number'));
   	   $installedSystem->setDateInstalled($this->formatDate(getUserField('installation_date')));
       $installedSystem->setItSystemId(getUserField('it_system_id'));
       $installedSystem->setDateLastServiced($this->formatDate(getUserField('last_service_date')));
       $installedSystem->setWarrantyExpiration($this->formatDate(getUserField('warranty_expiration_date')));
       //$installedSystem->setWarrantyExpirat
       $installedSystem->setPurchasePrice(getUserField('purchase_price'));
       $installedSystem->setNextMaintDate($this->formatDate(getUserField('next_maint_date')));
       $installedSystem->setContractRenewalDate($this->formatDate(getUserField('contract_renewal_date')));
       $installedSystem->setComments(getUserField('comments'));
       $installedSystem->setStatus(getUserField('status'));
       $installedSystem->setVendor(getUserField('vendor'));
       $installedSystem->setModel(getUserField('model'));
       
       //echo "VAL:".getUserField('service_contract')."->";
       
       if(getUserField('service_contract')=="true")
       {
       	 
       	  $installedSystem->setServiceContract('Yes');
       }
       else
       {
       	 
       	  $installedSystem->setServiceContract('No');
       }    	  
       
       $companyName = getUserField('customer_company');
       $pos         = strpos($companyName," (");
       $companyName = substr($companyName,0,$pos);
       
       $info['table'] = CUSTOMER_TBL;
       $info['where'] = 'company=\'' . $companyName . '\'';
       $result        = select($info);
       
       $customerId    = $result[0]->id;
       
       $installedSystem->setCustomerId($customerId);
       
       $category      = getUserField('category');
       $info['table'] = CATEGORY_TBL;
       $info['where'] = 'name=\'' . $category . '\' AND category_type=\'Installed System\'';
       $result        = select($info);
       
       $categoryId    = $result[0]->id;
       
       $installedSystem->setCategoryId($categoryId);
       
       
       
       
       /**************************** UPLOAD THE DOCUMENT *******************************/
       
       $isSuccessful = $installedSystem->update();
       
       if($isSuccessful)
   	   {
   	   	  echo 'INSTALLED_SYSTEM_EDIT,SUCCESS';
   	   }
   	   else
   	   {
   	   	  echo 'INSTALLED_SYSTEM_EDIT,FAILED';
   	   }
   
  	
   }
   
   /*
   * Adding a new installed system
   * @param none
   * @return none
   */
   function add()
   {
   	    $installedSystem = new InstalledSystem();
   	    
   	    $installedSystem->setSerialNumber(getUserField('serial_number'));
   	    $installedSystem->setDateInstalled($this->formatDate(getUserField('installation_date')));
        $installedSystem->setItSystemId(getUserField('it_system_id'));
        $installedSystem->setDateLastServiced($this->formatDate(getUserField('last_service_date')));
        $installedSystem->setWarrantyExpiration($this->formatDate(getUserField('warranty_expiration_date')));
        $installedSystem->setPurchasePrice(getUserField('purchase_price'));
        $installedSystem->setNextMaintDate($this->formatDate(getUserField('next_maint_date')));
        $installedSystem->setContractRenewalDate($this->formatDate(getUserField('contract_renewal_date')));
        $installedSystem->setComments(getUserField('comments'));
        $installedSystem->setStatus(getUserField('status'));
        $installedSystem->setVendor(getUserField('vendor'));
        $installedSystem->setModel(getUserField('model'));
        
        
        if(getUserField('service_contract')=="true")
        {
        	  $installedSystem->setServiceContract('Yes');      
        }
        else
        {
        	  $installedSystem->setServiceContract('No');
        }    	  
        
        if(!empty($_SESSION['temp_file']))
        {
        	  $installedSystem->setServiceContractDoc($_SESSION['temp_file']);
        	  $this->is_temp_uploaded="yes";     
        }
        
        $companyName = getUserField('customer_company');
        $pos         = strpos($companyName," (");
        $companyName = substr($companyName,0,$pos);
   
        $info['table'] = CUSTOMER_TBL;
        $info['where'] = 'company=\'' . $companyName . '\'';
        $result        = select($info);
        
        $customerId = $result[0]->id;
        
       
        $installedSystem->setCustomerId($customerId);
         
         
        $category      = getUserField('category');
        $info['table'] = CATEGORY_TBL;
        $info['where'] = 'name=\'' . $category . '\' AND category_type=\'Installed System\'';
        $result        = select($info);
        
        $categoryId    = $result[0]->id;
        
        $installedSystem->setCategoryId($categoryId);
        
        
        /**************************** UPLOAD THE DOCUMENT *******************************/
   
   	    $isSuccessful = $installedSystem->add();   	    
   	    
   	    if($installedSystem->id && $this->is_temp_uploaded=="yes")
   	    {
   	    	
   	    	$doc = $_SESSION['temp_file'];
   	    	$doc_file = explode("-", $doc);
   	    	$doc_file[1] = $installedSystem->id;
   	    	
   	    	$doc_new = implode("-",$doc_file);
   	    	   	    	
   	    	copy($this->tempUploadDir.$doc, $this->uploadDir.$doc_new);
   	    	$this->updateServiceContractDoc($installedSystem->id, $doc_new, "add");
   	    	unlink($this->tempUploadDir.$doc);
   	    	
   	    	//Unset Temp File
   	    	
   	    	unset($_SESSION['temp_file']);
   	    	
   	    	//$_SESSION['temp_file'] = "";

   	    }
   	    
   	    if($isSuccessful)
   	    {
   	    	  echo 'INSTALLED_SYSTEM_ADD,SUCCESS';
   	    }
   	    else
   	    {
   	    	  echo 'INSTALLED_SYSTEM_ADD,FAILED';
   	    }
   	    
   }
  
 function findServiceDoc($id)
 {
    $info = array();
  	$data = array();
  	$info['table'] = INSTALLED_SYSTEM_TBL;  	  	
  	$info['fields']  = array("service_contract_document as scd");
  	$info['where'] = "id=$id";
  	$info['debug'] = false;
  	
  	$res = select($info);
  	
  	if(count($res))
  	{
  		  foreach($res as $i=>$v)
  		  {
  		  	  $scd = $v->scd;
  		  	  break;
  		  }
  	}
  	
  	return $scd;
 }
 
 
 function addDoc()
 {
	
   	 $uploadDir = $this->uploadDir;
   	 $tempUploadDir = $this->tempUploadDir;
   	   	
   	 $id = $_REQUEST['installed_system_id'];
   	 $file = $_FILES['document_file']['name'];
   	 $file_to_upload = 'doc-'.$id.'-'.$file;
   	 
   	 if(empty($id))
   	 {
   	    $file_to_upload = 'doc-temp-'.$file;   	     
   	    $uploaded = copy($_FILES['document_file']['tmp_name'], $tempUploadDir.$file_to_upload);
   	 }   	  
   	 else
   	 {
   	 	
   	 	$uploaded = copy($_FILES['document_file']['tmp_name'], $uploadDir.$file_to_upload);
   	 }
   	 
   	 if(!empty($file))
   	 {
   	    if (!$uploaded) 
   	    {
            ;
            //echo '<script> alert("Failed to upload file");</script>';       
   	    }
   	    else 
   	    {
   	    	   if(empty($id))
   	    	   {   	     	   	  
   	    	   	  $_SESSION['temp_file'] = $file_to_upload;
   	        }
   	    	   else
   	    	   {
   	    	       
   	    	       
   	    	       $this->updateServiceContractDoc($id, $file_to_upload);
   	        }
   	    	  
   	        echo "File Uploaded";
   	        
   	    }
   	 }
	 
 }//EOFn



/* Will be updated for doc_tem_file 

 function addDoc()
 {
	
   	 $uploadDir = $this->uploadDir;
   	 $tempUploadDir = $this->tempUploadDir;
   	   	
   	 $id = $_REQUEST['installed_system_id'];
   	 $file = $_FILES['document_file']['name'];
   	 $file_to_upload = 'doc-'.$id.'-'.$file;
   	 
   	 if(empty($id))
   	 {
   	    $file_to_upload = 'doc-temp-'.$file;   	     
   	    $uploaded = copy($_FILES['document_file']['tmp_name'], $tempUploadDir.$file_to_upload);
   	 }   	  
   	 else
   	 {
   	 	$file_to_upload = 'doc-'.$id.'-temp-'.$file;
   	 	$uploaded = copy($_FILES['document_file']['tmp_name'], $tempUploadDir.$file_to_upload);
   	 }
   	 
   	 if(!empty($file))
   	 {
   	    if (!$uploaded) 
   	    {
            echo '<script> alert("Failed to upload file");</script>';       
   	    }
   	    else 
   	    {
   	    	   if(empty($id))
   	    	   {   	     	   	  
   	    	   	  $_SESSION['temp_file'] = $file_to_upload;
   	         }
   	    	   else
   	    	   {
   	    	       
   	    	       $_SESSION['doc_temp_file'] = $file_to_upload;
   	    	       //$this->updateServiceContractDoc($id, $file_to_upload);
   	        }
   	    	   //echo "<script> alert('File Uploaded');</script>";       	    
   	        echo "File Uploaded";
   	        //return $file_to_upload;
   	    }
   	 }
	 
 }//EOFn

*/
  function updateServiceContractDoc($id, $doc, $operation = "edit")
  {	  	
  	
  	$current_doc_file = trim($this->findServiceDoc($id));
  	
  	if($operation == "edit")
  	{
  	   unlink($this->uploadDir.$current_doc_file);
    }
  	
  	$info = array();
  	$data = array();
  	$info['table'] = INSTALLED_SYSTEM_TBL;  	
  	$data['service_contract_document'] = $doc; 
  	$info['data']  = $data;
  	$info['where'] = "id=$id";
  	$info['debug'] = false;
  	
  	$res = update($info);
  }

function delDoc()
{
	$uploadDir = $this->uploadDir;
	$tempUploadDir = $this->tempUploadDir;
	
	$id = trim(getUserField('id'));	
	
	$file = trim($this->findServiceDoc($id));
	
	if(!empty($id) && is_numeric($id))
	{
		$info['table'] = INSTALLED_SYSTEM_TBL;  	
  	$data['service_contract_document'] = ''; 
  	$info['data']  = $data;
  	$info['where'] = "id=$id";
  	$info['debug'] = true;
    $res = update($info);
    
    echo "->".$id."->".$uploadDir."->".$file."<-";
    
    unlink($uploadDir.$file);
    
	}
	else if(!empty($_SESSION['temp_file']))
	{
		  
		  $file = $_SESSION['temp_file']; 
		  
		  unset($_SESSION['temp_file']);
		  
		  if(unlink($tempUploadDir.$file))
		  {
		      echo "File Deleted";
	    }
	    else
	    {
	       echo "Can not Delete File: Permission Denied.";	
	    }
	}
	
	if($res)
	{
		if(unlink($uploadDir.$file))
		{
		   echo "File Deleted";
	  }
	  else
	  {
	     echo "Can not Delete File: Permission Denied.";	
	  }
	}
	else
	{
		  echo "File Can not be Deleted";
	}
}

function findFileById()
{
	  $system_id = trim(getUserField('installedSystemID'));
	  
	  if(empty($system_id))
	  {
	  	if($_SESSION['temp_file'])
	  	{
	  		  $file_to_find = $this->tempUploadDir.$_SESSION['temp_file'];
	  		  if(file_exists($file_to_find))
	  		  {
	  		     echo $_SESSION['temp_file'];
	  		    
	  		  }
	  		  else
	  		  {
	  		  	  echo "";
	  		  }
	  	}
	  }
	  else
	  {
	  	  $file = $this->findServiceDoc($system_id);
	  	  $file_to_find = $this->uploadDir.$file;
	  	  
	  	  if(file_exists($file_to_find))
 		    {                            
 		        echo $file;
 		                               
 		    }                            
 		    else                         
 		    {                            
 		  	    echo "";                 
 		    }                            
	  	  
	  }                                                          
} //EOFn
      
function formatDate($dateString)
{       
	  $dateArr = explode("/",trim($dateString));
	      
	  $year    = $dateArr[2];
	  $month =   $dateArr[0];
	  $date  =   $dateArr[1];
	  
	  $dateString = "$year-$month-$date";
	  
	  return $dateString;
	
}
}//EO Class

?>
