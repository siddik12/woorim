<?php
require_once('classes/admin.class.php');
class employee extends admin
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
      	 case 'PermissionONUpdate'  : $this->PermissionONUpdate();   					break;
      	 case 'PermissionOFFUpdate'  : $this->PermissionOFFUpdate();   					break;
      	 case 'ViewEmployeeProfile'	: $this->ViewEmployeeProfile();   					break;
      	 case 'createUsers'			: $this->createUsers();   							break;
      	 case 'EditUsersSkin'			: $this->EditUsersSkin();   							break;
      	 case 'EditUser'			: $this->EditUser();   							break;
      	 case 'createUsersSkin'			: $this->createUsersSkin();   							break;
      	 case 'UsersListSkin'		: $this->UsersListSkin();   							break;
      	 case 'save'               : $this->addEmployeeProfile();   					break;
		 case 'edit'               : $this->EditEmployeeProfile();      				break;
      	 case 'delete'             : $screen = $this->DeleteEmployeeProfile();			break;
		 case 'ajaxSearch'         : echo $this->EmployeeProfileFetch();  				break;
		 case 'ajaxcheckemail'	   : $this->chkEmailExistence(getRequest('uid')); 		break;
         case 'list'               : $this->getList();   								break; 
         default                   : $cmd == 'list'; $this->getList();					break;
      }
 }
   
   function getList(){
   
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM ".HRM_EMPLOYEE_TBL." ";
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
			$pagination.= "<a href=\"?app=employee&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=employee&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=employee&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=employee&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=employee&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=employee&page=1\">1</a>";
				$pagination.= "<a href=\"?app=employee&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=employee&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=employee&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=employee&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=employee&page=1\">1</a>";
				$pagination.= "<a href=\"?app=employee&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=employee&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=employee&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 


	$EmployeeFetch = $this->EmployeeProfileFetch($start, $limit);

	 require_once(CURRENT_APP_SKIN_FILE); 
	 
  }
  
  function chkEmailExistence($uid){
		  $sql = "SELECT employee_id FROM ".HRM_EMPLOYEE_TBL." WHERE employee_id='".$uid."'";
		  $user_id = mysql_num_rows(mysql_query($sql));
		  echo $user_id.'######';	
	}
   // ===== End chkEmailExistence ========   


  function addEmployeeProfile(){
		 $branch_dropdown = $this->SelectBranch();
		 $department_dropdown = $this->SelectDepartment();
		 $designation_dropdown = $this->SelectDesignation();
		 $adminTyp_dropdown = $this->SelectAdminType();
		$PersonDropdown = $this->SelectPerson($ele_id = 'person_id', $ele_lbl_id = 'lbl_client');
  		 require_once(EMPLOYEE_ADD_SKIN);
		 
 	 if ((getRequest('submit'))) {
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_EMPLOYEE_TBL;
		 $reqData = getUserDataSet(HRM_EMPLOYEE_TBL);
		 $reqData['actual_join_date'] = formatDate4insert(getRequest('actual_join_date'));
		 $reqData['password'] = md5(md5(getRequest('employee_id')));
		 $person_id = $this->addPerson();
		 $reqData['person_id'] = $person_id;
		 $reqData['employee_id'] = $person_id;
		 $reqData['user_group_id'] = '3';
		 $reqData['blocked'] = 'Y';
		 $reqData['active'] ='N';
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
			 if($res){
				//$employee_id = getRequest('employee_id');
					header("location:?app=employee&cmd=ViewEmployeeProfile&person_id=$person_id&EDmsg=Successfully Saved");
					} 	         	   
      	      	
      	 else
      	 {	//mysql_query('ROLLBACK');
      		header("location:index.php?app=employee&cmd=save&msg=Not Saved");
      	   	
      	 } 
	} // // end submit if
 }

