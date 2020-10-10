<?php
class whole_sales_accounts
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'WholeDueEnterSkin'          : echo $this->WholeDueEnterSkin();								break;
         case 'list'                  	: $this->getList();                       					break;
         default                      	: $cmd == 'list'; $this->getList();	       					break;
      }
 }
 
 
function getList(){
     if ((getRequest('submit'))) {
	 	$msg = $this->SaveWholeSalesInfo();
	 }
	 					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM detail_account ";
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
			$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

  	 $WholeSalesInfoTab = $this->WholeSalesInfoFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveWholeSalesInfo(){
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addSaveWholeSalesInfo();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		header('location:?app=whole_sales_accounts');
	  }
	   if($mode == 'edit'){
	 	$res = $this->EditSaveWholeSalesInfo();
		 if($res){
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
function addSalesmanInfo(){
  	 	 $info = array();
		 $info['table'] = WHOLE_SALER_ACC_TBL;
		 $reqData =  getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }

function EditSalesmanInfo(){
		 
		 $slman_id = getRequest('slman_id');
	 	 $info				 = array();
		 $info['table']     	= WHOLE_SALER_ACC_TBL;
		 $reqData        		 = getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $info['data'] = $reqData;
      	 $info['where']= "slman_id='$slman_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   

function SalesmanInfoFetch(){
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql="select slman_id, name, contact_no from ".INV_SALSEMAN_INFO_TBL." ";
			}else{
				  $sql="select slman_id, name, contact_no from ".INV_SALSEMAN_INFO_TBL." ";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table width=500 border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th></th>
				 <th  align="left">Name</th>
	             <th  align="left" nowrap="nowrap">Contact No.</th>
	       </tr>';
                         $rowcolor=0;
	
	while($row=mysql_fetch_array($res)){

				 if($rowcolor==0){ 
					$style = "oddClassStyle";$rowcolor=1;
				 }else{
					$style = "evenClassStyle";$rowcolor=0;
				 }
				$MeasurementTab .='<tr  class="'.$style.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\''.$style.'\'"
				onclick="javascript:updateSalesmanInfo(\''.$row['slman_id'].'\',\''.
														  $row['name'].'\',\''.
						                                  $row['contact_no'].'\');" >
					
					<td>
					<a href="index.php?app=accounts&cmd=delete&slman_id='.$row['slman_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td nowrap="nowrap">'.$row["name"].'&nbsp;</td>
					<td nowrap="nowrap">'.$row['contact_no'].'&nbsp;</td>
					</tr>';
	
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 

 function ChallanEnterSkin(){
    
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM detail_account ";
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
			$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts&cmd=ChallanEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $Challan = $this->ChallanInfoFetch($start, $limit);
	require_once(CHALLAN_ENTER_SKIN);
 } 


function InsertChallan(){
 $challan_no = getRequest('challan_no');
 if($challan_no){ 
 //=========== checked existing challan no in acc detail table =============
   $Sql1 = "SELECT * FROM ".ACC_DETAILS_TBL." where challan_no = '$challan_no'";
 $res1 = mysql_query($Sql1); //exit();

 //=========== checked  challan no in INV_RECEIVE_MASTER_TBL table =============
 $Sql2 = "select challan_no,supplier_id, receive_date from ".INV_ITEMINFO_TBL." where challan_no = '$challan_no'";
 $res2 = mysql_query($Sql2);
 $row2 = mysql_fetch_array($res2);
 $challan_no2 = $row2['challan_no'];
 $receive_date = $row2['receive_date'];
 $supplier_id = $row2['supplier_id'];
	
			if(mysql_num_rows($res1)>0){
			
		echo '<b>Sorry!! already you have entered this challan no</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?app=accounts&cmd=ChallanEnterSkin"><u>Go Back</u></a>';
			
			}else if($challan_no2!=$challan_no){
			
			echo '<b>Sorry!! not found this challan no</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?app=accounts&cmd=ChallanEnterSkin"><u>Go Back</u></a>';
			
			}else{
			 $Sql3 = "SELECT sum(total_cost) as totalCost FROM view_item_info where challan_no = '$challan_no2'";
		 	$res3 = mysql_query($Sql3);
		 	$row3 = mysql_fetch_array($res3);
		 	$totalCost = $row3['totalCost'];
				
/*				if($totalPrice){
				 $Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
 				$res4 = mysql_query($Sql4);
 				$row4 = mysql_fetch_array($res4);
 				$balance = $row4['balance'];
					   	 
						 $totalBl = $totalPrice+$balance;
*/   						  $sql = "INSERT INTO detail_account(supplier_id,challan_no,dr,recdate) values('$supplier_id','$challan_no2',$totalCost,'$receive_date')";
						$res = mysql_query($sql);
						if($res){
							echo $Challan = $this->ChallanInfoFetch($start, $limit);
							}

				//} //============ end if($totalPrice) ========
		
			} // ======== end else =======================
 
 		}// ===end 1st if($challan_no) =========================
 
 } // EOF ================
 
 
  function DelChallan(){
	$detail_accid = getRequest('detail_accid');
      if($detail_accid)
      {
      	            	
      	$info = array();
      	$info['table'] = ACC_DETAILS_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=accounts&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=accounts&Dmsg=Not Edited");      	   	
      	}      	
      }	


 }

 
 function ChallanInfoFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	 $sql='SELECT
	detail_accid,
	challan_no,
	particular,
	dr,
	cr,
	recdate,
	company_name
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id 
	where challan_no LIKE "%'.$searchq.'%" ORDER BY recdate desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	detail_accid,
	challan_no,
	particular,
	dr,
	cr,
	recdate,
	company_name
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id
	ORDER BY recdate desc LIMIT $start, $limit";
	}if(!$searchq && !$Page ){
$sql="SELECT
	detail_accid,
	challan_no,
	particular,
	dr,
	cr,
	recdate,
	company_name
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id
	ORDER BY recdate desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Received Date</th>
	             <th nowrap=nowrap align=left>Challan no</th>
	             <th nowrap=nowrap align=left>Particular</th>
				 <th nowrap=nowrap align=left>Dr.(Tk)</th>
				 <th nowrap=nowrap align=left>Cr.(Tk)</th>
				 <th nowrap=nowrap align=left>Supplier</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');
			
			 $Cmd = getRequest('cmd');
			
			if($Cmd=='ChallanEnterSkin'){
				$delEdit='<td><a href="?app=accounts&cmd=DelChallan&detail_accid='.$row['detail_accid'].'" onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>';
			}
			if($Cmd=='CheckEnterSkin'){

				$delEdit='<td><a href="?app=accounts&cmd=DelCheck&detail_accid='.$row['detail_accid'].'" onclick="return confirmDelete();" title="Delete"><img src="images/common/delete.gif" style="border:none"></a></td>';
			}

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >'.$delEdit.'
			<td nowrap=nowrap>'._date($row['recdate']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['challan_no'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['particular'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['dr'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cr'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['company_name'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc


 function checkEnterSkin(){
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM detail_account ";
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
			$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $Check = $this->ChallanInfoFetch($start, $limit);
 $SuplierList=$this->SelectSupplier();
	require_once(CHECK_ENTER_SKIN);
 } 


function InsertCheck(){
 $particulars = getRequest('particulars');
 $cr = getRequest('cr');
 $supplier_id = getRequest('supplier_id');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 
/*		$Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
		$res4 = mysql_query($Sql4);
		$row4 = mysql_fetch_array($res4);
		$balance = $row4['balance']; //exit();

			 if($balance){
					$totalBl = $balance-$cr;
*/					$sql = "INSERT INTO detail_account(supplier_id,particular,cr,recdate) values('$supplier_id','$particulars',$cr,'$receive_date')";
					$res = mysql_query($sql);
					if($res){
						echo $Check = $this->ChallanInfoFetch($start, $limit);
					}
			//}// end if($balance)===========================
  }// EOF =====================

 function DelCheck(){
	$detail_accid = getRequest('detail_accid');
      if($detail_accid)
      {
      	            	
      	$info = array();
      	$info['table'] = ACC_DETAILS_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=accounts&cmd=CheckEnterSkin&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=accounts&cmd=CheckEnterSkin&Dmsg=Not Edited");      	   	
      	}      	
      }	


 }
  


function SelectSupplier($sup = null){ 
		$sql="SELECT supplier_id, company_name FROM ".INV_SUPPLIER_INFO_TBL." ORDER BY company_name";
	    $result = mysql_query($sql);
		$country_select = "<select name='supplier_id' size='1' id='supplier_id' class=\"textBox\" style='width:150px;'>";
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



//===================== Whole Sales dues collection =============================
 function checkEnterSkin(){
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM detail_account ";
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
			$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts&cmd=CheckEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $Check = $this->WholeSaleInfoFetch($start, $limit);
 $CustomerList=$this->SelectCustomer();
	require_once(CHECK_ENTER_SKIN);
 } 


function InsertWholeDues(){
 $particulars = getRequest('particulars');
 $cr_amount = getRequest('cr_amount');
 $customer_ID = getRequest('customer_ID');
 $paid_date = formatDate4insert(getRequest('paid_date'));
 

					$sql = "INSERT INTO whole_saler_acc(customer_ID,invoice,cr_amount,paid_date) values('$customer_ID','$particulars',$cr_amount,'$paid_date')";
					$res = mysql_query($sql);
					if($res){
						echo $Check = $this->WholeSaleInfoFetch($start, $limit);
					}
  }// EOF =====================





























  function GLReportSkin(){
	require_once(MONTHLY_LEDGER_SKIN); 
  
  }
  
  function GLReportShowSkin4PDF(){
  $fromdt = formatDate4insert(getRequest('fromdt'));
  $todt = formatDate4insert(getRequest('todt'));
  $fromdt1 = _date($fromdt);
  $todt1 = _date($todt);
  $result = $this->GLReportFetch4PDF($fromdt,$todt);

	$mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0);
	 
	$mpdf->SetDisplayMode('fullpage');
	 
	$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
	 
	$mpdf->WriteHTML($result);
			 
	$mpdf->Output();
  
  }
   function GLReportFetch4PDF($fromdt,$todt){
	 $sqlc="SELECT
	challan_no,
	particular,
	dr,
	cr,
	recdate,
	@rt := @rt +(dr-cr) as balance
	
FROM
	detail_account, (SELECT @rt := 0 ) as tempName where recdate between '$fromdt' and '$todt'  order by recdate";
		
	//echo $sql;
	$resc= mysql_query($sqlc);
	$speci = "<!DOCTYPE html>
<html>
<head>
    <title>Print Ledger</title>
    <style>
        *
        {
            margin:0;
            padding:0;
            font-family:Arial;
            font-size:10pt;
            color:#000000;
        }
        body
        {
            width:100%;
            font-family:Arial;
            font-size:10pt;
            margin:0;
            padding:0;
        }
         
        p
        {
            margin:0;
            padding:0;
        }
         
        #wrapper
        {
            width:180mm;
            margin:0 15mm;
        }
         
        .page
        {
            height:297mm;
            width:210mm;
            page-break-after:always;
        }
 
        table
        {
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            border-spacing:0;
            border-collapse: collapse;
             
        }
         
        table td
        {
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 2mm;
        }
         
        table.heading
        {
            height:50mm;
        }
         
        h1.heading
        {
            font-size:14pt;
            color:#000;
            font-weight:bold;
        }
         
        h2.heading
        {
            font-size:9pt;
            color:#000;
            font-weight:normal;
        }
         
        hr
        {
            color:#ccc;
            background:#ccc;
        }
         
        #invoice_body
        {
            height: 149mm;
        }
         
        #invoice_body , #invoice_total
        {  
            width:100%;
        }
        #invoice_body table , #invoice_total table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
     
            border-spacing:0;
            border-collapse: collapse;
             
            margin-top:5mm;
        }
         
        #invoice_body table td , #invoice_total table td
        {
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding:2mm 0;
        }
         
        #invoice_body table td.mono  , #invoice_total table td.mono
        {
            font-family:monospace;
            text-align:right;
            padding-right:3mm;
            font-size:10pt;
        }
         
        #footer
        {  
            width:180mm;
            margin:0 15mm;
            padding-bottom:3mm;
        }
        #footer table
        {
            width:100%;
            border-left: 1px solid #ccc;
            border-top: 1px solid #ccc;
             
            background:#eee;
             
            border-spacing:0;
            border-collapse: collapse;
        }
        #footer table td
        {
            width:25%;
            text-align:center;
            font-size:9pt;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>
<div id=\"wrapper\">
     
<p style=\"text-align:center;\"><div style=\"font-weight:bolder; 	font-size:18px;color:#002953; font-style:oblique;\">M/S ZAHIR TRADERs</div> 
	<div style=\"font-weight:bold; font-size:12px;color:#002953; font-style:normal;\">Distributor of<br />".getFromSession('company_name')."</div>
	</p>
	<hr />
    <br />
    <div id=\"content\">
         
        <div id=\"invoice_body\">
<table>
	            <tr style=\"background:#eee;\"> 
	             <th>Date</th>
	             <!--th>Challan No</th-->
	             <th>Ptcls</th>
				 <th>Dr.</th>
				 <th>Cr.</th>
				 <th >Balance</th>
	       </tr>";

                 $rowcolor=0;
	while($rowc=mysql_fetch_array($resc)){
		 $recdate = _date($rowc['recdate']);
		 $challan_no = $rowc['challan_no'];
		 $particular = $rowc['particular'];
		 $dr=$rowc['dr'];
		 $cr=$rowc['cr'];
		 $balance=$rowc['balance'];

  if($challan_no!=''){
   $sql2 = "SELECT
	Item_Name,
	totalpcs,
	unit_price,
	quantity_unit,
	quantity_pcs,
	total_in_kg
FROM
	inv_receive_detail d inner join inv_receive_master m on m.mst_receiv_id = d.mst_receiv_id
	inner join inv_iteminfo i on i.Item_ID = d.Item_ID where challan_no='$challan_no' ";
	//}
	$res2 =mysql_query($sql2);
	$tab="<table>";


	while($row2 = mysql_fetch_array($res2)){
	$Item_Name = $row2['Item_Name'];
	$totalpcs = $row2['totalpcs'];
	$unit_price = $row2['unit_price'];
	$quantity_unit = $row2['quantity_unit'];
	$total_in_kg = $row2['total_in_kg'];
	
	$tab .="<tr height=8>
    <td width=100>".$Item_Name."</td>
    <td>".$totalpcs."</td>
    <td>".number_format($unit_price, 2)."</td>
    <td>".$quantity_unit."</td>
    <td>".$total_in_kg."</td>
  </tr>";
  $carton = $carton+$quantity_unit;
  $KG = $KG+$total_in_kg;
	}
$tab .="</table>";
  
  }// end if($particular==''){

			$speci .= "<tr height=25>
		
		<td valign=\"top\" width=50>".$recdate."</td>
		<!--td valign=\"top\">&nbsp;".$particular."</td-->
		<td  valign=\"top\" width=250>".$challan_no.$particular.$tab."</td>
		<td valign=\"top\" align=right>".number_format($dr,2)."&nbsp;</td>
		<td valign=\"top\" align=right>".number_format($cr,2)."&nbsp;</td>
		<td valign=\"top\" align=right>".number_format($balance,2)."&nbsp;</td>
		</tr>";
	$drAmnt = $drAmnt+$dr;
	$crAmnt = $crAmnt+$cr;
	}

	$speci .= "<tr height=25>
	<td>&nbsp;</td>
	<!--td>&nbsp;</td-->
	<td align=right><b>Total :</b></td>
	<td align=right><b>".number_format($drAmnt,2)."</b></td>
	<td align=right><b>".number_format($crAmnt,2)."</b></td>
	<td>&nbsp;</td>
	</tr>
	</table>
	</div>
</div>

     
</div>
    <htmlpagefooter name=\"footer\">
        <hr />
        <div id=\"footer\">
            <table>
                <tr><td>Zahir Traders</td><td>&nbsp;</td><td>Signature ____________________</td></tr>
            </table>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name=\"footer\" value=\"on\" />
     
</body>
</html>";
	return $speci;

}//end fnc
  
  
  
  function GLReportShowSkin(){
  $fromdt = formatDate4insert(getRequest('fromdt'));
  $todt = formatDate4insert(getRequest('todt'));
  $fromdt1 = _date($fromdt);
  $todt1 = _date($todt);
  $result = $this->GLReportFetch($fromdt,$todt);

require_once(SHOW_GL_REPORT_SKIN);  
  }




 function GLReportFetch($fromdt,$todt){
 // Previous calculation ==========
 $sqlPre="SELECT sum(dr) as debit_total, sum(cr) as credit_total FROM detail_account where recdate<'$fromdt'";
 $resPre= mysql_query($sqlPre);
 while($rowpre=mysql_fetch_array($resPre)){
		 $debit_total = $rowpre['debit_total'];
		 $credit_total = $rowpre['credit_total'];
		 }
 
 //======= pre calculation end ===========
 
 
	 $sqlc="SELECT
	challan_no,
	particular,
	dr,
	cr,
	recdate,
	@rt := @rt +(dr-cr) as balance
	
FROM
	detail_account, (SELECT @rt := 0 ) as tempName where recdate between '$fromdt' and '$todt'  order by recdate";
		
	//echo $sql;
	$resc= mysql_query($sqlc);
	$speci = "<table width=\"500\" border=0 style=\"background-color:#FFFFFF;\">
	            <tr style=\"background:#eee;\"> 
	             <th>Date</th>
	             <!--th>Challan No</th-->
	             <th>Particulars</th>
				 <th>Dr.</th>
				 <th>Cr.</th>
				 <th >Balance</th>
	       </tr>";

                 $rowcolor=0;
	while($rowc=mysql_fetch_array($resc)){
		 $recdate = _date($rowc['recdate']);
		 $challan_no = $rowc['challan_no'];
		 $particular = $rowc['particular'];
		 $dr=$rowc['dr'];
		 $cr=$rowc['cr'];
		 $balance=$rowc['balance'];

 // if($challan_no!=''){
   $sql2 = "SELECT
	Item_Name,
	totalpcs,
	unit_price,
	quantity_unit,
	quantity_pcs,
	total_in_kg
FROM
	inv_receive_detail d inner join inv_receive_master m on m.mst_receiv_id = d.mst_receiv_id
	inner join inv_iteminfo i on i.Item_ID = d.Item_ID where challan_no='$challan_no' ";
	//}
	$res2 =mysql_query($sql2);
	$tab="<table width=200>";


	while($row2 = mysql_fetch_array($res2)){
	$Item_Name = $row2['Item_Name'];
	$totalpcs = $row2['totalpcs'];
	$unit_price = $row2['unit_price'];
	$quantity_unit = $row2['quantity_unit'];
	$total_in_kg = $row2['total_in_kg'];
	
	$tab .="<tr>
    <td width=100 style=\"border:#ccc solid 1px;\">".$Item_Name."</td>
    <td width=50 style=\"border:#ccc solid 1px;\">".$totalpcs."</td>
    <td width=50 style=\"border:#ccc solid 1px;\">".number_format($unit_price, 2)."</td>
    <td width=50 style=\"border:#ccc solid 1px;\">".$quantity_unit."</td>
    <td width=50 style=\"border:#ccc solid 1px;\">".$total_in_kg."</td>
  </tr>";
	}
$tab .="</table>";
  
  //}// end if($$challan_no=='')
  
   if($challan_no!=''){ $showTab= $tab;}else{ $showTab= ''; }

			$speci .= "<tr >
		
		<td valign=\"top\" width=70 style=\"border:#000000 solid 1px;\">".$recdate."</td>
		<!--td valign=\"top\">&nbsp;".$particular."</td-->
		<td  valign=\"top\" style=\"border:#000000 solid 1px;\"><b>".$challan_no.$particular."</b>".$showTab."</td>
		<td valign=\"top\" align=right width=70  style=\"border:#000000 solid 1px;\">".number_format($dr,2)."&nbsp;</td>
		<td valign=\"top\" align=right width=70 style=\"border:#000000 solid 1px;\">".number_format($cr,2)."&nbsp;</td>
		<td valign=\"top\" align=right width=70  style=\"border:#000000 solid 1px;\">".number_format($balance,2)."&nbsp;</td>
		</tr>";
	$drAmnt = $drAmnt+$dr;
	$crAmnt = $crAmnt+$cr;
	}
		$preBalance=($drAmnt+$debit_total)-($crAmnt+$credit_total);
		$LastBalance=($drAmnt-$crAmnt);

	$speci .= "<tr height=40>
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	<!--td>&nbsp;</td-->
	<td align=right style=\"border:#000000 solid 1px;\"><b>Total Amount:</b></td>
	<td align=right style=\"border:#000000 solid 1px;\"><b>".number_format($drAmnt,2)."<br>Prevous Amount : ".number_format($debit_total,2)."</b></td>
	<td align=right style=\"border:#000000 solid 1px;\"><b>".number_format($crAmnt,2)."<br>Prevous Amount : ".number_format($credit_total,2)."</b></td>
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	</tr>
	<tr height=40>
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	<!--td>&nbsp;</td-->
	<td align=right style=\"border:#000000 solid 1px;\"><b>Total:</b></td>
	<td align=right style=\"border:#000000 solid 1px;\"><b>".number_format($drAmnt+$debit_total,2)."</b></td>
	<td align=right style=\"border:#000000 solid 1px;\"><b>".number_format($crAmnt+$credit_total,2)."</b></td>
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	</tr>
	<tr height=40 >
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	<!--td>&nbsp;</td-->
	<td align=right style=\"border:#000000 solid 1px;\"><b>Balance :</b></td>
	<td align=right style=\"border:#000000 solid 1px;\" colspan=\"2\"><b>".number_format($preBalance,2)."</b></td>
	<td style=\"border:#000000 solid 1px;\">&nbsp;</td>
	</tr>
	</table>";
	return $speci;

}//end fnc
  
  function notPendingListSkin(){
  $notPending = $this->NotPendingFetch();
  require_once(NOT_PENDING_BILL_ENTRY_SKIN); 
}//end fnc


function NotPendingFetch(){
$searchq 	= getRequest('searchq');
	//$WhereSrch = " bill_isuu_date LIKE '%".$searchq."%' and ststus='Not Pending' ORDER BY pending_id   LIMIT 0,80 "; 
	//$ordr = " ORDER BY pending_id   LIMIT 0,80";
	
	if($searchq){
	$sql=" SELECT
	pending_id,
	m.month_name,
	bt.type_name,
	bill_isuu_date,
	bill_amount,
	ststus
	FROM ".INV_PENDING_BILL_TBL." pb inner join ".INV_BILL_TYPE_TBL." bt on bt.bid=pb.bid 
	inner join ".BILL_MONTH_TBL." m on m.month_id = pb.month_id 
	where bill_isuu_date LIKE '%".$searchq."%' and ststus='Not Pending' ORDER BY pending_id   LIMIT 0,80";

	}else{
	$sql=" SELECT
	pending_id,
	m.month_name,
	bt.type_name,
	bill_isuu_date,
	bill_amount,
	ststus
	FROM ".INV_PENDING_BILL_TBL." pb inner join ".INV_BILL_TYPE_TBL." bt on bt.bid=pb.bid  
	inner join ".BILL_MONTH_TBL." m on m.month_id = pb.month_id
	where ststus='Not Pending' ORDER BY pending_id   LIMIT 0,80";

	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=960 cellspacing=0 class="tableGrid" >
	            <tr>
	             <th nowrap=nowrap align=left>Name of Bill</th>
	             <th nowrap=nowrap align=left>Submitted Amount</th>
				 <th nowrap=nowrap align=left>Submitted Date</th>
				 <th nowrap=nowrap align=left>Status</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">
			<td nowrap=nowrap>'.$row['type_name'].' of '.$row['month_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['bill_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'._date($row['bill_isuu_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['ststus'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;

}


	function EditpendingBill(){
	$pending_id2 = getRequest('pending_id2');
	$bid = getRequest('bid');
 	$month_id = getRequest('month_id');
	$bill_amount = getRequest('bill_amount');
	$bill_isuu_date = formatDate4insert(getRequest('bill_isuu_date'));
	
	 $sql = "UPDATE	".INV_PENDING_BILL_TBL." SET bid = $bid, month_id = $month_id, bill_isuu_date = '$bill_isuu_date', bill_amount =$bill_amount
	 WHERE pending_id = $pending_id2";
	$res =mysql_query($sql);
	
		if($res){
			echo $Bill = $this->PendingBillFetch();
		}
	}


function DeletePendingBill(){
	$pending_id = getRequest('pending_id');
      if($pending_id)
      {
      	$sql ="DELETE FROM ".INV_PENDING_BILL_TBL." WHERE pending_id=$pending_id";            	
      	$res= mysql_query($sql);
		
		if($res){   
			// echo 'deleted..';  	   
      	   echo $Bill = $this->PendingBillFetch();      	         	   
      	}      	
      }	
 
 } // EOF

	
  
  function PendingbillEntrySkin(){
  $billtype = $this->SelectBillType();
  $Monthtype = $this->SelectMonth();
  $Bill = $this->PendingBillFetch();
	require_once(PENDING_BILL_ENTRY_SKIN);
 } 
 function InsertBill(){
 $bid = getRequest('bid');
 $month_id = getRequest('month_id');
 $bill_amount = getRequest('bill_amount');
 $bill_isuu_date = formatDate4insert(getRequest('bill_isuu_date'));
 $createddate = date('Y-m-d');
    	 $sql = "INSERT INTO ".INV_PENDING_BILL_TBL."(bid,month_id,bill_amount,bill_isuu_date,createddate) 
			values($bid,$month_id,$bill_amount,'$bill_isuu_date', '$createddate')";
	$res = mysql_query($sql);
		if($res){// echo 'Saved';
			echo $Bill = $this->PendingBillFetch();
			}
  }
function AddToLedger(){
 $pending_id1 = getRequest('pending_id1');
 $bill_type = getRequest('bill_type');
 $cr = getRequest('cr');
 $particulars = getRequest('particulars');
 $parti = $bill_type.'('.$particulars.')';
 $receive_date = formatDate4insert(getRequest('receive_date'));
// $createddate = date('Y-m-d');
/* 
				$Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
 				$res4 = mysql_query($Sql4);
 				$row4 = mysql_fetch_array($res4);
 				$balance = $row4['balance']; //exit();


			 if($balance){
						$totalBl = $balance-$cr;
*/   						$sql = "INSERT INTO detail_account(particular,cr,recdate) values('$parti',$cr,'$receive_date')";
						$res = mysql_query($sql);
						if($res){
							$updateAcc = "update ".INV_PENDING_BILL_TBL." set ststus='Not Pending'	where pending_id = $pending_id1 ";
							$updateres = mysql_query($updateAcc);
							if($updateres){
							echo $Bill = $this->PendingBillFetch();			     
							}else{
							echo $Bill = $this->PendingBillFetch();
							}
	 					}// end if($res)------------------------------
					//}// end if($balance)===========================

} // EOF ================



function PendingBillFetch(){
$searchq 	= getRequest('searchq');
	 $sql=" SELECT
	pending_id,
	pb.bid,
	pb.month_id,
	m.month_name,
	bt.type_name,
	bill_isuu_date,
	bill_amount,
	ststus
	FROM ".INV_PENDING_BILL_TBL." pb inner join ".INV_BILL_TYPE_TBL." bt on bt.bid=pb.bid 
	inner join ".BILL_MONTH_TBL." m on m.month_id = pb.month_id";
	$WhereSrch = " bill_isuu_date LIKE '%".$searchq."%' and ststus='Pending' "; 
	$ordr = " ORDER BY pending_id   LIMIT 0,80";
	
	if($searchq){
			  $sql .= " where ".$WhereSrch.$ordr." ";
		}else{
				 $sql .= " where ".$WhereSrch.$ordr;
		}
		//echo $sql;
		$res= mysql_query($sql);

	      $type = '<table width="1000" cellspacing=0 class="tableGrid" >
	            <tr>
				<th>Delete</th>
				<th>Edit</th>
	             <th nowrap=nowrap align=left>Name of Bill</th>
	             <th nowrap=nowrap align=left>Amount</th>
				 <th nowrap=nowrap align=left>Date</th>
				 <th nowrap=nowrap align=left>Status</th>
				 <th nowrap=nowrap align=left>Issue Amount</th>
				 <th nowrap=nowrap align=left>Tracking Id</th>
				 <th nowrap=nowrap align=left>Issue Date</th>
				 <th nowrap=nowrap align=left></th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$createdby = getFromSession('userid');
			
			if($createdby=='1401'){
				$delEdit='<td><a href="javascript:ajaxCall4DeleteBill(\''.$row['pending_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td><a onClick="javascript:ajaxCall4EditBill(\''.$row['pending_id'].'\',\''.
														  $row['bid'].'\',\''.
														  $row['month_id'].'\',\''.
														  $row['bill_amount'].'\',\''.
						                                  dateInputFormatDMY($row['bill_isuu_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}
			
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" type_name
			onMouseOut="this.className=\''.$style.'\'" height="30">'.$delEdit.'			
			<td width=250><input type="hidden" name="pending_id1" id="pending_id1" value="'.$row['pending_id'].'" size=3 />
			<input name="bill_type" id="bill_type" type="text" style="border:none" size=40  value="'.$row['type_name'].' of '.$row['month_name'].'" readonly="readonly"/></td>
			<td >'.$row['bill_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'._date($row['bill_isuu_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['ststus'].'&nbsp;</td>
			<td nowrap=nowrap><input name="cr" id="cr" type="text" size="9" 
			class="textBox" onfocus="this.className=\'onFocus\'" onblur="this.className=\'onBlur\'"/></td>
			<td nowrap=nowrap><input name="particulars" id="particulars" type="text" size="9" 
			class="textBox" onfocus="this.className=\'onFocus\'" onblur="this.className=\'onBlur\'"/></td>
			<td nowrap=nowrap><input name="receive_date" id="receive_date" type="text" size="8"
			class="textBox" onfocus="this.className=\'onFocus\'" onblur="this.className=\'onBlur\'" /> 
			<img src="images/common/icons/calendar.gif" align="absmiddle" width="20" height="20"
		  onClick="gfPop.fPopCalendar(document.getElementById(\'receive_date\'));return false;" style="cursor:pointer;" title="calendar" /></td>
			<td nowrap=nowrap><label class="btnClass"  onmouseover="this.className=\'btnPress\'" 
			onmouseout="this.className=\'btnClass\'" ><a onclick="javascript:if(validate_4Billpending()){ajaxCall4Addtoledger();}" style="color:red">Submit</a></label></td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc



function OpeningBEnterSkin(){
 $Check = $this->ChallanInfoFetch();
	require_once(ENTER_OPENING_BALANCE_SKIN);
 } 
function InsertOpeningBalance(){
 $particulars = getRequest('particulars');
 $dr = getRequest('dr');
 $receive_date = formatDate4insert(getRequest('receive_date'));
/* 
				$Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
 				$res4 = mysql_query($Sql4);
 				$row4 = mysql_fetch_array($res4);
 				$balance = $row4['balance']; //exit();

			 if($balance){
*/						//$totalBl = $balance+$dr;
   						 $sql = "INSERT INTO detail_account(particular,dr,recdate) values('$particulars',$dr,'$receive_date')";
						$res = mysql_query($sql);
						if($res){
							echo $Check = $this->ChallanInfoFetch();
							}
					//}// end if($balance)===========================
  }// eof =======================
 
 function DelOpeningBalance(){
	$detail_accid = getRequest('detail_accid');
      if($detail_accid)
      {
      	            	
      	$info = array();
      	$info['table'] = ACC_DETAILS_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=accounts&cmd=OpeningBEnterSkin&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=accounts&cmd=OpeningBEnterSkin&Dmsg=Not Edited");      	   	
      	}      	
      }	


 }
  
  
  

 
 function DrVoucherEnterSkin(){
 $Dr_voucher = $this->ChallanInfoFetch();
	require_once(DR_VOUCHER_SKIN);
 } 


function InsertDrVoucher(){
 $particulars = getRequest('particulars');
 $dr = getRequest('dr');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 
/*		$Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
		$res4 = mysql_query($Sql4);
		$row4 = mysql_fetch_array($res4);
		$balance = $row4['balance']; //exit();

			 if($balance){
					$totalBl = $balance-$cr;
*/					$sql = "INSERT INTO detail_account(particular,dr,recdate) values('$particulars',$dr,'$receive_date')";
					$res = mysql_query($sql);
					if($res){
						echo $Check = $this->ChallanInfoFetch();
					}
			//}// end if($balance)===========================
  }// EOF =====================
  
  function CrVoucherEnterSkin(){
 $Cr_voucher = $this->ChallanInfoFetch();
	require_once(CR_VOUCHER_SKIN);
 } 

 function InsertCrVoucher(){
 $particulars = getRequest('particulars');
 $cr = getRequest('cr');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 
/*		$Sql4 = "select balance from ".ACC_DETAILS_TBL." where detail_accid = (select max(detail_accid) from detail_account)";
		$res4 = mysql_query($Sql4);
		$row4 = mysql_fetch_array($res4);
		$balance = $row4['balance']; //exit();

			 if($balance){
					$totalBl = $balance-$cr;
*/					$sql = "INSERT INTO detail_account(particular,cr,recdate) values('$particulars',$cr,'$receive_date')";
					$res = mysql_query($sql);
					if($res){
						echo $Check = $this->ChallanInfoFetch();
					}
			//}// end if($balance)===========================
  }// EOF =====================

 
  
 /* function EditCheck(){
	$glid = getRequest('glid');
	$particulars = getRequest('particulars');
 	$cr = getRequest('cr');
	$receive_date = formatDate4insert(getRequest('receive_date'));
	
	 $sql = "UPDATE	".GENERAL_LEDGER_TBL." SET particulars = '$particulars', cr = $cr,  receive_date ='$receive_date'	 WHERE glid = $glid";
	$res =mysql_query($sql);
	
		if($res){
			echo $Check = $this->CheckInfoFetch();
		}
	}
*/

function CheckInfoFetch($start = null, $limit = null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
 $sql=" SELECT
	glid,
	particulars,
	dr,
	cr,
	receive_date
	FROM
	".GENERAL_LEDGER_TBL." where particulars LIKE '%".$searchq."%' ORDER BY receive_date desc LIMIT 0,29";
	}
if($Page){
$sql=" SELECT
	glid,
	particulars,
	dr,
	cr,
	receive_date
	FROM
	".GENERAL_LEDGER_TBL." ORDER BY receive_date desc LIMIT $start, $limit";
}		
if(!$searchq && !$Page){
$sql=" SELECT
	glid,
	particulars,
	dr,
	cr,
	receive_date
	FROM
	".GENERAL_LEDGER_TBL." ORDER BY receive_date desc LIMIT 0,29";
}		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=480 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Issue Date</th>
	             <th nowrap=nowrap align=left>Particulars</th>
				 <th nowrap=nowrap align=left>Dr.(Tk)</th>
				 <th nowrap=nowrap align=left>Cr.(Tk)</th>
				 <!--th nowrap=nowrap align=left>Balance</th-->
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$createdby = getFromSession('userid');
			
			if($createdby=='1401'){
				$delEdit='<td><a href="javascript:ajaxCall4EditCheck(\''.$row['glid'].'\',\''.
					 									  $row['particulars'].'\',\''.
														  $row['cr'].'\',\''.
						                                  dateInputFormatDMY($row['receive_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>';
			}
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >'.$delEdit.'
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['particulars'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['dr'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cr'].'&nbsp;</td>
			<!--td nowrap=nowrap>'.$row['balance'].'&nbsp;</td-->
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc


function MeasurementSkin(){
$MeasurementUnitFetch = $this->MeasurementUnitFetch();
//$SaveMeasurementInfo = AddSMeasurementInfo();
require_once(INV_MEASUREMENT);
}

function AddSMeasurementInfo(){
 $measurementunit = getRequest('measurementunit');
 $measurementname = getRequest('measurementname');
 $created_by = getFromSession('userid');
 $createddate = date('Y-m-d');
    $sql = "INSERT INTO ".INV_ITEM_MEASURE_TBL."(measurementunit, measurementname, createdby, createddate)
			VALUES ('$measurementunit', '$measurementname', '$created_by', '$createddate')";
	$res = mysql_query($sql);
	if($res){
		echo $MeasurementUnitFetch = $this->MeasurementUnitFetch();
	}
 
 }
function DeleteMeasurement(){
	$measurementunit = getRequest('measurementunit');
  	if($measurementunit)  {
		 $sql = "DELETE from ".INV_ITEM_MEASURE_TBL." where measurementunit = '$measurementunit' " ;
		$res = mysql_query($sql);	
		if($res){echo $MeasurementUnitFetch = $this->MeasurementUnitFetch();}
  	}	
}  
  
function MeasurementUnitFetch(){
	 $searchq = getRequest('searchq');
		if($searchq){
					$sql="SELECT measurementunit, measurementname, createdby, createddate FROM	".INV_ITEM_MEASURE_TBL."
							 WHERE measurementunit LIKE '%".$searchq."%' or measurementname LIKE '%".$searchq."%' ORDER BY measurementunit";
			}else{
				  $sql="SELECT measurementunit, measurementname, createdby, createddate FROM	".INV_ITEM_MEASURE_TBL." ";
		 }
		$res= mysql_query($sql);
	
		$MeasurementTab = '<table border=0 cellspacing=0 class="tableGrid">
	            <tr>
				 <th></th>
	             <th  align="left" width=50>Unit</th>
				 <th  align="left" width=80 >Name</th>
				 <th  align="left" width=100 nowrap="nowrap">Created By</th>
	             <th   align="left" width=100 nowrap="nowrap">Date</th>
	       </tr>';
                         $rowcolor=0;
	
	while($row=mysql_fetch_array($res)){

				 if($rowcolor==0){ 
					$style = "oddClassStyle";$rowcolor=1;
				 }else{
					$style = "evenClassStyle";$rowcolor=0;
				 }
				 
				$MeasurementTab .='<tr  class="'.$style.'" onMouseOver="this.className=\'highlight\'" onMouseOut="this.className=\''.$style.'\'"
				onclick="javascript:updateSupplierInfo(\''.$row['measurementunit'].'\',\''.
														  $row['measurementname'].'\',\''.
														  $row['createdby'].'\',\''.
						                                  $row['createddate'].'\');" >
					
					<td>
					<a href="javascript:ajaxCallDelMeasurement(\''.$row['measurementunit'].'\');" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td nowrap="nowrap" width=50>'.$row["measurementunit"].'&nbsp;</td>
					<td nowrap="nowrap" width = 80>'.$row['measurementname'].'&nbsp;</td>
					<td width = 100>'.$row['createdby'].'&nbsp;</td>
					<td nowrap="nowrap" width=100>'._date($row["createddate"]).'&nbsp;</td>

					</tr>';
	
	}
	$MeasurementTab .= '</table>';
	return $MeasurementTab;
}	 


function CollectionEntrySkin(){
$SR = $this->SelectSR();
$srBill = $this->SRCollectFetch();
require_once(COLLECTION_ENTRY_SKIN);
}

function InsertSRCollection(){
 $slman_id = getRequest('slman_id');
 $due_amount = getRequest('due_amount');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 
			 $sql = "INSERT INTO ".SR_BILL_TBL."(slman_id, due_amount, receive_date) 
				values($slman_id,  $due_amount, '$receive_date')";
		   $res = mysql_query($sql);
		   
				if($res){// echo 'Saved';
					echo $srBill = $this->SRCollectFetch();
				}// end if($res)
			

}


function SRbillEntrySkin(){
$SR = $this->SelectSR();
$srBill = $this->SRBillFetch();
require_once(SR_BILL_ENTRY_SKIN);
}


 function InsertSRBill(){
 $slman_id = getRequest('slman_id');
 $receive_amount = getRequest('receive_amount');
 $due_amount = getRequest('due_amount');
 $receive_date = formatDate4insert(getRequest('receive_date'));
 
   $sqltcom = "SELECT sum(total_comm) as total_comm, SUM(issu_total_cost) AS issu_total_cost FROM
	".INV_ITEM_ISSUE_MAIN_TBL." m inner join ".INV_ITEM_ISSUE_SUB_TBL." s on m.issue_id= s.issue_id
	where m.slman_id=$slman_id and issue_date='$receive_date' ";
	$restcom= mysql_query($sqltcom);
	 while($rowtcom=mysql_fetch_array($restcom)){
	 $total_comm = $rowtcom['total_comm'];
	 $issu_total_cost = $rowtcom['issu_total_cost'];
	 }
	
//===================== Check sales has or not for thid date==================
 if(mysql_num_rows($restcom)<=0){
    echo '<b>Sorry!! not any sales for this date or dsr.</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?app=accounts&cmd=SrBillEntrySkin"><u>Go Back</u></a>';
 //============================================================================
}else{

	 $sql1="SELECT * FROM ".SR_BILL_TBL." where slman_id=$slman_id and receive_date = '$receive_date'";
	 $res1 = mysql_query($sql1);
	 if(mysql_num_rows($res1)>0){
		echo '<b>Sorry!! Already you have received from this DSR.</b>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?app=accounts&cmd=SrBillEntrySkin"><u>Go Back</u></a>';
	 }else{
		
			 $sql = "INSERT INTO ".SR_BILL_TBL."(slman_id, issuevalues, total_commi, receive_amount, due_amount,  receive_date) 
				values($slman_id, $issu_total_cost, $total_comm, $receive_amount,$due_amount,'$receive_date')"; //exit();
		  		 $res = mysql_query($sql);
		   
				if($res){// echo 'Saved';
					echo $srBill = $this->SRBillFetch();
				}// end if($res)

		}// end else
		
 } // end else
}// EOF

	function EditSRBill(){
	$accid = getRequest('accid');
	 $slman_id = getRequest('slman_id');
	 $receive_amount = getRequest('receive_amount');
	 $due_amount = getRequest('due_amount');
	 $receive_date = formatDate4insert(getRequest('receive_date'));
	
	$sql = "UPDATE	".SR_BILL_TBL." SET slman_id =$slman_id, receive_amount =$receive_amount, due_amount =$due_amount ,	
			receive_date = '$receive_date' WHERE accid = $accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $srBill = $this->SRBillFetch();
		}
	}

function DeleteSRBill(){
	$accid = getRequest('accid');
      if($accid)
      {
      	            	
      	$info = array();
      	$info['table'] = SR_BILL_TBL;
      	$info['where'] = "accid=$accid";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=accounts&cmd=SrBillEntrySkin&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=accounts&cmd=SrBillEntrySkin&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 
 } 



function SRBillFetch(){
$searchq 	= getRequest('searchq');
if($searchq){
 $sql=" SELECT
	accid,
	b.slman_id,
	name,
	issuevalues,
	total_commi,
	receive_amount,
	due_amount,
	receive_date
	FROM ".SR_BILL_TBL." b inner join ".INV_SALSEMAN_INFO_TBL." s on b.slman_id=s.slman_id 
	where opening_dues='0.00' receive_date LIKE '%".$searchq."%' or name LIKE '%".$searchq."%'  ORDER BY receive_date desc";
	}else{
 $sql=" SELECT
	accid,
	b.slman_id,
	name,
	issuevalues,
	total_commi,
	receive_amount,
	due_amount,
	receive_date
	FROM ".SR_BILL_TBL." b inner join ".INV_SALSEMAN_INFO_TBL." s on b.slman_id=s.slman_id 
	where opening_dues='0.00' ORDER BY receive_date desc";
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=1000 cellspacing=0 class="tableGrid" >
	            <tr>
				<th align=left colspan="2">Action</th>
	             <th nowrap=nowrap align=left width=100>Receive Date</th>
	             <th nowrap=nowrap align=left>SR Name</th>
	             <th nowrap=nowrap align=left>Sales Values</th>
	             <th nowrap=nowrap align=left>Commission</th>
				 <th nowrap=nowrap align=left>Current Received</th>
				 <th nowrap=nowrap align=left>Dues Received</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
					$totaldues = $row['totaldues'];
					$receive_amount = $row['receive_amount'];
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');
			
			if($createdby=='1401'){
				$delEdit='<td><a href="index.php?app=accounts&cmd=deleteSRbill&accid='.$row['accid'].'" onclick="return confirmDelete();" title="Delete">
		   	<img src="images/common/delete.gif" style="border:none"></a></td>
			<td><a onClick="javascript:ajaxCall4EditSRBill(\''.$row['accid'].'\',\''.
														  $row['slman_id'].'\',\''.
														  $row['receive_amount'].'\',\''.
														  $row['due_amount'].'\',\''.
						                                  dateInputFormatDMY($row['receive_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" type_name
			onMouseOut="this.className=\''.$style.'\'">'.$delEdit.'			
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['issuevalues'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['total_commi'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['receive_amount'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['due_amount'],2).'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

function SRCollectFetch(){
$searchq 	= getRequest('searchq');
$searchq 	= getRequest('searchq');
if($searchq){
 $sql=" SELECT
	accid,
	b.slman_id,
	name,
	issuevalues,
	total_commi,
	receive_amount,
	due_amount,
	receive_date
	FROM ".SR_BILL_TBL." b inner join ".INV_SALSEMAN_INFO_TBL." s on b.slman_id=s.slman_id 
	where opening_dues='0.00' receive_date LIKE '%".$searchq."%' or name LIKE '%".$searchq."%'  ORDER BY receive_date desc";
	}else{
 $sql=" SELECT
	accid,
	b.slman_id,
	name,
	issuevalues,
	total_commi,
	receive_amount,
	due_amount,
	receive_date
	FROM ".SR_BILL_TBL." b inner join ".INV_SALSEMAN_INFO_TBL." s on b.slman_id=s.slman_id 
	where opening_dues='0.00' ORDER BY receive_date desc";
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=1000 cellspacing=0 class="tableGrid" >
	            <tr>
				<th align=left colspan="2">Action</th>
	             <th nowrap=nowrap align=left width=100>Receive Date</th>
	             <th nowrap=nowrap align=left>SR Name</th>
	             <th nowrap=nowrap align=left>Sales Values</th>
	             <th nowrap=nowrap align=left>Commission</th>
				 <th nowrap=nowrap align=left>Current Received</th>
				 <th nowrap=nowrap align=left>Dues Received</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
					$totaldues = $row['totaldues'];
					$receive_amount = $row['receive_amount'];
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');
			
			if($createdby=='1401'){
				$delEdit='<td><a href="index.php?app=accounts&cmd=deleteSRbill&accid='.$row['accid'].'" onclick="return confirmDelete();" title="Delete">
		   	<img src="images/common/delete.gif" style="border:none"></a></td>
			<td><a onClick="javascript:ajaxCall4EditSRCollect(\''.$row['accid'].'\',\''.
														  $row['slman_id'].'\',\''.
														  $row['due_amount'].'\',\''.
						                                  dateInputFormatDMY($row['receive_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" type_name
			onMouseOut="this.className=\''.$style.'\'">'.$delEdit.'			
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['issuevalues'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['total_commi'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['receive_amount'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['due_amount'],2).'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc



	function EditCollection(){
	$accid = getRequest('accid');
	 $slman_id = getRequest('slman_id');
	 $due_amount = getRequest('due_amount');
	 $receive_date = formatDate4insert(getRequest('receive_date'));
	
	$sql = "UPDATE	".SR_BILL_TBL." SET slman_id =$slman_id, due_amount =$due_amount, receive_date = '$receive_date' WHERE accid = $accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $srBill = $this->SRCollectFetch();
		}
	}


function SelectSR(){ 
		$sql="SELECT slman_id, name FROM ".INV_SALSEMAN_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='slman_id' size='1' id='slman_id' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['slman_id']."'>".$row['name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }
   



function SelectBillType(){ 
		$sql="SELECT bid, type_name FROM ".INV_BILL_TYPE_TBL." ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='bid' size='1' id='bid' class=\"textBox\" style='width:180px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['bid']."'>".$row['type_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }
function SelectMonth(){ 
		$sql="SELECT month_id, month_name FROM ".BILL_MONTH_TBL." ";
	    $result = mysql_query($sql);
	    $Supplier_select = "<select name='month_id' size='1' id='month_id' class=\"textBox\" style='width:100px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {

			$Supplier_select .= "<option value='".$row['month_id']."'>".$row['month_name']."</option>";	
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }
   
   
   
} // End class

?>