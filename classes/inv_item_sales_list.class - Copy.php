<?php
class inv_item_sales_list
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'DeleteSales'             : $this->DeleteSales();                       	break;
		 case 'list'                  	: $this->getList();                       		break;
		 default                      	: $cmd == 'list'; $this->getList();	       		break;
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

	 $query = "SELECT COUNT(*) as num FROM ".INV_ITEM_SALES_TBL." where sales_status='Not Pending' and branch_id=$branch_id and sales_type=1";
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
	require_once(CURRENT_APP_SKIN_FILE);
  }


function ItemIssueFetch($start = null, $limit = null){
$page= getRequest('page');
$sub_item_category_id = getRequest('sub_item_category_id');
$sales_date = formatDate4insert(getRequest('sales_date'));
$branch_id = getFromSession('branch_id');
	if($page){
		 $sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				totaldiscount,
				s.person_id,
				total_amount,
				item_qnt,
				invoice,
				person_name,
				cdate,
				sale_pay_id,
				sub_item_category_name,
				main_item_category_name
				
			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				inner join ".HRM_PERSON_TBL." p on s.person_id=p.person_id 
				where sales_status='Not Pending' and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC LIMIT $start, $limit";
				}
				if($sub_item_category_id){
				$sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				totaldiscount,
				s.person_id,
				total_amount,
				item_qnt,
				invoice,
				person_name,
				cdate,
				sale_pay_id,
				sub_item_category_name,
				main_item_category_name
				
			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				inner join ".HRM_PERSON_TBL." p on s.person_id=p.person_id 
				where sales_status='Not Pending' and s.sub_item_category_id='$sub_item_category_id'
				and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC";
				}
				if($sales_date){
				$sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				totaldiscount,
				s.person_id,
				total_amount,
				item_qnt,
				invoice,
				person_name,
				cdate,
				sale_pay_id,
				sub_item_category_name,
				main_item_category_name
				
			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				inner join ".HRM_PERSON_TBL." p on s.person_id=p.person_id 
				where sales_status='Not Pending' and sales_date='$sales_date'
				and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC";
				}
				if($sales_date && $sub_item_category_id){
					$sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				totaldiscount,
				s.person_id,
				total_amount,
				item_qnt,
				invoice,
				person_name,
				cdate,
				sale_pay_id,
				sub_item_category_name,
				main_item_category_name
				
			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				inner join ".HRM_PERSON_TBL." p on s.person_id=p.person_id
				where sales_status='Not Pending' and sales_date='$sales_date' and s.sub_item_category_id='$sub_item_category_id' 
				and s.branch_id=$branch_id and sales_type=1
				order by sales_date DESC LIMIT 0,29 ";
				}
			
				if(!$sales_date&& !$sub_item_category_id && !$page){
				$sql="SELECT
				sales_id,
				s.item_id,
				costprice,
				salesprice,
				sales_date,
				totaldiscount,
				s.person_id,
				total_amount,
				item_qnt,
				invoice,
				person_name,
				cdate,
				sale_pay_id,
				sub_item_category_name,
				main_item_category_name
				
			FROM
				".INV_ITEM_SALES_TBL." s inner join ".INV_ITEMINFO_TBL." i on s.item_id=i.item_id
				inner join inv_item_category_sub sb on sb.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=sb.main_item_category_id
				inner join ".HRM_PERSON_TBL." p on s.person_id=p.person_id 
				where sales_status='Not Pending' and s.branch_id=$branch_id and sales_type=1
				order by sales_date DESC LIMIT 0,29";
				}
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="100%">
	            <tr>
				<th align=left>Date</th>
				 <th nowrap=nowrap align=left>Brand</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Sales</th>
				 <th nowrap=nowrap align=left>Qnt.</th>
				 <th nowrap=nowrap align=left>Discount</th>
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Invoice</th>
				 <th nowrap=nowrap align=left>Salesman</th>
				 <th nowrap=nowrap align=left>Delete</th>
				
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){

	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];
	
	

	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId and sales_type=1";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">	
			<td nowrap=nowrap>'._date($row['sales_date']).'</td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
			<td nowrap=nowrap>'.$NwDisc.'</td>
			<td nowrap=nowrap>'.$row['total_amount'].'</td>
			<td nowrap=nowrap><a href="?app=inv_item_sales&cmd=ajaxInvoicePrintViewSkin&invoice='.$row['invoice'].'">'.$row['invoice'].'</td>
			<td nowrap=nowrap>'.$row['person_name'].'</td>
			<td nowrap=nowrap><a href="?app=inv_item_sales_list&cmd=DeleteSales&sales_id='.$row['sales_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc


function DeleteSales(){
	$sale_pay_id = getRequest('sale_pay_id');
	$branch_id = getFromSession('branch_id');
	
		$sql4 = "SELECT invoice FROM inv_item_sales where sale_pay_id='$sale_pay_id' and branch_id=$branch_id and sales_type=1";
		$res4 = mysql_query($sql4);	
		$row4 = mysql_fetch_array($res4);
		
		$invoice=$row4['invoice'];//exit();

	
/*		$sql2 = "SELECT sale_pay_id, total_amount FROM inv_item_sales where sales_id='$sales_id' and branch_id=$branch_id and sales_type=2";
		$res2 = mysql_query($sql2);	
		$row2 = mysql_fetch_array($res2);
		
		$sale_pay_id2=$row2['sale_pay_id'];
		$total_amount2=$row2['total_amount'];

		$sql3 = "SELECT paid_amount FROM inv_item_sales_payment where sale_pay_id='$sale_pay_id2' and branch_id=$branch_id and sales_type=2";
		$res3 = mysql_query($sql3);	
		$row3 = mysql_fetch_array($res3);
		
		$paid_amount3=$row3['paid_amount'];
		
		$nowAmnt=($paid_amount3-$total_amount2);
		

		$sql4 = "UPDATE inv_item_sales_payment SET total_amount ='$nowAmnt', paid_amount ='$nowAmnt' WHERE sale_pay_id='$sale_pay_id2' and branch_id=$branch_id and sales_type=2";
		$res4 = mysql_query($sql4);	
*/
	
	$sql = "DELETE from ".INV_ITEM_SALES_TBL." where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id and sales_type=1";
	$res=mysql_query($sql);
		if($res){
			$sql2 = "DELETE from inv_item_sales_payment where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id and sales_type=1";
			$res2=mysql_query($sql2);

			$sql3 = "DELETE from whole_saler_acc where invoice = '$invoice' and branch_id=$branch_id and sales_type=1";
			$res3=mysql_query($sql3);
			
			header('location:?app=inv_item_sales_list&msg=Item Successfully Deleted');
  		}else{
			header('location:?app=inv_item_sales_list&msg=Item Not Deleted');
		}	
}  

/*function DeleteSalesSkin(){
	$sales_id = getRequest('sales_id');
  	if($sales_id)  {
		 $sql = "DELETE from ".INV_ITEM_SALES_TBL." where sales_id = '$sales_id'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=inv_item_sales&msg=Item Deleted');
		}else{
		header('location:?app=inv_item_sales&msg=Not Deleted');
		}
  	}	
}  


*/

} // End class
?>
