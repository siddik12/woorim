<?php
class whole_sales_return
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'WholeSalesReturn' 			:  $this->WholeSalesReturn();					break;
		 case 'list'                  		: $this->getList();                       			break;
		 default                      		: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$sales_id=getRequest('sales_id');
$customer_id=getRequest('customer_id');

	$sql="SELECT total_amount,itemcode,discount_percent,totaldiscount,salesprice,item_qnt,return_qnt,return_amount,sale_pay_id FROM inv_item_sales where sales_id='$sales_id'";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$total_amount=$row['total_amount'];
	$return_amount=$row['return_amount'];
	$salesprice=$row['salesprice'];
	$item_qnt=$row['item_qnt'];
	$return_qnt=$row['return_qnt'];
	$sale_pay_id=$row['sale_pay_id'];
	$itemcode=$row['itemcode'];
	$discount_percent=$row['discount_percent'];
	$totaldiscount=$row['totaldiscount'];
	//$return_date = date('Y-m-d');

	$sql2="SELECT store_name FROM customer_info where customer_id='$customer_id'";
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	$store_name=$row2['store_name'];


	
	require_once(CURRENT_APP_SKIN_FILE);
  }


function WholeSalesReturn(){
 $customer_id = getRequest('customer_id');
 //$invoice = getRequest('invoice');
 //$itemcode = getRequest('itemcode');
 //$sales_date = formatDate4insert(getRequest('sales_date'));
 $salesprice = getRequest('salesprice');
 $discount_percent = getRequest('discount_percent');
 $sales_id = getRequest('sales_id');
 $sale_pay_id=getRequest('sale_pay_id');
 $quantity=getRequest('quantity');
 $total_amount=getRequest('total_amount');

 $soldQnt=getRequest('soldQnt');
 
 $return_Value=($total_amount/$soldQnt);
 $returnValue=($return_Value*$quantity);
 
$disc=($discount_percent/100);
 
 $totalCost=($quantity*$salesprice);
 
 $net_amount=($totalCost*$disc);
 $return_amount=($totalCost-$net_amount);
 
 
 $return_date=date('Y-m-d');
 $branch_id = getFromSession('branch_id');
 
 //===== select from slaes table ===========================================
 	$sqlChk="SELECT item_qnt, return_qnt,return_amount FROM inv_item_sales where sales_id='$sales_id' and branch_id='$branch_id' and sales_type='2'";
	$resChk= mysql_query($sqlChk); //exit();
	$rowChk = mysql_fetch_array($resChk);
	$return_qntChk=$rowChk['return_qnt'];
	$return_amountChk=$rowChk['return_amount'];
	$item_qntChk=$rowChk['item_qnt'];

 //======================================================================
  //===== select from slaes table ===========================================

 	$sql2="SELECT ret_amount FROM inv_item_sales_payment where sale_pay_id='$sale_pay_id' and branch_id='$branch_id' and sales_type='2'"; //exit();
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	$return_amount2=$row2['ret_amount'];
 //======================================================================
	
		if($quantity<=$item_qntChk){
		
		$total_RetQnt1=($quantity+$return_qntChk);
		$total_RetAmount1=($return_amountChk+$returnValue);
 
			$sql = "UPDATE inv_item_sales SET return_qnt = '$total_RetQnt1', return_amount = '$total_RetAmount1', return_date='$return_date' 
			WHERE sales_id = $sales_id";//exit();
			$res = mysql_query($sql);
			
			$total_RetAmount2=($return_amount2+$returnValue);

			 $sql3 = "UPDATE inv_item_sales_payment SET ret_amount = '$total_RetAmount2' WHERE sale_pay_id = $sale_pay_id";//exit();
			 $res3 = mysql_query($sql3);

				if($res3)
				{
					$sqlSup = "INSERT INTO whole_saler_acc(customer_id,particulars,cr_amount,paid_date,branch_id,pay_type,stat) 
					values('$customer_id','Item Return Adjustment',$returnValue,'$return_date','$branch_id','Cash','Return')";
					mysql_query($sqlSup);
				
				header('location:?app=inv_item_sales_list_whole&msg=Return Success!!');
				}else{
				header('location:?app=inv_item_sales_list_whole&msg=Not Returned');
				}
		}else{
			header("location:?app=whole_sales_return&sales_id=$sales_id&customer_id=$customer_id&msg=Worning!! Return Quantity will not be more");
		}// end $quantity<=$tqnt1c else
	
	
}


} // End class
?>