function EditEmployeeProfile(){
  			$person_id = getRequest('person_id');
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
					photo,
					person_name,
					birthday,
					permanent_address,
					father,
					phone,
					employee_tmp_id
					
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					where e.person_id="'.$person_id.'"';
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
				$person_name				= $res['person_name'];
				$designation				= $res['designation'];
				$department_name			= $res['department_name'];
				$branch_name				= $res['branch_name'];
				$actual_join_date			= dateInputFormatDMY($res['actual_join_date']);
				$emptype					= $res['emptype'];
				$photo						= $res['photo'];
				$user_group_id				= $res['user_group_id'];
				$desig_id			    = $res['desig_id'];
				$department_id			    = $res['department_id'];
				$branch_id			    = $res['branch_id'];
				$person_id			    = $res['person_id'];
				$permanent_address	 = $res['permanent_address'];
				$father		    = $res['father'];
				$birthday			    = dateInputFormatDMY($res['birthday']);
				$phone			    = $res['phone'];
				$employee_tmp_id			    = $res['employee_tmp_id'];

			$department_dropdown = $this->SelectDepartment($department_id);
	 		$designation_dropdown = $this->SelectDesignation($desig_id);
		 	$branch_dropdown = $this->SelectBranch($branch_id);
		    $adminTyp_dropdown = $this->SelectAdminType($user_group_id);
			
	 require_once(EMPLOYEE_EDIT_SKIN); 
	 $person_id = getRequest('person_id');
	
	 if((getRequest('submit'))){
		$this->EditPerson($person_id);
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_EMPLOYEE_TBL;
		 $reqData = getUserDataSet(HRM_EMPLOYEE_TBL);
		 $reqData['actual_join_date'] = formatDate4insert(getRequest('actual_join_date'));
		 $info['data'] = $reqData; 
      	 $info['where']= 'person_id='.$person_id;
		 $info['debug']  = true;
		 $res = update($info);
		 if($res)  	{   
      	  header("location:?app=employee&cmd=ViewEmployeeProfile&person_id=$person_id&EDmsg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=employee&EDmsg=Not Edit");
      	   	
      	} 
  }// end submit if 
 }
  
function DeleteEmployeeProfile(){
	 $id = getRequest('person_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = HRM_EMPLOYEE_TBL;
      	$info['where'] = "person_id=".$id;
      	$info['debug'] = true;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=employee&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		header("location:index.php?app=employee&msg=Not Deleted");      	   	
      	}      	
   }
 }  

 
//---------------------------------- Employee List Fetch -------------------------------------------
function EmployeeProfileFetch($start= null, $limit= null){
   		$searchq =$_GET['searchq'];
		$Page 	= getRequest('page');
		if($searchq){
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
					photo,
					e.blocked,
					employee_tmp_id
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					WHERE employee_id LIKE "%'.$searchq.'%" or person_name LIKE "%'.$searchq.'%" 
					order by e.branch_id DESC LIMIT 0, 29';
			  }if($Page){
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
					photo,
					e.blocked,
					employee_tmp_id
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					order by e.branch_id DESC LIMIT $start, $limit';
			  }if(!$searchq && !$Page ){
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
					photo,
					e.blocked,
					employee_tmp_id
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					order by e.branch_id DESC LIMIT 0, 29';
			  }
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width=100% border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	            <th  align="left">#</th>
	            <th  align="left" nowrap>Employee ID</th>
	            <th  align="left">Photo</th>
	            <th  align="left">Employee Name</th>
				<th  align="left">Designation</th>
<!--				<th  align="left">Department</th>
-->	            <th  align="left">Joine Date</th>
<!--	            <th  align="left">Employment</th>
-->	            <th  align="left">branch</th>
	            <th  align="left">Status</th>
                <th colspan=3 align="center" nowrap>options</th>
           </tr>';
                          $i=1;
						  $rowcolor=0;
	while($row = mysql_fetch_array($res)){ 
					 
					 $person_id = $row['person_id'];
					 $blocked = $row['blocked'];
					 if($blocked=='N'){
					 $staus='<span style="color:#00CC33; font-weight:bold">User</span>';
					 }else{
					 $staus='<span style="color:#CC0000; font-weight:bold">Not User</span>';
					 }
					 
					 if($blocked=='N'){
					 $blkurl="<a href='?app=employee&cmd=PermissionOFFUpdate&person_id=$person_id' style='color:#CC0000; font-weight:bold; text-decoration:none'>OFF</a>";
					 }else{
					 $blkurl="<a href='?app=employee&cmd=PermissionONUpdate&person_id=$person_id' style='color:#00CC33; font-weight:bold; text-decoration:none'>ON</a>";
					 }
					 
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
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['employee_tmp_id'].'</td>
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">'.$photoPath.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=ViewEmployeeProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['designation'].'&nbsp;</td>
<!--					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['department_name'].'&nbsp;</td>
-->					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'._date($row['actual_join_date']).'&nbsp;</td>
<!--					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['emptype'].'&nbsp;</td>
-->					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=createUsersSkin&person_id='.$row['person_id'].'&person_name='.$row['person_name'].'"><span class="label label-success">Create User</span></a></td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=edit&person_id='.$row['person_id'].'" title="Edit">
					         <span class=\"label label-success\">Edit</span></a></td>
					<td width="40" align="left" style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="?app=employee&cmd=delete&person_id='.$row['person_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class=\"label label-danger\">Delete</span></a></td>  	
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['employee_tmp_id'].'</td>
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">'.$photoPath.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=ViewEmployeeProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['designation'].'&nbsp;</td>
<!--					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['department_name'].'&nbsp;</td>
-->					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'._date($row['actual_join_date']).'&nbsp;</td>
<!--					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['emptype'].'&nbsp;</td>
-->					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=createUsersSkin&person_id='.$row['person_id'].'&person_name='.$row['person_name'].'"><span class="label label-success">Create User</span></a></td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=edit&person_id='.$row['person_id'].'" title="Edit">
					         <span class=\"label label-success\">Edit</span></a></td>
					<td width="40" align="left" style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="?app=employee&cmd=delete&person_id='.$row['person_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class=\"label label-danger\">Delete</span></a></td>  	
				</tr>';
	
			  $rowcolor=0;
			  } 
	$i++; }
	$html .= '</table>';
	
	return $html;
	
 }

 
