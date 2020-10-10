<?php
class inv_item_sales
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
$sql="SELECT
			sum(totaldiscount) AS totaldiscount,
			sum(total_amount) as total_amount,
			sum(item_qnt) as item_qnt
		FROM
			inv_item_sales where sales_status='Pending' and branch_id=$branch_id and sales_type=1";
			
		$res= mysql_query($sql);
		while($row = mysql_fetch_array($res)){
		$total_amount=$row['total_amount'];
		$totaldiscount=$row['totaldiscount'];
		$item_qnt=$row['item_qnt'];
		}
		
	$ItemIssue = $this->ItemIssueFetch();
/*	$SalseManList=$this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'), getRequest('ele_lbl_id1'));
	$SelectSalesMan=$this->SelectSalesMan();
	$SelectBankList = $this->SelectBankList();
*/	$curdate=dateInputFormatDMY(SelectCDate());
	//$ItmCategory4Model=$this->ItmCategory4Model();
	//$SelectModel=$this->SelectModel();

	
	require_once(CURRENT_APP_SKIN_FILE);
  }


 function CheckExistingItem($itemcode){
		  $sql = "SELECT itemcode from ".INV_ITEM_SALES_TBL." where itemcode = '$itemcode';";
		  $itemcode = mysql_num_rows(mysql_query($sql));
		  echo $itemcode.'######';	
	}
   // ===== End chkEmailExistence ========   
 

function SaveItemSales(){

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
                 header('location:?app=inv_item_sales&salesprice='.$sales_price.'&warranty='.$warranty.'&msg=Not Found This Item');
                 }else{
                    $rowItem = mysql_fetch_array($resItem);
                     
                     $item_id = $rowItem['item_id'];
                     $cost_price = $rowItem['cost_price'];
                     //$sales_price = $rowItem['sales_price'];
                     $description = $rowItem['description'];
                     $sub_item_category_id = $rowItem['sub_item_category_id'];
                     $item_qnt=1;
                     $sales_date=date('Y-m-d');     
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
                            header('location:?app=inv_item_sales&salesprice='.$sales_price.'&warranty='.$warranty.'&msg=Not available in stock !!');
                        }else{
                 
                     $sql2 = "INSERT INTO ".INV_ITEM_SALES_TBL."(item_id, sub_item_category_id, item_qnt, costprice, salesprice,sales_date,person_id,total_amount,createdby,branch_id,sales_type,warranty)
                    VALUES($item_id, '$sub_item_category_id', '$item_qnt', '$cost_price', '$sales_price', '$sales_date', '$person_id', '$sales_price','$person_id','$branch_id','1','$warranty')"; //exit();
                    $res = mysql_query($sql2);
                        if($res){
                        header('location:?app=inv_item_sales&salesprice='.$sales_price.'&warranty='.$warranty.'&msg=Item Saved');
                        }else{
                        header('location:?app=inv_item_sales&salesprice='.$sales_price.'&warranty='.$warranty.'&msg=Not Saved');
                        }
                    }// End $Stock<=0
            }// end $itemCnt<=0
	}//eof

function EditItemSales(){
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
 
 if($Warranty){ $warranty =$Warranty; }else{ $warranty ='Not Applicable'; }
 
 
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
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id and sales_type='1' and sales_status ='Not Pending'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$sals_quantity = $row2c['sals_quantity'];
	   
		$Stock =(($rec_quantity)-($sals_quantity+$stock_out)); //exit();
		//======================== End =====================
		if($item_qnt<=$Stock){
					$sql = "UPDATE inv_item_sales SET warranty = '$warranty'
					WHERE sub_item_category_id = $sub_item_category_id and branch_id=$branch_id and sales_type='1' and sales_status='Pending'"; //exit();
							
				$res = mysql_query($sql);
					if($res)
					{
					header('location:?app=inv_item_sales&msg=Item Saved');
					}else{
					header('location:?app=inv_item_sales&msg=Not Saved');
					}

		}else{
			
			header('location:?app=inv_item_sales_emi&msg=Not available in stock !!');
 
			}// End $item_qnt>$Stock

}


function DeleteSalesItem(){
	$sales_id = getRequest('sales_id');
  	if($sales_id)  {
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_id = '$sales_id'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales&msg=Item Deleted');
		}else{
		header('location:?app=inv_item_sales&msg=Not Deleted');
		}
  	}	
}  

