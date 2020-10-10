<?php


 /**
 * File: InstalledSystem.class.php
 * Purpose: This class defines the ServiceOrder object
 * @author  php@DGITEL.com
 * @version $Id$
 * @copyright 2006 DGITEL, Inc.
 *
 */


 class InstalledSystem
 {
     var $id;                      // Stores the id of this installed system.
     var $customerID;              // Indicates the customer for this installed system.
     var $customerNumber;          // Stores the customer number.
     var $customerCompany;         // Stores the name of the customer company.
     var $categoryID;              // Indicates the category of this installed system.
     var $status;                  // Indicates the status of this installed system.
     var $vendor;                  // Stores the vendor of this installed system.
     var $model;                   // Stores the model of this installed system.
     var $serialNumber;            // Stores the serial number of this installed system.
     var $dateInstalled;           // Stores the date when this installed system was installed.
     var $dateLastServiced;        // Stores the date when this installed system was last serviced.
     var $nextScheduledMaintDate;  // Stores the next scheduled maintenance date for this installed system.
     var $contractRenewalDate;     // Stores the contract renewal date for this installed system.
     var $contractType;            // Indicates the type of contract for this installed system.
     var $purchasePrice;           // Stores the purchase price for this installed system.
     var $serviceContract;         // Indicates whether there is a service contract for this installed system.
     var $serviceContractDocument; // Indicates name of service contract document for this installed system.
     var $comments;                // Stores any comments for this installed system.
     var $ITSystemID;              // Stores the it_system_id for this installed system.
     var $warrantyExpirationDate;  // Stores the warranty expiration date.
     
     /*
     * Constructor - If an ID is provided it loads an Installed System
     *             - from the database. Otherwise, it initializes the
     *             - member variables.
     *
     * @params associative array
     * @return InstalledSystem - If id provided
     *         none            - otherwise
     */

     function InstalledSystem($params = null)
     {
         // If id is supplied then load from database, else initialize member variables with the parameters supplied
         if($params['id'] != null)
         {
             $this->load($params['id']);
         }
         else
         {
             $this->customerCompany         = $params['customerCompany'];
             $this->categoryID              = $params['categoryID'];
             $this->status                  = $params['status'];
             $this->vendor                  = $params['vendor'];
             $this->model                   = $params['model'];
             $this->serialNumber            = $params['serialNumber'];
             $this->dateInstalled           = $params['dateInstalled'];
             $this->dateLastServiced        = $params['dateLastServiced'];
             $this->nextScheduledMaintDate  = $params['nextScheduledMaintDate'];
             $this->contractRenewalDate     = $params['contractRenewalDate'];
             $this->contractType            = $params['contractType'];
             $this->purchasePrice           = $params['purchasePrice'];
             $this->serviceContract         = $params['serviceContract'];
             $this->serviceContractDocument = $params['serviceContractDocument'];
             $this->comments                = $params['comments'];
             $this->ITSystemID              = $params['ITSystemID'];
             $this->warrantyExpirationDate  = $params['warrantyExpirationDate'];
             $this->setCustomerId();        
             $this->setCustomerNumber();    
         }                                  

     }


     /**
     * This method loads an installed system from the database
     * @param Installed System ID
     * @return true on success and false when failed
     */
     function load($id = null)
     {
         // If no id is supplied then get ready to reload this from the database.
         if(empty($id))
         {
             $id = $this->id;
         }

         // If neither id is supplied during the function call nor the member variable id is set then don't go for SQL query.
         if(!empty($id))
         {
             $info['table'] = INSTALLED_SYSTEM_TABLE;
             $info['where'] = 'id=' . $id;
             $result = select($info);
         }

         // Finall, if result set is empty then return false, otherwise set the member variables with the result set and return true.
         if(empty($result))
         {
             return false;
         }

         $this->id                     = $result[0]->id;
         $this->customerID             = $result[0]->customer_id;
         $this->categoryID             = $result[0]->category_id;
         $this->status                 = $result[0]->status;
         $this->vendor                 = $result[0]->vendor;
         $this->model                  = $result[0]->model;
         $this->serialNumber           = trim($result[0]->serial_number);
         $this->dateInstalled          = $result[0]->date_installed;
         $this->dateLastServiced       = $result[0]->date_last_serviced;
         $this->nextScheduledMaintDate = $result[0]->next_scheduled_maint_date;
         //$this->contractRenewalDate    = date("m/d/Y",trim($result[0]->contract_renewal_date)); //explode & convert..
         $this->contractRenewalDate    = trim($result[0]->contract_renewal_date);
         $this->contractType           = $result[0]->contract_type;
         $this->purchasePrice          = $result[0]->purchase_price;
         $this->serviceContract        = $result[0]->service_contract;
         $this->serviceContractDocument= $result[0]->service_contract_document;
         $this->comments               = $result[0]->comments;
         $this->ITSystemID             = $result[0]->it_system_id;
         $this->warrantyExpirationDate = $result[0]->warranty_expiration;
         $this->setCustomerNumber();
         $this->setCustomerCompany();
        

         return true;

     }


     /**
     * Returns the id of this installed system.
     * @param none
     * @return installed system's ID
     */
     function getId()
     {
         return (!empty($this->id)) ? $this->id : null;
     }


     /**
     * Returns the id of the corresponding customer.
     * @param none
     * @return customer ID
     */
     function getCustomerId()
     {
         return (!empty($this->customerID)) ? $this->customerID : null;
     }

     /**
     * Returns the customer number.
     * @param none
     * @return none
     */
     function getCustomerNumber()
     {
         return (!empty($this->customerNumber)) ? $this->customerNumber : null;
     }
     
     /**
     * Returns the customer company name.
     * @param none
     * @return customer company name
     */
     function getCustomerCompany()
     {
     	   // if customer company name is set then return it
     	   if(!empty($this->customerCompany))
         return $this->customerCompany;
     }

     /**
     * Returns the id corresponding IT system.
     * @param none
     * @return IT system ID
     */
     function getItSystemId()
     {
         return (!empty($this->ITSystemID)) ? $this->ITSystemID : null;
     }


     /**
     * Returns the id of the corresponding category.
     * @param none
     * @return category ID
     */
     function getCategoryId()
     {
         return (!empty($this->categoryID)) ? $this->categoryID : null;
     }


     /**
     * Returns the status of this installed system.
     * @param none
     * @return status
     */
     function getStatus()
     {
         return (!empty($this->status)) ? $this->status : null;
     }


     /**
     * Returns the vendor of the instead system.
     * @param none
     * @return vendor
     */
     function getVendor()
     {
         return (!empty($this->vendor)) ? $this->vendor : null;
     }


     /**
     * Returns the model of the instead system.
     * @param none
     * @return installed system's model
     */
     function getModel()
     {
         return (!empty($this->model)) ? $this->model : null;
     }

   /**
   * Returns the serial number of the instead system. Every Installed system is given a serial number.
   * @return serial number
   */
   function getSerialNumber()
   {
      return (!empty($this->serialNumber)) ? $this->serialNumber : null;	
   }
   /**                                                                                              
   * Returns the name of Service Contract Document of the installed system.                                                            
   * @param none                                                                                        
   * @return installed system's Service Contract Document                                                                 
   */                                                                                                    
   
   function getServiceContractDoc()                                                                               
   {                                                                                                     
       return (!empty($this->serviceContractDocument)) ? $this->serviceContractDocument : null;                                              
   }                                                                                                         
                                                                                                        
                                                                                                    

     /**
     * Returns the date of installation.
     * @param none
     * @return date
     */
     function getDateInstalled()
     {
         return (!empty($this->dateInstalled)) ? $this->dateInstalled : null;
     }


     /**
     * Returns the date of last service.
     * @param none
     * @return date
     */
     function getDateLastServiced()
     {
         return (!empty($this->dateLastServiced)) ? $this->dateLastServiced : null;
     }


     /**
     * Returns the next scheduled maintainance date of this installed system.
     * @param none
     * @return date
     */
     function getNextMaintDate()
     {
         return (!empty($this->nextScheduledMaintDate)) ? $this->nextScheduledMaintDate : null;
     }


     /**
     * Returns the contract renewal date of this installed system.
     * @param none
     * @return date
     */
     function getContractRenewalDate()
     {
         return (!empty($this->contractRenewalDate)) ? $this->contractRenewalDate : null;
     }
     
    
     /**
     * Returns the contract type of this installed system.
     * @param none
     * @return contract type
     */
     function getContractType()
     {
         return (!empty($this->contractType)) ? $this->contractType : null;
     }


     /**
     * Returns the purchase price for the installed system.
     * @param none
     * @return purchase price
     */
     function getPurchasePrice()
     {
         return (!empty($this->purchasePrice)) ? $this->purchasePrice : null;
     }


     /**
     * Returns the service contract price for the installed system.
     * @param none
     * @return date
     */
     function getServiceContract()
     {
         return (!empty($this->serviceContract)) ? $this->serviceContract : null;
     }


     /**
     * Return the warranty expiration date of this installed system
     * @param none
     * @return date
     */
     function getWarrantyExpiration()
     {
         return (!empty($this->warrantyExpirationDate)) ? $this->warrantyExpirationDate : null;
     }


     /**
     * Returns the comments.
     * @param none
     * @return comment
     */
     function getComments()
     {
         return (!empty($this->comments)) ? $this->comments : null;
     }

     /**
     * Sets the id of this installed system.
     * @param  id
     * @return none
     */
     function setId($id = null)
     {
         $this->id = $id;
     }


     /**
     * Sets the number of the corresponding customer.
     * @param customer number
     * @return none
     */
     function setCustomerNumber()
     {
          if(empty($this->customerID))
          {
          	   $this->customerNumber = null;
          }
          else
          {
          	   $info['table'] = CUSTOMER_TABLE;
          	   $info['where'] = 'id=' . $this->customerID;
          	   $result        = select($info);
          	   
          	   $this->customerNumber = $result[0]->customer_number;
          }
 
     }
     
     /**
     * Sets the id of the corresponding customer.
     * @param none
     * @return none
     */
     
     function setCustomerId($id = null)
     {
     	   if(!empty($id))
     	   {
     	   	   $this->customerID = $id;
     	   }
     	   else
     	   {
             if(empty($this->customerCompany))
             {
             	   $this->customerID = null;
             }
             else
             {
             	   $info['table'] = CUSTOMER_TABLE;
             	   $info['where'] = 'customer_number=\'' . $this->customerCompany . '\'';
             	   $result        = select($info);
             	   
             	   $this->customerID = $result[0]->id;
             }
         }
         	
     }
     
     /**
     * Sets the customer company name.
     * @param customer company name
     * @return none
     */
     function setCustomerCompany($company = null)
     {
     	   if(!empty($company))
     	   {
     	   	   $this->customerCompany = $company;
     	   }
     	   else
     	   {
             // if customer company id is not set then return null
             if($this->customerID == null)
             return null;
             
             // else find out the customer company name, set the memeber variable and return the name
             $info['table'] = CUSTOMER_TABLE;
             $info['where'] = 'id=' . $this->customerID;
             $result        = select($info);
             
             $this->customerCompany = $result[0]->company;
         }
     }


     /**
     * Sets the id corresponding IT system.
     * @param IT system ID
     * @return none
     */
     function setItSystemId($id = null)
     {
         $this->ITSystemID = $id;
     }


     /**
     * Sets the id of the corresponding category.
     * @param category id
     * @return none
     */
     function setCategoryId($id = null)
     {
         $this->categoryID = $id;
     }


     /**
     * Sets the status of this installed system as argument supplied
     * @param status
     * @return none
     */
     function setStatus($status = null)
     {
         $this->status = $status;
     }


     /**
     * Sets the vendor of the instead system.
     * @param vendor
     * @return none
     */
     function setVendor($vendor = null)
     {
         $this->vendor = $vendor;
     }


     /**
     * Sets the model of the instead system.
     * @param installed system's model
     * @return none
     */
     function setModel($model = null)
     {
         $this->model = $model;
     }


     /**
     * Sets the serial number of the instead system. Every Installed system is given a serial number.
     * @param serial number
     * @return none
     */
     function setSerialNumber($num = null)
     {
         $this->serialNumber = $num;
     }


     /**
     * Sets the date of installation.
     * @param date
     * @return none
     */
     function setDateInstalled($date = null)
     {
         $this->dateInstalled = $date;
     }


     /**
     * Sets the date of last service.
     * @param date
     * @return none
     */
     function setDateLastServiced($date = null)
     {
         $this->dateLastServiced = $date;
     }


     /**
     * Sets the next scheduled maintainance date of this installed system.
     * @param date
     * @return none
     */
     function setNextMaintDate($date = null)
     {
         $this->nextScheduledMaintDate = $date;
     }


     /**
     * Sets the contract renewal date of this installed system.
     * @param date
     * @return none
     */
     function setContractRenewalDate($date = null)
     {
         $this->contractRenewalDate = $date;
     }


     /**
     * Sets the contract type of this installed system as argument supplied
     * @param contract type
     * @return none
     */
     function setContractType($type = null)
     {
         $this->contractType = $type;
     }


     /**
     * Sets the purchase price for the installed system.
     * @param price
     * @return none
     */
     function setPurchasePrice($price = null)
     {
         $this->purchasePrice = $price;
     }


     /**
     * Sets the service contract price for the installed system.
     * @param service contract
     * @return none
     */
     function setServiceContract($serviceContract = null)
     {
         $this->serviceContract = $serviceContract;
     }

     /**
     * Sets the service contract document for the installed system.
     * @param service contract document
     * @return none
     */
     
     function setServiceContractDoc($serviceContractDoc)
     {
         $this->serviceContractDocument = $serviceContractDoc;
     }

     /**
     * Sets the warranty expiration date of this installed system
     * @param date
     * @return none
     */
     function setWarrantyExpiration($date = null)
     {
         
         $this->warrantyExpirationDate = $date;
     }


     /**
     * Sets the comments.
     * @param comment
     * @return none
     */
     function setComments($comments = null)
     {
         $this->comments = $comments;
     }


     /**
     * This method adds a new installed system in the database.
     * @param none
     * @return true on success and false when failed
     */
     function add()
     {
         //$info['table'] = INSTALLED_SYSTEM_TABLE;
         $info = array();
         $info['table'] = INSTALLED_SYSTEM_TABLE;

         $data['customer_id']               = $this->customerID;
         $data['category_id']               = $this->categoryID;
         $data['status']                    = $this->status;
         $data['vendor']                    = $this->vendor;
         $data['model']                     = $this->model;
         $data['serial_number']             = $this->serialNumber;
         $data['date_installed']            = $this->dateInstalled;
         $data['date_last_serviced']        = $this->dateLastServiced;
         $data['next_scheduled_maint_date'] = $this->nextScheduledMaintDate;
         $data['contract_renewal_date']     = $this->contractRenewalDate;
         $data['contract_type']             = $this->contractType;
         $data['purchase_price']            = $this->purchasePrice;
         $data['service_contract']          = $this->serviceContract;
         $data['service_contract_document'] = $this->serviceContractDocument;
         $data['comments']                  = $this->comments;
         $data['it_system_id']              = $this->ITSystemID;
         $data['warranty_expiration']       = $this->warrantyExpirationDate;
         
         $info['data'] = $data;
         $info['debug'] = false;
         
         
         $ret=insert($info);

         // If adding was successful set the member variable id to new id and return true
         // otherwise, set id to null and return false
         if(!empty($ret['newid']))
         {
             $this->id = $ret['newid'];
             return true;
         }
         else
         {
             $this->id = null;
             return false;
         }

     }


     /**
     * This method updates this installed system in the database.
     * @param none
     * @return true on success and false when failed
     */
     function update()
     {
         // If $this->id is not set then return false
         if(empty($this->id))
         {
             return false;
         }

         $info['table'] = INSTALLED_SYSTEM_TABLE;
         $info['where'] = 'id=' . $this->id;

         $data['customer_id']               = $this->customerID;
         $data['category_id']               = $this->categoryID;
         $data['status']                    = $this->status;
         $data['vendor']                    = $this->vendor;
         $data['model']                     = $this->model;
         $data['serial_number']             = $this->serialNumber;
         $data['date_installed']            = $this->dateInstalled;
         $data['date_last_serviced']        = $this->dateLastServiced;
         $data['next_scheduled_maint_date'] = $this->nextScheduledMaintDate;
         $data['contract_renewal_date']     = $this->contractRenewalDate;
         $data['contract_type']             = $this->contractType;
         $data['purchase_price']            = $this->purchasePrice;
         $data['service_contract']          = $this->serviceContract;
         $data['comments']                  = $this->comments;
         $data['it_system_id']              = $this->ITSystemID;
         $data['warranty_expiration']       = $this->warrantyExpirationDate;

         $info['data'] = $data;
         $info['debug'] = false;
         $isSuccessful = update($info);
         
         return $isSuccessful;

     }



     /**
     * This method deletes this installed system from the database
     * @param none
     * @return true on success and false when failed
     */
     function delete()
     {
         // If $this->id is not set then return false
         if(empty($this->id))
         {
             return false;
         }

         $info['table'] = INSTALLED_SYSTEM_TABLE;
         $info['where'] = 'id=' . $this->id;
         $isSuccessful  = delete($info);

         return $isSuccessful;
     }

 }

?>