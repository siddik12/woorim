<?php
class inv_item_sales_whole
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
	 	 case 'ajaxSalesManLoad'  			: echo $this->SalesWholeSaller(getRequest('ele_id'), getRequest('ele_lbl_id')); 	break;
		
		 case 'ajaxcheckExistingItem'  		: $this->CheckExistingItem(getRequest('itemcode')); 	break;

		 case 'ajaxModel'	   					: $this->SelectModel(); 				break;
		 
		 case 'list'                  		: $this->getList();                       			break;
		 default                      		: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$branch_id = getFromSession('branch_id');
$sql="SELECT
			sum(totaldiscount) AS totaldiscount,
			sum(total_amount) as total_amount
		FROM
			inv_item_sales where sales_status='Pending' and branch_id=$branch_id and sales_type=2";
			
		$res= mysql_query($sql);
		while($row = mysql_fetch_array($res)){
		$total_amount=$row['total_amount'];
		$totaldiscount=$row['totaldiscount'];
		}
		
	$ItemIssue = $this->ItemIssueFetch();
	$WholeSallerList=$this->SalesWholeSaller(getRequest('ele_id'), getRequest('ele_lbl_id'));
	$SelectSalesMan=$this->SelectSalesMan();
	$SelectBankList = $this->SelectBankList();
	$curdate=dateInputFormatDMY(SelectCDate());
	$ItmCategory4Model=$this->ItmCategory4Model();
	$SelectModel=$this->SelectModel();

	
	require_once(CURRENT_APP_SKIN_FILE);
  }


 function CheckExistingItem($itemcode){
		  $sql = "SELECT itemcode from ".INV_ITEM_SALES_TBL." where itemcode = '$itemcode';";
		  $itemcode = mysql_num_rows(mysql_query($sql));
		  echo $itemcode.'######';	
	}
   // ===== End chkEmailExistence ========   
 

function SaveItemSales(){

	 $sub_item_category_id = getRequest('sub_item_category_id');
     $person_id = getFromSession('person_id');
     $branch_id = getFromSession('branch_id');
     
/*$sqlf = "SELECT issu_unit_cost,unit_price FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";   
*/             //  for select item information-------------------------
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
				where item_id=(select MAX(item_id) from inv_iteminfo where sub_item_category_id = '$sub_item_category_id' and branch_id=$branch_id)";//exit();
                 
                 $resItem = mysql_query($selectItem);
                 $itemCnt = mysql_num_rows($resItem);
                 if($itemCnt<=0){
                 header('location:?app=inv_item_sales_whole&msg=Not Found This Item');
                 }else{
                    $rowItem = mysql_fetch_array($resItem);
                     
                     $item_id = $rowItem['item_id'];
                     $cost_price = $rowItem['cost_price'];
                     $sales_price = $rowItem['sales_price'];
                     $description = $rowItem['description'];
                     $sub_item_category_id = $rowItem['sub_item_category_id'];
                     $item_qnt=1;
                     $sales_date=date('Y-m-d');     
                     //======================== Available Quantity count =====================
                        $sqlStk = "SELECT sum(quantity) as rec_quantity FROM inv_iteminfo 
						where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id";
                        $resStk =mysql_query($sqlStk);
                        $rowStk = mysql_fetch_array($resStk);
                        $rec_quantity = $rowStk['rec_quantity'];
                    
 
                         $sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
						where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id";
                        $resStk1 =mysql_query($sqlStk1);
                        $rowStk1 = mysql_fetch_array($resStk1);
                        $stock_out = $rowStk1['stock_out'];

                        $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
						where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id  and sales_status ='Not Pending'";
                        $res2c =mysql_query($sql2c);
                        $row2c = mysql_fetch_array($res2c);
                        $sals_quantity = $row2c['sals_quantity'];
                       
                        $Stock =(($rec_quantity)-($sals_quantity+$stock_out));// exit();
                        //======================== End =====================
                        if($Stock<=0){
                            header('location:?app=inv_item_sales_whole&msg=Not available in stock !!');
                        }else{
                 
                     $sql2 = "INSERT INTO ".INV_ITEM_SALES_TBL."(item_id, sub_item_category_id, item_qnt, costprice, salesprice,sales_date,person_id,total_amount,createdby,branch_id,sales_type)
                    VALUES($item_id, '$sub_item_category_id', '$item_qnt', '$cost_price', '$sales_price', '$sales_date', '$person_id', '$sales_price','$person_id','$branch_id','2')"; //exit();
                    $res = mysql_query($sql2);
                        if($res){
                        header('location:?app=inv_item_sales_whole&msg=Item Saved');
                        }else{
                        header('location:?app=inv_item_sales_whole&msg=Not Saved');
                        }
                    }// End $Stock<=0
            }// end $itemCnt<=0
	}//eof

