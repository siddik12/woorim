<?php
class godown_entry
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxcheckExistingItem'  		: $this->CheckExistingItem(getRequest('main_item_category_id'),getRequest('challan_no')); 			break;
		 case 'ajaxSearchItemInfo' 			: echo $this->ItemInfoFetch();  						        break;
		 case 'ajaxInsertItemInfo'          : echo $this->InsertItemInfo();									break;
		 case 'ajaxDeleteItemInfo'          : echo $this->DeleteItemInfo();									break;
		 case 'ajaxEditItemInfo'            : echo $this->EditItemInfo();									break;
         case 'list'                  		: $this->getList();                       						break;
         default                      		: $cmd == 'list'; $this->getList();	       						break;
      }
 }
function getList(){
$main_item_category_id 	= getRequest('main_item_category_id');
 	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/

	 $query = "SELECT COUNT(*) as num FROM ".WARE_HOUSE_TBL." where main_item_category_id=$main_item_category_id";
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
			$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=1\">1</a>";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=1\">1</a>";
				$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=godown_entry&main_item_category_id=$main_item_category_id&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	
	} 

$main_item_category_id=getRequest('main_item_category_id');
 $ItemInfo = $this->ItemInfoFetch($start, $limit);
$catList=$this->SelectItmCategory($main_item_category_id);
$SuplierList=$this->SelectSupplier();
$branch_dropdown = $this->SelectBranch();
require_once(CURRENT_APP_SKIN_FILE);
  }

function CheckExistingItem($main_item_category_id,$challan_no){

		  $sql = "SELECT main_item_category_id from ".WARE_HOUSE_TBL." where main_item_category_id = '$main_item_category_id' and challan_no = '$challan_no'";
		  $main_item_category_id = mysql_num_rows(mysql_query($sql));
		  echo $main_item_category_id.'######';	
	}
   // ===== End chkEmailExistence ========   


function InsertItemInfo(){
 $sub_item_category_id = getRequest('sub_item_category_id');
 $item_name = getRequest('item_name');
 $cost_price = getRequest('cost_price');
 $cost_exp = getRequest('cost_exp');
 $totalCost=($cost_price+$cost_exp);
 $sales_price = getRequest('sales_price');
 $whole_sales_price = getRequest('whole_sales_price');
 $challan_no = getRequest('challan_no');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $main_item_category_id = getRequest('main_item_category_id');
 $quantity = getRequest('quantity');
 $item_size = getRequest('item_size');
 $supplier_id = getRequest('supplier_id');
 //$description = getRequest('description');
 $branch_id = getRequest('branch_id');//exit();
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d H:i:s');
 //$totalcost=($cost_price*$quantity);

    	 $sql = "INSERT INTO ".WARE_HOUSE_TBL."(sub_item_category_id,cost_price,cost_exp,sales_price,whole_sales_price,main_item_category_id,quantity,receive_date,challan_no,supplier_id,item_size,branch_id,createdby, createddate)
		 values($sub_item_category_id,'$totalCost','$cost_exp','$sales_price','$whole_sales_price','$main_item_category_id','$quantity','$receive_date','$challan_no','$supplier_id','$item_size','$branch_id','$createdby', '$createddate')";
	 
	$res = mysql_query($sql);
		if($res){
					$MaxId ="select max(ware_house_id) as  MXware_house_id from ".WARE_HOUSE_TBL." ";
						$Maxres = mysql_query($MaxId);
						$Maxrow = mysql_fetch_array($Maxres);
						$MXware_house_id = $Maxrow['MXware_house_id'];
						
			     $sqlMast = "INSERT INTO ".ACC_DETAILS_TBL."(recdate,supplier_id,ware_house_id,challan_no,cost,quantity,createdby) 
				values('$receive_date','$supplier_id','$MXware_house_id','$challan_no','$cost_price','$quantity', '$createdby')";
				 mysql_query($sqlMast);
				 
				 $sqldis = "INSERT INTO ".INV_ITEMINFO_TBL."(sub_item_category_id,main_item_category_id,cost_price,sales_price,whole_sales_price,quantity,receive_date,branch_id,ware_house_id,item_size,createdby, createddate)
values($sub_item_category_id,'$main_item_category_id','$cost_price','$sales_price','$whole_sales_price','$quantity','$receive_date','$branch_id','$MXware_house_id','$item_size','$createdby', '$createddate')";//exit();
	mysql_query($sqldis);

			echo $ItemType = $this->ItemInfoFetch();
			}
  }

