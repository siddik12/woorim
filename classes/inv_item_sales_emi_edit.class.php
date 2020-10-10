<?php
class inv_item_sales_emi_edit
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'SaveItemSales' 				: echo $this->SaveItemSales();					break;
		 case 'DeleteSalesItem' 			: echo $this->DeleteSalesItem();				break;
		 case 'CancelSalesItem' 			: echo $this->CancelSalesItem();				break;

		 case 'ComplateSales' 				: echo $this->ComplateSales();					break;
		 case 'ajaxInvoicePrintViewSkin' 		: echo $this->InvoicePrintViewSkin();			break;
		 
		 case 'ajaxEditItemSales' 			: echo $this->EditItemSales();					break;
	 	 case 'ajaxSalesManLoad'  			: echo $this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'), getRequest('ele_lbl_id1')); 	break;
		
		 case 'ajaxcheckExistingItem'  		: $this->CheckExistingItem(getRequest('itemcode')); 	break;

		 case 'ajaxModel'	   					: $this->SelectModel(); 				break;
		 
		 case 'list'                  		: $this->getList();                       			break;
		 default                      		: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$branch_id = getFromSession('branch_id');
 $invoice = getRequest('invoice');
 $customer_id = getRequest('customer_id');
 $sales_date = getRequest('sales_date');
 $sale_pay_id = getRequest('sale_pay_id');

/*$sql3 = "UPDATE inv_item_sales SET sales_status='Pending'
WHERE branch_id=$branch_id and sales_type=1 and customer_id=$customer_id and invoice='$invoice'  and sales_date='$sales_date'"; //exit();
$res3 = mysql_query($sql3);
*/

$sql="SELECT
			sum(totaldiscount) AS totaldiscount,
			sum(total_amount) as total_amount,
			sum(item_qnt) as item_qnt,
			sale_pay_id,
			customer_id,
			sales_date,
			invoice
		FROM
			inv_item_sales where sale_pay_id='$sale_pay_id' and branch_id=$branch_id and sales_type=1 and customer_id=$customer_id and invoice='$invoice'  and sales_date='$sales_date'";
			
		$res= mysql_query($sql);
		$row = mysql_fetch_array($res);
		$total_amount=$row['total_amount'];
		$totaldiscount=$row['totaldiscount'];
		$invoice=$row['invoice'];
		$customer_id=$row['customer_id'];
		$sales_date=$row['sales_date'];
		$sale_pay_id=$row['sale_pay_id'];
		$item_qnt=$row['item_qnt'];


		  $sql2 = "SELECT store_name from customer_info where customer_id='$customer_id'";
			$res2= mysql_query($sql2);
			$row2 = mysql_fetch_array($res2);
			$store_name=$row2['store_name'];
		
	$ItemIssue = $this->ItemIssueFetch();

	
	require_once(CURRENT_APP_SKIN_FILE);
  }


