 <?php

class group_dashboard
{
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ReceivedSalesReport'  	: echo $this->ReceiveSalesReportSkin();				break;
         case 'list'                  	: $this->getList();                       			break;
         default                      	: $cmd == 'list'; $this->getList();	       			break;
      }
 }
function getList(){
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  

function ReceiveSalesReportSkin(){
 $rsfrom = formatDate4insert(getRequest('rsfrom'));
 $rsto = formatDate4insert(getRequest('rsto'));
 
  $ABKSales = $this->ABKSalesReport($rsfrom, $rsto);
 $ABKtotal = $this->ABKtotal($rsfrom, $rsto);
 
 $FviSales = $this->FviSalesReport($rsfrom, $rsto);
  $FviTotal = $this->FviTotal($rsfrom, $rsto);


 $DburSales = $this->DburSalesReport($rsfrom, $rsto);
 $Dburtotal = $this->Dburtotal($rsfrom, $rsto);

 $JohnSales = $this->JohnSalesReport($rsfrom, $rsto);
 $JohnTotal = $this->JohnTotal($rsfrom, $rsto);

 $SBSales = $this->SBSalesReport($rsfrom, $rsto);
 $SBtotal = $this->SBtotal($rsfrom, $rsto);

 $SuprSales = $this->SuprSalesReport($rsfrom, $rsto);
 $SuprTotal = $this->SuprTotal($rsfrom, $rsto);

 $SBSuprSales = $this->SBSuperSalesReport($rsfrom, $rsto);
 $SBSuperTotal = $this->SBSuperTotal($rsfrom, $rsto);
 //============== Total Profit ============
 

  require_once(GROUP_RECEIVED_SALES_REPORT);
}
 
 //========== abk sales ================
 function ABKSalesReport($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="	SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$res1= mysql_query($sql1);
	$res2= mysql_query($sql2);
	$res3= mysql_query($sql3);
	$res4= mysql_query($sql4);
	$res5= mysql_query($sql5);
	$res6= mysql_query($sql6);
	
	// ========Count unit Price ===========================
	  $sqlcUnitPrice="SELECT `unit_price` FROM `inv_receive_detail` WHERE `mst_receiv_id`=(select max(`mst_receiv_id`) from inv_receive_detail where `Item_ID`='C01')";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice);
	$rowcUnitPrice=mysql_fetch_array($rescUnitPrice);
	$Cunit_price = $rowcUnitPrice['unit_price'];
	// ========End Count unit Price ===========================


	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Sweetened Condensed Milk</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $issue_qnt1=$row1['issue_qnt'];
	 $total_qnt1=$row1['total_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($issue_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);
	 
	 $totalQnt1=$issue_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$issue_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 
	}
	 
	 $valance1=$subTotal1-$RsubTotal1;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Full Cream Milk Powder</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
	}
	
	 $valance2=$subTotal2-$RsubTotal2;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Tea</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
	}
	
	 $valance3=$subTotal3-$RsubTotal3;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Beverage</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
	}
	
	 $valance4=$subTotal4-$RsubTotal4;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Snacks</strong></td></tr>";
  	while($row5=mysql_fetch_array($res5)){
	$subunt5 =  substr($row5['measurementunit'], 0, 1);
	 $total_qnt5=$row5['issue_qnt'];
	 $unit_value5=$row5['unit_value'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 $issue_total_kg5=$row5['issue_total_kg'];
 	 $quantity5 = ($total_qnt5/$unit_value5);
	 $quantity_unit5  = floor($quantity5);
	 $quantityPcs5 = strrchr($quantity5, ".");
	 $quantity_pcs5 =  round($quantityPcs5*$unit_value5);
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row5['Item_Name']."(".$total_qnt5.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit5.$subunt5." - ".$quantity_pcs5."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt5,2)."</td>
  </tr>";
  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
	}
	
	 $valance5=$subTotal5-$RsubTotal5;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal5,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal5,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance5,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Candy</strong></td></tr>";
  	while($row6=mysql_fetch_array($res6)){
	$subunt6 =  substr($row6['measurementunit'], 0, 1);
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $unit_value6=$row6['unit_value'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	 $issue_total_kg6=$row6['issue_total_kg'];
 	 $quantity6 = ($total_qnt6/$unit_value6);
	 $quantity_unit6  = floor($quantity6);
	 $quantityPcs6 = strrchr($quantity6, ".");
	 $quantity_pcs6 =  round($quantityPcs6*$unit_value6);
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row6['Item_Name']."(".$total_qnt6.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit6.$subunt6." - ".$quantity_pcs6."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt6,2)."</td>
  </tr>";
  $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
	}
	
	 $valance6=$subTotal6-$RsubTotal6;
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $profit=$SalesSum-$RecvSum;

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal6,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal6,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance6,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 }//EOF

 
 
 // ========== abk profit count ============ 
 
function ABKtotal($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$res1= mysql_query($sql1);
	$res2= mysql_query($sql2);
	$res3= mysql_query($sql3);
	$res4= mysql_query($sql4);
	$res5= mysql_query($sql5);
	$res6= mysql_query($sql6);
	
	// ========Count unit Price ===========================
	  $sqlcUnitPrice="SELECT `unit_price` FROM `inv_receive_detail` WHERE `mst_receiv_id`=(select max(`mst_receiv_id`) from inv_receive_detail where `Item_ID`='C01')";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice);
	$rowcUnitPrice=mysql_fetch_array($rescUnitPrice);
	$Cunit_price = $rowcUnitPrice['unit_price'];
	// ========End Count unit Price ===========================


	$speci = "";
	
  	while($row1=mysql_fetch_array($res1)){
	 $issue_qnt1=$row1['issue_qnt'];
	 $total_qnt1=$row1['total_qnt'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 
	 $totalQnt1=$issue_qnt1*$Cunit_price;

	
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
 	 
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
	}
	

	
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
	}
	

  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
	}
	

  	while($row5=mysql_fetch_array($res5)){
	 $total_qnt5=$row5['issue_qnt'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;


  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
	}
	

  	while($row6=mysql_fetch_array($res6)){
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	 
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

 $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
	}
	
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $profit=$SalesSum-$RecvSum;

	$speci .=$profit;
	return $speci;
 }//EOF
 
 
 //===============================  FAVICOLE =====================================================
 function FviSalesReport($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//echo $sql;
		$link = dbFavi();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Poducts Group1</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subComm1 = $subComm1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subComm1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subComm1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Poducts Group2</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subComm2 = $subComm2+$total_comm2;
 
	}
	 
	 $valance2=$subTotal2-($RsubTotal2+$subComm2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subComm2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Poducts Group3</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subComm3 = $subComm3+$total_comm3;
	}
	
	 $valance3=$subTotal3-($RsubTotal3+$subComm3);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subComm3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Poducts Group4</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subComm4 = $subComm4+$total_comm4;
	}
	
	 $valance4=$subTotal4-($RsubTotal4+$subComm4);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4);
	 $CommSum=($subComm1+$subComm2+$subComm3+$subComm4);
	 $profit=$SalesSum-($RecvSum+$CommSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
 <tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subComm4,2)."</b></td>
  </tr>

  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Total Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($CommSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 }//EOF



