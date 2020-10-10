<?php
class inv_item_sales_list_whole
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'Delete'             : $this->DeleteSales();                       	break;
		 case 'ajaxModel'	   		: $this->SelectModel(); 				break;
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

	 $query = "SELECT COUNT(*) as num FROM ".INV_ITEM_SALES_TBL." where sales_status='Not Pending' and branch_id=$branch_id and sales_type=2";
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
			$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=inv_item_sales_list_whole&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	}
	
	
	$ItemIssue = $this->ItemIssueFetch($start, $limit);
	$Customer = $this->SelectCustomer();
	$ItmCategory4Model=$this->ItmCategory4Model();
	$SelectModel=$this->SelectModel();

	require_once(CURRENT_APP_SKIN_FILE);
  }


function ItemIssueFetch($start = null, $limit = null){
$page= getRequest('page');
$sub_item_category_id = getRequest('sub_item_category_id');
$main_item_category_id = getRequest('main_item_category_id');
$branch_id = getFromSession('branch_id');
 $isfrom = formatDate4insert(getRequest('isfrom'));
	
	if($page && !$main_item_category_id && !$sub_item_category_id){
		 $sql="SELECT
				sales_id,
				item_id,
				sub_item_category_name,
				main_item_category_name,
				costprice,
				salesprice,
				sales_date,
				discount_percent,
				totaldiscount,
				person_id,
				total_amount,
				item_qnt,
				invoice,
				customer_id,
				store_name,
				sale_pay_id,
				branch_id,
				branch_name,
				sales_status,
				sales_type,
				sub_item_category_id,
				main_item_category_id
			FROM
				view_inv_item_sales
				where sales_status='Not Pending' and item_qnt!=0 and branch_id=$branch_id 
				and sales_type=2 and sales_date='$isfrom' order by sales_date DESC LIMIT $start, $limit";
				}
				if($main_item_category_id && !$sub_item_category_id && !$page){
				$sql="SELECT
				sales_id,
				item_id,
				sub_item_category_name,
				main_item_category_name,
				costprice,
				salesprice,
				sales_date,
				discount_percent,
				totaldiscount,
				person_id,
				total_amount,
				item_qnt,
				invoice,
				customer_id,
				store_name,
				sale_pay_id,
				branch_id,
				branch_name,
				sales_status,
				sales_type,
				sub_item_category_id,
				main_item_category_id
			FROM
				view_inv_item_sales
				where sales_status='Not Pending' and item_qnt!=0
				main_item_category_id='$main_item_category_id' and branch_id=$branch_id and sales_type=2 and sales_date='$isfrom' order by sales_date DESC";
				}
				if($sub_item_category_id && !$main_item_category_id && !$page){
					 $sql="SELECT
				sales_id,
				item_id,
				sub_item_category_name,
				main_item_category_name,
				costprice,
				salesprice,
				sales_date,
				discount_percent,
				totaldiscount,
				person_id,
				total_amount,
				item_qnt,
				invoice,
				customer_id,
				store_name,
				sale_pay_id,
				branch_id,
				branch_name,
				sales_status,
				sales_type,
				sub_item_category_id,
				main_item_category_id
			FROM
				view_inv_item_sales
				where sales_status='Not Pending' and item_qnt!=0 and sub_item_category_id='$sub_item_category_id'
				and branch_id=$branch_id and sales_type=2 and sales_date='$isfrom' order by sales_date DESC ";
				}
			
				if($sub_item_category_id && $main_item_category_id && !$page){
				 $sql="SELECT
				sales_id,
				item_id,
				sub_item_category_name,
				main_item_category_name,
				costprice,
				salesprice,
				sales_date,
				discount_percent,
				totaldiscount,
				person_id,
				total_amount,
				item_qnt,
				invoice,
				customer_id,
				store_name,
				sale_pay_id,
				branch_id,
				branch_name,
				sales_status,
				sales_type,
				sub_item_category_id,
				main_item_category_id
			FROM
				view_inv_item_sales
				where sales_status='Not Pending' and item_qnt!=0 and branch_id=$branch_id and sales_type=2 
				and sub_item_category_id='$sub_item_category_id and main_item_category_id='$main_item_category_id and sales_date='$isfrom'
				order by sales_date DESC LIMIT 0,29";
				}
				
				if(!$sub_item_category_id && !$main_item_category_id && !$page){
				$sql="SELECT
				sales_id,
				item_id,
				sub_item_category_name,
				main_item_category_name,
				costprice,
				salesprice,
				sales_date,
				discount_percent,
				totaldiscount,
				person_id,
				total_amount,
				item_qnt,
				invoice,
				customer_id,
				store_name,
				sale_pay_id,
				branch_id,
				branch_name,
				sales_status,
				sales_type,
				sub_item_category_id,
				main_item_category_id
			FROM
				view_inv_item_sales
				where sales_status='Not Pending' and item_qnt!=0 and branch_id=$branch_id and sales_type=2
				order by sales_date DESC LIMIT 0,29";
				}
				
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="100%">
	            <tr>
				<th align=left>SL.</th>
				<th align=left>Date</th>
				 <th nowrap=nowrap align=left>Brand</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Sales</th>
				 <th nowrap=nowrap align=left>Qnt.</th>
<!--				 <th nowrap=nowrap align=left>Disc(%)</th>
				 <th nowrap=nowrap align=left>Total Disc</th>
-->				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Invoice</th>
				 <th nowrap=nowrap align=left>Client</th>
<!--				 <th nowrap=nowrap align=left>Return</th>
-->				 <th nowrap=nowrap align=right>Delete</th>
				
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){

	$totaldiscount=$row['totaldiscount'];
	$discount_percent=$row['discount_percent'];
	
	

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where sale_pay_id=$salePayId and sales_type=2";
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
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
<!--			<td nowrap=nowrap>'.$discount_percent.'</td>
			<td nowrap=nowrap>'.$totaldiscount.'</td>
-->			<td nowrap=nowrap>'.$row['total_amount'].'</td>
			<td nowrap=nowrap><a href="?app=inv_item_sales_whole&cmd=ajaxInvoicePrintViewSkin&invoice='.$row['invoice'].'">'.$row['invoice'].'</td>
			<td nowrap=nowrap>'.$row['store_name'].'</td>
			<!--<td nowrap=nowrap>
			<a href="?app=whole_sales_return&sales_id='.$row['sales_id'].'&customer_id='.$row['customer_id'].'"><span class="label label-danger">Return</label></a></td>-->
			<td nowrap=nowrap align="right"><a href="?app=inv_item_sales_list_whole&cmd=Delete&sale_pay_id='.$row['sale_pay_id'].'" 
			onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
		$i++; }
		$type .= '</table>';
		return $type;
}//end fnc