function CancelSalesItem(){
$branch_id = getFromSession('branch_id');
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_status = 'Pending' and branch_id=$branch_id and sales_type='1'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales&msg=Sales has been canceled');
		}else{
		header('location:?app=inv_item_sales&msg=Not canceled');
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
				sum(total_amount) as total_amount,
				sum(item_qnt) as item_qnt,
				item_size,
				i.sub_item_category_id,
				sub_item_category_name,
				main_item_category_name,
				warranty

			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id 
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				where sales_status='Pending' and s.branch_id=$branch_id and sales_type=1 group by i.sub_item_category_id";
			
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
				 <th nowrap=nowrap align=left>Warranty</th>
				 <th nowrap=nowrap align=left>Edit</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){

		$item_id=$row['item_id'];
		$sub_item_category_id=$row['sub_item_category_id'];
				
			$rs = '<label style="font-weight:normal">';	
			 $sql3="SELECT item_size FROM ".INV_ITEMINFO_TBL." 
			 where sub_item_category_id=$sub_item_category_id and item_id in(SELECT item_id FROM ".INV_ITEM_SALES_TBL."	where sales_status='Pending' and branch_id=$branch_id)";
			$res3= mysql_query($sql3);
			while($row3 = mysql_fetch_array($res3)){
			$itemSize=$row3['item_size'];
			$rs .=$row3['item_size'].'<br>';
		}
		$rs .= '</label>';

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<form action="?app=inv_item_sales&cmd=ajaxEditItemSales" method="post" name="itmsls">
			<div><tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" height="35">	
			<td nowrap=nowrap><a href="?app=inv_item_sales&cmd=DeleteSalesItem&sales_id='.$row['sales_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td onmouseover="javascript:showElement('.$sub_item_category_id.');" onmouseout="javascript:hideElement('.$sub_item_category_id.');">'.$row['sub_item_category_name'].'
			<div id="'.$sub_item_category_id.'" class="imgBox" style="visibility:hidden;">'.$rs.'</div></td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap><strong>'.$row['item_qnt'].'</strong></td>
<!--		<td nowrap=nowrap></td>-->		
			<td nowrap=nowrap><strong>'.$row['total_amount'].'</strong>
			<input type="hidden" name="sub_item_category_id" id="item_code" value="'.$row['sub_item_category_id'].'" size=5 class="textBox"/>
			<input type="hidden" name="discount" id="discount" value="'.$row['discount'].'" size=5 /></td>
			<td nowrap=nowrap>'.$row['warranty'].'</td>
			<td nowrap=nowrap><!--<input name="submit" type="submit" value="Edit" style="cursor:pointer" class="btnClass"/>--></td>
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
		 $reqData['sales_type'] = '1';
		 //$reqData['customer_id'] = $customer_id;
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		if($res['newid']){
		$sale_pay_id = $res['newid'];
			
/*			$dt = substr(date('Y'),0,4);
		 	$tracking = $this->gen_rand(4);
			$invoice = $branch_id.$dt.$sale_pay_id.$tracking;
*/		 	 
			  
			  
			 $MaxCod ="select max(invoice) as  MXinvoice from inv_item_sales "; //exit();
			$MaxresCod = mysql_query($MaxCod);
			$MaxrowCod = mysql_fetch_array($MaxresCod);
			$MXinvoice = $MaxrowCod['MXinvoice'];
			$invoice=($MXinvoice+1);//exit();

			$selectItem = "UPDATE	inv_item_sales SET sale_pay_id =$sale_pay_id, invoice='$invoice', 
			  totaldiscount='$allDiscount', customer_id='$customer_id', pay_type='$pay_type', sales_date='$sales_date'
			  WHERE sales_status ='Pending' and branch_id=$branch_id and sales_type=1";//exit();
	  			$resItem = mysql_query($selectItem);
				if($resItem){
				
				$sqlDisc="select count(sale_pay_id) as TotalSlspayid from inv_item_sales 
				where sale_pay_id=$sale_pay_id and branch_id=$branch_id and sales_type=1";
				$resDisc = mysql_query($sqlDisc);
				$rowDisc = mysql_fetch_array($resDisc);
				$TotalSlspayid=$rowDisc['TotalSlspayid'];
				$indvDisc=($allDiscount/$TotalSlspayid);
					
				  $selectItem2 = "UPDATE inv_item_sales SET sales_status ='Not Pending', totaldiscount='$indvDisc' 
				  WHERE sale_pay_id ='$sale_pay_id' and branch_id=$branch_id and sales_type=1";//exit();
				  $resItem2 = mysql_query($selectItem2);
				  
				  header('location:?app=inv_item_sales&cmd=ajaxInvoicePrintViewSkin&invoice='.$invoice.'');
				 }

		}
 
		
  }//eof
 
 
 function InvoicePrintViewSkin(){
 $branch_id = getFromSession('branch_id');
 $invoice=getRequest('invoice');

  $sql="SELECT	sale_pay_id,person_name FROM ".INV_ITEM_SALES_TBL." s inner join hrm_person p on p.person_id=s.person_id where invoice='$invoice' and branch_id=$branch_id";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$sale_pay_id=$row['sale_pay_id'];
	$person_name=$row['person_name'];

   $sql4="SELECT sales_date, sale_time FROM	".INV_ITEM_SALES_PAYMENT_TBL."  where sale_pay_id=$sale_pay_id";
	$res4= mysql_query($sql4);
	$row4 = mysql_fetch_array($res4);
	$sales_date=$row4['sales_date'];
	$sale_time=strtotime($row4['sale_time']);
	//$paid_amount=$row4['paid_amount'];
	

//$inword=$this->number_to_word($paid_amount);
 $printView=$this->InvoicePrintView($invoice);
 require_once(SALES_INVOICE_PRINT_VIEW); 
} //EOF


 function InvoicePrintView($invoice){
  $branch_id = getFromSession('branch_id');
 
 $sql="SELECT
	sum(item_qnt) as item_qnt,
	return_qnt,
	salesprice,
	sales_date,
	discount,
	total_amount,
	return_amount,
	invoice,
	item_size,
	sale_pay_id,
	s.sub_item_category_id,
	sub_item_category_name,
	main_item_category_name,
	warranty
	

FROM
	".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id 
	inner join inv_item_category_sub su on su.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=i.main_item_category_id
	where invoice='$invoice' and s.branch_id=$branch_id and sales_type=1 group by i.sub_item_category_id";
			
		$res= mysql_query($sql);
	      $type = '<table  width="700" cellpadding="0"  cellspacing="0" style="border-collapse:collapse">
	            <tr>
	             <td nowrap=nowrap align=left style="border:1px solid #000000;font-size:12px;color:#000000"><strong>SL</strong></td>
	             <td nowrap=nowrap align=left style="border:1px solid #000000;font-size:12px;color:#000000"><strong>Description</strong></td>
	             <td nowrap=nowrap align=center style="border:1px solid #000000;font-size:12px;color:#000000"><strong>Warranty</strong></td>
	             <td nowrap=nowrap align=center widtd="20" style="border:1px solid #000000;12px-size:18px;color:#000000"><strong>Qnt</strong>.</td>
	             <td nowrap=nowrap align=center style="border:1px solid #000000;font-size:12px;color:#000000"><strong>Unit Price</strong></td>
				 <!--				 <td nowrap=nowrap align=right widtd="20" style="border:1px solid #000000;font-size:18px;color:#000000"><strong>Disc</strong>.</td>
-->				 <td nowrap=nowrap align=right style="border:1px solid #000000;font-size:12px;color:#000000"><strong>Amount</strong></td>
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){
		
		$sale_pay_id= $row['sale_pay_id'];
		$sub_item_category_id= $row['sub_item_category_id'];
		$item_qnt= $row['item_qnt'];
		$invoice= $row['invoice'];
		$item_size= $row['item_size'];
		$warranty= $row['warranty'];
		$return_qnt= $row['return_qnt'];
		$total_amount= $row['total_amount'];
		$return_amount= $row['return_amount'];
		$qnt=($item_qnt-$return_qnt);
		$Amnt=($total_amount*$item_qnt);
			
		$rs = '<label style="font-weight:normal">';	
			$sql3="SELECT item_size FROM ".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id  where  s.sub_item_category_id=$sub_item_category_id and invoice='$invoice'";
			$res3= mysql_query($sql3);
			while($row3 = mysql_fetch_array($res3)){
			$itemSize=$row3['item_size'];
			$rs .=$row3['item_size'].', ';
		}
		$rs .= '</label>';
			
			$type .='<tr >	
			<td nowrap=nowrap style="border:1px solid #000000;font-size:12px" width="20" align=center>'.$i.'</td>
			<td style="border:1px solid #000000;font-size:12px">'.$row['sub_item_category_name'].'<br>'.$rs.'</td>
			<td nowrap=nowrap align=center style="border:1px solid #000000;font-size:12px" width="50">'.$row['warranty'].'</td>
			<td nowrap=nowrap align=center style="border:1px solid #000000;font-size:12px" width="10">'.$qnt.'</td>
			<td nowrap=nowrap align=center style="border:1px solid #000000;" width="50">'.$row['salesprice'].'</td>
			<!--			<td nowrap=nowrap align=right style="border:1px solid #000000;font-size:12p"">'.$row['discount'].'</td>
-->			<td nowrap=nowrap align=right style="border:1px solid #000000;font-size:12px" width="50">'.number_format($Amnt,2).'</td>
			</tr>';
			
			$i++;
		}
		
		$sql2="SELECT pay_type,total_amount,paid_amount,dues_amount,total_discount,ret_amount FROM inv_item_sales_payment where sale_pay_id='$sale_pay_id'";
		$res2= mysql_query($sql2);
		$row2 = mysql_fetch_array($res2);
		$pay_type2=$row2['pay_type'];		
		$total_amount2=$row2['total_amount'];		
		$ret_amount2=$row2['ret_amount'];		
		$dues_amount2=$row2['dues_amount'];		
		$paid_amount2=$row2['paid_amount'];		
		$total_discount2=$row2['total_discount'];
		$Amnt2=($total_amount2-$ret_amount2);		
			
			$type .='<tr>
			  <td align=right nowrap=nowrap style="border-left:1px solid #000000">&nbsp;</td>
			  <td colspan="3" rowspan="3" align=center nowrap=nowrap style="font-size:12px;"><img src="images/paid.png" height="60"  width="120" /></td>
			  <td align=right nowrap=nowrap style="font-size:12px;"><strong>Total Amount :</strong></td>
			  <td nowrap=nowrap align=right style="font-size:12px;border:1px solid #000000;"><strong>'.number_format(($total_amount2+$total_discount2)).'</strong></td>
		    </tr>
			<tr>
			  <td align=right nowrap=nowrap style="border-left:1px solid #000000">&nbsp;</td>
			  <td align=right nowrap=nowrap style="font-size:12px;"><strong>Total Discount:</strong></td>
			  <td nowrap=nowrap align=right style="font-size:12px;border:1px solid #000000;"><strong>'.$total_discount2.'</strong></td>
		    </tr>
			<tr >
			  <td align=right nowrap=nowrap style="border-left:1px solid #000000">&nbsp;</td>
			  <td align=right nowrap=nowrap style="font-size:12px"><strong>Total Payable: </strong></td>
			  <td nowrap=nowrap align=right style="font-size:12px;border:1px solid #000000;"><strong>'.number_format($Amnt2).'</strong></td>
		    </tr>
			<tr>
			  <td align=right nowrap=nowrap style="border-left:1px solid #000000;border-bottom:1px solid #000000">&nbsp;</td>
			  <td colspan="3" align=center nowrap=nowrap style="font-size:12px;border-bottom:1px solid #000000"> <b>IN WORD : '.strtoupper($this->number_to_word($paid_amount2)).' TAKA ONLY</b> </td>
			  <td align=right nowrap=nowrap style="font-size:12px;border-bottom:1px solid #000000"><strong>Paid Amount :</strong></td>
			  <td nowrap=nowrap align=right style="font-size:12px;border:1px solid #000000;border-bottom:1px solid #000000"><strong>'.number_format($paid_amount2).'</strong></td>
		    </tr>';
		
		$type .= '</table>
	      ';
		return $type;

 
}

