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

	$sql="SELECT total_amount,itemcode,discount_percent,totaldiscount,salesprice,item_qnt,sale_pay_id FROM inv_item_sales where sales_id='$sales_id'";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$total_amount=$row['total_amount'];
	$salesprice=$row['salesprice'];
	$item_qnt=$row['item_qnt'];
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
 $invoice = getRequest('invoice');
 $itemcode = getRequest('itemcode');
 //$sales_date = formatDate4insert(getRequest('sales_date'));
 $salesprice = getRequest('salesprice');
 $discount_percent = getRequest('discount_percent');
 $sales_id = getRequest('sales_id');
 $sale_pay_id=getRequest('sale_pay_id');
 $quantity=getRequest('quantity');
$disc=($discount_percent/100);
 
 $totalCost=($quantity*$salesprice);
 
 $net_amount=($totalCost*$disc);
 $return_amount=($totalCost-$net_amount);
 
 
 $return_date=date('Y-m-d');
 $branch_id = getFromSession('branch_id');
 
 //===== Check New Sales Item ===========================================
 	$sqlChk="SELECT * FROM inv_item_sales where itemcode='$itemcode' and sales_date='$sales_date' 
	and customer_id='$customer_id' and branch_id='$branch_id' and sales_type='2' and invoice='$invoice'";
	$resChk= mysql_query($sqlChk); //exit();
 //======================================================================
 	$sql2="SELECT item_qnt FROM inv_item_sales where sales_id='$sales_id'";
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	$item_qnt=$row2['item_qnt'];
	
	if(mysql_num_rows($resChk)<=0){
	header("location:?app=whole_sales_return&sales_id=$sales_id&customer_id=$customer_id&msg=Item code or date info Worning!!");
	}else{
	
		if($quantity<=$item_qnt){
 
			$sql = "UPDATE inv_item_sales SET return_qnt = '$quantity', return_amount = '$return_amount', return_date='$return_date' 
			WHERE sales_id = $sales_id";//exit();
			$res = mysql_query($sql);

			 $sql3 = "UPDATE inv_item_sales_payment SET ret_amount = '$return_amount' WHERE sale_pay_id = $sale_pay_id";//exit();
			 $res3 = mysql_query($sql3);

			 $sql4 = "UPDATE inv_item_sales SET return_amount = '$return_amount' WHERE itemcode='$itemcode' and sales_date='$sales_date' 
	and customer_id='$customer_id' and branch_id='$branch_id' and sales_type='2' and invoice='$invoice'";//exit();
			 $res4 = mysql_query($sql4);
			
				if($res3)
				{
					$sqlSup = "INSERT INTO whole_saler_acc(customer_id,particulars,cr_amount,paid_date,branch_id,pay_type) 
					values('$customer_id','Sales Return',$return_amount,'$return_date','$branch_id','Cash')";
					mysql_query($sqlSup);
				
				header('location:?app=inv_item_sales_list_whole&msg=Return Success!!');
				}else{
				header('location:?app=inv_item_sales_list_whole&msg=Not Returned');
				}
		}else{
			header("location:?app=whole_sales_return&sales_id=$sales_id&customer_id=$customer_id&msg=Worning!! Return Quantity will not be more");
		}// end $quantity<=$tqnt1c else
	}
	
}


} // End class
?>
