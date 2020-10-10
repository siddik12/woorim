<?php
class stock_out
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'EditStcokOut' 				: echo $this->EditStcokOut();					break;
		 case 'list'                  		: $this->getList();                       			break;
		 default                      		: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$item_code=getRequest('item_code');

	$sql="SELECT max(ware_house_id) as ware_house_id FROM ware_house where item_code='$item_code'";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$ware_house_id=$row['ware_house_id'];


	$sql2="SELECT item_name,cost_price FROM ware_house where item_code='$item_code' and ware_house_id='$ware_house_id'";
	$res2= mysql_query($sql2);
	$row2= mysql_fetch_array($res2);
	$item_name=$row2['item_name'];
	$cost_price=$row2['cost_price'];
	
	
	$SelectSupplier=$this->SelectSupplier();	
	require_once(CURRENT_APP_SKIN_FILE);
  }


function EditStcokOut(){
 $quantity = getRequest('quantity');
 $supplier_ID = getRequest('supplier_ID');
 $item_code=getRequest('item_code');
 $item_name=getRequest('item_name');
 $cost_price=getRequest('cost_price');
 $particulars='Damage '.$quantity.' Item-'.$item_code.'';
 $damage_date=date('Y-m-d');

 
 $totalCost=($quantity*$cost_price);
 $receive_date=date('Y-m-d');
 
 	$sql2="SELECT * FROM ware_house where item_code='$item_code' and supplier_ID='$supplier_ID'";
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	if(mysql_num_rows($res2)<=0){
	header("location:?app=stock_out&quantity=$quantity&item_code=$item_code&msg=Worning!! Not matched code and supplier");
	}else{
			$sql = "INSERT INTO damage_info(item_code, stock_out, damage_date, supplier_ID, item_name,cost_price)
			VALUES('$item_code','$quantity','$damage_date', '$supplier_ID', '$item_name','$cost_price')";
						
			$res = mysql_query($sql);
				if($res)
				{
					$sqlSup = "INSERT INTO detail_account(supplier_ID,particular,cr,recdate) 	
								values('$supplier_ID','$particulars',$totalCost,'$receive_date')";
					mysql_query($sqlSup);
				
				header('location:?app=inv_report_generator_head&cmd=DamageRequestList&msg=Saved');
				}else{
				header('location:?app=inv_report_generator_head&cmd=DamageRequestList&msg=Not Saved');
				}

	}// end else

}

function SelectSupplier($sup = null){ 
		$sql="SELECT supplier_ID, company_name FROM ".INV_SUPPLIER_INFO_TBL." ORDER BY supplier_ID";
	    $result = mysql_query($sql);
		$country_select = "<select name='supplier_ID' size='1' id='supplier_ID' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['supplier_ID'] == $sup){
					   $country_select .= "<option value='".$row['supplier_ID']."' selected='selected'>".$row['company_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['supplier_ID']."'>".$row['company_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }



} // End class
?>
