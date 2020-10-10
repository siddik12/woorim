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
		 case 'currentStockReport' 		: echo $this->currentStockReport(); 				break;
		 
		 case 'MovedStockReportskin'  			: echo $this->MovedReportGeneratrorSkin(); 			break;
		 case 'TotalSalesReportSkin'  			: echo $this->TotalSalesReportSkin(); 				break;
		 case 'BranchWiseSalesReportSkin'  		: echo $this->BranchWiseSalesReportSkin(); 			break;
		 case 'SRWiseSalesReportSkin'  			: echo $this->SRWiseSalesReportSkin(); 				break;
		 case 'ShopWiseSalesReportSkin'  		: echo $this->ShopWiseSalesReportSkin(); 			break;
		 case 'ajaxShopWiseSalesReportSkin'  		: echo $this->ShopWiseSalesReportSkin2(); 			break;

		 case 'DuesReportskin'  				: echo $this->DuesReportGeneratrorSkin(); 			break;
		 case 'SRWiseDuesReportSkin'  			: echo $this->SRWiseDuesReportSkin(); 				break;
		 case 'ShopWiseDuesReportSkin'  		: echo $this->ShopWiseDuesReportSkin(); 			break;

		 case 'dateWiseStockReportSkin' : echo $this->DatewiseStockReportSkin(); 			break;
		 case 'SummaryReportSkinAziz' 	: echo $this->SummaryReportSkinAziz(); 				break;
		 case 'ShowSummaryReportAziz' 	: echo $this->ShowSummaryReportSkinAziz(); 			break;

 		 case 'ajaxModel'	   			: echo $this->SelectModel(); 					    break;
		
		 case 'SummaryReportSkin' 		: echo $this->SummaryReportSkin(); 					break;
		 case 'ShowSummaryReport' 		: echo $this->ShowSummaryReportSkin(); 				break;
         case 'list'                  	: $this->getList();                       			break;
         default                      	: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
function getList(){
$SalseManList=$this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'));
 $salesman=$this->SelectSalesMan($person_id);
 $catList=$this->SelectItmCategory();
 
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
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
				i.main_item_category_id,
				main_item_category_name,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				where i.branch_id=$branch_id and receive_date <= '$isfrom'
				group by i.sub_item_category_id order by i.main_item_category_id";
		}
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=900 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr  bgcolor='#003333'>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">S/L</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Model</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Stock</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Return</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Damage</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Cost(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Sales(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Sales</th>
	       </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$rec_quantity=($row['rec_quantity']);
	$sub_item_category_id=$row['sub_item_category_id'];
	
		 $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
		 where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id and sales_date<='$isfrom'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$sals_quantity = $row2c['sals_quantity'];
		
		$sqlStk1 = "SELECT sum(stock_out) as stock_out FROM return_info 
		where item_code ='$item_code' and branch_id=$branch_id";
		$resStk1 =mysql_query($sqlStk1);
		$rowStk1 = mysql_fetch_array($resStk1);
		$stock_out = $rowStk1['stock_out'];
		
		
		$tqnt1c =$rec_quantity-($sals_quantity+$stock_out);
		
		

		//=================== return Pupose Branch===========
		$sqlStk2 = "SELECT sum(stock_out) as stock_out2 FROM return_info 
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id and return_purpose='Branch'";
		$resStk2 =mysql_query($sqlStk2);
		$rowStk2 = mysql_fetch_array($resStk2);
		$Ret_branch = $rowStk2['stock_out2'];

		//=================== return Pupose Damage===========
		$sqlStk3 = "SELECT sum(stock_out) as stock_out3 FROM return_info 
		where sub_item_category_id ='$sub_item_category_id' and branch_id=$branch_id and return_purpose='Damage'";
		$resStk3 =mysql_query($sqlStk3);
		$rowStk3 = mysql_fetch_array($resStk3);
		$Ret_damage = $rowStk3['stock_out3'];
		

	// ========Count unit Price ===========================
	$sqlUnitPrice="SELECT max(item_id) as mxitem_id FROM inv_iteminfo  WHERE sub_item_category_id='$sub_item_category_id' and branch_id=$branch_id";
	//echo $sql;
	$resUnitPrice= mysql_query($sqlUnitPrice);
	while($rowUnitPrice=mysql_fetch_array($resUnitPrice)){
	$mxitem_id = $rowUnitPrice['mxitem_id'];
	}

	$sqlUnitPrice2="SELECT cost_price as cost_price, sales_price as sales_price FROM inv_iteminfo WHERE item_id=$mxitem_id 
	and sub_item_category_id='$sub_item_category_id' and branch_id='$branch_id'";
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">
		<a href=\"?app=item_return&tqnt1c=$tqnt1c&sub_item_category_id=".$row['sub_item_category_id']."&branch_id=".$branch_id."\" 
		style=\"text-decoration:none; cursor:pointer\">".$row['sub_item_category_name']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$tqnt1c."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$Ret_branch."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$Ret_damage."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($cost_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValues,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($sales_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValuesSls,2)."</td>
		</tr>";
		$i++;
		$subTotal1 = $subTotal1+$totalValues;
		$subTotal2 = $subTotal2+$tqnt1c;
		$subRet2 = $subRet2+$Ret_branch;
		$subDmg2 = $subDmg2+$Ret_damage;
		
		$subTotalSales = ($subTotalSales+$totalValuesSls);
	
	}//===================== End powder milk ===========================================
	
$speci .= "<tr>
  <td colspan='3' align='right'><b>Total Qnt : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subTotal2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subRet2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subDmg2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Cost : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotal1,2)." Taka</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Sales : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotalSales,2)." Taka</td>
  </tr>
  </table>";
	return $speci;

}//end fnc

