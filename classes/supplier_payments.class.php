<?php
class supplier_payments
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'SavePayment'      : echo $this->SavePayment();				break;
		 case 'Delete'       	 : echo $this->Delete();   			break;
		 case 'ajaxSearchData'   : echo $this->DataFetch();   				break;
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
	 $query = "SELECT COUNT(*) as num FROM detail_account where cr!='0.00000' ";
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
			$pagination.= "<a href=\"?app=supplier_payments&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=supplier_payments&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=supplier_payments&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=supplier_payments&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=supplier_payments&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=supplier_payments&page=1\">1</a>";
				$pagination.= "<a href=\"?app=supplier_payments&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=supplier_payments&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=supplier_payments&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=supplier_payments&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=supplier_payments&page=1\">1</a>";
				$pagination.= "<a href=\"?app=supplier_payments&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=supplier_payments&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=supplier_payments&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $DataFetch = $this->DataFetch($start, $limit);
 $SuplierList=$this->SelectSupplier();
 $SelectBankList = $this->SelectBankList();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
function SavePayment(){
 $bankid = getRequest('bankid');
 $pay_mode = getRequest('pay_mode');
 $cr = getRequest('cr');
 $supplier_id = getRequest('supplier_id');
 $receive_date = formatDate4insert(getRequest('receive_date'));
$createdby = getFromSession('username');
 
 if($pay_mode=='Bank'){
  $branch_id=getFromSession('branch_id');
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = ACC_DETAILS_TBL;
		 $reqData = getUserDataSet(ACC_DETAILS_TBL);
		 $reqData['supplier_id']=$supplier_id;
		 $reqData['cr']=$cr;
		 $reqData['createdby']=$createdby;
		 $reqData['branch_id']=$branch_id;
		 $reqData['bankid']=$bankid;
		 $reqData['particular']='Paid by';
		 $reqData['paytype']='Bank';
		 $reqData['recdate']=$receive_date;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res['newid']){
			$detail_accid=$res['newid'];
				 $sql="INSERT INTO bank_trans(bankid, transtyp, withdrawal, trans_date, supplier_id,detail_accid,branch_id)
				VALUES('$bankid', 'Withdraw', '$cr', '$receive_date', '$supplier_id','$detail_accid','$branch_id')";
				mysql_query($sql);
				header("location:?app=supplier_payments&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=supplier_payments&msg=Not Saved");
		
			} 
 
 }

 if($pay_mode=='Cash'){
 $branch_id=getFromSession('branch_id');
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = ACC_DETAILS_TBL;
		 $reqData = getUserDataSet(ACC_DETAILS_TBL);
		 $reqData['supplier_id']=$supplier_id;
		 $reqData['cr']=$cr;
		 $reqData['branch_id']=$branch_id;
		 $reqData['createdby']=$createdby;
		 //$reqData['particular']='Paid by Cash';
		 $reqData['recdate']=$receive_date;
		 $reqData['paytype']='Cash';
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res['newid']){
			$detail_accid=$res['newid'];
				 $sql="INSERT INTO daily_acc_ledger(detail_accid, chart_id, dr, expdate, supplier_id,branch_id,particulars,paytype)
				VALUES('$detail_accid', '7', '$cr', '$receive_date', '$supplier_id','$branch_id','Payment by Cash','Cash')";
				mysql_query($sql);
				header("location:?app=supplier_payments&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=supplier_payments&msg=Not Saved");
		
			} 
 
 }

}// EOF =====================


 function Delete(){
	$detail_accid = getRequest('detail_accid');
      if($detail_accid)
      {
      	            	
      	$info = array();
      	$info['table'] = ACC_DETAILS_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res){  
		$this->DeleteFromExpence($detail_accid); 
		$this->DeleteFromBank($detail_accid); 
			 	   
      	   header("location:?app=supplier_payments&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=supplier_payments&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 }
  
  
  function DeleteFromExpence($detail_accid){
      	$info = array();
      	$info['table'] = DAILY_ACC_LEDGER_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
		return $res;
 }

  function DeleteFromBank($detail_accid){
      	$info = array();
      	$info['table'] = BANK_TRANS_TBL;
      	$info['where'] = "detail_accid='$detail_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
		return $res;
 }
 
  
  
 function DataFetch($start=null, $limit=null){
 $branch_id = getFromSession('branch_id');
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	$sql='SELECT
	detail_accid,
	challan_no,
	particular,
	cr,
	recdate,
	company_name,
	bank_name,
	account_no
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id
	left outer join bank_info b on b.bankid=a.bankid
	where cr!="0.00000" and company_name LIKE "%'.$searchq.'%" ORDER BY recdate desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	detail_accid,
	challan_no,
	particular,
	cr,
	recdate,
	company_name,
	bank_name,
	account_no
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id
	left outer join bank_info b on b.bankid=a.bankid
	where cr!='0.00000' ORDER BY recdate desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
 $sql="SELECT
	detail_accid,
	challan_no,
	particular,
	cr,
	recdate,
	company_name,
	bank_name,
	account_no
FROM
	detail_account a inner join inv_supplier_info s on s.supplier_id=a.supplier_id
	left outer join bank_info b on b.bankid=a.bankid
	where cr!='0.00000' ORDER BY recdate desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Paid Date</th>
<!--	             <th nowrap=nowrap align=left>Challan no</th>
-->	             <th nowrap=nowrap align=left>Particular</th>
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

			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			<td><a href="?app=supplier_payments&cmd=Delete&detail_accid='.$row['detail_accid'].'" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'._date($row['recdate']).'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['challan_no'].'&nbsp;</td>
-->			<td>'.$row['particular'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cr'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['company_name'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc



function SelectSupplier($sup = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT supplier_id, company_name FROM ".INV_SUPPLIER_INFO_TBL." ORDER BY company_name ";
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


function SelectBankList($bankid=null){ 
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

   
} // End class

?>