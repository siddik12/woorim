
<?php


class personal_profile
{
   
  function run()
  {
      $cmd = getRequest('cmd');
	  $user_group_id = getFromSession('user_group_id'); 
		
	  if($user_group_id == 1){
		  switch ($cmd)  {
			 case 'editAllPerson'      : $this->editAllPersonalProfile(); 							break;
			 case 'AdminAccessEditUploadPhoto': $this->AdminAccessEditUploadImage(getRequest('person_id')); break;
			 case 'ListAllPerson'      : $this->ListOfAllpersons();        			                break;
			 case 'view'               : $this->fetchPersonalProfile();   							break;
			 case 'save'               : $screen = $this->addPersonalProfile();   					break;
			 case 'edit'               : $this->editPersonalProfile(); 								break;
			 case 'EditUploadPhoto'    : $this->editUploadImage(getRequest('person_id')); 			break;
			 case 'ajaxSearch'         : echo $this->PersonalProfileFetch();  						break;
			 case 'delete'             : $screen = $this->DeletePersonalProfile();					break;
			 /*case 'fetch'              : $this->fetchPersonalProfile(getRequest('person_id'));   	break;
			 case 'list'               : $this->getList();   										break; */
			 default                   : header('location:?app=userhome');				break;
		  }	  
	  }else{

		  switch ($cmd)  {
			 case 'view'               : $this->fetchPersonalProfile();   							break;
			 case 'save'               : $screen = $this->addPersonalProfile();   					break;
			 case 'edit'               : $this->editPersonalProfile(); 								break;
			 case 'EditUploadPhoto'    : $this->editUploadImage(getRequest('person_id')); 			break;
			 case 'ajaxSearch'         : echo $this->PersonalProfileFetch();  						break;
			 case 'delete'             : $screen = $this->DeletePersonalProfile();					break;
			 /*case 'fetch'              : $this->fetchPersonalProfile(getRequest('person_id'));   	break;
			 case 'list'               : $this->getList();   										break; */
			 default                   : header('location:?app=userhome');				break;
		  }
	  }
 }
 
 
/************************ To show all person list for admin***********************************
**********************************************************************************************/
 function ListOfAllpersons(){
	 $List = $this->PersonalProfileFetch();
	 require_once(ADMIN_ACCESS_PERSONAL_PROFILE_SKIN);
 }
 /*************************************Edit all person list for Admin****************************
 ************************************************************************************************/
 function editAllPersonalProfile(){

	        $person_id = getRequest('person_id');
			$sql="SELECT person_id, person_name, nick_name, photo, birth_date,gender, father_name, mother_name,mobile, 
				  blood_group, religion, email, im,work_phone, national_id, nationality, present_address, present_city,                  present_division, present_country,present_zip_code, permanent_address, permanent_city, permanent_division,                  permanent_country, permanent_zip_code,marital_status, notes, person_type  
		          FROM ".HRM_PERSON_TBL."  
 	   	          WHERE person_id =".$person_id;
				 // echo $sql;

	   	$ros = mysql_query($sql);
			
		 while($res = mysql_fetch_array($ros))	{
		 		$person_id				=$res['person_id'];
				$person_name				=$res['person_name'];
				$nick_name					=$res['nick_name'];
				$photo						=$res['photo'];
				$birth_date					=formatDateDMY($res['birth_date']);
				$gender						=$res['gender'];
				$father_name				=$res['father_name'];
				$mother_name				=$res['mother_name'];
				$marital_status				=$res['marital_status'];
				$mobile						=$res['mobile'];
				$blood_group				=$res['blood_group'];
				$religion					=$res['religion'];
				$email						=$res['email'];
				$im							=$res['im'];
				$work_phone					=$res['work_phone'];
				$national_id				=$res['national_id'];
				$nationality				=$res['nationality'];
				$present_address			=$res['present_address'];
				$present_city				=$res['present_city'];
				$present_division			=$res['present_division'];
				$present_country			=$res['present_country'];
				$present_zip_code			=$res['present_zip_code'];
				$permanent_address			=$res['permanent_address'];
				$permanent_city			    =$res['permanent_city'];
				$permanent_division			=$res['permanent_division'];
				$permanent_country			=$res['permanent_country'];
				$permanent_zip_code			=$res['permanent_zip_code'];
				$notes						=$res['notes'];
		        $person_type				=$res['person_type']; 
			}
	 $marital_status_dropdown = $this->SelectMaritalStatus($marital_status);
	 $religion_dropdown = $this->SelectReligion($religion);
	 $blood_group_dropdown = $this->SelectBloodGroup($blood_group);
	 $person_type_dropdown = $this->SelectPersonType($person_type);
	 $present_district_dropdown = $this->SelectPresentDistrict($present_city);
	 $present_dividion_dropdown = $this->SelectPresentDivision($present_division);
	 $present_country_dropdown = $this->SelectPresentCountry($present_country);
	 $permanent_district_dropdown = $this->SelectPermanentDistrict($permanent_city);
	 $permanent_division_dropdown = $this->SelectPermanentDivision($permanent_division);
	 $permanent_country_dropdown = $this->SelectPermanentCountry($permanent_country);
	 //$this->fetchPersonalProfile();
		if ((getRequest('submit'))) {
		 $person_id = getRequest('person_id');
	 	 $info = array();
		 $reqdata = array();
		 $info['table'] = HRM_PERSON_TBL;
		 $reqdata = getUserDataSet(HRM_PERSON_TBL);
		/*$photoUp = $this->PhotoUpload($person_id);
			if($photoUp != "Size Error" && $photoUp != "Upload Error"){
				$reqdata['photo'] = $photoUp;
			}*/
		 $reqdata['birth_date'] = formatDate4insert(getRequest('birth_date'));
		 $info['data'] = $reqdata;
      	 $info['where']= "person_id = $person_id";
		 $info['debug']  = true;
		$res = update($info);
		if($res){
			header("location:index.php?app=personal_profile&cmd=ListAllPerson&Edmsg=Successfully Edited!");
		}else{
			header("location:index.php?app=personal_profile&cmd=ListAllPerson&Edmsg=Not Edited!");
		}
	}					     
	require_once(ADMIN_EDIT_ALL_PERSONAL_PROFILE_SKIN);

		
		
}
/***************************Image Upload for person by Admin******************************
******************************************************************************************/
function AdminAccessEditUploadImage($person_id){
				 $sql="SELECT photo
		          FROM ".HRM_PERSON_TBL." WHERE person_id = ".$person_id;
				 // echo $sql;

	   	$ros = mysql_query($sql);
			
		 while($res = mysql_fetch_array($ros))	{
		 		//$person_id				=$res['person_id'];
				$person_photo			=$res['photo'];
			}
		require_once(ACCESS_ADMIN_PHOTO_UPLOAD_SKIN);
	 //$this->fetchPersonalProfile();
		if ((getRequest('submit'))) {
			 echo $photoUp = $this->PhotoUpload($person_id);
				if($photoUp != "Size Error" && $photoUp != "Upload Error"){
					//$reqdata['photo'] = $photoUp;
					$this->updatePhotoField($person_id, $photoUp);
					//insertIntoSession('photo',$photoUp);
					header("location:index.php?app=personal_profile&cmd=ListAllPerson&Editmsg=Successfully Edited Image!");
				}else{
					header("location:index.php?app=personal_profile&cmd=ListAllPerson&Editmsg=Not Edited Image!");
				}

		}			     
		
}
/**********************************Edit person photo by admin********************************
*********************************************************************************************/
/*function AdminAccessPhotoEdit(){
	        require_once(ACCESS_ADMIN_PHOTO_EDIT_SKIN);  
			$person_id = getRequest('person_id');
			$sql="SELECT person_id, photo
		          FROM hrm_person
 	   	          WHERE person_id =".$person_id;
				 echo $sql;

	   	$res = mysql_query($sql);
			
		 while($row = mysql_fetch_array($res))	{
		 		$person_id				=$row['person_id'];
				$person_photo					=$row['photo'];
			}

}*/


/******************************Delete Person *************************************************
***********************************************************************************************/
function DeletePersonalProfile(){
	$id = getRequest('person_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = HRM_PERSON_TBL;
      	$info['where'] = "person_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			 //echo 'deleted..';  	   
      	   header("location:index.php?app=personal_profile&cmd=ListAllPerson&Dmsg=Successfully Deleted!");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=personal_profile&cmd=ListAllPerson&Dmsg=Not Deleted!");      	   	
      	}      	
      }	
	//   require_once(ADMIN_ACCESS_PERSONAL_PROFILE_SKIN);

}
/*********************************************Fetch all person for Admin**************************************************
**************************************************************************************************************************/
function PersonalProfileFetch(){
		// Number of records to show per page
		$recordsPerPage = 20;  
		
		// default startup page
		//$pageNum = 1;
		//$offset = ($pageNum - 1) * $recordsPerPage;
		
   		$searchq =$_GET['searchq'];
		if($searchq){
 	          $sql="SELECT 
			            person_id, person_name, nick_name, photo, birth_date, mobile, blood_group 
			        FROM                    
					    ".HRM_PERSON_TBL." 
					WHERE 
					     person_name LIKE '%".$searchq."%' or nick_name LIKE '%".$searchq."%' or blood_group LIKE '%".$searchq."%' 
						 "."ORDER BY person_id ASC LIMIT 0, $recordsPerPage";
			  }
			  else{
			  $sql="SELECT 
			            person_id, person_name, nick_name, photo, birth_date, mobile, blood_group 
			        FROM                    
					    ".HRM_PERSON_TBL." ORDER BY person_id ASC LIMIT 0, $recordsPerPage";
					    
			  }
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table   border=0 class="tableStyleClassPerson" >
	            <tr> 
				<th colspan=2 style = "border-collapse:collapse;border:1px solid #cccccc;align="center";
			             width:100px;font-family:Arial, Helvetica, sans-serif;font-size:14px">Action</th>
	            <th  align="left">Person Id</th>
	            <th  align="center">Name</th>
	            <th  align="center">Nick Name</th>
				<th  align="center">Date of birth</th>
				<th  align="center">Photo</th>
				<th  align="center">mobile</th>
				<th  align="center">Blood Group</th>
	           
	       </tr>';
                         $rowcolor=0;
	while($row = mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td  align="center" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">
					
					<a href="javascript:deletePersonalProfile(\''.$row['person_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="60" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updatePersonalProfile(\''.$row['person_id'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
					<td align="center"   style="border-right:1px solid #cccccc;padding:2px;">'.$row['person_id'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['person_name'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['nick_name'].'</td>
			<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.formatDateDMY($row['birth_date']).'</td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;" align="center">
					<img src='.IMAGE_DIR.'/'.$row['photo'].' width=20 height=22></td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'</td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;">'.$row['blood_group'].'</td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                  onMouseOut="this.className=\'evenClassStyle\'" >
					
					<td width="60" align="center" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'evenClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">
					
					<a href="javascript:deletePersonalProfile(\''.$row['person_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="60" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updatePersonalProfile(\''.$row['person_id'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
					<td align="center"   style="border-right:1px solid #cccccc;padding:2px;">'.$row['person_id'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['person_name'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['nick_name'].'</td>
			<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.formatDateDMY($row['birth_date']).'</td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;" align="center">
					<img src='.IMAGE_DIR.'/'.$row['photo'].' width=20 height=22></td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'</td>
					<td align="center" style="border-right:1px solid #cccccc;padding:2px;">'.$row['blood_group'].'</td>
					</tr>';
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
 }
/*********************************************Add personal profile by who login***************************************
**********************************************************************************************************************/   
function addPersonalProfile(){
	 $marital_status_dropdown = $this->SelectMaritalStatus();
	 $religion_dropdown = $this->SelectReligion();
	 $blood_group_dropdown = $this->SelectBloodGroup();
	 $person_type_dropdown = $this->SelectPersonType();
	 $present_district_dropdown = $this->SelectPresentDistrict();
	 $present_dividion_dropdown = $this->SelectPresentDivision();
	 $present_country_dropdown = $this->SelectPresentCountry();
	 $permanent_district_dropdown = $this->SelectPermanentDistrict();
	 $permanent_division_dropdown = $this->SelectPermanentDivision();
	 $permanent_country_dropdown = $this->SelectPermanentCountry();
 if ((getRequest('submit'))) {
		
		 if($person_id = $this->addPerson()){
		 			header("location:index.php?app=personal_profile&cmd=save&msg=Successfully Saved!");
		 }else{
		 			header("location:index.php?app=personal_profile&cmd=save&msg=Not Saved!");
		 }
		}	require_once(PERSONAL_PROFILE_ADD_SKIN);
				
		 /*if($res) {   
			 $photoUp =$this->PhotoUpload($res['newid']);
			 if($photoUp != "Size Error" && $photoUp != "Upload Error"){
		  	 	$info = array();
			 	$data = array();
				$data['photo'] = $photoUp;
				$info['data'] = $data;
				$info['table'] = HRM_PERSON_TBL;
				$info['where'] = "person_id = ".$res['newid'];
				if(update($info)){
					return $photoUp;
				}
			 }
		 }else{
		 	return "UnSuccessfull!!!";
		 }*/

}

function addPerson(){
	 $info = array();
	 $reqData = array();
	 $reqData = getUserDataSet(HRM_PERSON_TBL);
	 $reqData['birth_date'] = formatDateDMY(getRequest('birth_date'));
	 $info['table'] = HRM_PERSON_TBL;
	 $info['data'] = $reqData;
	 $info['debug']  = true;
	 $res = insert($info);
	 if($res['newid']){
		return $person_id = $res['newid'];
	}
}
/*********************************************Edit personal profile by who login***************************************
**********************************************************************************************************************/


function editPersonalProfile(){

	        $userid = getFromSession('userid');
			$sql="SELECT p.person_id, e.employee_id, person_name, nick_name, photo, birth_date,gender, father_name, mother_name,                  mobile,blood_group, religion, email, im,work_phone, national_id, nationality, present_address, present_city,                  present_division, present_country,present_zip_code, permanent_address, permanent_city, permanent_division,                  permanent_country, permanent_zip_code,marital_status, notes, person_type  
		          FROM ".HRM_PERSON_TBL." p inner join ".HRM_EMPLOYEE_TBL." e on e.person_id = p.person_id  
 	   	          WHERE e.employee_id ='".$userid."'";
				 // echo $sql;

	   	$ros = mysql_query($sql);
			
		 while($res = mysql_fetch_array($ros))	{
		 		$person_id				=$res['person_id'];
				$person_name				=$res['person_name'];
				$nick_name					=$res['nick_name'];
				$photo						=$res['photo'];
				$birth_date					=formatDateDMY($res['birth_date']);
				$gender						=$res['gender'];
				$father_name				=$res['father_name'];
				$mother_name				=$res['mother_name'];
				$marital_status				=$res['marital_status'];
				$mobile						=$res['mobile'];
				$blood_group				=$res['blood_group'];
				$religion					=$res['religion'];
				$email						=$res['email'];
				$im							=$res['im'];
				$work_phone					=$res['work_phone'];
				$national_id				=$res['national_id'];
				$nationality				=$res['nationality'];
				$present_address			=$res['present_address'];
				$present_city				=$res['present_city'];
				$present_division			=$res['present_division'];
				$present_country			=$res['present_country'];
				$present_zip_code			=$res['present_zip_code'];
				$permanent_address			=$res['permanent_address'];
				$permanent_city			    =$res['permanent_city'];
				$permanent_division			=$res['permanent_division'];
				$permanent_country			=$res['permanent_country'];
				$permanent_zip_code			=$res['permanent_zip_code'];
				$notes						=$res['notes'];
		        $person_type				=$res['person_type']; 
			}
	 $marital_status_dropdown = $this->SelectMaritalStatus($marital_status);
	 $religion_dropdown = $this->SelectReligion($religion);
	 $blood_group_dropdown = $this->SelectBloodGroup($blood_group);
	 $person_type_dropdown = $this->SelectPersonType($person_type);
	 $present_district_dropdown = $this->SelectPresentDistrict($present_city);
	 $present_dividion_dropdown = $this->SelectPresentDivision($present_division);
	 $present_country_dropdown = $this->SelectPresentCountry($present_country);
	 $permanent_district_dropdown = $this->SelectPermanentDistrict($permanent_city);
	 $permanent_division_dropdown = $this->SelectPermanentDivision($permanent_division);
	 $permanent_country_dropdown = $this->SelectPermanentCountry($permanent_country);
	 //$this->fetchPersonalProfile();
		if ((getRequest('submit'))) {
		 $person_id = getRequest('person_id');
	 	 $info = array();
		 $reqdata = array();
		 $info['table'] = HRM_PERSON_TBL;
		 $reqdata = getUserDataSet(HRM_PERSON_TBL);
		/*$photoUp = $this->PhotoUpload($person_id);
			if($photoUp != "Size Error" && $photoUp != "Upload Error"){
				$reqdata['photo'] = $photoUp;
			}*/
		 $reqdata['birth_date'] = formatDate4insert(getRequest('birth_date'));
		 $info['data'] = $reqdata;
      	 $info['where']= "person_id = $person_id";
		 $info['debug']  = true;
		$res = update($info);
		if($res){
			header("location:index.php?app=personal_profile&cmd=edit&Edmsg=Successfully Edited!");
		}else{
			header("location:index.php?app=personal_profile&cmd=edit&Edmsg=Not Edited!");
		}
	}					     
	require_once(PERSONAL_PROFILE_EDIT_SKIN);

		
		
}
/*********************************************Edit image personal profile by himself login***************************************
**********************************************************************************************************************/
function editUploadImage($person_id){
			
		$sql="SELECT person_id, photo
		          FROM ".HRM_PERSON_TBL." WHERE person_id = ".$person_id;
				 // echo $sql;

	   	$ros = mysql_query($sql);
			
		 while($res = mysql_fetch_array($ros))	{
		 		$person_id				=$res['person_id'];
				$photo					=$res['photo'];
			}
		require_once(PERSONAL_PHOTO_UPLOAD_SKIN);
	 //$this->fetchPersonalProfile();
		if ((getRequest('submit'))) {
	
			 $photoUp = $this->PhotoUpload($person_id);
				if($photoUp != "Size Error" && $photoUp != "Upload Error"){
					//$reqdata['photo'] = $photoUp;
					insertIntoSession('photo',$photoUp);
					$this->updatePhotoField($person_id, $photoUp);
					header("location:index.php?app=personal_profile&cmd=view&Editmsg=Successfully Edited Image!");
				}else{
					header("location:index.php?app=personal_profile&cmd=view&Editmsg=Not Edited Image!");
				}

		}					     
		
} 

function updatePhotoField($person_id, $photoUp){
	$sql = "update ".HRM_PERSON_TBL." SET photo = '".$photoUp."' where person_id = $person_id AND photo LIKE '%nouser%'";
	$res = mysql_query($sql);
}

/*********************************************Fetch personal profile by who login***************************************
**********************************************************************************************************************/  
function fetchPersonalProfile(){
		    $userid = getFromSession('userid');
			$sql="SELECT e.employee_id, p.person_id, person_name, nick_name, photo, birth_date,gender, father_name, mother_name
				, mobile, blood_group, religion, email, im,work_phone, national_id, nationality, present_address, present_city,                  present_division, present_country,present_zip_code, permanent_address, permanent_city, permanent_division,                  permanent_country, permanent_zip_code,marital_status, notes, person_type  
		          FROM ".HRM_PERSON_TBL." p inner join ".HRM_EMPLOYEE_TBL." e on e.person_id = p.person_id  
 	   	          WHERE e.employee_id ='".$userid."'";
				
	   	$ros = mysql_query($sql);
			
		 while($res = mysql_fetch_array($ros))	{
				$person_id					=$res['person_id'];
				$person_name				=$res['person_name'];
				$nick_name					=$res['nick_name'];
				$photo						=$res['photo'];
				$birth_date					=formatDateDMY($res['birth_date']);
				$gender						=$res['gender'];
				$father_name				=$res['father_name'];
				$mother_name				=$res['mother_name'];
				$marital_status				=$res['marital_status'];
				$mobile						=$res['mobile'];
				$blood_group				=$res['blood_group'];
				$religion					=$res['religion'];
				$email						=$res['email'];
				$im							=$res['im'];
				$work_phone					=$res['work_phone'];
				$national_id				=$res['national_id'];
				$nationality				=$res['nationality'];
				$present_address			=$res['present_address'];
				$present_city				=$res['present_city'];
				$present_division			=$res['present_division'];
				$present_country			=$res['present_country'];
				$present_zip_code			=$res['present_zip_code'];
				$permanent_address			=$res['permanent_address'];
				$permanent_city			    =$res['permanent_city'];
				$permanent_division			=$res['permanent_division'];
				$permanent_country			=$res['permanent_country'];
				$permanent_zip_code			=$res['permanent_zip_code'];
				$notes						=$res['notes'];
		        $person_type				=$res['person_type']; 
			}
					
			
			$taskMemResNotification = $this->taskCountMemRequest();
	 /*$html = $this->PersonalProfileFetch();
	 $marital_status_dropdown = $this->SelectMaritalStatus($marital_status);
	 $religion_dropdown = $this->SelectReligion($religion);
	 $blood_group_dropdown = $this->SelectBloodGroup($blood_group);
	 $person_type_dropdown = $this->SelectPersonType($person_type);
	 $present_district_dropdown = $this->SelectPresentDistrict($present_city);
	 $present_dividion_dropdown = $this->SelectPresentDivision($present_division);
	 $present_country_dropdown = $this->SelectPresentCountry($present_country);
	 $permanent_district_dropdown = $this->SelectPermanentDistrict($permanent_city);
	 $permanent_division_dropdown = $this->SelectPermanentDivision($permanent_division);
	 $permanent_country_dropdown = $this->SelectPermanentCountry($permanent_country);*/
	 require_once(PERSONAL_PROFILE_VIEW_SKIN); 
}

function taskCountMemRequest(){
	$userid = getFromSession('userid');
	$sql = "SELECT t.task_id 
		FROM ".HRM_TASK_TBL." t inner join ".HRM_TASK_TRACKER_TBL." tt
		on t.task_id = tt.task_id 	
		WHERE task_owner_id = '$userid' and mem_status = 0 ";
	$res = mysql_query($sql);
	$cnt = mysql_num_rows($res);
	if($cnt > 0){
		return "<blink><a style=\"color:#009900;font-weight:bolder\" href=\"?app=task_management&cmd=TMRA\">Task Member Requests (".$cnt.")</a></blink>";
	}
}
/*********************************************Photo edit for personal profile by who login***************************************
**********************************************************************************************************************/
/*function PhotoEdit(){
	require_once(PERSONAL_PHOTO_EDIT_SKIN);  

}*/

/***************************************************** Photo Upload Function ************************************************
**************************************************************************************************************************/
function PhotoUpload($person_id){
		$limit_size = 50000;
		$upload_dir = IMAGES_DIR."/photo";
		        /*if((getRequest('submit'))){*/
		    if(isset($_FILES['photo'])){
			$file_name = $_FILES['photo']['name'];
			//$file_type = $_FILES['photo']['type'];
			$file_type    = trim(array_pop(explode('.', $file_name)));
			$fname = $person_id.".".$file_type;
			$file_size = $_FILES['photo']['size'];
			
			if($file_size > $limit_size){
				//$msgImg = "Your file size is over limit. Your file size =" .$file_size."kb. Should file size limit = 70000KB";
				return "Size Error";
			}
			else{
				// check the same file exist
				echo $upload_dir."/".$fname;//, $upload_dir."/".$person_id."_old".rand(1000000,999999).".".$file_type
				
				if (file_exists($upload_dir.'/'.$fname)) {
				   unlink($upload_dir."/".$fname); //, $upload_dir."/".$person_id."_old".rand(1000000,999999).".".$file_type);
				}

			
				 if(move_uploaded_file($_FILES['photo']['tmp_name'], "$upload_dir"."/".$fname)) {
							$msgImg = "Successful!";
							return "photo/".$fname;
				 }else{
							return "Upload Error";
				 }
		   }		
		
	}
}

/************************************************** Function for Marital Status ************************************
********************************************************************************************************************/
 function SelectMaritalStatus($mStatus = NULL){ 
		$sql="SELECT marital_status FROM hrm_marital_status";
	    $result = mysql_query($sql);
	    $marital_status_select = "<select name='marital_status' size='1' id='marital_status' style='width:137px'>";
		$marital_status_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if(trim($row['marital_status']) == trim($mStatus)){
			$marital_status_select .= "<option value='".$row['marital_status']."' selected = 'selected'>".$row['marital_status']."                                       </option>";
			}
			else{
			$marital_status_select .= "<option value='".$row['marital_status']."'>".$row['marital_status']."</option>";	
			}
		}
		$marital_status_select .= "</select>";
		return $marital_status_select;
	}	
