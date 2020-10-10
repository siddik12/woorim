 <?php

class summary_report
{
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		// case 'ReceivedSalesReport'  	: echo $this->ReceiveSalesReportSkin();				break;
         case 'list'                  	: $this->getList();                       			break;
         default                      	: $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
/*function getList(){
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
*/  

function getList(){
// $rsfrom = formatDate4insert(getRequest('smfrom'));
 $rsto = formatDate4insert(getRequest('smto'));
$rsfrom=substr($rsto,0,7).'-01';
	
 $ABKStckValu = $this->ABKstockValues($rsto);
 $ABKdues = $this->ABKdues($rsto);
 $ABKPendingBill = $this->ABKPendingBill();
 $ABKglAcc = $this->ABKglAcc($rsto);
 $ABKtotal = $this->ABKtotal($rsfrom, $rsto);
 
 $Adjustment = $this->AdjustmentAccounts($rsto);
 
 $ABKFixedAssets = $this->ABKFixedAssets();
 $ABKDailyExpense = $this->ABKDailyExpense($rsfrom, $rsto);
 
 
 $FviTotal = $this->FviTotal($rsfrom, $rsto);
 $FviStckValu = $this->FvistockValues($rsto);
 $Fvidues = $this->Fvidues($rsto);
 $FviPendingBill = $this->FviPendingBill();
 $FviglAcc = $this->FviglAcc($rsto);

 
  $Dburtotal = $this->Dburtotal($rsfrom, $rsto);
  $DburStckValu = $this->DburstockValues($rsto);
  $Dburdues = $this->Dburdues($rsto);
  $DburPendingBill = $this->DburPendingBill();
  $DburglAcc = $this->DburglAcc($rsto);
 
  $JohnTotal = $this->JohnTotal($rsfrom, $rsto);
  $JohnStckValu = $this->JohnstockValues($rsto);
  $Johndues = $this->Johndues($rsto);
  $JohnPendingBill = $this->JohnPendingBill();
  $JohnglAcc = $this->JohnglAcc($rsto);

 $SBtotal = $this->SBtotal($rsfrom, $rsto);
 $SBStckValu = $this->SBstockValues($rsto);
 $SBdues = $this->SBdues($rsto);
 $SBPendingBill = $this->SBPendingBill();
 $SBglAcc = $this->SBglAcc($rsto);

 $SuprTotal = $this->SuprTotal($rsfrom, $rsto);
 $SuprStckValu = $this->SuprstockValues($rsto);
 $Suprdues = $this->Suprdues($rsto);
 $SuprPendingBill = $this->SuprPendingBill();
 $SuprglAcc = $this->SuprglAcc($rsto);

 $SBSuperTotal = $this->SBSuperTotal($rsfrom, $rsto);
 $SBSuprStckValu = $this->SBSuprstockValues($rsto);
 $SBSuprdues = $this->SBSuprdues($rsto);
 $SBSuprPendingBill = $this->SBSuprPendingBill();
 $SBSuprglAcc = $this->SBSuprglAcc($rsto);


  require_once(CURRENT_APP_SKIN_FILE);
}
 
  //========== ABK Stock Values ================
 function ABKstockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 1 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$resc= mysql_query($sqlc);
	
// ========Count unit Price ===========================
	  $sqlcUnitPrice="SELECT `unit_price` FROM ".INV_RECEIVE_DETAIL_TBL." WHERE `mst_receiv_id`=(select max(`mst_receiv_id`) from ".INV_RECEIVE_DETAIL_TBL." where `Item_ID`='C01')";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice);
	$rowcUnitPrice=mysql_fetch_array($rescUnitPrice);
	$unit_price = $rowcUnitPrice['unit_price'];
	// ========End Count unit Price ===========================
	
	$speci = "";

	while($rowc=mysql_fetch_array($resc)){
		$totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	$sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
		 
  $subTotal1 = $subTotal1+($tqnt1c*$unit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 2 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp);
	
	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto'";
	$res2p =mysql_query($sql2p);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 

		// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as receive_dtlid FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDp'  group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['receive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDp' and
	receive_dtlid=$mxreceive_dtlid group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal2 = $subTotal2+($tqnt1p*$punit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 3 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt);
	
	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto'";
	$res2t =mysql_query($sql2t);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDt' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDt'
	group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$tunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 4 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb);
	
	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDb'  and m.issue_date <= '$rsto'";
	$res2b =mysql_query($sql2b);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;
	 
	
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$bunit_price);
	
	}//===================== End Baverage ===========================================
	$sqls="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 5 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$ress= mysql_query($sqls);

	while($rows=mysql_fetch_array($ress)){
		 $totalpcss = $rows['totalpcs'];
		 $Item_IDs = $rows['Item_ID'];

 	 $sql2s = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDs'  and m.issue_date <= '$rsto'";
	$res2s =mysql_query($sql2s);
	$row2s = mysql_fetch_array($res2s);

	$issue_total_qnts = $row2s['total_qnt'];
	$tqnt1s = $totalpcss-$issue_total_qnts;
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal5 = $subTotal5+($tqnt1s*$sunit_price);
	
	}//===================== End snacks ===========================================
	$sqlcd="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs
			 FROM ".INV_RECEIVE_DETAIL_TBL." rd  
			 inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
			 inner join ".INV_RECEIVE_MASTER_TBL." rm on rm.mst_receiv_id = rd.mst_receiv_id 
			 inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id
			 where i.Item_Category_ID = 6 AND rm.receive_date <= '$rsto' group by rd.Item_ID";
		
	//echo $sql;
	$rescd= mysql_query($sqlcd);
	
	while($rowcd=mysql_fetch_array($rescd)){
		 $totalpcscd = $rowcd['totalpcs'];
		 $Item_IDcd = $rowcd['Item_ID'];

 	 $sql2cd = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM ".INV_ITEM_ISSUE_SUB_TBL." sb 
		inner join 	".INV_ITEM_ISSUE_MAIN_TBL." m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDcd'  and m.issue_date <= '$rsto'";
	$res2cd =mysql_query($sql2cd);
	$row2cd = mysql_fetch_array($res2cd);

	$issue_total_qntcd = $row2cd['total_qnt'];
	$tqnt1cd = $totalpcscd-$issue_total_qntcd;
	 
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM ".INV_RECEIVE_DETAIL_TBL."  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================
	
  $subTotal6 = $subTotal6+($tqnt1cd*$cdunit_price);
	}//===================== End Candy ===========================================
	
	$totalSubTotal=($subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6);
	
	$speci .=$totalSubTotal ;
	return $speci;

}//EOF