function EditItemSales(){
 $salesprice = getRequest('salesprice');
 $sales_id = getRequest('sales_id');
 $discount = getRequest('discount');
 $item_qnt = getRequest('item_qnt');
 $sub_item_category_id = getRequest('sub_item_category_id');
 $branch_id = getFromSession('branch_id');
 
  $DiscountPercent=($discount/100);
  $ItemPrice=($salesprice*$item_qnt);
 $TotalDisc=($ItemPrice*$DiscountPercent);
 //$NetSales=($salesprice-$TotalDisc);

 
 $NetSales=($ItemPrice-$TotalDisc);
 
	  //======================== Available Quantity count =====================
		$sqlStk = "SELECT sum(quantity) as rec_quantity FROM inv_iteminfo 
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id";
		$resStk =mysql_query($sqlStk);
		$rowStk = mysql_fetch_array($resStk);
		$rec_quantity = $rowStk['rec_quantity'];

		$sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id";
		$resStk1 =mysql_query($sqlStk1);
		$rowStk1 = mysql_fetch_array($resStk1);
		$stock_out = $rowStk1['stock_out'];

	
		$sql2c = "SELECT sum(item_qnt) as sals_quantity FROM inv_item_sales  
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id and sales_type='2' and sales_status ='Not Pending'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$sals_quantity = $row2c['sals_quantity'];
	   
		$Stock =(($rec_quantity)-($sals_quantity+$stock_out)); //exit();
		//======================== End =====================
		if($item_qnt<=$Stock){
					$sql = "UPDATE inv_item_sales
					SET
						total_amount = '$NetSales',
						discount_percent = $discount,
						salesprice = $salesprice,
						totaldiscount = $TotalDisc,
						item_qnt = $item_qnt
					WHERE sales_id = $sales_id and branch_id=$branch_id and sales_type='2'"; //exit();
							
				$res = mysql_query($sql);
					if($res)
					{
					header('location:?app=inv_item_sales_whole&msg=Item Saved');
					}else{
					header('location:?app=inv_item_sales_whole&msg=Not Saved');
					}

		}else{
			
			header('location:?app=inv_item_sales_whole&msg=Not available in stock !!');
 
			}// End $item_qnt>$Stock

}


function DeleteSalesItem(){
	$sales_id = getRequest('sales_id');
  	if($sales_id)  {
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_id = '$sales_id'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales_whole&msg=Item Deleted');
		}else{
		header('location:?app=inv_item_sales_whole&msg=Not Deleted');
		}
  	}	
}  

function CancelSalesItem(){
$branch_id = getFromSession('branch_id');
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_status = 'Pending' and branch_id=$branch_id and sales_type='2'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales_whole&msg=Sales has been canceled');
		}else{
		header('location:?app=inv_item_sales_whole&msg=Not canceled');
		}
  	}	