//============== fvi total profit ======================

 function FviTotal($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//echo $sql;
		$link = dbFavi();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	

	$speci = "";
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subComm1 = $subComm1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subComm2 = $subComm2+$total_comm2;
 
	}
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;

  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subComm3 = $subComm3+$total_comm3;
	}
	
  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subComm4 = $subComm4+$total_comm4;
	}
	
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4);
	 $CommSum=($subComm1+$subComm2+$subComm3+$subComm4);
	 $profit=$SalesSum-($RecvSum+$CommSum);

	$speci .=$profit;
	return $speci;
 }



//=========== dabour total sales =================

function DburSalesReport($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="	SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbDabur();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Oil</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subcom1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Shampoo</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
	}
	
	 $valance2=$subTotal2-($RsubTotal2+$subcom2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Toothpaste</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
	}
	
	 $valance3=$subTotal3-($RsubTotal3+$subcom3);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Cream</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
	}
	
	 $valance4=$subTotal4-($RsubTotal4+$subcom4);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Others</strong></td></tr>";
  	while($row5=mysql_fetch_array($res5)){
	$subunt5 =  substr($row5['measurementunit'], 0, 1);
	 $total_qnt5=$row5['issue_qnt'];
	 $unit_value5=$row5['unit_value'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 $issue_total_kg5=$row5['issue_total_kg'];
 	 $quantity5 = ($total_qnt5/$unit_value5);
	 $quantity_unit5  = floor($quantity5);
	 $quantityPcs5 = strrchr($quantity5, ".");
	 $quantity_pcs5 =  round($quantityPcs5*$unit_value5);
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row5['Item_Name']."(".$total_qnt5.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit5.$subunt5." - ".$quantity_pcs5."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt5,2)."</td>
  </tr>";
  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
	}
	
	 $valance5=$subTotal5-($RsubTotal5+$subcom5);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal5,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal5,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom5,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance5,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>total Commission: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($ComSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 }


//========= Dabour Total Profit ===============================================
function Dburtotal($rsfrom, $rsto){
	
	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbDabur();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	

	$speci = "";
	
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;


  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
	}
	
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 

  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
	}
	
  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
	}
	
  	while($row5=mysql_fetch_array($res5)){
	 $total_qnt5=$row5['issue_qnt'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
	}
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .=$profit;
	return $speci;
 }



