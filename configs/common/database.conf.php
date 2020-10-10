<?php
   /*******************************************************
    *  File name: database.conf.php
    *
    *  Purpose: this file is used to store database
    *           table name constants and it also starts
	
    *           the database connection
    *
    *  CVS ID: $Id$
    *
    ********************************************************/

   // If main configuration file which defines VERSION constant
   // is not loaded, die!
/*  
 if (! defined('VERSION'))
   {
      echo "You cannot access this file directly!";
      die();
   }
*/
   // Please note:
   // in production mode, the database authentication information
   // may vary.
   
   define('PRODUCTION_MODE', TRUE);
  
/*   if (PRODUCTION_MODE)
   {
     define('DB_USER', 'sarkerit_worimdb');
     define('DB_PASS', 'MkEWF?mEZ34+');

     define('DB_NAME', 'sarkerit_woorim');
     define('DB_HOST', 'localhost');
   } 
*/   
    if (PRODUCTION_MODE)
   {
     define('DB_USER', 'sumon');
     define('DB_PASS', '123');

     define('DB_NAME', 'woorim');
     define('DB_HOST', 'localhost');
   }

   
//===================For Inventory===================
	    
   // TABLES
   define('ADMIN_TYPE_TBL', DB_NAME . '.admin_type');
   define('DEPARTMENT_TBL', DB_NAME . '.smis_department');
   define('DESIGNATION_TBL', DB_NAME . '.hrm_designation');
   define('HRM_PERSON_TBL', DB_NAME . '.hrm_person');
   define('HRM_EMPLOYEE_TBL', DB_NAME . '.hrm_employee');
   define('HRM_RELIGION_TBL', DB_NAME . '.hrm_religion');
   define('BLOOD_GROUP_TBL', DB_NAME . '.blood_group');
   define('MARITAL_STATUS_TBL', DB_NAME . '.hrm_marital_status');
   define('RELIGION_TBL', DB_NAME . '.hrm_religion');
   define('BRANCH_TBL', DB_NAME . '.branch');
   define('SETTINGS_TBL', DB_NAME . '.settings');
   define('HRM_COMPANY_CATEGORY_TBL', DB_NAME . '.company_category ');
   define('HRM_DISTRICT_CITY_TBL', DB_NAME . '.district_city');
   define('HRM_DIVISION_STATE_TBL', DB_NAME . '.division_state');
   define('HRM_COUNTRY_TBL', DB_NAME . '.country');
   define('WHOLE_SALER_ACC_TBL', DB_NAME . '.whole_saler_acc');
   define('RETAIL_SALER_ACC_TBL', DB_NAME . '.retail_saler_acc');
   define('WARE_HOUSE_TBL', DB_NAME . '.ware_house');
   define('OTHERS_SUPPLIER_ACC_TBL', DB_NAME . '.others_supplier_acc');
   define('BKASH_TRANS_TBL', DB_NAME . '.bkash_transaction');
   define('SHARE_HOLDER_TBL', DB_NAME . '.share_holder');
   define('INVEST_INCOME_TBL', DB_NAME . '.invest_income');

   define('INV_CATEGORY_MAIN_TBL', DB_NAME . '.inv_item_category_main');// Start  Inventory Management System Table
   define('INV_CATEGORY_SUB_TBL', DB_NAME . '.inv_item_category_sub');
   define('INV_ITEMINFO_TBL', DB_NAME . '.inv_iteminfo');
   define('INV_ITEM_MEASURE_TBL', DB_NAME . '.inv_item_measurement');
   define('INV_RECEIVE_MASTER_TBL', DB_NAME . '.inv_receive_master');
   define('INV_RECEIVE_DETAIL_TBL', DB_NAME . '.inv_receive_detail');
   define('INV_ITEM_ISSUE_MAIN_TBL', DB_NAME . '.inv_item_issue_main');
   define('INV_ITEM_ISSUE_SUB_TBL', DB_NAME . '.inv_item_issue_sub');
   define('ACCOUNTS_CHART_TBL',  DB_NAME . '.accounts_chart'); 
   define('DAILY_ACC_LEDGER_TBL',  DB_NAME . '.daily_acc_ledger'); 
   define('GENERAL_LEDGER_TBL', DB_NAME . '.general_ledger');
   define('INV_BILL_TYPE_TBL', DB_NAME . '.inv_bill_type');
   define('INV_PENDING_BILL_TBL', DB_NAME . '.inv_pending_bill');
   define('SR_BILL_TBL', DB_NAME . '.inv_sr_account');
   define('BILL_MONTH_TBL', DB_NAME . '.pending_bill_month');
   define('OPENING_BALANCE_TBL', DB_NAME . '.opening_balance');
   define('INV_RECEIVE_FREE_TBL', DB_NAME . '.inv_receive_free');
   define('INV_ISSUE_FREE_TBL', DB_NAME . '.inv_item_issue_free');
   define('ACC_DETAILS_TBL', DB_NAME . '.detail_account');
   define('ADJUSTMENT_ENTRY_TBL', DB_NAME . '.adjustment_entry');
   define('ADJUSTMENT_TYPE_TBL', DB_NAME . '.adjustment_type');

   define('BANK_INFO_TBL',       			DB_NAME . '.bank_info');
   define('BANK_TRANS_TBL',       			DB_NAME . '.bank_trans');

   define('INV_ITEM_RECEIVE_TBL', DB_NAME . '.inv_item_receive');
   define('INV_ITEM_ISSUE_TBL', DB_NAME . '.inv_item_issue');
   define('INV_ITEM_SALES_TBL', DB_NAME . '.inv_item_sales');
   define('INV_ITEM_SALES_PAYMENT_TBL', DB_NAME . '.inv_item_sales_payment');
   
   define('FIXED_ASSET_TBL', DB_NAME . '.fixed_asset_type');
   define('FIXED_ASSET_ENTRY_TBL', DB_NAME . '.fixed_asset_entry');
   define('INV_ITEM_TYPE_TBL', DB_NAME . '.inv_itemtype');
   define('INV_SUPPLIER_INFO_TBL', DB_NAME . '.inv_supplier_info');
   define('CUSTOMER_INFO_TBL', DB_NAME . '.customer_info');
   define('INV_SALSEMAN_INFO_TBL', DB_NAME . '.sales_maninfo');

   // VIEWS
   define('VW_CONDENSE_MILK', DB_NAME . '.view_condense_milk');
   define('VW_BAVERAGE', DB_NAME . '.view_beverage');
   define('VW_POEDER_MILK', DB_NAME . '.view_powder_milk');
   define('VW_TEA', DB_NAME . '.view_tea');
   define('VW_BRVERAGE', DB_NAME . '.view_beverage');
   define('VW_SNACKS', DB_NAME . '.view_snaks');
   define('HRM_EMPLOYEE_VW', DB_NAME . '.hrm_vw_employee');

   define('AUTO_CONNECT_TO_DATABASE', TRUE);   

  if (AUTO_CONNECT_TO_DATABASE)
  {
      $dbcon = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Could not connect: " . mysql_error());
      mysql_select_db(DB_NAME, $dbcon) or die("Could not find: " . mysql_error());
	  
  }

?>
