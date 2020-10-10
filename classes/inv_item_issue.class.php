<?php
class inv_item_issue
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 case 'ajaxSalesManLoad'  	: echo $this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id')); 					break;
	 case 'ajaxItemLoad4Cond'  	: echo $this->ItemDpdw4Condense(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'),getRequest('ele_lbl_id')); break;
	 case 'ajaxItemLoad4Powd'  	: echo $this->ItemDpdw4Powder(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'),getRequest('grm_vel'),getRequest('ele_lbl_id')); break;
	 case 'ajaxItemLoad4Tea'  	: echo $this->ItemDpdw4Tea(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id')); break;
	 case 'ajaxItemLoad4Bev'  	: echo $this->ItemDpdw4Bev(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('ele_lbl_id')); break;
	 case 'ajaxItemLoad4Snak'  	: echo $this->ItemDpdw4Snak(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id')); break;
	 case 'ajaxItemLoad4Candy'  : echo $this->ItemDpdw4Candy(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'),getRequest('ele_lbl_id')); break;
	 case 'ajaxInsertItemIssue' : echo $this->InsertItemIssue();														 				break;
	 case 'ItemIssueSkin'            	: echo $this->ItemIssueSkin();												 					break;
	 case 'ajaxEditItemIssue'         	: echo $this->EditItemIssue();												 					break;
	 case 'ajaxDeleteItemIssue'       	: echo $this->DeleteItemIssue();												 				break;
	 case 'list'                  		: $this->getList();                       											 			break;
	 default                      		: $cmd == 'list'; $this->getList();	       											 			break;
      }
 }
function getList(){
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/

	$query = "SELECT COUNT(*) as num FROM ".INV_ITEM_ISSUE_SUB_TBL." ";
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
			$pagination.= "<a href=\"?app=inv_item_issue&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=inv_item_issue&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=inv_item_issue&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_issue&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_issue&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=inv_item_issue&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_issue&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_issue&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=inv_item_issue&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=inv_item_issue&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=inv_item_issue&page=1\">1</a>";
				$pagination.= "<a href=\"?app=inv_item_issue&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=inv_item_issue&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=inv_item_issue&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*29;}else { $res3=0; $res3=29;}
	
	} 

	$ItemIssue = $this->ItemIssueFetch($start, $limit);
	$ItemListCondense=$this->ItemDpdw4Condense(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
	$ItemListPowder = $this->ItemDpdw4Powder(getRequest('ele_id'),getRequest('sls'),getRequest('uni_val'),getRequest('grm_vel'),getRequest('ele_lbl_id')); 
	$ItemListTea=$this->ItemDpdw4Tea(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id'));
	$ItemListBev=$this->ItemDpdw4Bev(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
	$ItemListSnak=$this->ItemDpdw4Snak(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('grm_vel'), getRequest('ele_lbl_id'));
	$ItemListCandy=$this->ItemDpdw4Candy(getRequest('ele_id'),getRequest('sls'),getRequest('uni_vel'), getRequest('ele_lbl_id'));
	$SalseManList=$this->SalesManDpDw(getRequest('ele_id'), getRequest('ele_lbl_id'));
	require_once(CURRENT_APP_SKIN_FILE);
  }

function ExistingItemIssue($Item_ID,$slman_id,$issue_date){
		  $sql = "SELECT Item_ID FROM ".INV_ITEM_ISSUE_SUB_TBL." ii inner join ".INV_ITEM_ISSUE_MAIN_TBL." im on im.issue_id = ii.issue_id
		  			where im.slman_id=$slman_id and  Item_ID = '$Item_ID' and im.issue_date = '$issue_date'";
		  $Item_ID = mysql_num_rows(mysql_query($sql));
		  echo $Item_ID.'######';	
	}
   // ===== End chkEmailExistence ========   

function InsertItemIssue(){
 $Item_ID = getRequest('Item_ID');
 $slman_id = getRequest('slman_id');
 $issu_unit_cost = getRequest('issu_unit_cost');
 $issue_qnt = getRequest('issue_qnt');
 $issue_date = formatDate4insert(getRequest('issue_date'));
 $free_qnt = getRequest('free_qnt');
 $total_comm = getRequest('total_comm');
 $damage_qnt = getRequest('damage_qnt');
 $damage_price = getRequest('damage_price');
 $totalQnt = $issue_qnt+$free_qnt;
 $unit_value = getRequest('unit_value');
   $grm = getRequest('grm')*$issue_qnt;
 	$total_in_grm = $grm;
 	$total_in_kg = $grm/1000;

	$quantity = ($totalQnt/$unit_value);
	$quantity_unit  = floor($quantity);
	$quantityPcs = strrchr($quantity, ".");
	$quantity_pcs =  ($quantityPcs*$unit_value);


	 $totalCost=$issue_qnt*$issu_unit_cost;
	 $createdby = getFromSession('userid');
	 $company = '1';
	 $createddate = date('Y-m-d');
 	 $TotalDmgCost=$damage_qnt*$damage_price;
 //  for check issue quantity grater than stock qnt-------------------------
 	 $selectItem = "SELECT  sum(totalpcs) as totalpcs  FROM inv_receive_detail  where Item_ID ='$Item_ID'  ";
 	 $resItem = mysql_query($selectItem);
	 while($rowItem = mysql_fetch_array($resItem)){
	 $tpcs = $rowItem['totalpcs'];
	 }
 	 
	  $selectItem2 = "SELECT sum(total_qnt) as total_qnt FROM inv_item_issue_sub where Item_ID ='$Item_ID' ";
 	 $resItem2 = mysql_query($selectItem2);
	 while($rowItem2 = mysql_fetch_array($resItem2)){
	 $tpcs2 = $rowItem2['total_qnt'];
	 }
	 $chk = $tpcs-$tpcs2; //exit();
	 
	 if($issue_qnt>$chk){
echo '<b>Sorry!! Qnt will must less then stock</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?app=inv_item_issue"><u>Go Back</u></a>';
}
//------------------- end check------------------------
	 else{

 	$IssueId ="select * from ".INV_ITEM_ISSUE_MAIN_TBL." where slman_id = $slman_id and  issue_date = '$issue_date' ";
 	$ResIssueId =mysql_query($IssueId);
	$issueRow = mysql_fetch_array($ResIssueId);
	$issue_id =$issueRow['issue_id'];
	$issueRows = mysql_num_rows($ResIssueId);	
 	if($issueRows==0){
    $sqlMast = "INSERT INTO ".INV_ITEM_ISSUE_MAIN_TBL."(slman_id,issue_date,createdby, company_id, createddate) 
			values('$slman_id','$issue_date','$createdby', $company, '$createddate')";
	$resMast = mysql_query($sqlMast);
	
				if($resMast){
						$MaxId ="select max(issue_id) as  maxissue_id from ".INV_ITEM_ISSUE_MAIN_TBL." ";
						$Maxres = mysql_query($MaxId);
						$Maxrow = mysql_fetch_array($Maxres);
						$maxissue_id = $Maxrow['maxissue_id'];
	
						$sql1 = "INSERT INTO ".INV_ITEM_ISSUE_SUB_TBL.
						
			"(issue_id,Item_ID,issue_qnt,free_qnt,total_qnt,issu_qnt_unit,issu_qnt_pcs,issu_unit_cost,issu_total_cost,issue_total_grm,issue_total_kg,total_comm,damage_qnt,damage_price,total_damage_price,slman_id) 
values($maxissue_id,'$Item_ID',$issue_qnt, $free_qnt, $totalQnt,$quantity_unit, $quantity_pcs,$issu_unit_cost,$totalCost,$total_in_grm,$total_in_kg,$total_comm,$damage_qnt,$damage_price,$TotalDmgCost,$slman_id)";
						$res1 = mysql_query($sql1);
							if($res1)
							{
							echo $ItemIssue = $this->ItemIssueFetch();
							}// end if($res)
				
				}// end if($resMast)

			}else{
						$sql2 = "INSERT INTO ".INV_ITEM_ISSUE_SUB_TBL.						"(issue_id,Item_ID,issue_qnt,free_qnt,total_qnt,issu_qnt_unit,issu_qnt_pcs,issu_unit_cost,issu_total_cost,issue_total_grm,issue_total_kg,total_comm,damage_qnt,damage_price,total_damage_price,slman_id) 
 values($issue_id,'$Item_ID',$issue_qnt, $free_qnt, $totalQnt,$quantity_unit, $quantity_pcs,$issu_unit_cost,$totalCost,$total_in_grm,$total_in_kg,$total_comm,$damage_qnt,$damage_price,$TotalDmgCost,$slman_id)";
				$res2 = mysql_query($sql2);
						if($res2)
						{
						echo $ItemIssue = $this->ItemIssueFetch();
						}// end if

			}// end else
		
		} // end qnt check else

  }//eof

function EditItemIssue(){
 $issue_id = getRequest('issue_id');
 $issue_subid = getRequest('issue_subid');
 $Item_ID = getRequest('Item_ID');
 $slman_id = getRequest('slman_id');
 $issu_unit_cost = getRequest('issu_unit_cost');
 $issue_qnt = getRequest('issue_qnt');
 $issue_date = formatDate4insert(getRequest('issue_date'));
 $free_qnt = getRequest('free_qnt');
 $total_comm = getRequest('total_comm');
 $damage_qnt = getRequest('damage_qnt');
 $damage_price = getRequest('damage_price');
 $totalQnt = $issue_qnt+$free_qnt;
 $unit_value = getRequest('unit_value');
   $grm = getRequest('grm')*$issue_qnt;
 	$total_in_grm = $grm;
 	$total_in_kg = $grm/1000;

	$quantity = ($totalQnt/$unit_value);
	$quantity_unit  = floor($quantity);
	$quantityPcs = strrchr($quantity, ".");
	$quantity_pcs =  ($quantityPcs*$unit_value);
	 $TotalDmgCost=$damage_qnt*$damage_price;
	 $totalCost=$issue_qnt*$issu_unit_cost;

 	$IssueId ="UPDATE	inv_item_issue_main SET issue_date ='$issue_date'  WHERE issue_id = $issue_id ";
 	$ResIssueId =mysql_query($IssueId);
	
				if($ResIssueId){
				
					$sql = "UPDATE inv_item_issue_sub
							SET
								Item_ID = '$Item_ID',
								issue_qnt = $issue_qnt,
								free_qnt = $free_qnt,
								total_comm = $total_comm,
								total_qnt = $totalQnt,
								issu_qnt_unit = $quantity_unit,
								issu_qnt_pcs = $quantity_pcs,
								issu_unit_cost = $issu_unit_cost,
								issu_total_cost = $totalCost,
								issue_total_grm =$total_in_grm,
								issue_total_kg =$total_in_kg,
								damage_qnt =$damage_qnt,
								damage_price =$damage_price,
								total_damage_price =$TotalDmgCost,
								slman_id =$slman_id
							WHERE issue_subid = $issue_subid and issue_id = $issue_id";
						
						$res = mysql_query($sql);
							if($res)
							{
							echo $ItemIssue = $this->ItemIssueFetch();
							}// end if($res)
				
				}// end if($resMast)


}


function DeleteItemIssue(){
	$issue_subid = getRequest('issue_subid');
  	if($issue_subid)  {
		 $sql = "DELETE from ".INV_ITEM_ISSUE_SUB_TBL." where issue_subid = '$issue_subid'";
		$res = mysql_query($sql);	
		if($res){
		echo $ItemIssue = $this->ItemIssueFetch();
		}
  	}	
}  

function ItemIssueFetch($start = null, $limit = null){
$page 	= getRequest('page');
if($page){
  $sql="SELECT
	im.issue_id,
	issue_subid,
	ii.slman_id,
	s.name,
	ii.Item_ID,
	i.Item_Name,
	i.unit_value,
	i.grm,
	m.measurementunit,
	issue_qnt,
	free_qnt,
	total_comm,
	total_qnt,
	issu_qnt_unit,
	issu_qnt_pcs,
	issu_unit_cost,
	issu_total_cost,
	issue_total_grm,
	issue_total_kg,
	im.issue_date,
	damage_qnt,
	damage_price,
	total_damage_price

FROM
	".INV_ITEM_ISSUE_SUB_TBL." ii inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = ii.Item_ID
	inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id = i.mesure_id
	inner join ".INV_ITEM_ISSUE_MAIN_TBL." im on im.issue_id = ii.issue_id
	inner join ".INV_SALSEMAN_INFO_TBL." s on s.slman_id = ii.slman_id ORDER BY im.issue_date desc LIMIT $start, $limit";
	}else{
	 $sql="SELECT
	im.issue_id,
	issue_subid,
	ii.slman_id,
	s.name,
	ii.Item_ID,
	i.Item_Name,
	i.unit_value,
	i.grm,
	m.measurementunit,
	issue_qnt,
	free_qnt,
	total_comm,
	total_qnt,
	issu_qnt_unit,
	issu_qnt_pcs,
	issu_unit_cost,
	issu_total_cost,
	issue_total_grm,
	issue_total_kg,
	im.issue_date,
	damage_qnt,
	damage_price,
	total_damage_price

FROM
	".INV_ITEM_ISSUE_SUB_TBL." ii inner join ".INV_ITEMINFO_TBL." i on i.Item_ID = ii.Item_ID
	inner join ".INV_ITEM_MEASURE_TBL." m on m.mesure_id = i.mesure_id
	inner join ".INV_ITEM_ISSUE_MAIN_TBL." im on im.issue_id = ii.issue_id
	inner join ".INV_SALSEMAN_INFO_TBL." s on s.slman_id = ii.slman_id ORDER BY im.issue_date desc LIMIT 0,29";
}

		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="1100">
	            <tr>
				<th colspan=2 >Actions</th>
				 <th nowrap=nowrap align=left>Issue Date</th>
	             <th nowrap=nowrap align=left>Salesman</th>
	             <th nowrap=nowrap align=left>SKU Name</th>
				 <th nowrap=nowrap align=left>Pack</th>
				 <th nowrap=nowrap align=left>Pack Size</th>
				 <th nowrap=nowrap align=left>Issue Qnt(pcs)</th>
				 <th nowrap=nowrap align=left>Free Qnt(pcs)</th>
				 <th nowrap=nowrap align=left>Total Qnt(pcs)</th>
				 <th nowrap=nowrap align=left>In Pack</th>
				 <!--th nowrap=nowrap align=left>Total grm</th-->
				 <th nowrap=nowrap align=left>Total Kg</th>
				 <th nowrap=nowrap align=left>Issue price</th>
				 <th nowrap=nowrap align=left>Total Price</th>
				 <th nowrap=nowrap align=left>Damage Qnt</th>
				 <th nowrap=nowrap align=left>Damage Price</th>
				 <th nowrap=nowrap align=left>Total Damage Cost</th>
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
				$delEdit='<td><a href="javascript:ajaxCall4DeleteItemIssue(\''.$row['issue_subid'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td><a onClick="javascript:ajaxCall4EditItemIssue(\''.$row['issue_id'].'\',\''.
					 									  $row['issue_subid'].'\',\''.
					 									  $row['issue_qnt'].'\',\''.
														  $row['issu_unit_cost'].'\',\''.
														  $row['free_qnt'].'\',\''.
														  $row['total_comm'].'\',\''.
														  $row['damage_qnt'].'\',\''.
														  $row['damage_price'].'\',\''.
														  $row['Item_ID'].'\',\''.
														  $row['Item_Name'].'\',\''.
														  $row['unit_value'].'\',\''.
														  $row['grm'].'\',\''.
														  $row['name'].'\',\''.
														  $row['slman_id'].'\',\''.
						                                  dateInputFormatDMY($row['issue_date']).'\');"title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">'.$delEdit.'			
			<td nowrap=nowrap>'._date($row['issue_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['Item_Name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['measurementunit'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['unit_value'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['issue_qnt'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['free_qnt'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['total_qnt'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['issu_qnt_unit'].$subunt.' - '.$row['issu_qnt_pcs'].'P&nbsp;</td>
			<!--td nowrap=nowrap>'.$row['issue_total_grm'].'&nbsp;</td-->
			<td nowrap=nowrap>'.$row['issue_total_kg'].'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.$row['issu_unit_cost'].'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.$row['issu_total_cost'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['damage_qnt'].'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.$row['damage_price'].'&nbsp;</td>
			<td nowrap=nowrap>Tk. '.$row['total_damage_price'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

function ItemDpdw4Condense($ele_id,$sls,$uni_val,$ele_lbl_id){ 
	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =1 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];
		 
		 
    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];

   $sql2 = "SELECT sum(total_qnt) as total_qnt, sum(damage_qnt) as damage_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	 $tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."','".$IssueCost."','".$sls."','".$row['unit_value']."','".$uni_val."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }

function ItemDpdw4Powder($ele_id, $sls, $uni_val, $grm, $ele_lbl_id){ 
	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs,
	grm
	
	
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =2 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];

    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];


  $sql2 = "SELECT sum(total_qnt) as total_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	 $tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Powder('".$row['Item_ID']."','".$ele_id."', '".$IssueCost."','".$sls."','".$row['unit_value']."','".$uni_val."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }



function ItemDpdw4Tea($ele_id,$sls,$unit_value, $grm, $ele_lbl_id){ 

	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs,
	grm
	
	
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =3 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];

    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];

  $sql2 = "SELECT sum(total_qnt) as total_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	 $tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Tea('".$row['Item_ID']."','".$ele_id."','".$IssueCost."','".$sls."','".$row['unit_value']."','".$unit_value."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }


function ItemDpdw4Bev($ele_id,$sls,$uni_val, $ele_lbl_id){ 

	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs
	
	
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =4 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];

    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];


  $sql2 = "SELECT sum(total_qnt) as total_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	 $tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."', '".$IssueCost."','".$sls."','".$row['unit_value']."', '".$uni_val."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }


function ItemDpdw4Snak($ele_id,$sls,$unit_value, $grm, $ele_lbl_id){ 
	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs,
	grm
	
	
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =5 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];


    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];


   $sql2 = "SELECT sum(total_qnt) as total_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	$tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addItmId4Snak('".$row['Item_ID']."','".$ele_id."','".$IssueCost."','".$sls."','".$row['unit_value']."','".$unit_value."','".$row['grm']."','".$grm."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }



function ItemDpdw4Candy($ele_id,$sls,$uni_val,$ele_lbl_id){ 
	 $sql="SELECT
	rd.Item_ID,
	issu_unit_cost,
	i.Item_Name,
	i.unit_value,
	sum(totalpcs) as totalpcs
FROM
	".INV_RECEIVE_DETAIL_TBL." rd inner join ".INV_ITEMINFO_TBL." i on i.Item_ID=rd.Item_ID where i.Item_Category_ID =6 group by rd.Item_ID ";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=150 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 $totalpcs = $row['totalpcs'];
		 $Item_ID = $row['Item_ID'];
		 $Item_Name=$row['Item_Name'];

    $sqlf = "SELECT issu_unit_cost  FROM ".INV_RECEIVE_DETAIL_TBL." where receive_dtlid = (select MAX(receive_dtlid) from ".INV_RECEIVE_DETAIL_TBL." where Item_ID = '$Item_ID')";
	$resf =mysql_query($sqlf);
	$rowf = mysql_fetch_array($resf);
	$IssueCost = $rowf['issu_unit_cost'];

  $sql2 = "SELECT sum(total_qnt) as total_qnt FROM ".INV_ITEM_ISSUE_SUB_TBL." where Item_ID ='$Item_ID'";
	$res2 =mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);

	$total_qnt = $row2['total_qnt'];
	 $tqnt1 = $totalpcs-$total_qnt;
	if($totalpcs!=$total_qnt){
		$sd = $tqnt1;	$Itnm = $Item_Name;//$sd = 0; $Itnm = '-----';
	}else{ $sd = 0; $Itnm = $Item_Name; }
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
onClick=\"javascript:addItmId('".$row['Item_ID']."','".$ele_id."','".$IssueCost."','".$sls."','".$row['unit_value']."','".$uni_val."','".$row['Item_Name']."','".$ele_lbl_id."'); SetFreeUnitValue();\" style=\"cursor:pointer\" height=25>
		
		<td style=\"border-right:1px solid #cccccc;padding:2px;\"><input type=\"radio\" name=\"itm\" id=\"itm\" /></td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$Itnm."</td>
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$sd."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }


function SalesManDpDw($ele_id, $ele_lbl_id){ 
	     
		 $sql="SELECT slman_id, name from ".INV_SALSEMAN_INFO_TBL." ORDER BY slman_id LIMIT 0, 20";
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
		onClick=\"javascript:addSalsManId('".$row['slman_id']."','".$ele_id."','".$row['name']."','".$ele_lbl_id."');
		hideElement('slsManLookUp');\" style=\"cursor:pointer\">
		
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }





} // End class
?>
