<?php
class UserHomeApp 
{
	
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
	   case 'test6Jmu'           : echo $this->test6Jmu();   					break;
	   case 'DailyCahReportSkin'           : echo $this->DailyCahReportSkin();   					break;
	   case 'IMEISearchSales'           : $screen = $this->IMEISearchSales();   					break;
	   case 'IMEISearch'           : $screen = $this->IMEISearch();   					break;
		 case 'ajaxItemReportSkin'          : echo $this->ItemReportSkin();		 					break;
         default                   : $cmd = 'home'; $screen = $this->userhome();			break;
      }
  }
   
  function userhome()
  {
  	$salesmanwiseMonthSlas = $this->salesmanwiseMonthSlas();
	$TotalSupplier = $this->TotalSupplier();
	$OperatingCostDaily = $this->TodaysOperatingCostCash();
	$OperatingCostDaily2 = $this->TodaysOperatingCostCash2();
	$itemcode = getRequest('itemcode');
 	$selectItem = "SELECT
                item_id,
				sub_item_category_id
            FROM 
                inv_iteminfo where item_size = '$itemcode'";//exit();
                 
                 $resItem = mysql_query($selectItem);
                 $itemCnt = mysql_num_rows($resItem);
                  $rowItem = mysql_fetch_array($resItem);
                   $item_id = $rowItem['item_id'];
                   $sub_item_categoryId = $rowItem['sub_item_category_id'];

	$currentStockReport = $this->CurrentStockReportFetch($sub_item_categoryId,$item_id);
	$curdate=dateInputFormatDMY(SelectCDate());

     require_once(HOME_SKIN);
  }

 function CurrentStockReportFetch($sub_item_categoryId=null,$item_id=null){
 	if($sub_item_categoryId){
			 $sql="SELECT
				item_id,
				i.sub_item_category_id,
				sub_item_category_name,
				main_item_category_name,
				item_size,
				cost_price,
				sales_price,
				quantity
			FROM
				inv_iteminfo i 
				inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id
				where s.sub_item_category_id=$sub_item_category_id 
				and i.item_id not in(SELECT item_id FROM inv_item_sales WHERE sub_item_category_id=$sub_item_categoryId) order by i.item_size";
	}
			
			$res= mysql_query($sql);


	$speci = "<table width=700 cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse:collapse\">
	            <tr  bgcolor='#003333'>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">S/L</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Serial</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px;\">Cost</th>
		   </tr>";
	
		$i=1;
     $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$row['cost_price'];

	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
		if($tqnt1c!='0'){	 
			$speci .= "<tr  height=22>
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['main_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['item_size']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;\">".$row['cost_price']."</td>
		</tr>";
	}
		$i++;
	
	}
	

	$speci .= " </table>";
	return $speci;

 }	

function test6Jmu()
  {
    $sql="SELECT
	ware_house_id,
	sub_item_category_id,
	item_size,
	description,
	main_item_category_id,
	cost_price,
	sales_price,
	whole_sales_price,
	receive_date				
	FROM
		ware_house order by ware_house_id";
    $res=mysql_query($sql);
  
    while($row = mysql_fetch_array($res)){
    $ware_house_id=$row['ware_house_id'];
    $sub_item_category_id=$row['sub_item_category_id'];
    $item_size=$row['item_size'];
    $description=$row['description'];
    $main_item_category_id=$row['main_item_category_id'];
    $cost_price=$row['cost_price'];
    $sales_price=$row['sales_price'];
    $whole_sales_price=$row['whole_sales_price'];
    $receive_date=$row['receive_date'];
  
     $sql2="INSERT INTO inv_iteminfo(sub_item_category_id,main_item_category_id,cost_price,sales_price,whole_sales_price,quantity,receive_date,branch_id,item_size,description,createdby,ware_house_id)
values($sub_item_category_id,'$main_item_category_id','$cost_price','$sales_price','$whole_sales_price','1','$receive_date','18','$item_size','$description','ifran1','$ware_house_id')";

    $res2=mysql_query($sql2);
        if($res2){
        header('location:?app=userhome&msg=success');
        }else{
        header('location:?app=userhome&msg=Failed');
        }
    }
  }

