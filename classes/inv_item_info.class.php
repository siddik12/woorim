<?php
class inv_item_info
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxcheckExistingItem'  		: $this->CheckExistingItem(getRequest('item_code'),getRequest('challan_no')); 			break;
		 case 'ajaxSearchItemInfo' 			: echo $this->ItemInfoFetch();  						        break;
		 case 'ajaxInsertItemInfo'          : echo $this->InsertItemInfo();									break;
		 case 'ajaxDeleteItemInfo'          : echo $this->DeleteItemInfo();									break;
		 case 'ajaxEditItemInfo'            : echo $this->EditItemInfo();									break;
         case 'list'                  		: $this->getList();                       						break;
         default                      		: $cmd == 'list'; $this->getList();	       						break;
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

	 $query = "SELECT COUNT(*) as num FROM ".INV_ITEMINFO_TBL." where branch_id=$branch_id ";
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
			$pagination.= "<a href=\"?app=inv_item_info&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=inv_item_info&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=inv_item_info&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_info&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_info&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=inv_item_info&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_info&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_info&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_info&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_info&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=inv_item_info&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_info&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_info&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=inv_item_info&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	
	} 


 $ItemInfo = $this->ItemInfoFetch($start, $limit);
$catList=$this->SelectItmCategory();
$SuplierList=$this->SelectSupplier();
$branch_dropdown = $this->SelectBranch();
require_once(CURRENT_APP_SKIN_FILE);
  }

function CheckExistingItem($item_code,$challan_no){
$branch_id = getFromSession('branch_id');
		  $sql = "SELECT item_code from ".INV_ITEMINFO_TBL." where item_code = '$item_code' and challan_no = '$challan_no' and branch_id=$branch_id";
		  $item_code = mysql_num_rows(mysql_query($sql));
		  echo $item_code.'######';	
	}
   // ===== End chkEmailExistence ========   


function InsertItemInfo(){
 $sub_item_category_id = getRequest('sub_item_category_id');
 $item_name = getRequest('item_name');
 $cost_price = getRequest('cost_price');
 $sales_price = getRequest('sales_price');
 $whole_sales_price = getRequest('whole_sales_price');
 $branch_id = getRequest('branch_id');
 $challan_no = getRequest('challan_no');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $item_code = getRequest('item_code');
 $quantity = getRequest('quantity');
 $item_size = getRequest('item_size');
 $supplier_ID = getRequest('supplier_ID');
 $description = getRequest('description');
 $createdby = getFromSession('username');
 $createddate = date('Y-m-d H:i:s');

    	 $sql = "INSERT INTO ".INV_ITEMINFO_TBL."(sub_item_category_id,item_name,cost_price,sales_price,whole_sales_price,item_code,quantity,receive_date,challan_no,branch_id,supplier_ID,item_size,description,createdby, createddate)
		 values($sub_item_category_id,'$item_name','$cost_price','$sales_price','$whole_sales_price','$item_code','$quantity','$receive_date','$challan_no','$branch_id','$supplier_ID','$item_size','$description','$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
			echo $ItemType = $this->ItemInfoFetch();
			}
  }

function EditItemInfo(){
 $item_id = getRequest('item_id');
 $sub_item_category_id = getRequest('sub_item_category_id');
 $item_name = getRequest('item_name');
 $cost_price = getRequest('cost_price');
 $sales_price = getRequest('sales_price');
 $whole_sales_price = getRequest('whole_sales_price');
 $challan_no = getRequest('challan_no');
 $branch_id = getRequest('branch_id');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $item_code = getRequest('item_code');
 $quantity = getRequest('quantity');
 $item_size = getRequest('item_size');
 $supplier_ID = getRequest('supplier_ID');
 $description = getRequest('description');

	$sql = "UPDATE ".INV_ITEMINFO_TBL." set  sub_item_category_id='$sub_item_category_id', item_name='$item_name',cost_price='$cost_price',sales_price='$sales_price',whole_sales_price='$whole_sales_price',challan_no='$challan_no', receive_date='$receive_date', item_code='$item_code',quantity='$quantity',supplier_ID='$supplier_ID',branch_id=$branch_id,item_size='$item_size', description='$description' where item_id='$item_id' ";
	$res = mysql_query($sql);
	if($res){
		echo $ItemType = $this->ItemInfoFetch();
	}
}  

function DeleteItemInfo(){
	$item_id = getRequest('item_id');
  	if($item_id)  {
		 $sql = "DELETE from ".INV_ITEMINFO_TBL." where item_id = '$item_id'";
		$res = mysql_query($sql);	
		if($res){
		echo $ItemType = $this->ItemInfoFetch();
		}
  	}	
}  