// ===================== ABK Dues ================
 function ABKdues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$res= mysql_query($sql);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== ABK Pending bill ================
 function ABKPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM ".INV_PENDING_BILL_TBL."  where ststus='Pending' ";
 
 	$speci = "";
	$res= mysql_query($sql);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}


 // ===================== Fixed Assets ================
 function ABKFixedAssets(){
  $sql="SELECT sum(dr) as amount FROM ".FIXED_ASSET_ENTRY_TBL." ";
 
 	$speci = "";
	$res= mysql_query($sql);
  	while($row=mysql_fetch_array($res)){
	$amount=$row['amount'];
	}
	
$speci .=$amount;
return $speci;
}

 // ===================== DAILY Expense ================
 function ABKDailyExpense($rsfrom, $rsto){
 
  $sql="SELECT sum(dr) as amount FROM ".DAILY_ACC_LEDGER_TBL." where expdate between '$rsfrom' and  '$rsto' ";
 
 	$speci = "";
	$res= mysql_query($sql);
  	while($row=mysql_fetch_array($res)){
	$amount=$row['amount'];
	}
	
$speci .=$amount;
return $speci;
}

 // ===================== Adjustment Accounts  ================
 function AdjustmentAccounts($rsto){
  $sql="SELECT	adjust_name,dr,cr
		FROM
			".ADJUSTMENT_ENTRY_TBL." e inner join ".ADJUSTMENT_TYPE_TBL." t on t.adjust_type_id=e.adjust_type_id
			where entry_date='$rsto'";
 
 	$speci = '<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
    <td height="22" colspan="2" align="center" style="font-size:14px; font-weight:bold; color:#0066FF; border-bottom:1px solid #006699">&nbsp;&nbsp;DEBIT</td>
    <td colspan="2" align="center" style="font-size:14px; font-weight:bold; color:#0066FF; border-bottom:1px solid #006699">&nbsp;CREDIT</td>
  </tr>';
	$res= mysql_query($sql);
  	while($row=mysql_fetch_array($res)){
	$adjust_name=$row['adjust_name'];
	$dr=$row['dr'];
	$cr=$row['cr'];
	
	$speci .='<tr>
    <td width="283" height="34" style="font-size:12px; font-weight:bold; color:#0066FF; border-bottom:1px solid #006699">'.$adjust_name.'</td>
    <td width="321" style="font-size:12px; color:#000000; border-bottom:1px solid #006699;border-right:1px solid #006699"> Tk. '.$dr.'</td>
    <td width="319" colspan="4" align="center" style="font-size:12px; color:#000000; border-bottom:1px solid #006699;border-right:1px solid #006699">&nbsp;Tk. '.$cr.'</td>
  </tr>';
  $totalDr=$totalDr+$dr;
  $totalCr=$totalCr+$cr;
	}
	$adjBalance=$totalDr-$totalCr;
	
$speci .='<tr>
    <td width="283" height="34" style="font-size:12px; font-weight:bold; color:#0066FF; border-bottom:1px solid #006699"></td>
    <td width="321" style="font-size:12px; color:#000000; border-bottom:1px solid #006699;border-right:1px solid #006699"><strong>Total :  Tk. '.number_format($totalDr,2).'</strong></td>
    <td width="319" colspan="4" align="center" style="font-size:12px; color:#000000; border-bottom:1px solid #006699;border-right:1px solid #006699"><strong>Total : Tk. '.number_format($totalCr,2).'</strong></td>
  </tr><tr>
    <td height="36" colspan="4" align="center" valign="middle" style="font-weight:bold; border-bottom:solid 1px;border-top:solid 1px;font-size:14px;color:#000099;"> Adjustments  Balance : Tk. '.number_format($adjBalance,2).'</td>
  </tr><table>';
return $speci;
}


//============ABK GL Accounts===============================
function ABKglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM ".ACC_DETAILS_TBL." where recdate<='$rsto'";
 $resPre= mysql_query($sqlPre);
 $speci = "";
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }
 
 
 // ========== abk Current assets ============ 
 
