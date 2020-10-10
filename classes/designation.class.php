
<?php


class designation
{
   
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
      	 case 'delete'             : $screen = $this->DeleteHrmDesignation();   				break;
		 case 'ajaxSearch'         : echo $this->HrmDesignationFetch();   						break;
         case 'list'               : $this->getList();  										break;
         default                   : $cmd == 'list'; $this->getList();							break;
      }
 }
 
  function getList(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveHrmDesignation();
	 }
	 $html = $this->HrmDesignationFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
 
  function SaveHrmDesignation(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addDesignation();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditDesignation();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addDesignation(){
  	 	 $info = array();
		 $info['table'] = DESIGNATION_TBL;
		 $info['data'] = getUserDataSet(DESIGNATION_TBL);
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
  function EditDesignation(){
  	$desig_id = getRequest('desig_id');
	 	 $info = array();
		 $info['table'] = DESIGNATION_TBL;
		 $info['data'] = getUserDataSet(DESIGNATION_TBL);
      	 $info['where']= "desig_id='$desig_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteHrmDesignation()
   {$id = getRequest('desig_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = DESIGNATION_TBL;
      	$info['where'] = "desig_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			 echo 'deleted..';  	   
      	   header("location:index.php?app=designation");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:index.php?app=designation");      	   	
      	}      	
      }	
   }
   
   
   function HrmDesignationFetch(){
	 $searchq =$_GET['searchq'];
		if(searchq){
 	            $sql='SELECT desig_id, designation FROM hrm_designation WHERE desig_id LIKE "%'.$searchq.'%" or designation LIKE                      "%'.$searchq.'%" '.'ORDER BY desig_id ASC';
	}
	else{
	     
		 $sql='SELECT desig_id, designation FROM hrm_designation ORDER BY desig_id ASC';
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass" >';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					<td width="60%" style="border-right:1px solid #cccccc;padding:2px;">'.$row['designation'].'</td>
		
		   			<td width="70" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteDesignation(\''.$row['desig_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="70" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateDesignation(\''.$row['designation'].'\',\''.
						$row['desig_id'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					<td width="60%" style="border-right:1px solid #cccccc;padding:2px;">'.$row['designation'].'</td>
		
		   			<td width="70" align="center" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteDesignation(\''.$row['desig_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td width="70" align="center"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateDesignation(\''.$row['designation'].'\',\''.
						$row['desig_id'].'\');" title="Edit">
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
