<?php
class bkash_acc_total
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
		 case 'DateWiseSalsmanwiseShopBalance'   : echo $this->DateWiseSalsmanwiseShopBalance();  				break;
		 case 'SalsmanwiseShopBalance'   : echo $this->SalsmanwiseShopBalance();  				break;
		 case 'ViewDetailsBkashTrans'   : echo $this->ViewDetailsTrans();  				break;
		 case 'ajaxSearch'         : echo $this->ViewTransFetch();  				break;
         case 'list'               : $this->getList();   							break; 
         default                   : $cmd == 'list'; $this->getList();				break;
      }
 }
   
   function getList(){
//$branch_id = getFromSession('branch_id');

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM bkash_transaction";
	$total_pages = mysql_fetch_array(mysql_query($query)); //exit();
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	//$targetpage = "?app=loan_rec"; 	//your file name  (the name of this file)
	$limit = 30; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
/*	$sql = "SELECT client_id FROM client_info LIMIT $start, $limit";
	$result = mysql_query($sql);
*/	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"?app=bkash_acc_total&page=$prev\">&laquo; previous</a>";
		else
			$pagination.= "<span class=\"disabled\">previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"?app=bkash_acc_total&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=bkash_acc_total&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=bkash_acc_total&page=1\">1</a>";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=bkash_acc_total&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=bkash_acc_total&page=1\">1</a>";
				$pagination.= "<a href=\"?app=bkash_acc_total&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=bkash_acc_total&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=bkash_acc_total&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }	
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30;  $res2 = $Rec*30-1;}else { $res3=0; $res3=29;}
		 
	} 
	//return $pagination;
	//$SearchTeam=$this->SearchTeam();
	$ViewTransListFetch = $this->ViewTransFetch($start, $limit);

  
	 require_once(CURRENT_APP_SKIN_FILE); 
	 
  }
  
  
//---------------------------------- Client List Fetch -------------------------------------------
function ViewTransFetch($start=null, $limit=null){
   		$searchq =$_GET['searchq'];
		$Page 	= getRequest('page');
		if($searchq){

 	           $sql='SELECT
					bkash_id,
					bkash_acc,
					sum(cash_in) as cash_in,
					sum(cash_out) as cash_out,
				    (sum(cash_in)-sum(cash_out)) as balance
				FROM
					bkash_transaction where bkash_acc LIKE "%'.$searchq.'%"  group by bkash_acc';
		}
		if($Page && !$searchq){
 	           $sql="SELECT
					bkash_id,
					bkash_acc,
					sum(cash_in) as cash_in,
					sum(cash_out) as cash_out,
				    (sum(cash_in)-sum(cash_out)) as balance
				FROM
					bkash_transaction group by bkash_acc LIMIT $start, $limit";
		
		}
		if(!$Page && !$searchq){
 	            $sql="SELECT
					bkash_id,
					bkash_acc,
					sum(cash_in) as cash_in,
					sum(cash_out) as cash_out,
				    (sum(cash_in)-sum(cash_out)) as balance
				FROM
					bkash_transaction group by bkash_acc LIMIT 0, 29";
		
		}
			  
	$res= mysql_query($sql);
	
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table border=0 cellspacing=0 class="tableGrid" width="1000">
	            <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">bKash Acc.</th>
				<th   align="left" nowrap>Cash In</th>
				<th   align="left" nowrap>Cash Out</th>
				<th   align="left" nowrap>Balance</th>
           </tr>';
                          $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
					
					$cash_in=$row['cash_in'];
					$cash_out=$row['cash_out'];
					
					$balance=($cash_in-$cash_out);
					
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsBkashTrans&bkash_acc='.$row['bkash_acc'].'">'.$row['bkash_acc'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cash_in'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cash_out'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($balance,2).'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsBkashTrans&bkash_acc='.$row['bkash_acc'].'">'.$row['bkash_acc'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cash_in'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cash_out'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($balance,2).'&nbsp;</td>
				   
				</tr>';
	
			  $rowcolor=0;
			  } 
	
		$i++; 
		
		}
	$html .= '</table>';
	
	return $html;
	
 }


//---------------- Store wise transaction report ------------------------------

function ViewDetailsTrans(){
$bkash_acc 	= getRequest('bkash_acc');
		$CollectionFetch = $this->CollectionReport($bkash_acc);
		require_once(BKASH_ACCOUNTS_TRANSACTION_VIEW_SKIN); 

				
	} // EOF
	
  function CollectionReport($bkash_acc){
		 $sql = "select trans_date,particulars,bkash_acc,cash_in,cash_out, @rt2 := @rt2 +(cash_in-cash_out) as balance2
 						from bkash_transaction, (SELECT @rt2 := 0 ) as tempName2 where bkash_acc=$bkash_acc ";
 
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=600 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Particulars</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cash In</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cash Out</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Balance</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){ 
							//$balance2=$row['balance2'];
					
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['trans_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".number_format($row['cash_in'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".number_format($row['cash_out'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".number_format($row['balance2'],2)."</td>
		</tr>";
//$total = $total+$row['actual_price'];
	$i++;
	}
	$speci .= "</table>";
	return $speci;

}//end fnc

function SalsmanwiseShopBalance(){
$slman_id = getRequest('slman_id');
 $Fetch = $this->SalsmanwiseShopBalancefetch($slman_id);
require_once(SALSMAN_WISE_SHOP_BALANCE_SKIN); 
}


