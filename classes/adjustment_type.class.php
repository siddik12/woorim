<?php
class adjustment_type
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
         case 'list'                  	: $this->getList();                       					break;
         default                      	: $cmd == 'list'; $this->getList();	       					break;
      }
 }
 
 
function getList(){
     if ((getRequest('submit'))) {
	 	$msg = $this->SaveSalesmanInfo();
	 }
  	 $SalesmanInfoTab = $this->SalesmanInfoFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveSalesmanInfo(){
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addSalesmanInfo();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		 //header('location:?app=inv_supplierinfo');
	  }
	   if($mode == 'edit'){
	 	$res = $this->EditSalesmanInfo();
		 if($res){
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }
  
    
function addSalesmanInfo(){
  	 	 $info = array();
		 $info['table'] = ADJUSTMENT_TYPE_TBL;
		 $reqData =  getUserDataSet(ADJUSTMENT_TYPE_TBL);
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }


function EditSalesmanInfo(){
		 
		 $adjust_type_id = getRequest('adjust_type_id');
	 	 $info				 = array();
		 $info['table']     	= ADJUSTMENT_TYPE_TBL;
		 $reqData        		 = getUserDataSet(ADJUSTMENT_TYPE_TBL);
		 $info['data'] = $reqData;
      	 $info['where']= "adjust_type_id='$adjust_type_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteSalesmanInfo(){
	$adjust_type_id = getRequest('adjust_type_id');
      if($slman_id)
      {
      	            	
      	$info = array();
      	$info['table'] = ADJUSTMENT_TYPE_TBL;
      	$info['where'] = "adjust_type_id='$adjust_type_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=adjustment_type&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=adjustment_type&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function SalesmanInfoFetch(){
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql="select adjust_type_id, adjust_name from ".ADJUSTMENT_TYPE_TBL." ";
			}else{
				 $sql="select adjust_type_id, adjust_name from ".ADJUSTMENT_TYPE_TBL." ";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width=500 border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th align="left">SL</th>
				 <th align="left">Adjustment Type</th>
	       </tr>';
                         $rowcolor=0;
	$i=1;
	while($row=mysql_fetch_array($res)){

				 if($rowcolor==0){ 
					$style = "oddClassStyle";$rowcolor=1;
				 }else{
					$style = "evenClassStyle";$rowcolor=0;
				 }
				$MeasurementTab .='<tr  class="'.$style.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\''.$style.'\'"
				onclick="javascript:updateCht(\''.$row['adjust_type_id'].'\',\''.
						                          $row['adjust_name'].'\');" >
					
					<!--<td>
					<a href="index.php?app=adjustment_type&cmd=delete&adjust_type_id='.$row['adjust_type_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	-->  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["adjust_name"].'&nbsp;</td>
					</tr>';
	
	$i++; }
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 

   
} // End class

?>