/************************************************** Function for Religious ************************************
********************************************************************************************************************/
function SelectReligion($religion = null){ 
		$sql="SELECT religion FROM  hrm_religion ";
	    $result = mysql_query($sql);
	    $religion_select = "<select name='religion' size='1' id='religion' style='width:137px'>";
		$religion_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['religion'] == $religion){
			$religion_select .= "<option value='".$row['religion']."' selected = 'selected'>".$row['religion']."</option>";
			}
			else{
			$religion_select .= "<option value='".$row['religion']."'>".$row['religion']."</option>";	
			}
		}
		$religion_select .= "</select>";
		return $religion_select;
	}
/************************************************** Function for Blood Group ************************************
********************************************************************************************************************/	
function SelectBloodGroup($bGroup = null){ 
		$sql='SELECT blood_group FROM blood_group';
	    $result = mysql_query($sql);
		$blood_group_select = '<select name="blood_group" size="1" id="blood_group" style="width:137px">';
			$blood_group_select .= '<option value="">Select</option>';
		while($row = mysql_fetch_array($result)) {
			if ($row['blood_group'] == $bGroup){
				$blood_group_select .= '<option value="'.$row['blood_group'].'" selected="selected">'.$row['blood_group'].'                                        </option>';
			}else{
				$blood_group_select .= '<option value="'.$row['blood_group'].'">'.$row['blood_group'].'</option>';
			}
			
		}
		$blood_group_select .= '</select>';
		return $blood_group_select;
	}