function ItemInfoFetch($start = null, $limit = null){
$searchq 	= getRequest('searchq');
$page 	= getRequest('page');
$branch_id = getFromSession('branch_id');
if($searchq){
	  $sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.supplier_ID,
	company_name,
	item_code,
	quantity,
	challan_no,
	receive_date,
	i.createdby,
	i.createddate,
	i.branch_id,
	branch_name
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_ID=i.supplier_ID 
	inner join branch b on b.branch_id=i.branch_id
	where i.branch_id=$branch_id and item_name LIKE '%".$searchq."%' or item_code LIKE '%".$searchq."%' or company_name LIKE '%".$searchq."%' 
	order by receive_date desc LIMIT 0, 2"; 
	}if($page){
		$sql="SELECT
		item_id,
		i.sub_item_category_id,
		sub_item_category_name,
		item_size,
		description,
		item_name,
		cost_price,
		sales_price,
		whole_sales_price,
		i.supplier_ID,
		company_name,
		item_code,
		quantity,
		challan_no,
		receive_date,
		i.createdby,
		i.createddate,
		i.branch_id,
		branch_name
	FROM
		".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
		inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_ID=i.supplier_ID  
		inner join branch b on b.branch_id=i.branch_id where i.branch_id=$branch_id order by receive_date desc LIMIT $start, $limit"; 
	}
if(!$page && !$searchq){
	$sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	item_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.supplier_ID,
	company_name,
	item_code,
	quantity,
	challan_no,
	receive_date,
	i.createdby,
	i.createddate,
	i.branch_id,
	branch_name
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_ID=i.supplier_ID  
	inner join branch b on b.branch_id=i.branch_id where i.branch_id=$branch_id order by receive_date desc LIMIT 0, 29"; 
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width="1200" cellspacing=0 class="tableGrid" >
	            <tr>
				<th>SL</th>
	             <th nowrap=nowrap align=left>Item Name</th>
	             <th nowrap=nowrap align=left>Item Code</th>
	             <th nowrap=nowrap align=left>Quantity</th>
				 <th nowrap=nowrap align=left>Category</th>
				 <th nowrap=nowrap align=left>Cost</th>
				 <th nowrap=nowrap align=left>Sales(retail)</th>
				 <th nowrap=nowrap align=left>Sales(whole)</th>
				 <th nowrap=nowrap align=left>Size</th>
				 <th nowrap=nowrap align=left>Supplier</th>
				 <th nowrap=nowrap align=left>Challan</th>
				 <th nowrap=nowrap align=left>Received</th>
				 <th nowrap=nowrap align=left>Description</th>
				 <th nowrap=nowrap align=left>Created By</th>
				 <th nowrap=nowrap align=left>Date</th>
<!--				 <th nowrap=nowrap align=left>Branch</th>
-->				<th></th>
				<th></th>
	       </tr>';
		$rowcolor=0;$i=1;
		while($row = mysql_fetch_array($res)){
 			
			
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			 
			 
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			
			<td nowrap=nowrap>'.$i.'&nbsp;</td>
			<td nowrap=nowrap>'.$row['item_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['item_code'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['quantity'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cost_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['sales_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['whole_sales_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['item_size'].'&nbsp;</td>
			<td>'.$row['company_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['challan_no'].'&nbsp;</td>
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['description'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['createdby'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['branch_name'].'&nbsp;</td>
-->			<td width="30"><a onClick="javascript:ajaxCall4EditItem(\''.$row['item_id'].'\',\''.
					 									  $row['sub_item_category_id'].'\',\''.
					 									  $row['item_name'].'\',\''.
					 									  $row['item_code'].'\',\''.
					 									  $row['quantity'].'\',\''.
					 									  $row['cost_price'].'\',\''.
					 									  $row['sales_price'].'\',\''.
					 									  $row['whole_sales_price'].'\',\''.
					 									  $row['supplier_ID'].'\',\''.
					 									  $row['branch_id'].'\',\''.
					 									  $row['challan_no'].'\',\''.
					 									  dateInputFormatDMY($row['receive_date']).'\',\''.
														  $row['item_size'].'\',\''.
						                                  $row['description'].'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>
			<td width="30"><a href="javascript:ajaxCall4DeleteItem(\''.$row['item_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
			
			$i++;
		}
		$type .= '</table>';
		return $type;
}//end fnc

function SelectItmCategory($cate = null){ 
		$sql="SELECT sub_item_category_id, sub_item_category_name FROM ".INV_CATEGORY_SUB_TBL." ORDER BY sub_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='sub_item_category_id' size='1' id='sub_item_category_id' class=\"textBox\" style='width:220px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['sub_item_category_id'] == $cate){
					   $country_select .= "<option value='".$row['sub_item_category_id']."' selected='selected'>".$row['sub_item_category_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['sub_item_category_id']."'>".$row['sub_item_category_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }

function SelectSupplier($sup = null){
$branch_id = getFromSession('branch_id'); 
		$sql="SELECT supplier_ID, company_name FROM ".INV_SUPPLIER_INFO_TBL." where branch_id=$branch_id ORDER BY supplier_ID";
	    $result = mysql_query($sql);
		$country_select = "<select name='supplier_ID' size='1' id='supplier_ID' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['supplier_ID'] == $sup){
					   $country_select .= "<option value='".$row['supplier_ID']."' selected='selected'>".$row['company_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['supplier_ID']."'>".$row['company_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }

function SelectBranch($branch = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT branch_id, branch_name FROM branch where branch_id=$branch_id";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:215px' class=\"textBox\">";
		/*$branch_select .= "<option value=''>Select</option>"; */
			while($row = mysql_fetch_array($result)) {
			if($row['branch_id'] == $branch){
			$branch_select .= "<option value='".$row['branch_id']."' selected = 'selected'>".$row['branch_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['branch_id']."'>".$row['branch_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} 


} // End class
?>
