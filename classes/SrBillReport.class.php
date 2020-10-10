<?php

class SrBillReport
{
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'DSRDateWiseDuesSkin'   	: echo $this->DSRDateWiseDuesSkin();					break;
		 case 'DSRCurrentDuesSkin'   	: echo $this->DSRCurrentDuesSkin();					break;
		 case 'DailyTransactionGroup'   : echo $this->DailyTransactionGroup();					break;
		 case 'FullTRansactionShow'  	: echo $this->DSRfullTransactionRShow(); 			break;
         case 'list'                  	: $this->getList();                       			break;
         default                      	: $cmd == 'list'; $this->getList();	       			break;
      }
 }
function getList(){
$slsman = $this->SelectSalesMan();
require_once(CURRENT_APP_SKIN_FILE); 
  }

 function DSRfullTransactionRShow(){
 $slman_id = getRequest('slman_id');
 $from_full = formatDate4insert(getRequest('from_full'));
 $to_full = formatDate4insert(getRequest('to_full'));
 $sql = "SELECT	name FROM sales_maninfo where slman_id=$slman_id ";
 $res = mysql_query($sql);
 $row = mysql_fetch_array($res);
 $name = $row['name'];
 $ShowBill = $this->FullTransactionFetch($slman_id,$from_full,$to_full);
  require_once(DSR_FULL_TRANSACTION_REPORT);
 }

 function FullTransactionFetch($slman_id,$from_full,$to_full){
  // Previous calculation ==========
 $sqlPre="SELECT
	sum(issuevalues) as issuevalues,
	sum(total_commi) as total_commi,
	sum(opening_dues) as opening_dues,
	sum(receive_amount) as receive_amount,
	sum(due_amount) as due_amount, 
	receive_date
FROM
	inv_sr_account where slman_id=$slman_id and receive_date<'$from_full'";
 $resPre= mysql_query($sqlPre);
 while($rowpre=mysql_fetch_array($resPre)){
		 $issuevalues = $rowpre['issuevalues'];
		 $opening_dues = $rowpre['opening_dues'];
		 $total_commi = $rowpre['total_commi'];
		 $receive_amount = $rowpre['receive_amount'];
		 $due_amount = $rowpre['due_amount'];
		 }
 
 $totalSub1=($issuevalues+$opening_dues);
 $totalSub2=($total_commi+$receive_amount+$due_amount);
 $pre_balance=($totalSub1-$totalSub2);
 
 //======= pre calculation end ===========


  // Current calculation ==========
  $sqlCur="SELECT
	sum(issuevalues) as issuevalues,
	sum(total_commi) as total_commi,
	sum(opening_dues) as opening_dues,
	sum(receive_amount) as receive_amount,
	sum(due_amount) as due_amount, 
	receive_date
FROM
	inv_sr_account where slman_id=$slman_id and receive_date between '$from_full' and '$to_full'";
 $resCur= mysql_query($sqlCur);
 while($rowCur=mysql_fetch_array($resCur)){
		$issuevalues2 = $rowCur['issuevalues'];
		 $opening_dues2 = $rowCur['opening_dues'];
		 $total_commi2 = $rowCur['total_commi'];
		 $receive_amount2 = $rowCur['receive_amount'];
		 $due_amount2 = $rowCur['due_amount'];
		 }
 
$totalSub12=($issuevalues2+$opening_dues2);
 $totalSub22=($total_commi2+$receive_amount2+$due_amount2);
 $CurBalance=($totalSub12-$totalSub22);
 

 //======= Current calculation end ===========
	
	
	$sql = "SELECT
	accid,
	slman_id,
	opening_dues,
	issuevalues,
	total_commi,
	receive_amount,
	due_amount,
	receive_date,
	(issuevalues-(total_commi+receive_amount)) as market_dues,
	@rt2 := @rt2 +((issuevalues+opening_dues)-(total_commi+receive_amount+due_amount)) as balance
FROM
	inv_sr_account, (SELECT @rt2 := 0 ) as tempName2 where slman_id=$slman_id and receive_date between '$from_full' and '$to_full'
	order by receive_date ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=900 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Receive Date</th>
<!--	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Opening Dues</th>
-->	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Values</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Comission</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Receive Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dues in Market</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dues Received</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Balance</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
			$opening_dues = $row['opening_dues'];
			if($opening_dues!='0.00'){
				$opdues='<br>+'.$opening_dues.'&nbsp;(opening dues)';
			}else{
				$opdues='';
			}
			
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['receive_date'])."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['opening_dues'],2)."</td>
	-->	<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['issuevalues'],2).$opdues."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['total_commi'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['receive_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['market_dues'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['due_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['balance'],2)."</td>
		</tr>";

	
	$i++;
	}
 $NetBalance=$pre_balance+$CurBalance;
	$speci .= "<tr height=25 >
			  <td nowrap=nowrap>&nbsp;</td>
			  <td nowrap=nowrap>&nbsp;</td>
			  <td nowrap=nowrap align=right>&nbsp;</td>
			  <td nowrap=nowrap align=right>&nbsp;</td>
			  <td nowrap=nowrap colspan=4  align=right><strong>Previous Balance : ".number_format($pre_balance,2)."</strong></td>
	  </tr>
	  <tr height=25 >
			  <td nowrap=nowrap>&nbsp;</td>
			  <td nowrap=nowrap>&nbsp;</td>
			  <td nowrap=nowrap align=right>&nbsp;</td>
			  <td nowrap=nowrap align=right>&nbsp;</td>
			  <td nowrap=nowrap colspan=4  align=right><strong>Current Balance : ".number_format($NetBalance,2)."</strong></td>
	  </tr></table>";
	return $speci;

}//end fnc


 function DailyTransactionGroup(){
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $ShowBill = $this->DailyTransactionGroupFetch($receive_date);
  require_once(DSR_DAILY_TRANSACTION_GROUP);
 }

 function DailyTransactionGroupFetch($receive_date){
  $sql = "SELECT
	accid,
	name,
	slman_id,
	opening_dues,
	issuevalues,
	total_commi,
	receive_amount,
	market_dues,
	due_amount,
	receive_date,
	opening_balance
FROM
	view_dsr_transaction where receive_date='$receive_date' ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=900 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Values</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Comission</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Receive Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dues in Market</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dues Received</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Balance</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Pre-dues</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Dues</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	$slman_id=$row['slman_id'];
	
	 $sql2 = "SELECT (sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount)) as las_due FROM view_dsr_full_transaction
	 where slman_id=$slman_id and receive_date<'$receive_date'";
	$res2= mysql_query($sql2);
	while($row2=mysql_fetch_array($res2)){
	 $pre_dues=$row2['las_due'];
	}
	$tota_dues=$pre_dues+$row['opening_balance'];
	
			$opening_dues = $row['opening_dues'];
			if($opening_dues!='0.00'){
				$opdues='<br/>+'.$opening_dues.'&nbsp;(opening dues)';
			}else{
				$opdues='';
			}
			
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['issuevalues'],2).$opdues."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['total_commi'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['receive_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['market_dues'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['due_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['opening_balance'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($pre_dues,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($tota_dues,2)."</td>
		</tr>";

	$issue=$issue+$row['issuevalues'];
	$commi=$commi+$row['total_commi'];
	$reci=$reci+$row['receive_amount'];
	$due=$due+$row['due_amount'];
	$ob=$ob+$row['opening_balance'];
	$marketDues=$marketDues+$row['market_dues'];
	$pre=$pre+$pre_dues;
	$totdues=$totdues+$tota_dues;
	$i++;
	}
	$speci .= "<tr height=30><td colspan=2 align=right style=\"border:#000000 solid 1px\"><strong>GR Total : </strong></td><td align=right style=\"border:#000000 solid 1px\"><b>".number_format($issue,2)."</b></td><td align=right style=\"border:#000000 solid 1px\"><b>".number_format($commi,2)."</b></td><td  align=right style=\"border:#000000 solid 1px\"><b>".number_format($reci,2)."</b></td><td  align=right style=\"border:#000000 solid 1px\"><b>".number_format($marketDues,2)."</b></td><td  align=right style=\"border:#000000 solid 1px\"><b>".number_format($due,2)."</b></td><td  align=right style=\"border:#000000 solid 1px\"><b>".number_format($ob,2)."</b></td><td align=right style=\"border:#000000 solid 1px\"><b>".number_format($pre,2)."</b></td><td align=right style=\"border:#000000 solid 1px\"><b>".number_format($totdues,2)."</b></td></tr>
	</table>";
	return $speci;

}//end fnc


 function DSRCurrentDuesSkin(){
 //$receive_date = formatDate4insert(getRequest('receive_datec'));
 $ShowDues = $this->DSRCurrentDuesFetch();
  require_once(DSR_CURRENT_DUES);
 }


 function DSRCurrentDuesFetch(){
  $sql = "SELECT slman_id, name,current_dues FROM view_dsr_current_dues3";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=400 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Current Dues</th>
	       </tr>";
                 
				 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['current_dues'],2)."</td>
		</tr>";

	$cur=$cur+$row['current_dues'];
	$i++;
	}
	$speci .= "<tr height=30><td colspan=2 align=right style=\"border:#000000 solid 1px\"><strong>GR Total : </strong></td>
	<td align=right style=\"border:#000000 solid 1px\"><b>".number_format($cur,2)."</b></td></tr>
	</table>";
	return $speci;

}//end fnc

 function DSRDateWiseDuesSkin(){
 $receive_date = formatDate4insert(getRequest('receive_datec'));
 $ShowDues = $this->DSRDateWiseDuesFetch($receive_date);
  require_once(DSR_DATE_WISE_DUES);
 }


 function DSRDateWiseDuesFetch($receive_date){
  $sql = "SELECT name,((sum(issuevalues) + sum(opening_dues))-(sum(total_commi)+sum(receive_amount)+sum(due_amount))) as las_due 
  	FROM inv_sr_account a inner join sales_maninfo s on s.slman_id=a.slman_id
	where receive_date<='$receive_date' group by a.slman_id";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=400 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Dues</th>
	       </tr>";
                 
				 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['las_due'],2)."</td>
		</tr>";

	$cur=$cur+$row['las_due'];
	$i++;
	}
	$speci .= "<tr height=30><td colspan=2 align=right style=\"border:#000000 solid 1px\"><strong>GR Total : </strong></td>
	<td align=right style=\"border:#000000 solid 1px\"><b>".number_format($cur,2)."</b></td></tr>
	</table>";
	return $speci;

}//end fnc



/*function DailyTransPreDueFetch($receive_date){
 $sql = "SELECT name,sum(opening_balance) as pre_dues FROM	view_dsr_transaction where receive_date<'$receive_date' group by slman_id";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=400 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Values</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Comission</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Receive Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dues Received</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Balance</th>
	       </tr>";
                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
			$opening_dues = $row['opening_dues'];
			if($opening_dues!='0.00'){
				$opdues='<br/>+'.$opening_dues.'&nbsp;(opening dues)';
			}else{
				$opdues='';
			}
			
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['issuevalues'],2).$opdues."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['total_commi'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['receive_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['due_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['opening_balance'],2)."</td>
		</tr>";

	
	$i++;
	}
	$speci .= "<!--<tr height=30><td colspan=7></td><td></td></tr>
	<tr><td colspan=9>Payable Dues</td><td style=\"border:#000000 solid 1px\"><b>".number_format($due_amount,2)."</b></td></tr>--></table>";
	return $speci;

}//end fnc

*/   
function SelectSalesMan(){ 
		$sql="SELECT slman_id, name FROM ".INV_SALSEMAN_INFO_TBL." order by name asc ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='slman_id' size='1' id='slman_id2' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['slman_id']."'>".$row['name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }



   
} // End class

?>