<?php
class fixed_asset
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
		 $info['table'] = FIXED_ASSET_TBL;
		 $reqData =  getUserDataSet(FIXED_ASSET_TBL);
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }


function EditSalesmanInfo(){
		 
		 $fixed_asset_id = getRequest('fixed_asset_id');
	 	 $info				 = array();
		 $info['table']     	= FIXED_ASSET_TBL;
		 $reqData        		 = getUserDataSet(FIXED_ASSET_TBL);
		 $info['data'] = $reqData;
      	 $info['where']= "fixed_asset_id='$fixed_asset_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteSalesmanInfo(){
	$fixed_asset_id = getRequest('fixed_asset_id');
      if($slman_id)
      {
      	            	
      	$info = array();
      	$info['table'] = FIXED_ASSET_TBL;
      	$info['where'] = "fixed_asset_id='$fixed_asset_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=fixed_asset&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=fixed_asset&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function SalesmanInfoFetch(){
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql="select fixed_asset_id, asset_name from ".FIXED_ASSET_TBL." ";
			}else{
				 $sql="select fixed_asset_id, asset_name from ".FIXED_ASSET_TBL." ";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width=500 border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th>SL</th>
				 <th align="left">Fixed Asset Type</th>
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
				onclick="javascript:updateCht(\''.$row['fixed_asset_id'].'\',\''.
						                          $row['asset_name'].'\');" >
					
					<!--<td>
					<a href="index.php?app=fixed_asset&cmd=delete&fixed_asset_id='.$row['fixed_asset_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	-->  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["asset_name"].'&nbsp;</td>
					</tr>';
	
	$i++; }
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 

   
} // End class

?>