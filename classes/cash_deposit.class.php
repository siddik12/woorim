<?php
class cash_deposit
{
   
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd) 

      {
		 
		 case 'SaveExpense'         			: $this->addExpence();   								break;
		// case 'Expence'          				: $screen = $this->Expence();   						break;
      	 case 'DeleteExpence'       			: $screen = $this->DeleteExpence();   					break;
		 case 'list'               				: $this->Expence();  									break;
         default                   				: $cmd == 'list'; $this->Expence();						break;
      }
 }
 


  function Expence(){
  $branch_id = getFromSession('branch_id');

   
					 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM ".DAILY_ACC_LEDGER_TBL." where branch_id=$branch_id and dr !='0.00000'";
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
			$pagination.= "<a href=\"?app=cash_deposit&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=cash_deposit&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=cash_deposit&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=cash_deposit&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=cash_deposit&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=cash_deposit&page=1\">1</a>";
				$pagination.= "<a href=\"?app=cash_deposit&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=cash_deposit&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=cash_deposit&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=cash_deposit&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=cash_deposit&page=1\">1</a>";
				$pagination.= "<a href=\"?app=cash_deposit&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=cash_deposit&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=cash_deposit&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 
	 
	 
	 $expence = $this->ExpenceFetch($start, $limit);
	 $SelectBankList = $this->SelectBankList();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
 

  function addExpence(){
 $branch_id = getFromSession('branch_id');
  $bankid = getRequest('bankid');
 $dr = getRequest('dr');
 $chart_id = getRequest('chart_id');
 $particulars = getRequest('particulars');
 $expdate = formatDate4insert(getRequest('expdate'));


  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = DAILY_ACC_LEDGER_TBL;
		 $reqData = getUserDataSet(DAILY_ACC_LEDGER_TBL);
		 $reqData['created_by'] = getFromSession('userid');
		 $reqData['expdate'] = $expdate;
		 $reqData['chart_id'] = $chart_id;
		 $reqData['branch_id'] = $branch_id;
		 $reqData['dr'] = $dr;
		 $reqData['bankid'] = $bankid;
		 $reqData['particulars'] = $particulars;
		 $reqData['paytype'] = 'Cash';
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
		 if($res['newid']){
			$dlygl_id=$res['newid'];
				 $sql="INSERT INTO bank_trans(bankid, transtyp, deposit, trans_date,chart_id, dlygl_id,branch_id)
				VALUES('$bankid', 'Deposit', '$dr', '$expdate', '$chart_id','$dlygl_id','$branch_id')"; //exit();
				mysql_query($sql);
				header("location:?app=cash_deposit&msg=Successfully saved");
			} 	         	   
			else
			{	
				header("location:?app=cash_deposit&msg=Not saved");
		
			}
	}


  
  
  function EditExpence(){
  	$dlygl_id = getRequest('dlygl_id');
	 	 $info = array();
		 $reqData = array();
		 $info['table'] = DAILY_ACC_LEDGER_TBL;
		 $reqData = getUserDataSet(DAILY_ACC_LEDGER_TBL);
		 $reqData['expdate'] = formatDate4insert(getRequest('expdate'));
		 $info['data'] = $reqData; 
      	 $info['where']= "dlygl_id=$dlygl_id";
		// $info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteExpence()
   {$id = getRequest('dlygl_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = DAILY_ACC_LEDGER_TBL;
      	$info['where'] = "dlygl_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res){   
			$sql="DELETE FROM bank_trans WHERE dlygl_id='$id'";
			mysql_query($sql);
						 
      	   header("location:?app=cash_deposit");      	         	   
      	}      	
      	else
      	{ 
      		 header("location:?app=cash_deposit");      	   	
      	}      	
      }	
   }
   
   
   function ExpenceFetch($start= null, $limit= null){
   $branch_id = getFromSession('branch_id');
	 $searchq =$_GET['searchq'];
	 $Page 	= getRequest('page');
		if($Page){
 	            $sql="SELECT dlygl_id,detail_accid,g.chart_id,a.account_name,expdate,particulars, dr,company_name,bank_name,account_no 
				FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a on a.chart_id = g.chart_id 
				left outer join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=g.supplier_id
				left outer join bank_info b on b.bankid=g.bankid
				where dr !='0.00000' and g.branch_id=$branch_id and g.chart_id='12' order by expdate desc LIMIT $start, $limit";
	}
	else{
	     
		 $sql="SELECT dlygl_id,detail_accid,g.chart_id,a.account_name,expdate,particulars, dr,company_name,bank_name,account_no 
				FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a on a.chart_id = g.chart_id 
				left outer join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=g.supplier_id
				left outer join bank_info b on b.bankid=g.bankid
		where dr !='0.00000' and g.branch_id=$branch_id and g.chart_id='12' order by expdate desc LIMIT 0, 29";
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass">
					<tr>
					<th align="left" nowrap="nowrap">Date</th>
					<th align="left" nowrap="nowrap">Expence Account</th>
					<th align="left" nowrap="nowrap">Particulars</th>
					<th align="left" nowrap="nowrap">Dr.</th>
<!--					<th align="left">Edit</th>
-->					<th align="left">Delete</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	$detail_accid=$row['detail_accid'];
	
	if($detail_accid==''){
	$link='<a href="javascript:updateExpence(\''.$row['dlygl_id'].'\',
					\''.$row['chart_id'].'\',
					\''.$row['particulars'].'\',
					\''.$row['dr'].'\',
					\''.dateInputFormatDMY($row['expdate']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a>';
					
	$link2='<a href="?app=cash_deposit&cmd=DeleteExpence&dlygl_id='.$row['dlygl_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a>';
	}else{
	$link='';
	$link2='';
	}
	

               if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particulars'].' '
					.$row['company_name'].' '.$row['bank_name'].' '.$row['account_no'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
<!--					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">'.$link.'</td>
-->					<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">'.$link2.'</td>	  	
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particulars'].' '
					.$row['company_name'].' '.$row['bank_name'].' '.$row['account_no'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
<!--					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">'.$link.'</td>
-->					<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">'.$link2.'</td>	  	
					</tr>';
				
	
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
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