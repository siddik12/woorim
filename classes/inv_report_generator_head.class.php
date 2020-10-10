<?php

class inv_report_generator_head
{
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxSalesManLoad'  				: echo $this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id')); break;
		 case 'MonthlySalesReport'  			: echo $this->MonthlySalesReport(); 				break;
		 case 'MonthlySalesReportSkin'  		: echo $this->MonthlySalesReportGeneratrorSkin(); 	break;
		 case 'DamageReport'  					: echo $this->DamageReportSkin(); 					break;
		 case 'DamageStockReportskin'  			: echo $this->DamageReportGeneratrorSkin(); 		break;
		 case 'ReturnReport'  					: echo $this->ReturnReportSkin(); 					break;
		 case 'ReturneStockReportskin'  		: echo $this->ReturnReportGeneratrorSkin(); 		break;
		 
		 case 'MovedStockReportskin'  			: echo $this->MovedReportGeneratrorSkin(); 			break;
		 case 'TotalSalesReportSkin'  			: echo $this->TotalSalesReportSkin(); 				break;
		 case 'BranchWiseSalesReportSkin'  		: echo $this->BranchWiseSalesReportSkin(); 			break;
		 case 'SRWiseSalesReportSkin'  			: echo $this->SRWiseSalesReportSkin(); 				break;
		 case 'ShopWiseSalesReportSkin'  		: echo $this->ShopWiseSalesReportSkin(); 			break;
		 
		 case 'ReceivedStockReport1'  			: echo $this->ReceiveStockReportSkin1();			break;
		 case 'ReceivedStockReport2'  			: echo $this->ReceiveStockReportSkin2();			break;
		 
		 case 'DistributeReport'  				: echo $this->ItemDistributeReport();				break;
		 case 'DistributeReportSkin'  			: echo $this->ItemDistributeReportSkin();			break;
 		 case 'DailyCashGeneratrorSkin' 		: echo $this->DailyCashGeneratrorSkin(); 			break;
 		 case 'DailyCashReportSkin' 			: echo $this->DailyCashReportSkin(); 				break;
		 case 'currentStockReportSkin' 			: echo $this->currentStockReportSkin(); 			break;
		 case 'currentStockReport' 				: echo $this->currentStockReport(); 				break;
		 case 'GodownStockReportSkin' 			: echo $this->GodownStockReportSkin(); 				break;
		 case 'GodownStockReport' 				: echo $this->GodownStockReport(); 					break;
		 case 'DamageRequestList' 				: echo $this->DamageRequestList(); 					break;
		 case 'dateWiseStockReportSkin' 		: echo $this->DatewiseStockReportSkin(); 			break;
		 case 'SummaryReportSkin' 				: echo $this->SummaryReportSkin(); 					break;
		 case 'ShowSummaryReport' 				: echo $this->ShowSummaryReportSkin(); 				break;
		 case 'IncomeStateSkin' 				: echo $this->IncomeStateSkin(); 					break;
		 case 'ShowIncomeStateSkin' 			: echo $this->ShowIncomeStateSkin(); 				break;
		 case 'WholeSaleDuesGeneSkin' 			: echo $this->WholeSaleDuesGeneSkin(); 				break;
 		 case 'WholeSalesTotalDues' 			: echo $this->WholeSalesTotalDues(); 				break;
 		 case 'WholeSaleDateWiseDuesSkin' 		: echo $this->WholeSaleDateWiseDuesSkin(); 			break;
 		 case 'WholeSaleDuesCollectSkin' 		: echo $this->WholeSaleDuesCollectSkin(); 			break;
 		 case 'ajaxModel'	   					: $this->SelectModel(); 							break;
       case 'list'                  			: $this->getList();                       			break;
         default                      			: $cmd == 'list'; $this->getList();	       			break;
      }
 }