function ABKtotal($rsfrom, $rsto){
		
	// =======================for cindense milk =================================
		   $sql1="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=1  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for powder=========================
	  
		 $sql2="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=2  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Tea=========================
	
	$sql3="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=3  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
		//===============================for Baverage=========================

	$sql4="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=4  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";
	
	//===============================for Snacks=========================

	$sql5="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=5  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

	//===============================for Candy======================================================================

	$sql6="SELECT
			iis.Item_ID,
			sum(issue_qnt) as issue_qnt,
			sum(total_qnt)as total_qnt,
			sum(total_comm) as total_comm,
			sum(issu_total_cost) as issu_total_cost
		FROM
			".INV_ITEM_ISSUE_SUB_TBL." iis inner join ".INV_ITEM_ISSUE_MAIN_TBL."  im on im.issue_id= iis.issue_id
			inner join ".INV_ITEMINFO_TBL." i on i.Item_ID= iis.Item_ID
			where i.Item_Category_ID=6  and  issue_date between '$rsfrom' and '$rsto' group by iis.Item_ID";

						
	//echo $sql;
	$res1= mysql_query($sql1);
	$res2= mysql_query($sql2);
	$res3= mysql_query($sql3);
	$res4= mysql_query($sql4);
	$res5= mysql_query($sql5);
	$res6= mysql_query($sql6);
	
	// ========Count unit Price ===========================
	  $sqlcUnitPrice="SELECT unit_price as Cunit_price  FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where r.Item_ID='C01' and receive_date between '$rsfrom' and '$rsto' group by unit_price";
	//echo $sql;
	$rescUnitPrice= mysql_query($sqlcUnitPrice);
	$rowcUnitPrice=mysql_fetch_array($rescUnitPrice);
	$Cunit_price = $rowcUnitPrice['Cunit_price'];
	
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
	 $sqlpUnitPrice2p="SELECT unit_price as punit_price FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2p= mysql_query($sqlpUnitPrice2p);
	while($rowpUnitPrice2p=mysql_fetch_array($respUnitPrice2p)){
	 $punit_price = $rowpUnitPrice2p['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2p)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

	
  $subTotal2 = $subTotal2+($issu_total_cost2);
  $RsubTotal2 = $RsubTotal2+$TotalQnt2;
	}
	

	
  	while($row3=mysql_fetch_array($res3)){
	 $total_qnt3=$row3['issue_qnt'];
	 $Item_ID3=$row3['Item_ID'];
	 $issu_total_cost3=$row3['issu_total_cost'];
	 $total_comm3=$row3['total_comm'];
	

	// ========Count unit Price ===========================
	$sqltUnitPrice2t="SELECT unit_price as tunit_price FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$restUnitPrice2t= mysql_query($sqltUnitPrice2t);
	while($rowtUnitPrice2t=mysql_fetch_array($restUnitPrice2t)){
	$tunit_price = $rowtUnitPrice2t['tunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice2t)<=0){
	    $sqlft = "SELECT unit_price as maxTprice  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
	  //$TotalQnt3=$total_qnt3*$tunit_price;
 
  $subTotal3 = $subTotal3+($issu_total_cost3);
  $RsubTotal3 = $RsubTotal3+$TotalQnt3;
	}
	

  	while($row4=mysql_fetch_array($res4)){
	 $total_qnt4=$row4['issue_qnt'];
	 $Item_ID4=$row4['Item_ID'];
	 $issu_total_cost4=$row4['issu_total_cost'];
	 $total_comm4=$row4['total_comm'];
	 
	 
 // ========Count unit Price ===========================
	$sqlbUnitPrice2b="SELECT unit_price as bunit_price FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$resbUnitPrice2b= mysql_query($sqlbUnitPrice2b);
	while($rowbUnitPrice2b=mysql_fetch_array($resbUnitPrice2b)){
	$bunit_price = $rowbUnitPrice2b['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice2b)<=0){
	    $sqlfb = "SELECT unit_price as maxBprice  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	 //$TotalQnt4=$total_qnt4*$bunit_price;

  $subTotal4 = $subTotal4+($issu_total_cost4);
  $RsubTotal4 = $RsubTotal4+$TotalQnt4;
	}
	

  	while($row5=mysql_fetch_array($res5)){
	 $total_qnt5=$row5['issue_qnt'];
	 $Item_ID5=$row5['Item_ID'];
	 $issu_total_cost5=$row5['issu_total_cost'];
	 $total_comm5=$row5['total_comm'];
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice2s="SELECT unit_price as sunit_price FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID5' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$ressUnitPrice2s= mysql_query($sqlsUnitPrice2s);
	while($rowsUnitPrice2s=mysql_fetch_array($ressUnitPrice2s)){
	$sunit_price = $rowsUnitPrice2s['sunit_price'];
	}
	
	if(mysql_num_rows($ressUnitPrice2s)<=0){
	    $sqlfs = "SELECT unit_price as maxSprice  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID5')";
		$resfs =mysql_query($sqlfs);
		$rowfs = mysql_fetch_array($resfs);
		$maxSprice = $rowfs['maxSprice'];

	  $TotalQnt5=$total_qnt5*$maxSprice;
	}else{
	  $TotalQnt5=$total_qnt5*$sunit_price;
	}
	// ========End Count unit Price ===========================

	// $TotalQnt5=$total_qnt5*$sunit_price;


  $subTotal5 = $subTotal5+($issu_total_cost5);
  $RsubTotal5 = $RsubTotal5+$TotalQnt5;
	}
	

  	while($row6=mysql_fetch_array($res6)){
	 $total_qnt6=$row6['issue_qnt'];
	 $Item_ID6=$row6['Item_ID'];
	 $issu_total_cost6=$row6['issu_total_cost'];
	 $total_comm6=$row6['total_comm'];
	 
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice2cd="SELECT unit_price as cdunit_price FROM ".INV_RECEIVE_DETAIL_TBL." r inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID6' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$rescdUnitPrice2cd= mysql_query($sqlcdUnitPrice2cd);
	while($rowcdUnitPrice2cd=mysql_fetch_array($rescdUnitPrice2cd)){
	$cdunit_price = $rowcdUnitPrice2cd['cdunit_price'];
	}

	if(mysql_num_rows($respUnitPrice2cd)<=0){
	    $sqlfcd = "SELECT unit_price as maxCDprice  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID6')";
		$resfcd =mysql_query($sqlfcd);
		$rowfcd = mysql_fetch_array($resfcd);
		$maxCDprice = $rowfcd['maxCDprice'];

	 $TotalQnt6=$total_qnt6*$maxCDprice;
	}else{
	 $TotalQnt6=$total_qnt6*$cdunit_price;
	}
	// ========End Count unit Price ===========================


	 //$TotalQnt6=$total_qnt6*$cdunit_price;

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
 function FvistockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$link = dbFavi();
	
	$resc= mysql_query($sqlc,$link);
	

	$speci = "";

	while($rowc=mysql_fetch_array($resc)){
		$totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	$sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto' ";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
	
	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);
	
	

	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto' ";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 
	
		// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal2 = $subTotal2+($tqnt1p*$cdunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);

	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto' ";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}
	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDt' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$tunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =4 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb,$link);
	
	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDb'  and m.issue_date <= '$rsto' ";
	$res2b =mysql_query($sql2b,$link);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;

	
 // ========Count unit Price ===========================
	$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
	while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
	$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and
	Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
	while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
	$bunit_price = $rowbUnitPrice2['bunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$bunit_price);
	
	}//===================== End Baverage ===========================================
	
	$totalSubCount=$subTotal1+$subTotal2+$subTotal3+$subTotal4;
	$speci .=$totalSubCount;
	return $speci;

}//EOF

// ===================== Favi Dues ================
 function Fvidues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link = dbFavi();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== Favi Pending bill ================
 function FviPendingBill(){
 echo $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbFavi();
	
	$res= mysql_query($sql,$link);
	$row=mysql_fetch_array($res);
	$billAmount=$row['billAmount'];
	
/*  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
*/	
$speci .=$billAmount;
return $speci;
}

//============FAVI GL Accounts===============================
function FviglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbFavi();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }


//============== fvi Current assets ======================

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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================

	 
	// $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subComm1 = $subComm1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
	//$TotalQnt3=$total_qnt3*$tunit_price;

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
	 $sqlpUnitPrice4="SELECT unit_price as bunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice4= mysql_query($sqlpUnitPrice4,$link);
	while($rowpUnitPrice4=mysql_fetch_array($respUnitPrice4)){
	 $bunit_price = $rowpUnitPrice4['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice4)<=0){
	     $sqlfb = "SELECT unit_price as maxBprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb,$link);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $TotalQnt4=$total_qnt4*$bunit_price;

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



//=========== dabour total Stock values =================

function DburstockValues($rsto){
	  $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$link = dbDabur();
	
	$resc= mysql_query($sqlc,$link);
	$speci = "";

	while($rowc=mysql_fetch_array($resc)){
		 $totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	 $sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto' ";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
	
	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}
	
	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);

	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto' ";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 
	
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}

	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and  Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

		
  $subTotal2 = $subTotal2+($tqnt1p*$tunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);
	
	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto' ";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	
	 // ========Count unit Price ===========================
		$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
		while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
		$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
		}

		$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
		and Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
		while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
		$bunit_price = $rowbUnitPrice2['bunit_price'];
		}
		// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$bunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =4 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb,$link);
	
	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDb'  and m.issue_date <= '$rsto' ";
	$res2b =mysql_query($sql2b,$link);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;
		
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}

	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid
	and Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$sunit_price);
	
	}//===================== End Baverage ===========================================
	$sqls="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =5 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$ress= mysql_query($sqls,$link);
	
	while($rows=mysql_fetch_array($ress)){
		 $totalpcss = $rows['totalpcs'];
		 $Item_IDs = $rows['Item_ID'];

 	 $sql2s = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDs'  and m.issue_date <= '$rsto' ";
	$res2s =mysql_query($sql2s,$link);
	$row2s = mysql_fetch_array($res2s);

	$issue_total_qnts = $row2s['total_qnt'];
	$tqnt1s = $totalpcss-$issue_total_qnts;
	 
	// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}

	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid
	and Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal5 = $subTotal5+($tqnt1s*$cdunit_price);
	
	}//===================== End snacks ===========================================
	
	$totalSubCount=$subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5;
	
	$speci .= $totalSubCount;
	return $speci;

}

