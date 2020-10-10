<?php
class inv_item_sales_list
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'SalesList2Skin'  : echo $this->SalesList2Skin();                       	break;
		 case 'Delete'             : $this->DeleteSales();                       	break;
		 case 'ajaxModel'	   		: $this->SelectModel(); 				break;
		 case 'ajaxModel2'	   		: $this->SelectModel2(); 				break;
		 case 'list'              : $this->getList();                       		break;
		 default                  : $cmd == 'list'; $this->getList();	       		break;
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

	 $query = "SELECT COUNT(*) as num FROM ".INV_ITEM_SALES_TBL." where sales_status='Not Pending' and sales_type=1";
	$total_pages = mysql_fetch_array(mysql_query($query));
	 $total_pages = $total_pages[num]; //exit();
	
	/* Setup vars for query. */
	//$targetpage = "?app=client_profile"; 	//your file name  (the name of this file)
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
			$pagination.= "<a href=\"?app=inv_item_sales_list&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=inv_item_sales_list&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=inv_item_sales_list&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_sales_list&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_sales_list&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=inv_item_sales_list&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	}
	
	
	$ItemIssue = $this->ItemIssueFetch($start, $limit);
	$Customer = $this->SelectCustomer();
	$curdate=dateInputFormatDMY(SelectCDate());
	
$customer_id = getRequest('customer_id');
$from = formatDate4insert(getRequest('from'));
$to = formatDate4insert(getRequest('to'));

if($customer_id && $from && $to){
				$sql="select sum(total_amount) as total_amount, sum(paid_amount) as paid_amount from  inv_item_sales_payment
						where customer_id='$customer_id' and sales_date between '$from' and '$to'";
				}

			if($customer_id && !$from && !$to){
				$sql="select sum(total_amount) as total_amount, sum(paid_amount) as paid_amount from  inv_item_sales_payment
						where customer_id='$customer_id'";
				}				
				
				if(!$customer_id && $from && $to){
					$sql="select sum(total_amount) as total_amount, sum(paid_amount) as paid_amount from  inv_item_sales_payment
					where sales_date between '$from' and '$to' ";
				}
				if(!$customer_id && !$from && !$to){
					$sql="select sum(total_amount) as total_amount, sum(paid_amount) as paid_amount from  inv_item_sales_payment";
				}
			
			$res= mysql_query($sql);
			$row = mysql_fetch_array($res);
			$total_amount=$row['total_amount'];
			$paid_amount=$row['paid_amount'];

	require_once(CURRENT_APP_SKIN_FILE);
  }