//================= Johnson Sales ============
function JohnSalesReport($rsfrom, $rsto){

	// =======================for cindense milk =================================
		   $sql1="	SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbJohnson();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	$res6= mysql_query($sql6,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Oil</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subcom1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Lotion</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
	 $valance2=$subTotal2-($RsubTotal2+$subcom2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Cream</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
	 $valance3=$subTotal3-($RsubTotal3+$subcom3);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Soap</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}
	 
	 $valance4=$subTotal4-($RsubTotal4+$subcom4);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Powder</strong></td></tr>";
  	while($row5=mysql_fetch_array($res5)){
	$subunt5 =  substr($row5['measurementunit'], 0, 1);
	 $total_qnt5=$row5['issue_qnt'];
	 $unit_value5=$row5['unit_value'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 $issue_total_kg5=$row5['issue_total_kg'];
 	 $quantity5 = ($total_qnt5/$unit_value5);
	 $quantity_unit5  = floor($quantity5);
	 $quantityPcs5 = strrchr($quantity5, ".");
	 $quantity_pcs5 =  round($quantityPcs5*$unit_value5);
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row5['Item_Name']."(".$total_qnt5.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit5.$subunt5." - ".$quantity_pcs5."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt5,2)."</td>
  </tr>";
  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
 
	}
	 
	 $valance5=$subTotal5-($RsubTotal5+$subcom5);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal5,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal5,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom5,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance5,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Shampoo</strong></td></tr>";
  	while($row6=mysql_fetch_array($res6)){
	$subunt6 =  substr($row6['measurementunit'], 0, 1);
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $unit_value6=$row6['unit_value'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	 $issue_total_kg6=$row6['issue_total_kg'];
 	 $quantity6 = ($total_qnt6/$unit_value6);
	 $quantity_unit6  = floor($quantity6);
	 $quantityPcs6 = strrchr($quantity6, ".");
	 $quantity_pcs6 =  round($quantityPcs6*$unit_value6);
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row6['Item_Name']."(".$total_qnt6.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit6.$subunt6." - ".$quantity_pcs6."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt6,2)."</td>
  </tr>";
  $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
 $subcom6 = $subcom6+$total_comm6;
 
	}
	 
	 $valance6=$subTotal6-($RsubTotal6+$subcom6);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5+$subcom6);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal6,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal6,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom6,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance6,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Total Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($ComSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 
}
 
//==================== Johnson Total Profit  ================================= 
function JohnTotal($rsfrom, $rsto){

	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbJohnson();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	$res6= mysql_query($sql6,$link);
	

	$speci = "";
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}

  	while($row5=mysql_fetch_array($res5)){
	 $total_qnt5=$row5['issue_qnt'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
 
	}

  	while($row6=mysql_fetch_array($res6)){
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

  $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
 $subcom6 = $subcom6+$total_comm6;
 
	}
	 
	 $valance6=$subTotal6-($RsubTotal6+$subcom6);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5+$subcom6);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .=$profit;
	return $speci;
 
}
 
 
  //===============================  SB Distribution total sales=====================================================
 function SBSalesReport($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	

		
	//echo $sql;
		$link = dbSB();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Foog Body Spray</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subcom1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Face Powder</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
	 $valance2=$subTotal2-($RsubTotal2+$subcom2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Others</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
	 $valance3=$subTotal3-($RsubTotal3+$subcom3);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3);
	 $ComSum=($subcom1+$subcom2+$subcom3);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
 <tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom3,2)."</b></td>
  </tr>

  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Total Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($ComSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 }//EOF
 
 
 //=============== SB Total Profit==================
  function SBtotal($rsfrom, $rsto){

	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	
	//echo $sql;
		$link = dbSB();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	

	$speci = "";
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}

  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3);
	 $ComSum=($subcom1+$subcom2+$subcom3);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .=$profit;
	return $speci;
 }
 
  
 // ================== Super shop total sales===========================
 function SuprSalesReport($rsfrom, $rsto){

	// =======================for cindense milk =================================
		   $sql1="	SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbGroup();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	$res6= mysql_query($sql6,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Oil</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subcom1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Shampoo</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
	 $valance2=$subTotal2-($RsubTotal2+$subcom2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Toothpaste</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
	 $valance3=$subTotal3-($RsubTotal3+$subcom3);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Honey</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}
	 
	 $valance4=$subTotal4-($RsubTotal4+$subcom4);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Cream</strong></td></tr>";
  	while($row5=mysql_fetch_array($res5)){
	$subunt5 =  substr($row5['measurementunit'], 0, 1);
	 $total_qnt5=$row5['issue_qnt'];
	 $unit_value5=$row5['unit_value'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 $issue_total_kg5=$row5['issue_total_kg'];
 	 $quantity5 = ($total_qnt5/$unit_value5);
	 $quantity_unit5  = floor($quantity5);
	 $quantityPcs5 = strrchr($quantity5, ".");
	 $quantity_pcs5 =  round($quantityPcs5*$unit_value5);
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row5['Item_Name']."(".$total_qnt5.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit5.$subunt5." - ".$quantity_pcs5."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost5,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt5,2)."</td>
  </tr>";
  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
 
	}
	 
	 $valance5=$subTotal5-($RsubTotal5+$subcom5);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal5,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal5,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom5,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance5,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Others</strong></td></tr>";
  	while($row6=mysql_fetch_array($res6)){
	$subunt6 =  substr($row6['measurementunit'], 0, 1);
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $unit_value6=$row6['unit_value'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	 $issue_total_kg6=$row6['issue_total_kg'];
 	 $quantity6 = ($total_qnt6/$unit_value6);
	 $quantity_unit6  = floor($quantity6);
	 $quantityPcs6 = strrchr($quantity6, ".");
	 $quantity_pcs6 =  round($quantityPcs6*$unit_value6);
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row6['Item_Name']."(".$total_qnt6.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit6.$subunt6." - ".$quantity_pcs6."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost6,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt6,2)."</td>
  </tr>";
  $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
 $subcom6 = $subcom6+$total_comm6;
 
	}
	 
	 $valance6=$subTotal6-($RsubTotal6+$subcom6);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5+$subcom6);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal6,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal6,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom6,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance6,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Total Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($ComSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 
 }

//============ Super shop total Profit==================
 function SuprTotal($rsfrom, $rsto){

	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$link=dbGroup();
	
	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	$res5= mysql_query($sql5,$link);
	$res6= mysql_query($sql6,$link);
	

	$speci = "";
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}
	 
  	while($row5=mysql_fetch_array($res5)){
	 $total_qnt5=$row5['issue_qnt'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID5' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

	 $TotalQnt5=$total_qnt5*$sunit_price;

  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
 $subcom5 = $subcom5+$total_comm5;
 
	}
	 
  	while($row6=mysql_fetch_array($res6)){
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID6' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================


	 $TotalQnt6=$total_qnt6*$cdunit_price;

  $subTotal6 = $subTotal6+($issu_total_cost6);
  $RsubTotal6 = $RsubTotal6+$TotalQnt6;
 $subcom6 = $subcom6+$total_comm6;
 
	}
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4+$RsubTotal5+$RsubTotal6);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4+$subcom5+$subcom6);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .=$profit;
	return $speci;
 
 }

