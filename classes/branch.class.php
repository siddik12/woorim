
<?php


class branch
{
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
      	 case 'delete'             : $screen = $this->DeleteSmisBranch();   						break;
		 case 'ajaxSearch'         : echo $this->SmisBranchFetch();   							        break;
         case 'list'               : $this->getList();   break;
         default                   : $cmd == 'list'; $this->getList();	break;
      }
 }
  function getList(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveSmisBranch();
	 }
	
  	 $html = $this->SmisBranchFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
 function SaveSmisBranch(){
  	 
	  $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addBranch();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditBranch();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addBranch(){
  	 	 $info = array();
		 $requestdata = array();
		 $requestdata = getUserDataSet(BRANCH_TBL);
		 $info['table'] = BRANCH_TBL;
		 $info['data'] = $requestdata;
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
function EditBranch(){
  	     $branch_id = getRequest('branch_id');
	 	 $info = array();
		 $requestdata = array();
		 $requestdata = getUserDataSet(BRANCH_TBL);
		 $info['table'] = BRANCH_TBL;
		 $info['data'] = $requestdata;
      	 $info['where']= "branch_id='$branch_id'";
		//$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteSmisBranch()
   {$id = getRequest('branch_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = BRANCH_TBL;
      	$info['where'] = "branch_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			 echo 'deleted..';  	   
      	   header("location:index.php?app=branch");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:index.php?app=branch");      	   	
      	}      	
      }	
   }
   
   
   function SmisBranchFetch(){

			  $sql='SELECT branch_id, branch_name,stat FROM '.BRANCH_TBL.'  ORDER BY branch_id';
			
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
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['stat'].'</td>
					<td width="50" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteBranch(\''.$row['branch_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="50" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBranch(\''.$row['branch_id'].'\',\''.
						$row['branch_name'].'\',\''.
						$row['stat'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'" >
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['stat'].'</td>
					<td width="50" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteBranch(\''.$row['branch_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="50" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBranch(\''.$row['branch_id'].'\',\''.
						$row['branch_name'].'\',\''.
						$row['stat'].'\');" title="Edit">
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