function getList(){
$SalseManList=$this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'));
$slsman = $this->SelectBillType();
$catList=$this->SelectItmCategory($sub_item_category_id);
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();

	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  

function DatewiseStockReportSkin(){
 $isfrom = formatDate4insert(getRequest('isfrom'));
 $branch_id = getRequest('branch_id');
$sql = "select DATE_SUB('$isfrom', INTERVAL 1 DAY) as clsDate";
$res= mysql_query($sql);
$row=mysql_fetch_array($res);
$clsDate=_date($row['clsDate']);
$sub_item_category_id=getRequest('sub_item_category_id');
	$DatewiseStockReport = $this->DateWiseStockReportFetch($isfrom,$sub_item_category_id);
	$cdate = SelectCDate();
	$catList=$this->SelectItmCategory($sub_item_category_id);
	$Branch=$this->SelectBranch($branch_id);

	 require_once(DATE_WISE_STOCK_REPORT_HEAD); 
}

 function DateWiseStockReportFetch($isfrom, $sub_item_category_id=null){
 	$item_size=getRequest('item_size');
	$branch_id = getRequest('branch_id');
	
	if($sub_item_category_id && !$item_size && !$branch_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and i.sub_item_category_id=$sub_item_category_id 
				group by item_code order by i.sub_item_category_id";	
	}
	if($item_size && !$sub_item_category_id && !$branch_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and item_size='$item_size' group by item_code order by i.sub_item_category_id";
		}
	if($branch_id && !$item_size && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and i.branch_id=$branch_id
				group by item_code order by i.sub_item_category_id";
		}
	if($item_size && $branch_id && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and i.branch_id=$branch_id and item_size='$item_size' group by item_code order by i.sub_item_category_id";
		}
	if($item_size && $branch_id && $sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and i.branch_id=$branch_id and item_size='$item_size' and i.sub_item_category_id=$sub_item_category_id
				group by item_code order by i.sub_item_category_id";
		}
	if($item_size && !$branch_id && $sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and item_size='$item_size' and i.sub_item_category_id=$sub_item_category_id
				group by item_code order by i.sub_item_category_id";
		}
	if(!$item_size && $branch_id && $sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and i.branch_id=$branch_id and i.sub_item_category_id=$sub_item_category_id
				group by item_code order by i.sub_item_category_id";
		}
	if($item_size && !$branch_id && $sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where receive_date<'$isfrom' and item_size='$item_size' and i.sub_item_category_id=$sub_item_category_id
				group by item_code order by i.sub_item_category_id";
		}
	if(!$item_size && !$branch_id && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				item_code,
				i.branch_id,
				branch_name,
				sum(quantity-stock_out) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join branch b on b.branch_id=i.branch_id where receive_date<'$isfrom'
				group by item_code order by i.sub_item_category_id";
		}
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=900 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Branch</th>
	       </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$rec_quantity=($row['rec_quantity']);
	$item_code=$row['item_code'];
	
		 $sql2c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  
		 where itemcode ='$item_code'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		
		$sals_quantity = $row2c['sals_quantity'];
		
		$tqnt1c =($rec_quantity-$sals_quantity);
		
		

	// ========Count unit Price ===========================
	$sqlUnitPrice="SELECT max(item_id) as mxitem_id FROM inv_iteminfo  WHERE item_code='$item_code'";
	//echo $sql;
	$resUnitPrice= mysql_query($sqlUnitPrice);
	while($rowUnitPrice=mysql_fetch_array($resUnitPrice)){
	$mxitem_id = $rowUnitPrice['mxitem_id'];
	}

	$sqlUnitPrice2="SELECT cost_price as cost_price, sales_price as sales_price FROM inv_iteminfo WHERE item_id=$mxitem_id 
	and item_code='$item_code'";
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"><a href=\"?app=stock_out&tqnt1c=$tqnt1c&item_code=".$row['item_code']."\" style=\"text-decoration:none; cursor:pointer\">".$row['item_code']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">
		<a href='?app=inv_report_generator_head&cmd=dateWiseStockReportSkin&item_size=".$row['item_size']."&branch_id=".$row['branch_id']."&sub_item_category_id=".$row['sub_item_category_id']."'>".$row['item_size']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\"  align='right'>".$tqnt1c."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($cost_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValues,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($sales_price,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\" align='right'>".number_format($totalValuesSls,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['branch_name']."</td>
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
  <td  style='border:#000000 solid 1px;' align='right'>&nbsp;</td>
  </tr>
  </table>";
	return $speci;

}//end fnc
   
function currentStockReportSkin(){
$Branch=$this->SelectBranch();
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
	 require_once(CURRENT_STOCK_REPORT_HEAD); 
}  

function currentStockReport(){
	$sub_item_category_id=getRequest('sub_item_category_id');
	$main_item_category_id=getRequest('main_item_category_id');
	$branch_id = getRequest('branch_id');
	
	$sql2c = "SELECT branch_name FROM branch where branch_id =$branch_id";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		
		$branch_name = $row2c['branch_name'];
	
	$currentStockReport=$this->CurrentStockReportFetch($sub_item_category_id,$main_item_category_id);
	 require_once(CURRENT_STOCK_REPORT_HEAD2); 
}  
  
 function CurrentStockReportFetch($sub_item_category_id=null,$main_item_category_id=null){
	
	$branch_id = getRequest('branch_id');
	
	if($sub_item_category_id && !$main_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				i.main_item_category_id,
				main_item_category_name,
				i.branch_id,
				branch_name,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where i.sub_item_category_id=$sub_item_category_id and i.branch_id=$branch_id 
				group by i.sub_item_category_id order by i.main_item_category_id";	
	}
	if($main_item_category_id && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				i.main_item_category_id,
				main_item_category_name,
				i.branch_id,
				branch_name,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where i.main_item_category_id=$main_item_category_id and i.branch_id=$branch_id 
				group by i.sub_item_category_id order by i.main_item_category_id";
		}
	if($main_item_category_id && $sub_item_category_id){
			$sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				i.main_item_category_id,
				main_item_category_name,
				i.branch_id,
				branch_name,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where i.sub_item_category_id=$sub_item_category_id and i.branch_id=$branch_id and i.main_item_category_id=$main_item_category_id
				group by i.sub_item_category_id order by i.main_item_category_id";
		}
	if(!$main_item_category_id && !$sub_item_category_id){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				i.main_item_category_id,
				main_item_category_name,
				i.branch_id,
				branch_name,
				sum(quantity) as rec_quantity
			FROM
				inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on s.main_item_category_id=m.main_item_category_id
				inner join branch b on b.branch_id=i.branch_id
				where i.branch_id=$branch_id 
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
	$branch_id=$row['branch_id'];
	
		$sql2c = "SELECT sum(stock_out) as stock_out FROM return_info where sub_item_category_id ='$sub_item_category_id' and branch_id='$branch_id'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$stock_out = $row2c['stock_out'];

		$sql3c = "SELECT sum(item_qnt-return_qnt) as sals_quantity FROM inv_item_sales  where sub_item_category_id ='$sub_item_category_id' and branch_id='$branch_id'";
		$res3c =mysql_query($sql3c);
		$row3c = mysql_fetch_array($res3c);
		$sals_quantity = $row3c['sals_quantity'];
		
		$tqnt1c =(($rec_quantity)-($sals_quantity+$stock_out));
		
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
	$sqlUnitPrice="SELECT max(item_id) as mxitem_id FROM inv_iteminfo  WHERE sub_item_category_id='$sub_item_category_id' and branch_id='$branch_id'";
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">
		<a href='?app=inv_report_generator_head&cmd=currentStockReportSkin&branch_id=".$row['branch_id']."&sub_item_category_id=".$row['sub_item_category_id']."'>".$row['sub_item_category_name']."</a></td>
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


function DamageReportGeneratrorSkin(){
$SelectSupplier=$this->SelectSupplier();
require_once(DAMAGE_REPORT_GENERATOR_HEAD_SKIN);
}

function DamageReportSkin(){
 $supplier_id=getRequest('supplier_id');
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
  $slto2 = formatDate4insert(getRequest('slto2'));//exit();
 $Damage = $this->SKUDamageReport($slfrom2, $slto2,$supplier_id);
 require_once(DAMAGE_STOCK_REPORT_HEAD);
}
 
 function SKUDamageReport($slfrom2, $slto2, $supplier_id=null){
 

if($supplier_id && $slfrom2 && $slto2){
	 $sql = "SELECT
	damage_info_id,
	item_code,
	stock_out,
	damage_date,
	company_name,
	item_name,
	cost_price
	
FROM
	damage_info d inner join inv_supplier_info s on s.supplier_id=d.supplier_id
	where damage_date between '$slfrom2' and '$slto2' and d.supplier_id=$supplier_id  
	group by item_code order by damage_date";
	}


if($slfrom2 && $slto2 && !$supplier_id){
 
	 $sql = "SELECT
	damage_info_id,
	item_code,
	stock_out,
	damage_date,
	company_name,
	item_name,
	cost_price
FROM
	damage_info d inner join inv_supplier_info s on s.supplier_id=d.supplier_id
	where damage_date between '$slfrom2' and '$slto2'
	group by item_code order by damage_date";
	}

	$res= mysql_query($sql);
	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Supplier</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['company_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['damage_date'])."</td>
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

//============================= ALl Sales Report Start=========================================

function MovedReportGeneratrorSkin(){
$catList=$this->SelectItmCategory();
$Branch=$this->SelectBranch($branch_id);
$SelectSalesMan=$this->SelectSalesMan($person_id);
$SalesWholeSaller=$this->SalesWholeSaller();

require_once(MOVED_REPORT_GENERATOR_HEAD_SKIN);
}

function TotalSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromTotal'));
 $slto2 = formatDate4insert(getRequest('toTotal'));
 $TotalSals = $this->TotalSalsReport($slfrom2, $slto2);

  require_once(SALES_REPORT_TOTAL_HEAD_SKIN);
}

 function TotalSalsReport($slfrom2, $slto2){ 

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
	branch_name,
	return_qnt
FROM
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	inner join branch b on b.branch_id=s.branch_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type=2 order by sales_date DESC";

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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Branch</th>
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	   
   $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
			where sales_date between '$slfrom2' and '$slto2' and sales_type='2'";
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	
	$speci .= "</table>";
	return $speci;

}//end fnc

function BranchWiseSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('brFrom'));
 $slto2 = formatDate4insert(getRequest('brTo'));
 $branch_id = getRequest('branch_id');
 $BranchWiseSalsReport = $this->BranchWiseSalsReport($slfrom2, $slto2,$branch_id);

  require_once(SALES_REPORT_BRANCH_WISE_HEAD_SKIN);
}

 function BranchWiseSalsReport($slfrom2, $slto2,$branch_id){ 

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
	branch_name,
	return_qnt
FROM
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	inner join branch b on b.branch_id=s.branch_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type=2 and s.branch_id=$branch_id order by sales_date DESC";

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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Branch</th>
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	   
   $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
			where sales_date between '$slfrom2' and '$slto2' and sales_type='2' and branch_id=$branch_id";
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	
	$speci .= "</table>";
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

 $SRwiseSalsReport = $this->SRwiseSalsReport($slfrom2, $slto2,$person_id);

  require_once(SALES_REPORT_SR_WISE_HEAD_SKIN);
}

 function SRwiseSalsReport($slfrom2, $slto2,$person_id){ 

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
	branch_name,
	return_qnt
FROM
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	inner join branch b on b.branch_id=s.branch_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type=2 and s.person_id=$person_id order by sales_date DESC";

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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Branch</th>
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	  
   $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
			where sales_date between '$slfrom2' and '$slto2' and sales_type='2' and person_id=$person_id";
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	
	$speci .= "</table>";
	return $speci;

}//end fnc

function ShopWiseSalesReportSkin(){

 $slfrom2 = formatDate4insert(getRequest('fromShop'));
 $slto2 = formatDate4insert(getRequest('toShop'));
 $customer_id = getRequest('customer_id');

$sql="SELECT company_name from customer_info i inner join settings s on s.company_id=i.company_id 
where customer_type=2 and customer_id=$customer_id";
$res = mysql_query($sql);
$row = mysql_fetch_array($res); 
$company_name=$row['company_name'];
 
 $ShopSalsReport = $this->ShopSalsReport($slfrom2, $slto2,$customer_id);

  require_once(SALES_REPORT_SHOP_WISE_HEAD_SKIN);
}

 function ShopSalsReport($slfrom2, $slto2,$customer_id){ 

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
	branch_name,
	return_qnt
FROM
	inv_item_sales s inner join inv_item_category_sub sc on sc.sub_item_category_id=s.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sc.main_item_category_id
	inner join hrm_person p on p.person_id=s.person_id
	inner join branch b on b.branch_id=s.branch_id
	where sales_status='Not Pending' and sales_date between '$slfrom2' and '$slto2' 
	and sales_type=2 and s.customer_id=$customer_id order by sales_date DESC";

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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Branch</th>
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	  
   $sql4 = "SELECT sum(dues_amount) as dues_amount FROM inv_item_sales_payment 
			where sales_date between '$slfrom2' and '$slto2' and sales_type='2' and customer_id=$customer_id";
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
	  <td nowrap=nowrap ></td>
	  </tr>";
	
	$speci .= "</table>";
	return $speci;

}//end fnc


//============================= ALl Sales Report END=========================================
//================================= Monthly Report Generator ============================
function MonthlySalesReportGeneratrorSkin(){
$catList=$this->SelectItmCategory();
$Branch=$this->SelectBranch($branch_id);
$salesman=$this->SelectSalesMan($person_id);

require_once(MONTHLY_SALES_REPORT_GENERATOR_HEAD_SKIN);
}

function MonthlySalesReport(){
$branch_id = getRequest('branch_id');
$sub_item_category_id=getRequest('sub_item_category_id');
 $slfrom2 = formatDate4insert(getRequest('Monthslfrom2'));
 $slto2 = formatDate4insert(getRequest('Montslto2'));
 $person_id = getRequest('person_id');
	 if($person_id){
	 $sql = "SELECT	person_name FROM hrm_person where person_id=$person_id";
	 $res= mysql_query($sql);
	 $row=mysql_fetch_array($res);
	 $person_name=$row['person_name'];
	 }
	 if($branch_id){
	 $sql2 = "SELECT branch_name FROM branch where branch_id=$branch_id";
	 $res2= mysql_query($sql2);
	 $row2=mysql_fetch_array($res2);
	 $branch_name=$row2['branch_name'];
	 }
 $monthlySals = $this->MonthlySalesReportShow($slfrom2, $slto2,$sub_item_category_id, $person_id);

  require_once(MONTHLY_SALES_REPORT_HEAD_SKIN);
}



 function MonthlySalesReportShow($slfrom2, $slto2,$sub_item_category_id=null,$person_id=null){
 $branch_id = getRequest('branch_id');
 if($sub_item_category_id && $branch_id  && !$person_id){
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
 if($sub_item_category_id && $branch_id && $person_id){
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
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id and person_id=$person_id and sales_type=1
	group by itemcode order by sales_date DESC ";
}

 if($branch_id && $person_id){
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
if($branch_id && !$sub_item_category_id && !$person_id){
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
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
	       </tr>
		  <tr><td colspan=12 align=left nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Retail Sales</strong></td></tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 
	 $netSales=(($total_amount)-($return_amount+$totaldiscount));
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['sales_date'])."</td>
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalSales."</strong></td>
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
	  </tr>
	  <tr><td colspan=12 align=left nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Whole Sales</strong></td></tr>";
	  
	  //============================================= Whole Sales=======================
if($sub_item_category_id && $branch_id  && !$person_id){
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
 if($sub_item_category_id && $branch_id && $person_id){
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
	and c.sub_item_category_id=$sub_item_category_id and s.branch_id=$branch_id and person_id=$person_id and sales_type=2
	group by itemcode order by sales_date DESC ";
}

 if($branch_id && $person_id){
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
if($branch_id && !$sub_item_category_id && !$person_id){
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
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

	 
	 $netSales2=(($total_amount2)-($return_amount2+$totaldiscount2));
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row2['sales_date'])."</td>
		</tr>";
	
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
		<td nowrap=nowrap ></td>
		</tr>
	<tr height=25 >
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td nowrap=nowrap ></td>
	  <td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Profite : </strong></td>
	  <td colspan=6 align=center nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$profit2." (".($inPercnt2)." %)</strong></td>
	  <td nowrap=nowrap ></td>
	  </tr></table>";
	return $speci;

}//end fnc


function ReceiveStockReportSkin1(){
 $rsfrom = formatDate4insert(getRequest('rsfrom'));
 $rsto = formatDate4insert(getRequest('rsto'));
 //$challan_no = getRequest('challan_no');
 $sub_item_category_id=getRequest('sub_item_category_id');
 $main_item_category_id=getRequest('main_item_category_id');

 $catList=$this->SelectItmCategory($sub_item_category_id);

 $ReceivedStock = $this->ReceivedStockReport1($rsfrom, $rsto,$sub_item_category_id,$main_item_category_id);
  require_once(RECEIVED_STOCK_REPORT_HEAD1);
}


 function ReceivedStockReport1($rsfrom, $rsto, $sub_item_category_id=null, $main_item_category_id=null){

if($sub_item_category_id && $main_item_category_id && $rsfrom && $rsto){
	$sql = "SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	company_name,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	inner join inv_supplier_info i on i.supplier_id=w.supplier_id
	where w.sub_item_category_id=$sub_item_category_id and w.main_item_category_id=$main_item_category_id 
	and receive_date between '$rsfrom' and '$rsto'
	group by w.sub_item_category_id,cost_price,receive_date order by receive_date DESC";

}
if($main_item_category_id && $rsfrom && $rsto && !$sub_item_category_id){
 
	 $sql = "SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	company_name,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	inner join inv_supplier_info i on i.supplier_id=w.supplier_id
	where w.main_item_category_id=$main_item_category_id and receive_date between '$rsfrom' and '$rsto'
	group by w.sub_item_category_id,cost_price,receive_date order by receive_date DESC";
	}
	
	if($rsfrom && $rsto && !$main_item_category_id && !$sub_item_category_id){
	 $sql = "SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	company_name,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join inv_supplier_info i on i.supplier_id=w.supplier_id 
	where receive_date between '$rsfrom' and '$rsto'
	group by w.sub_item_category_id,cost_price,receive_date order by receive_date DESC";

}
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Model</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Supplier</th>
<!--				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\" >Date</th>
-->	       </tr>";
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['quantity']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['sales_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalSales."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['company_name']."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['receive_date'])."</td>
-->		</tr>";
		
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total Qnt. : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Cost : ".number_format($totalcostprice,2)."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Values : ".number_format($totalsalesprice,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		</tr>
		</table>";
	return $speci;
}

function ReceiveStockReportSkin2(){
 $challan_no = getRequest('challan_no');
 
$sql = "SELECT supplier_id,receive_date from ware_house where challan_no='$challan_no'";
$res= mysql_query($sql);
$row=mysql_fetch_array($res);
$supplier_id=$row['supplier_id'];
$receive_date=$row['receive_date'];

$sql2 = "SELECT company_name,address from inv_supplier_info where supplier_id=$supplier_id";
$res2= mysql_query($sql2);
$row2=mysql_fetch_array($res2);
$company_name=$row2['company_name'];
$address=$row2['address'];


 $ReceivedStock = $this->ReceivedStockReport2($challan_no);
  require_once(RECEIVED_STOCK_REPORT_HEAD2);
}

 function ReceivedStockReport2($challan_no){

	 $sql = "SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	company_name,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join inv_supplier_info i on i.supplier_id=w.supplier_id 
	where challan_no='$challan_no'
	group by w.sub_item_category_id,cost_price,receive_date order by receive_date DESC";

	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Model</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['quantity']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
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
		<td align=right></td>
		<td align=right nowrap=nowrap><strong>Total Qnt. : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Cost : ".number_format($totalcostprice,2)."</strong></td>
		</tr>
		</table>";
	return $speci;
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
   
function SelectBillType(){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT e.person_id, person_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on e.person_id=p.person_id 
			where e.branch_id=$branch_id order by person_name asc ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='person_id' size='1' id='person_id' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['person_id']."'>".$row['person_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
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



//============================= Income Statement ============================================
function IncomeStateSkin(){
  require_once(INCOME_STSTEMENT_GENERATOR_HEAD_SKIN);
}

function ShowIncomeStateSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $Fetch = $this->ShowIncomeStsteFetch($slfrom2, $slto2);
require_once(INCOME_STSTEMENT_HEAD_SKIN);
}


 function ShowIncomeStsteFetch($slfrom2, $slto2){
	 $sql = "SELECT
	branch_name,
	sum(paid_amount) as paid_amount,
	sum(ret_amount) as ret_amount
FROM
	inv_item_sales_payment s inner join branch b on b.branch_id=s.branch_id
	where sales_type=1 and s.branch_id!=6 and sales_date between '$slfrom2' and '$slto2' group by s.branch_id";

	//echo $sql;
	$res= mysql_query($sql);



	$speci = "<table width=600 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th width='53%' align=left nowrap=nowrap style=\"border:#000000 solid 1px\">Particulars</th>
				 <th width='41%' align=right nowrap=nowrap style=\"border:#000000 solid 1px\">Amount</th>
	       </tr>
		   <tr><td colspan='3' align='center'></td></tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>1. Sales</b></td></tr>
	<tr>
    <td width='6%' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'>&nbsp;</td>
    <td colspan='2' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>1.a. Retail Sales</strong> </td>
  </tr>";

                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$paid_amount=$row['paid_amount'];
	$ret_amount=$row['ret_amount'];
	$netSales=($paid_amount-$ret_amount);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['branch_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	$TotalretailSales = $TotalretailSales+$netSales;
	$i++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='2'><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalretailSales."</strong></td>
		</tr>
	  <tr>
    <td width='6%' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'>&nbsp;</td>
    <td colspan='2' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>1.b. Whole Sales </strong></td>
  </tr>";

//================================== For Whole Sale ==============================================================
	$sql2 = "SELECT
	branch_name,
	sum(dues_amount) as dues_amount,
	sum(paid_amount) as paid_amount,
	sum(ret_amount) as ret_amount
FROM
	inv_item_sales_payment s inner join branch b on b.branch_id=s.branch_id
	where sales_type=2 and sales_date between '$slfrom2' and '$slto2' group by s.branch_id";

	$res2= mysql_query($sql2);

                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$dues_amount=$row2['dues_amount'];
	$paid_amount2=$row2['paid_amount'];
	$ret_amount2=$row2['ret_amount'];
	$netSales2=($paid_amount2-$ret_amount2);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['branch_name']." (sales)</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
	$TotalretailSales2 = $TotalretailSales2+$netSales2;
	$i2++;
	}
	
/*	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='2'><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$TotalretailSales2."</strong></td>
		</tr>";*/

//================================== For Whole Sale Dues Collection==============================================================
	 $sql3 = "SELECT
	sum(cr_amount) as cr_amount,
	branch_name
FROM
	whole_saler_acc s inner join branch b on b.branch_id=s.branch_id
	where paid_date between '$slfrom2' and '$slto2' and invoice is NULL group by s.branch_id";

	$res3= mysql_query($sql3);

                 $i3=1;        $rowcolor=0;
	while($row3=mysql_fetch_array($res3)){

	$cr_amount=$row3['cr_amount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row3['branch_name']." (dues rec)</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($cr_amount,2)."</td>
		</tr>";
	
	$TotalDues = $TotalDues+$cr_amount;
	$i3++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='2'><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".($TotalDues+$TotalretailSales2)."</strong></td>
		</tr>
	  <tr><td colspan='3' align='center'></td></tr>

		<tr>
		<td colspan='3' align='left' height='30' style='border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>2. Income</b></td>
		</tr>";

//================================== For Income ==============================================================
	 $sql4 = "SELECT
	branch_name,
	sum(cr) as cr
FROM
	daily_acc_ledger s inner join branch b on b.branch_id=s.branch_id
	where cr!='0.00000' and expdate between '$slfrom2' and '$slto2' group by s.branch_id";

	//echo $sql;
	$res4= mysql_query($sql4);

      $i4=1;        $rowcolor=0;
	while($row4=mysql_fetch_array($res4)){

	$branch_name=$row4['branch_name'];
	$cr4=$row4['cr'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\"  onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$branch_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($cr4,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$cr4;
	
	$i4++;
	}
	
	$totalAmount1=($totalIncome+$TotalDues+$TotalretailSales2+$TotalretailSales);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan='2'><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan='2'><strong>Total Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalAmount1,2)."</strong></td>
		</tr>
		<tr><td colspan='3' align='center'></td></tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>3. Cost of Goods Sold</b></td></tr>
		<tr>
    <td width='6%' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'>&nbsp;</td>
    <td colspan='2' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>1.a. Supplier Payments </strong></td>
  </tr>";


//================================== For Supplier Bills ==============================================================
	 $sql5 = "SELECT
	company_name,
	sum(cr) as cr
FROM
	detail_account d inner join inv_supplier_info s on s.supplier_id=d.supplier_id
	where cr!='0.00000' and recdate between '$slfrom2' and '$slto2' group by d.supplier_id";

	//echo $sql;
	$res5= mysql_query($sql5);

      $i4=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$company_name=$row5['company_name'];
	$cr5=$row5['cr'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$company_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($cr5,2)."</td>
		</tr>";
	
	$totalCredit = $totalCredit+$cr5;
	
	$i4++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan='2' style=\"border-left:#000000 solid 1px\"><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalCredit,2)."</strong></td>
		</tr>
		<tr><td colspan='3' align='center'></td></tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>4. Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	$sql6 = "SELECT
	account_name,
	branch_name,
	sum(dr) as drAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	inner join branch b on b.branch_id=l.branch_id
	where dr!='0.00000' and l.branch_id!=6 and l.chart_id!='3' and expdate between '$slfrom2' and '$slto2' group by l.branch_id, l.chart_id";

	//echo $sql;
	$res6= mysql_query($sql6);

      $i6=1;        $rowcolor=0;
	while($row6=mysql_fetch_array($res6)){

	$branch_name6=$row6['branch_name'];
	$account_name=$row6['account_name'];
	$drAmount6=$row6['drAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name."&nbsp;&nbsp;|&nbsp;&nbsp;".$branch_name6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($drAmount6,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount6;
	
	$i5++;
	}
	
	$totalAmount2=($totalExpense+$totalCredit);	
	$netIncome=($totalAmount1-$totalAmount2);
	
	$sqlbnk="SELECT
	sum(deposit) as deposit,
	sum(withdrawal) as withdrawal	
FROM
	bank_trans";

	$resbnk= mysql_query($sqlbnk);
	$rowbnk=mysql_fetch_array($resbnk);
	
	$deposit=$rowbnk['deposit'];
	$withdrawal=$rowbnk['withdrawal'];
	
	$bankBalance=($deposit-$withdrawal);

//============== Acc Receibale===================
	$sqlAccRec="SELECT
	sum(dr_amount) as dr_amount,
	sum(cr_amount) as cr_amount
FROM
	whole_saler_acc";

	$resAccRec= mysql_query($sqlAccRec);
	$rowAccRec=mysql_fetch_array($resAccRec);
	
	$dr_AccRec=$rowAccRec['dr_amount'];
	$cr_AccRec=$rowAccRec['cr_amount'];
	
	$AccRecBalance=($dr_AccRec-$cr_AccRec);
	
//============== Acc Payable===================
	$sqlAccPay="SELECT
	sum(dr) as dr_acc_payble,
	sum(cr) as cr_acc_payble
	
FROM
	view_detail_account";

	$resAccPay= mysql_query($sqlAccPay);
	$rowAccPay=mysql_fetch_array($resAccPay);
	
	$dr_acc_payble=$rowAccPay['dr_acc_payble'];
	$cr_acc_payble=$rowAccPay['cr_acc_payble'];
	
	$AccPayBalance=($dr_acc_payble-$cr_acc_payble);
	
	
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=2><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=2><strong>Total Amount : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalAmount2,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=2><strong>Net Profit/Loss : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($netIncome,2)."</strong></td>
		</tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>5. Bank Balance</b></td></tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=2 style=\"border:#000000 solid 1px\"><strong>Total Bank Balance : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($bankBalance,2)."</strong></td>
		</tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>6. Accounts Receivable</b></td></tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=2 style=\"border:#000000 solid 1px\"><strong>Total Receivable Amounts : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($AccRecBalance,2)."</strong></td>
		</tr>
		<tr><td colspan='3' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>7. Accounts Payable</b></td></tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=2 style=\"border:#000000 solid 1px\"><strong>Total Payable Amounts : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($AccPayBalance,2)."</strong></td>
		</tr>
		
		</table>";
	return $speci;

}//end fnc






//============================= Sumamry Report ============================================
function SummaryReportSkin(){
require_once(SUMMARY_REPORT_GENERATOR_HEAD_SKIN);
}

function ShowSummaryReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $summary = $this->ShowSummaryReport($slfrom2, $slto2);

  require_once(SUMMARY_REPORT_HEAD_SKIN);
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
	sum(total_amount) as total_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and return_qnt='0' and sales_date between '$slfrom2' and '$slto2' 
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
	$item_qnt=$row['item_qnt'];
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
	 
	 //$netSales=(($total_amount)-($return_amount+$NwDisc));
	 $netSales=($total_amount-$NwDisc);

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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['item_qnt']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$row['item_qnt'];
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
	  <td  align=right nowrap=nowrap style=\"border-left:#000000 solid 1px;border-right:#000000 solid 1px;\"><strong>".$profit." (".($inPercnt)." %)</strong></td>
	  </tr>
	  <tr><td colspan='11' align='center'></td></tr>
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
	sum(total_amount) as total_amount
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and return_qnt='0' and sales_date between '$slfrom2' and '$slto2' 
	and s.branch_id=$branch_id and sales_type=2 group by itemcode";

	//echo $sql;
	$res2= mysql_query($sql2);

                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	//$return_amount=$row['return_amount'];
	$costprice2=$row2['costprice'];
	$item_qnt2=$row2['item_qnt'];
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

	 $sql3="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId2";
	 $res3= mysql_query($sql3);
	 $row3 = mysql_fetch_array($res3);
	 $totsale_pay_id3=$row3['totsale_pay_id'];
	 
	 $NwDisc2=($totaldiscount2/$totsale_pay_id3);
	 
	 //$netSales=(($total_amount)-($return_amount+$NwDisc));
	 $netSales2=($total_amount2-$NwDisc2);

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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['item_qnt']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($NwDisc2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
	//$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$row2['item_qnt'];
	$netDisc2 = $netDisc2+$NwDisc2;
	
	
	
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
	  <td align=right nowrap=nowrap style=\"border-left:#000000 solid 1px;border-bottom:#000000 solid 1px;border-right:#000000 solid 1px;\"><strong>".$profit2." (".($inPercnt2)." %)</strong></td>
	  </tr>
	  <tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Supplier Bills</b></td></tr>";

//================================== For Supplier Bills ==============================================================
	 $sql4 = "SELECT
	company_name,
	sum(cr) as cr
FROM
	detail_account d inner join inv_supplier_info s on s.supplier_id=d.supplier_id
	where cr!='0.00000' and d.branch_id=$branch_id and recdate between '$slfrom2' and '$slto2' group by d.supplier_id";

	//echo $sql;
	$res4= mysql_query($sql4);

      $i4=1;        $rowcolor=0;
	while($row4=mysql_fetch_array($res4)){

	$company_name=$row4['company_name'];
	$cr4=$row4['cr'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i4."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$company_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($cr4,2)."</td>
		</tr>";
	
	$totalCredit = $totalCredit+$cr4;
	
	$i4++;
	}
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalCredit,2)."</strong></td>
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
	where l.branch_id=$branch_id group by l.chart_id";

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
	
	$netProfit=($profit+$profit2);
	$netExp=($totalExpense+$totalCredit);
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


function GodownStockReportSkin(){
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
require_once(GODOWN_CURRENT_STOCK_REPORT); 
}  

function GodownStockReport(){
	$sub_item_category_id=getRequest('sub_item_category_id');
	$main_item_category_id=getRequest('main_item_category_id');
	$GodownStockReport = $this->GodownStockReportFetch($sub_item_category_id,$main_item_category_id);$cdate = SelectCDate();
	 require_once(GODOWN_CURRENT_STOCK_REPORT2); 
}  
  
function GodownStockReportFetch($sub_item_category_id=null,$main_item_category_id=null){
	
	if($main_item_category_id && !$sub_item_category_id){
	$sql="SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as distribut_quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	where  w.main_item_category_id=$main_item_category_id
	group by w.sub_item_category_id order by w.sub_item_category_id";	
	}
	if(!$main_item_category_id && $sub_item_category_id){
			$sql="SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as distribut_quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	where  w.sub_item_category_id=$sub_item_category_id
	group by w.sub_item_category_id order by w.sub_item_category_id";
		}
	if($main_item_category_id && $sub_item_category_id){
			$sql="SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as distribut_quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	where  w.sub_item_category_id=$sub_item_category_id and w.sub_item_category_id=$sub_item_category_id
	group by w.sub_item_category_id order by w.sub_item_category_id";
		}
	if(!$main_item_category_id && !$sub_item_category_id){
 $sql="SELECT
	ware_house_id,
	w.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	w.main_item_category_id,
	main_item_category_name,
	item_code,
	sum(quantity) as distribut_quantity,
	challan_no,
	receive_date
FROM
	ware_house w inner join inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	group by w.sub_item_category_id order by w.sub_item_category_id";
		}
			//echo $sql;
			$res= mysql_query($sql);


	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr  bgcolor='#003333'>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">S/L</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Model</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Current Stock</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Cost(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Sales(last)</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Total Sales</th>
	       </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$distribut_quantity=($row['distribut_quantity']);
	$sub_item_category_id=$row['sub_item_category_id'];
	
		$sql2c = "SELECT sum(stock_out) as stock_out FROM return_info where sub_item_category_id='$item_code' and return_purpose='Branch'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$stock_out = $row2c['stock_out'];


		$sql3c = "SELECT sum(quantity) as quantity2 FROM inv_iteminfo  where sub_item_category_id ='$sub_item_category_id' ";
		$res3c =mysql_query($sql3c);
		$row3c = mysql_fetch_array($res3c);
		$quantity3 = $row3c['quantity2'];
		
		$sql4c = "SELECT sum(stock_out) as stock_out2 FROM damage_info  where sub_item_category_id ='$sub_item_category_id' ";
		$res4c =mysql_query($sql4c);
		$row4c = mysql_fetch_array($res4c);
		$quantity4 = $row4c['stock_out2'];
		
		
		//$tqnt1c =($distribut_quantity-$quantity3)+$stock_out-$quantity4;
		
	$ToSum1=($distribut_quantity+$stock_out);
	$ToSum2=($quantity3+$quantity4);
	$tqnt1c =($ToSum1-$ToSum2);


	// ========Count unit Price ===========================
	$sqlUnitPrice="SELECT max(ware_house_id) as ware_house_idMX FROM ware_house  WHERE sub_item_category_id='$sub_item_category_id' ";
	//echo $sql;
	$resUnitPrice= mysql_query($sqlUnitPrice);
	while($rowUnitPrice=mysql_fetch_array($resUnitPrice)){
	$ware_house_idMX = $rowUnitPrice['ware_house_idMX'];
	}

	$sqlUnitPrice2="SELECT cost_price as cost_price, sales_price as sales_price FROM ware_house WHERE ware_house_id=$ware_house_idMX 
	and sub_item_category_id='$sub_item_category_id'";
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
		<a href='?app=inv_report_generator&cmd=currentStockReportSkin&sub_item_category_id=".$row['sub_item_category_id']."'>".$row['sub_item_category_name']."</a></td>
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
  <td colspan='3' align='right'><b>Total Qnt : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".$subTotal2." Pcs</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Cost : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotal1,2)." Taka</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>Net Sales : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($subTotalSales,2)." Taka</td>
  </tr>
  </table>";
	return $speci;

}//end fnc

function ItemDistributeReportSkin(){
 //$catList=$this->SelectItmCategory();
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
$Branch=$this->SelectBranch();
require_once(DISTRIBUTE_REPORT_GENERATOR_SKIN);

}

function ItemDistributeReport(){
 $rsfrom = formatDate4insert(getRequest('slfrom2'));
 $rsto = formatDate4insert(getRequest('slto2'));
 $Branch=$this->SelectBranch($branch_id);
 $sub_item_category_id=getRequest('sub_item_category_id');
 $main_item_category_id=getRequest('main_item_category_id');
 $branch_id=getRequest('branch_id');

 $ReportFetch = $this->ItemDistributeReportFetch($rsfrom, $rsto,$sub_item_category_id,$main_item_category_id,$branch_id);
  require_once(DISTRIBUTE_REPORT_SKIN);
}


 function ItemDistributeReportFetch($rsfrom, $rsto, $sub_item_category_id=null,$main_item_category_id=null, $branch_id=null){

if(!$sub_item_category_id && !$main_item_category_id && $rsfrom && $rsto && $branch_id){
	 $sql = "SELECT
	item_id,
	i.sub_item_category_id,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	item_code,
	quantity,
	receive_date,
	i.branch_id,
	branch_name
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id 
	where i.branch_id=$branch_id and receive_date between '$rsfrom' and '$rsto'
	group by i.sub_item_category_id,receive_date order by receive_date DESC";
}

if(!$sub_item_category_id && $main_item_category_id && $rsfrom && $rsto && $branch_id){
 
	$sql = "SELECT
	item_id,
	i.sub_item_category_id,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	item_code,
	quantity,
	receive_date,
	i.branch_id,
	branch_name
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	where i.main_item_category_id=$main_item_category_id and i.branch_id=$branch_id and receive_date between '$rsfrom' and '$rsto' 
	group by i.sub_item_category_id,receive_date order by receive_date DESC";
	}
	//echo $sql;
if($sub_item_category_id && $main_item_category_id && $rsfrom && $rsto && $branch_id){
 
	 $sql = "SELECT
	item_id,
	i.sub_item_category_id,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	item_code,
	quantity,
	receive_date,
	i.branch_id,
	branch_name
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	where i.main_item_category_id=$main_item_category_id and i.sub_item_category_id=$sub_item_category_id 
	and i.branch_id=$branch_id and receive_date between '$rsfrom' and '$rsto' 
	group by i.sub_item_category_id,receive_date order by receive_date DESC";
	}
if(!$sub_item_category_id && $main_item_category_id && $rsfrom && $rsto && !$branch_id){
 
	 $sql = "SELECT
	item_id,
	i.sub_item_category_id,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	item_code,
	quantity,
	receive_date,
	i.branch_id,
	branch_name
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id 
	where i.main_item_category_id=$main_item_category_id and receive_date between '$rsfrom' and '$rsto' 
	group by i.sub_item_category_id,receive_date order by receive_date DESC";
	}

if(!$sub_item_category_id && !$main_item_category_id && $rsfrom && $rsto && !$branch_id){
 
	 $sql = "SELECT
	item_id,
	i.sub_item_category_id,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	item_code,
	quantity,
	receive_date,
	i.branch_id,
	branch_name
FROM
	inv_iteminfo i inner join inv_item_category_sub s on s.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id 
	where receive_date between '$rsfrom' and '$rsto' 
	group by i.sub_item_category_id,receive_date order by receive_date DESC";
	}

	$res= mysql_query($sql);
	$speci = "<table width=1100 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Model</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Branch</th>
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">
		<a href=\"?app=stock_out&tqnt1c=$quantity&item_code=".$row['item_code']."&branch_id=".$row['branch_id']."\" 
		style=\"text-decoration:none; cursor:pointer\">".$row['sub_item_category_name']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['quantity']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['sales_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalSales."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['branch_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" >"._date($row['receive_date'])."</td>
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total Qnt. : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Cost : ".number_format($totalcostprice,2)."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong> Total Values : ".number_format($totalsalesprice,2)."</strong></td>
		<td nowrap=nowrap>&nbsp;</td>
		<td nowrap=nowrap>&nbsp;</td>
	</tr>
		</table>";
	return $speci;
}


function SelectBranch($branch = null){ 
		$sql="SELECT branch_id, branch_name FROM branch where branch_id!=5 ";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:150px' class=\"textBox\">";
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

function SelectSalesMan(){ 

		$sql="SELECT e.person_id, person_name, branch_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on e.person_id=p.person_id
		inner join branch b on e.branch_id=b.branch_id where user_group_id in(3,5) ORDER BY person_name";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='person_id' id='person_id' class=\"textBox\" style='width:300px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['person_id']."'>".$row['person_name']."->".$row['branch_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }

//============================== Whole Sales Total Dues ===============
function WholeSalesTotalDues(){
	$TotalDuesFetch = $this->WholeSalesTotalDuesFetch();
	 require_once(WHOLE_SALES_TOTAL_DUES_REPORT_SKIN); 
}  
  
 function WholeSalesTotalDuesFetch(){
			 $sql="SELECT
					whole_sales_accid,
					customer_id,
					store_name,
					dr_amount,
					cr_amount,
					district_name,
					upzila,
					branch_id,
					balance
				FROM
					view_whole_sales_acc_balance where balance!='0.00'
					group by customer_id order by store_name";
			//echo $sql;
			$res= mysql_query($sql);


	$html = '<table width="900" cellpadding="5" cellspacing="0" style="border-collapse:collapse">
	           <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">Customer</th>
	            <th   align="left">District</th>
	            <th   align="left">Thana</th>
				<th   align="right" nowrap>DR Amount</th>
				<th   align="right" nowrap>CR Amount</th>
				<th   align="right" nowrap>Balance</th>
           </tr>';
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$dr_amount=$row['dr_amount'];
	$cr_amount=$row['cr_amount'];
	$balance=$row['balance'];
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['store_name'].'</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['district_name'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['upzila'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['dr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['cr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['balance'],2).'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['store_name'].'</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['district_name'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['upzila'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['dr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['cr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;" align="right">'.number_format($row['balance'],2).'&nbsp;</td>
				   
				</tr>';
	
			  $rowcolor=0;
			  } 
	
		$i++; 
		
$totalDR=($totalDR+$dr_amount);		
$totalCR=($totalCR+$cr_amount);		
$totalBalance=($totalBalance+$balance);		
		}
	
$html .= "<tr>
  <td colspan='4' align='right'><b>Total : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($totalDR,2)."</td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($totalCR,2)."</td>
  <td  style='border:#000000 solid 1px;' align='right'>".number_format($totalBalance,2)." </td>
  </tr>
  </table>";
	return $html;

}//end fnc



 function WholeSaleDuesGeneSkin(){
 $Customer=$this->SelectCustomer();
require_once(WHOLE_SALES_DUES_GENERATOR_SKIN);

 }
 
 function SelectCustomer($branch = null){ 
		$sql="SELECT customer_ID, store_name FROM customer_info where branch_id='1' and customer_type='2' ";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='customer_ID' size='1' id='customer_ID' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['customer_ID'] == $branch){
			$branch_select .= "<option value='".$row['customer_ID']."' selected = 'selected'>".$row['store_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['customer_ID']."'>".$row['store_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 
 
  //=============================== Whole sales date wise dues ===========================================
  function WholeSaleDateWiseDuesSkin(){
 $Dues = $this->WholeSaleDateWiseDuesFetch();
  require_once(WHOLE_SALES_DATE_WISE_DUES_REPORT_SKIN);
}


 function WholeSaleDateWiseDuesFetch(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $slto2 = formatDate4insert(getRequest('slto2'));
 $customer_ID = getRequest('customer_ID');
 
 if($customer_ID){
	$sql = "SELECT
			sales_date,
			store_name,
			total_amount,
			paid_amount,
			dues_amount,
			total_discount,
			ret_amount
			
		FROM
			inv_item_sales_payment s inner join customer_info c on c.customer_id=s.customer_id
			 where sales_type=2 and s.customer_id='$customer_ID' and sales_date between '$slfrom2' and '$slto2'";
			 }else{
			$sql = "SELECT
					sales_date,
					store_name,
					total_amount,
					paid_amount,
					dues_amount,
					total_discount,
					ret_amount
					
				FROM
					inv_item_sales_payment s inner join customer_info c on c.customer_id=s.customer_id
					 where sales_type=2 and sales_date between '$slfrom2' and '$slto2'";
			 
			 }
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=1000 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Client</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Amount</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Paid Amount</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dues Amount</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['sales_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['total_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['paid_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['dues_amount'],2)."</td>
		</tr>";
		
	$totalAmount = $totalAmount+$row['total_amount'];
	$Totalpaid = $Totalpaid+$row['paid_amount'];
	$Totaldues = $Totaldues+$row['dues_amount'];

	$i++;
	}
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap colspan=\"3\" align=right> <strong>Total : </strong> </td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalAmount,2)."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($Totalpaid,2)."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($Totaldues,2)."</strong></td>
		</tr>
		</table>";
	return $speci;

}//end fnc


  //=============================== Whole sales dues collection ===========================================
  function WholeSaleDuesCollectSkin(){
 $DuesColec = $this->WholeSaleDuesCollectFetch();
  require_once(WHOLE_SALES_DUES_COLLECTION_REPORT_SKIN);
}


 function WholeSaleDuesCollectFetch(){
 $slfrom2 = formatDate4insert(getRequest('slfrom3'));
 $slto2 = formatDate4insert(getRequest('slto3'));
 $customer_ID = getRequest('customer_ID');
 
 if($customer_ID){
	$sql = "SELECT
			store_name,
			dr_amount,
			cr_amount,
			paid_date
		FROM
			whole_saler_acc s inner join customer_info c on c.customer_ID=s.customer_ID
			where s.customer_ID='$customer_ID' and paid_date between '$slfrom2' and '$slto2'";
			 }else{
			$sql = "SELECT
					store_name,
					dr_amount,
					cr_amount,
					paid_date
				FROM
					whole_saler_acc s inner join customer_info c on c.customer_ID=s.customer_ID
					where paid_date between '$slfrom2' and '$slto2'";
			 
			 }
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=500 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Client</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dues Collect</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['store_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['paid_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['cr_amount'],2)."</td>
		</tr>";
		
	$totalAmount = $totalAmount+$row['cr_amount'];

	$i++;
	}
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap colspan=\"3\" align=right> <strong>Total : </strong> </td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalAmount,2)."</strong></td>
		</tr>
		</table>";
	return $speci;

}//end fnc

   
 function ReturnReportGeneratrorSkin(){
$Branch=$this->SelectBranch();
require_once(RETURN_REPORT_GENERATOR_HEAD_SKIN);
}

function ReturnReportSkin(){
  $slfrom2 = formatDate4insert(getRequest('slfrom2'));
  $slto2 = formatDate4insert(getRequest('slto2'));//exit();

$branch_id = getRequest('branch_id');
 $Return = $this->ReturnItemReportReport();
 $Branch=$this->SelectBranch($branch_id);
 require_once(RETURN_STOCK_REPORT_HEAD);
}
 
 function ReturnItemReportReport(){

  $slfrom2 = formatDate4insert(getRequest('slfrom2'));
  $slto2 = formatDate4insert(getRequest('slto2'));//exit();
  $branch_id = getRequest('branch_id');

if($branch_id && $slfrom2 && $slto2){
	 $sql = "SELECT
	return_info_id,
	item_code,
	sum(stock_out) as stock_out,
	return_purpose,
	damage_date,
	item_name,
	cost_price,
	branch_name
FROM
	return_info r inner join branch b on b.branch_id=r.branch_id
	where damage_date between '$slfrom2' and '$slto2' and r.branch_id=$branch_id and return_purpose='Branch'
	group by item_code order by damage_date";
	}


if($slfrom2 && $slto2 && !$branch_id){
 
	$sql = "SELECT
	return_info_id,
	item_code,
	sum(stock_out) as stock_out,
	return_purpose,
	damage_date,
	item_name,
	cost_price,
	branch_name
FROM
	return_info r inner join branch b on b.branch_id=r.branch_id
	where damage_date between '$slfrom2' and '$slto2'  and return_purpose='Branch'
	group by item_code order by damage_date";
	}
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Purpose</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Branch</th>
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>".$row['return_purpose']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['branch_name']."</td>
		</tr>";
		
	$totalcostprice = $totalcostprice+$TotalCost;
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
  
  
function DamageRequestList(){
$Damage = $this->DamageRequestListfetch();
require_once(DAMAGE_REQUEST_LIST_HEAD);
}  

 function DamageRequestListfetch(){
	 $sql = "SELECT
	return_info_id,
	item_code,
	sum(stock_out) as stock_out,
	return_purpose,
	damage_date,
	item_name,
	cost_price,
	branch_name
FROM
	return_info r inner join branch b on b.branch_id=r.branch_id
	where return_purpose='Damage'
	group by item_code order by damage_date";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=800 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Quantity</th>
				 <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Purpose</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Branch</th>
       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		
		$stock_out=$row['stock_out'];
		$cost_price=$row['cost_price'];
		$item_code=$row['item_code'];
		
		$sql2c = "SELECT sum(stock_out) as quantity2 FROM damage_info where item_code ='$item_code' ";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		
		$quantity2 = $row2c['quantity2'];
		
		$tqnt1c =($stock_out-$quantity2);
		
		$TotalCost=($tqnt1c*$cost_price);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">
		<a href=\"?app=stock_out&quantity=".$tqnt1c."&item_code=".$row['item_code']."\" style=\"text-decoration:none; cursor:pointer\">".$row['item_code']."</a></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['cost_price']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$tqnt1c."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$TotalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>".$row['return_purpose']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['branch_name']."</td>
		</tr>";
		
/*	$totalcostprice = $totalcostprice+$TotalCost;
	$totalQnt = $totalQnt+$row['stock_out'];
*/	
	$i++;
	}
	
	
	$speci .= "</table>";
	return $speci;
 
 }//end fnc

function SelectSupplier($sup = null){ 
		$sql="SELECT supplier_id, company_name FROM ".INV_SUPPLIER_INFO_TBL." ORDER BY supplier_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='supplier_id' size='1' id='supplier_id' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['supplier_id'] == $sup){
					   $country_select .= "<option value='".$row['supplier_id']."' selected='selected'>".$row['company_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['supplier_id']."'>".$row['company_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }
   
 //============================= Daily Cash ========================================
function DailyCashGeneratrorSkin(){
$Branch=$this->SelectBranch();
require_once(DAILY_CASH_GENERATOR_HEAD_SKIN);
}

function DailyCashReportSkin(){
 $branch_id=getRequest('branch_id');
 
 $sql="Select branch_name from branch where branch_id=$branch_id";
 $res= mysql_query($sql);
 $row=mysql_fetch_array($res);
 $branch_name=$row['branch_name'];
 
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
 $DailyCash = $this->DailyCashReport($slfrom2,$branch_id);
 require_once(DAILY_CASH_BALANCE_SHEET_HEAD_SKIN);
}
 
 function DailyCashReport($slfrom2,$branch_id){
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
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date='$slfrom2' and pay_type='Cash'
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
	 
	 $netSales=(($total_amount)-($return_amount+$totaldiscount));
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> <strong>Profit : ".$profit." (".$inPercnt.")%</strong></td>
		</tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Whole Sales</b></td></tr>";

//================================== For whole sales ==============================================================
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
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date='$slfrom2' and pay_type='Cash'
	and s.branch_id=$branch_id and sales_type=2 group by itemcode";

	//echo $sql;
	$res2= mysql_query($sql2);


                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$return_amount2=$row2['return_amount'];
	$costprice2=$row2['costprice'];
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];
	 
	 $netSales2=(($total_amount2)-($return_amount2+$totaldiscount2));
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
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
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
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalSales2,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> <strong>Profit : ".$profit2." (".$inPercnt2.")%</strong></td>
		</tr>";
//================================== For whole sales Received ==============================================================
	$sqlRec = "SELECT
	sum(paid_amount) as paid_amount,
	sum(ret_amount) as ret_amount,
	sum(dues_amount) as dues_amount
	
FROM
	inv_item_sales_payment
	where sales_date='$slfrom2' and pay_type='Cash'	and branch_id=$branch_id and sales_type=2";

	//echo $sql;
	$resRec= mysql_query($sqlRec);

	while($rowRec=mysql_fetch_array($resRec)){

	$paid_amountRec=$rowRec['paid_amount'];
	$ret_amountRec=$rowRec['ret_amount'];
	$dues_amountRec=$rowRec['dues_amount'];
	}
	
	$WholeSalepaid=($paid_amountRec-$ret_amountRec);
	
	$wholeSalesDues=$dues_amountRec;//($TotalSales2-$WholeSalepaid);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> 
		<strong>Whole Sales Received Amount : ".number_format($WholeSalepaid,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> 
		<strong>Whole Sales Dues : ".number_format($wholeSalesDues,2)."</strong></td>
		</tr>";

//========================= Dues Receive ========================
	 $sqlduRec = "SELECT sum(cr_amount) as cr_amount FROM whole_saler_acc	
	 where paid_date='$slfrom2' and pay_type='Cash' and branch_id=$branch_id and invoice IS NULL";

	//echo $sql;
	$resduRec= mysql_query($sqlduRec);

	while($rowduRec=mysql_fetch_array($resduRec)){
	$cr_amountduRec=$rowduRec['cr_amount'];
	}

		
$speci .= "<tr><td colspan='11' align='right' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' ><b>&nbsp;Whole Sales Dues Received : ".number_format($cr_amountduRec,2)."</b></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' ><b>&nbsp;Cash Received</b></td></tr>";

	

//================================== For Cash Received ==============================================================
	 $sql5 = "SELECT
	account_name,
	cr as crAmount,
	particulars
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where  cr!='0.00000' and expdate='$slfrom2' and branch_id=$branch_id ";

	//echo $sql;
	$res5= mysql_query($sql5);

      $i5=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$account_name5=$row5['account_name'];
	$crAmount5=$row5['crAmount'];
	$particulars5=$row5['particulars'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$particulars5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=8>".number_format($crAmount5,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$crAmount5;
	
	$i5++;
	}
	
	//echo ($TotalSales+$WholeSalepaid+$cr_amountduRec+$totalIncome);
	
	  $TotalAmount=($TotalSales+$WholeSalepaid+$cr_amountduRec+$totalIncome);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>

	  <tr>
	  <td colspan='10' align='right' style=\"border-top:#000000 solid 1px\"><strong>Total Amount : </strong></td>
	  <td  align='right' style=\"border-top:#000000 solid 1px\"><strong>".number_format($TotalAmount,2)."<hr></strong></td>
	  </tr>
	  <tr><td colspan='11' align='center'>&nbsp;</td></tr>

		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	 $sql6 = "SELECT
	account_name,
	dr as drAmount,
	particulars
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where dr!='0.00000' and expdate='$slfrom2' and paytype='Cash' and branch_id=$branch_id";

	//echo $sql;
	$res6= mysql_query($sql6);

      $i6=1;        $rowcolor=0;
	while($row6=mysql_fetch_array($res6)){

	$account_name6=$row6['account_name'];
	$drAmount6=$row6['drAmount'];
	$particulars6=$row6['particulars'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$particulars6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=8>".number_format($drAmount6,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount6;
	
	$i6++;
	}
	
	$TotalProfite=($TotalAmount-$totalExpense);

//===================== =============Calculation of last Day ==============================================================

/*$sqlLstDay = "select DATE_SUB('".$slfrom2."', INTERVAL 1 DAY) as Last_Sales_Date";
$resLstDay= mysql_query($sqlLstDay);
$rowLstDay=mysql_fetch_array($resLstDay);
$Last_Sales_Date=$rowLstDay['Last_Sales_Date'];
*/
// ================= last day Sales =====================

	$sql7 = "SELECT sum(paid_amount) as paid_amount,sum(ret_amount) as ret_amount FROM
	inv_item_sales_payment where sales_date<'$slfrom2' and pay_type='Cash' and branch_id=$branch_id";

	//echo $sql;
	$res7= mysql_query($sql7);

	while($row7=mysql_fetch_array($res7)){
	$paid_amount7=($row7['paid_amount']-$row7['ret_amount']);
	}
	
// ================= last day dues reeceived =====================
$sqlduRec2 = "SELECT sum(cr_amount) as cr_amount FROM whole_saler_acc	
	 where branch_id=$branch_id and paid_date<'$slfrom2' and pay_type='Cash' and branch_id=$branch_id and invoice IS NULL";

	//echo $sql;
	$resduRec2= mysql_query($sqlduRec2);

	while($rowduRec2=mysql_fetch_array($resduRec2)){
	$cr_amountduRec2=$rowduRec2['cr_amount'];
	}
	
	
	
	//================================== Cash Received Last Date==============================================================
	 $sql9 = "SELECT sum(cr) as crAmount FROM daily_acc_ledger where  cr!='0.00000' and expdate<'$slfrom2' and branch_id=$branch_id";
	$res9= mysql_query($sql9);
	while($row9=mysql_fetch_array($res9)){
	$totalIncome9 = $row9['crAmount'];
	}
	
	$LastDayIncome=($paid_amount7+$totalIncome9+$cr_amountduRec2);

//================= Last Day Operating Cost =======================
 $sql10 = "SELECT sum(dr) as drAmount FROM daily_acc_ledger where dr!='0.00000' and expdate<'$slfrom2' and paytype='Cash' and branch_id=$branch_id";
 $res10= mysql_query($sql10);

	while($row10=mysql_fetch_array($res10)){
	$totalExpense10 = $row10['drAmount'];
	}
	
	$LastDayCash=($LastDayIncome-$totalExpense10);	
	

//=====================  END============================

	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Today's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalProfite,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Last Day's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($LastDayCash,2)."</strong></td>
		</tr>		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Cash in Hand : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format(($LastDayCash+$TotalProfite),2)."</strong></td>
		</tr>		
		</table>";
	return $speci;

}//end fnc
 
 
function SelectModel(){ 
$main_item_category_id=getRequest('main_item_category_id');

		 $sql="SELECT sub_item_category_id,	sub_item_category_name FROM inv_item_category_sub where main_item_category_id=$main_item_category_id order by sub_item_category_name";
	    $result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) {

	echo '<label style="font-weight:normal"><input name="sub_item_category_id" id="sub_item_category_id" type="radio" value="'.$row['sub_item_category_id'].'" />'.$row['sub_item_category_name'].'</label><br>';	
		}

}	
 
 function SalesWholeSaller(){ 

		 $sql="SELECT customer_id,store_name, name, branch_name from customer_info c inner join branch b on b.branch_id=c.branch_id ORDER BY name";
	    $result = mysql_query($sql);
		
	    $Supplier_select = "<select name='customer_id' id='customer_id' class=\"textBox\" style='width:300px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['customer_id']."'>".$row['name']."->".$row['branch_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }

    
} // End class

?>