function SaveItemSales(){
$invoice = getRequest('invoice');
$customer_id = getRequest('customer_id');
$sales_date = getRequest('sales_date');
$sale_pay_id = getRequest('sale_pay_id');

     $person_id = getFromSession('person_id');
     $branch_id = getFromSession('branch_id');
     $item_size = getRequest('item_size');
    $sales_price = getRequest('salesprice');
	$warranty = getRequest('warranty');
     
 $sqlf = "SELECT item_id FROM inv_iteminfo where item_size = '$item_size' and branch_id=$branch_id";//exit();
$resf = mysql_query($sqlf);
$rowf = mysql_fetch_array($resf); 
$item_id = $rowf['item_id'];  
             
			 //  for select item information-------------------------
                  $selectItem = "SELECT
                item_id,
                item_size,
                description,
                cost_price,
                sales_price,
                item_code,
                description,
				i.sub_item_category_id,
				i.main_item_category_id,
				sub_item_category_name,
				main_item_category_name
            FROM
                inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=i.main_item_category_id
				where item_id = '$item_id' and branch_id=$branch_id";//exit();
                 
                 $resItem = mysql_query($selectItem);
                 $itemCnt = mysql_num_rows($resItem);
                 if($itemCnt<=0){
                 header('location:?app=inv_item_sales_emi_edit&salesprice='.$sales_price.'&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&warranty='.$warranty.'&msg=Not Found This Item');
                 }else{
                    $rowItem = mysql_fetch_array($resItem);
                     
                     $item_id = $rowItem['item_id'];
                     $cost_price = $rowItem['cost_price'];
                     //$sales_price = $rowItem['sales_price'];
                     $description = $rowItem['description'];
                     $sub_item_category_id = $rowItem['sub_item_category_id'];
                     $item_qnt=1;
                     //$sales_date=date('Y-m-d');     
                     //======================== Available Quantity count =====================
                        $sqlStk = "SELECT sum(quantity) as rec_quantity FROM inv_iteminfo 
						where item_id ='$item_id' and branch_id=$branch_id";
                        $resStk =mysql_query($sqlStk);
                        $rowStk = mysql_fetch_array($resStk);
                        $rec_quantity = $rowStk['rec_quantity'];
                    
 
                         $sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
						where item_id ='$item_id' and branch_id=$branch_id";
                        $resStk1 =mysql_query($sqlStk1);
                        $rowStk1 = mysql_fetch_array($resStk1);
                        $stock_out = $rowStk1['stock_out'];

                        $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
						where item_id ='$item_id' and branch_id=$branch_id";
                        $res2c =mysql_query($sql2c);
                        $row2c = mysql_fetch_array($res2c);
                        $sals_quantity = $row2c['sals_quantity'];
                       
                        $Stock =(($rec_quantity)-($sals_quantity+$stock_out));// exit();
                        //======================== End =====================
                        if($Stock<=0){
                            header('location:?app=inv_item_sales_emi_edit&salesprice='.$sales_price.'&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&warranty='.$warranty.'&msg=Not available in stock !!');
                        }else{
                 
$sql2 = "INSERT INTO ".INV_ITEM_SALES_TBL."(item_id, sub_item_category_id, item_qnt, costprice, salesprice,sales_date,person_id,total_amount,createdby,branch_id,sales_type,sale_pay_id,customer_id,invoice,sales_status,warranty)
VALUES($item_id, '$sub_item_category_id', '$item_qnt', '$cost_price', '$sales_price', '$sales_date', '$person_id', '$sales_price','$person_id','$branch_id','1','$sale_pay_id','$customer_id','$invoice','Not Pending','$warranty')"; //exit();
                    $res = mysql_query($sql2);
                        if($res){
header('location:?app=inv_item_sales_emi_edit&salesprice='.$sales_price.'&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&warranty='.$warranty.'&msg=Item Saved');
                        }else{
header('location:?app=inv_item_sales_emi_edit&salesprice='.$sales_price.'&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&warranty='.$warranty.'&msg=Not Saved');
                        }
                    }// End $Stock<=0
            }// end $itemCnt<=0
	}//eof

function EditItemSales(){

$invoice = getRequest('invoice');
$customer_id = getRequest('customer_id');
$sales_date = getRequest('sales_date');
$sale_pay_id = getRequest('sale_pay_id');

 $salesprice = getRequest('salesprice');
 $sales_id = getRequest('sales_id');
 $discount = getRequest('discount');
 $item_qnt = getRequest('item_qnt');
 $Warranty = getRequest('warranty');
 $sub_item_category_id = getRequest('sub_item_category_id');
 $branch_id = getFromSession('branch_id');
 
  $DiscountPercent=($discount/100);
  $ItemPrice=($salesprice*$item_qnt);
 $TotalDisc=($ItemPrice*$DiscountPercent);
 //$NetSales=($salesprice-$TotalDisc);

 
 $NetSales=($ItemPrice-$TotalDisc);
 
					$sql = "UPDATE inv_item_sales SET salesprice = '$salesprice',total_amount = '$salesprice'
					WHERE sub_item_category_id = $sub_item_category_id and branch_id=$branch_id and sales_date='$sales_date' and sale_pay_id=$sale_pay_id"; //exit();
							
				$res = mysql_query($sql);
					if($res)
					{
					header('location:?app=inv_item_sales_emi_edit&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&msg=Item Saved');
					}else{
					header('location:?app=inv_item_sales_emi_edit&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&msg=Not Saved');
					}


}


function DeleteSalesItem(){
$invoice = getRequest('invoice');
$customer_id = getRequest('customer_id');
$sales_date = getRequest('sales_date');
$sale_pay_id = getRequest('sale_pay_id');

	$sales_id = getRequest('sales_id');
  	if($sales_id)  {
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_id = '$sales_id'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales_emi_edit&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&msg=Item Deleted');
		}else{
		header('location:?app=inv_item_sales_emi_edit&sales_date='.$sales_date.'&customer_id='.$customer_id.'&invoice='.$invoice.'&sale_pay_id='.$sale_pay_id.'&msg=Not Deleted');
		}
  	}	
}  



