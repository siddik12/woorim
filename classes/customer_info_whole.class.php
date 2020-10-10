<?php
class customer_info_whole
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
  	 $SelectDistrict = $this->SelectDistrict();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveCustomerInfo(){
	 $mode = getRequest('mode');
	 $company_name = getRequest('store_name');
	 $contact_person = getRequest('name');
	 $address = getRequest('address');
	 $mobile = getRequest('mobile');
	 $created_by=getFromSession('username');
	 $branch_id = getFromSession('branch_id');
	 
	 if($mode == 'add'){
	 	$res = $this->addCustomerInfo();
		if($res['newid']){
		$customer_id = $res['newid'];
		 $sql2 = "INSERT INTO ".INV_SUPPLIER_INFO_TBL."(customer_id, company_name, contact_person, address, mobile,created_by,branch_id)
                    VALUES($customer_id, '$company_name', '$contact_person', '$address', '$mobile','$created_by','$branch_id')"; //exit();
                    $res = mysql_query($sql2);
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		header('location:?app=customer_info_whole');
	  }
	   if($mode == 'edit'){
	   $customerID = getRequest('customer_id');
	 	$res = $this->EditCustomerInfo();
		 if($res){
			$selectItem = "UPDATE ".INV_SUPPLIER_INFO_TBL." SET company_name='$company_name', contact_person='$contact_person', 
			  address='$address', mobile='$mobile' WHERE customer_id ='$customerID'";//exit();
	  			$resItem = mysql_query($selectItem);
		 
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  } 
   
function addCustomerInfo(){
$branch_id = getFromSession('branch_id');
  	 	 $info = array();
		 $info['table'] = CUSTOMER_INFO_TBL;
		 $reqData =  getUserDataSet(CUSTOMER_INFO_TBL);
		 $reqData['created_by'] = getFromSession('username');
		 $reqData['branch_id'] = $branch_id;
		 $reqData['customer_type'] = '2';
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);//exit();
		 return $res;
  }

function EditCustomerInfo(){
		 
		 $customer_id = getRequest('customer_id');
	 	 $info				 = array();
		 $info['table']     	= CUSTOMER_INFO_TBL;
		 $reqData        		 = getUserDataSet(CUSTOMER_INFO_TBL);
		 $info['data'] = $reqData;
      	 $info['where']= "customer_id='$customer_id'";
		// $info['debug']  = true;
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
      	   header("location:?app=customer_info_whole&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=customer_info_whole&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function CustomerInfoFetch(){
$branch_id = getFromSession('branch_id');
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql='SELECT
						customer_id,
						name,
						address,
						mobile,
						email,
						created_by,
						cdate,
						upzila,
						c.branch_id,
						c.district_id,
						district_name,
						store_name,
						viber
						FROM '.CUSTOMER_INFO_TBL.' c 
						inner join '.HRM_DISTRICT_CITY_TBL.' d on d.district_id=c.district_id
						 where branch_id='.$branch_id.' and store_name LIKE "%'.$searchq.'%" or mobile LIKE "%'.$searchq.'%" order by store_name';
			}else{
					$sql='SELECT
						customer_id,
						name,
						address,
						mobile,
						email,
						created_by,
						cdate,
						upzila,
						c.branch_id,
						c.district_id,
						district_name,
						store_name,
						viber
						FROM '.CUSTOMER_INFO_TBL.' c 
						inner join '.HRM_DISTRICT_CITY_TBL.' d on d.district_id=c.district_id
						where branch_id='.$branch_id.' order by store_name';
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width="1000" border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th  align="left">SL</th>
				 <th  align="left">Shop Name</th>
				 <th  align="left">Customer Name</th>
	             <th  align="left">District</th>
<!--	             <th  align="left">Thana</th>
-->	             <th  align="left">Mobile</th>
<!--	             <th  align="left">What apps/viber</th>
	             <th  align="left">Email</th>
-->	             <th  align="left">Address</th>
				 <th>&nbsp;</th>
	       </tr>';
                       $i=1;  $rowcolor=0;
	
	while($row=mysql_fetch_array($res)){
					
				 if($rowcolor==0){ 
					$style = "oddClassStyle";$rowcolor=1;
				 }else{
					$style = "evenClassStyle";$rowcolor=0;
				 }
				$MeasurementTab .='<tr  class="'.$style.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\''.$style.'\'"
				onclick="javascript:updateCustomerInfo(\''.$row['customer_id'].'\',\''.
														  $row['store_name'].'\',\''.
														  $row['name'].'\',\''.
														  $row['address'].'\',\''.
														  $row['email'].'\',\''.
														  $row['district_id'].'\',\''.
														  $row['upzila'].'\',\''.
														  $row['viber'].'\',\''.
						                                  $row['mobile'].'\');" >
						  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["store_name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["district_name"].'&nbsp;</td>
<!--					<td nowrap="nowrap">'.$row["upzila"].'&nbsp;</td>
-->					<td nowrap="nowrap">'.$row["mobile"].'&nbsp;</td>
<!--					<td nowrap="nowrap">'.$row["viber"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["email"].'&nbsp;</td>
-->					<td>'.$row["address"].'&nbsp;</td>
					<td>
					<a href="?app=customer_info_whole&cmd=Delete&customer_id='.$row['customer_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>
					</tr>';
	$i++;
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 
 
function SelectBranch($branch = null){ 
		$sql="SELECT branch_id, branch_name FROM branch";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:150px' class=\"textBox\">";
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

function SelectDistrict($dist = null){ 
		$sql="SELECT district_id, district_name FROM district_city";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='district_id' size='1' id='district_id' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['district_id'] == $dist){
			$branch_select .= "<option value='".$row['district_id']."' selected = 'selected'>".$row['district_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['district_id']."'>".$row['district_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 
 
   
} // End class

?>