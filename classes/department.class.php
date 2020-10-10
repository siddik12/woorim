
<?php


class department
{
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
      	 case 'delete'             : $screen = $this->DeleteSmisDepartment();   						break;
		 case 'ajaxSearch'         : echo $this->SmisDepartmentFetch();   							        break;
         case 'list'               : $this->getList();   break;
         default                   : $cmd == 'list'; $this->getList();	break;
      }
 }
  function getList(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveSmisDepartment();
	 }
	
  	 $html = $this->SmisDepartmentFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
 function SaveSmisDepartment(){
  	 
	  $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addDepartment();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditDepartment();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addDepartment(){
  	 	 $info = array();
		 $requestdata = array();
		 $requestdata = getUserDataSet(DEPARTMENT_TBL);
		 $requestdata['created_by'] = getFromSession('userid');
		 $requestdata['created_time'] = date('Y-m-d H:i:s');
		 $requestdata['modified_by'] = getFromSession('userid');
		 $requestdata['modified_time'] = date('Y-m-d H:i:s');
		 $info['table'] = DEPARTMENT_TBL;
		 $info['data'] = $requestdata;
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
function EditDepartment(){
  	     $department_id = getRequest('department_id');
	 	 $info = array();
		 $requestdata = array();
		 $requestdata = getUserDataSet(DEPARTMENT_TBL);
		 $requestdata['modified_by'] = getFromSession('userid');
		 $requestdata['modified_time'] = date('Y-m-d H:i:s');
		 $info['table'] = DEPARTMENT_TBL;
		 $info['data'] = $requestdata;
      	 $info['where']= "department_id='$department_id'";
		//$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteSmisDepartment()
   {$id = getRequest('department_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = DEPARTMENT_TBL;
      	$info['where'] = "department_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			 echo 'deleted..';  	   
      	   header("location:index.php?app=department");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:index.php?app=department");      	   	
      	}      	
      }	
   }
   
   
   function SmisDepartmentFetch(){

			  $sql='SELECT department_id, department_name FROM smis_department  ORDER BY department_id';
			
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table   border=0 class="tableStyleClassDept" >';
                         $rowcolor=0;
	while($row = mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['department_name'].'</td>
					<td width="50" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteDepartment(\''.$row['department_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="50" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateDepartment(\''.$row['department_id'].'\',\''.
						$row['department_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'" >
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['department_name'].'</td>
					<td width="50" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteDepartment(\''.$row['department_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="50" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateDepartment(\''.$row['department_id'].'\',\''.
						$row['department_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
 }

	 
} // End class

?>