function ItemIssueFetch(){
$invoice = getRequest('invoice');
$customer_id = getRequest('customer_id');
$sales_date = getRequest('sales_date');
$sale_pay_id = getRequest('sale_pay_id');

$branch_id = getFromSession('branch_id');
		 $sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				discount,
				person_id,
				total_amount,
				item_qnt,
				item_size,
				i.sub_item_category_id,
				sub_item_category_name,
				main_item_category_name,
				warranty

			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id 
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				where sale_pay_id='$sale_pay_id' and sales_date='$sales_date' and s.branch_id=$branch_id and sales_type=1";
			
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="700">
	            <tr>
				<th align=left width="50">Delete</th>
				<th align=left width="50">&nbsp;</th>
				 <th nowrap=nowrap align=left width="100">Category</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Price</th>
				 <th nowrap=nowrap align=left>Qnt.</th>
				 <!--<th nowrap=nowrap align=left>Discount(%)</th>-->
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Warranty</th>
				 <th nowrap=nowrap align=left>Edit</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<form action="?app=inv_item_sales_emi_edit&cmd=ajaxEditItemSales" method="post" name="itmsls">
			<div><tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" height="35">	
			<td nowrap=nowrap><a href="?app=inv_item_sales_emi_edit&cmd=DeleteSalesItem&sales_id='.$row['sales_id'].'&invoice='.$invoice.'&customer_id='.$customer_id.'&sales_date='.$sales_date.'&sale_pay_id='.$sale_pay_id.'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			<td width="30"><a onClick="javascript:SetToTop(\''.$row['salesprice'].'\',\''.$row['warranty'].'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'<br>'.$row['item_size'].'</td>
			<td nowrap=nowrap><input type="text" name="salesprice" id="salesprice" value="'.$row['salesprice'].'" size=7 /></td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
<!--		<td nowrap=nowrap></td>-->		
			<td nowrap=nowrap>'.$row['total_amount'].'<input type="hidden" name="sales_id" id="sales_id" value="'.$row['sales_id'].'" size=5 class="textBox"/>
			<input type="hidden" name="sub_item_category_id" id="item_code" value="'.$row['sub_item_category_id'].'" size=5 class="textBox"/>
			<input type="hidden" name="discount" id="discount" value="'.$row['discount'].'" size=5 />
			<input type="hidden" name="sales_date" id="sales_date" value="'.getRequest('sales_date').'" size=5 />
					<input type="hidden" name="customer_id" id="customer_id" value="'.getRequest('customer_id').'" size=5 />
					<input type="hidden" name="sale_pay_id" id="sale_pay_id" value="'.getRequest('sale_pay_id').'" size=5 />
					<input type="hidden" name="invoice" id="invoice" value="'.getRequest('invoice').'" size=5 /></td>
			<td nowrap=nowrap>'.$row['warranty'].'</td>
			<td nowrap=nowrap><input name="submit" type="submit" value="Edit" style="cursor:pointer" class="btnClass"/></td>
			</tr></div></form>';
		}
		$type .= '</table>
	      ';
		return $type;
}//end fnc


function ComplateSales(){
	$branch_id = getFromSession('branch_id');
 	$amount = getRequest('amount');
 	$total_discount = getRequest('total_discount');
 	
	$totaldiscount = getRequest('totaldiscount');
 	$total_amount = getRequest('total_amount');

	$afterDiscAmnt=($amount-$totaldiscount);

	$allDiscount=($total_discount+$totaldiscount);

 	$paid_amount = getRequest('paid_amount');
	$dues_amount=($afterDiscAmnt-$paid_amount);	 	
	 $person_id = getRequest('person_id');
	 $customer_id = getRequest('customer_id');
	 $person_id = getFromSession('person_id');
	 //$wholePAidDate=date('Y-m-d');
	 //$bankid = getRequest('bankid');
	 $pay_type = getRequest('pay_type');
		$invoice = getRequest('invoice');
		$customer_id = getRequest('customer_id');
		$sales_date = getRequest('sales_date');
		$sale_pay_id = getRequest('sale_pay_id');
		
				$sqlDisc="UPDATE ".INV_ITEM_SALES_PAYMENT_TBL." SET total_discount ='$allDiscount', total_amount='$afterDiscAmnt',paid_amount='$paid_amount',dues_amount='$dues_amount'
			   WHERE sale_pay_id ='$sale_pay_id' and branch_id=$branch_id and customer_id='$customer_id'"; 
				$resDisc = mysql_query($sqlDisc);
				  
					if($resDisc){
						$sqlDisc2="UPDATE whole_saler_acc SET dr_amount ='$total_amount',cr_amount ='$paid_amount' WHERE customer_id ='$customer_id' and branch_id=$branch_id and invoice='$invoice'";
						$resDisc2 = mysql_query($sqlDisc2);
				 	 header('location:?app=inv_item_sales_emi&cmd=ajaxInvoicePrintViewSkin&invoice='.$invoice.'');
				 	}
		
  }//eof
 

} // End class
?>
