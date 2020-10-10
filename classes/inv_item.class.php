<?php
class inv_item
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxcheckExistingItem'  		: $this->CheckExistingItem(getRequest('Item_ID'),getRequest('challan_no')); 										break;
		 case 'ajaxItemLoad4Cond'  			: echo $this->ItemDpdw4Condense(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad4Powd'  			: echo $this->ItemDpdw4Powder(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad4Tea'  			: echo $this->ItemDpdw4Tea(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad4Bev'  			: echo $this->ItemDpdw4Bev(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad4Snak'  			: echo $this->ItemDpdw4Snak(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad4Candy'  		: echo $this->ItemDpdw4Candy(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id')); break;
		 case 'ajaxItemLoad'  				: echo $this->SelectItemDpdw(getRequest('ele_id'),getRequest('uni_id'), getRequest('uni_val'), getRequest('ele_lbl_id')); break;
		 case 'ajaxSearchCategory' 			: echo $this->ItemCategoryInfoFetch();  						               					break;
		 case 'ajaxSearchItemInfo' 			: echo $this->ItemTypeInfoFetch();  						               						break;
		 case 'ajaxInsertCategory'          : echo $this->InsertItemCategory();														 		break;
		 case 'ajaxInsertItemInfo'          : echo $this->InsertItemInfo();														 			break;
		 case 'ajaxInsertItemReceive'       : echo $this->InsertItemReceive();														 		break;
		 case 'ajaxDeleteCategory'          : echo $this->DeleteCategory();												 					break;
		 case 'ajaxDeleteItemInfo'          : echo $this->DeleteItemInfo();												 					break;
		 case 'ajaxEditCategory'            : echo $this->EditCategory();												 					break;
		 case 'ajaxEditItemInfo'            : echo $this->EditItemInfo();												 					break;
		 case 'ajaxEditItemReceive'         : echo $this->EditItemReceive();												 				break;
		 case 'ajaxDeleteItemReceive'       : echo $this->DeleteItemReceive();												 				break;
		 case 'ItemInfoSkin'            	: echo $this->ItemInfoSkin();												 					break;
		 case 'ItemReceiveSkin'            	: echo $this->ItemReceiveSkin();												 				break;
         case 'list'                  		: $this->getList();                       											 			break;
         default                      		: $cmd == 'list'; $this->getList();	       											 			break;
      }
 }
function getList(){
 	 $Category = $this->ItemCategoryInfoFetch();
	 require_once(INV_ITEM_CATEGORY); 
  }
  
function CheckExistingItem($Item_ID,$challan_no){
		  $sql = "SELECT Item_ID from ".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_RECEIVE_MASTER_TBL." m on m.mst_receiv_id = rd.mst_receiv_id
					where m.challan_no ='$challan_no' and Item_ID = '$Item_ID';";
		  $Item_ID = mysql_num_rows(mysql_query($sql));
		  echo $Item_ID.'######';	
	}
   // ===== End chkEmailExistence ========   




function InsertItemCategory(){
 $Item_Category_Name = getRequest('Item_Category_Name');
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d');
    	 $sql = "INSERT INTO ".INV_CATEGORY_TBL."(Item_Category_Name,  createdby, createddate) 
			values('$Item_Category_Name',  '$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
			echo $Category = $this->ItemCategoryInfoFetch();
			}
  }

function EditCategory(){
 $Item_Category_ID =getRequest('Item_Category_ID');
 $Item_Category_Name = getRequest('Item_Category_Name');
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d H:i:s');
 
 $sql = "UPDATE ".INV_CATEGORY_TBL." set Item_Category_ID='$Item_Category_ID', Item_Category_Name='$Item_Category_Name' createdby='$createdby', createddate='$createddate' where Item_Category_ID = '$Item_Category_ID' ";
	$res = mysql_query($sql);
	if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
	}
}  

