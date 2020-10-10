<?php
class accounts_total
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
	 $query = "SELECT COUNT(distinct(supplier_ID)) as num FROM ".ACC_DETAILS_TBL."";
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
			$pagination.= "<a href=\"?app=accounts_total&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts_total&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts_total&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_total&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_total&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts_total&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_total&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_total&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_total&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_total&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts_total&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_total&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_total&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts_total&page=$next\">next &raquo;</a>";
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
					supplier_id,
					company_name,
					sum(dr) as dr,
					sum(cr) as cr,
				    (sum(dr)-sum(cr)) as balance
				FROM
					view_detail_account	where company_name LIKE "%'.$searchq.'%"  group by supplier_id order  by company_name ASC';
		}
		if($Page && !$searchq){
 	           $sql="SELECT
					supplier_id,
					company_name,
					sum(dr) as dr,
					sum(cr) as cr,
				    (sum(dr)-sum(cr)) as balance
				FROM
					view_detail_account	group by supplier_id order  by company_name ASC LIMIT $start, $limit";
		
		}
		if(!$Page && !$searchq){
 	            $sql="SELECT
					supplier_id,
					company_name,
					sum(dr) as dr,
					sum(cr) as cr,
				    (sum(dr)-sum(cr)) as balance
				FROM
					view_detail_account	group by supplier_id order  by company_name ASC	LIMIT 0, 29";
		
		}
			  
	$res= mysql_query($sql);
	
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table border=0 cellspacing=0 class="tableGrid" width="1000">
	            <tr> 
	            <th   align="left">S/L</th>
	            <th   align="left">Supplier</th>
				<th   align="left" nowrap>DR Amount</th>
				<th   align="left" nowrap>CR Amount</th>
				<th   align="left" nowrap>Balance</th>
           </tr>';
                          $rowcolor=0;
						  $i=1;
	while($row = mysql_fetch_array($res)){
					
					$dr=$row['dr'];
					$cr=$row['cr'];
					
					$balance=($dr-$cr);
					
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&supplier_id='.$row['supplier_id'].'">'.$row['company_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($balance,2).'&nbsp;</td>
				   
				</tr>';
	
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'evenClassStyle\'">
					
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&supplier_id='.$row['supplier_id'].'">'.$row['company_name'].'</a></td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'&nbsp;</td>
				    <td  style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'&nbsp;</td>
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
$supplier_id= getRequest('supplier_id');
    $sql = "SELECT
	supplier_id,
	company_name,
	contact_person,
	address,
	mobile,
	email,
	acc_no
FROM
	inv_supplier_info where supplier_id= $supplier_id";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	$company_name=$row['company_name'];
	$mobile= $row['mobile'];
	$address= $row['address'];

  
  
 	            $sql3="select sum(dr) as drAmnt, sum(cr) as crAmnt
				FROM view_detail_account where supplier_id= $supplier_id";
		
	   	        $ros3 = mysql_query($sql3);
			
		while($res3 = mysql_fetch_array($ros3)){
				$drAmnt				= $res3['drAmnt'];
				$crAmnt			= $res3['crAmnt'];
				}
				
				$balance = ($drAmnt-$crAmnt);
				
				
		$CollectionFetch = $this->CollectionReport($supplier_id);
		require_once(ACCOUNTS_TRANSACTION_VIEW_SKIN); 

				
	} // EOF
	
  function CollectionReport($supplier_id){
		 $sql = "select recdate,particular,bank_name,account_no, dr,cr from view_detail_account where supplier_id=$supplier_id order by recdate ";
 
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=600 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Particulars</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">DR Amount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">CR Amount</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){ 
							$dr=$row['dr'];
							$cr=$row['cr'];
							$balance2=($dr-$cr);
					
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['recdate'])."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particular']."-".$row['bank_name']."-".$row['account_no']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['dr'],2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($row['cr'],2)."</td>
		</tr>";
$totalDr = $totalDr+$row['dr'];
$totalCr = $totalCr+$row['cr'];
$totalBlnc = $totalBlnc+$balance2;
	$i++;
	}
	$speci .= "<tr>
		<td colspan=3 align=right><strong>Total : </strong></td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totalDr,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totalCr,2)."</td>
		</tr>
		<tr>
		<td nowrap=nowrap align=right colspan=5> <strong>Balance : ".number_format($totalBlnc,2)."</strong></td>
		</tr></table>";
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
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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
					<a href="?app=accounts_total&cmd=ViewDetailsTrans&store_id='.$row['store_id'].'">'.$row['store_name'].'</a></td>
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