function ItemIssueFetch(){
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
				i.sub_item_category_id,
				sub_item_category_name,
				main_item_category_name

			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id 
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				where sales_status='Pending' and s.branch_id=$branch_id and sales_type=2";
			
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="700">
	            <tr>
				<th align=left width="50">Delete</th>
				 <th nowrap=nowrap align=left width="100">Category</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Price</th>
				 <th nowrap=nowrap align=left>Qnt.</th>
				 <!--<th nowrap=nowrap align=left>Discount(%)</th>-->
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Edit</th>
				
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<form action="?app=inv_item_sales_whole&cmd=ajaxEditItemSales" method="post" name="itmsls">
			<div><tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">	
			<td nowrap=nowrap><a href="?app=inv_item_sales_whole&cmd=DeleteSalesItem&sales_id='.$row['sales_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap><input type="text" name="salesprice" id="salesprice" value="'.$row['salesprice'].'" size=7 /></td>
			<td nowrap=nowrap><input type="text" name="item_qnt" id="item_qnt" value="'.$row['item_qnt'].'" size=5 /></td>
<!--		<td nowrap=nowrap></td>-->		
			<td nowrap=nowrap>'.$row['total_amount'].'<input type="hidden" name="sales_id" id="sales_id" value="'.$row['sales_id'].'" size=5 />
			<input type="hidden" name="sub_item_category_id" id="item_code" value="'.$row['sub_item_category_id'].'" size=5 />
			<input type="hidden" name="discount" id="discount" value="'.$row['discount'].'" size=5 /></td>
			<td nowrap=nowrap><input name="submit" type="submit" value="Edit" style="cursor:pointer"/></td>
			</tr></div></form>';
		}
		$type .= '</table>';
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
	 $createdby = getFromSession('person_id');
	 //$wholePAidDate=date('Y-m-d');
	 //$bankid = getRequest('bankid');
	 $pay_type = getRequest('pay_type');
	 $sales_date = formatDate4insert(getRequest('sales_date'));
	 //exit();
	 
	// if
	 
 		$info = array();
		 $info['table'] = INV_ITEM_SALES_PAYMENT_TBL;
		 $reqData =  getUserDataSet(INV_ITEM_SALES_PAYMENT_TBL);
		 $reqData['person_id'] = $person_id;
		 $reqData['total_discount'] = $allDiscount;
		 $reqData['total_amount'] = $afterDiscAmnt;
		 $reqData['dues_amount'] = $dues_amount;
		 $reqData['sales_date'] = $sales_date; //date('Y-m-d');
		 $reqData['branch_id'] = $branch_id;
		 $reqData['sales_type'] = '2';
		 $reqData['customer_id'] = $customer_id;
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		if($res['newid']){
		$sale_pay_id = $res['newid'];
			
			$dt = substr(date('Y'),0,4);
		 	$tracking = $this->gen_rand(4);
			$invoice = $branch_id.$dt.$sale_pay_id.$tracking;
		 	 
			  $selectItem = "UPDATE	inv_item_sales SET sale_pay_id =$sale_pay_id, invoice='$invoice', 
			  totaldiscount='$allDiscount', customer_id='$customer_id', pay_type='$pay_type', sales_date='$sales_date'
			  WHERE sales_status ='Pending' and branch_id=$branch_id and sales_type=2";//exit();
	  			$resItem = mysql_query($selectItem);
				if($resItem){
				
				$sqlDisc="select count(sale_pay_id) as TotalSlspayid from inv_item_sales 
				where sale_pay_id=$sale_pay_id and branch_id=$branch_id and sales_type=2";
				$resDisc = mysql_query($sqlDisc);
				$rowDisc = mysql_fetch_array($resDisc);
				$TotalSlspayid=$rowDisc['TotalSlspayid'];
				$indvDisc=($allDiscount/$TotalSlspayid);
					
				  $selectItem2 = "UPDATE inv_item_sales SET sales_status ='Not Pending', totaldiscount='$indvDisc' 
				  WHERE sale_pay_id ='$sale_pay_id' and branch_id=$branch_id and sales_type=2";//exit();
				  $resItem2 = mysql_query($selectItem2);
				  
					  $wholeAcc = "INSERT INTO whole_saler_acc(customer_id,dr_amount, cr_amount, paid_date, invoice,branch_id,pay_type,person_id)
					  VALUES('$customer_id','$total_amount', '$paid_amount', '$sales_date', '$invoice','$branch_id','$pay_type','$person_id');";//exit();
					  mysql_query($wholeAcc);
				  
				  header('location:?app=inv_item_sales_whole&cmd=ajaxInvoicePrintViewSkin&invoice='.$invoice.'');
				 }

		}
 
		
  }//eof
 
 
 function InvoicePrintViewSkin(){
 $branch_id = getFromSession('branch_id');
 $invoice=getRequest('invoice');

  $sql="SELECT
	sales_date,
	person_name,
	store_name,
	address,
	mobile
FROM
	".INV_ITEM_SALES_TBL." s inner join hrm_person p on p.person_id=s.person_id 
	inner join customer_info c on c.customer_id=s.customer_id
	where invoice='$invoice' and s.branch_id=$branch_id";
$res= mysql_query($sql);
$row = mysql_fetch_array($res);
$sales_date=$row['sales_date'];
$memo_no=$row['memo_no'];
$person_name=$row['person_name'];
$address=$row['address'];
$mobile=$row['mobile'];
$store_name=$row['store_name'];

 $printView=$this->InvoicePrintView($invoice);
 require_once(SALES_INVOICE_PRINT_VIEW_WHOLE); 
} //EOF


 function InvoicePrintView($invoice){
  $branch_id = getFromSession('branch_id');
 
 $sql="SELECT
	item_qnt,
	salesprice,
	sales_date,
	discount,
	total_amount,
	invoice,
	sale_pay_id,
	i.sub_item_category_id,
	sub_item_category_name,
	main_item_category_name

FROM
	".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id 
	inner join inv_item_category_sub su on su.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=i.main_item_category_id
	where invoice='$invoice' and s.branch_id=$branch_id and sales_type=2";
			
		$res= mysql_query($sql);
	      $type = '<table  width="700" cellpadding="5"  cellspacing="5" style="border-collapse:collapse">
	            <tr>
	             <td nowrap=nowrap align=left style="border:1px solid #000000;font-size:18px;color:#000000"><strong>SL</strong></td>
	             <td nowrap=nowrap align=left style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Category</strong></td>
	             <td nowrap=nowrap align=left style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Model</strong></td>
	             <td nowrap=nowrap align=right style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Price</strong></td>
				 <td nowrap=nowrap align=right widtd="20" style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Qnt</strong>.</td>
<!--				 <td nowrap=nowrap align=right widtd="20" style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Disc</strong>.</td>
-->				 <td nowrap=nowrap align=right style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Total</strong></td>
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){
		
		$sale_pay_id= $row['sale_pay_id'];
			
			$type .='<tr >	
			<td nowrap=nowrap style="border:1px solid #000000;">'.$i.'</td>
			<td nowrap=nowrap style="border:1px solid #000000;">'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap style="border:1px solid #000000;">'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap align=right style="border:1px solid #000000;">'.$row['salesprice'].'</td>
			<td nowrap=nowrap align=right style="border:1px solid #000000;">'.$row['item_qnt'].'</td>
<!--			<td nowrap=nowrap align=right style="border:1px solid #000000;">'.$row['discount'].'</td>
-->			<td nowrap=nowrap align=right style="border:1px solid #000000;">'.number_format($row['total_amount'],2).'</td>
			</tr>';
			
			$i++;
		}
		
		$sql2="SELECT pay_type,total_amount,paid_amount,dues_amount,total_discount FROM inv_item_sales_payment where sale_pay_id='$sale_pay_id'";
		$res2= mysql_query($sql2);
		$row2 = mysql_fetch_array($res2);
		$pay_type=$row2['pay_type'];		
		$total_amount=$row2['total_amount'];		
		$dues_amount=$row2['dues_amount'];		
		$paid_amount=$row2['paid_amount'];		
		$total_discount=$row2['total_discount'];		
			
			$type .='<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px;"><strong>Total Amount :</strong></td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.($total_amount+$total_discount).'</strong></td>
		    </tr>
			<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px;"><strong>Total Discount:</strong> </td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.$total_discount.'</strong></td>
		    </tr>
			<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px"><strong>Total Payable: </strong></td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.($total_amount).'</strong></td>
		    </tr>
			<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px"><strong>Paid Amount :</strong></td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.$paid_amount.'</strong></td>
		    </tr>
			<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px"><strong>Pay Type : </strong></td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.$pay_type.'</strong></td>
		    </tr>
			<tr height="30">
			  <td nowrap=nowrap align=right colspan="5" style="font-size:16px"><strong>Dues Amount : </strong></td>
			  <td nowrap=nowrap align=right style="font-size:16px;border:1px solid #000000;"><strong>'.$dues_amount.'</strong></td>
		    </tr>';
		
		$type .= '</table>';
		return $type;

 
}


