<?php
class invest_exp
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
	 $ItmCategory = $this->ItmCategory(); 
	 $SelectBranch = $this->SelectBranch(); 
	 $SelectShareHolder = $this->SelectShareHolder();
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
		 //header('location:?app=inv_supplierinfo');
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
		 $info['table'] = INVEST_INCOME_TBL;
		 $reqData =  getUserDataSet(INVEST_INCOME_TBL);
		 $reqData['created_by'] = getFromSession('userid');
		 $reqData['invdate'] = formatDate4insert(getRequest('invdate'));
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }

function EditSupplierInfo(){
		 
		 $invest_id = getRequest('invest_id');
	 	 $info				 = array();
		 $info['table']     	= INVEST_INCOME_TBL;
		 $reqData        		 = getUserDataSet(INVEST_INCOME_TBL);
		 $reqData['invdate'] = formatDate4insert(getRequest('invdate'));
		 $info['data'] = $reqData;
      	 $info['where']= "invest_id='$invest_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteSupplierInfo(){
	$invest_id = getRequest('invest_id');
      if($invest_id)
      {
      	            	
      	$info = array();
      	$info['table'] = INVEST_INCOME_TBL;
      	$info['where'] = "invest_id='$invest_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=invest_exp&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=invest_exp&Dmsg=Not Edited");      	   	
      	}      	
      }	
   }  

function SupplierInfoFetch(){
	 $searchq = getRequest('searchq');
	 $branch_id = getFromSession('branch_id');
		if($searchq){
	$sql='SELECT
	invest_id,
	i.holder_id,
	holder_name,
	i.branch_id,
	branch_name,
	i.main_item_category_id,
	main_item_category_name,
	invest,
	profit,
	expense,
	invdate
FROM
	invest_income i inner join inv_item_category_main m on m.main_item_category_id=i.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	inner join share_holder s on s.holder_id=i.holder_id where expense!="0.00"
	holder_name LIKE "%'.$searchq.'%" or main_item_category_name LIKE "%'.$searchq.'%" or branch_name LIKE "%'.$searchq.'%" ';
	}else{
	$sql="SELECT
	invest_id,
	i.holder_id,
	holder_name,
	i.branch_id,
	branch_name,
	i.main_item_category_id,
	main_item_category_name,
	invest,
	profit,
	expense,
	invdate
FROM
	invest_income i inner join inv_item_category_main m on m.main_item_category_id=i.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	inner join share_holder s on s.holder_id=i.holder_id where expense!='0.00'";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width="100%" border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th  align="left">SL</th>
				 <th  align="left">Share Holder</th>
	             <th  align="left" >Shop</th>
	             <th  align="left" >Brand Name</th>
	             <th  align="left" >Expense Amount</th>
	             <th  align="left" >Date</th>
				 <th>&nbsp;</th>
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
				onclick="javascript:updateSupplierInfo(\''.$row['invest_id'].'\',\''.
														  $row['holder_id'].'\',\''.
														  $row['branch_id'].'\',\''.
														  $row['main_item_category_id'].'\',\''.
														  $row['invdate'].'\',\''.
						                                  $row['expense'].'\');" >
						  	
					<td nowrap="nowrap">'.$i.'&nbsp;</td>
					<td nowrap="nowrap">'.$row["holder_name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["branch_name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["main_item_category_name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row["expense"].'&nbsp;</td>
					<td nowrap="nowrap">'._date($row["invdate"]).'&nbsp;</td>
					<td>
					<a href="?app=invest_exp&cmd=Delete&invest_id='.$row['invest_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>
					</tr>';
	$i++;
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 

function ItmCategory($cate = null){ 
		$sql="SELECT main_item_category_id, main_item_category_name FROM inv_item_category_main ORDER BY main_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='main_item_category_id' size='1' id='main_item_category_id' class=\"textBox\" style='width:150px;' onchange=\"getModelId(this.value)\">";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['main_item_category_id'] == $cate){
					   $country_select .= "<option value='".$row['main_item_category_id']."' selected='selected'>".$row['main_item_category_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['main_item_category_id']."'>".$row['main_item_category_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }

function SelectBranch($branch = null){ 

		$sql="SELECT branch_id, branch_name FROM branch";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:215px' class=\"textBox\">";
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
 
 function SelectShareHolder(){ 

		 $sql="SELECT holder_id,holder_name from share_holder ORDER BY holder_name";
	    $result = mysql_query($sql);
		
	    $Supplier_select = "<select name='holder_id' id='holder_id' class=\"textBox\" style='width:150px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['holder_id']."'>".$row['holder_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }
 
   
} // End class

?>