/// ================ SB Distribution super shop Sales========================

function SBSuperSalesReport($rsfrom, $rsto){		
	// =======================for cindense milk =================================
		 $sql1="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			i.Item_Name,
			iis.Item_ID,
			i.unit_value,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			i.Item_Name,
			i.unit_value,
			iis.Item_ID,
			ms.measurementunit,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost,
			sum(issue_total_kg) as issue_total_kg
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			inner join inv_item_measurement ms on ms.mesure_id = i.mesure_id
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//echo $sql;
		$link = dbSBsuper();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	

	$speci = "<table width='700' border='0' style=\"border-collapse:collapse\">
  <tr>
    <th style=\"border:#000000 solid 1px;\">Total Pcs</th>
   <th style=\"border:#000000 solid 1px;\">In Pack</th>
    <th style=\"border:#000000 solid 1px;\">Total Kg</th>
    <th style=\"border:#000000 solid 1px;\">Sales Values</th>
    <th style=\"border:#000000 solid 1px;\">Received Values</th>
  </tr>";
	$speci .="<tr><td colspan='5' align='center'></td></tr>
				<tr><td align='left' colspan='5' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;' ><strong>Foog Body Spray</strong></td></tr>";
  	while($row1=mysql_fetch_array($res1)){
	$subunt1 =  substr($row1['measurementunit'], 0, 1);
	 $total_qnt1=$row1['issue_qnt'];
	 $unit_value1=$row1['unit_value'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];
	 $issue_total_kg1=$row1['issue_total_kg'];
 	 $quantity1 = ($total_qnt1/$unit_value1);
	 $quantity_unit1  = floor($quantity1);
	 $quantityPcs1 = strrchr($quantity1, ".");
	 $quantity_pcs1 =  round($quantityPcs1*$unit_value1);

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row1['Item_Name']."(".$total_qnt1.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit1.$subunt1." - ".$quantity_pcs1."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost1,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($totalQnt1,2)."</td>
  </tr>";
 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	 $valance1=$subTotal1-($RsubTotal1+$subcom1);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal1,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom1,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance1,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
		<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Face Powder</strong></td></tr>";
  	while($row2=mysql_fetch_array($res2)){ 
	$subunt2 =  substr($row2['measurementunit'], 0, 1);
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $unit_value2=$row2['unit_value'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 $issue_total_kg2=$row2['issue_total_kg'];
 	 $quantity2 = ($total_qnt2/$unit_value2);
	 $quantity_unit2  = floor($quantity2);
	 $quantityPcs2 = strrchr($quantity2, ".");
	 $quantity_pcs2 =  round($quantityPcs2*$unit_value2);
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

	$speci .=" <tr height=22>
			<td style='border:#000000 solid 1px;'>".$row2['Item_Name']."(".$total_qnt2.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit2.$subunt2." - ".$quantity_pcs2."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost2,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt2,2)."</td>
  </tr>";
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
	 $valance2=$subTotal2-($RsubTotal2+$subcom2);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal2,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom2,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance2,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Begin Hair Color</strong></td></tr>";
  	while($row3=mysql_fetch_array($res3)){
	$subunt3 =  substr($row3['measurementunit'], 0, 1);
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $unit_value3=$row3['unit_value'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	 $issue_total_kg3=$row3['issue_total_kg'];
 	 $quantity3 = ($total_qnt3/$unit_value3);
	 $quantity_unit3  = floor($quantity3);
	 $quantityPcs3 = strrchr($quantity3, ".");
	 $quantity_pcs3 =  round($quantityPcs3*$unit_value3);

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row3['Item_Name']."(".$total_qnt3.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit3.$subunt3." - ".$quantity_pcs3."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost3,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt3,2)."</td>
  </tr>";
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
	 $valance3=$subTotal3-($RsubTotal3+$subcom3);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal3,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal3,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom3,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance3,2)."</b></td>
  </tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
				<tr><td colspan='5' align='left' height='30' style='border-top:#000000 solid 1px;border-right:#000000 solid 1px;'><strong>Others</strong></td></tr>";
  	while($row4=mysql_fetch_array($res4)){
	 $subunt4 =  substr($row4['measurementunit'], 0, 1);
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $unit_value4=$row4['unit_value'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 $issue_total_kg4=$row4['issue_total_kg'];
 	 $quantity4 = ($total_qnt4/$unit_value4);
	 $quantity_unit4  = floor($quantity4);
	 $quantityPcs4 = strrchr($quantity4, ".");
	 $quantity_pcs4 =  round($quantityPcs4*$unit_value4);
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

	$speci .=" <tr>
			<td style='border:#000000 solid 1px;'>".$row4['Item_Name']."(".$total_qnt4.")</td>
			<td style='border:#000000 solid 1px;'>".$quantity_unit4.$subunt4." - ".$quantity_pcs4."P</td>
			<td style='border:#000000 solid 1px;'>".number_format($issue_total_kg4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($issu_total_cost4,2)."</td>
			<td style='border:#000000 solid 1px;' align='right'>".number_format($TotalQnt4,2)."</td>
  </tr>";
  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}
	 
	 $valance4=$subTotal4-($RsubTotal4+$subcom4);
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .="<tr>
  <td  align='right' colspan='3'><b>Sub Total : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($subTotal4,2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format($RsubTotal4,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($subcom4,2)."</b></td>
  </tr>
 <tr>
  <td  align='right' colspan='3'><b>Balance : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($valance4,2)."</b></td>
  </tr>
  
  	<tr><td colspan='5' align='center' height=35></td></tr>
	<tr><td colspan='5' align='right' height='35'>&nbsp;</td></tr>
	<tr>
  <td  align='right' colspan='3'><b>Total Values : </b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($subTotal1+$subTotal2+$subTotal3+$subTotal4),2)."</b></td>
  <td  style='border:#000000 solid 1px;' align='right'><b>".number_format(($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4),2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Total Commission : </b> &nbsp;</td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($ComSum,2)."</b></td>
  </tr>
  <tr>
  <td  align='right' colspan='3'><b>Profit: </b></td>
  <td  style='border:#000000 solid 1px;' align='center' colspan='2'><b>".number_format($profit,2)."</b></td>
  </tr>
  </table>";
	return $speci;
 
 }
  
// =============== Sb Super shop Total Profit=======
function SBSuperTotal($rsfrom, $rsto){		
		
	// =======================for cindense milk =================================
		 $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt)as issue_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			inv_item_issue_sub iis inner join inv_item_issue_main  im on im.issue_id= iis.issue_id
			inner join inv_iteminfo i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//echo $sql;
		$link = dbSBsuper();

	$res1= mysql_query($sql1,$link);
	$res2= mysql_query($sql2,$link);
	$res3= mysql_query($sql3,$link);
	$res4= mysql_query($sql4,$link);
	

	$speci = "";
  	while($row1=mysql_fetch_array($res1)){
	 $total_qnt1=$row1['issue_qnt'];
	 $Item_ID1=$row1['Item_ID'];
	 $issu_total_cost1=$row1['issu_total_cost'];
	 $total_comm1=$row1['total_comm'];

	 // ========Count unit Price ===========================
	$sqlcUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID1'  group by Item_ID";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice,$link);
	while($rowcUnitPrice=mysql_fetch_array($rescUnitPrice)){
	$mxreceive_dtlid1 = $rowcUnitPrice['receive_dtlid'];
	}

	$sqlcUnitPrice1="SELECT unit_price as Cunit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID1' and
	receive_dtlid=$mxreceive_dtlid1 group by Item_ID";
	//echo $sql;
	$rescUnitPrice1= mysql_query($sqlcUnitPrice1,$link);
	while($rowcUnitPrice1=mysql_fetch_array($rescUnitPrice1)){
	$Cunit_price = $rowcUnitPrice1['Cunit_price'];
	}
	// ========End Count unit Price ===========================

	 
	 $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$totalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID2'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE Item_ID='$Item_ID2' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	// ========End Count unit Price ===========================

$TotalQnt2=$total_qnt2*$punit_price;

  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
 $subcom2 = $subcom2+$total_comm2;
 
	}
	 
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_ID3' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================
	  $TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
 $subcom3 = $subcom3+$total_comm3;
 
	}
	 
  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_ID4' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================
	 
	 $TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
 $subcom4 = $subcom4+$total_comm4;
 
	}
	 
	 
	 $SalesSum=($subTotal1+$subTotal2+$subTotal3+$subTotal4);
	 $RecvSum=($RsubTotal1+$RsubTotal2+$RsubTotal3+$RsubTotal4);
	 $ComSum=($subcom1+$subcom2+$subcom3+$subcom4);
	 $profit=$SalesSum-($RecvSum+$ComSum);

	$speci .=$profit;
	return $speci;
 
 } 
  
} // End class

?>