function DeleteSales(){
	$sale_pay_id = getRequest('sale_pay_id');
	$branch_id = getFromSession('branch_id');
	
	$sql4 = "SELECT invoice FROM inv_item_sales where sale_pay_id='$sale_pay_id' and branch_id=$branch_id and sales_type=2";
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
	
	$sql = "DELETE from ".INV_ITEM_SALES_TBL." where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id and sales_type=2";
	$res=mysql_query($sql);
		if($res){
			$sql2 = "DELETE from inv_item_sales_payment where sale_pay_id = '$sale_pay_id' and branch_id=$branch_id and sales_type=2";
			$res2=mysql_query($sql2);

			$sql3 = "DELETE from whole_saler_acc where invoice = '$invoice' and branch_id=$branch_id and sales_type=2";
			$res3=mysql_query($sql3);
			
			header('location:?app=inv_item_sales_list_whole&msg=Item Successfully Deleted');
  		}else{
			header('location:?app=inv_item_sales_list_whole&msg=Item Not Deleted');
		}	
}  
 function SelectCustomer($cust = null){ 
 //$branch_id = getFromSession('branch_id');
		$sql="SELECT customer_id,mobile,company_name,person_id from customer_info i inner join settings s on s.company_id=i.company_id
		where customer_type=2 and i.branch_id=".getFromSession('branch_id')." ORDER BY company_name";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='customer_id' size='1' id='customer_id' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['customer_id'] == $cust){
			$branch_select .= "<option value='".$row['customer_id']."' selected = 'selected'>".$row['company_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['customer_id']."'>".$row['company_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
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


function SelectModel(){ 
$main_item_category_id=getRequest('main_item_category_id');

		 $sql="SELECT sub_item_category_id,	sub_item_category_name FROM inv_item_category_sub where main_item_category_id=$main_item_category_id order by sub_item_category_name";
	    $result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) {

	echo '<label style="font-weight:normal"><input name="sub_item_category_id" id="sub_item_category_id" type="radio" value="'.$row['sub_item_category_id'].'" />'.$row['sub_item_category_name'].'</label><br>';	
		}

}


} // End class
?>
