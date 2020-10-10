<?php

class inv_report_generator
{ 
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxSalesManLoad'  		: echo $this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id')); break;
		 case 'DailySalesReport'  		: echo $this->DailySalsmanWiseReportSkin(); 		break;
		 case 'MonthlySalesReport'  	: echo $this->MonthlySalsmanWiseReportSkin(); 		break;
		 case 'DamageReport'  			: echo $this->DamageReportSkin(); 					break;
		 case 'DamageStockReportskin'  	: echo $this->DamageReportGeneratrorSkin(); 		break;
		 case 'skumovedReport'  		: echo $this->SKUMovedReportSkin(); 				break;
		 case 'MovedStockReportskin'  	: echo $this->MovedReportGeneratrorSkin(); 			break;
		 case 'WholeSalesReport'  		: echo $this->WholeSalesReportSkin(); 				break;
		 case 'WoleSalesReportskin'  	: echo $this->WoleSalesReportGeneratrorSkin(); 		break;
		 case 'ReceivedStockReport'  	: echo $this->ReceiveStockReportSkin();				break;
		 case 'currentStockReportSkin' 	: echo $this->currentStockReportSkin(); 			break;
		 case 'dateWiseStockReportSkin' : echo $this->DatewiseStockReportSkin(); 			break;
		 case 'SummaryReportSkinAziz' 	: echo $this->SummaryReportSkinAziz(); 				break;
		 case 'ShowSummaryReportAziz' 	: echo $this->ShowSummaryReportSkinAziz(); 			break;
		
		 case 'SummaryReportSkin' 		: echo $this->SummaryReportSkin(); 					break;
		 case 'ShowSummaryReport' 		: echo $this->ShowSummaryReportSkin(); 				break;
         case 'list'                  	: $this->getList();                       			break;
         default                      	: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
function getList(){
$SalseManList=$this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'));
 $slsman = $this->SelectSalesMan();
 
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
function DatewiseStockReportSkin(){
 $isfrom = formatDate4insert(getRequest('isfrom'));
$sql = "select DATE_SUB('$isfrom', INTERVAL 1 DAY) as clsDate";
$res= mysql_query($sql);
$row=mysql_fetch_array($res);
$clsDate=_date($row['clsDate']);
$sub_item_category_id=getRequest('sub_item_category_id');
	$DatewiseStockReport = $this->DateWiseStockReportFetch($isfrom,$sub_item_category_id);
	$cdate = SelectCDate();
	$catList=$this->SelectItmCategory($sub_item_category_id);
	 require_once(DATE_WISE_STOCK_REPORT); 
}

 function DateWiseStockReportFetch($isfrom, $sub_item_category_id=null){
	
 	$item_size=getRequest('item_size');
	$branch_id = getFromSession('branch_id');
	
	if($sub_item_category_id && !$item_size){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where i.branch_id=$branch_id AND i.sub_item_category_id=$sub_item_category_id 
				AND receive_date <= '$isfrom'  group by item_code order by i.sub_item_category_id";	
	}
	if($item_size && !$sub_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where i.branch_id=$branch_id AND item_size=$item_size 
				AND receive_date <='$isfrom'  group by item_code order by i.sub_item_category_id";
	}
	if($item_size && $sub_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where i.branch_id=$branch_id AND item_size=$item_size and i.sub_item_category_id=$sub_item_category_id
				AND receive_date <= '$isfrom'  group by item_code order by i.sub_item_category_id";
	}
	
if(!$item_size && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where i.branch_id=$branch_id AND receive_date <= '$isfrom' group by item_code order by i.sub_item_category_id";
		}
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr  bgcolor='#003333'>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">S/L</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Size</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Current Stock</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Cost(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Sales(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Sales</th>
	       </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$rec_quantity=($row['rec_quantity']);
	$item_code=$row['item_code'];
	
		echo $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
		 where itemcode ='$item_code' and branch_id=$branch_id and sales_date<='$isfrom'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$sals_quantity = $row2c['sals_quantity'];
		
		$sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
		where item_code ='$item_code' and branch_id=$branch_id";
		$resStk1 =mysql_query($sqlStk1);
		$rowStk1 = mysql_fetch_array($resStk1);
		$stock_out = $rowStk1['stock_out'];
		
		
		$tqnt1c =$rec_quantity-($sals_quantity+$stock_out);
		
		

	// ========Count unit Price ===========================
	$sqlUnitPrice="SELECT max(item_id) as mxitem_id FROM inv_iteminfo  WHERE item_code='$item_code' and branch_id=$branch_id";
	//echo $sql;
	$resUnitPrice= mysql_query($sqlUnitPrice);
	while($rowUnitPrice=mysql_fetch_array($resUnitPrice)){
	$mxitem_id = $rowUnitPrice['mxitem_id'];
	}

	$sqlUnitPrice2="SELECT cost_price as cost_price, sales_price as sales_price FROM inv_iteminfo WHERE item_id=$mxitem_id 
	and item_code='$item_code' and branch_id=$branch_id";
	//echo $sql;
	$resUnitPrice2= mysql_query($sqlUnitPrice2);
	while($rowUnitPrice2=mysql_fetch_array($resUnitPrice2)){
	$cost_price = $rowUnitPrice2['cost_price'];
	$sales_price = $rowUnitPrice2['sales_price'];
	}
	// ========End Count unit Price ===========================
	
	$totalValues=($cost_price*$tqnt1c);
	$totalValuesSls=($sales_price*$tqnt1c);

	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr height=22 class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"><a href=\"?app=stock_out&item_code=".$row['item_code']."\" style=\"text-decoration:none; cursor:pointer\">".$row['item_code']."</a></td>
<td nowrap=nowrap style=\"border:#000000 solid 1px;\"><a href='?app=inv_report_generator&cmd=dateWiseStockReportSkin&item_size=".$row['item_size']."&sub_item_category_id=".$row['sub_item_category_id']."'>".$row['item_size']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$tqnt1c."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($cost_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValues,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($sales_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValuesSls,2)."</td>
		</tr>";
		$i++;
		$subTotal1 = ($subTotal1+$totalValues);
		$subTotal2 = ($subTotal2+$tqnt1c);
		
		$subTotalSales = ($subTotalSales+$totalValuesSls);
	
	}//===================== End powder milk ===========================================
	

	$speci .= "<tr>
  <td colspan='5' align='right'><b>Total Qnt : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subTotal2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Cost : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotal1,2)." Taka</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Sales : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotalSales,2)." Taka</td>
  </tr>
  </table>";
	return $speci;

}//end fnc
   
