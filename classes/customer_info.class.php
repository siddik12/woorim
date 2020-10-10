<?php
class customer_info
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxSearchCustomer' : echo $this->CustomerInfoFetch();   						break;
		 case 'Delete'             : $this->DeleteCustomerInfo();                       		break;
		 case 'list'               : $this->getList();                       					break;
         default                   : $cmd == 'list'; $this->getList();	       					break;
      }
 }
function getList(){
     if ((getRequest('submit'))) {
	 	$msg = $this->SaveCustomerInfo();
	 }
  	 $CustomerInfoFetch = $this->CustomerInfoFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveCustomerInfo(){
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addCustomerInfo();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		header('location:?app=customer_info');
	  }
	   if($mode == 'edit'){
	 	$res = $this->EditCustomerInfo();
		 if($res){
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
function addCustomerInfo(){
$branch_id=getFromSession('branch_id');
  	 	 $info = array();
		 $info['table'] = CUSTOMER_INFO_TBL;
		 $reqData =  getUserDataSet(CUSTOMER_INFO_TBL);
		 $reqData['created_by'] = getFromSession('username');
		 $reqData['customer_type'] = '1';
		 $reqData['branch_id'] =$branch_id;
		 $reqData['member_date'] =formatDate4insert(getRequest('member_date'));
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }

function EditCustomerInfo(){
		 
		 $customer_id = getRequest('customer_id');
	 	 $info				 = array();
		 $info['table']     	= CUSTOMER_INFO_TBL;
		 $reqData        		 = getUserDataSet(CUSTOMER_INFO_TBL);
		 $reqData['member_date'] = formatDate4insert(getRequest('member_date'));
		 $info['data'] = $reqData;
      	 $info['where']= "customer_id='$customer_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteCustomerInfo(){
	$customer_id = getRequest('customer_id');
      if($customer_id)
      {
      	            	
      	$info = array();
      	$info['table'] = CUSTOMER_INFO_TBL;
      	$info['where'] = "customer_id='$customer_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=customer_info&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=customer_info&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function CustomerInfoFetch(){
$branch_id=getFromSession('branch_id');
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql='SELECT
						customer_id,
						name,
						member_date,
						mobile,
						email,
						created_by,
						cdate
						FROM '.CUSTOMER_INFO_TBL.' 
						where branch_id=$branch_id and customer_type=1 and name LIKE "%'.$searchq.'%" or mobile LIKE "%'.$searchq.'%" ';
			}else{
				$sql="SELECT
						customer_id,
						name,
						member_date,
						mobile,
						email,
						created_by,
						cdate
						FROM ".CUSTOMER_INFO_TBL." 
					where branch_id=$branch_id and customer_type=1 ";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width="800" border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th  align="left">SL</th>
				 <th  align="left">Customer Name</th>
	             <th  align="left">Mobile</th>
	             <th  align="left">Email</th>
	             <th  align="left">Membership Date</th>
				 <th>&nbsp;</th>
	       </tr>';
                         $rowcolor=0;
	
	while($row=mysql_fetch_array($res)){
					$i=1;
				 if($rowcolor==0){ 
					$style = "oddClassStyle";$rowcolor=1;
				 }else{
					$style = "evenClassStyle";$rowcolor=0;
				 }
				$MeasurementTab .='<tr  class="'.$style.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\''.$style.'\'"
				onclick="javascript:updateCustomerInfo(\''.$row['customer_id'].'\',\''.
														  $row['name'].'\',\''.
														  dateInputFormatDMY($row['member_date']).'\',\''.
														  $row['email'].'\',\''.
						                                  $row['mobile'].'\');" >
						  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["mobile"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["email"].'&nbsp;</td>
					<td nowrap="nowrap">'._date($row["member_date"]).'&nbsp;</td>
					<td>
					<a href="?app=customer_info&cmd=Delete&customer_id='.$row['customer_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>
					</tr>';
	$i++;
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 
 
   
} // End class

?>