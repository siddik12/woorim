<?php
class item_distribute
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
      	// case 'PersonList4ViewTrans' : $this->PersonList4ViewTrans();   					           	break;
      	 case 'ItemDistSkin'       : $this->ItemDistSkin();   					           	break;
      	 case 'save'               : $this->ItemDistSave();   					           	break;
		 case 'edit'               : $this->EditItemDist();      						   	break;
      	 case 'delete'             : $screen = $this->DeleteItemDist();				    break;
		 case 'ajaxSearch'         : echo $this->ItemInfoFetch();  					    break;
		 case 'ajaxSearchClientList'  : echo $this->SelectClient($ele_id = 'sub_item_category_id',$ele_id2 = 'main_item_category_id',$ele_id3 = 'balance', $ele_lbl_id = 'lbl_client');  		break;
         case 'list'               : $this->getList();   										break; 
         default                   : $cmd == 'list'; $this->getList();							break;
      }
 }
   
   function getList(){

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM ".INV_ITEMINFO_TBL."";
	$total_pages = mysql_fetch_array(mysql_query($query)); //exit();
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	//$targetpage = "?app=item_distribute"; 	//your file name  (the name of this file)
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
			$pagination.= "<a href=\"?app=item_distribute&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=item_distribute&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=item_distribute&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=item_distribute&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=item_distribute&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=item_distribute&page=1\">1</a>";
				$pagination.= "<a href=\"?app=item_distribute&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=item_distribute&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=item_distribute&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=item_distribute&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=item_distribute&page=1\">1</a>";
				$pagination.= "<a href=\"?app=item_distribute&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=item_distribute&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=item_distribute&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }	
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30;  $res2 = $Rec*30-1;}else { $res3=0; $res3=29;}
		 
	} 
	//return $pagination;
	$ItemInfo = $this->ItemInfoFetch($start, $limit);
  
	 require_once(CURRENT_APP_SKIN_FILE); 
	 
  }
  
  

  function ItemDistSkin(){
  $main_item_category_id = getRequest('main_item_category_id');// exit();
 		$ClientDropdown = $this->SelectClient($ele_id = 'sub_item_category_id',$ele_id2 = 'main_item_category_id',$ele_id3 = 'balance', $ele_lbl_id = 'lbl_client',$main_item_category_id);
		$branch_dropdown = $this->SelectBranch();
		$curdate=dateInputFormatDMY(SelectCDate());
		$SelectItmCategoryMain = $this->SelectItmCategoryMain($main_item_category_id);
  		require_once(ITEM_DISTRIBUTE_ADD_SKIN);
		 
}