function currentStockReportSkin(){
	$sub_item_category_id=getRequest('sub_item_category_id');
	$currentStockReport = $this->CurrentStockReportFetch($sub_item_category_id);
	$cdate = SelectCDate();
	$catList=$this->SelectItmCategory($sub_item_category_id);
	 require_once(CURRENT_STOCK_REPORT); 
}  
  
 function CurrentStockReportFetch($sub_item_category_id=null){
 	$item_size=getRequest('item_size');
	$branch_id = getFromSession('branch_id');
	
	if($sub_item_category_id && !$item_size){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where branch_id=$branch_id and i.sub_item_category_id=$sub_item_category_id 
				group by item_code order by i.sub_item_category_id";	
	}
	if($item_size && !$sub_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where item_size='$item_size' and branch_id=$branch_id 
				group by item_code order by i.sub_item_category_id";
		}
	if($item_size && $sub_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where item_size='$item_size' and branch_id=$branch_id and i.sub_item_category_id=$sub_item_category_id 
				group by item_code order by i.sub_item_category_id";
		}
	if(!$item_size && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where branch_id=$branch_id group by item_code order by i.sub_item_category_id";
		}
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr  bgcolor='#003333'>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">S/L</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Size</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Current Stock</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Cost(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Sales(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Sales</th>
	       </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$rec_quantity=($row['rec_quantity']);
	$item_code=$row['item_code'];
	
		 $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
		 where itemcode ='$item_code' and branch_id=$branch_id ";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$sals_quantity = $row2c['sals_quantity'];
		
		$sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
		where item_code ='$item_code' and branch_id=$branch_id";
		$resStk1 =mysql_query($sqlStk1);
		$rowStk1 = mysql_fetch_array($resStk1);
		$stock_out = $rowStk1['stock_out'];
	
		
		$tqnt1c =$rec_quantity-($sals_quantity+$stock_out);
		
		

	// ========Count unit Price ===========================
	$sqlUnitPrice="SELECT max(item_id) as mxitem_id FROM inv_iteminfo  WHERE item_code='$item_code' and branch_id=$branch_id";
	//echo $sql;
	$resUnitPrice= mysql_query($sqlUnitPrice);
	while($rowUnitPrice=mysql_fetch_array($resUnitPrice)){
	$mxitem_id = $rowUnitPrice['mxitem_id'];
	}

	$sqlUnitPrice2="SELECT cost_price as cost_price, sales_price as sales_price FROM inv_iteminfo WHERE item_id=$mxitem_id 
	and item_code='$item_code' and branch_id=$branch_id";
	//echo $sql;
	$resUnitPrice2= mysql_query($sqlUnitPrice2);
	while($rowUnitPrice2=mysql_fetch_array($resUnitPrice2)){
	$cost_price = $rowUnitPrice2['cost_price'];
	$sales_price = $rowUnitPrice2['sales_price'];
	}
	// ========End Count unit Price ===========================
	
	$totalValues=($cost_price*$tqnt1c);
	$totalValuesSls=($sales_price*$tqnt1c);

	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr height=22 class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">
		<a href=\"?app=item_return&tqnt1c=$tqnt1c&item_code=".$row['item_code']."&branch_id=".$branch_id."\" 
		style=\"text-decoration:none; cursor:pointer\">".$row['item_code']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"><a href='?app=inv_report_generator&cmd=currentStockReportSkin&item_size=".$row['item_size']."&sub_item_category_id=".$row['sub_item_category_id']."'>".$row['item_size']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$tqnt1c."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($cost_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValues,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($sales_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValuesSls,2)."</td>
		</tr>";
		$i++;
		$subTotal1 = ($subTotal1+$totalValues);
		$subTotal2 = ($subTotal2+$tqnt1c);
		
		$subTotalSales = ($subTotalSales+$totalValuesSls);
	
	}//===================== End powder milk ===========================================
	
