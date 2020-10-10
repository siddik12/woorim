<?php
ini_set("display_errors","off");
ob_start();
define('PROJECT_DIR', '/woorim');
date_default_timezone_set('Asia/Dhaka');

require_once($_SERVER['DOCUMENT_ROOT'].PROJECT_DIR. '/configs/common/main.conf.php');

/*
	$userid = getFromSession('userid');
	
	it will be used any where within the application.
	so, don't use $userid variable in other place.
	And you don't need to get from session
*/

$userid = getFromSession('userid');
$user_group_id = getFromSession('user_group_id');
$logintype = getFromSession('company');
$username = getFromSession('username');

/* echo '<br><br><br>';
echo session_id();
echo '<pre>';
print_r($_SESSION);*/

//Gets the current application
$currentApp = getRequest('app');

switch($currentApp)
{
   case 'accounts':   break;
   case 'accounts_total':   break;
   case 'accounts_whole':   break;
   case 'accounts_total_whole':   break;
   case 'admin':   break;
   case 'adjustment_type':   break;
   case 'adjustment_entry':   break;
   case 'banking':			break;
   case 'bkash_acc_total':			break;
   case 'bill_type':   break;
   case 'branch':   break;
   case 'change_pass':  break;
   case 'customer_info':  break;
   case 'customer_info_whole':  break;
   case 'customer_ob':  break;
   case 'cash_deposit':  break;
   case 'department':  break;
   case 'designation':  break;
   case 'dsr_collection':  break;
   case 'employee':  break;
   case 'finance':	break;
   case 'fixed_asset':	break;
   case 'fixed_asset_entry':	break;
   case 'forgot_passwd':   break;
   case 'group_dashboard':   break;
   case 'godown_entry':   break;
   case 'inv_supplierinfo':   break;
   case 'institute':   break;
   case 'institute_head':   break;
   case 'item_distribute':   break;
   case 'inv_report_generator':   break;
   case 'inv_report_generator_head':   break;
   case 'inv_item_cat_main':  break;
   case 'inv_item_cat_sub':  break;
   
   case 'inv_item':  break;
   case 'inv_item_issue':   break;

   case 'inv_item_sales_whole':   break;

   case 'inv_item_info':   break;
   case 'inv_item_sales':   break;
   case 'inv_item_sales_edit':   break;
   case 'inv_item_sales_emi':   break;
   case 'inv_item_sales_emi_edit':   break;
   case 'inv_item_sales_return':   break;
   case 'inv_item_sales_list':   break;
   case 'inv_item_sales_list_whole':   break;
   case 'inv_item_sales_return_whole':   break;
   case 'whole_sales_return':   break;
   
   case 'share_holder':   break;
   case 'invest':   break;
   case 'invest_exp':   break;
   case 'invest_profit':   break;

   case 'stock_out':   break;
   case 'item_return':   break;
   
   case 'login':  break;
   case 'search':  break;
   case 'schedule':  break;
   case 'SrBillReport':  break;
   case 'userhome':  break;   
   case 'summary_report':  break;   
   case 'supplier_payments':   break;
   case 'other_supplier_acc':   break;
   case 'other_supplier_total_acc':   break;
   case 'bkash_trans':   break;
   case 'logout':
   		//logoutLogged();
	   	session_unset();
	   	session_destroy();
	   	header("Location:".LOGIN_URL);
   break;
   default:
	   header("Location:".USER_HOME_URL);
   break;
}
include_once("apps/$currentApp.php");
//include( dirname(__FILE__) . "/phpjobscheduler/firepjs.php"); 
?>