/* function InvoicePrintView($invoice){
  
 $sql="SELECT
	itemcode,
	item_name,
	item_qnt,
	salesprice,
	sales_date,
	discount,
	total_amount,
	invoice,
	sale_pay_id
FROM
	".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id where invoice='$invoice'";
			
		$res= mysql_query($sql);
	      $type = '<table  width="500" cellpadding="0"  cellspacing=0>
	            <tr>
				 <td nowrap=nowrap align=left widtd="90" style="border-bottom:1px dotted #000000;"><strong>Item #</strong></td>
	             <td nowrap=nowrap align=left style="border-bottom:1px dotted #000000;"><strong>Item</strong></td>
	             <td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;"><strong>Price</strong></td>
				 <td nowrap=nowrap align=right widtd="20" style="border-bottom:1px dotted #000000;"><strong>Qnt.</strong></td>
				 <td nowrap=nowrap align=right widtd="20" style="border-bottom:1px dotted #000000;"><strong>Discount</strong></td>
				 <td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;"><strong>Total</strong></td>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		
		$sale_pay_id= $row['sale_pay_id'];
			
			$type .='<tr >	
			<td nowrap=nowrap style="border-bottom:1px dotted #000000;">'.$row['itemcode'].'</td>
			<td nowrap=nowrap style="border-bottom:1px dotted #000000;">'.$row['item_name'].'</td>
			<td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;">'.$row['salesprice'].'</td>
			<td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;">'.$row['item_qnt'].'</td>
			<td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;">'.$row['discount'].'</td>
			<td nowrap=nowrap align=right style="border-bottom:1px dotted #000000;">'.number_format($row['total_amount'],2).'</td>
			</tr>';
		}
		$sql2="SELECT pay_type,total_amount,paid_amount,dues_amount FROM inv_item_sales_payment where sale_pay_id='$sale_pay_id'";
		$res2= mysql_query($sql2);
		$row2 = mysql_fetch_array($res2);
		$pay_type=$row2['pay_type'];		
		$total_amount=$row2['total_amount'];		
		$dues_amount=$row2['dues_amount'];		
		$paid_amount=$row2['paid_amount'];		
			
			$type .='<tr>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >Total Amount :</td>
			  <td nowrap=nowrap align=right >'.$total_amount.'</td>
		    </tr>
			<tr >
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >Total Payable: </td>
			  <td nowrap=nowrap align=right >'.$total_amount.'</td>
		    </tr>
			<tr >
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >Paid Amount :</td>
			  <td nowrap=nowrap align=right >'.$paid_amount.'</td>
		    </tr>
			<tr >
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >Pay Type : </td>
			  <td nowrap=nowrap align=right >'.$pay_type.'</td>
		    </tr>
			<tr>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >&nbsp;</td>
			  <td nowrap=nowrap align=right >Dues Amount : </td>
			  <td nowrap=nowrap align=right >'.$dues_amount.'</td>
		    </tr>';
		
		$type .= '</table>';
		return $type;

 
}
*/
 
  
  function gen_rand($digits){
$number = "";
	for($i = 1; $i<=$digits; $i++)
	{
		$number.=mt_rand(0,9);
	}
	return $number;
	}


