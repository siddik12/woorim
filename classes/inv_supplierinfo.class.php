<?php
class inv_supplierinfo
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxSearchSupplier' : echo $this->SupplierInfoFetch();   						break;
		 case 'Delete'             : $this->DeleteSupplierInfo();                       		break;
		 case 'list'               : $this->getList();                       					break;
         default                   : $cmd == 'list'; $this->getList();	       					break;
      }
 }
function getList(){
     if ((getRequest('submit'))) {
	 	$msg = $this->SaveSupplierInfo();
	 }
  	 $SupplierInfoFetch = $this->SupplierInfoFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveSupplierInfo(){
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addSupplierInfo();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		 header('location:?app=inv_supplierinfo');
	  }
	   if($mode == 'edit'){
	 	$res = $this->EditSupplierInfo();
		 if($res){
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
function addSupplierInfo(){
  	 	 $info = array();
		 $info['table'] = INV_SUPPLIER_INFO_TBL;
		 $reqData =  getUserDataSet(INV_SUPPLIER_INFO_TBL);
		 $reqData['created_by'] = getFromSession('username');
		 $reqData['branch_id'] = getFromSession('branch_id');
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }

function EditSupplierInfo(){
		 
		 $supplier_ID = getRequest('supplier_ID');
	 	 $info				 = array();
		 $info['table']     	= INV_SUPPLIER_INFO_TBL;
		 $reqData        		 = getUserDataSet(INV_SUPPLIER_INFO_TBL);
		 $info['data'] = $reqData;
      	 $info['where']= "supplier_ID='$supplier_ID'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteSupplierInfo(){
	$supplier_ID = getRequest('supplier_ID');
      if($supplier_ID)
      {
      	            	
      	$info = array();
      	$info['table'] = INV_SUPPLIER_INFO_TBL;
      	$info['where'] = "supplier_ID='$supplier_ID'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=inv_supplierinfo&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=inv_supplierinfo&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function SupplierInfoFetch(){
	 $searchq = getRequest('searchq');
	 $branch_id = getFromSession('branch_id');
		if($searchq){
					$sql='SELECT
						supplier_ID,
						company_name,
						contact_person,
						address,
						mobile,
						email,
						acc_no,
					created_by,
						cdate
						FROM '.INV_SUPPLIER_INFO_TBL.' where  
						company_name LIKE "%'.$searchq.'%" or contact_person LIKE "%'.$searchq.'%" 
						or mobile LIKE "%'.$searchq.'%" order by company_name';
			}else{
				$sql="SELECT
						supplier_ID,
						company_name,
						contact_person,
						address,
						mobile,
						email,
						acc_no,
					created_by,
						cdate
					FROM ".INV_SUPPLIER_INFO_TBL." order by company_name";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width="100%" border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th  align="left">SL</th>
				 <th  align="left">Company Name</th>
	             <th  align="left" >Contact Person</th>
	             <th  align="left" >Mobile</th>
<!--	             <th  align="left" >Email</th>
-->	             <th  align="left" >Address</th>
<!--	             <th  align="left" >Acount No.</th>
-->				 <th>&nbsp;</th>
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
				onclick="javascript:updateSupplierInfo(\''.$row['supplier_ID'].'\',\''.
														  $row['company_name'].'\',\''.
														  $row['contact_person'].'\',\''.
														  $row['address'].'\',\''.
														  $row['email'].'\',\''.
														  $row['mobile'].'\',\''.
						                                  $row['acc_no'].'\');" >
						  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["company_name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["contact_person"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["mobile"].'&nbsp;</td>
<!--					<td nowrap="nowrap">'.$row["email"].'&nbsp;</td>
-->					<td>'.$row["address"].'&nbsp;</td>
<!--					<td nowrap="nowrap">'.$row["acc_no"].'&nbsp;</td>
-->					<td>
					<!--<a href="?app=inv_supplierinfo&cmd=Delete&supplier_ID='.$row['supplier_ID'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a>--></td>
					</tr>';
	$i++;
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 
 
   
} // End class

?>