function SelectBranch($branch = null){ 
		$sql="SELECT branch_id, branch_name FROM branch";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:160px'>";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['branch_id'] == $branch){
			$branch_select .= "<option value='".$row['branch_id']."' selected = 'selected'>".$row['branch_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['branch_id']."'>".$row['branch_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 

function SelectDepartment($dept = null){ 
		$sql="SELECT department_id, department_name FROM smis_department";
	    $result = mysql_query($sql);
	    $department_select = "<select name='department_id' size='1' id='department_id' style='width:160px' class=\"validate[required]\">";
		$department_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['department_id'] == $dept){
			$department_select .= "<option value='".$row['department_id']."' selected = 'selected'>".$row['department_name']." </option>";
			}
			else{
			$department_select .= "<option value='".$row['department_id']."'>".$row['department_name']."</option>";	
			}
		}
		$department_select .= "</select>";
		return $department_select;
}	
function SelectDesignation($deg = null){ 
		$sql="SELECT desig_id, designation FROM hrm_designation";
	    $result = mysql_query($sql);
	    $designation_select = "<select name='desig_id' size='1' id='desig_id' style='width:160px' class=\"validate[required]\">";
		$designation_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['desig_id'] == $deg){
			$designation_select .= "<option value='".$row['desig_id']."' selected = 'selected'>".$row['designation']." </option>";
			}
			else{
			$designation_select .= "<option value='".$row['desig_id']."'>".$row['designation']."</option>";	
			}
		}
		$designation_select .= "</select>";
		return $designation_select;
   }
function SelectAdminType($admin = null){ 
		$sql="SELECT admin_typid, admin_type FROM ".ADMIN_TYPE_TBL." where admin_typid in(1,3,5)";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='user_group_id' size='1' id='user_group_id' style='width:137px' class=\"validate[required]\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['admin_typid'] == $admin){
			$branch_select .= "<option value='".$row['admin_typid']."' selected = 'selected'>".$row['admin_type']."  </option>";
			}
			else{
			$branch_select .= "<option value='".$row['admin_typid']."'>".$row['admin_type']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 
   
  function SelectPerson($ele_id, $ele_lbl_id){
		 $sql='SELECT person_id, person_name FROM '.HRM_PERSON_TBL.' ';
	$res= mysql_query($sql);
	$html = "<table width=300  border=0 >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
			$html .= "<tr  class=\"evenTr\" onMouseOver=\"this.className='highLightTr'\" onMouseOut=\"this.className='evenTr'\" 
						onClick=\"javascript:addClientId('".$row['person_id']."','".$ele_id."','".$row['person_name']."','".$ele_lbl_id."');\">
				
				<td width=\"60\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['person_id']."</td>
				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['person_name']."</td>
				</tr>";
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= "<tr class=\"oddTr\" onMouseOver=\"this.className='highLightTr'\" onMouseOut=\"this.className='oddTr'\" 
						onClick=\"javascript:addClientId('".$row['person_id']."','".$ele_id."','".$row['person_name']."','".$ele_lbl_id."');\">
				
				<td width=\"60\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['person_id']."</td>
				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['person_name']."</td>
				</tr>";
			  $rowcolor=0;
			  }
	}
	$html .= "</table>";
	
	return $html;
  }// EOF



//---------------- Client View profile ------------------------------