function ItemIssueFetch($start = null, $limit = null){
$page= getRequest('page');
$customer_id = getRequest('customer_id');
$from = formatDate4insert(getRequest('from'));
$to = formatDate4insert(getRequest('to'));
$branch_id = getFromSession('branch_id');
	if($page && !$customer_id && !$from && !$to){
						$sql="select 
						sales_id,
						s.item_id,
						sub_item_category_name,
						main_item_category_name,
						costprice,
						salesprice,
						sales_date,
						item_qnt,
						return_qnt,
						total_amount,
						return_amount,
						discount_percent,
						totaldiscount,
						s.person_id,
						(total_amount - return_amount) AS totalAmount,
						(item_qnt -return_qnt) AS net_qnt,
						s.customer_id,
						store_name,
						s.sale_pay_id,
						s.branch_id,
						sales_status,
						sales_type,
						invoice as memo_no,
						i.sub_item_category_id,
						sb.main_item_category_id,
						s.cdate
						from inv_item_sales s 
						inner join inv_iteminfo i on s.item_id = i.item_id 
						inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
						inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
						left join customer_info c on c.customer_id = s.customer_id
						where sales_status='Not Pending' and s.branch_id=$branch_id 
						and sales_type=1 group by invoice order by sales_date DESC LIMIT $start, $limit";
}
if($customer_id && $from && $to && !$page){
				$sql="select 
						sales_id,
						s.item_id,
						sub_item_category_name,
						main_item_category_name,
						costprice,
						salesprice,
						sales_date,
						item_qnt,
						return_qnt,
						total_amount,
						return_amount,
						discount_percent,
						totaldiscount,
						s.person_id,
						(total_amount - return_amount) AS totalAmount,
						(item_qnt -return_qnt) AS net_qnt,
						s.customer_id,
						store_name,
						s.sale_pay_id,
						s.branch_id,
						sales_status,
						sales_type,
						invoice as memo_no,
						i.sub_item_category_id,
						sb.main_item_category_id,
						s.cdate
						from inv_item_sales s 
						inner join inv_iteminfo i on s.item_id = i.item_id 
						inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
						inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
						left join customer_info c on c.customer_id = s.customer_id
						where sales_status='Not Pending' and s.customer_id='$customer_id' and sales_date between '$from' and '$to' 
						and s.branch_id=$branch_id and sales_type=1 group by invoice order by sales_date DESC";
				}

			if($customer_id && !$from && !$to && !$page){
				$sql="select 
						sales_id,
						s.item_id,
						sub_item_category_name,
						main_item_category_name,
						costprice,
						salesprice,
						sales_date,
						item_qnt,
						return_qnt,
						total_amount,
						return_amount,
						discount_percent,
						totaldiscount,
						s.person_id,
						(total_amount - return_amount) AS totalAmount,
						(item_qnt -return_qnt) AS net_qnt,
						s.customer_id,
						store_name,
						s.sale_pay_id,
						s.branch_id,
						sales_status,
						sales_type,
						invoice as memo_no,
						i.sub_item_category_id,
						sb.main_item_category_id,
						s.cdate
						from inv_item_sales s 
						inner join inv_iteminfo i on s.item_id = i.item_id 
						inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
						inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
						left join customer_info c on c.customer_id = s.customer_id
						where sales_status='Not Pending' and s.customer_id='$customer_id' and s.branch_id=$branch_id 
						and sales_type=1 group by invoice order by sales_date DESC";
				}				
				
				if(!$customer_id && $from && $to && !$page){
					$sql="select 
						sales_id,
						s.item_id,
						sub_item_category_name,
						main_item_category_name,
						costprice,
						salesprice,
						sales_date,
						item_qnt,
						return_qnt,
						total_amount,
						return_amount,
						discount_percent,
						totaldiscount,
						s.person_id,
						(total_amount - return_amount) AS totalAmount,
						(item_qnt -return_qnt) AS net_qnt,
						s.customer_id,
						store_name,
						s.sale_pay_id,
						s.branch_id,
						sales_status,
						sales_type,
						invoice as memo_no,
						i.sub_item_category_id,
						sb.main_item_category_id,
						s.cdate
						from inv_item_sales s 
						inner join inv_iteminfo i on s.item_id = i.item_id 
						inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
						inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
						left join customer_info c on c.customer_id = s.customer_id
					where sales_status='Not Pending' and sales_date between '$from' and '$to' 
					and s.branch_id=$branch_id and sales_type=1 group by invoice order by sales_date DESC";
				}
	if(!$customer_id && !$from && !$to && !$page){
					$sql="select 
						sales_id,
						s.item_id,
						sub_item_category_name,
						main_item_category_name,
						costprice,
						salesprice,
						sales_date,
						item_qnt,
						return_qnt,
						total_amount,
						return_amount,
						discount_percent,
						sum(totaldiscount) as totaldiscount,
						s.person_id,
						(total_amount - return_amount) AS totalAmount,
						(item_qnt -return_qnt) AS net_qnt,
						s.customer_id,
						store_name,
						s.sale_pay_id,
						s.branch_id,
						sales_status,
						sales_type,
						invoice as memo_no,
						i.sub_item_category_id,
						sb.main_item_category_id,
						s.cdate
						from inv_item_sales s 
						inner join inv_iteminfo i on s.item_id = i.item_id 
						inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
						inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
						left join customer_info c on c.customer_id = s.customer_id
						where sales_status='Not Pending' and s.branch_id=$branch_id and sales_type=1 
						group by invoice order by sales_date DESC LIMIT 0,100";
	}
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="100%">
	            <tr>
				<th align=left>SL.</th>
				<th align=left>Date</th>
				 <th nowrap=nowrap align=left>Time</th>
<!--				 <th nowrap=nowrap align=left>Brand</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Sales Price</th>
				 <th nowrap=nowrap align=left>Sales Qnt.</th>
				 <th nowrap=nowrap align=left>Ret Qnt</th>
				 <th nowrap=nowrap align=left>Net Qnt</th>
				 <th nowrap=nowrap align=left>Disc(%)</th>
				 <th nowrap=nowrap align=left>Dis</th>
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Ret. Amount</th>
				 <th nowrap=nowrap align=left>Net Amount</th>
-->				 <th nowrap=nowrap align=left>Invoice</th>
				 <th nowrap=nowrap align=left>Client</th>
				 <th nowrap=nowrap align=left>Sales Amount</th>
				 <th nowrap=nowrap align=left>Cash Sales</th>
				 <th nowrap=nowrap align=right></th>
				
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){
			$Crdate=date_create($row['cdate']);
			$cdate=date_format($Crdate,"H:i:s A");
			$totaldiscount=$row['totaldiscount'];
			$total_amount=$row['total_amount'];
			$return_amount=$row['return_amount'];
			$TotalAmount=($total_amount-$totaldiscount);
			$item_qnt=$row['item_qnt'];
			$sale_pay_id=$row['sale_pay_id'];
			$return_qnt=$row['return_qnt'];
			$netQnt=($item_qnt-$return_qnt);
			$netAmnt=($TotalAmount-$return_amount);//&cmd=SalesList2Skin
			
			$customer_id=$row['customer_id'];
			
			//if($customer_id!='0'){
				$View='<a href="?app=inv_item_sales_list&cmd=SalesList2Skin&invoice='.$row['memo_no'].'">'.$row['memo_no'].'</a>';
			/*}else{
				$View='<a href="?app=inv_item_sales&cmd=SalesList2Skin&invoice='.$row['memo_no'].'">'.$row['memo_no'].'</a>';
			}*/

          if($customer_id!='0'){
		  		$sals='';
		  }else{
		  			$sals='Cash';
		  }
			
	 $sql2="SELECT sum(total_amount) as total_amount, sum(paid_amount) as paid_amount, sum(dues_amount) as dues_amount FROM inv_item_sales_payment where sale_pay_id=$sale_pay_id";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $total_amount2=$row2['total_amount'];
	 $paid_amount2=$row2['paid_amount'];
	 $dues_amount2=$row2['dues_amount'];
		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">	
			<td nowrap=nowrap>'.$i.'</td>
			<td nowrap=nowrap>'._date($row['sales_date']).'</td>
			<td nowrap=nowrap>'.$cdate.'</td>
<!--			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
			<td nowrap=nowrap>'.$row['return_qnt'].'</td>
			<td nowrap=nowrap>'.$netQnt.'</td>
			<td nowrap=nowrap>'.$discount_percent.'</td>
			<td nowrap=nowrap>'.$totaldiscount.'</td>
			<td nowrap=nowrap>'.$TotalAmount.'</td>
			<td nowrap=nowrap>'.$row['return_amount'].'</td>
			<td nowrap=nowrap>'.$netAmnt.'</td>
-->			<td nowrap=nowrap>'.$View.'&nbsp;&nbsp;</td>
			<td nowrap=nowrap>'.$row['store_name'].''.$sals.'</td>
			<td nowrap=nowrap>'.$total_amount2.'</td>
			<td nowrap=nowrap>'.$paid_amount2.'</td>
			<!--<td nowrap=nowrap>
			<a href="?app=whole_sales_return&sales_id='.$row['sales_id'].'&customer_id='.$row['customer_id'].'">
			<span class="label label-danger" style="font-size:10px;">Return</label></a></td>-->
			<!--<td nowrap=nowrap>
			<a href="?app=whole_sales_return&sales_id='.$row['sales_id'].'&customer_id='.$row['customer_id'].'">
			<span class="label label-danger" style="font-size:10px;">Return with Sales</label></a></td>-->
			<td nowrap=nowrap align="right"><a href="?app=inv_item_sales_list&cmd=Delete&sale_pay_id='.$row['sale_pay_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
		$i++; }
		$type .= '</table>';
		return $type;
}//end fnc