function SalsmanwiseShopBalancefetch($slman_id){ 


 $sql="SELECT
	store_acc_id,
	a.store_id,
	a.slman_id,
	store_name,
	name,
	sum(dues_disburse) as dues_disburse,
	sum(dues_receive) as dues_receive
FROM
	store_accounts a inner join store_info i on a.store_id=i.store_id
	inner join sales_maninfo s on s.slman_id=a.slman_id
	where a.slman_id='$slman_id' group by a.store_id order by store_name ASC ";
	
$res= mysql_query($sql);

	$html = '<table width=1000 cellpadding="5" cellspacing="0" border="0" style="border-collapse:collapse">
	            <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">Shop Name</th>
				<th   align="left" nowrap>Dues Amount</th>
				<th   align="left" nowrap>Collection Amount</th>
				<th   align="left" nowrap>Balance</th>
				<th   align="left" nowrap>DSR Name</th>
           </tr>';
                         
						 
						  $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
					
					$balance=$row['dues_disburse']-$row['dues_receive'];
					
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['dues_disburse'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['dues_receive'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$balance.'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['name'].'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dues_disburse'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dues_receive'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($balance,2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['name'].'&nbsp;</td>
				   
				</tr>';
	
	
	
			  $rowcolor=0;
			  } 
	
		$i++; 
		
		$totalDues=$totalDues+$row['dues_disburse'];
		$totalCollec=$totalCollec+$row['dues_receive'];
		$totalBlnc=$totalBlnc+$balance;
		
		}
	$html .= '<tr>
			<td colspan=2 align="right"><strong>Total : </strong></td>
			<td><strong> '.number_format($totalDues,2).'</strong>&nbsp;</td>
			<td><strong>'.number_format($totalCollec,2).'</strong>&nbsp;</td>
			<td><strong>'.number_format($totalBlnc,2).'</strong>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
		</table>';
	
	return $html;
	
 }



function DateWiseSalsmanwiseShopBalance(){
 $fromdate = formatDate4insert(getRequest('fromdate'));
 $todate = formatDate4insert(getRequest('todate'));

 $Fetch = $this->DateWiseSalsmanShpBalancefetch();
require_once(SALSMAN_WISE_SHOP_BALANCE_FOR_DATE_SKIN); 
}


function DateWiseSalsmanShpBalancefetch(){ 
$slman_id = getRequest('slman_id');

 $fromdate = formatDate4insert(getRequest('fromdate'));
 $todate = formatDate4insert(getRequest('todate'));

echo  $sql2="SELECT
	sum(dues_disburse) as dues_disburse,
	sum(dues_receive) as dues_receive
FROM
	store_accounts a inner join store_info i on a.store_id=i.store_id
	inner join sales_maninfo s on s.slman_id=a.slman_id
	where a.slman_id='$slman_id' and transdate<'$fromdate' group by a.store_id order by store_name ASC ";

$res2= mysql_query($sql2);
while($row2 = mysql_fetch_array($res2)){
$dues_disburse2=$row2['dues_disburse'];
$dues_receive2=$row2['dues_receive'];
}

$Balance2=($dues_disburse2-$dues_receive2);

 $sql="SELECT
	store_acc_id,
	a.store_id,
	a.slman_id,
	store_name,
	name,
	sum(dues_disburse) as dues_disburse,
	sum(dues_receive) as dues_receive
FROM
	store_accounts a inner join store_info i on a.store_id=i.store_id
	inner join sales_maninfo s on s.slman_id=a.slman_id
	where a.slman_id='$slman_id' and transdate between '$fromdate' and '$todate' group by a.store_id order by store_name ASC ";
	
$res= mysql_query($sql);

	$html = '<table width=1000 cellpadding="5" cellspacing="0" border="0" style="border-collapse:collapse">
	            <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">Shop Name</th>
				<th   align="left" nowrap>Dues Amount</th>
				<th   align="left" nowrap>Collection Amount</th>
				<th   align="left" nowrap>Balance</th>
				<th   align="left" nowrap>DSR Name</th>
           </tr>';
                         
						 
						  $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
					
					$balance=$row['dues_disburse']-$row['dues_receive'];
					
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['dues_disburse'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['dues_receive'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$balance.'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['name'].'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=bkash_acc_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dues_disburse'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dues_receive'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($balance,2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['name'].'&nbsp;</td>
				   
				</tr>';
	
	
	
			  $rowcolor=0;
			  } 
	
		$i++; 
		
		$totalDues=$totalDues+$row['dues_disburse'];
		$totalCollec=$totalCollec+$row['dues_receive'];
		$totalBlnc=$totalBlnc+$balance;
		
		}
		
		$totalDues2=($totalDues+$dues_disburse2);
		$totalCollec2=($totalCollec+$dues_receive2);
		$totalBlnc2=($totalBlnc+$Balance2);
		
		
	$html .= '<tr>
			<td colspan=2 align="right"><strong>Total :&nbsp; </strong></td>
			<td><strong> '.number_format($totalDues2,2).'</strong>&nbsp;</td>
			<td><strong>'.number_format($totalCollec2,2).'</strong>&nbsp;</td>
			<td><strong>'.number_format($totalBlnc2,2).'</strong>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
		</table>';
	
	return $html;
	
 }


} // End class

?>