function number_to_word( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );
   
    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );
       
        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
       
        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');
       
        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');
       
        $list3  = array('','thousand','million','billion','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');
       
        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );
       
        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';
           
            if( $tens < 20 )
            {
                $tens   = ( $tens ? ' ' . $list1[$tens] . ' ' : '' );
            }
            else
            {
                $tens   = ( int ) ( $tens / 10 );
                $tens   = ' ' . $list2[$tens] . ' ';
                $singles    = ( int ) ( $num_part % 10 );
                $singles    = ' ' . $list1[$singles] . ' ';
            }
            $words[]    = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
       
        $commas = count( $words );
       
        if( $commas > 1 )
        {
            $commas = $commas - 1;
        }
       
        $words  = implode( ', ' , $words );
       
        //Some Finishing Touch
        //Replacing multiples of spaces with one space
        $words  = trim( str_replace( ' ,' , ',' , trim( ucwords( $words ) ) ) , ', ' );
        if( $commas )
        {
            $words  = str_replace( ',' , ' and' , $words );
        }
       
        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}//EOF


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


function SalesManDpDw($ele_id, $ele_lbl_id, $ele_lbl_id1){ 
	     
	$search =getRequest('search');
		if($search){
				$sql="SELECT customer_id,mobile, name from customer_info where customer_type=1 and name LIKE '%".$search."%' ORDER BY name LIMIT 0, 30";
			}else{
				$sql="SELECT customer_id,mobile, name from customer_info where customer_type=1 ORDER BY name LIMIT 0, 30";			
			}
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=300 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$customer_id=$row['customer_id'];

	$sql2="SELECT sum(dr_amount-cr_amount) as Sabek_dues from whole_saler_acc where customer_id=$customer_id ";
	$res2= mysql_query($sql2);
	$row2=mysql_fetch_array($res2);
	$Sabek_dues=0;//$row2['Sabek_dues'];
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addSalsManId('".$row['customer_id']."','".$ele_id."','".$row['name']."','".$ele_lbl_id."','".$Sabek_dues."','".$ele_lbl_id1."');
		hideElement('slsManLookUp');\" style=\"cursor:pointer\">
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['name']."->".$Sabek_dues."<br>".$row['mobile']."</td>
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
	$rad.='<input name="person_id" type="radio" value="'.$row['person_id'].'" />'.$row['person_name'].'-'.$row['person_id'].'<br>';	
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