/************************************************** Function for Person type ************************************
********************************************************************************************************************/
function SelectPersonType($pType = null){ 
		$sql='SELECT person_type FROM hrm_person_type';
	    $result = mysql_query($sql);
		$person_type_select = '<select name="person_type" size="1" id="person_type" style="width:137px">';
			$person_type_select .= '<option value="">Select</option>';
		while($row = mysql_fetch_array($result)) {
			if ($row['person_type'] == $pType){
				$person_type_select .= '<option value="'.$row['person_type'].'" selected="selected">'.$row['person_type'].'                                        </option>';
			}else{
				$person_type_select .= '<option value="'.$row['person_type'].'">'.$row['person_type'].'</option>';
			}
		}
		$person_type_select .= '</select>';
		return $person_type_select;
	}
/************************************************** Function for present District ************************************
********************************************************************************************************************/		
function SelectPresentDistrict($District = null){ 
		$sql='SELECT district_name FROM district_city';
	    $result = mysql_query($sql);
		$district_name_select = '<select name="present_city" size="1" id="present_city"                                         style="width:100px">';
			$district_name_select .= '<option value="">Select</option>';
		while($row = mysql_fetch_array($result)) {
			if ($row['district_name'] == $District){
				$district_name_select .= '<option value="'.$row['district_name'].'" selected="selected">'.$row['district_name'].'                                        </option>';
			}else{
				$district_name_select .= '<option value="'.$row['district_name'].'">'.$row['district_name'].'</option>';
			}
			
		}
		$district_name_select .= '</select>';
		return $district_name_select;
	}