function currentStockReportSkin(){
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
require_once(CURRENT_STOCK_REPORT_SKIN_BRANCH); 
}  

   
function currentStockReport(){
	$sub_item_category_id=getRequest('sub_item_category_id');
	$main_item_category_id=getRequest('main_item_category_id');
	$currentStockReport = $this->CurrentStockReportFetch($sub_item_category_id,$main_item_category_id);
	 require_once(CURRENT_STOCK_REPORT); 
}  
  
 function CurrentStockReportFetch($sub_item_category_id=null,$main_item_category_id=null){
 
 $branch_id = getFromSession('branch_id');
	
			 $sql="SELECT
				main_item_category_name,
				i.sub_item_category_id,
				s.main_item_category_id
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				group by m.main_item_category_id order by m.main_item_category_id";
	
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=750 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr >
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=500>Product Description </th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Unit Cost&nbsp;</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Total Cost&nbsp;</th>
		   </tr>";
	
		
   
	while($row=mysql_fetch_array($res)){
	
	$main_item_category_id=($row['main_item_category_id']);
	$sub_item_category_id=$row['sub_item_category_id'];

			$speci .= "<tr>
		<td colspan=4 nowrap=nowrap style=\"font-size:12px; font-family:Verdana;\"><strong>".$row['main_item_category_name']."</strong>
		
		<table width=750 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">";
			 $sql2="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				cost_price,
				count(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where i.main_item_category_id=$main_item_category_id and item_id not in(SELECT item_id FROM inv_item_sales)
				group by i.sub_item_category_id order by i.sub_item_category_id";
				
		$res2= mysql_query($sql2);
		while($row2=mysql_fetch_array($res2)){
			$cost_price=$row2['cost_price'];
			$rec_quantity=$row2['rec_quantity'];
			$totalValues=($cost_price*$rec_quantity);
			$speci .= "<tr height=25 >
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" width=450>".$row2['sub_item_category_name']."</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"  align='right' width=100>".$rec_quantity."&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>".number_format($cost_price,2)."&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>".number_format($totalValues,2)."&nbsp;</td>
			</tr>";
			
		//$Totalrec_quantity = $Totalrec_quantity+$rec_quantity;
		//$NetTotalValues = $NetTotalValues+$totalValues;
		}
		//=======================Sub Total ========================

			 $sql4="SELECT
				cost_price,
				count(quantity) as rec_quantity
			FROM
				inv_iteminfo 
				where main_item_category_id=$main_item_category_id and item_id not in(SELECT item_id FROM inv_item_sales)";
					
			$res4= mysql_query($sql4);
			$row4=mysql_fetch_array($res4);
			$cost_price4=$row4['cost_price'];
			$rec_quantity4=$row4['rec_quantity'];
			$totalValues4=($cost_price4*$rec_quantity4);

		$Totalrec_quantity4 = $Totalrec_quantity+$rec_quantity4;
		$NetTotalValues4 = $NetTotalValues+$totalValues4;
		
		$speci .= "<tr height=25 >
			<td nowrap=nowrap  width=450 style=\"font-family:Verdana;font_size:11px\"><strong>Sub Total</strong></td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"  align='right' width=100><strong>".$Totalrec_quantity4."</strong>&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100><strong>".number_format($NetTotalValues4,2)."</strong>&nbsp;</td>
			</tr>
			<tr>
			  <td align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  <td align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  </tr></table></td>
		</tr>";
		$NetTotalValues5 = $NetTotalValues5+$NetTotalValues4;
		$Totalrec_quantity5 = $Totalrec_quantity5+$Totalrec_quantity4;

	}/*
				 $sql5="SELECT
				cost_price,
				count(quantity) as rec_quantity
			FROM
				inv_iteminfo 
				where item_id not in(SELECT item_id FROM inv_item_sales)";
					
			$res5= mysql_query($sql5);
			while($row5=mysql_fetch_array($res5)){
			$cost_price5=$row5['cost_price'];
			$rec_quantity5=$row5['rec_quantity'];
			$totalValues5=($cost_price5*$rec_quantity5);

		$Totalrec_quantity5 = $Totalrec_quantity5+$rec_quantity5;
		$NetTotalValues5 = $NetTotalValues5+$totalValues5;
		}*/

$speci .= "<tr>
  <td align='right' style=\"font-family:Verdana;font_size:11px\"><strong>Gross Total :</strong></td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'><strong>".$Totalrec_quantity5."</strong>&nbsp;</td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'>&nbsp;</td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'><strong>".number_format($NetTotalValues5,2)."</strong>&nbsp;</td>
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
	$item_qnt=$row['item_qnt'];
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $NetSales=($total_amount-$totaldiscount);
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NetSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
		
	$totalValue = $totalValue+$NetSales;
	$totalcostprice = $totalcostprice+$totalCost;
	//$totalsalesprice = $totalsalesprice+$row['salesprice'];
	$netDisc = $netDisc+$totaldiscount;
	

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
 $slfrom2 = formatDate4insert(getRequest('Monthslfrom2'));
 $slto2 = formatDate4insert(getRequest('Montslto2'));
 $person_id = getRequest('person_id');
$sub_item_category_id=getRequest('sub_item_category_id');

 $sql = "SELECT person_name from hrm_person where person_id='$person_id'";
 $res=mysql_query($sql);
 $row=mysql_fetch_array($res);
 $person_name=$row['person_name'];

 $monthlySals = $this->monthlySalsmanWiseReport($slfrom2, $slto2);
  require_once(SALESMAN_WISE_MONTHLY_REPORT);
}


 function monthlySalsmanWiseReport($slfrom2, $slto2){
 $branch_id = getFromSession('branch_id');
 $person_id = getRequest('person_id');
$sub_item_category_id=getRequest('sub_item_category_id');

 if($sub_item_category_id && !$person_id){
	 $sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id and sales_type=1
	group by itemcode order by sales_date DESC ";
}

 if($person_id && !$sub_item_category_id){
	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and person_id=$person_id and sales_type=1
	group by itemcode order by sales_date DESC ";
}
 if($person_id && $sub_item_category_id){
	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and c.sub_item_category_id=$sub_item_category_id and person_id=$person_id and sales_type=1
	group by itemcode order by sales_date DESC ";
}

if(!$sub_item_category_id && !$person_id){
	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id  and sales_type=1 group by itemcode order by sales_date DESC ";

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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
<!--				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
-->	       </tr>
		  <tr><td colspan=12 align=left nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Retail Sales</strong></td></tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	if($branch_id=='6'){
			$costprice=0;
			}else{
			$costprice=$row['costprice'];
			}
	//$costprice=$row['costprice'];
	$item_qnt=$row['item_qnt'];
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 
	 $netSales=($total_amount-$totaldiscount);
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$costprice."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
-->		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$totaldiscount;
	
	
	
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
<!--		<td nowrap=nowrap ></td>
-->		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=5 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit." (".($inPercnt)." %)</strong></td>
<!--	  <td nowrap=nowrap ></td>
-->	  </tr>
	  <tr><td colspan=11 align=left nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Whole Sales</strong></td></tr>";
	  
	  //============================================= Whole Sales=======================
 if($sub_item_category_id && !$person_id){
	 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id and sales_type=2
	group by itemcode order by sales_date DESC ";
}

 if($person_id){
	$sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and person_id=$person_id and sales_type=2
	group by itemcode order by sales_date DESC ";
}
if(!$sub_item_category_id && !$person_id){
$sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sales_date,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id  and sales_type=2 group by itemcode order by sales_date DESC ";

}

	//echo $sql;
	$res2= mysql_query($sql2);
                
	 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$return_amount2=$row2['return_amount'];
	$costprice2=$row2['costprice'];
	$item_qnt2=$row2['item_qnt'];
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

	 
	 $netSales2=($total_amount2-$totaldiscount2);
	// $netSales=($total_amount-$NwDisc);

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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row2['sales_date'])."</td>
-->		</tr>";
	
	$returnAmount2=$returnAmount2+$row2['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
	
	
	$i2++;
	}
	
	$TotalSales2=($totalValue2);
	$profit2=($TotalSales2-$totalcostprice2);
	$inPercnt2=($profit2/$totalcostprice2)*100;
	
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales2."</strong></td>
<!--		<td nowrap=nowrap ></td>
-->		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=5 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit2." (".($inPercnt2)." %)</strong></td>
<!--	  <td nowrap=nowrap ></td>
-->	  </tr></table>";
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
$catList=$this->SelectItmCategory();
//$Branch=$this->SelectBranch($branch_id);
$SelectSalesMan=$this->SelectSalesMan($person_id);
$SalesWholeSaller=$this->SalesWholeSaller();

require_once(MOVED_REPORT_GENERATOR_SKIN);
}

function TotalSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromTotal'));
 $slto2 = formatDate4insert(getRequest('toTotal'));
 $TotalSals = $this->TotalSalsReport($slfrom2, $slto2);

  require_once(SALES_REPORT_TOTAL_SKIN);
}

 function TotalSalsReport($slfrom2, $slto2){
 
 $branch_id = getFromSession('branch_id');
	
			 $sql="SELECT
				main_item_category_name,
				i.sub_item_category_id,
				s.main_item_category_id
			FROM
				inv_item_sales i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2'
				group by m.main_item_category_id order by m.main_item_category_id";
	
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=750 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr >
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=500>Product Description </th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Unit Price&nbsp;</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-size:12px; font-family:Verdana;\" width=100>Total Price&nbsp;</th>
		   </tr>";
	
		
   
	while($row=mysql_fetch_array($res)){
	
	$main_item_category_id=($row['main_item_category_id']);
	$sub_item_category_id=$row['sub_item_category_id'];

			$speci .= "<tr>
		<td colspan=4 nowrap=nowrap style=\"font-size:12px; font-family:Verdana;\"><strong>".$row['main_item_category_name']."</strong>
		
		<table width=750 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">";
		 $sql2="SELECT
				i.sub_item_category_id,
				sub_item_category_name,
				salesprice,
				count(item_qnt) as item_qnt
			FROM
				inv_item_sales i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where main_item_category_id=$main_item_category_id and sales_status='Not Pending'
				group by i.sub_item_category_id order by i.sub_item_category_id";
				
		$res2= mysql_query($sql2);
		while($row2=mysql_fetch_array($res2)){
			$salesprice=$row2['salesprice'];
			$item_qnt=$row2['item_qnt'];
			$totalValues=($salesprice*$item_qnt);
			
			$speci .= "<tr height=25 >
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" width=450>".$row2['sub_item_category_name']."</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"  align='right' width=100>".$item_qnt."&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>".number_format($salesprice,2)."&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>".number_format($totalValues,2)."&nbsp;</td>
			</tr>";
			
		//$Totalrec_quantity = $Totalrec_quantity+$rec_quantity;
		//$NetTotalValues = $NetTotalValues+$totalValues;
		}
		//=======================Sub Total ========================

			 $sql4="SELECT
				salesprice,
				count(item_qnt) as item_qnt
			FROM
				inv_item_sales i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				where main_item_category_id=$main_item_category_id";
					
			$res4= mysql_query($sql4);
			$row4=mysql_fetch_array($res4);
			$salesprice4=$row4['salesprice'];
			$item_qnt4=$row4['item_qnt'];
			$totalValues4=($salesprice4*$item_qnt4);

		$Totalrec_quantity4 = $Totalrec_quantity+$item_qnt4;
		$NetTotalValues4 = $NetTotalValues+$totalValues4;
		
		$speci .= "<tr height=25 >
			<td nowrap=nowrap  width=450 style=\"font-family:Verdana;font_size:11px\"><strong>Sub Total</strong></td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"  align='right' width=100><strong>".$Totalrec_quantity4."</strong>&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100><strong>".number_format($NetTotalValues4,2)."</strong>&nbsp;</td>
			</tr>
			<tr>
			  <td align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  <td align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  </tr></table></td>
		</tr>";
		$NetTotalValues5 = $NetTotalValues5+$NetTotalValues4;
		$Totalrec_quantity5 = $Totalrec_quantity5+$Totalrec_quantity4;

	}/*
				 $sql5="SELECT
				cost_price,
				count(quantity) as rec_quantity
			FROM
				inv_iteminfo 
				where item_id not in(SELECT item_id FROM inv_item_sales)";
					
			$res5= mysql_query($sql5);
			while($row5=mysql_fetch_array($res5)){
			$cost_price5=$row5['cost_price'];
			$rec_quantity5=$row5['rec_quantity'];
			$totalValues5=($cost_price5*$rec_quantity5);

		$Totalrec_quantity5 = $Totalrec_quantity5+$rec_quantity5;
		$NetTotalValues5 = $NetTotalValues5+$totalValues5;
		}*/

$speci .= "<tr>
  <td align='right' style=\"font-family:Verdana;font_size:11px\"><strong>Gross Total :</strong></td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'><strong>".$Totalrec_quantity5."</strong>&nbsp;</td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'>&nbsp;</td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'><strong>".number_format($NetTotalValues5,2)."</strong>&nbsp;</td>
  </tr>
  </table>";
	return $speci;

}//end fnc


function SRWiseSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromsr'));
 $slto2 = formatDate4insert(getRequest('tosr'));

$person_id = getRequest('person_id');

 $sql="SELECT person_name from ".HRM_PERSON_TBL." where person_id=$person_id";
	    $res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$person_name=$row['person_name'];

 $SRwiseSalsReport = $this->SRwiseSalsReport($slfrom2, $slto2);

  require_once(SALES_REPORT_SR_WISE_SKIN);
}

 function SRwiseSalsReport($slfrom2, $slto2){ 

 $branch_id = getFromSession('branch_id');
 
 $sql = "SELECT
	sales_date,
	customer_id
FROM
	inv_item_sales_payment where sales_date between '$slfrom2' and '$slto2' group by sales_date order by sales_date ASC";

	//echo $sql;
	$res= mysql_query($sql);
	$speci .= "<table width=700 cellpadding=\"0\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana\" width=500>Customer Name</th>
	             <th nowrap=nowrap align=center style=\"border:#000000 solid 1px;font-family:Verdana\" width=100>Invoice No</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px;font-family:Verdana\" width=100>Sales Amount&nbsp;</th>
	       </tr>";
                  $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	//$customer_id=$row['customer_id'];
	$sales_date=$row['sales_date'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr>
		
		<td colspan=3 nowrap=nowrap style=\"font-family:Verdana\"><strong><br>"._date($row['sales_date'])."<br></strong>
		
		<table width=700 cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse:collapse\">";
			 
			 $sql2="SELECT
				store_name,
				total_amount,
				p.customer_id,
				sale_pay_id,
				p.customer_id
			FROM
				inv_item_sales_payment p 
				left outer join customer_info c on c.customer_id=p.customer_id
				where sales_date='$sales_date' ";
				
		$res2= mysql_query($sql2);
		while($row2=mysql_fetch_array($res2)){
			$total_amount=$row2['total_amount'];
			$sale_pay_id=$row2['sale_pay_id'];
			$customer_id=$row2['customer_id'];
			if($customer_id!=''){
				$customer=$row2['store_name'];
			}else{
				$customer=CASH;
			}

			 $sql3="SELECT invoice FROM	inv_item_sales where sale_pay_id='$sale_pay_id' ";
			$res3= mysql_query($sql3);
			$row3=mysql_fetch_array($res3);
			$invoice=$row3['invoice'];

			$speci .= "<tr height=25 >
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" width=500>".$customer."</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='center' width=100>".$invoice."&nbsp;</td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100>".number_format($total_amount,2)."&nbsp;</td>
			</tr>";
			
		//$Totalrec_quantity = $Totalrec_quantity+$rec_quantity;
		//$NetTotalValues = $NetTotalValues+$totalValues;
		}
		//=======================Sub Total ========================

			$sql4="SELECT sum(total_amount) as total_amount FROM inv_item_sales_payment where sales_date='$sales_date'";
			$res4= mysql_query($sql4);
			$row4=mysql_fetch_array($res4);
			$total_amount4=$row4['total_amount'];
			
			$NetTotalValues4 = $NetTotalValues+$total_amount4;
		
		$speci .= "<tr height=25 >
			<td nowrap=nowrap  width=500 style=\"font-family:Verdana;font_size:11px\"></td>
			<td nowrap=nowrap style=\"font-family:Verdana\" align='right' width=100><strong>Sub Total : </strong></td>
			<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align='right' width=100><strong>".number_format($NetTotalValues4,2)."</strong>&nbsp;</td>
			</tr>
			<tr>
			  <td align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  <td  align='right'>&nbsp;</td>
			  </tr></table>
		</td>
		</tr>";
	$NetTotalValues5 = $NetTotalValues5+$NetTotalValues4;
	
	}
	
		
	$speci .= "<tr>
  <td align='right' style=\"font-family:Verdana;font_size:11px\"></td>
  <td  style='font-family:Verdana;' align='right'><strong>Gross Total : </strong></td>
  <td  style='border:#000000 solid 1px;font-family:Verdana;' align='right'><strong>".number_format($NetTotalValues5,2)."</strong>&nbsp;</td>
  </tr>
  </table>";
	return $speci;

}//end fnc


function ShopWiseSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromShop'));
 $slto2 = formatDate4insert(getRequest('toShop'));

$customer_id = getRequest('customer_id');

 $sql="SELECT name from customer_info where customer_id=$customer_id";
	    $res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$name=$row['name'];

 $ShopWiseSalesReport = $this->ShopWiseSalesReport($slfrom2, $slto2,$customer_id);

  require_once(SALES_REPORT_SHOP_WISE_SKIN);
}

 
 
 function ShopWiseSalesReport($slfrom2, $slto2,$customer_id){ 

 $branch_id = getFromSession('branch_id');

 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	main_item_category_name,
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
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type='1' and s.branch_id=$branch_id and s.customer_id=$customer_id order by sales_date DESC";

	//echo $sql;
	$res2= mysql_query($sql2);
	$speci .= "<table width=1000 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Model</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Disc(%)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Total Disc</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\" >Invoice</th>
	       </tr>";
                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){
	$costprice2=$row2['costprice'];
	$item_qnt2=$row2['item_qnt'];
	$totalCost2=($item_qnt2*$costprice2);
	$salesprice2=$row2['salesprice'];

	$return_amount2=$row2['return_amount'];
	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];
/*
	$sql22="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId2";
	 $res22= mysql_query($sql22);
	 $row22 = mysql_fetch_array($res22);
	 $totsale_pay_id22=$row22['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id22);
*/	 
	 $netSales2=($total_amount2-$totaldiscount2);
	 $totalSales2=($salesprice2*$item_qnt2);
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$i2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row2['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$costprice2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$totalSales2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$row2['discount_percent']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($netSales2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" >"._date($row2['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" >".$row2['invoice']."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
	
	
	$i2++;
	}
	
//=============================================================
 $sql3 = "SELECT
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount
FROM
	inv_item_sales 
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and branch_id=$branch_id  and sales_type=1 and customer_id=$customer_id group by sub_item_category_id ";

	//echo $sql;
	$res3= mysql_query($sql3);
	
	while($row3=mysql_fetch_array($res3)){

	$costprice3=$row3['costprice'];
	$item_qnt3=$row3['item_qnt'];
	$totalCost3=($item_qnt3*$costprice3);

	$total_amount3=$row3['total_amount'];
	$totaldiscount3=$row3['totaldiscount'];
	 
	 $netSales3=($total_amount3-$totaldiscount3);	
	 
	 $totalValue3 = $totalValue3+$netSales3;
	 $totalcostprice3 = $totalcostprice3+$totalCost3;
	$totalQnt3 = $totalQnt3+$item_qnt3;
	$netDisc3 = $netDisc3+$totaldiscount3;
	$TotalSalesValue3 = $TotalSalesValue3+$row3['total_amount'];
	}


//===============================================================	
	
	
	$TotalSales3=($totalValue3);
	$profit3=($TotalSales3-$totalcostprice3);
	$inPercnt3=($profit3/$totalcostprice3)*100;
	
	

//========================= Dues Receive ========================
	 $sql3 = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and stat IS NULL and w.branch_id=$branch_id and w.customer_id=$customer_id and paid_date between '$slfrom2' and '$slto2'";

	//echo $sql;
	$res3= mysql_query($sql3);