function EditItemInfo(){
 $ware_house_id = getRequest('ware_house_id');
 $sub_item_category_id = getRequest('sub_item_category_id');
 $cost_price = getRequest('cost_price');
 $cost_exp = getRequest('cost_exp');
 $totalCost=($cost_price+$cost_exp);
 $sales_price = getRequest('sales_price');
 $whole_sales_price = getRequest('whole_sales_price');
 $challan_no = getRequest('challan_no');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $main_item_category_id = getRequest('main_item_category_id');
 $quantity = getRequest('quantity');
 $item_size = getRequest('item_size');
 $supplier_id = getRequest('supplier_id');
 $branch_id = getRequest('branch_id');

	$sql = "UPDATE ".WARE_HOUSE_TBL." set  sub_item_category_id='$sub_item_category_id', cost_price='$totalCost',cost_exp='$cost_exp',sales_price='$sales_price',whole_sales_price='$whole_sales_price',challan_no='$challan_no', receive_date='$receive_date', main_item_category_id='$main_item_category_id',quantity='$quantity',supplier_id='$supplier_id',item_size='$item_size', branch_id='$branch_id' where ware_house_id='$ware_house_id' ";
	mysql_query($sql);
		
	$sqlMast = "UPDATE ".ACC_DETAILS_TBL." set
	recdate='$receive_date',supplier_id='$supplier_id',challan_no='$challan_no',cost='$cost_price',quantity='$quantity'
	where ware_house_id='$ware_house_id' ";
	$res2=mysql_query($sqlMast);//exit();
	
	$sqlDist = "UPDATE
				inv_iteminfo
			SET
				sub_item_category_id = '$sub_item_category_id',
				cost_price = '$cost_price',
				sales_price = '$sales_price',
				whole_sales_price = '$whole_sales_price',
				item_size = '$item_size',
				main_item_category_id = '$main_item_category_id',
				quantity = '$quantity',
				receive_date = '$receive_date',
				branch_id = '$branch_id'
			WHERE ware_house_id=$ware_house_id"; //exit();
	mysql_query($sqlDist);

	if($res2){
		echo $ItemType = $this->ItemInfoFetch();
	}
}  

function DeleteItemInfo(){
	$ware_house_id = getRequest('ware_house_id');
  	if($ware_house_id)  {
		 $sql = "DELETE from ".WARE_HOUSE_TBL." where ware_house_id = '$ware_house_id'";
		$res = mysql_query($sql);	
		if($res){
		$sqlMast = "DELETE from ".ACC_DETAILS_TBL." where ware_house_id = '$ware_house_id'";
		mysql_query($sqlMast);
		
		$sqlDist = "DELETE FROM inv_iteminfo where ware_house_id = '$ware_house_id'";
		mysql_query($sqlDist);
		
		echo $ItemType = $this->ItemInfoFetch();
		}
  	}	
}  

