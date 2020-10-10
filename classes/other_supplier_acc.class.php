<?php
class other_supplier_acc
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
	 $query = "SELECT COUNT(*) as num FROM others_supplier_acc where paid_amount!='0.00' ";
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
			$pagination.= "<a href=\"?app=other_supplier_acc&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=other_supplier_acc&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=other_supplier_acc&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=other_supplier_acc&page=1\">1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=other_supplier_acc&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=other_supplier_acc&page=1\">1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=other_supplier_acc&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=other_supplier_acc&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $DataFetch = $this->DataFetch($start, $limit);
 $SuplierList=$this->SelectSupplier();
 //$SelectBankList = $this->SelectBankList();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
function SavePayment(){
 $particulars = getRequest('particulars');
 $pay_mode = getRequest('pay_mode');
 $paid_amount = getRequest('paid_amount');
 $supplier_id = getRequest('supplier_id');
 $transaction_date = formatDate4insert(getRequest('transaction_date'));
$createdby = getFromSession('username');
 
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = OTHERS_SUPPLIER_ACC_TBL;
		 $reqData = getUserDataSet(OTHERS_SUPPLIER_ACC_TBL);
		 $reqData['creadet_by']=$createdby;
		 $reqData['transaction_date']=$transaction_date;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res){
				header("location:?app=other_supplier_acc&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=other_supplier_acc&msg=Not Saved");
		
			} 

}// EOF =====================


 function Delete(){
	$other_acc_id = getRequest('other_acc_id');
      if($other_acc_id)
      {
      	            	
      	$info = array();
      	$info['table'] = OTHERS_SUPPLIER_ACC_TBL;
      	$info['where'] = "other_acc_id='$other_acc_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res){  
      	   header("location:?app=other_supplier_acc&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=other_supplier_acc&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 }
  
  
  
 function DataFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	$sql='SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where paid_amount!="0.00" and company_name LIKE "%'.$searchq.'%" ORDER BY transaction_date desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where paid_amount!='0.00' ORDER BY transaction_date desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
 $sql="SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where paid_amount!='0.00' ORDER BY transaction_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Paid Date</th>
             	 <th nowrap=nowrap align=left>Particular</th>
				 <th nowrap=nowrap align=left>Cr. Amount</th>
				 <th nowrap=nowrap align=left>Supplier</th>
				 <th nowrap=nowrap align=left>Pay Mode</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			<td><a href="?app=other_supplier_acc&cmd=Delete&other_acc_id='.$row['other_acc_id'].'" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'._date($row['transaction_date']).'&nbsp;</td>
			<td>'.$row['particulars'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['paid_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['company_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['pay_mode'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc



function SelectSupplier($sup = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT supplier_id, company_name FROM ".INV_SUPPLIER_INFO_TBL." where branch_id=$branch_id ORDER BY company_name ";
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


/*function SelectBankList($bankid=null){ 
		$sql="SELECT bankid, bank_name, account_no FROM ".BANK_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $department_select = "<select name='bankid' size='1' id='bankid' style='width:200px' class=\"textBox\">";
		$department_select .= "<option value=''>Select Bank</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['bankid'] == $bankid){
			$department_select .= "<option value='".$row['bankid']."' selected = 'selected'>".$row['bank_name']."&nbsp;:&nbsp;".$row['account_no']."</option>";
			}
			else{
			$department_select .= "<option value='".$row['bankid']."'>".$row['bank_name']."&nbsp;:&nbsp;".$row['account_no']."</option>";	
			}
		}
		$department_select .= "</select>";
		return $department_select;
}	
*/


function SaveDebitSkin(){

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM others_supplier_acc where receive_amount!='0.00' ";
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
			$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=other_supplier_acc&cmd=SaveDebitSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $DataFetchDebit = $this->DataFetchDebit($start, $limit);
 $SuplierList=$this->SelectSupplier();
 //$SelectBankList = $this->SelectBankList();
	 require_once(OTHER_SUPPLIER_ACC_DEBIT_SKIN); 
  }
  
function SaveDebit(){
 $particulars = getRequest('particulars');
 $pay_mode = getRequest('pay_mode');
 $receive_amount = getRequest('receive_amount');
 $supplier_id = getRequest('supplier_id');
 $transaction_date = formatDate4insert(getRequest('transaction_date'));
$createdby = getFromSession('username');
 
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = OTHERS_SUPPLIER_ACC_TBL;
		 $reqData = getUserDataSet(OTHERS_SUPPLIER_ACC_TBL);
		 $reqData['creadet_by']=$createdby;
		 $reqData['transaction_date']=$transaction_date;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res){
				header("location:?app=other_supplier_acc&cmd=SaveDebitSkin&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=other_supplier_acc&cmd=SaveDebitSkin&msg=Not Saved");
		
			} 

}// EOF =====================


 function DeleteDebit(){
	$other_acc_id = getRequest('other_acc_id');
      if($other_acc_id)
      {
      	            	
      	$info = array();
      	$info['table'] = OTHERS_SUPPLIER_ACC_TBL;
      	$info['where'] = "other_acc_id='$other_acc_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res){  
      	   header("location:?app=other_supplier_acc&cmd=SaveDebitSkin&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=other_supplier_acc&cmd=SaveDebitSkin&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 }
  
  
  
 function DataFetchDebit($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	$sql='SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where receive_amount!="0.00" and company_name LIKE "%'.$searchq.'%" ORDER BY transaction_date desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where receive_amount!='0.00' ORDER BY transaction_date desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
 $sql="SELECT
	other_acc_id,
	company_name,
	particulars,
	receive_amount,
	paid_amount,
	transaction_date,
	pay_mode
FROM
	others_supplier_acc a inner join inv_supplier_info s on a.supplier_id=s.supplier_id
	where receive_amount!='0.00' ORDER BY transaction_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Paid Date</th>
             	 <th nowrap=nowrap align=left>Particular</th>
				 <th nowrap=nowrap align=left>Dr. Amount</th>
				 <th nowrap=nowrap align=left>Supplier</th>
				 <th nowrap=nowrap align=left>Pay Mode</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }

			$createdby = getFromSession('userid');

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			<td><a href="?app=other_supplier_acc&cmd=SaveDebitSkin&cmd=DeleteDebit&other_acc_id='.$row['other_acc_id'].'" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'._date($row['transaction_date']).'&nbsp;</td>
			<td>'.$row['particulars'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['receive_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['company_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['pay_mode'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc

   
} // End class

?>