// ===================== Dabur Dues ================
 function Dburdues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link = dbDabur();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== Dabur Pending bill ================
 function DburPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbDabur();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}

//============Dabur GL Accounts===============================
function DburglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbDabur();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }


//========= Dabour current assets ===============================================
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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 
	 	// ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;


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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
	 // $TotalQnt3=$total_qnt3*$tunit_price;
 

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
	 $sqlpUnitPrice4="SELECT unit_price as bunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice4= mysql_query($sqlpUnitPrice4,$link);
	while($rowpUnitPrice4=mysql_fetch_array($respUnitPrice4)){
	 $bunit_price = $rowpUnitPrice4['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice4)<=0){
	     $sqlfb = "SELECT unit_price as maxBprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb,$link);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	 //$TotalQnt4=$total_qnt4*$bunit_price;

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
	 $sqlpUnitPrice5="SELECT unit_price as sunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID5' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice5= mysql_query($sqlpUnitPrice5,$link);
	while($rowpUnitPrice5=mysql_fetch_array($respUnitPrice5)){
	 $sunit_price = $rowpUnitPrice5['sunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice5)<=0){
	     $sqlfs = "SELECT unit_price as maxSprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID5')";
		$resfs =mysql_query($sqlfs,$link);
		$rowfs = mysql_fetch_array($resfs);
		$maxSprice = $rowfs['maxSprice'];

	  $TotalQnt5=$total_qnt5*$maxSprice;
	}else{
	  $TotalQnt5=$total_qnt5*$sunit_price;
	}
	// ========End Count unit Price ===========================

	// $TotalQnt5=$total_qnt5*$sunit_price;

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



//================= Johnson Current Stock ============
function JohnstockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$link=dbJohnson();
	
	$resc= mysql_query($sqlc,$link);

	while($rowc=mysql_fetch_array($resc)){
		 $totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	 $sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto'";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================


  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);

	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto'";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}

	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal2 = $subTotal2+($tqnt1p*$tunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);
	

	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto'";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	
	 // ========Count unit Price ===========================
		$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
		while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
		$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
		}

		$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
		and Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
		while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
		$bunit_price = $rowbUnitPrice2['bunit_price'];
		}
		// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$bunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =4 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb,$link);
	

	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub where Item_ID ='$Item_IDb'";
	$res2b =mysql_query($sql2b,$link);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}

	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$sunit_price);
	
	}//===================== End Baverage ===========================================
	$sqls="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =5 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$ress= mysql_query($sqls,$link);

	while($rows=mysql_fetch_array($ress)){
		 $totalpcss = $rows['totalpcs'];
		 $Item_IDs = $rows['Item_ID'];

 	 $sql2s = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDs'  and m.issue_date <= '$rsto'";
	$res2s =mysql_query($sql2s,$link);
	$row2s = mysql_fetch_array($res2s);

	$issue_total_qnts = $row2s['total_qnt'];
	$tqnt1s = $totalpcss-$issue_total_qnts;
	 

	// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}

	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal5 = $subTotal5+($tqnt1s*$cdunit_price);
	
	}//===================== End snacks ===========================================
	$sqlcd="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =6 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rescd= mysql_query($sqlcd,$link);
	
	while($rowcd=mysql_fetch_array($rescd)){
		 $totalpcscd = $rowcd['totalpcs'];
		 $Item_IDcd = $rowcd['Item_ID'];

 	 $sql2cd = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDcd'  and m.issue_date <= '$rsto'";
	$res2cd =mysql_query($sql2cd,$link);
	$row2cd = mysql_fetch_array($res2cd);

	$issue_total_qntcd = $row2cd['total_qnt'];
	$tqnt1cd = $totalpcscd-$issue_total_qntcd;
	
	// ========Count unit Price ===========================
	$sqlshUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid  FROM inv_receive_detail  WHERE Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$resshUnitPrice= mysql_query($sqlshUnitPrice,$link);
	while($rowshUnitPrice=mysql_fetch_array($resshUnitPrice)){
	$mxreceive_dtlid = $rowshUnitPrice['mxreceive_dtlid'];
	}

	$sqlshUnitPrice2="SELECT unit_price as shunit_price FROM inv_receive_detail WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$resshUnitPrice2= mysql_query($sqlshUnitPrice2,$link);
	while($rowshUnitPrice2=mysql_fetch_array($resshUnitPrice2)){
	$shunit_price = $rowshUnitPrice2['shunit_price'];
	}
	// ========End Count unit Price ===========================

	
  $subTotal6 = $subTotal6+($tqnt1cd*$shunit_price);
	}//===================== End Candy ===========================================
	
	$TotalSubCount=$subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6;
	$speci .=$TotalSubCount;
	return $speci;

}
 