function ViewEmployeeProfile(){
  			$person_id = getRequest('person_id');
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
					photo,
					employee_tmp_id
				
				FROM
					'.HRM_EMPLOYEE_TBL.' e inner join '.HRM_PERSON_TBL.' p on p.person_id=e.person_id
					inner join '.DESIGNATION_TBL.' dg on dg.desig_id=e.desig_id
					inner join '.DEPARTMENT_TBL.' d on d.department_id=e.department_id
					inner join '.BRANCH_TBL.' b on b.branch_id=e.branch_id
					where e.person_id="'.$person_id.'"';
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
				$person_name				= $res['person_name'];
				$employee_id				= $res['employee_tmp_id'];
				$designation				= $res['designation'];
				$department_name			= $res['department_name'];
				$branch_name				= $res['branch_name'];
				$actual_join_date			= _date($res['actual_join_date']);
				$emptype					= $res['emptype'];
				$photo						= $res['photo'];
				$user_group_id				= $res['user_group_id'];
				$employee_tmp_id			= $res['employee_tmp_id'];
		
		require_once(EMPLOYEE_PROFILE_VIEW_SKIN); 

				
	} // EOF 


function PermissionOFFUpdate(){
$person_id = getRequest('person_id');

$sql ="UPDATE hrm_employee SET blocked='Y', active='N' where person_id ='$person_id' ";
$ros = mysql_query($sql);
	if($ros){
	header("location:?app=employee&msg=Successfully Close");
	}
}

function PermissionONUpdate(){
$person_id = getRequest('person_id');
$sql ="UPDATE hrm_employee SET blocked='N', active='Y' where person_id ='$person_id'";
$ros = mysql_query($sql);
	if($ros){
	header("location:?app=employee&msg=Successfully Open");
	}
}

function EditPerson($person_id){
	 $info = array();
	 $reqData = array();
	 $info['table'] = HRM_PERSON_TBL;
	 $reqData = getUserDataSet(HRM_PERSON_TBL);
	 $reqData['birthday'] = formatDate4insert(getRequest('birthday'));
	 $info['data'] = $reqData; 
	 $info['where']= 'person_id='.$person_id;
	 $info['debug']  = true;
	 $res = update($info);
	 return $res; 
	}// EOF 


 function createUsersSkin(){
		$person_id = getRequest('person_id');
 		 $adminTyp_dropdown = $this->SelectAdminType();
  		 require_once(EMPLOYEE_USER_ADD_SKIN);

 }

  function createUsers(){
		 
	 $person_name = getRequest('person_name');
	 $person_id = getRequest('person_id');
	 $employee_id = getRequest('employee_id');
	 $sql="select * from hrm_employee where employee_id='$employee_id'";
	 $res=mysql_query($sql);
if(mysql_num_rows($res)<=0){
	
	 if((getRequest('submit'))){
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_EMPLOYEE_TBL;
		 $reqData = getUserDataSet(HRM_EMPLOYEE_TBL);
		 $reqData['plain_pass'] = getRequest('password');
		 $reqData['employee_id'] = getRequest('employee_id');
		 $reqData['user_stat'] = '1';
		 $reqData['password'] = md5(md5(getRequest('password')));
		 $reqData['blocked'] = 'N';
		 $reqData['active'] ='Y';
		 $info['data'] = $reqData; 
      	 $info['where']= 'person_id='.$person_id;
		 $info['debug']  = true;
		 $res = update($info);
		 if($res)  	{   
      	 header("location:?app=employee&cmd=UsersListSkin&msg=Successfully Created");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=employee&msg=Not Created");
      	   	
      	} 
  }// end submit if 
 }else{
 header("location:?app=employee&cmd=createUsersSkin&person_id=$person_id&person_name=$person_name&msg=Already used this user name!! Try another");
 }
 }// eof


function UsersListSkin(){
$UserListFetch=$this->UserListFetch();
require_once(EMPLOYEE_USER_LIST_SKIN);
}

