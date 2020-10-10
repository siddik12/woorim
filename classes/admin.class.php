<?php
class admin 
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
      	 case 'PermissionONUpdate'  : $this->PermissionONUpdate();   					        break;
      	 case 'PermissionOFFUpdate'  : $this->PermissionOFFUpdate();   					        break;
      	 case 'PermissionSetSkin'  : $this->PermissionSetSkin();   					           	break;
      	 case 'ViewPersonalProfile'  : $this->ViewPersonalProfile();   					        break;
      	 case 'save'               : $this->addPersonalProfile();   					        break;
		 case 'edit'               : $this->EditPersonalProfile();      						break;
		 case 'PersonalPhotoUpSkin'  : $this->PersonalPhotoUpSkin();      						break;
         case 'UploadPersonalPhoto'  :  $this->UploadPersonalPhoto(); 							break;
      	 case 'delete'             : $screen = $this->DeletePersonalProfile();				    break;
		 case 'ajaxSearch'         : echo $this->PersonalProfileFetch();  					    break;
         case 'list'               : $this->getList();   										break; 
         default                   : $cmd == 'list'; $this->getList();							break;
      }
 }
   
   function getList(){
   
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM ".HRM_PERSON_TBL." ";
	$total_pages = mysql_fetch_array(mysql_query($query));
	 $total_pages = $total_pages[num]; //exit();
	
	/* Setup vars for query. */
	//$targetpage = "?app=client_profile"; 	//your file name  (the name of this file)
	$limit = 30; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
/*	$sql = "SELECT client_id FROM client_info LIMIT $start, $limit";
	$result = mysql_query($sql);
*/	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"?app=admin&page=$prev\">&laquo; previous</a>";
		else
			$pagination.= "<span class=\"disabled\">previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"?app=admin&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=admin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=admin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=admin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=admin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=admin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=admin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=admin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=admin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=admin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=admin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=admin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=admin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 


	$PersonalProfileFetch = $this->PersonalProfileFetch($start, $limit);

	 require_once(CURRENT_APP_SKIN_FILE); 
	 
  }
  

  function addPersonalProfile(){
 		 $SelectReligion = $this->SelectReligion();
		 $SelectMari = $this->SelectMari();
		 $SelectBlood = $this->SelectBlood();

  		 require_once(ADMIN_ADD_SKIN);
		 
 	 if ((getRequest('submit'))) {
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_PERSON_TBL;
		 $reqData = getUserDataSet(HRM_PERSON_TBL);
		 $reqData['birthday'] = formatDate4insert(getRequest('birthday'));
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
			 if($res['newid']){
				$person_id = $res['newid'];
					header("location:?app=admin&cmd=ViewPersonalProfile&person_id=$person_id&EDmsg=Successfully Saved");
					} 	         	   
      	      	
      	 else
      	 {	//mysql_query('ROLLBACK');
      		header("location:index.php?app=admin&cmd=save&msg=Not Saved");
      	   	
      	 } 
	} // // end submit if
 }



  
function EditPersonalProfile(){
  			$person_id = getRequest('person_id');
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
				$birthday						= dateInputFormatDMY($res['birthday']);
				$email						= $res['email'];
				$present_address					= $res['present_address'];
				$permanent_address					= $res['permanent_address'];
				$phone			        	= $res['phone'];
				$religion			        = $res['religion'];
				$marital_status			    = $res['marital_status'];
				$blood_group			    = $res['blood_group'];
				
 		 	$SelectReligion = $this->SelectReligion($religion);
		  	$SelectMari = $this->SelectMari($marital_status);
		 	$SelectBlood = $this->SelectBlood($blood_group);
	 require_once(ADMIN_EDIT_SKIN); 
	
	 if((getRequest('submit'))){

  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_PERSON_TBL;
		 $reqData = getUserDataSet(HRM_PERSON_TBL);
		 $reqData['birthday'] = formatDate4insert(getRequest('birthday'));
		 $info['data'] = $reqData; 
      	 $info['where']= 'person_id='.$person_id;
		 $info['debug']  = true;
		 $res = update($info);
		 if($res)  	{   
      	  header("location:?app=admin&cmd=ViewPersonalProfile&person_id=$person_id&EDmsg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=admin&EDmsg=Not Edit");
      	   	
      	} 
  }// end submit if 
 }
  
function DeletePersonalProfile(){
	 $id = getRequest('person_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = HRM_PERSON_TBL;
      	$info['where'] = "person_id=".$id;
      	$info['debug'] = true;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=admin&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		header("location:index.php?app=admin&msg=Not Deleted");      	   	
      	}      	
   }
 }  

 