//==============================================================
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=4> <strong>Total Qnt : ".$totalQnt3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".$totalcostprice3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".$netDisc3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".$TotalSales3."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=11> <strong>Total Profit : ".$profit3." (".$inPercnt3.")%</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";
		
 $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment where sales_date between '$slfrom2' and '$slto2' 
				and branch_id=$branch_id  and sales_type='1' and customer_id=$customer_id";
	$res4= mysql_query($sql4);
                 
	$row4=mysql_fetch_array($res4);	
		
		$totalDuesWhole4=$row4['dues_amount'];
		$netWholeSales4=($TotalSales3-$totalDuesWhole4);
		

		
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=9> <strong>Total Received Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".number_format($netWholeSales4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";
	$speci .= "<!--<tr height=25 >
		<td align=right nowrap=nowrap colspan=8> <strong>Dues : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".number_format($totalDuesWhole4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>-->
		<tr><td colspan='13' align='center'></td></tr>
		<tr><td colspan='13' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;font-family:Verdana;' ><b>Dues Recived</b></td></tr>";

                 $i3=1;        $rowcolor=0;
	while($row3=mysql_fetch_array($res3)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
		
$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($row3['cr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row3['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" colspan=2>"._date($row3['paid_date'])."</td>
		</tr>";
	$totalduesRec3=$totalduesRec3+$row3['cr_amount'];
	
	}
		
	$netDues4=($totalDuesWhole4-$totalduesRec3);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>".number_format($totalduesRec3,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>Net Dues : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>".number_format($netDues4,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		</table>";
	return $speci;

}//end fnc


function ShopWiseSalesReportSkin2(){

 $slfrom2 = getRequest('slfrom2');
 $slto2 = getRequest('slto2');

$customer_id = getRequest('customer_id');

 $sql="SELECT name from customer_info where customer_id=$customer_id";
	    $res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$name=$row['name'];

 $ShopWiseSalesReport = $this->ShopWiseSalesReport2($slfrom2, $slto2,$customer_id);

  require_once(SALES_REPORT_SHOP_WISE2_SKIN);
}

 
 
 function ShopWiseSalesReport2($slfrom2, $slto2,$customer_id){ 

 $branch_id = getFromSession('branch_id');

 $sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	main_item_category_name,
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
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type='1' and s.branch_id=$branch_id and s.customer_id=$customer_id order by sales_date DESC";

	//echo $sql;
	$res2= mysql_query($sql2);
	$speci .= "<table width=750 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px;font-family:Verdana;\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Model</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\" colspan=3>Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\"  colspan=2>Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Total Disc</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\">Net Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\" >Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;font-family:Verdana;\" >Invoice</th>
	       </tr>";
                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){
	$costprice2=$row2['costprice'];
	$item_qnt2=$row2['item_qnt'];
	$totalCost2=($item_qnt2*$costprice2);
	$salesprice2=$row2['salesprice'];

	$return_amount2=$row2['return_amount'];
	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];
/*
	$sql22="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId2";
	 $res22= mysql_query($sql22);
	 $row22 = mysql_fetch_array($res22);
	 $totsale_pay_id22=$row22['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id22);
*/	 
	 $netSales2=($total_amount2-$totaldiscount2);
	 $totalSales2=($salesprice2*$item_qnt2);
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$i2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row2['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right colspan=3>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right  colspan=2>".$totalSales2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($netSales2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" >"._date($row2['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" >".$row2['invoice']."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
	
	
	$i2++;
	}
	
//=============================================================
 $sql3 = "SELECT
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	sum(totaldiscount) as totaldiscount,
	sum(total_amount) as total_amount
FROM
	inv_item_sales 
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and branch_id=$branch_id  and sales_type=1 and customer_id=$customer_id group by sub_item_category_id ";

	//echo $sql;
	$res3= mysql_query($sql3);
	
	while($row3=mysql_fetch_array($res3)){

	$costprice3=$row3['costprice'];
	$item_qnt3=$row3['item_qnt'];
	$totalCost3=($item_qnt3*$costprice3);

	$total_amount3=$row3['total_amount'];
	$totaldiscount3=$row3['totaldiscount'];
	 
	 $netSales3=($total_amount3-$totaldiscount3);	
	 
	 $totalValue3 = $totalValue3+$netSales3;
	 $totalcostprice3 = $totalcostprice3+$totalCost3;
	$totalQnt3 = $totalQnt3+$item_qnt3;
	$netDisc3 = $netDisc3+$totaldiscount3;
	$TotalSalesValue3 = $TotalSalesValue3+$row3['total_amount'];
	}


//===============================================================	
	
	
	$TotalSales3=($totalValue3);
	$profit3=($TotalSales3-$totalcostprice3);
	$inPercnt3=($profit3/$totalcostprice3)*100;
	
	

//========================= Dues Receive ========================
	$sql3 = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and stat IS NULL and w.branch_id=$branch_id and w.customer_id=$customer_id and paid_date between '$slfrom2' and '$slto2'";

	//echo $sql;
	$res3= mysql_query($sql3);

//==============================================================
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=6> <strong>Total Qnt : ".$totalQnt3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".$netDisc3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".$TotalSales3."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">&nbsp;</td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";
		
 $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment where sales_date between '$slfrom2' and '$slto2' 
				and branch_id=$branch_id  and sales_type='1' and customer_id=$customer_id";
	$res4= mysql_query($sql4);
                 
	$row4=mysql_fetch_array($res4);	
		
		$totalDuesWhole4=$row4['dues_amount'];
		$netWholeSales4=($TotalSales3-$totalDuesWhole4);
		

		
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=9> <strong>Total Received Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netWholeSales4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>";
	$speci .= "<!--<tr height=25 >
		<td align=right nowrap=nowrap colspan=8> <strong>Dues : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\"><strong>".number_format($totalDuesWhole4,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>-->
		<tr><td colspan='13' align='center'></td></tr>
		<tr><td colspan='13' align='left' height='30' ><b>Dues Recived</b></td></tr>";

                 $i3=1;        $rowcolor=0;
	while($row3=mysql_fetch_array($res3)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
		
$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right>".number_format($row3['cr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\">".$row3['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" colspan=2>"._date($row3['paid_date'])."</td>
		</tr>";
	$totalduesRec3=$totalduesRec3+$row3['cr_amount'];
	
	}
		
	$netDues4=($totalDuesWhole4-$totalduesRec3);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>".number_format($totalduesRec3,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>Net Dues : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana;\" align=right><strong>".number_format($netDues4,2)."</strong></td>
		<td nowrap=nowrap colspan=2></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=10 ></td>
		<td nowrap=nowrap  align=right></td>
		<td nowrap=nowrap colspan=2 align=right></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=10 height=50 ></td>
		<td nowrap=nowrap  align=right></td>
		<td nowrap=nowrap colspan=2 align=right></td>
		</tr>
		<tr>
		<td nowrap=nowrap colspan=10 ><strong>Authorized Signature</strong></td>
		<td nowrap=nowrap  align=right></td>
		<td nowrap=nowrap colspan=2 align=right><strong>Received by</strong></td>
		</tr>
		</table>
	";
	return $speci;

}//end fnc



//================ All Sales End ====================================

//============================= ALl Dues Report Start=========================================

function DuesReportGeneratrorSkin(){

$SelectSalesMan=$this->SelectSalesMan($person_id);
//	$WholeSaller=$this->WholeSaller();
	$SRname=$this->SRname();
	
	//$ItmCategory4Model=$this->ItmCategory4Model();
//$SelectModel=$this->SelectModel();


require_once(DUES_REPORT_GENERATOR_SKIN);
}



function SRWiseDuesReportSkin(){

$person_id = getRequest('person_id');

  $sql="SELECT person_name from ".HRM_PERSON_TBL." where person_id=$person_id";
	    $res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		$person_name=$row['person_name'];

 $SRwiseSalsReport = $this->SRwiseDuesReport($person_id);

  require_once(DUES_REPORT_SR_WISE_SKIN);
}

 function SRwiseDuesReport($person_id){ 
$branch_id = getFromSession('branch_id');
$person_id = getRequest('person_id');
 $sql = "select 
		whole_sales_accid,
		a.customer_id,
		store_name,
		address,
		mobile,
		sum(dr_amount) AS dr_amount,
		sum(cr_amount) AS cr_amount,
		a.branch_id,
		(sum(dr_amount) - sum(cr_amount)) AS balance 
		from whole_saler_acc a inner join customer_info s on s.customer_id = a.customer_id 
		group by a.customer_id order by s.store_name";

	//echo $sql;
	$res= mysql_query($sql);

$html = '<table width="750" cellpadding="5" cellspacing="0" style="border-collapse:collapse">
	            <tr> 
	            <th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana">S/L</th>
	            <th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana">Customer</th>
	            <th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana">Mobile</th>
				<th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana" nowrap>DR Amount</th>
				<th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana" nowrap>CR Amount</th>
				<th   align="left" style="font-size:12px;border-bottom:1px solid #000000; font-family:Verdana" nowrap>Balance</th>
           </tr>';
                          $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
	
	//if($row['balance']!='0'){

				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&customer_id='.$row['customer_id'].'">'.$row['store_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">'.$row['mobile'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">'.number_format($row['dr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">'.number_format($row['cr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;font-size:12px; font-family:Verdana">'.number_format($row['balance'],2).'&nbsp;</td>
				   
				</tr>';
			//}
	
		$i++; 
		$Totaldr_amount=$Totaldr_amount+$row['dr_amount'];
		$Totalcr_amount=$Totalcr_amount+$row['cr_amount'];
		$Totalbalance=$Totalbalance+$row['balance'];
		
		}
	$html .= '<tr>
				  <td colspan="3"  align="right" style="border-right:1px solid #cccccc;padding:2px;font-size:14px; font-family:Verdana"><strong>Total : </strong></td>
				  <td  align="right" style="border-right:1px solid #cccccc;padding:2px;font-size:14px; font-family:Verdana"><strong>'.number_format($Totaldr_amount,2).'</strong></td>
				  <td  align="right" style="border-right:1px solid #cccccc;padding:2px;font-size:14px; font-family:Verdana"><strong>'.number_format($Totalcr_amount,2).'</strong></td>
				  <td  style="border-right:1px solid #cccccc;padding:2px;font-size:14px; font-family:Verdana"><strong>'.number_format($Totalbalance,2).'</strong></td>
  </tr>
  </table>
';
	
	return $html;
	
 }// EOF
 
 
function ShopWiseDuesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromShop'));
 $slto2 = formatDate4insert(getRequest('toShop'));
 $customer_id = getRequest('customer_id');

$sql="SELECT company_name from customer_info i inner join settings s on s.company_id=i.company_id 
where customer_type=2 and customer_id=$customer_id";
$res = mysql_query($sql);
$row = mysql_fetch_array($res); 
$company_name=$row['company_name'];
 
 $ShopSalsReport = $this->ShopSalsReport($slfrom2, $slto2,$customer_id);

  require_once(DUES_REPORT_SHOP_WISE_SKIN);
}

 function ShopDuesReport($slfrom2, $slto2,$customer_id){ 
$branch_id = getFromSession('branch_id');
	$sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	main_item_category_name,
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
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type=2 and s.customer_id=$customer_id and s.branch_id=$branch_id order by sales_date DESC";

	//echo $sql;
	$res2= mysql_query($sql2);

	$speci = "<table width=1000 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Model</th>
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
	while($row2=mysql_fetch_array($res2)){

	$return_amount2=$row2['return_amount'];
	$costprice2=$row2['costprice'];
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $netSales2=(($total_amount2)-($return_amount2+$totaldiscount2));
	// $netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['person_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row2['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row2['invoice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row2['branch_name']."</td>
		</tr>";
	
	$returnAmount2=$returnAmount2+$row2['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2= $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
	
	
	$i++;
	}
	
	$TotalSales2=($totalValue2);
	$profit2=($TotalSales2-$totalcostprice2);
	$inPercnt2=($profit2/$totalcostprice2)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"> <strong>Total Qnt : ".$totalQnt2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales2."</strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit2." (".($inPercnt2)." %)</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>";
	  
   $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
			where sales_date between '$slfrom2' and '$slto2' and sales_type='2' and customer_id=$customer_id and branch_id=$branch_id";
	$res4= mysql_query($sql4);
	$row4=mysql_fetch_array($res4);	
	$totalDuesWhole4=$row4['dues_amount'];
	$netWholeSales4=($TotalSales2-$totalDuesWhole4);
		
	$speci .= "<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Dues Amount : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalDuesWhole4,2)."</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>
	  <tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Net Sales : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netWholeSales4,2)."</strong></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  </tr>";
	
	$speci .= "</table>";
	return $speci;

}//end fnc


//============================= ALl Dues Report END=========================================



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
	$item_qnt=$row['item_qnt'];
	$totalCost=($item_qnt*$costprice);
	$salesprice=$row['salesprice'];

	$return_amount=$row['return_amount'];
	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $netSales=($total_amount-$totaldiscount);
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >".$row['invoice']."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$totaldiscount;
	
	
	
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
	where dr_amount='0.00' and stat IS NULL and w.branch_id=$branch_id and paid_date between '$slfrom2' and '$slto2'";

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

		$speci .= "<!--<tr height=25 >
		<td align=right nowrap=nowrap colspan=10> <strong>Dues : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalDuesWhole,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		<td nowrap=nowrap >&nbsp;</td>
		</tr>-->
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
 $sub_item_category_id=getRequest('sub_item_category_id');
 $main_item_category_id=getRequest('main_item_category_id');

 $ReceivedStock = $this->ReceivedStockReport($rsfrom, $rsto,$sub_item_category_id,$main_item_category_id);
  require_once(RECEIVED_STOCK_REPORT);
}


 function ReceivedStockReport($rsfrom, $rsto, $sub_item_category_id=null, $main_item_category_id=null){

$branch_id = getFromSession('branch_id');	
if($sub_item_category_id && $main_item_category_id && $rsfrom && $rsto){
	 $sql = "SELECT
	item_id,
	sub_item_category_name,
	item_size,
	description,
	main_item_category_name,
	cost_price,
	sales_price,
	receive_date,
	count(quantity) as quantity,
	i.sub_item_category_id
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	where receive_date between '$rsfrom' and '$rsto' and i.sub_item_category_id=$sub_item_category_id 
	and i.main_item_category_id=$main_item_category_id and i.branch_id=$branch_id  
	group by i.sub_item_category_id order by receive_date DESC";

}

if($main_item_category_id && $rsfrom && $rsto && !$sub_item_category_id){	
	 $sql = "SELECT
	item_id,
	sub_item_category_name,
	item_size,
	description,
	main_item_category_name,
	cost_price,
	sales_price,
	receive_date,
	count(quantity) as quantity,
	i.sub_item_category_id
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	where receive_date between '$rsfrom' and '$rsto' and i.main_item_category_id=$main_item_category_id and i.branch_id=$branch_id  
	group by i.sub_item_category_id order by receive_date DESC";
 
	}

if($rsfrom && $rsto && !$main_item_category_id && !$sub_item_category_id){
	 $sql = "SELECT
	item_id,
	sub_item_category_name,
	item_size,
	description,
	main_item_category_name,
	cost_price,
	sales_price,
	receive_date,
	count(quantity) as quantity,
	i.sub_item_category_id
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	where receive_date between '$rsfrom' and '$rsto' and i.branch_id=$branch_id  
	group by i.sub_item_category_id order by receive_date DESC";

}	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Model</th>
	             <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Serial</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
<!--				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Sales</th>
-->				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\" >Date</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		
		$quantity=$row['quantity'];
		$cost_price=$row['cost_price'];
		$sales_price=$row['sales_price'];
		$sub_item_category_id=$row['sub_item_category_id'];
		
		$TotalCost=($quantity*$cost_price);
		$TotalSales=($quantity*$sales_price);
		
			$rs = '<label style="font-weight:normal">';	
			 $sql3="SELECT item_size FROM ".INV_ITEMINFO_TBL." where sub_item_category_id=$sub_item_category_id ";
			$res3= mysql_query($sql3);
			while($row3 = mysql_fetch_array($res3)){
			$itemSize=$row3['item_size'];
			$rs .=$row3['item_size'].', ';
		}
		$rs .= '</label>';


		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td align=center style=\"border:#000000 solid 1px\">".$rs."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$cost_price."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>".$row['quantity']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['sales_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalSales."</td>
-->		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>"._date($row['receive_date'])."</td>
		</tr>";
		
	$totalcostprice = $totalcostprice+$TotalCost;
	$totalsalesprice = $totalsalesprice+$TotalSales;
	$totalQnt = $totalQnt+$row['quantity'];
	
	$i++;
	}
	
	
	$speci .= "<tr height=25 >
		<td colspan=5 align=right nowrap=nowrap><strong>TOTAL : </strong></td>
		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalcostprice,2)."</strong></td>
<!--		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Values : ".number_format($totalsalesprice,2)."</strong></td>
-->		<td align=center nowrap=nowrap>&nbsp;</td>
		</tr>
		</table>
";
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
if($branch_id=='6'){
			$costprice=0;
			}else{
			$costprice=$row['costprice'];
			}	
	//$costprice=$row['costprice'];
	$item_qnt=$row['item_qnt'];
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $netSales=($total_amount-$totaldiscount);
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$costprice."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$totaldiscount;
	
	
	
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
if($branch_id=='6'){
			$costprice2=0;
			}else{
			$costprice2=$row2['costprice'];
			}
	//$costprice2=$row2['costprice'];
	$item_qnt2=$row2['item_qnt'];
	$totalCost2=($item_qnt2*$costprice2);
	$salesprice2=$row2['salesprice'];

	$return_amount2=$row2['return_amount'];
	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

/*	$sql22="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId2";
	 $res22= mysql_query($sql22);
	 $row22 = mysql_fetch_array($res22);
	 $totsale_pay_id22=$row22['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id22);
*/	 
	 $netSales2=($total_amount2-$totaldiscount2);
	 //$totalSales2=($salesprice2*$item_qnt2);

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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$costprice2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalSales2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
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
	  <td colspan='11' align='left'><strong>Dues Received : </strong></td>
	  </tr>";
	  
	  //========================= Dues Receive ========================
	$sqlDRes = "SELECT
	store_name,
	cr_amount,
	paid_date

FROM
	whole_saler_acc w inner join customer_info c on w.customer_id=c.customer_id
	where dr_amount='0.00' and stat IS NULL and w.branch_id=$branch_id and paid_date between '$slfrom2' and '$slto2' and pay_type='Cash'";

	//echo $sql;
	$resDRes= mysql_query($sqlDRes);
                // $i7=1; 
				$rowcolor=0;
	while($rowDRes=mysql_fetch_array($resDRes)){
	
	$totalduesRec=$totalduesRec+$rowDRes['cr_amount'];
	
	}
		
	$netDues=($dues_amount-$totalduesRec);
	$speci .= "<tr>
		<td nowrap=nowrap colspan=10 style=\"border:#000000 solid 1px\" align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><strong>".number_format($totalduesRec,2)."</strong></td>
		</tr>
			  <tr>
	  <td colspan='10' align='right' style=\"border-top:#000000 solid 1px\"><strong>Total Sales(retail+whole+dues Received) : </strong></td>
	  <td  align='right' style=\"border-top:#000000 solid 1px\"><strong>".number_format(($TotalSales+$netWholeSales+$totalduesRec),2)."<hr></strong></td>
	  </tr>
	  <tr>
	  <td colspan='10' align='right'><strong> Whole Sales on Dues : </strong></td>
	  <td  align='right'><strong>".number_format($netDues,2)."</strong></td>
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
	
	$TotalIncome2=($TotalSales+$netWholeSales+$totalIncome+$totalduesRec);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>
		<tr><td colspan='11' align='right'><strong>Total Income :<u> ".number_format($TotalIncome2,2)."</u></strong><br></td></tr>
		<tr><td colspan='11' align='right'>&nbsp;</td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	 $sql5 = "SELECT sum(dr) as drAmount FROM daily_acc_ledger where branch_id=$branch_id and dr!='0.00000' 
	 and chart_id not in(27,28,29) and expdate between '$slfrom2' and '$slto2' and paytype='Cash'";

	//echo $sql;
	$res5= mysql_query($sql5);

	while($row5=mysql_fetch_array($res5)){
	$drAmount5=$row5['drAmount'];
	$totalExpense = $totalExpense+$drAmount5;
	}
	
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total Operating Cost : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		<tr><td colspan='11' align='right'>&nbsp;</td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Supplier Payments</b></td></tr>";
		
		
		//================================== For Supplier Payments ==============================================================
	 $sqlPY = "SELECT sum(cr) as totalCr FROM detail_account where branch_id=$branch_id and recdate between '$slfrom2' and '$slto2' and paytype='Cash'";

	//echo $sql;
	$resPY= mysql_query($sqlPY);
	while($rowPY=mysql_fetch_array($resPY)){
	$totalCrPY=$rowPY['totalCr'];
	$totalSupplierExp ='0';// $totalSupplierExp+$totalCrPY;
	}
	
	//================================== For Supplier Payments by expense skin ==============================================================
	 $sqlEXP = "SELECT sum(dr) as drAmount FROM daily_acc_ledger 
	 where branch_id=$branch_id and chart_id in(27,28,29) and expdate between '$slfrom2' and '$slto2' and paytype='Cash'";

	$resEXP= mysql_query($sqlEXP);
	$rowEXP=mysql_fetch_array($resEXP);
	$drAmountEXP=$rowEXP['drAmount'];
	

	//================================== For Supplier Payments by Bank ==============================================================
	 $sqlBnk = "SELECT sum(cr) as crAmount FROM detail_account 
	 where branch_id=$branch_id and recdate between '$slfrom2' 
	 and '$slto2' and cr != '0.00000' AND paytype='Bank'";

	$resBnk= mysql_query($sqlBnk);
	$rowBnk=mysql_fetch_array($resBnk);
	$crAmountBnk=$rowBnk['crAmount'];
	
	//================================== Adjusted Income ==============================================================
	 $sqlAdjust = "SELECT sum(cr) as crAmount FROM daily_acc_ledger 
	 where branch_id=$branch_id and chart_id='0' and expdate between '$slfrom2' and '$slto2' ";

	$resAdjust= mysql_query($sqlAdjust);
	$rowAdjust=mysql_fetch_array($resAdjust);
	$crAmountAdjust=$rowAdjust['crAmount'];

	 
	 $netIncomeTotal=(($TotalIncome2)-($totalExpense+$totalSupplierExp+$drAmountEXP));
	 
//===================== Calculation of last Day ============================

/*$sqlLstDay = "select DATE_SUB('".$slfrom2."', INTERVAL 1 DAY) as Last_Sales_Date";
$resLstDay= mysql_query($sqlLstDay);
$rowLstDay=mysql_fetch_array($resLstDay);
$Last_Sales_Date=$rowLstDay['Last_Sales_Date'];
*/
	$sql7 = "SELECT sum(paid_amount) as paid_amount,sum(ret_amount) as ret_amount FROM
	inv_item_sales_payment where sales_date<'$slfrom2' and pay_type='Cash' and branch_id=$branch_id";

	//echo $sql;
	$res7= mysql_query($sql7);

	while($row7=mysql_fetch_array($res7)){
	$paid_amount7=($row7['paid_amount']-$row7['ret_amount']);
	}
// ================= last day dues reeceived =====================
$sqlduRec2 = "SELECT sum(cr_amount) as cr_amount FROM whole_saler_acc	
	 where paid_date<'$slfrom2' and pay_type='Cash' and branch_id=$branch_id and stat IS NULL and invoice IS NULL ";

	//echo $sql;
	$resduRec2= mysql_query($sqlduRec2);

	while($rowduRec2=mysql_fetch_array($resduRec2)){
	$cr_amountduRec2=$rowduRec2['cr_amount'];
	}
	
	
	
	//================================== Cash Received Last Date==============================================================
	 $sql9 = "SELECT sum(cr) as crAmount FROM daily_acc_ledger where  cr!='0.00000' and expdate<'$slfrom2' and branch_id=$branch_id";
	$res9= mysql_query($sql9);
	while($row9=mysql_fetch_array($res9)){
	$totalIncome9 =$row9['crAmount'];
	}
	
	$LastDayIncome=($paid_amount7+$totalIncome9+$cr_amountduRec2);

//================= Last Day Operating Cost =======================
 $sql10 = "SELECT sum(dr) as drAmount FROM	daily_acc_ledger where dr!='0.00000' and expdate<'$slfrom2' and paytype='Cash' and branch_id=$branch_id and chart_id not in(27,28,29)";
 $res10= mysql_query($sql10);

	while($row10=mysql_fetch_array($res10)){
	$totalExpense10 = $row10['drAmount'];
	}
	
	$LastDayCash=($LastDayIncome-$totalExpense10);	
	

//=====================  END============================
	 
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total Supplier Payments : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format(($totalSupplierExp+$drAmountEXP),2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=11>&nbsp;</td>
		</tr>		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total Expense: </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format(($totalExpense+$totalSupplierExp+$drAmountEXP),2)."</strong></td>
		</tr>
		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Previous Balance : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format(($LastDayCash),2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Net Income : </strong></td>
<!--		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format((($netIncomeTotal+$LastDayCash)-$crAmountAdjust),2)."</strong></td>
-->		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format((($netIncomeTotal+$LastDayCash)),2)."</strong></td>
		</tr>
		</table>";
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

function ItmCategory4Model($cate = null){ 
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

function SelectModel(){ 
$main_item_category_id=getRequest('main_item_category_id');

		 $sql="SELECT sub_item_category_id,	sub_item_category_name FROM inv_item_category_sub where main_item_category_id=$main_item_category_id order by sub_item_category_name";
	    $result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) {

	echo '<label style="font-weight:normal"><input name="sub_item_category_id" id="sub_item_category_id" type="radio" value="'.$row['sub_item_category_id'].'" />'.$row['sub_item_category_name'].'</label><br>';	
		}

}	

 
 function SalesWholeSaller(){ 
 $branch_id = getFromSession('branch_id');
		 $sql="SELECT customer_id,store_name, name from customer_info ORDER BY store_name";
	    $result = mysql_query($sql);
		
	    $Supplier_select = "<select name='customer_id' id='customer_id' class=\"textBox\" style='width:300px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['customer_id']."'>".$row['store_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }


 function SRname($cate = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT person_name, e.person_id FROM hrm_employee e inner join hrm_person p on p.person_id=e.person_id 
		where e.branch_id=$branch_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='person_id' size='1' id='person_id' class=\"textBox\" style='width:150px;' onchange=\"getCustomer(this.value)\">";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['person_id'] == $cate){
					   $country_select .= "<option value='".$row['person_id']."' selected='selected'>".$row['person_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['person_id']."'>".$row['person_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }
    

     
} // End class

?>