// ===================== Johnson Dues ================
 function Johndues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link=dbJohnson();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== Johnson Pending bill ================
 function JohnPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbJohnson();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}

//============Johnson GL Accounts===============================
function JohnglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbJohnson();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }

 
 
 
//==================== Johnson Current Assets  ================================= 
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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 	// ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
// $TotalQnt3=$total_qnt3*$tunit_price;
 
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
	 $sqlpUnitPrice4="SELECT unit_price as bunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice4= mysql_query($sqlpUnitPrice4,$link);
	while($rowpUnitPrice4=mysql_fetch_array($respUnitPrice4)){
	 $bunit_price = $rowpUnitPrice4['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice4)<=0){
	     $sqlfb = "SELECT unit_price as maxBprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb,$link);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $TotalQnt4=$total_qnt4*$bunit_price;

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
	 $sqlpUnitPrice5="SELECT unit_price as sunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID5' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice5= mysql_query($sqlpUnitPrice5,$link);
	while($rowpUnitPrice5=mysql_fetch_array($respUnitPrice5)){
	 $sunit_price = $rowpUnitPrice5['sunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice5)<=0){
	     $sqlfs = "SELECT unit_price as maxSprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID5')";
		$resfs =mysql_query($sqlfs,$link);
		$rowfs = mysql_fetch_array($resfs);
		$maxSprice = $rowfs['maxSprice'];

	  $TotalQnt5=$total_qnt5*$maxSprice;
	}else{
	  $TotalQnt5=$total_qnt5*$sunit_price;
	}
	// ========End Count unit Price ===========================

	// $TotalQnt5=$total_qnt5*$sunit_price;

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
	 $sqlpUnitPrice6="SELECT unit_price as cdunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID6' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice6= mysql_query($sqlpUnitPrice6,$link);
	while($rowpUnitPrice6=mysql_fetch_array($respUnitPrice6)){
	 $cdunit_price = $rowpUnitPrice6['cdunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice6)<=0){
	     $sqlfcd = "SELECT unit_price as maxCDprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID6')";
		$resfcd =mysql_query($sqlfcd,$link);
		$rowfcd = mysql_fetch_array($resfcd);
		$maxCDprice = $rowfcd['maxCDprice'];

	  $TotalQnt6=$total_qnt6*$maxCDprice;
	}else{
	  $TotalQnt6=$total_qnt6*$cdunit_price;
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
 
 
  //===============================  SB Current Stock Value=====================================================
 function SBstockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	
	$link = dbSB();
	
	$resc= mysql_query($sqlc,$link);
	
	while($rowc=mysql_fetch_array($resc)){
		 $totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	 $sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto'";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}

	// ========End Count unit Price ===========================

		 
  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);
	
	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto'";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 

	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}

	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

		
  $subTotal2 = $subTotal2+($tqnt1p*$tunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);
	
	
	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto'";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	
	 // ========Count unit Price ===========================
		$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
		while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
		$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
		}

		$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
		while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
		$bunit_price = $rowbUnitPrice2['bunit_price'];
		}
		// ========End Count unit Price ===========================

		
  $subTotal3 = $subTotal3+($tqnt1t*$bunit_price);
	
	}//===================== End Tea ===========================================
	
	$totalSubCount=$subTotal1+$subTotal2+$subTotal3;
	
	$speci .= $totalSubCount;
	return $speci;

}//EOF
 
 
 
 // ===================== SB Distribution Dues ================
 function SBdues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link=dbSB();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== SB Distribution Pending bill ================
 function SBPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbSB();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}