//---------------------------------- User List Fetch -------------------------------------------
function UserListFetch(){
 	            $sql="SELECT
					employee_id,
					e.desig_id,
					actual_join_date,
					resign_date,
					e.person_id,
					admin_type,
					e.department_id,
					e.branch_id,
					department_name,
					designation,
					branch_name,
					person_name,
					photo,
					e.blocked,
					employee_tmp_id,
					plain_pass,
					e.user_group_id
				
				FROM
					".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on p.person_id=e.person_id
					inner join ".DESIGNATION_TBL." dg on dg.desig_id=e.desig_id
					inner join ".DEPARTMENT_TBL." d on d.department_id=e.department_id
					inner join ".BRANCH_TBL." b on b.branch_id=e.branch_id 
					inner join admin_type a on a.admin_typid=e.user_group_id
					where user_stat='1'";
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width=100% border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	            <th  align="left">Photo</th>
	            <th  align="left">Employee Name</th>
	            <th  align="left" nowrap>User ID</th>
				<th  align="left">Password</th>
				<th  align="left">User Type</th>
				<th  align="left">Branch</th>
                <th colspan=2 align="center" nowrap>options</th>
           </tr>';
                          
						  $rowcolor=0;
	while($row = mysql_fetch_array($res)){ 
					 
					 $person_id = $row['person_id'];
					 $blocked = $row['blocked'];
					 
					 if($blocked=='N'){
					 $blkurl="<a href='?app=employee&cmd=PermissionOFFUpdate&person_id=$person_id'><span class=\"label label-danger\">OFF</span></a>";
					 }else{
					 $blkurl="<a href='?app=employee&cmd=PermissionONUpdate&person_id=$person_id'><span class=\"label label-success\">ON</span></a>";
					 }
					 
					 $photo = $row['photo'];
					 if($photo!=''){
					      $photoPath ='<img src="images/'.$photo.'" heigt=60 width=50>';
					 }else{
					    $photoPath ='<img src="images/photo/nouser.png" heigt=60 width=50>';
					 }
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">'.$photoPath.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=ViewEmployeeProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['employee_id'].'&nbsp;</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['plain_pass'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['admin_type'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">'.$blkurl.'</td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=EditUsersSkin&person_id='.$row['person_id'].'&person_name='.$row['person_name'].'&user_group_id='.$row['user_group_id'].'&employee_id='.$row['employee_id'].'"><span class="label label-success">Edit</span>
					</a></td>
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td nowrap="nowrap" align=center style="border-right:1px solid #cccccc;padding:2px;">'.$photoPath.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=ViewEmployeeProfile&person_id='.$row['person_id'].'">'.$row['person_name'].'</a></td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['employee_id'].'&nbsp;</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['plain_pass'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['admin_type'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">'.$blkurl.'</td>
					<td width="40" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=employee&cmd=EditUsersSkin&person_id='.$row['person_id'].'&person_name='.$row['person_name'].'&user_group_id='.$row['user_group_id'].'&employee_id='.$row['employee_id'].'"><span class="label label-success">Edit</span>
				</tr>';
	
			  $rowcolor=0;
			  } 
	 }
	$html .= '</table>';
	
	return $html;
	
 }
 
 function EditUsersSkin(){
		$employee_id = getRequest('employee_id');
		$person_id = getRequest('person_id');
		$user_group_id = getRequest('user_group_id');
		$person_name = getRequest('person_name');
 		 $adminTyp_dropdown = $this->SelectAdminType($user_group_id);
  		 require_once(EMPLOYEE_USER_EDIT_SKIN);

 }


function EditUser(){
		 
		$employee_id = getRequest('employee_id');
		$person_id = getRequest('person_id');
		$user_group_id = getRequest('user_group_id');
		$person_name = getRequest('person_name');
	 $sql="select * from hrm_employee where employee_id='$employee_id'";
	 $res=mysql_query($sql);
if(mysql_num_rows($res)<=0){
	
	 if((getRequest('submit'))){
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = HRM_EMPLOYEE_TBL;
		 $reqData = getUserDataSet(HRM_EMPLOYEE_TBL);
		 $reqData['plain_pass'] = getRequest('password');
		 $reqData['employee_id'] = getRequest('employee_id');
		 $reqData['password'] = md5(md5(getRequest('password')));
		 $reqData['blocked'] = 'N';
		 $reqData['active'] ='Y';
		 $info['data'] = $reqData; 
      	 $info['where']= 'person_id='.$person_id;
		 $info['debug']  = true;
		 $res = update($info);
		 if($res)  	{   
      	 header("location:?app=employee&cmd=UsersListSkin&msg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=employee&cmd=UsersListSkin&msg=Not Created");
      	   	
      	} 
  }// end submit if 
 }else{
 header("location:?app=employee&cmd=EditUsersSkin&person_id=$person_id&person_name=$person_name&user_group_id=$user_group_id&employee_id=$employee_id&msg=Already used this user name!! Try another");
 }
 }


} // End class

?>