$speci .= "<tr>
  <td colspan='5' align='right'><b>Total Qnt : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subTotal2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Cost : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotal1,2)." Taka</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Sales : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotalSales,2)." Taka</td>
  </tr>
  </table>";
	return $speci;

}//end fnc



function DailySalsmanWiseReportSkin(){
 $slfrom = formatDate4insert(getRequest('slfrom'));
 $person_id = getRequest('person_id');
 $sql = "SELECT person_name from hrm_person where person_id='$person_id'";
 $res=mysql_query($sql);
 $row=mysql_fetch_array($res);
 $person_name=$row['person_name'];
 
 $dailySals = $this->dailySalsmanWiseReport($slfrom, $person_id);
  require_once(SALESMAN_WISE_DAILY_REPORT);
}


 function dailySalsmanWiseReport($slfrom, $person_id){
 $branch_id = getFromSession('branch_id');
 

	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date='$slfrom' 
	and person_id='$person_id' and s.branch_id=$branch_id and sales_type='1'";
	

	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Invoice</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $NetSales=(($total_amount)-($return_amount+$NwDisc));
	 //$NetSales=($total_amount-$NwDisc);


		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NetSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
		
	$totalValue = $totalValue+$NetSales;
	$totalcostprice = $totalcostprice+$totalCost;
	//$totalsalesprice = $totalsalesprice+$row['salesprice'];
	$netDisc = $netDisc+$NwDisc;
	

	$i++;
	}
	
	$profit=($totalValue-$totalcostprice);
	
	$inPercnt=($profit/$totalcostprice)*100;	
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalValue."</strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit."(".$inPercnt.") %</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>
		</table>";
	return $speci;

}//end fnc




function MonthlySalsmanWiseReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 
 $person_id = getRequest('person_id');
 $sql = "SELECT person_name from hrm_person where person_id='$person_id'";
 $res=mysql_query($sql);
 $row=mysql_fetch_array($res);
 
 $person_name=$row['person_name'];
 

 $monthlySals = $this->monthlySalsmanWiseReport($slfrom2, $slto2, $person_id);
  require_once(SALESMAN_WISE_MONTHLY_REPORT);
}


 function monthlySalsmanWiseReport($slfrom2, $slto2, $person_id){
 $branch_id = getFromSession('branch_id');


	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and person_id='$person_id' and s.branch_id=$branch_id  and sales_type='1'";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Invoice</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=(($row['item_qnt'])-($row['return_qnt']));
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];
	
	

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $netSales=(($total_amount)-($return_amount+$NwDisc));
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
		
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalsalesprice = $totalsalesprice+$row['salesprice'];
	$netDisc = $netDisc+$NwDisc;
	

	$i++;
	}
	
	$profit=($totalValue-$totalcostprice);
	
	$inPercnt=($profit/$totalcostprice)*100;	
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalValue."</strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		</tr>
<!--	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit."(".$inPercnt.") %</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>-->
		</table>";
	return $speci;

}//end fnc

