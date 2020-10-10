<?php
class dsr_collection
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'SrBillEntrySkin'         : echo $this->SRbillEntrySkin();							break;
		 case 'ajaxInsertBill'         	: echo $this->InsertBill();									break;
		 case 'ajaxInserSRBill'        	: echo $this->InsertSRBill();								break;
		 
		 case 'OpeningDuesEntrySkin'    : echo $this->OpeningDuesEntrySkin();						break;
		 case 'ajaxInserOpenDues'     	: echo $this->InsertOpenDues();								break;
		 case 'ajaxEditOpenDues'    	: echo $this->EditOpenDues();								break;
		 case 'deleteOpenDues'          : echo $this->deleteOpenDues();   							break;
		 case 'ajaxSearchOpDues'        : echo $this->DSROpenDuesFetch();   						break;

		 case 'ajaxInserCollection'     : echo $this->InsertDSRCollection();						break;
		 case 'ajaxEditCollection'    	: echo $this->EditDSRCollection();							break;
		 case 'deleteDSRcoll'           : echo $this->deleteDSRcoll();   							break;
		 case 'ajaxSearchColl'          : echo $this->DSRcollectionFetch();   						break;
         case 'list'                  	: $this->getList();                       					break;
         default                      	: $cmd == 'list'; $this->getList();	       					break;
      }
 }
function getList(){
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM inv_sr_account where opening_dues='0.00' ";
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
			$pagination.= "<a href=\"?app=dsr_collection&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=dsr_collection&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=dsr_collection&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=dsr_collection&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=dsr_collection&page=1\">1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=dsr_collection&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=dsr_collection&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=dsr_collection&page=1\">1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=dsr_collection&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=dsr_collection&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 


$SR = $this->SelectSR();
$collection = $this->DSRcollectionFetch($start, $limit);
 require_once(CURRENT_APP_SKIN_FILE); 
  }

function InsertDSRCollection(){
 $slman_id = getRequest('slman_id');
 $credit_colect = getRequest('credit_colect');
 $credit = getRequest('credit');
 $others = getRequest('others');
 $prp = getRequest('prp');
 $receive_date = formatDate4insert(getRequest('receive_date'));
	
	$sql = "INSERT INTO ".SR_BILL_TBL."(slman_id, credit_colect, credit,  others, prp, receive_date) 
								 values($slman_id,  $credit_colect, $credit, $others, $prp, '$receive_date')";
		   $res = mysql_query($sql);
		   
				if($res){// echo 'Saved';
					echo $collection = $this->DSRcollectionFetch($start, $limit);
				}// end if($res)
		

}

function DSRcollectionFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
 $sql="SELECT
	accid,
	a.slman_id,
	opening_dues,
	credit_colect,
	credit,
	others,
	prp,
	receive_date,
	name
	
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id
	where opening_dues='0.00' and receive_date LIKE '%".$searchq."%' or m.name LIKE '%".$searchq."%' LIMIT 0, 29";
}if($Page){
 $sql="SELECT
	accid,
	a.slman_id,
	credit_colect,
	credit,
	others,
	prp,
	receive_date,
	name
	
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id where opening_dues='0.00'
	order by receive_date DESC LIMIT $start, $limit";
}if(!$Page && !$searchq ){
 $sql="SELECT
	accid,
	a.slman_id,
	credit_colect,
	credit,
	others,
	prp,
	receive_date,
	name
	
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id where opening_dues='0.00'
	order by receive_date DESC LIMIT 0, 29 ";
}

		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th align=left width=70>Delete</th>
				<th align=left width=70>Edit</th>
	             <th nowrap=nowrap align=left width=100>Receive Date</th>
	             <th nowrap=nowrap align=left>SR Name</th>
	             <th nowrap=nowrap align=left>Credit Collection</th>
	             <th nowrap=nowrap align=left>Credit</th>
				 <th nowrap=nowrap align=left>Others</th>
				 <th nowrap=nowrap align=left>VAN/PRP</th>
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
				$delEdit='<td><span class="label label-danger"><a href="?app=dsr_collection&cmd=deleteDSRcoll&accid='.$row['accid'].'" onclick="return confirmDelete();" title="Delete" style="text-decoration:none;color:#FFFFFF">Delete</a></span></td>
			<td><a onClick="javascript:ajaxCall4EditDSRColl(\''.$row['accid'].'\',\''.
														  $row['slman_id'].'\',\''.
														  $row['credit_colect'].'\',\''.
														  $row['credit'].'\',\''.
														  $row['others'].'\',\''.
														  $row['prp'].'\',\''.
						                                  dateInputFormatDMY($row['receive_date']).'\');" title="Edit">
			<span class="label label-success">Edit</span></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" type_name
			onMouseOut="this.className=\''.$style.'\'" height="30">'.$delEdit.'
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['credit_colect'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['credit'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['others'],2).'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['prp'],2).'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

	function EditDSRCollection(){
	$accid = getRequest('accid');
	 $slman_id = getRequest('slman_id');
	 $credit_colect = getRequest('credit_colect');
	 $credit = getRequest('credit');
	 $others = getRequest('others');
	 $prp = getRequest('prp');
	 $receive_date = formatDate4insert(getRequest('receive_date'));
	  $createdby = getFromSession('userid');
	 
	
	$sql = "UPDATE	inv_sr_account SET slman_id =$slman_id, credit_colect =$credit_colect,	credit =$credit,	
			others = $others,	receive_date = '$receive_date', prp = '$prp', createdby='$createdby' WHERE accid = $accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $collection = $this->DSRcollectionFetch($start, $limit);
		}
}//EOF