//---------------------------------- Personal List Fetch -------------------------------------------
function PersonalProfileFetch($start= null, $limit= null){
   		$searchq =$_GET['searchq'];
		$Page 	= getRequest('page');
		if($searchq){
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
					WHERE person_name LIKE "%'.$searchq.'%"	or phone LIKE "%'.$searchq.'%" or email LIKE "%'.$searchq.'%"
					order by person_id desc LIMIT 0, 29';
			  }if($Page){
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
					order by person_id LIMIT $start, $limit';
			  }if(!$searchq && !$Page ){
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
					order by person_id LIMIT 0, 29';
			  }
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width=100% border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	            <th  align="left">#</th>
	            <th  align="left">Photo</th>
	            <th  align="left">Name</th>
				<th  align="left">Email</th>
				<th  align="left">Mobile</th>
<!--	            <th  align="left">Gender</th>
-->	           	<th align="left">Father</th>
	           	<th align="left">Mother</th>
<!--	            <th  align="left" nowrap>Marital Status </th>
	            <th  align="left">Religion</th>
                <th  align="left" nowrap>Blood</th>
	            <th  align="left">Pre. Address</th>-->	
            <th  align="left">Address</th>
                <th colspan=2  align="center" nowrap>options</th>
           </tr>';
                          $i=1;
						  $rowcolor=0;
	while($row = mysql_fetch_array($res)){ 
					 
					 $photo = $row['photo'];
					 if($photo!=''){
					      $photoPath ='<img src="images/'.$photo.'" heigt=60 width=50>';
					 }else{
					    $photoPath ='<img src="images/photo/nouser.png" heigt=60 width=50>';
					 }
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=admin&cmd=PersonalPhotoUpSkin&person_id='.$row['person_id'].'">'.$photoPath.'</a>&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=admin&cmd=ViewPersonalProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['email'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['phone'].'&nbsp;</td>
<!--					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['sex'].'&nbsp;</td>
-->					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['father'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['mother'].'&nbsp;</td>
<!--					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['marital_status'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['religion'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['blood_group'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['present_address'].'&nbsp;</td>
-->					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['permanent_address'].'&nbsp;</td>

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="javascript:deleteAdminProfile(\''.$row['person_id'].'\')" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateAdminProfile(\''.$row['person_id'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=admin&cmd=PersonalPhotoUpSkin&person_id='.$row['person_id'].'">'.$photoPath.'</a>&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=admin&cmd=ViewPersonalProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['email'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['phone'].'&nbsp;</td>
<!--					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['sex'].'&nbsp;</td>
-->					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['father'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['mother'].'&nbsp;</td>
<!--					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['marital_status'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['religion'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['blood_group'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['present_address'].'&nbsp;</td>
-->					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['permanent_address'].'&nbsp;</td>

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="javascript:deleteAdminProfile(\''.$row['person_id'].'\')" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateAdminProfile(\''.$row['person_id'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
				</tr>';
	
			  $rowcolor=0;
			  } 
	$i++; }
	$html .= '</table>';
	
	return $html;
	
 }

 
/************************************************** Function for  religion ************************************
********************************************************************************************************************/	
function SelectReligion($Religion = null){ 
		$sql="SELECT religion FROM ".RELIGION_TBL." ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='religion' size='1' id='religion' style='width:150px' class=\"validate[required]\">";
		$country_name_select .= "<option value=''>select</option>";
			while($row = mysql_fetch_array($result)) {
			if($row['religion'] == $Religion){
			$country_name_select .= "<option value='".$row['religion']."' selected = 'selected'>".$row['religion']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['religion']."'>".$row['religion']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	

/************************************************** Function for  Blood ************************************
********************************************************************************************************************/	
function SelectBlood($Blood = null){ 
		$sql="SELECT blood_group FROM ".BLOOD_GROUP_TBL." ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='blood_group' size='1' id='blood_group' style='width:150px' class=\"validate[required]\">";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['blood_group'] == $Blood){
			$country_name_select .= "<option value='".$row['blood_group']."' selected = 'selected'>".$row['blood_group']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['blood_group']."'>".$row['blood_group']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	
/************************************************** Function for  Marital Status ************************************
********************************************************************************************************************/	
function SelectMari($Mari= null){ 

		 $sql="SELECT marital_status FROM ".MARITAL_STATUS_TBL." ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='marital_status' size='1' id='marital_status' style='width:150px' class=\"validate[required]\" >";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['marital_status'] == $Mari){
			$country_name_select .= "<option value='".$row['marital_status']."' selected = 'selected'>".$row['marital_status']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['marital_status']."'>".$row['marital_status']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	



//-------------------------------- Personal Inage Upload-----------------------------------------------
function PersonalPhotoUpSkin(){
	require_once(ADMIN_PHOTO_UP_SKIN);
}

function UploadPersonalPhoto(){
			$person_id=getRequest('person_id');
			 $photoUp = $this->UplogoPath($person_id);
				if($photoUp != "Size must less than 100kb" && $photoUp != "Upload Error"){
					$this->updateLogoField($person_id, $photoUp);
					header("location:?app=admin&EDmsg=Successfully Saved");
				}else{
					header("location:?app=admin&cmd=PersonalPhotoUpSkin&person_id=$person_id&EDmsg=Not Saved. Size must less than 100kb");
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

//---------------- Client View profile ------------------------------

function ViewPersonalProfile(){
  			$person_id = getRequest('person_id');
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
		
		require_once(PERSONAL_PROFILE_VIEW_SKIN); 

				
	} // EOF 



function PermissionSetSkin(){
require_once(PERMISSION_SET_SKIN);
}

function PermissionOFFUpdate(){
$employee_id = getFromSession('employee_id');

$sql ="UPDATE hrm_employee SET blocked='Y', active='N' where employee_id !='$employee_id' ";
$ros = mysql_query($sql);
	if($ros){
	header("location:?app=admin&cmd=PermissionSetSkin&msg=Successfully Close");
	}
}

function PermissionONUpdate(){
$sql ="UPDATE hrm_employee SET blocked='N', active='Y' ";
$ros = mysql_query($sql);
	if($ros){
	header("location:?app=admin&cmd=PermissionSetSkin&msg=Successfully Open");
	}
}

function addPerson(){
	 $info = array();
	 $reqData = array();
	 $reqData = getUserDataSet(HRM_PERSON_TBL);
	 $reqData['birthday'] = formatDateDMY(getRequest('birthday'));
	 $info['table'] = HRM_PERSON_TBL;
	 $info['data'] = $reqData;
	 $info['debug']  = true;
	 $res = insert($info);
	 if($res['newid']){
		return $person_id = $res['newid'];
	}
}



} // End class

?>