/************************************************** Function for present Division ************************************
********************************************************************************************************************/	
function SelectPresentDivision($Division = null){ 
		$sql="SELECT division_name FROM division_state";
	    $result = mysql_query($sql);
	    $division_name_select = "<select name='present_division' size='1' id='present_division'                                 style='width:100px'>";
		$division_name_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['division_name'] == $Division){
			$division_name_select .= "<option value='".$row['division_name']."' selected = 'selected'>".$row['division_name']."                                       </option>";
			}
			else{
			$division_name_select .= "<option value='".$row['division_name']."'>".$row['division_name']."</option>";	
			}
		}
		$division_name_select .= "</select>";
		return $division_name_select;
	}
/************************************************** Function for present Country ************************************
********************************************************************************************************************/	
function SelectPresentCountry($Country = null){ 
		$sql="SELECT country_name FROM country";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='present_country' size='1' id='present_country' style='width:100px'>";
		$country_name_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['country_name'] == $Country){
			$country_name_select .= "<option value='".$row['country_name']."' selected = 'selected'>".$row['country_name']."                                       </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['country_name']."'>".$row['country_name']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	
/************************************************** Function for Permanent District ************************************
********************************************************************************************************************/	
function SelectPermanentDistrict($district = null){ 
		$sql='SELECT district_name FROM district_city';
	    $result = mysql_query($sql);
		$permanent_district_name_select = '<select name="permanent_city" size="1" id="permanent_city"                                         style="width:100px">';
			$permanent_district_name_select .= '<option value="">Select</option>';
		while($row = mysql_fetch_array($result)) {
			if ($row['district_name'] == $district){
				$permanent_district_name_select .= '<option value="'.$row['district_name'].'" selected="selected">'
				.$row['district_name'].' </option>';
			}else{
				$permanent_district_name_select .= '<option value="'.$row['district_name'].'">'.$row['district_name'].'</option>';
			}
			
		}
		$permanent_district_name_select .= '</select>';
		return $permanent_district_name_select;
	}
/************************************************** Function for Permanent Division ************************************
********************************************************************************************************************/	
function SelectPermanentDivision($division = null){ 
		$sql="SELECT division_name FROM division_state";
	    $result = mysql_query($sql);
	    $permanent_division_name_select = "<select name='permanent_division' size='1' id='permanent_division'                                 style='width:100px'>";
		$permanent_division_name_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['division_name'] == $division){
			$permanent_division_name_select .= "<option value='".$row['division_name']."' selected = 'selected'>"
			.$row['division_name']." </option>";
			}
			else{
			$permanent_division_name_select .= "<option value='".$row['division_name']."'>".$row['division_name']."</option>";	
			}
		}
		$permanent_division_name_select .= "</select>";
		return $permanent_division_name_select;
	}
