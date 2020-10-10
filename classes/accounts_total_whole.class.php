<?php
class accounts_total_whole
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
		 case 'DateWiseSalsmanwiseShopBalance'   : echo $this->DateWiseSalsmanwiseShopBalance();  				break;
		 case 'SalsmanwiseShopBalance'   : echo $this->SalsmanwiseShopBalance();  				break;
		 case 'ViewDetailsTrans'   : echo $this->ViewDetailsTrans();  				break;
		 case 'ajaxSearch'         : echo $this->ViewTransFetch();  				break;
         case 'list'               : $this->getList();   							break; 
         default                   : $cmd == 'list'; $this->getList();				break;
      }
 }
   
   function getList(){
$branch_id = getFromSession('branch_id');
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(distinct(customer_id)) as num FROM view_whole_sales_acc_balance where branch_id=$branch_id and balance!='0.00'";
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
			$pagination.= "<a href=\"?app=accounts_total_whole&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts_total_whole&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts_total_whole&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts_total_whole&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_total_whole&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts_total_whole&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_total_whole&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_total_whole&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts_total_whole&page=$next\">next &raquo;</a>";
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
		$branch_id = getFromSession('branch_id');
		if($searchq && !$Page){

 	 $sql='select 
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
		where store_name LIKE "%'.$searchq.'%" or mobile LIKE "%'.$searchq.'%"  group by a.customer_id order by s.store_name';
		}
		if($Page && !$searchq){
   $sql="select 
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
		group by a.customer_id order by s.store_name LIMIT $start, $limit";
		
		}
		if(!$Page && !$searchq){
		   $sql="select 
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
		group by a.customer_id order by s.store_name ";
		}
			  
	$res= mysql_query($sql);
	
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table border=0 cellspacing=0 class="tableGrid" width="800">
	            <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">Customer</th>
<!--	            <th   align="left">Address</th>
-->	            <th   align="left">Mobile</th>
				<th   align="left" nowrap>DR Amount</th>
				<th   align="left" nowrap>CR Amount</th>
<!--				<th   align="left" nowrap>Return Amount</th>
-->				<th   align="left" nowrap>Balance</th>
           </tr>';
                          $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
					
					/*$dr_amount=$row['dr_amount'];
					$cr_amount=$row['cr_amount'];*/
					
					$balance=$row['balance'];
					
				if($balance>0){	
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&customer_id='.$row['customer_id'].'">'.$row['store_name'].'</a></td>
<!--				    <td  style="border-right:1px solid #cccccc;padding:2px;" width=200>'.$row['address'].'&nbsp;</td>
-->				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr_amount'],2).'&nbsp;</td>
<!--				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['return_amount'],2).'&nbsp;</td>
-->				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['balance'],2).'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
				   <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&customer_id='.$row['customer_id'].'">'.$row['store_name'].'</a></td>
<!--				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['address'].'&nbsp;</td>
-->				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['mobile'].'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr_amount'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr_amount'],2).'&nbsp;</td>
<!--				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['return_amount'],2).'&nbsp;</td>
-->				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['balance'],2).'&nbsp;</td>
				   
				</tr>';
	
			  $rowcolor=0;
			  } 
			}//Balance
	
		$i++; 
		
		$totalbalance=$totalbalance+$row['balance'];
		
		}
	$html .= '<tr>
					
				   <td  colspan="5" align="right"><strong>Total Dues :</strong></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;"><strong>'.number_format($totalbalance,2).'</strong>&nbsp;</td>
				   
				</tr></table>';
	
	return $html;
	
 }


//---------------- Store wise transaction report ------------------------------

function ViewDetailsTrans(){
$customer_id = getRequest('customer_id');
$branch_id = getFromSession('branch_id');
    $sql = "SELECT
	customer_id,
	store_name,
	address,
	mobile,
	email
FROM
	 customer_info
	 where customer_id = $customer_id ";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$store_name=$row['store_name'];
	$mobile= $row['mobile'];
	$address= $row['address'];
	$upzila= $row['upzila'];

  
  
 	            $sql3="select sum(dr_amount) as drAmnt, sum(cr_amount) as crAmnt, sum(return_amount) as retAmnt
				FROM whole_saler_acc where customer_id = $customer_id ";
		
	   	        $ros3 = mysql_query($sql3);
			
		while($res3 = mysql_fetch_array($ros3)){
				$drAmnt				= $res3['drAmnt'];
				$crAmnt			= $res3['crAmnt'];
				$retAmnt			= $res3['retAmnt'];
				}
				
				$balance = (($drAmnt)-($crAmnt+$retAmnt));
				
				
		$CollectionFetch = $this->CollectionReport($customer_id);
		require_once(ACCOUNTS_TRANSACTION_VIEW_WHOLE_SKIN); 

				
	} // EOF
	
  function CollectionReport($customer_id){
 		$branch_id = getFromSession('branch_id');
		 $sql = "select paid_date, pay_type,dr_amount,cr_amount,invoice,stat, @rt2 := @rt2 +(dr_amount-cr_amount) as balance2
 						from whole_saler_acc, (SELECT @rt2 := 0 ) as tempName2 where customer_id=$customer_id 
 						order by paid_date ASC";
 
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=700 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th align=center style=\"border:#000000 solid 1px; font-family:Verdana\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;Date</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;Particulars</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;DR Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;CR Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;Balance</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){ 
							$invoice=$row['invoice'];
							$stat=$row['stat'];
							if($invoice!=''){
								$Particular='<b><a href="?app=inv_item_sales_emi&cmd=ajaxInvoicePrintViewSkin&invoice='.$invoice.'" target="_blank">'.$invoice.'</a></b>';
							}elseif($invoice=='' && $stat=='OB'){
								$Particular='Opening Dues';
							}else{
								$Particular='Cash Recived';
							}
					
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\" align=center>".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;"._date($row['paid_date'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;".$Particular."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;".number_format($row['dr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;".number_format($row['cr_amount'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px; font-family:Verdana\">&nbsp;".number_format($row['balance2'],2)."</td>
		</tr>";
//$total = $total+$row['actual_price'];
	$i++;
	}
	$speci .= "</table>
	";
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
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total_whole&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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