//============SB Distribution GL Accounts===============================
function SBglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbSB();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }


 
 //=============== SB Current Assets==================
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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================

	 
	 //$totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}

  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
	  //$TotalQnt3=$total_qnt3*$tunit_price;
 
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
 
  
 // ================== Dabur Super shop current Stock ===========================
 function SuprstockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	
	$link=dbGroup();
	$resc= mysql_query($sqlc,$link);
	

	while($rowc=mysql_fetch_array($resc)){
		 $totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	 $sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto' ";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 
	
	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid  FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);

	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto'";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;
	 
	
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}

	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal2 = $subTotal2+($tqnt1p*$tunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);
	
	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto'";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;
	 
	
	 // ========Count unit Price ===========================
		$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid  FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
		while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
		$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
		}

		$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
		and Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
		while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
		$bunit_price = $rowbUnitPrice2['bunit_price'];
		}
		// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$bunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =4 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb,$link);

	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDb'  and m.issue_date <= '$rsto'";
	$res2b =mysql_query($sql2b,$link);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}

	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$sunit_price);
	
	}//===================== End Baverage ===========================================
	$sqls="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =5 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$ress= mysql_query($sqls,$link);
	
	
	while($rows=mysql_fetch_array($ress)){
		 $totalpcss = $rows['totalpcs'];
		 $Item_IDs = $rows['Item_ID'];

 	 $sql2s = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDs'  and m.issue_date <= '$rsto'";
	$res2s =mysql_query($sql2s,$link);
	$row2s = mysql_fetch_array($res2s);

	$issue_total_qnts = $row2s['total_qnt'];
	$tqnt1s = $totalpcss-$issue_total_qnts;
	 
	
	// ========Count unit Price ===========================
	$sqlcdUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice= mysql_query($sqlcdUnitPrice,$link);
	while($rowcdUnitPrice=mysql_fetch_array($rescdUnitPrice)){
	$mxreceive_dtlid = $rowcdUnitPrice['mxreceive_dtlid'];
	}

	$sqlcdUnitPrice2="SELECT unit_price as cdunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDs' group by Item_ID";
	//echo $sql;
	$rescdUnitPrice2= mysql_query($sqlcdUnitPrice2,$link);
	while($rowcdUnitPrice2=mysql_fetch_array($rescdUnitPrice2)){
	$cdunit_price = $rowcdUnitPrice2['cdunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal5 = $subTotal5+($tqnt1s*$cdunit_price);
	
	}//===================== End snacks ===========================================
	$sqlcd="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =6 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rescd= mysql_query($sqlcd,$link);
	
	while($rowcd=mysql_fetch_array($rescd)){
		 $totalpcscd = $rowcd['totalpcs'];
		 $Item_IDcd = $rowcd['Item_ID'];

 	 $sql2cd = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDcd'  and m.issue_date <= '$rsto'";
	$res2cd =mysql_query($sql2cd,$link);
	$row2cd = mysql_fetch_array($res2cd);

	$issue_total_qntcd = $row2cd['total_qnt'];
	$tqnt1cd = $totalpcscd-$issue_total_qntcd;
	 
	
		// ========Count unit Price ===========================
	$sqlshUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$resshUnitPrice= mysql_query($sqlshUnitPrice,$link);
	while($rowshUnitPrice=mysql_fetch_array($resshUnitPrice)){
	$mxreceive_dtlid = $rowshUnitPrice['mxreceive_dtlid'];
	}

	$sqlshUnitPrice2="SELECT unit_price as shunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid 
	and Item_ID='$Item_IDcd' group by Item_ID";
	//echo $sql;
	$resshUnitPrice2= mysql_query($sqlshUnitPrice2,$link);
	while($rowshUnitPrice2=mysql_fetch_array($resshUnitPrice2)){
	$shunit_price = $rowshUnitPrice2['shunit_price'];
	}
	// ========End Count unit Price ===========================

	
  $subTotal6 = $subTotal6+($tqnt1cd*$shunit_price);
	}//===================== End Candy ===========================================
	
	$totalSubCount=$subTotal1+$subTotal2+$subTotal3+$subTotal4+$subTotal5+$subTotal6;
	$speci .= $totalSubCount;
	return $speci;

}



 // ===================== Dabur Super shop Dues ================
 function Suprdues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link=dbGroup();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== Dabur Super shop Pending bill ================
 function SuprPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbGroup();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}

//============DAbur Super shop GL Accounts===============================
function SuprglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbGroup();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }


//============ Super shop total assets==================
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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================
	 
	 //$totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 	// ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
	 // $TotalQnt3=$total_qnt3*$tunit_price;
 
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
	 $sqlpUnitPrice4="SELECT unit_price as bunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice4= mysql_query($sqlpUnitPrice4,$link);
	while($rowpUnitPrice4=mysql_fetch_array($respUnitPrice4)){
	 $bunit_price = $rowpUnitPrice4['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice4)<=0){
	     $sqlfb = "SELECT unit_price as maxBprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb,$link);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $TotalQnt4=$total_qnt4*$bunit_price;

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
	 $sqlpUnitPrice5="SELECT unit_price as sunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID5' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice5= mysql_query($sqlpUnitPrice5,$link);
	while($rowpUnitPrice5=mysql_fetch_array($respUnitPrice5)){
	 $sunit_price = $rowpUnitPrice5['sunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice5)<=0){
	     $sqlfs = "SELECT unit_price as maxSprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID5')";
		$resfs =mysql_query($sqlfs,$link);
		$rowfs = mysql_fetch_array($resfs);
		$maxSprice = $rowfs['maxSprice'];

	  $TotalQnt5=$total_qnt5*$maxSprice;
	}else{
	  $TotalQnt5=$total_qnt5*$sunit_price;
	}
	// ========End Count unit Price ===========================

	// $TotalQnt5=$total_qnt5*$sunit_price;

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
	 $sqlpUnitPrice6="SELECT unit_price as cdunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID6' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice6= mysql_query($sqlpUnitPrice6,$link);
	while($rowpUnitPrice6=mysql_fetch_array($respUnitPrice6)){
	 $cdunit_price = $rowpUnitPrice6['cdunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice6)<=0){
	     $sqlfcd = "SELECT unit_price as maxCDprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID6')";
		$resfcd =mysql_query($sqlfcd,$link);
		$rowfcd = mysql_fetch_array($resfcd);
		$maxCDprice = $rowfcd['maxCDprice'];

	  $TotalQnt6=$total_qnt6*$maxCDprice;
	}else{
	  $TotalQnt6=$total_qnt6*$cdunit_price;
	}
	// ========End Count unit Price ===========================


	// $TotalQnt6=$total_qnt6*$cdunit_price;

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

/// ================ SB Distribution super shop Current Stock========================

function SBSuprstockValues($rsto){
	 $sqlc="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =1 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$link = dbSBsuper();
	$resc= mysql_query($sqlc,$link);

	while($rowc=mysql_fetch_array($resc)){
		 $totalpcsc = $rowc['totalpcs'];
		 $Item_IDc = $rowc['Item_ID'];

 	 	 $sql2c = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDc'  and m.issue_date <= '$rsto' ";
		$res2c =mysql_query($sql2c,$link);
		$row2c = mysql_fetch_array($res2c);

	$issue_total_qntc = $row2c['total_qnt'];
	$tqnt1c = $totalpcsc-$issue_total_qntc;
	 

	// ========Count unit Price ===========================
	$sqlpUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice= mysql_query($sqlpUnitPrice,$link);
	while($rowpUnitPrice=mysql_fetch_array($respUnitPrice)){
	$mxreceive_dtlid = $rowpUnitPrice['mxreceive_dtlid'];
	}

	$sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDc' group by Item_ID";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	$punit_price = $rowpUnitPrice2['punit_price'];
	}

	// ========End Count unit Price ===========================

  $subTotal1 = $subTotal1+($tqnt1c*$punit_price);
	
	}//===================== End Condense milk ===========================================
	$sqlp="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =2 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resp= mysql_query($sqlp,$link);

	while($rowp=mysql_fetch_array($resp)){
		 $totalpcsp = $rowp['totalpcs'];
		 $Item_IDp = $rowp['Item_ID'];

 	 $sql2p = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDp'  and m.issue_date <= '$rsto'";
	$res2p =mysql_query($sql2p,$link);
	$row2p = mysql_fetch_array($res2p);

	$issue_total_qntp = $row2p['total_qnt'];
	$tqnt1p = $totalpcsp-$issue_total_qntp;

		
	// ========Count unit Price ===========================
	$sqltUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice= mysql_query($sqltUnitPrice,$link);
	while($rowtUnitPrice=mysql_fetch_array($restUnitPrice)){
	$mxreceive_dtlid = $rowtUnitPrice['mxreceive_dtlid'];
	}

	$sqltUnitPrice2="SELECT unit_price as tunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDp' group by Item_ID";
	//echo $sql;
	$restUnitPrice2= mysql_query($sqltUnitPrice2,$link);
	while($rowtUnitPrice2=mysql_fetch_array($restUnitPrice2)){
	$tunit_price = $rowtUnitPrice2['tunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal2 = $subTotal2+($tqnt1p*$tunit_price);
	
	}//===================== End powder milk ===========================================
	
	$sqlt="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =3 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$rest= mysql_query($sqlt,$link);
	
	while($rowt=mysql_fetch_array($rest)){
		 $totalpcst = $rowt['totalpcs'];
		 $Item_IDt = $rowt['Item_ID'];

 	 $sql2t = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDt'  and m.issue_date <= '$rsto'";
	$res2t =mysql_query($sql2t,$link);
	$row2t = mysql_fetch_array($res2t);

	$issue_total_qntt = $row2t['total_qnt'];
	$tqnt1t = $totalpcst-$issue_total_qntt;

	
	 // ========Count unit Price ===========================
		$sqlbUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice= mysql_query($sqlbUnitPrice,$link);
		while($rowbUnitPrice=mysql_fetch_array($resbUnitPrice)){
		$mxreceive_dtlid = $rowbUnitPrice['mxreceive_dtlid'];
		}

		$sqlbUnitPrice2="SELECT unit_price as bunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDt' group by Item_ID";
		//echo $sql;
		$resbUnitPrice2= mysql_query($sqlbUnitPrice2,$link);
		while($rowbUnitPrice2=mysql_fetch_array($resbUnitPrice2)){
		$bunit_price = $rowbUnitPrice2['bunit_price'];
		}
		// ========End Count unit Price ===========================

  $subTotal3 = $subTotal3+($tqnt1t*$bunit_price);
	
	}//===================== End Tea ===========================================
	$sqlb="SELECT  
			rd.Item_ID, 
			sum(totalpcs) as totalpcs 
	FROM inv_receive_detail rd
	INNER JOIN inv_iteminfo i ON i.Item_ID = rd.Item_ID
	INNER JOIN inv_receive_master rm ON rm.mst_receiv_id = rd.mst_receiv_id
	INNER JOIN inv_item_measurement im ON im.mesure_id = i.mesure_id
	WHERE i.Item_Category_ID =4 AND rm.receive_date <= '$rsto' GROUP BY rd.Item_ID";
		
	//echo $sql;
	$resb= mysql_query($sqlb,$link);
	
	while($rowb=mysql_fetch_array($resb)){
		 $totalpcsb = $rowb['totalpcs'];
		 $Item_IDb = $rowb['Item_ID'];

 	 $sql2b = "SELECT sum(total_qnt) as total_qnt, sum(issue_total_grm) as issue_total_grm FROM inv_item_issue_sub sb 
		inner join 	inv_item_issue_main m on m.issue_id = sb.issue_id  where Item_ID ='$Item_IDb'  and m.issue_date <= '$rsto'";
	$res2b =mysql_query($sql2b,$link);
	$row2b = mysql_fetch_array($res2b);

	$issue_total_qntb = $row2b['total_qnt'];
	$tqnt1b = $totalpcsb-$issue_total_qntb;
	 
	
	// ========Count unit Price ===========================
	$sqlsUnitPrice="SELECT max(receive_dtlid) as mxreceive_dtlid FROM inv_receive_detail  WHERE Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice= mysql_query($sqlsUnitPrice,$link);
	while($rowsUnitPrice=mysql_fetch_array($ressUnitPrice)){
	$mxreceive_dtlid = $rowsUnitPrice['mxreceive_dtlid'];
	}

	$sqlsUnitPrice2="SELECT unit_price as sunit_price FROM inv_receive_detail  WHERE receive_dtlid=$mxreceive_dtlid and Item_ID='$Item_IDb' group by Item_ID";
	//echo $sql;
	$ressUnitPrice2= mysql_query($sqlsUnitPrice2,$link);
	while($rowsUnitPrice2=mysql_fetch_array($ressUnitPrice2)){
	$sunit_price = $rowsUnitPrice2['sunit_price'];
	}
	// ========End Count unit Price ===========================

  $subTotal4 = $subTotal4+($tqnt1b*$sunit_price);
	
	}//===================== End Baverage ===========================================
	
	$totalSubCount=$subTotal1+$subTotal2+$subTotal3+$subTotal4;
	$speci .= $totalSubCount;
	return $speci;

}
 
 
  // ===================== Dabur Super shop Dues ================
 function SBSuprdues($rsto){
 $sql="SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction 
 		where receive_date<='$rsto'";
 
 	$speci = "";
	$link=dbSBsuper();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$Dues=$row['las_due'];
	}
$speci .=$Dues;
return $speci;
}
 
 // ===================== SB Super shop Pending bill ================
 function SBSuprPendingBill(){
  $sql="SELECT sum(bill_amount) as billAmount FROM  inv_pending_bill where ststus='Pending' ";
 
 	$speci = "";
	$link = dbSBsuper();
	
	$res= mysql_query($sql,$link);
  	while($row=mysql_fetch_array($res)){
	$billAmount=$row['billAmount'];
	}
	
$speci .=$billAmount;
return $speci;
}

//============SB Distri Super shop GL Accounts===============================
function SBSuprglAcc($rsto){

 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<='$rsto'";
 $speci = "";
 
 $link = dbSBsuper();
 $resPre= mysql_query($sqlPre,$link);
 
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
		 
		 $total=($debit_total-$credit_total);
		 $speci .= $total;
return $speci;
 }

 
  
// =============== SB Super shop Total Assets=======
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
	 $sqlpUnitPrice1="SELECT unit_price as cunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID1' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice1= mysql_query($sqlpUnitPrice1,$link);
	while($rowpUnitPrice1=mysql_fetch_array($respUnitPrice1)){
	 $cunit_price = $rowpUnitPrice1['cunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice1)<=0){
	    $sqlfc = "SELECT unit_price as maxCprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID1')";
		$resfc =mysql_query($sqlfc,$link);
		$rowfc = mysql_fetch_array($resfc);
		$maxCprice = $rowfc['maxCprice'];

	$TotalQnt1=$total_qnt1*$maxCprice;
	}else{
	$TotalQnt1=$total_qnt1*$cunit_price;
	}
	// ========End Count unit Price ===========================

	 
	// $totalQnt1=$total_qnt1*$Cunit_price;

 $subTotal1 = $subTotal1+($issu_total_cost1);
 $RsubTotal1 = $RsubTotal1+$TotalQnt1;
 $subcom1 = $subcom1+$total_comm1;
 
	}
	 
  	while($row2=mysql_fetch_array($res2)){ 
	 $Item_ID2=$row2['Item_ID'];
	 $total_qnt2=$row2['issue_qnt'];
	 $issu_total_cost2=$row2['issu_total_cost'];
	 $total_comm2=$row2['total_comm'];
	 
	 // ========Count unit Price ===========================
	 $sqlpUnitPrice2="SELECT unit_price as punit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID2' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice2= mysql_query($sqlpUnitPrice2,$link);
	while($rowpUnitPrice2=mysql_fetch_array($respUnitPrice2)){
	 $punit_price = $rowpUnitPrice2['punit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice2)<=0){
	    $sqlfp = "SELECT unit_price as maxPprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID2')";
		$resfp =mysql_query($sqlfp,$link);
		$rowfp = mysql_fetch_array($resfp);
		$maxPprice = $rowfp['maxPprice'];

	$TotalQnt2=$total_qnt2*$maxPprice;
	}else{
	$TotalQnt2=$total_qnt2*$punit_price;
	}
	// ========End Count unit Price ===========================

//$TotalQnt2=$total_qnt2*$punit_price;

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
	 $sqlpUnitPrice3="SELECT unit_price as tunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID3' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice3= mysql_query($sqlpUnitPrice3,$link);
	while($rowpUnitPrice3=mysql_fetch_array($respUnitPrice3)){
	 $tunit_price = $rowpUnitPrice3['tunit_price'].'<br>';
	}
	
	if(mysql_num_rows($respUnitPrice3)<=0){
	     $sqlft = "SELECT unit_price as maxTprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID3')";
		$resft =mysql_query($sqlft,$link);
		$rowft = mysql_fetch_array($resft);
		$maxTprice = $rowft['maxTprice'];

	  $TotalQnt3=$total_qnt3*$maxTprice;
	}else{
	  $TotalQnt3=$total_qnt3*$tunit_price;
	}
	// ========End Count unit Price ===========================
  //$TotalQnt3=$total_qnt3*$tunit_price;
 
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
	 $sqlpUnitPrice4="SELECT unit_price as bunit_price FROM inv_receive_detail r inner join inv_receive_master m on m.mst_receiv_id=r.mst_receiv_id
where Item_ID='$Item_ID4' and receive_date between '$rsfrom' and '$rsto'";
	//echo $sql;
	$respUnitPrice4= mysql_query($sqlpUnitPrice4,$link);
	while($rowpUnitPrice4=mysql_fetch_array($respUnitPrice4)){
	 $bunit_price = $rowpUnitPrice4['bunit_price'];
	}
	
	if(mysql_num_rows($respUnitPrice4)<=0){
	     $sqlfb = "SELECT unit_price as maxBprice  FROM inv_receive_detail where receive_dtlid = (select MAX(receive_dtlid) from inv_receive_detail where Item_ID = '$Item_ID4')";
		$resfb =mysql_query($sqlfb,$link);
		$rowfb = mysql_fetch_array($resfb);
		$maxBprice = $rowfb['maxBprice'];

	  $TotalQnt4=$total_qnt4*$maxBprice;
	}else{
	  $TotalQnt4=$total_qnt4*$bunit_price;
	}
	// ========End Count unit Price ===========================
	 
	// $TotalQnt4=$total_qnt4*$bunit_price;

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