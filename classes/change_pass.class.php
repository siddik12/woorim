<?php

/**
 * File: change_pass.class.php
 * This application is used to authenticate users
 *
 * @package LoginApp
 * @author  php@dgitel.com
 * @version $Id$
 * @copyright 2005 DGITEL, Inc.
 *
 */

class Change_Pass
{
   /**
   * This is the "main" function which is called to run the application
   * @return true if successful, else returns false
   */
   function run()
   {
      $cmd = getRequest('cmd');
      
      switch($cmd)
      {      
      	 case 'ViewEmployeeProfile'		: $this->ViewEmployeeProfile();   		break;
      	 case 'ViewPersonalProfile'  	: $this->ViewPersonalProfile();   		break;
 		 case 'PersonalPhotoUpSkin'  	: $this->PersonalPhotoUpSkin();      	break;
         case 'UploadPersonalPhoto'  	:  $this->UploadPersonalPhoto(); 		break;
         case 'add'						: $this->showEditor("change pass"); 	break;
         case 'change'					: $this->showSuccessText(); 			break;
         case 'fail'					: $this->showFailureText(); 			break;
         default						: $cmd = 'add'; $this->showEditor("change pass");
      }
      
      if($cmd=='add')
      {
         include_once(CURRENT_APP_SKIN_FILE);
      }
      
      return true;      
   } 
   
   //=========function showEditor===================== 
   function showEditor($msg=null)
   {
     $data['message'] = $msg;
          
     if(getRequest('submit'))
     {                
        $this->changePass();           
     }                  
   
   } 

   function changePass()
   {	
   	//==================values get from session==========================
   	
   		$userid            =  getFromSession('userid');
    	$old_password      =  getFromSession('password');
    	
    //==========values get from skin=================================== 
    	$current_password  =  md5(md5(getRequest('current_password')));
    	$new_password      =  md5(md5(getRequest('new_password')));
			
			$requestdata = array();
			$requestdata['new_password'] = $new_password;
		
   	if($new_password !="")
   	{ 		
   		 if($current_password == $old_password)
   			{
   				if($new_password!=$current_password)
   				{  
   		 		   $info['table']   = USER_TBL;
   		 		   $info['where']   = LOGIN_ID_FIELD. " = '$userid'";
   		 		   $info['data']    = array("password"=>"$requestdata[new_password]");	
   		 		   $info['debug']   = false;
   		 		   //dBug($info);
   		 		   if(update($info))
   		 		   {
   		 			   header("location:index.php?app=change_pass&cmd=change");	
   		 		   }
   		 		}header("location:index.php?app=change_pass&cmd=change");
   			}else header("location:index.php?app=change_pass&cmd=fail");
   			
   	}else header("location:index.php?app=change_pass&cmd=fail");
   }//==============EOFf changePass()========================
   
   function showSuccessText()
   {
         $data['message']  = "Password Changed Successfully.";

         include_once(CHANGE_PASS_SUCCESS_SKIN);
   }
   function showFailureText()
   {
         $data['message']  = "Password has not changed. Please, try again.";

         include_once(CHANGE_PASS_FAIL_SKIN);
   }
   
 function ViewPersonalProfile(){
  			$person_id = getFromSession('person_id');
 	           $sql='SELECT
						person_id,
						person_name,
						father,
						mother,
						birthday,
						sex,
						p.religion,
						p.blood_group,
						present_address,
						permanent_address,
						phone,
						email,
						photo,
						p.marital_status
				FROM
					'.HRM_PERSON_TBL.' p 
					inner join '.BLOOD_GROUP_TBL.' b on b.blood_group=p.blood_group
					inner join '.MARITAL_STATUS_TBL.' m on m.marital_status=p.marital_status
					inner join '.RELIGION_TBL.' r on r.religion=p.religion
					where person_id='.$person_id;
	
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
				$person_name				= $res['person_name'];
				$father				        = $res['father'];
				$mother				        = $res['mother'];
				$sex				    = $res['sex'];
				$birthday						= _date($res['birthday']);
				$email						= $res['email'];
				$present_address					= $res['present_address'];
				$permanent_address					= $res['permanent_address'];
				$phone			        	= $res['phone'];
				$religion			        = $res['religion'];
				$marital_status			    = $res['marital_status'];
				$blood_group			    = $res['blood_group'];
				$photo			    		= $res['photo'];
		
		require_once(PERSONAL_PROFILE_VIEW_SKIN2); 

				
	} // EOF 

 //-------------------------------- Personal Inage Upload-----------------------------------------------
function PersonalPhotoUpSkin(){
	require_once(PERSON_PHOTO_UP_SKIN);
}

function UploadPersonalPhoto(){
			$person_id=getFromSession('person_id');
			 $photoUp = $this->UplogoPath($person_id);
				if($photoUp != "Size must less than 100kb" && $photoUp != "Upload Error"){
					$this->updateLogoField($person_id, $photoUp);
					header("location:?app=change_pass&cmd=ViewPersonalProfile&EDmsg=Successfully Saved");
				}else{
					header("location:?app=change_pass&cmd=PersonalPhotoUpSkin&person_id=$person_id&EDmsg=Not Saved. Size must less than 100kb");
				}

		
} 

function updateLogoField($person_id, $photoUp){
	  $sql = "update ".HRM_PERSON_TBL." SET photo = '".$photoUp."' where person_id=$person_id";
	$res = mysql_query($sql);
}




function UplogoPath($person_id){
		$limit_size = 100000;
		$upload_dir = IMAGES_DIR."/photo";
		       
		 if($_FILES['photo']){
			
			$file_name = $_FILES['photo']['name'];
			$file_type    = trim(array_pop(explode('.', $file_name)));
			 $fname = $person_id.'.'.$file_type;
			$file_size = $_FILES['photo']['size'];
			
			if($file_size > $limit_size){
				return "Size must less than 100kb";
			}
			else{
				// check the same file exist
			 //$upload_dir."/".$fname;
				
				if (file_exists($upload_dir."/".$fname)) {
				  echo unlink($upload_dir."/".$fname);
					}
				 if(move_uploaded_file($_FILES['photo']['tmp_name'], "$upload_dir"."/".$fname)) {
					$msgImg = "Successful!";
					return "photo/".$fname;
				 }else{		return "Upload Error";	 }
		   }//end else		
		
	
	}//$_FILES['photo_path']
}
  
  
 function ViewEmployeeProfile(){
  			$employee_id = getFromSession('person_id');
 	           $sql='SELECT
					employee_id,
					e.desig_id,
					actual_join_date,
					resign_date,
					e.person_id,
					emptype,
					e.department_id,
					e.branch_id,
					department_name,
					designation,
					branch_name,
					person_name,
					user_group_id,
					photo
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					where e.person_id='.$employee_id;
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
				$person_name				= $res['person_name'];
				$employee_id				= $res['employee_id'];
				$designation				= $res['designation'];
				$department_name			= $res['department_name'];
				$branch_name				= $res['branch_name'];
				$actual_join_date			= _date($res['actual_join_date']);
				$emptype					= $res['emptype'];
				$photo						= $res['photo'];
				$user_group_id				= $res['user_group_id'];
		
		require_once(EMPLOYEE_PROFILE_VIEW_SKIN2); 

				
	} // EOF 
 
   
} // End class

?>