function DamageReportGeneratrorSkin(){
 $catList=$this->SelectItmCategory();
require_once(DAMAGE_REPORT_GENERATOR_SKIN);
}

function DamageReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
  $slto2 = formatDate4insert(getRequest('slto2'));//exit();
 $Damage = $this->SKUDamageReport($slfrom2, $slto2);
 require_once(DAMAGE_STOCK_REPORT);
}
 
 function SKUDamageReport($slfrom2, $slto2){
 
 $branch_id = getFromSession('branch_id');


	 $sql = "SELECT
	return_info_id,
	item_code,
	stock_out,
	return_purpose,
	damage_date,
	branch_name,
	item_name,
	cost_price
FROM
	return_info r  inner join branch b on b.branch_id=r.branch_id
	where damage_date between '$slfrom2' and '$slto2' and r.branch_id=$branch_id ";

	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=1100 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Purpose</th>
       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		
		$stock_out=$row['stock_out'];
		$cost_price=$row['cost_price'];
		//$sales_price=$row['sales_price'];
		
		$TotalCost=($stock_out*$cost_price);
		//$TotalSales=($quantity*$sales_price);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_code']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['stock_out']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['return_purpose']."</td>
		</tr>";
		
	$totalcostprice = $totalcostprice+$TotalCost;
	//$totalsalesprice = $totalsalesprice+$TotalSales;
	$totalQnt = $totalQnt+$row['stock_out'];
	
	$i++;
	}
	
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap></td>
		<td align=right></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total Qnt. : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Cost : ".number_format($totalcostprice,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap>&nbsp;</td>
	</tr>
		</table>";
	return $speci;
}//end fnc


function MovedReportGeneratrorSkin(){
$sub_item_category_id=getRequest('sub_item_category_id');
$catList=$this->SelectItmCategory($sub_item_category_id);

require_once(MOVED_REPORT_GENERATOR_SKIN);
}