function ItemInfoFetch($start = null, $limit = null){
$searchq 	= getRequest('searchq');
$page 	= getRequest('page');
$main_item_category_id 	= getRequest('main_item_category_id');
if($searchq){
	  $sql="SELECT
	ware_house_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.supplier_id,
	company_name,
	quantity,
	challan_no,
	receive_date,
	i.createdby,
	i.createddate,
	i.branch_id,
	branch_name
FROM
	".WARE_HOUSE_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=i.supplier_id
	inner join branch b on b.branch_id=i.branch_id
	where challan_no LIKE '%".$searchq."%' or item_size LIKE '%".$searchq."%' or main_item_category_name LIKE '%".$searchq."%' or company_name LIKE '%".$searchq."%' or sub_item_category_name LIKE '%".$searchq."%' order by receive_date desc LIMIT 0, 5"; 
	}if($page){
		$sql="SELECT
	ware_house_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.supplier_id,
	company_name,
	quantity,
	challan_no,
	receive_date,
	i.createdby,
	i.createddate,
	i.branch_id,
	branch_name
FROM
	".WARE_HOUSE_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=i.supplier_id
	inner join branch b on b.branch_id=i.branch_id
	where m.main_item_category_id=$main_item_category_id
	order by receive_date desc LIMIT $start, $limit"; 
	}
if(!$page && !$searchq){
	 $sql="SELECT
	ware_house_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.supplier_id,
	company_name,
	quantity,
	challan_no,
	receive_date,
	i.createdby,
	i.createddate,
	i.branch_id,
	branch_name
FROM
	".WARE_HOUSE_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=i.supplier_id
	inner join branch b on b.branch_id=i.branch_id
	 where m.main_item_category_id=$main_item_category_id
	order by receive_date desc LIMIT 0, 29"; 
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width="1200" cellspacing=0 class="tableGrid" >
	            <tr>
				<th>SL</th>
	             <th nowrap=nowrap align=left>Brand Name</th>
	             <th nowrap=nowrap align=left>Item Name</th>
	             <th nowrap=nowrap align=left>Quantity</th>
<!--				 <th nowrap=nowrap align=left>Category</th>
-->				 <th nowrap=nowrap align=left>Cost</th>
				 <th nowrap=nowrap align=left>Sales(retail)</th>
				 <th nowrap=nowrap align=left>Sales(whole)</th>
				 <th nowrap=nowrap align=left>IMEI</th>
				 <th nowrap=nowrap align=left>Supplier</th>
				 <th nowrap=nowrap align=left>Challan</th>
				 <th nowrap=nowrap align=left>Received</th>
<!--				 <th nowrap=nowrap align=left>Description</th>
-->				 <th nowrap=nowrap align=left>Branch</th>
<!--				 <th nowrap=nowrap align=left>Date</th>
-->			<th></th>
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
			<td nowrap=nowrap>'.$row['main_item_category_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['quantity'].'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['sub_item_category_name'].'&nbsp;</td>
-->			<td nowrap=nowrap>'.$row['cost_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['sales_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['whole_sales_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['item_size'].'&nbsp;</td>
			<td>'.$row['company_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['challan_no'].'&nbsp;</td>
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['description'].'&nbsp;</td>
-->			<td nowrap=nowrap>'.$row['branch_name'].'&nbsp;</td>
<!--			<td>'._date($row['createddate']).'&nbsp;</td>
-->			<td width="30"><a onClick="javascript:ajaxCall4EditItem(\''.$row['ware_house_id'].'\',\''.
					 									  $row['sub_item_category_id'].'\',\''.
					 									  $row['main_item_category_id'].'\',\''.
					 									  $row['quantity'].'\',\''.
					 									  $row['cost_price'].'\',\''.
					 									  $row['cost_exp'].'\',\''.
					 									  $row['sales_price'].'\',\''.
					 									  $row['whole_sales_price'].'\',\''.
					 									  $row['supplier_id'].'\',\''.
					 									  $row['challan_no'].'\',\''.
					 									  dateInputFormatDMY($row['receive_date']).'\',\''.
														  $row['item_size'].'\',\''.
						                                  $row['branch_id'].'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>
			<td width="30" align="right"><a href="javascript:ajaxCall4DeleteItem(\''.$row['ware_house_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
			
			$i++;
		}
		$type .= '</table>';
		return $type;
}//end fnc

function SelectItmCategory($main_item_category_id){ 
		$sql="SELECT sub_item_category_id, sub_item_category_name FROM ".INV_CATEGORY_SUB_TBL." where main_item_category_id=$main_item_category_id ORDER BY sub_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='sub_item_category_id' size='1' id='sub_item_category_id' class=\"textBox\" style='width:120px;'>";
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
		$sql="SELECT supplier_id, company_name FROM ".INV_SUPPLIER_INFO_TBL." ORDER BY supplier_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='supplier_id' size='1' id='supplier_id' class=\"textBox\" style='width:120px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['supplier_id'] == $sup){
					   $country_select .= "<option value='".$row['supplier_id']."' selected='selected'>".$row['company_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['supplier_id']."'>".$row['company_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }


function SelectBranch($branch = null){ 
		$sql="SELECT branch_id, branch_name FROM branch where branch_id!=5 ";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:150px' class=\"textBox\" class=\"validate[required]\">";
		$branch_select .= "<option value=''>Select</option>"; 
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
