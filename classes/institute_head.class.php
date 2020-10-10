<?php


class institute_head
{
   
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
      	 case 'delete'             : $screen = $this->Deleteinstitute();   				break;
		 case 'ajaxSearch'         : echo $this->instituteFetch();   					break;
         case 'list'               : $this->getList();  								break;
         default                   : $cmd == 'list'; $this->getList();					break;
      }
 }
 
  function getList(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->Saveinstitute();
	 }
	 $html = $this->instituteFetch();
	 $SelectBranch = $this->SelectBranch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
 
  function Saveinstitute(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addinstitute();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
				header("location:?app=institute_head");
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->Editinstitute();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addinstitute(){
  	 	 $info = array();
		 $info['table'] = SETTINGS_TBL;
		 $info['data'] = getUserDataSet(SETTINGS_TBL);
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
  function Editinstitute(){
  	$company_id = getRequest('company_id');
	 	 $info = array();
		 $info['table'] = SETTINGS_TBL;
		 $info['data'] = getUserDataSet(SETTINGS_TBL);
      	 $info['where']= "company_id='$company_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
  }
  
  function Deleteinstitute()
   {$id = getRequest('company_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = SETTINGS_TBL;
      	$info['where'] = "company_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			 echo 'deleted..';  	   
      	   header("location:index.php?app=institute_head");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:index.php?app=institute_head");      	   	
      	}      	
      }	
   }
   
   
   function instituteFetch(){
   
	 $searchq =$_GET['searchq'];
		if($searchq){
 	            $sql='select company_id,
						company_name,
						address,
						mobile,
						i.branch_id,
						branch_name,
						email,
						web FROM '.SETTINGS_TBL.' i inner join branch b on b.branch_id=i.branch_id
				 WHERE institute_name LIKE "%'.$searchq.'%" and where stat="Head Office"';
	}
	if(!$searchq){
	     
		 $sql='select company_id,
						company_name,
						address,
						mobile,
						i.branch_id,
						branch_name,
						email,
						web FROM '.SETTINGS_TBL.' i inner join branch b on b.branch_id=i.branch_id
						where stat="Head Office" ';
		 }
	$res= mysql_query($sql);
	$html = '<table  border=2 class="tableStyleClass" style="width:1100px" >
		            <tr> 
	            <th  align="left">#</th>
	            <th  align="left" nowrap>Shop Name</th>
	            <th  align="left" nowrap>Address</th>
	            <th  align="left" nowrap>Phone</th>
	            <th  align="left" nowrap>Email</th>
	            <th  align="left" nowrap>Web</th>
	            <th  align="left" nowrap>Branch</th>
	            <th colspan=2  align="center">Options</th>
				</tr>'; 
                         $i=1;
						 $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$i.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['company_name'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['address'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['email'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['web'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
		
					<td align="center"  style="border-right:1px solid #cccccc;padding:2px;" width="50">
					<a href="javascript:updateInstitute(\''.$row['company_id'].'\',\''.
														$row['company_name'].'\',\''.
														$row['address'].'\',\''.
														$row['mobile'].'\',\''.
														$row['email'].'\',\''.
														$row['web'].'\',\''.
														$row['branch_id'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
		   			<td  align="center" style="border-right:1px solid #cccccc;padding:2px;" width="20">
					<a href="javascript:deleteInstitute(\''.$row['company_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$i.'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['company_name'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['address'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['email'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['web'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'</td>
		
					<td align="center"  style="border-right:1px solid #cccccc;padding:2px;" width="50">
					<a href="javascript:updateInstitute(\''.$row['company_id'].'\',\''.
														$row['company_name'].'\',\''.
														$row['address'].'\',\''.
														$row['mobile'].'\',\''.
														$row['email'].'\',\''.
														$row['web'].'\',\''.
														$row['branch_id'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
		   			<td  align="center" style="border-right:1px solid #cccccc;padding:2px;" width="20">
					<a href="javascript:deleteInstitute(\''.$row['company_id'].'\');" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					</tr>';
			  $rowcolor=0;
			  }
	$i++; }
	$html .= '</table>';
	
	return $html;
         }

function SelectBranch($brnc = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT branch_id, branch_name FROM ".BRANCH_TBL." where stat='Head Office' ";
	    $result = mysql_query($sql);
	    $department_select = "<select name='branch_id' size='1' id='branch_id' style='width:250px; height:25px;' class=\"validate[required]\">";
/*		$department_select .= "<option value=''>select branch</option>"; 
*/			while($row = mysql_fetch_array($result)) {
			if($row['branch_id'] == $brnc){
			$department_select .= "<option value='".$row['branch_id']."' selected = 'selected'>".$row['branch_name']."</option>";
			}
			else{
			$department_select .= "<option value='".$row['branch_id']."'>".$row['branch_name']."</option>";	
			}
		}
		$department_select .= "</select>";
		return $department_select;
}	


  
} // End class

?>