function SKUMovedReportSkin(){
$sub_item_category_id=getRequest('sub_item_category_id');
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $monthlySals = $this->SKUMovedReport($slfrom2, $slto2,$sub_item_category_id);

  require_once(MOVED_STOCK_REPORT);
}



 function SKUMovedReport($slfrom2, $slto2,$sub_item_category_id=null){
 $branch_id = getFromSession('branch_id');
 if($sub_item_category_id ){
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	person_name,
	invoice,
	return_amount,
	return_qnt
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id  and sales_type='1'";
}else{
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	person_name,
	invoice,
	return_amount,
	return_qnt
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id  and sales_type='1'";

}
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">SalesMan</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Invoice</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $netSales=(($total_amount)-($return_amount+$NwDisc));
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['person_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$NwDisc;

	$TotalSalesValue = $TotalSalesValue+$row['total_amount'];
	
	
	
	$i++;
	}
	
	$TotalSales=($totalValue);
	$profit=($TotalSales-$totalcostprice);
	$inPercnt=($profit/$totalcostprice)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSalesValue."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales."</strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td
		><td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit." (".($inPercnt)." %)</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>";

 if($sub_item_category_id ){
	 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt,
	store_name
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join customer_info p on p.customer_id=s.customer_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id  and sales_type='2'";
}else{
	 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt,
	store_name
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join customer_info p on p.customer_id=s.customer_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id  and sales_type='2'";

}
	//echo $sql;
	$res2= mysql_query($sql2);
	$speci .= "<tr height=25 >
	  <td colspan=15 align=left><strong>Whole Sales</strong></td>
	  </tr><table width=1000 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Disc(%)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Disc</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Customer</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Invoice</th>
	       </tr>";
                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$costprice2=$row2['costprice'];
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);
	$salesprice2=$row2['salesprice'];

	$return_amount2=$row2['return_amount'];
	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

	$sql22="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId2";
	 $res22= mysql_query($sql22);
	 $row22 = mysql_fetch_array($res22);
	 $totsale_pay_id22=$row22['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id22);
	 
	 $netSales2=(($total_amount2)-($return_amount2+$NwDisc2));
	 $totalSales2=($salesprice2*$item_qnt2);
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalSales2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['discount_percent']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row2['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row2['invoice']."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$NwDisc2;
	
	
	
	$i2++;
	}
	
	$TotalSales2=($totalValue2);
	$profit2=($TotalSales2-$totalcostprice2);
	$inPercnt2=($profit2/$totalcostprice2)*100;
	
	

//========================= Dues Receive ========================
	 $sql3 = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and w.branch_id=$branch_id and paid_date between '$slfrom2' and '$slto2'";

	//echo $sql;
	$res3= mysql_query($sql3);

//==============================================================
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=5> <strong>Total Qnt : ".$totalQnt2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales2."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap ></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=12> <strong>Total Profit : ".$profit2." (".$inPercnt2.")%</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap ></td>
		</tr>";
		
 $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment where sales_date between '$slfrom2' and '$slto2' 
				and branch_id=$branch_id  and sales_type='2'";
	$res4= mysql_query($sql4);
                 
	$row4=mysql_fetch_array($res4);	
		
		$totalDuesWhole4=$row4['dues_amount'];
		$netWholeSales4=($TotalSales2-$totalDuesWhole4);
		

		
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10> <strong>Total Received Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netWholeSales4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10> <strong>Dues : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalDuesWhole4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>
		<tr><td colspan='15' align='center'></td></tr>
		<tr><td colspan='15' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' ><b>Dues Recived</b></td></tr>";

                 $i3=1;        $rowcolor=0;
	while($row3=mysql_fetch_array($res3)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
		
$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap colspan=12 style=\"border:#000000 solid 1px\" align=right>".number_format($row3['cr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row3['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2>"._date($row3['paid_date'])."</td>
		</tr>";
	$totalduesRec3=$totalduesRec3+$row3['cr_amount'];
	
	}
		
	$netDues4=($totalDuesWhole4-$totalduesRec3);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=11 style=\"border:#000000 solid 1px\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($totalduesRec3,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=11 style=\"border:#000000 solid 1px\" align=right><strong>Net Dues : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($netDues4,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		</table>";
	return $speci;

}//end fnc


//==================== Whole Sales ====================
function WoleSalesReportGeneratrorSkin(){
$sub_item_category_id=getRequest('sub_item_category_id');
$catList=$this->SelectItmCategory($sub_item_category_id);

require_once(WHOLE_SALES_REPORT_GENERATOR_SKIN);
}

function WholeSalesReportSkin(){
$sub_item_category_id=getRequest('sub_item_category_id');
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $WholeSales = $this->WholeSalesReport($slfrom2, $slto2,$sub_item_category_id);

  require_once(WHOLE_SALES_REPORT_SKIN);
}



 function WholeSalesReport($slfrom2, $slto2,$sub_item_category_id=null){
 $branch_id = getFromSession('branch_id');
 if($sub_item_category_id ){
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt,
	store_name
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join customer_info p on p.customer_id=s.customer_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id  and sales_type='2'";
}else{
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	item_qnt,
	sale_pay_id,
	costprice,
	salesprice,
	sales_date,
	discount,
	totaldiscount,
	discount_percent,
	total_amount,
	sales_status,
	invoice,
	return_amount,
	return_qnt,
	store_name
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	inner join customer_info p on p.customer_id=s.customer_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id  and sales_type='2'";

}
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=1000 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Disc(%)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Disc</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Customer</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Invoice</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);
	$salesprice=$row['salesprice'];

	$return_amount=$row['return_amount'];
	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $netSales=(($total_amount)-($return_amount+$NwDisc));
	 $totalSales=($salesprice*$item_qnt);
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalSales."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['discount_percent']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$NwDisc;
	
	
	
	$i++;
	}
	
	$TotalSales=($totalValue);
	$profit=($TotalSales-$totalcostprice);
	$inPercnt=($profit/$totalcostprice)*100;
	
	

//========================= Dues Receive ========================
	 $sql2 = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and w.branch_id=$branch_id and paid_date between '$slfrom2' and '$slto2'";

	//echo $sql;
	$res2= mysql_query($sql2);

//==============================================================
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=5> <strong>Total Qnt : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap ></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=12> <strong>Total Profit : ".$profit." (".$inPercnt.")%</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap ></td>
		</tr>";
 $sql3 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment where sales_date between '$slfrom2' and '$slto2' 
				and branch_id=$branch_id  and sales_type='2'";
	$res3= mysql_query($sql3);

                 
	$row3=mysql_fetch_array($res3);	
		
		$totalDuesWhole=$row3['dues_amount'];
		$netWholeSales=($TotalSales-$totalDuesWhole);
		

	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10> <strong>Total Received Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netWholeSales,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";

		$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10> <strong>Dues : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalDuesWhole,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>
		<tr><td colspan='15' align='center'></td></tr>
		<tr><td colspan='15' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' ><b>Dues Recived</b></td></tr>";

                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
		
$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap colspan=12 style=\"border:#000000 solid 1px\" align=right>".number_format($row2['cr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2>"._date($row2['paid_date'])."</td>
		</tr>";
	$totalduesRec=$totalduesRec+$row2['cr_amount'];
	
	}
		
	$netDues=($totalDuesWhole-$totalduesRec);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=11 style=\"border:#000000 solid 1px\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($totalduesRec,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=11 style=\"border:#000000 solid 1px\" align=right><strong>Net Dues : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($netDues,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		</table>";
	return $speci;

}//end fnc


//=====================================================
function ReceiveStockReportSkin(){
 $rsfrom = formatDate4insert(getRequest('rsfrom'));
 $rsto = formatDate4insert(getRequest('rsto'));
 //$challan_no = getRequest('challan_no');
 $sub_item_category_id=getRequest('sub_item_category_id');

 $catList=$this->SelectItmCategory($sub_item_category_id);

 $ReceivedStock = $this->ReceivedStockReport($rsfrom, $rsto,$sub_item_category_id);
  require_once(RECEIVED_STOCK_REPORT);
}


 function ReceivedStockReport($rsfrom, $rsto, $sub_item_category_id=null){

$branch_id = getFromSession('branch_id');	
if($sub_item_category_id){
	 $sql = "SELECT
	item_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	sales_price,
	item_code,
	receive_date,
	sum(quantity) as quantity
FROM
	inv_iteminfo i inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where receive_date between '$rsfrom' and '$rsto' and i.sub_item_category_id=$sub_item_category_id and i.branch_id=$branch_id  
	group by item_code order by receive_date DESC";

}else{	
 
	 $sql = "SELECT
	item_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	sales_price,
	item_code,
	receive_date,
	sum(quantity) as quantity
FROM
	inv_iteminfo i inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where receive_date between '$rsfrom' and '$rsto' and i.branch_id=$branch_id 
	group by item_code order by receive_date DESC";
	}
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=1100 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		
		$quantity=$row['quantity'];
		$cost_price=$row['cost_price'];
		$sales_price=$row['sales_price'];
		
		$TotalCost=($quantity*$cost_price);
		$TotalSales=($quantity*$sales_price);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_code']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['quantity']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['sales_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalSales."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>"._date($row['receive_date'])."</td>
		</tr>";
		
	$totalcostprice = $totalcostprice+$TotalCost;
	$totalsalesprice = $totalsalesprice+$TotalSales;
	$totalQnt = $totalQnt+$row['quantity'];
	
	$i++;
	}
	
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap></td>
		<td nowrap=nowrap></td>
		<td align=right></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total Qnt. : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Cost : ".number_format($totalcostprice,2)."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Values : ".number_format($totalsalesprice,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		</tr>
		</table>";
	return $speci;
}
//================================ Summary Report for Aziz Branch===========================
function SummaryReportSkinAziz(){
require_once(SUMMARY_REPORT_GENERATOR_AZIZ_SKIN);
}