function ItemDistSave(){

			$sub_item_category_id = getRequest('sub_item_category_id');
			$main_item_category_id = getRequest('main_item_category_id');
			$quantity = getRequest('quantity');
			$branch_id = getRequest('branch_id');
			$balance = getRequest('balance');
			$receive_date = formatDate4insert(getRequest('receive_date'));
		 	$createdby = getFromSession('userid');
		 	$createddate = date('Y-m-d H:i:s');
	
		$sql4="SELECT max(ware_house_id) as ware_house_idMX FROM ware_house where sub_item_category_id='$sub_item_category_id'";
		$ros4 = mysql_query($sql4);
		$res4 = mysql_fetch_array($ros4);
		$ware_house_idMX=$res4['ware_house_idMX'];
		
		$sql5 = "SELECT
				sub_item_category_id,
				item_size,
				description,
				main_item_category_id,
				cost_price,
				sales_price,
				whole_sales_price
			FROM
				ware_house where ware_house_id='$ware_house_idMX'";
		$ros5 = mysql_query($sql5);
		$res5 = mysql_fetch_array($ros5);
		
		$sub_item_category_id=$res5['sub_item_category_id'];
		$item_size=$res5['item_size'];
		$description=$res5['description'];
		$main_item_category_id=$res5['main_item_category_id'];
		$cost_price=$res5['cost_price'];
		$sales_price=$res5['sales_price'];
		$whole_sales_price=$res5['whole_sales_price'];
	
	if($balance>=$quantity){	
     	 $sql = "INSERT INTO ".INV_ITEMINFO_TBL."(sub_item_category_id,main_item_category_id,cost_price,sales_price,whole_sales_price,quantity,receive_date,branch_id,item_size,description,createdby, createddate)
values($sub_item_category_id,'$main_item_category_id','$cost_price','$sales_price','$whole_sales_price','$quantity','$receive_date','$branch_id','$item_size','$description','$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
     	    header("location:?app=item_distribute&cmd=ItemDistSkin&main_item_category_id=$main_item_category_id&msg=Successfully Saved"); 	
      	 }else
      	 {	
      		header("location:?app=item_distribute&cmd=ItemDistSkin&main_item_category_id=$main_item_category_id&msg=Not Saved");
      	   	
      	 } 
	 }else{
	  header("location:?app=item_distribute&cmd=ItemDistSkin&main_item_category_id=$main_item_category_id&msg=Not Saved!! Quantity not Sufficient!! ");
	 } //end Balance check//
   
}

  
function EditItemDist(){
  		$ClientDropdown = $this->SelectClient($ele_id = 'sub_item_category_id',$ele_id2 = 'main_item_category_id',$ele_id3 = 'balance', $ele_lbl_id = 'lbl_client',$main_item_category_id);
		$item_id = getRequest('item_id');
 	           $sql="SELECT
			   item_id,
				quantity,
				receive_date,
				branch_id,
				i.sub_item_category_id,
				i.main_item_category_id,
				main_item_category_name,
				sub_item_category_name
			FROM
				inv_iteminfo i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
				inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
				where item_id=$item_id";
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
		 		$item_id		= $res['item_id'];
		 		$quantity		= $res['quantity'];
				$branch_id		= $res['branch_id'];
				$receive_date	= dateInputFormatDMY($res['receive_date']);
				$sub_item_category_id		= $res['sub_item_category_id'];
				$main_item_category_id		= $res['main_item_category_id'];
				$main_item_category_name		= $res['main_item_category_name'];
				$sub_item_category_name		= $res['sub_item_category_name'];
				
	$branch_dropdown = $this->SelectBranch($branch_id);		
	 require_once(ITEM_DISTRIBUTE_EDIT_SKIN); 
	
	 if((getRequest('submit'))){
			$item_id = getRequest('item_id');
			$sub_item_category_id = getRequest('sub_item_category_id');
			$main_item_category_id = getRequest('main_item_category_id');
			$quantity = getRequest('quantity');
			$branch_id = getRequest('branch_id');
			$balance = getRequest('balance');
			$receive_date = formatDate4insert(getRequest('receive_date'));
	
$sql4="SELECT max(ware_house_id) as ware_house_idMX FROM ware_house where sub_item_category_id='$sub_item_category_id'";
		$ros4 = mysql_query($sql4);
		$res4 = mysql_fetch_array($ros4);
		$ware_house_idMX=$res4['ware_house_idMX'];
		
		$sql5 = "SELECT
				sub_item_category_id,
				item_size,
				description,
				main_item_category_id,
				cost_price,
				sales_price,
				whole_sales_price
			FROM
				ware_house where ware_house_id='$ware_house_idMX'";
		$ros5 = mysql_query($sql5);
		$res5 = mysql_fetch_array($ros5);
		
		$sub_item_category_id=$res5['sub_item_category_id'];
		$item_size=$res5['item_size'];
		$description=$res5['description'];
		$main_item_category_id=$res5['main_item_category_id'];
		$cost_price=$res5['cost_price'];
		$sales_price=$res5['sales_price'];
		$whole_sales_price=$res5['whole_sales_price'];

if($balance>=$quantity){		
     	 $sql = "UPDATE
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
				branch_id = '$branch_id',
				description='$description'
			WHERE item_id=$item_id"; //exit();
	$res = mysql_query($sql);
		if($res){
     	    header("location:?app=item_distribute&item_id=$item_id&msg=Successfully Edited"); 	
      	 }else
      	 {	
      		header("location:?app=item_distribute&msg=Not Edited");
      	   	
      	 } 
	 }else{
	  header("location:?app=item_distribute&cmd=save&msg=Not Saved!! Quantity not Sufficient!! ");
	 } //end Balance check//

  }
	 
}