function salesmanwiseMonthSlas(){
		$crmonth=date('Y-m');
				
		$sql="SELECT person_name, sum(total_amount) as total_sales FROM
					inv_item_sales_payment s inner join hrm_person p on p.person_id=s.person_id
					where sales_date like '%$crmonth%' group by s.person_id;";

	    $result = mysql_query($sql);
	$tab='<table width="100%" border="1" cellpadding="4" cellspacing="4" style="border-collapse:collapse;background-color:#FFFFFF;">
					<tr>
					  <td height="48" colspan="4" align="center" style="font-size:14px">Salesman Wise Monthly Sales (This month)</td>
	              </tr>
				  <tr>
				<td widtd="18" nowrap style="font-size:12px"><strong>S/L</strong></td>
	             <td widtd="172" align=left nowrp=nowrap style="font-size:12px"><strong>Name</strong></td>
	             <td widtd="118" align=right nowrap=nowrap style="font-size:12px"><strong>Total Sales</strong></td>
		   </tr>';
		   
		   $i=1;
		while($row = mysql_fetch_array($result)){
		$tab.='<tr>
				<td widtd="21" nowrap style="font-size:12px">'.$i.'</td>
	             <td widtd="172" align=left nowrp=nowrap style="font-size:12px">'.$row['person_name'].'</td>
	             <td widtd="118" align=right nowrap=nowrap style="font-size:12px">'.number_format($row['total_sales'],2).'</td>
		   </tr>';
		$i++;
		
		$otalSales=$otalSales+$row['total_sales'];
		}
		
		
		$tab.='<tr>
				<td widtd="21" nowrap colspan="2" style="font-size:12px"><strong>Total :</strong></td>
	             <td widtd="118" align=right nowrap=nowrap style="font-size:12px"><strong>'.number_format($otalSales,2).'</strong></td>
		   </tr></table>';
		return $tab;
}// EOF
  
function TotalSupplier(){

		$sql="SELECT count(supplier_ID) as supplier_ID FROM inv_supplier_info";

	    $result = mysql_query($sql);

		$row = mysql_fetch_array($result);

		return $supplier_ID= $row['supplier_ID'];
	}
 
 function TodaysOperatingCostCash(){
 $branch_id=getFromSession('branch_id');
 $CurrDate = date('Y-m-d');
 
 $sql6 = "SELECT sum(dr) as drAmount FROM daily_acc_ledger 
 where dr!='0.00000' and expdate='$CurrDate' and paytype='Cash' and branch_id=$branch_id";

	$res6= mysql_query($sql6);
    
	while($row6=mysql_fetch_array($res6)){
	$drAmount6=$row6['drAmount'];
	}
	
	return $drAmount6;
	
 }// Eof

 function TodaysOperatingCostCash2(){
 $branch_id=getFromSession('branch_id');
 $CurrDate = date('Y-m-d');
 
 $sql6 = "SELECT sum(dr) as drAmount FROM daily_acc_ledger 
 where dr!='0.00000' and expdate='$CurrDate' and paytype='Cash'";

	$res6= mysql_query($sql6);
    
	while($row6=mysql_fetch_array($res6)){
	$drAmount6=$row6['drAmount'];
	}
	
	return $drAmount6;
	
 }// Eof
 
  function IMEISearch()
  {
  $item_size = getRequest('item_size');
$sql="SELECT
	ware_house_id,
	sub_item_category_name,
	item_size,
	description,
	cost_price,
	cost_exp,
	sales_price,
	whole_sales_price,
	company_name,
	main_item_category_name,
	quantity,
	challan_no,
	receive_date

FROM
	ware_house w inner join  inv_item_category_sub s on s.sub_item_category_id=w.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=s.main_item_category_id 
	inner join inv_supplier_info i on i.supplier_id=w.supplier_id where item_size='$item_size'";

$ros = mysql_query($sql);
$res = mysql_fetch_array($ros);
		$sub_item_category_name=$res['sub_item_category_name'];
		$main_item_category_name=$res['main_item_category_name'];
		$item_size	=$res['item_size'];
		$cost_price	=$res['cost_price'];
		$sales_price=$res['sales_price'];
		$receive_date=formatDateDMY($res['receive_date']);
		$whole_sales_price=$res['whole_sales_price'];
		$challan_no=$res['challan_no'];
		$company_name=$res['company_name'];
		$quantity=$res['quantity'];

	require_once(IMEI_SEARCH_SKIN);
  }

 
  function IMEISearchSales()
  {
  $item_size = getRequest('item_size2');
 $branch_id = getFromSession('branch_id');
 
  $sqlf = "SELECT item_id FROM inv_iteminfo where item_size = '$item_size' and branch_id=$branch_id";//exit();
$resf = mysql_query($sqlf);
$rowf = mysql_fetch_array($resf); 
$item_id = $rowf['item_id'];  
             
  
 $sql="SELECT
	s.item_id,
	sub_item_category_name,
	item_size,
	sum(salesprice) as salesprice,
	person_name,
	main_item_category_name,
	sum(item_qnt) as item_qnt,
	invoice,
	sales_date,
	store_name as customer_name

FROM
	inv_item_sales s inner join inv_iteminfo i on s.item_id=i.item_id
	inner join  inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id 
	inner join hrm_person p on p.person_id=s.person_id
	inner join customer_info c on c.customer_id=s.customer_id where s.item_id='$item_id'";

$ros = mysql_query($sql);
$res = mysql_fetch_array($ros);
		$sub_item_category_name=$res['sub_item_category_name'];
		$main_item_category_name=$res['main_item_category_name'];
		$item_size	=$res['item_size'];
		$salesprice=$res['salesprice'];
		$sales_date=formatDateDMY($res['sales_date']);
		$invoice=$res['invoice'];
		$person_name=$res['person_name'];
		$item_qnt=$res['item_qnt'];
		$customer_name=$res['customer_name'];

	require_once(IMEI_SEARCH_SALES_SKIN);
  }



function DailyCahReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
// $slto2 = formatDate4insert(getRequest('slto2'));
 $Fetch = $this->DailyCahReportFetch($slfrom2);
require_once(DAILY_CASH_BALANCE_SHEET_REPORTS_SKIN);
}


 function DailyCahReportFetch($slfrom2){
 $branch_id = getFromSession('branch_id');

	 $sql = "SELECT paid_amount,invoice  FROM inv_item_sales_payment p inner join inv_item_sales s on s.sale_pay_id=p.sale_pay_id 
	 WHERE p.sales_date = '$slfrom2' AND p.customer_id IS NULL group by p.sale_pay_id";
	
	

	//echo $sql;
	$res= mysql_query($sql);



	$speci = "<table width=700 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th width=40 style=\"border:#000000 solid 1px;font-family:Verdana\">SL</th>
				 <th width=258 align=left nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">Customer</th>
	             <th width=251 align=left nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">Invoice</th>
	             <th colspan=2 align=left nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">Net Sales</th>
		   </tr>
		<tr><td colspan='5' align='center'></td></tr>
		<tr>
		  <td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Cash Sales</b></td>
		</tr>";

                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$paid_amount=$row['paid_amount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">Cash</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$row['invoice']."</td>
		<td colspan=2 align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".number_format($paid_amount,2)."</td>
		</tr>";
	
	$CashPaidAmount=$CashPaidAmount+$row['paid_amount'];
	$i++;
	}
	
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td colspan=2 align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($CashPaidAmount,2)."</strong></td>
		</tr>";

	
	$speci .= "<tr>
		  <td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;font-family:Verdana' >
		<b>&nbsp;Payment Received </b></td>
		</tr>";

//================================== For Dues Received ==============================================================

	 $sql5 = "SELECT store_name,sum(cr_amount) as cr_amount,particulars FROM whole_saler_acc l inner join customer_info c on c.customer_id=l.customer_id
	where paid_date='$slfrom2' and cr_amount!='0.00' group by l.customer_id";


	//echo $sql;
	$res5= mysql_query($sql5);

      $i5=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$store_name=$row5['store_name'];
	$cr_amount5=$row5['cr_amount'];
	$particulars5=$row5['particulars'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$i5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$store_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$particulars5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align=right colspan=2>".number_format($cr_amount5,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$cr_amount5;
	
	$i5++;
	}

	
	
	  $TotalAmount=($CashPaidAmount+$totalIncome);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=4><strong>Sub Total : </strong></td>
		<td width=398 align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>

	  <tr>
	  <td colspan='4' align='right' style=\"border-top:#000000 solid 1px;font-family:Verdana\"><strong>Total Cash Amount : </strong></td>
	  <td  align='right' style=\"border-top:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($TotalAmount,2)."<hr></strong></td>
	  </tr>
	  <tr><td colspan='5' align='center'>&nbsp;</td></tr>

		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;font-family:Verdana' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================

	$sql6 = "SELECT
	account_name,
	dr as drAmount,
	particulars
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where dr!='0.00000' and expdate='$slfrom2'";


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
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$i6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$account_name6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\">".$particulars6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\" align=right colspan=2>".number_format($drAmount6,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount6;
	
	$i6++;
	}

	$TotalProfite=($TotalAmount-$totalExpense);

//===================== Calculation of last Day ============================

/*$sqlLstDay = "select DATE_SUB('".$slfrom2."', INTERVAL 1 DAY) as Last_Sales_Date";
$resLstDay= mysql_query($sqlLstDay);
$rowLstDay=mysql_fetch_array($resLstDay);
$Last_Sales_Date=$rowLstDay['Last_Sales_Date'];
*/

	$sql7 = "SELECT
	sum(paid_amount) as paid_amount
	
FROM
	inv_item_sales_payment where sales_date<'$slfrom2' and customer_id IS NULL";
	//echo $sql;
	$res7= mysql_query($sql7);

	while($row7=mysql_fetch_array($res7)){
	$paid_amount7=($row7['paid_amount']);
	}
// ================= last day dues reeceived =====================


	 $sqlduRec2 = "SELECT sum(cr_amount) as cr_amount FROM whole_saler_acc	
	 where paid_date<'$slfrom2'";


	//echo $sql;
	$resduRec2= mysql_query($sqlduRec2);

	while($rowduRec2=mysql_fetch_array($resduRec2)){
	$cr_amountduRec2=$rowduRec2['cr_amount'];
	}
	
	
	
	
	$LastDayIncome=($paid_amount7+$cr_amountduRec2);

//================= Last Day Operating Cost =======================

 $sql10 = "SELECT sum(dr) as drAmount FROM	daily_acc_ledger where dr!='0.00000' and expdate<'$slfrom2' and paytype='Cash' and branch_id=$branch_id";
 $res10= mysql_query($sql10);

	while($row10=mysql_fetch_array($res10)){
	$totalExpense10 = $row10['drAmount'];
	}

	
	$LastDayCash=($LastDayIncome-$totalExpense10);	
	

//=====================  END============================

	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=4><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=4><strong>Today's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($TotalProfite,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=4><strong>Last Day's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format($LastDayCash,2)."</strong></td>
		</tr>		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=4><strong>Cash in Hand : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px;font-family:Verdana\"><strong>".number_format(($LastDayCash+$TotalProfite),2)."</strong></td>
		</tr>		
		</table>
	";
	return $speci;

}//end fnc


 function ItemReportSkin(){
   	$ItemReport = $this->ItemReport();
 require_once(ITEM_REPORT_SKIN);
 }
 function ItemReport(){
 
 $main_item_category_id=getRequest('main_item_category_id1');

if($main_item_category_id){
	 $sql = "SELECT sub_item_category_id, sub_item_category_name, s.main_item_category_id, main_item_category_name,s.createddate,s.createdby
	FROM ".INV_CATEGORY_SUB_TBL." s inner join ".INV_CATEGORY_MAIN_TBL." m on m.main_item_category_id=s.main_item_category_id
	where s.main_item_category_id=$main_item_category_id order by sub_item_category_name";
	
	}else{
	 $sql = "SELECT sub_item_category_id, sub_item_category_name, s.main_item_category_id, main_item_category_name,s.createddate,s.createdby
	FROM ".INV_CATEGORY_SUB_TBL." s inner join ".INV_CATEGORY_MAIN_TBL." m on m.main_item_category_id=s.main_item_category_id
	order by s.main_item_category_id";
	}
	
	//echo $sql;
	$res= mysql_query($sql);

		$speci = "<table width=700 cellpadding=\"5\" cellspacing=\"0\" border=1 align=center>
	            <tr>
				<th style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\">Product Name</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\">Category</th>
		   </tr>";
                 $i=1;      
	while($row=mysql_fetch_array($res)){
	
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\" align=center>".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana; font-size:14px\" align=center>".$row['main_item_category_name']."</td>
		</tr>";
		
	$i++;
	}
	
	

	$speci .= "</table>

";
	return $speci;
	
	
}//end fnc


   
} // End class

?>