function ShowSummaryReportSkinAziz(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $summary = $this->ShowSummaryReportAziz($slfrom2, $slto2);

  require_once(SUMMARY_REPORT_SKIN);
}


 function ShowSummaryReportAziz($slfrom2, $slto2){
 $branch_id = getFromSession('branch_id');
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sale_pay_id,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	discount,
	sum(totaldiscount) as totaldiscount,
	sum(discount_percent) as discount_percent,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and sales_type=1 group by itemcode";

	//echo $sql;
	$res= mysql_query($sql);



	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
	       </tr>
		   <tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Retail Sales</b></td></tr>";

                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=$row['item_qnt']-$row['return_qnt'];
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $netSales=(($total_amount)-($return_amount+$NwDisc));
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$NwDisc;
	
	
	
	$i++;
	}
	
	$TotalSales=($totalValue);
	$profit=($TotalSales-$totalcostprice);
	$inPercnt=($profit/$totalcostprice)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalSales,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=11> <strong>Total Profit : ".number_format($profit,2)." (".$inPercnt.")%</strong></td>
		</tr>

	  <tr><td colspan='11' align='center'>&nbsp;</td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Whole Sales</b></td></tr>";
//================================== For Whole Sale ==============================================================
	 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sale_pay_id,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	discount,
	sum(totaldiscount) as totaldiscount,
	sum(discount_percent) as discount_percent,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and sales_type=2 group by itemcode";

	//echo $sql;
	$res2= mysql_query($sql2);

    $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$costprice2=$row2['costprice'];
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);
	$salesprice2=$row2['salesprice'];

	$return_amount2=$row2['return_amount'];
	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

	$sql22="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId2";
	 $res22= mysql_query($sql22);
	 $row22 = mysql_fetch_array($res22);
	 $totsale_pay_id22=$row22['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id22);
	 
	 $netSales2=(($total_amount2)-($return_amount2+$NwDisc2));
	 $totalSales2=($salesprice2*$item_qnt2);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalSales2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$NwDisc2;
	
	$i2++;
	}
	
	$TotalSales2=($totalValue2);
	$profit2=($TotalSales2-$totalcostprice2);
	$inPercnt2=($profit2/$totalcostprice2)*100;
	
	$sqlDue = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
	where sales_type=2 and sales_date between '$slfrom2' and '$slto2' and branch_id=$branch_id ";
	$resDue= mysql_query($sqlDue);
	while($rowDue=mysql_fetch_array($resDue)){
		$dues_amount=$rowDue['dues_amount'];
	}
	
	$netWholeSales=($totalValue2-$dues_amount);
	
	$TotalSalesAmnt=($netWholeSales+$TotalSales);
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalValue2,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=11> <strong>Total Profit : ".$profit2." (".$inPercnt2.")%</strong></td>
		</tr>
		<tr>
	  <td colspan='10' align='right'><strong>Whole Sales Received Amount : </strong></td>
	  <td  align='right'><strong>".number_format($netWholeSales,2)."</strong></td>
	  </tr>
	  <tr>
	  <td colspan='10' align='right'><strong> Whole Sales Dues Amount : </strong></td>
	  <td  align='right'><strong>".$dues_amount."</strong></td>
	  </tr>
	  
	  <tr>
	  <td colspan='11' align='left'><strong>Dues Received : </strong></td>
	  </tr>";
	  
	  //========================= Dues Receive ========================
	 $sqlDRes = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and w.branch_id=$branch_id and paid_date between '$slfrom2' and '$slto2'";

	//echo $sql;
	$resDRes= mysql_query($sqlDRes);
                // $i7=1;        
				$rowcolor=0;
	while($rowDRes=mysql_fetch_array($resDRes)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
		
$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px\">".$rowDRes['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($rowDRes['cr_amount'],2)."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2>"._date($rowDRes['paid_date'])."</td>
-->		</tr>";
	$totalduesRec=$totalduesRec+$rowDRes['cr_amount'];
	
	}
		
	//$netDues=($totalDuesWhole-$totalduesRec);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($totalduesRec,2)."</strong></td>
		</tr>
			  <tr>
	  <td colspan='10' align='right' style=\"border-top:#000000 solid 1px\"><strong>Total Sales(retail+whole) : </strong></td>
	  <td  align='right' style=\"border-top:#000000 solid 1px\"><strong>".number_format(($TotalSalesAmnt+$totalduesRec),2)."<hr></strong></td>
	  </tr>

		<tr><td colspan='11' align='center'>&nbsp;</td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Others Income</b></td></tr>";

//================================== For Others Income ==============================================================
	 $sqlInc = "SELECT
	account_name,
	sum(cr) as crAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where l.branch_id=$branch_id and cr!='0.00000' and expdate between '$slfrom2' and '$slto2' group by l.chart_id";

	//echo $sql;
	$resInc= mysql_query($sqlInc);

      $iInc=1;        $rowcolor=0;
	while($rowInc=mysql_fetch_array($resInc)){

	$account_nameInc=$rowInc['account_name'];
	$crAmountInc=$rowInc['crAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$iInc."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_nameInc."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($crAmountInc,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$crAmountInc;
	
	$iInc++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>
		<tr><td colspan='11' align='center'>&nbsp;</td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	 $sql5 = "SELECT
	account_name,
	sum(dr) as drAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where l.branch_id=$branch_id and dr!='0.00000' and expdate between '$slfrom2' and '$slto2' group by l.chart_id";

	//echo $sql;
	$res5= mysql_query($sql5);

      $i5=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$account_name=$row5['account_name'];
	$drAmount5=$row5['drAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($drAmount5,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount5;
	
	$i5++;
	}
	
	$TotalIncome=($TotalSalesAmnt+$totalIncome+$totalduesRec);
	//$TotalExpense=($totalExpense);
	$TotalProfite=($TotalIncome-$totalExpense);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Net Income : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalProfite,2)."</strong></td>
		</tr></table>";
	return $speci;

}//end fnc

//=========================================================================================

//================================ Summary Report for All Branch===========================
function SummaryReportSkin(){
require_once(SUMMARY_REPORT_GENERATOR_SKIN);
}

function ShowSummaryReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $summary = $this->ShowSummaryReport($slfrom2, $slto2);

  require_once(SUMMARY_REPORT_SKIN);
}


 function ShowSummaryReport($slfrom2, $slto2){
 $branch_id = getFromSession('branch_id');
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sale_pay_id,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	discount,
	sum(totaldiscount) as totaldiscount,
	sum(discount_percent) as discount_percent,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount,
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and sales_type=1 group by itemcode";

	//echo $sql;
	$res= mysql_query($sql);



	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
	       </tr>
		   <tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Retail Sales</b></td></tr>";

                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 $netSales=(($total_amount)-($return_amount+$NwDisc));
	// $netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$NwDisc;
	
	
	
	$i++;
	}
	
	$TotalSales=($totalValue);
	$profit=($TotalSales-$totalcostprice);
	$inPercnt=($profit/$totalcostprice)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales."</strong></td>
		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap><strong>Profite : </strong></td>
	  <td  align=right nowrap=nowrap style=\"border-left:#000000 solid 1px;border-right:#000000 solid 1px;\"><strong>".$profit."</strong></td>
	  </tr>
	  <tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Others Income</b></td></tr>";