function SalesWholeSaller($ele_id, $ele_lbl_id){ 
	     
		 $sql="SELECT customer_id,mobile, store_name,name from customer_info where customer_type='2'
		 and branch_id=".getFromSession('branch_id')." ORDER BY name ASC";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=300 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addSalsManId('".$row['customer_id']."','".$ele_id."','".$row['name']."','".$ele_lbl_id."');
		hideElement('slsManLookUp');\" style=\"cursor:pointer\">
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['name']."<br>".$row['mobile']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }

function SelectSalesMan(){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT person_name, e.person_id FROM hrm_employee e inner join hrm_person p on p.person_id=e.person_id 
		where e.branch_id=$branch_id";
	    $result = mysql_query($sql);
		$rad='<label style="font-weight:normal">';
			while($row = mysql_fetch_array($result)) {
	$rad.='<input name="person_id" type="radio" value="'.$row['person_id'].'" />'.$row['person_name'].'<br>';	
		}
		$rad.='</label>';
		return $rad;

}	

function SelectBankList(){ 
		$sql="SELECT bankid, bank_name, account_no FROM ".BANK_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $department_select = "<select name='bankid' size='1' id='bankid' style='width:120px'>";
		$department_select .= "<option value=''>Select Bank</option>"; 
			while($row = mysql_fetch_array($result)) {
			$department_select .= "<option value='".$row['bankid']."'>".$row['bank_name']."&nbsp;:&nbsp;".$row['account_no']."</option>";	
		}
		$department_select .= "</select>";
		return $department_select;
}	


function ItmCategory4Model($cate = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT i.main_item_category_id, main_item_category_name FROM inv_iteminfo i inner join inv_item_category_main m 
		on m.main_item_category_id=i.main_item_category_id where branch_id=$branch_id group by i. main_item_category_id ORDER BY i. main_item_category_id";
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


function SelectModel(){ 
$main_item_category_id=getRequest('main_item_category_id');

		 $sql="SELECT i.sub_item_category_id,sub_item_category_name FROM inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
		  where i.main_item_category_id=$main_item_category_id group by i.sub_item_category_id order by sub_item_category_name";
	    $result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) {

	echo '<label style="font-weight:normal"><input name="sub_item_category_id" id="sub_item_category_id" type="radio" value="'.$row['sub_item_category_id'].'" />'.$row['sub_item_category_name'].'</label><br>';	
		}

}


} // End class
?>