/************************************************** Function for Permanent Country ************************************
********************************************************************************************************************/		
function SelectPermanentCountry($country = null){ 
		$sql="SELECT country_name FROM country";
	    $result = mysql_query($sql);
	    $permanent_country_name_select = "<select name='permanent_country' size='1' id='permanent_country'                                 style='width:100px'>";
		$permanent_country_name_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['country_name'] == $country){
			$permanent_country_name_select .= "<option value='".$row['country_name']."' selected = 'selected'>"
			.$row['country_name']." </option>";
			}
			else{
			$permanent_country_name_select .= "<option value='".$row['country_name']."'>".$row['country_name']."</option>";	
			}
		}
		$permanent_country_name_select .= "</select>";
		return $permanent_country_name_select;
	}
	


//}  	  
/*function getList(){
 	 if ((getRequest('submit'))) {
	 	//$msg = $this->SavePersonalProfile();
		 //$msgImg = $this->PhotoUpload();
	 }
  	 //$html = $this->PersonalProfileFetch();
	 //$photo = $this->PhotoUpload();
	 $marital_status_dropdown = $this->SelectMaritalStatus();
	 $religion_dropdown = $this->SelectReligion();
	 $blood_group_dropdown = $this->SelectBloodGroup();
	 $person_type_dropdown = $this->SelectPersonType();
	 $present_district_dropdown = $this->SelectPresentDistrict();
	 $present_dividion_dropdown = $this->SelectPresentDivision();
	 $present_country_dropdown = $this->SelectPresentCountry();
	 $permanent_district_dropdown = $this->SelectPermanentDistrict();
	 $permanent_division_dropdown = $this->SelectPermanentDivision();
	 $permanent_country_dropdown = $this->SelectPermanentCountry();
	 
  }*/
  

/*function SavePersonalProfile(){
  	 $mode = getRequest('mode');
	 $person_id = getRequest('person_id');
	 if( !$person_id ){
	 	//echo 'add';
	 	$res = $this->addPersonalProfile();		
		 if($res=="Size Error"){
				$msg = 'Your file size is over limit. Your file size ='.$file_size.' kb Should file size limit = 50000KB';
		 }else if($res=="Upload Error"){
				$msg = 'Upload Unsuccessful !!!';
		 }else{
				$msg = $res;
		 }
		header("location:index.php?app=personal_profile&msg=Successfully Saved");
	  }else{
	  //echo 'edit';
	 	$res = $this->EditPersonalProfile($person_id);
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		header("location:index.php?app=personal_profile&msg=$msg&page=1");
	  }
		//dBug($res);
	return $msg;
} */ 
	  
} // End class

?>