//================================== For Others Income ==============================================================
	 $sqlInc = "SELECT
	account_name,
	sum(cr) as crAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where l.branch_id=$branch_id and cr!='0.00' group by l.chart_id";

	//echo $sql;
	$resInc= mysql_query($sqlInc);

      $iInc=1;        $rowcolor=0;
	while($rowInc=mysql_fetch_array($resInc)){

	$account_nameInc=$rowInc['account_name'];
	$crAmountInc=$rowInc['crAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$iInc."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_nameInc."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($crAmountInc,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$crAmountInc;
	
	$iInc++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>
		<tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	 $sql5 = "SELECT
	account_name,
	sum(dr) as drAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where l.branch_id=$branch_id and dr!='0.00' group by l.chart_id";

	//echo $sql;
	$res5= mysql_query($sql5);

      $i5=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$account_name=$row5['account_name'];
	$drAmount5=$row5['drAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($drAmount5,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount5;
	
	$i5++;
	}
	
	$netProfit=($profit+$totalIncome);
	$netExp=($totalExpense);
	$netIncome=($netProfit-$netExp);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Net Income : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netIncome,2)."</strong></td>
		</tr></table>";
	return $speci;

}//end fnc

//=========================================================================================

function SelectSalesMan(){ 
$branch_id = getFromSession('branch_id');

		$sql="SELECT e.person_id, person_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on e.person_id=p.person_id 
			where e.branch_id=$branch_id ORDER BY person_name";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='person_id' id='person_id' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['person_id']."'>".$row['person_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }


function SalesManDpDw($ele_id, $ele_lbl_id){ 
	 $search =getRequest('searchspeci');
	 $branch_id = getFromSession('branch_id');
		if($search){
 	        $sql="SELECT e.person_id, person_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on e.person_id=p.person_id 
			where person_name LIKE '%".$search."%' and e.branch_id=$branch_id ORDER BY person_name";
	}
	else{
	     
		 $sql="SELECT e.person_id, person_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on e.person_id=p.person_id 
			where e.branch_id=$branch_id ORDER BY person_name";
		 }
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addSalsManId('".$row['person_id']."','".$ele_id."','".$row['person_name']."','".$ele_lbl_id."');
		hideElement('slsManLookUp');\" style=\"cursor:pointer\">
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['person_name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }
   

function SelectItmCategory($cate = null){ 
//$branch_id = getFromSession('branch_id');
		$sql="SELECT sub_item_category_id, sub_item_category_name FROM ".INV_CATEGORY_SUB_TBL." ORDER BY sub_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='sub_item_category_id' size='1' id='sub_item_category_id' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['sub_item_category_id'] == $cate){
					   $country_select .= "<option value='".$row['sub_item_category_id']."' selected='selected'>".$row['sub_item_category_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['sub_item_category_id']."'>".$row['sub_item_category_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }

     
} // End class

?>