function DeleteSales(){
	$sale_pay_id = getRequest('sale_pay_id');
	$branch_id = getFromSession('branch_id');
	
	$sql4 = "SELECT invoice FROM inv_item_sales where sale_pay_id='$sale_pay_id' and branch_id=$branch_id ";
		$res4 = mysql_query($sql4);	
		$row4 = mysql_fetch_array($res4);
		
		$invoice=$row4['invoice'];//exit();
	
	
	$sql = "DELETE from ".INV_ITEM_SALES_TBL." where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id ";
	$res=mysql_query($sql);
		if($res){
			$sql2 = "DELETE from inv_item_sales_payment where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id";
			$res2=mysql_query($sql2);

			$sql3 = "DELETE from whole_saler_acc where invoice = '$invoice' and branch_id=$branch_id";
			$res3=mysql_query($sql3);
			
			header('location:?app=inv_item_sales_list&msg=Item Successfully Deleted');
  		}else{
			header('location:?app=inv_item_sales_list&msg=Item Not Deleted');
		}	
}  
 function SelectCustomer($cust = null){ 
 $branch_id = getFromSession('branch_id');
		$sql="SELECT customer_id,store_name from customer_info  ORDER BY store_name";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='customer_id' size='1' id='customer_id' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['customer_id'] == $cust){
			$branch_select .= "<option value='".$row['customer_id']."' selected = 'selected'>".$row['store_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['customer_id']."'>".$row['store_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 


function SalesList2Skin(){
$SalesList2=$this->SalesList2();
require_once(ITEM_SALES_LIST2_SKIN);
}


function SalesList2(){
$invoice = getRequest('invoice');
$sub_item_category_id = getRequest('sub_item_category_id');
$customer_id = getRequest('customer_id');
$from = formatDate4insert(getRequest('from'));
$to = formatDate4insert(getRequest('to'));
$branch_id = getFromSession('branch_id');

/*if($customer_id && $sub_item_category_id){
		$sql="select 
		sales_id,
		s.item_id,
		sub_item_category_name,
		main_item_category_name,
		costprice,
		salesprice,
		sales_date,
		item_qnt,
		return_qnt,
		total_amount,
		return_amount,
		discount_percent,
		totaldiscount,
		s.person_id,
		(total_amount - return_amount) AS totalAmount,
		(item_qnt -return_qnt) AS net_qnt,
		s.customer_id,
		name,
		s.sale_pay_id,
		s.branch_id,
		sales_status,
		sales_type,
		invoice as memo_no,
		i.sub_item_category_id,
		sb.main_item_category_id 
		from inv_item_sales s 
		inner join inv_iteminfo i on s.item_id = i.item_id 
		inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
		inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
		left join customer_info c on c.customer_id = s.customer_id 
		where sales_status='Not Pending' 
		and s.customer_id='$customer_id' and i.sub_item_category_id=$sub_item_category_id 
		and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC";
}
				
elseif($from && $to){
		$sql="select 
		sales_id,
		s.item_id,
		sub_item_category_name,
		main_item_category_name,
		costprice,
		salesprice,
		sales_date,
		item_qnt,
		return_qnt,
		total_amount,
		return_amount,
		discount_percent,
		totaldiscount,
		s.person_id,
		(total_amount - return_amount) AS totalAmount,
		(item_qnt -return_qnt) AS net_qnt,
		s.customer_id,
		name,
		s.sale_pay_id,
		s.branch_id,
		sales_status,
		sales_type,
		invoice as memo_no,
		i.sub_item_category_id,
		sb.main_item_category_id 
		from inv_item_sales s 
		inner join inv_iteminfo i on s.item_id = i.item_id 
		inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
		inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
		left join customer_info c on c.customer_id = s.customer_id 
		where sales_status='Not Pending' and sales_date between '$from' and '$to' 
		and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC";
	}

else{
*/		$sql="select 
		sales_id,
		s.item_id,
		sub_item_category_name,
		main_item_category_name,
		costprice,
		salesprice,
		sales_date,
		item_qnt,
		item_size,
		return_qnt,
		total_amount,
		return_amount,
		discount_percent,
		totaldiscount,
		s.person_id,
		total_amount AS totalAmount,
		item_qnt AS net_qnt,
		s.customer_id,
		store_name,
		s.sale_pay_id,
		s.branch_id,
		sales_status,
		sales_type,
		invoice as memo_no,
		i.sub_item_category_id,
		sb.main_item_category_id 
		from inv_item_sales s 
		inner join inv_iteminfo i on s.item_id = i.item_id 
		inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
		inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
		left join customer_info c on c.customer_id = s.customer_id 
		where sales_status='Not Pending' and s.branch_id=$branch_id and sales_type=1 and invoice='$invoice' order by sales_date DESC";
	//}
				
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="100%">
	            <tr>
				<th align=left>SL.</th>
				<th align=left>Date</th>
				 <th nowrap=nowrap align=left>Brand</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Sales Price</th>
				 <th nowrap=nowrap align=left>Sales Qnt.</th>
				 <th nowrap=nowrap align=left>Ret Qnt</th>
				 <th nowrap=nowrap align=left>Net Qnt</th>
<!--				 <th nowrap=nowrap align=left>Disc(%)</th>-->
				 <th nowrap=nowrap align=left>Dis</th>
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Ret. Amount</th>
				 <th nowrap=nowrap align=left>Net Amount</th>
				 <th nowrap=nowrap align=left>Invoice</th>
				 <th nowrap=nowrap align=left>Customer</th>
				 <th nowrap=nowrap align=left>Date</th>
				<th nowrap=nowrap align=right></th>
				 <!-- <th nowrap=nowrap align=right>Delete</th>-->
				
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){

	$totaldiscount=$row['totaldiscount'];
	$total_amount=$row['total_amount'];
	$return_amount=$row['return_amount'];
	$TotalAmount=($total_amount-$totaldiscount);
	$item_qnt=$row['item_qnt'];
	$return_qnt=$row['return_qnt'];
	$customer_id=$row['customer_id'];
	$sales_date=$row['sales_date'];
	$netQnt=($item_qnt-$return_qnt);
	$netAmnt=($TotalAmount-$return_amount);
	

			if($customer_id!='0'){
				$View='<a href="?app=inv_item_sales_emi&cmd=ajaxInvoicePrintViewSkin&invoice='.$row['memo_no'].'">'.$row['memo_no'].'</a>';
			}else{
				$View='<a href="?app=inv_item_sales&cmd=ajaxInvoicePrintViewSkin&invoice='.$row['memo_no'].'">'.$row['memo_no'].'</a>';
			}


			if($customer_id!='0'){
				$Edit='<a href="?app=inv_item_sales_emi_edit&invoice='.$row['memo_no'].'&customer_id='.$row['customer_id'].'&sales_date='.$row['sales_date'].'&sale_pay_id='.$row['sale_pay_id'].'" onclick="return confirmSalesEdit();" >
				<span class="label label-success" style="font-size:10px;">Edit Invoice</label></a>';
			}else{
				$Edit='<a href="?app=inv_item_sales_edit&invoice='.$row['memo_no'].'&sales_date='.$row['sales_date'].'&sale_pay_id='.$row['sale_pay_id'].'" onclick="return confirmSalesEdit();" >
				<span class="label label-success" style="font-size:10px;">Edit Invoice</label></a>';
			}
	

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId and sales_type=1";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
*/		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">	
			<td nowrap=nowrap>'.$i.'</td>
			<td nowrap=nowrap>'._date($row['sales_date']).'</td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'<br>'.$row['item_size'].'</td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
			<td nowrap=nowrap>'.$row['return_qnt'].'</td>
			<td nowrap=nowrap>'.$netQnt.'</td>
<!--			<td nowrap=nowrap>'.$discount_percent.'</td>-->
			<td nowrap=nowrap>'.$totaldiscount.'</td>
			<td nowrap=nowrap>'.$TotalAmount.'</td>
			<td nowrap=nowrap>'.$row['return_amount'].'</td>
			<td nowrap=nowrap>'.$netAmnt.'</td>
			<td nowrap=nowrap>'.$View.'</td>
			<td nowrap=nowrap>'.$row['store_name'].'</td>
			<td nowrap=nowrap>'._date($row['sales_date']).'</td>
			<td nowrap=nowrap>'.$Edit.'</td>
			<!--<td nowrap=nowrap>
			<a href="?app=whole_sales_return&sales_id='.$row['sales_id'].'&customer_id='.$row['customer_id'].'">
			<span class="label label-danger" style="font-size:10px;">Return with Sales</label></a></td>-->
			<!--<td nowrap=nowrap align="right"><a href="?app=inv_item_sales_list&cmd=Delete&sale_pay_id='.$row['sale_pay_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>-->
			</tr>';
		$i++; }
		$type .= '</table>';
		return $type;
}//end fnc

} // End class
?>