function DeleteCategory(){
	$Item_Category_ID = getRequest('Item_Category_ID');
  	if($Item_Category_ID)  {
		$sql = "DELETE from ".INV_CATEGORY_TBL." where Item_Category_ID = '$Item_Category_ID'";
		$res = mysql_query($sql);	
		if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
		}
  	}	
}  


function ItemCategoryInfoFetch(){
$searchq 	= getRequest('searchq');
	$sql=" SELECT Item_Category_ID, Item_Category_Name,  createdby, createddate FROM ".INV_CATEGORY_TBL." ";
	$WhereSrch = " Item_Category_Name LIKE '%".$searchq."%' "; 
	$ordr = " ORDER BY Item_Category_ID  LIMIT 0,30";
	
	if($searchq){
			  $sql .= " where ".$WhereSrch.$ordr." ";
		}else{
				 $sql .= $ordr;
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $category = '<table width=600 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Category Name</th>
				 <th nowrap=nowrap align=left>Created By</th>
				 <th nowrap=nowrap align=left>Created Date</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$category .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" onClick="javascript:
			ajaxCall4EditCatagory(\''.$row['Item_Category_ID'].'\',\''.
					 				$row['Item_Category_Name'].'\');">
			<td><a href="javascript:ajaxCall4DeleteCategory(\''.$row['Item_Category_ID'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['Item_Category_Name'].'&nbsp;</td>
			<td>'.$row['createdby'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
			</tr>';
		}
		$category .= '</table>';
		return $category;
}//end fnc


function ItemReceiveSkin(){
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/

	 $query = "SELECT COUNT(*) as num FROM ".INV_RECEIVE_DETAIL_TBL." ";
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
			$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=inv_item&cmd=ItemReceiveSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	
	} 

$ItemReceive = $this->ItemReceiveFetch($start, $limit);
$ItemListCondense=$this->ItemDpdw4Condense(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
$ItemListPowder=$this->ItemDpdw4Powder(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id'));
$ItemListTea=$this->ItemDpdw4Tea(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id'));
$ItemListBev=$this->ItemDpdw4Bev(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
$ItemListSnak=$this->ItemDpdw4Snak(getRequest('ele_id'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id'));
$ItemListCandy=$this->ItemDpdw4Candy(getRequest('ele_id'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
$SupplierList=$this->SelectSupplier();
require_once(INV_ITEM_RECEIVE);
}//eof


function InsertItemReceive(){
 $Item_ID = getRequest('Item_ID');
 $unit_price = getRequest('unit_price');
 $issu_unit_cost  = getRequest('issu_unit_cost');
 $totalpcs = getRequest('totalpcs');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $unit_value = getRequest('unit_value');
  //if(getRequest('grm')!=''){
   $grm = getRequest('grm')*$totalpcs;
  //}
 	$total_in_grm = $grm;
 	$total_in_kg = $grm/1000;

	$quantity = ($totalpcs/$unit_value);
	$quantity_unit  = floor($quantity);
	$quantityPcs = strrchr($quantity, ".");
	$quantity_pcs =  ($quantityPcs*$unit_value);


 $totalPrice=$totalpcs*$unit_price;
 $challan_no = getRequest('challan_no');
 $remark = getRequest('remark');
 $createdby = getFromSession('userid');
 $company = '1';
 $createddate = date('Y-m-d');
 $last_rec_date = date('Y-m-d');
 
 	$ReceiveId ="select * from ".INV_RECEIVE_MASTER_TBL." where challan_no = '$challan_no' ";
 	$ResRecId =mysql_query($ReceiveId);
	$recRow = mysql_fetch_array($ResRecId);
	$mst_receiv_id =$recRow['mst_receiv_id'];
	$recRows = mysql_num_rows($ResRecId);	
 	if($recRows==0){
     $sqlMast = "INSERT INTO ".INV_RECEIVE_MASTER_TBL."(receive_date,challan_no,remark, createdby, company_id, createddate) 
			values('$receive_date','$challan_no','$remark', '$createdby', $company, '$createddate')";
	$resMast = mysql_query($sqlMast);
	
				if($resMast){
						$MaxId ="select max(mst_receiv_id) as  maxmst_receiv_id from ".INV_RECEIVE_MASTER_TBL." ";
						$Maxres = mysql_query($MaxId);
						$Maxrow = mysql_fetch_array($Maxres);
						$maxmst_receiv_id = $Maxrow['maxmst_receiv_id'];
	
						$sql = "INSERT INTO ".INV_RECEIVE_DETAIL_TBL.	
			"(mst_receiv_id,Item_ID,totalpcs,unit_price,issu_unit_cost,quantity_unit,quantity_pcs,total_price,total_in_grm,total_in_kg,last_rec_date) 
values($maxmst_receiv_id,'$Item_ID',$totalpcs,$unit_price, $issu_unit_cost, $quantity_unit,$quantity_pcs,$totalPrice,$total_in_grm,
$total_in_kg,'$last_rec_date')";
						$res = mysql_query($sql);
					
						if($res){
							echo $ItemReceive = $this->ItemReceiveFetch();
							}// end if($res)
				
				}// end if($resMast)

		}else{
					 $sql = "INSERT INTO ".INV_RECEIVE_DETAIL_TBL.			
			"(mst_receiv_id,Item_ID,totalpcs,unit_price,issu_unit_cost,quantity_unit,quantity_pcs,total_price,total_in_grm,total_in_kg,last_rec_date) 
				values($mst_receiv_id,'$Item_ID',$totalpcs, $unit_price, $issu_unit_cost, $quantity_unit, $quantity_pcs,$totalPrice,
				 $total_in_grm,$total_in_kg,'$last_rec_date')";
				$res = mysql_query($sql);
				
						if($res){
						echo $ItemReceive = $this->ItemReceiveFetch();
						}// end if

		}// end else

  }//eof

function EditItemReceive(){
 $receive_dtlid = getRequest('receive_dtlid');
 $Item_ID = getRequest('Item_ID');
 $unit_price = getRequest('unit_price');
 $issu_unit_cost  = getRequest('issu_unit_cost');
 $totalpcs = getRequest('totalpcs');
 $unit_value = getRequest('unit_value');
   $grm = getRequest('grm')*$totalpcs;
 	$total_in_grm = $grm;
 	$total_in_kg = $grm/1000;

	$quantity = ($totalpcs/$unit_value);
	$quantity_unit  = floor($quantity);
	$quantityPcs = strrchr($quantity, ".");
	$quantity_pcs =  ($quantityPcs*$unit_value);

 $totalPrice=$totalpcs*$unit_price;
 
 $mst_receiv_id = getRequest('mst_receiv_id');
 $challan_no = getRequest('challan_no');
 $remark = getRequest('remark');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 $last_update_date = date('Y-m-d');

	$sql1 = "UPDATE ".INV_RECEIVE_MASTER_TBL." set challan_no='$challan_no', receive_date='$receive_date', remark='$remark' where mst_receiv_id=$mst_receiv_id";
	$res1 = mysql_query($sql1);
	
	if($res1){
	$sql2 = "UPDATE ".INV_RECEIVE_DETAIL_TBL." set  Item_ID='$Item_ID', totalpcs=$totalpcs,  unit_price=$unit_price, 
			issu_unit_cost=$issu_unit_cost, quantity_unit=$quantity_unit, quantity_pcs=$quantity_pcs, total_price=$totalPrice, total_in_grm=$total_in_grm,
			 total_in_kg=$total_in_kg, last_update_date=$last_update_date  where receive_dtlid=$receive_dtlid and mst_receiv_id=$mst_receiv_id";
	$res2 = mysql_query($sql2);

		if($res2){
		echo $ItemReceive = $this->ItemReceiveFetch();
		}

	}
 
}  // eof


function DeleteItemReceive(){
	$receive_dtlid = getRequest('receive_dtlid');
  	if($receive_dtlid)  {
		 $sql = "DELETE from ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = '$receive_dtlid'";
		$res = mysql_query($sql);	
		if($res){
		echo $ItemReceive = $this->ItemReceiveFetch();
		}
  	}	
}  

function ItemReceiveFetch($start = null, $limit = null){
$page 	= getRequest('page');
if($page){
   $sql="SELECT
	rm.mst_receiv_id,
	rd.receive_dtlid,
	rd.Item_ID,
	i.Item_Name,
	m.measurementunit,
	i.unit_value,
	i.grm,
	rd.totalpcs,
	quantity_unit,
	quantity_pcs,
	total_in_grm,
	total_in_kg,
	rd.unit_price,
	rd.issu_unit_cost,
	rd.total_price,
	challan_no,
	receive_date,
	remark

FROM
	".INV_RECEIVE_MASTER_TBL."  rm inner join ".INV_RECEIVE_DETAIL_TBL." rd on rm.mst_receiv_id = rd.mst_receiv_id
	inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
	inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id ORDER BY receive_date DESC LIMIT $start, $limit";
	}else{
	   $sql="SELECT
	rm.mst_receiv_id,
	rd.receive_dtlid,
	rd.Item_ID,
	i.Item_Name,
	m.measurementunit,
	i.unit_value,
	i.grm,
	rd.totalpcs,
	quantity_unit,
	quantity_pcs,
	total_in_grm,
	total_in_kg,
	rd.unit_price,
	rd.issu_unit_cost,
	rd.total_price,
	challan_no,
	receive_date,
	remark

FROM
	".INV_RECEIVE_MASTER_TBL."  rm inner join ".INV_RECEIVE_DETAIL_TBL." rd on rm.mst_receiv_id = rd.mst_receiv_id
	inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = rd.Item_ID
	inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id=i.mesure_id ORDER BY receive_date DESC LIMIT 0,29";
}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table cellspacing=0 class="tableGrid" style="width:1100px;" >
	            <tr>
				<th width="40"></th>
				<th width="40"></th>
				 <th nowrap=nowrap align=left width="100">Receive Date</th>
	             <th nowrap=nowrap align=left width="80">SKU Code</th>
	             <th nowrap=nowrap align=left>SKU Name</th>
				 <th nowrap=nowrap align=left width="80">Pack</th>
				 <th nowrap=nowrap align=left width="50">Pack Size</th>
				 <th nowrap=nowrap align=left width="50">Total Pcs </th>
				 <th nowrap=nowrap align=left>In Pack</th>
				 <th nowrap=nowrap align=left>Total grm</th>
				 <th nowrap=nowrap align=left>Total Kg</th>
				 <th nowrap=nowrap align=left>Received</th>
				 <th nowrap=nowrap align=left>Sales</th>
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Challan no</th>
				 <th nowrap=nowrap align=left>Remark</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 $subunt =  substr($row['measurementunit'], 0, 1); 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			 $createdby = getFromSession('userid');

			if($createdby=='1401'){
			
			 $delEdit='<td><a href="javascript:ajaxCall4DeleteItemReceive(\''.$row['receive_dtlid'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td><a onClick="javascript:ajaxCall4EditItemReceive(\''.$row['receive_dtlid'].'\',\''.
					 									  $row['mst_receiv_id'].'\',\''.
					 									  $row['Item_ID'].'\',\''.
					 									  $row['Item_Name'].'\',\''.
					 									  $row['totalpcs'].'\',\''.
					 									  $row['unit_price'].'\',\''.
					 									  $row['issu_unit_cost'].'\',\''.
					 									  $row['challan_no'].'\',\''.
					 									  dateInputFormatDMY($row['receive_date']).'\',\''.
					 									  $row['remark'].'\',\''.
														  $row['grm'].'\',\''.
						                                  $row['unit_value'].'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			
			}else{
			 $delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}
			 
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">'.$delEdit.'
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['Item_ID'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['Item_Name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['measurementunit'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['unit_value'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['totalpcs'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['quantity_unit'].$subunt.' - '.$row['quantity_pcs'].'P&nbsp;</td>
			<td nowrap=nowrap>'.$row['total_in_grm'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['total_in_kg'].'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.number_format($row['unit_price'],2).'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.number_format($row['issu_unit_cost'],2).'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.$row['total_price'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['challan_no'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['remark'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc



function ItemInfoSkin(){
$ItemInfo = $this->ItemInfoFetch();
$catList=$this->SelectItmCategory();
$UnitList=$this->SelectUnit();

require_once(INV_ITEM_INFO);
}

function InsertItemInfo(){
 $Item_ID = getRequest('Item_ID');
 $Item_Category_ID = getRequest('Item_Category_ID');
 $Item_Name = getRequest('Item_Name');
 $unit_value = getRequest('unit_value');
 $mesure_id = getRequest('mesure_id');
 $grm = getRequest('grm');
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d H:i:s');
    	 $sql = "INSERT INTO ".INV_ITEMINFO_TBL."(Item_ID,Item_Category_ID,Item_Name,unit_value,mesure_id,grm, createdby, createddate) 
			values('$Item_ID',$Item_Category_ID,'$Item_Name','$unit_value',$mesure_id, $grm, '$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
			echo $ItemType = $this->ItemInfoFetch();
			}
  }

function EditItemInfo(){
 $Item_ID = getRequest('Item_ID');
 $Item_Category_ID = getRequest('Item_Category_ID');
 $Item_Name = getRequest('Item_Name');
 $unit_value = getRequest('unit_value');
 $mesure_id = getRequest('mesure_id');
 $grm = getRequest('grm');

	 $sql = "UPDATE ".INV_ITEMINFO_TBL." set  Item_Category_ID='$Item_Category_ID', Item_Name='$Item_Name', unit_value='$unit_value', mesure_id='$mesure_id',
	  grm='$grm' where Item_ID='$Item_ID' ";
	$res = mysql_query($sql);
	if($res){
		echo $ItemType = $this->ItemInfoFetch();
	}
}  

function DeleteItemInfo(){
	$Item_ID = getRequest('Item_ID');
  	if($Item_ID)  {
		 $sql = "DELETE from ".INV_ITEMINFO_TBL." where Item_ID = '$Item_ID'";
		$res = mysql_query($sql);	
		if($res){
		echo $ItemType = $this->ItemInfoFetch();
		}
  	}	
}  

function ItemInfoFetch(){
$searchq 	= getRequest('searchq');
	 $sql=" SELECT i.Item_Category_ID, i.Item_ID, grm, ic.Item_Category_Name,Item_Name,grm, im.measurementunit,unit_value,  i.createddate FROM ".INV_ITEMINFO_TBL." i 
				inner join ".INV_CATEGORY_TBL."  ic on ic.Item_Category_ID = i.Item_Category_ID inner join ".INV_ITEM_MEASURE_TBL." im on im.mesure_id=i.mesure_id";
	$WhereSrch = " Item_Name LIKE '%".$searchq."%' "; 
	$ordr = " ORDER BY i.Item_ID";
	
	if($searchq){
			  $sql .= " where ".$WhereSrch.$ordr." ";
		}else{
				 $sql .= $ordr;
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width="1000" cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
				<th></th>
	             <th nowrap=nowrap align=left>SKU Code</th>
	             <th nowrap=nowrap align=left>SKU Name</th>
				 <th nowrap=nowrap align=left>Unit</th>
				 <th nowrap=nowrap align=left>Grm</th>
				 <th nowrap=nowrap align=left>SKU Category</th>
				 <th nowrap=nowrap align=left>Unit Value</th>
				 <th nowrap=nowrap align=left>Created Date</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		$Item_Category_ID=$row['Item_Category_ID'];
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			 
			 $createdby = getFromSession('userid');
 			if($createdby=='1401'){
			$delEdit='<td width="30"><a href="javascript:ajaxCall4DeleteItem(\''.$row['Item_ID'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td width="30"><a onClick="javascript:ajaxCall4EditItem(\''.$row['Item_ID'].'\',\''.
					 									  $row['Item_Category_ID'].'\',\''.
					 									  $row['Item_Name'].'\',\''.
														  $row['grm'].'\',\''.
						                                  $row['unit_value'].'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{
			
			$delEdit='<td width="30">&nbsp;</td>
			<td width="30">&nbsp;</td>';
			}
			 
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >'.$delEdit.'
			<td nowrap=nowrap align="center">'.$row['Item_ID'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['Item_Name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['measurementunit'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['grm'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['Item_Category_Name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['unit_value'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

function SelectItmCategory($cate = null){ 
		$sql="SELECT Item_Category_ID, Item_Category_Name FROM ".INV_CATEGORY_TBL." ORDER BY Item_Category_ID";
	    $result = mysql_query($sql);
		$country_select = "<select name='Item_Category_ID' size='1' id='Item_Category_ID' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['Item_Category_ID'] == $cate){
					   $country_select .= "<option value='".$row['Item_Category_ID']."' selected='selected'>".$row['Item_Category_Name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['Item_Category_ID']."'>".$row['Item_Category_Name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }

function ItemDpdw4Condense($ele_id,$unit_value, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, Item_Name, unit_value from ".INV_ITEMINFO_TBL." where Item_Category_ID = 1 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }


function ItemDpdw4Powder($ele_id,$unit_value, $grm, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, grm, Item_Name, unit_value from ".INV_ITEMINFO_TBL." where Item_Category_ID = 2 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Pwder('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }

function ItemDpdw4Tea($ele_id,$unit_value, $grm, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, Item_Name, unit_value, grm from ".INV_ITEMINFO_TBL." where Item_Category_ID = 3 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Tea('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }

function ItemDpdw4Bev($ele_id,$unit_value, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, Item_Name, unit_value from ".INV_ITEMINFO_TBL." where Item_Category_ID = 4 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }


function ItemDpdw4Snak($ele_id,$unit_value, $grm, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, Item_Name, unit_value, grm from ".INV_ITEMINFO_TBL." where Item_Category_ID = 5 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Snak('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }
function ItemDpdw4Candy($ele_id,$unit_value, $ele_lbl_id){ 

		 $sql="SELECT Item_ID, Item_Name, unit_value from ".INV_ITEMINFO_TBL." where Item_Category_ID = 6 ORDER BY Item_ID  ";
	
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."','".$row['unit_value']."','".$unit_value."','".$row['Item_Name']."','".$ele_lbl_id."');\" style=\"cursor:pointer\">
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_ID']."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['Item_Name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }



function SelectUnit($msUnit = null){ 
		$sql="SELECT mesure_id, measurementunit FROM ".INV_ITEM_MEASURE_TBL." where mesure_id=2 ";
	    $result = mysql_query($sql);
	    $Unit_select = "<select name='mesure_id' size='1' id='mesure_id' class=\"textBox\" style='width:100px'>";
		$row = mysql_fetch_array($result);
			//while($row = mysql_fetch_array($result)) {
		
			$Unit_select .= "<option value='".$row['mesure_id']."'>".$row['measurementunit']."</option>";	
			//}
		
		$Unit_select .= "</select>";
		return $Unit_select;
   }


function SelectSupplier($supplier = null){ 
		$sql="SELECT Supplier_ID, Supplier_Name FROM ".INV_SUPPLIER_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='Supplier_ID' size='1' id='Supplier_ID' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['Supplier_ID'] == $supplier){
			$Supplier_select .= "<option value='".$row['Supplier_ID']."' selected = 'selected'>".$row['Supplier_Name']."</option>";
			}
			else{
			$Supplier_select .= "<option value='".$row['Supplier_ID']."'>".$row['Supplier_Name']."</option>";	
			}
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }


} // End class
?>
