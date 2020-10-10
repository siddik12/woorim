<?php
class bkash_trans
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'SaveDebitSkin'    : echo $this->SaveDebitSkin();				break;
		 case 'SaveDebit'      	 : echo $this->SaveDebit();				break;
		 case 'DeleteDebit'       	 : echo $this->DeleteDebit();   			break;
		 case 'SavePayment'      : echo $this->SavePayment();				break;
		 case 'Delete'       	 : echo $this->Delete();   			break;
		 case 'ajaxSearchData'   : echo $this->DataFetch();   				break;
		 case 'ajaxSearchDataDebit'   : echo $this->DataFetchDebit();   				break;
         case 'list'             : $this->getList();                       	break;
         default                 : $cmd == 'list'; $this->getList();	    break;
      }
 }
 
 
function getList(){

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM bkash_transaction ";
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
			$pagination.= "<a href=\"?app=bkash_trans&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=bkash_trans&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=bkash_trans&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=bkash_trans&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=bkash_trans&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=bkash_trans&page=1\">1</a>";
				$pagination.= "<a href=\"?app=bkash_trans&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=bkash_trans&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=bkash_trans&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=bkash_trans&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=bkash_trans&page=1\">1</a>";
				$pagination.= "<a href=\"?app=bkash_trans&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=bkash_trans&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=bkash_trans&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $DataFetch = $this->DataFetch($start, $limit);
 //$SuplierList=$this->SelectSupplier();
 //$SelectBankList = $this->SelectBankList();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
function SavePayment(){

$trans_date = formatDate4insert(getRequest('trans_date'));
$trans_type = getRequest('trans_type');
$trans_amount = getRequest('trans_amount');
$createdby = getFromSession('username');

  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = BKASH_TRANS_TBL;
		 $reqData = getUserDataSet(BKASH_TRANS_TBL);
		 $reqData['creadet_by']=$createdby;
		 $reqData['trans_date']=$trans_date;
		
		 if($trans_type=='1'){
		 $reqData['cash_in']=$trans_amount;
		 }
		 if($trans_type=='2'){
		 $reqData['cash_out']=$trans_amount;
		 }
		 
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res){
				header("location:?app=bkash_trans&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=bkash_trans&msg=Not Saved");
		
			} 

}// EOF =====================


 function Delete(){
	$bkash_id = getRequest('bkash_id');
      if($bkash_id)
      {
      	            	
      	$info = array();
      	$info['table'] = BKASH_TRANS_TBL;
      	$info['where'] = "bkash_id='$bkash_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res){  
      	   header("location:?app=bkash_trans&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=bkash_trans&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 }
  
  
  
 function DataFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	$sql='SELECT
	bkash_id,
	bkash_acc,
	cash_in,
	cash_out,
	particulars,
	trans_date,
	trans_type,
created_by
FROM
	bkash_transaction
	where bkash_acc LIKE "%'.$searchq.'%" ORDER BY trans_date desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	bkash_id,
	bkash_acc,
	cash_in,
	cash_out,
	particulars,
	trans_date,
	trans_type,
created_by
FROM
	bkash_transaction
	ORDER BY trans_date desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
 $sql="SELECT
	bkash_id,
	bkash_acc,
	cash_in,
	cash_out,
	particulars,
	trans_date,
	trans_type,
created_by
FROM
	bkash_transaction
	ORDER BY trans_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Date</th>
	             <th nowrap=nowrap align=left>bKash Acc</th>
             	 <th nowrap=nowrap align=left>Particular</th>
				 <th nowrap=nowrap align=left>Cash In</th>
				 <th nowrap=nowrap align=left>Cash Out</th>
<!--				 <th nowrap=nowrap align=left>Transaction Type</th>
-->	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		
		$trans_type=$row['trans_type'];
		
		if($trans_type=='1'){
		$transType='Cash In';
		}else{
		$transType='Cash Out';
		}
		
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			<td><a href="?app=bkash_trans&cmd=Delete&bkash_id='.$row['bkash_id'].'" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'._date($row['trans_date']).'&nbsp;</td>
			<td>'.$row['bkash_acc'].'&nbsp;</td>
			<td>'.$row['particulars'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cash_in'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cash_out'].'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$transType.'&nbsp;</td>
-->			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc
   
} // End class

?>