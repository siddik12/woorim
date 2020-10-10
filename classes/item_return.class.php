<?php
class item_return
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'ItemReturn' 				: echo $this->ItemReturn();					break;
		 case 'list'                  		: $this->getList();                       			break;
		 default                      		: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$item_code=getRequest('item_code');
$branch_id=getRequest('branch_id');

	$sql="SELECT max(item_id) as item_id,item_name,cost_price FROM inv_iteminfo where item_code='$item_code' and branch_id= $branch_id";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$item_id=$row['item_id'];
	$item_name=$row['item_name'];
	$cost_price=$row['cost_price'];
	//$damage_date = date('Y-m-d');
	
	$sql2="SELECT branch_name FROM branch where branch_id= $branch_id";
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	$branch_name=$row2['branch_name'];
	
	
	require_once(CURRENT_APP_SKIN_FILE);
  }


function ItemReturn(){
 $item_id = getRequest('item_id');
 $item_name = getRequest('item_name');
 $quantity = getRequest('quantity');
 $item_code=getRequest('item_code');
 $tqnt1c=getRequest('tqnt1c');
 $cost_price=getRequest('cost_price');
 $particulars='Return Item-'.$item_code.'';
 $branch_id=getRequest('branch_id');
 $return_purpose=getRequest('return_purpose');
 $damage_date=formatDate4insert(getRequest('damage_date'));
 
 $totalCost=($quantity*$cost_price);
 
	
		if($quantity<=$tqnt1c){
 
			$sql = "INSERT INTO return_info(item_code, stock_out, return_purpose, damage_date, branch_id,item_name,cost_price)
			VALUES('$item_code','$quantity','$return_purpose','$damage_date','$branch_id','$item_name','$cost_price' )";
			$res = mysql_query($sql);
				if($res)
				{
					header("location:?app=inv_report_generator&cmd=currentStockReportSkin&branch_id=$branch_id&msg=Saved");
				}else{
					header("location:?app=inv_report_generator&cmd=currentStockReportSkin&branch_id=$branch_id&msg=Not Saved");
				}
		}else{
			header("location:?app=item_return&tqnt1c=$tqnt1c&item_code=$item_code&branch_id=$branch_id&msg=Worning!! Stock not Sufficient");
		}// end $quantity<=$tqnt1c else
	

}


} // End class
?>