function deleteDSRcoll(){
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
      	   header("location:index.php?app=dsr_collection&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:index.php?app=dsr_collection&Dmsg=Not Deleted");     	   	
      	}      	
      }	
 
 } 

function OpeningDuesEntrySkin(){

					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM inv_sr_account where opening_dues!='0.00' ";
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
			$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=dsr_collection&cmd=OpeningDuesEntrySkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

$SR = $this->SelectSR();
$OpeningDues = $this->DSROpenDuesFetch($start, $limit);
 require_once(DSR_OPENING_DUES); 
}

function InsertOpenDues(){
 $slman_id = getRequest('slman_id');
 $opening_dues = getRequest('opening_dues');
 $receive_date = formatDate4insert(getRequest('receive_date'));
	
	$sql = "INSERT INTO ".SR_BILL_TBL."(slman_id, opening_dues, receive_date) 
								 values($slman_id,  $opening_dues, '$receive_date')";
		   $res = mysql_query($sql);
		   
				if($res){// echo 'Saved';
					echo $OpeningDues = $this->DSROpenDuesFetch();
				}// end if($res)
		

}

function DSROpenDuesFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
 $sql="SELECT
	accid,
	a.slman_id,
	opening_dues,
	receive_date,
	name
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id
	where opening_dues!='0.00' and receive_date LIKE '%".$searchq."%' or m.name LIKE '%".$searchq."%' LIMIT 0, 29";
}if($Page){
 $sql="SELECT
	accid,
	a.slman_id,
	opening_dues,
	receive_date,
	name
	
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id where opening_dues!='0.00'
	order by receive_date DESC LIMIT $start, $limit";
}if(!$Page && !$searchq ){
$sql="SELECT
	accid,
	a.slman_id,
	opening_dues,
	receive_date,
	name
	
FROM
	inv_sr_account a inner join sales_maninfo m on m.slman_id=a.slman_id where opening_dues!='0.00'
	order by receive_date DESC LIMIT 0, 29 ";
}

		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=600 cellspacing=0 class="tableGrid" >
	            <tr>
				<th align=left width=70>Delete</th>
				<th align=left width=70>Edit</th>
	             <th nowrap=nowrap align=left width=100>Receive Date</th>
	             <th nowrap=nowrap align=left>SR Name</th>
	             <th nowrap=nowrap align=left>Opening Dues</th>
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
				$delEdit='<td><span class="label label-danger"><a href="?app=dsr_collection&cmd=deleteOpenDues&accid='.$row['accid'].'" onclick="return confirmDelete();" title="Delete" style="text-decoration:none;color:#FFFFFF">Delete</a></span></td>
			<td><a onClick="javascript:ajaxCall4EditDSROpDue(\''.$row['accid'].'\',\''.
														  $row['slman_id'].'\',\''.
														  $row['opening_dues'].'\',\''.
						                                  dateInputFormatDMY($row['receive_date']).'\');" title="Edit">
			<span class="label label-success">Edit</span></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" type_name
			onMouseOut="this.className=\''.$style.'\'" height="30">'.$delEdit.'			
			<td nowrap=nowrap>'._date($row['receive_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
			<td nowrap=nowrap>'.number_format($row['opening_dues'],2).'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

	function EditOpenDues(){
	$accid = getRequest('accid');
	 $slman_id = getRequest('slman_id');
	 $opening_dues = getRequest('opening_dues');
	 $receive_date = formatDate4insert(getRequest('receive_date'));
	  $update_by = getFromSession('userid');
	 
	
	$sql = "UPDATE	inv_sr_account SET slman_id =$slman_id, opening_dues=$opening_dues,	receive_date='$receive_date', update_by='$update_by' WHERE accid = $accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $OpeningDues = $this->DSROpenDuesFetch();
		}
}//EOF

function deleteOpenDues(){
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
      	   header("location:?app=dsr_collection&cmd=OpeningDuesEntrySkin&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=dsr_collection&cmd=OpeningDuesEntrySkin&Dmsg=Not Deleted");     	   	
      	}      	
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
  
  
     
   
} // End class

?>