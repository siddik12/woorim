<?php

/**
 * File: login.class.php
 * This application is used to authenticate users
 *
 * @package LoginApp
 * @author  php@DGITEL.com
 * @version $Id$
 * @copyright 2005 DGITEL, Inc.
 *
 */

class LoginApp
{
   /**
   * This is the "main" function which is called to run the application
   *
   * @param none
   * @return true if successful, else returns false
   */
   function run()
   {
      $credentials = array();
		
      // Get the user supplied credentials
	  $company     = getRequest('company');
	  $loginid     = getRequest('loginid');
	 
      $credentials['userid']      = $loginid;
      //$credentials['userid']    = getRequest('loginid');
      $credentials['password']    = getRequest('password');
	  if($loginid == "" && getRequest('password') == ""){
		$infoMsg = "Please Enter valid userid and password...";
	  }else{
		  // Create a new user object with the credentials
		  $thisUser = new User($credentials);
	
		  // Authenticate the user
		  $ok = $thisUser->authenticate();
	
		  // If successful (i.e. user supplied valid credentials)
		  // show user home
			
		  if ($ok)      {   
		  		$this->getCompany(getFromSession('branch_id'));
				$this->getUserName(getFromSession('person_id'));
				$this->getDesig(getFromSession('person_id'));
				$userid = getFromSession('userid');
				$thisUser->goHome();
		  }   else   {
		  // User supplied invalid credentials so show login form
		  // again
			  //$data = array();
			  //$data = array_merge($_REQUEST, $data);
			  //$thisUser->goLogin();
			 $ErrMsg = "The userid or password you entered is incorrect.";
			  
		  }
	  }
			$company = $this->SelectCompany();
		  include_once(CURRENT_APP_SKIN_FILE);

      return true;
   }
   
   function getUserName($person_id){
   	
	  	$sql="select person_name, photo, email, phone from ".HRM_PERSON_TBL." where person_id = $person_id";
		$res= mysql_query($sql);
		while($row = mysql_fetch_array($res)){
			insertIntoSession('username', $row['person_name']);
			insertIntoSession('photo', $row['photo']);
			insertIntoSession('email', $row['email']);
			insertIntoSession('phone', $row['phone']);
		}
   }
 
 
    function getDesig($person_id){
   	
	  	$sql="select designation from hrm_designation d inner join hrm_employee e on e.desig_id=d.desig_id
		 where e.person_id = $person_id";
		$res= mysql_query($sql);
		while($row = mysql_fetch_array($res)){
			insertIntoSession('designation', $row['designation']);
		}
   }
 

 
 function getCompany($branch_id){
   	
	  	 $sql="select company_name, address, mobile, email,web,logo_path from settings ";
		$res= mysql_query($sql);
		$row = mysql_fetch_array($res);
			insertIntoSession('compcompany_name', $row['company_name']);
			insertIntoSession('compaddress', $row['address']);
			insertIntoSession('compemail', $row['email']);
			insertIntoSession('compmobile', $row['mobile']);
			insertIntoSession('compweb', $row['web']);
			insertIntoSession('complogo', $row['logo_path']);
			

   }
 
   
function SelectCompany(){ 
		$sql="SELECT company_id, company_name FROM  ".HRM_COMPANY_CATEGORY_TBL." ";
	    $result = mysql_query($sql);
	    $religion_select = "<select name='company' size='1' id='company' style='width:250px' class='textBox' onfocus='this.className='onFocus'' onblur='this.className='onBlur''>";

			while($row = mysql_fetch_array($result)) {
			$religion_select .= "<option value='".$row['company_id']."'>".$row['company_name']."</option>";	
		}
		$religion_select .= "</select>";
		return $religion_select;
	}

} // End class

?>