function DeleteItemDist(){
	$id = getRequest('item_id');
      if($id)
      {
      	            	
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = INV_ITEMINFO_TBL;
		 $reqData = getUserDataSet(INV_ITEMINFO_TBL);
		 $info['data'] = $reqData; 
      	$info['where'] = "item_id=$id";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
      	   header("location:?app=item_distribute&Dmsg=Successfully Deleted!");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=item_distribute&Dmsg=Not Delete!");      	   	
      	}      	
      }	
}

//---------------------------------- Item Distribute List Fetch -------------------------------------------
function ItemInfoFetch($start = null, $limit = null){
$searchq 	= getRequest('searchq');
$page 	= getRequest('page');
$sub_item_category_id 	= getRequest('sub_item_category_id');
if($searchq){
	$sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.branch_id,
	branch_name,
	quantity,
	receive_date,
	i.createdby,
	i.createddate
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	where branch_name LIKE '%".$searchq."%' or main_item_category_name LIKE '%".$searchq."%' or sub_item_category_name LIKE '%".$searchq."%' order by receive_date desc LIMIT 0, 5"; 
	}if($page){
		$sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.branch_id,
	branch_name,
	quantity,
	receive_date,
	i.createdby,
	i.createddate
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id
	order by receive_date desc LIMIT $start, $limit"; 
	}if($sub_item_category_id){
		$sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.branch_id,
	branch_name,
	quantity,
	receive_date,
	i.createdby,
	i.createddate
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id 
	where sub_item_category_id=$sub_item_category_id order by receive_date desc"; 
	}
if(!$page && !$searchq && !$sub_item_category_id){
	$sql="SELECT
	item_id,
	i.sub_item_category_id,
	sub_item_category_name,
	item_size,
	description,
	i.main_item_category_id,
	main_item_category_name,
	cost_price,
	sales_price,
	whole_sales_price,
	i.branch_id,
	branch_name,
	quantity,
	receive_date,
	i.createdby,
	i.createddate
FROM
	".INV_ITEMINFO_TBL." i inner join ".INV_CATEGORY_SUB_TBL." c on c.sub_item_category_id=i.sub_item_category_id
	inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
	inner join branch b on b.branch_id=i.branch_id 
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
				 <th nowrap=nowrap align=left>Size</th>
				 <th nowrap=nowrap align=left>Date</th>
<!--				 <th nowrap=nowrap align=left>Description</th>
-->				 <th nowrap=nowrap align=left>Created By</th>
				 <th nowrap=nowrap align=left>Branch</th>
				<th></th>
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
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['description'].'&nbsp;</td>
-->			<td nowrap=nowrap>'.$row['createdby'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['branch_name'].'&nbsp;</td>
			<td width="30"><a href="?app=item_distribute&cmd=edit&item_id='.$row['item_id'].'"><img src="images/common/edit.gif" style="border:none"></a></td>
			<td width="30"><a href="?app=item_distribute&cmd=DeleteItem&item_id='.$row['item_id'].'" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			</tr>';
			
			$i++;
		}
		$type .= '</table>';
		return $type;
}//end fnc


  function SelectClient($ele_id,$ele_id2,$ele_id3, $ele_lbl_id,$main_item_category_id=null){
	   		$searchq =$_GET['searchq'];
		if($searchq){
		  $sql='SELECT
				i.main_item_category_id,
				main_item_category_name,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				sum(quantity) as quantity
			FROM
				'.WARE_HOUSE_TBL.' i inner join '.INV_CATEGORY_SUB_TBL.' c on c.sub_item_category_id=i.sub_item_category_id 
				inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id
				where  sub_item_category_name LIKE "%'.$searchq.'%" or main_item_category_name LIKE "%'.$searchq.'%" 
				group by i.sub_item_category_id order by i.sub_item_category_id LIMIT 0,29';
		}else
		{
		 $sql='SELECT
		  		i.main_item_category_id,
				main_item_category_name,
				i.sub_item_category_id,
				sub_item_category_name,
				item_size,
				item_name,
				sum(quantity) as quantity
			FROM
				'.WARE_HOUSE_TBL.' i inner join '.INV_CATEGORY_SUB_TBL.' c on c.sub_item_category_id=i.sub_item_category_id 
				inner join inv_item_category_main m on m.main_item_category_id=c.main_item_category_id 
				where c.main_item_category_id='.$main_item_category_id.' 
				group by i.sub_item_category_id order by i.sub_item_category_id LIMIT 0,29';
		}
	$res= mysql_query($sql);
	$html = "<table width=500  border=0 >";
                         $rowcolor=0;
						 
	while($row=mysql_fetch_array($res)){
	$quantity=$row['quantity'];
	$sub_item_category_name=$row['sub_item_category_name'];
	$sub_item_category_id=$row['sub_item_category_id'];
	$main_item_category_name=$row['main_item_category_name'];
	
/*	$sql2="SELECT sum(quantity-stock_out) as quantity2 FROM inv_iteminfo where item_code='$item_code'";
	$res2= mysql_query($sql2);
	$row2=mysql_fetch_array($res2);
	$quantity2=$row2['quantity2'];
	$balance=($quantity-$quantity2);
*/	
		$sql2c = "SELECT sum(stock_out) as stock_out FROM return_info where sub_item_category_id ='$sub_item_category_id' and return_purpose='Branch'";
		$res2c =mysql_query($sql2c);
		$row2c = mysql_fetch_array($res2c);
		$stock_out = $row2c['stock_out'];


		$sql3c = "SELECT sum(quantity) as quantity2 FROM inv_iteminfo  where sub_item_category_id ='$sub_item_category_id' ";
		$res3c =mysql_query($sql3c);
		$row3c = mysql_fetch_array($res3c);
		$quantity3 = $row3c['quantity2'];
		
		$sql4c = "SELECT sum(stock_out) as stock_out2 FROM damage_info  where sub_item_category_id ='$sub_item_category_id' ";
		$res4c =mysql_query($sql4c);
		$row4c = mysql_fetch_array($res4c);
		$quantity4 = $row4c['stock_out2'];
		
		
		//$tqnt1c =($distribut_quantity-$quantity3)+$stock_out-$quantity4;
		
			$ToSum1=($quantity+$stock_out);
			$ToSum2=($quantity3+$quantity4);
			$balance =($ToSum1-$ToSum2);

	
	
	
				
             if($rowcolor==0){
			$html .= "<tr  class=\"evenTr\" onMouseOver=\"this.className='highLightTr'\" onMouseOut=\"this.className='evenTr'\" 
						onClick=\"javascript:addClientId('".$row['sub_item_category_id']."','".$ele_id."','".$row['main_item_category_id']."','".$ele_id2."','".$balance."','".$ele_id3."','".$row['sub_item_category_name']."','".$ele_lbl_id."');\">
				
				<td width=\"100\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['sub_item_category_name']."</td>
				<td width=\"150\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['main_item_category_name']."</td>
<!--				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['item_size']."</td>
-->				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$balance."</td>
				</tr>";
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= "<tr class=\"oddTr\" onMouseOver=\"this.className='highLightTr'\" onMouseOut=\"this.className='oddTr'\" 
						onClick=\"javascript:addClientId('".$row['sub_item_category_id']."','".$ele_id."','".$row['main_item_category_id']."','".$ele_id2."','".$balance."','".$ele_id3."','".$row['sub_item_category_name']."','".$ele_lbl_id."');\">
				
				<td width=\"100\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['sub_item_category_name']."</td>
				<td width=\"150\"  style=\"border-right:1px solid #cccccc;padding:2px; border:hidden;\">".$row['main_item_category_name']."</td>
<!--				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['item_size']."</td>
-->				<td  style=\"border-right:1px solid #cccccc;padding:2px;\">".$balance."</td>
				</tr>";
			  $rowcolor=0;
			  }
	}
	$html .= "</table>";
	
	return $html;
  }// EOF

function SelectBranch($branch = null){ 
		$sql="SELECT branch_id, branch_name FROM branch where branch_id!=5 ";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='branch_id' size='1' id='branch_id' style='width:150px' class=\"validate[required]\">";
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


function SelectItmCategoryMain($main_item_category_id=null){ 
		$sql="SELECT main_item_category_id, main_item_category_name FROM ".INV_CATEGORY_MAIN_TBL." ORDER BY main_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='main_item_category_id' size='1' id='main_item_category_id' class=\"textBox\" style='width:120px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['main_item_category_id'] == $main_item_category_id){
					   $country_select .= "<option value='".$row['main_item_category_id']."' selected='selected'>".$row['main_item_category_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['main_item_category_id']."'>".$row['main_item_category_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }


function SelectItmCategorySub($main_item_category_id){ 
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



} // End class

?>