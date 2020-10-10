<?php
class finance
{
   
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd) 

      {
      	 case 'Reports'          				: $screen = $this->ReportsSkin();   					break;
      	 case 'SalaryMonthWiseReportSkin'       : $screen = $this->SalaryMonthWiseReportSkin();   		break;
      	 case 'SalarySummaryReportSkin'         : $screen = $this->SalarySummaryReportSkin();   		break;
		 case 'IncAllReportSkin'       			: $screen = $this->IncomeAllReportSkin();   			break;
      	 case 'IncGroupReportSkin'       		: $screen = $this->IncomeGroupReportSkin();   			break;
      	 case 'BankDepoReportSkin'       		: $screen = $this->BankDepoReportSkin();   				break;
      	 case 'BankWithdrawReportSkin'       	: $screen = $this->BankWithdrawReportSkin();   			break;
      	 case 'BankBlncShtSkin'       			: $screen = $this->BankBlncShtSkin();   				break;
      	 case 'GLReportSkin'        			: $screen = $this->GLReportSkin();   					break;
		 
		 case 'DailyCahReportSkin'        		: $screen = $this->DailyCahReportSkin();   				break;

      	 case 'ExpAllReportSkin'       			: $screen = $this->ExpenceAllReportSkin();   			break;
		 case 'ExpGroupReportSkin'       		: $screen = $this->ExpenceGroupReportSkin();   			break;

      	 case 'FixedAssetAllReportSkin'       			: $screen = $this->FixedAssetAllReportSkin();   			break;
		 case 'FixedAssetGroupReportSkin'       		: $screen = $this->FixedAssetGroupReportSkin();   			break;
		 
		 case 'Income'          				: $screen = $this->Income();   							break;
      	 case 'DeleteIncome'        			: $screen = $this->DeleteIncome();   					break;
      	
		 case 'IncomeCht'          				: $screen = $this->getIncomeCht();   					break;
      	 case 'delete'             				: $screen = $this->DeleteCht();   						break;
      	 case 'deleteIncCht'       				: $screen = $this->DeleteIncomeCht();   				break;
		 
		 case 'SaveExpense'         			: $this->addExpence();   								break;
		 case 'Expence'          				: $screen = $this->Expence();   						break;
      	 case 'DeleteExpence'       			: $screen = $this->DeleteExpence();   					break;
         
		 case 'list'               				: $this->getList();  									break;
         default                   				: $cmd == 'list'; $this->getList();						break;
      }
 }
 
  function getList(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveExpCht();
	 }
	 $ExpCht = $this->ExpChtFetch();
	 require_once(EXPENCE_CHART_SKIN); 
  }
 
  function SaveExpCht(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 		$res = $this->addExpCht();
			header("location:?app=finance");
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditExpCht();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addExpCht(){
  if(getRequest('account_name') !=''){
  	 	 $info = array();
		 $info['table'] = ACCOUNTS_CHART_TBL;
		 $info['data'] = getUserDataSet(ACCOUNTS_CHART_TBL);
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
		 }
  }
  function EditExpCht(){
  	$chart_id = getRequest('chart_id');
	 	 $info = array();
		 $info['table'] = ACCOUNTS_CHART_TBL;
		 $info['data'] = getUserDataSet(ACCOUNTS_CHART_TBL);
      	 $info['where']= "chart_id='$chart_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteCht()
   {$id = getRequest('chart_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = ACCOUNTS_CHART_TBL;
      	$info['where'] = "chart_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res){   
			 echo 'deleted..';  	   
      	   header("location:?app=finance");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:?app=finance");      	   	
      	}      	
      }	
   }
   
   
   function ExpChtFetch(){
/*	 $searchq =$_GET['searchq'];
		if(searchq){
 	            $sql='SELECT chart_id,account_name FROM '.ACCOUNTS_CHART_TBL.' WHERE type ='"expence"' and account_name LIKE "%'.$searchq.'%" ';
	}
	else{*/
	     
		 $sql="SELECT chart_id,account_name FROM ".ACCOUNTS_CHART_TBL." where type ='expence'";
		// }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass"  >
					<tr>
					<th align="left" nowrap="nowrap">Account Name</th>
					<th align="left">Delete</th>
					<th align="left">Edit</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){

			$createdby = getFromSession('userid');
			
/*			if($createdby=='1501'){
				$delEdit='<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteCht(\''.$row['chart_id'].'\');" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateCht(\''.$row['chart_id'].'\',\''.$row['account_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}*/

                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<!--<a href="javascript:deleteCht(\''.$row['chart_id'].'\');" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a>--></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateCht(\''.$row['chart_id'].'\',\''.$row['account_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<!--<a href="javascript:deleteCht(\''.$row['chart_id'].'\');" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a>--></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateCht(\''.$row['chart_id'].'\',\''.$row['account_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>
					</tr>';
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
         }

  
/************************************************** Function for  ioncome chart ************************************
********************************************************************************************************************/	
  function getIncomeCht(){
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveIncomeCht();
	 }
	 $IncomeCht = $this->IncomeChtFetch();
	 require_once(INCOME_CHART_SKIN); 
  }
 
  function SaveIncomeCht(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addIncomeCht();
		header("location:?app=finance&cmd=IncomeCht");
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditIncomeCht();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
  function addIncomeCht(){
  	 	 $info = array();
		 $info['table'] = ACCOUNTS_CHART_TBL;
		 $info['data'] = getUserDataSet(ACCOUNTS_CHART_TBL);
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
  function EditIncomeCht(){
  	$chart_id = getRequest('chart_id');
	 	 $info = array();
		 $info['table'] = ACCOUNTS_CHART_TBL;
		 $info['data'] = getUserDataSet(ACCOUNTS_CHART_TBL);
      	 $info['where']= "chart_id='$chart_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteIncomeCht()
   {$id = getRequest('chart_id');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = ACCOUNTS_CHART_TBL;
      	$info['where'] = "chart_id='$id'";
      	$info['debug'] = false;      	
      	$res = delete($info);
      	dBug($res);
      	if($res){   
			 echo 'deleted..';  	   
      	   header("location:?app=finance&cmd=IncomeCht");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:?app=finance&cmd=IncomeCht");      	   	
      	}      	
      }	
   }
   
   
   function IncomeChtFetch(){
/*	 $searchq =$_GET['searchq'];
		if(searchq){
 	            $sql='SELECT chart_id,account_name FROM '.ACCOUNTS_CHART_TBL.' WHERE type ='"expence"' and account_name LIKE "%'.$searchq.'%" ';
	}
	else{*/
	     
		 $sql="SELECT chart_id,account_name FROM ".ACCOUNTS_CHART_TBL." where type ='income'";
		// }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass"  >
					<tr>
					<th align="left" nowrap="nowrap">Account Name</th>
					<th align="left">Delete</th>
					<th align="left">Edit</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){

			$createdby = getFromSession('userid');
			
			if($createdby=='1401'){
				$delEdit='<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:deleteCht(\''.$row['chart_id'].'\');" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateCht(\''.$row['chart_id'].'\',\''.$row['account_name'].'\');" title="Edit">
					<img src="images/common/edit.gif" style="border:none" ></a></td>';
			}else{

				$delEdit='<td>&nbsp;</td>
			<td>&nbsp;</td>';
			}

                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
		   			'.$delEdit.'
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
		   			'.$delEdit.'
					</tr>';
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
         }



/************************************************** Function for  Income Save ************************************
********************************************************************************************************************/	
  function Income(){
  $branch_id = getFromSession('branch_id');
	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveIncome();
	 }

   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM ".DAILY_ACC_LEDGER_TBL." where cr !='0.00000' and branch_id=$branch_id";
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
			$pagination.= "<a href=\"?app=finance&cmd=Income&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=finance&cmd=Income&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=finance&cmd=Income&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=1\">1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=finance&cmd=Income&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=1\">1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Income&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=finance&cmd=Income&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=finance&cmd=Income&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 
	 
	 
	 $income = $this->IncomeFetch($start, $limit);
	 $Inco = $this->SelectIncCht();
	 require_once(INCOME_SAVE_SKIN); 
  }
 
  function SaveIncome(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addIncome();
		  
		 if($res['affected_rows']){
				//$msg = 'Successfully saved !!!';
				header("location:?app=finance&cmd=Income&msg=Successfully saved");
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditIncome();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  

  function addIncome(){
 
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = DAILY_ACC_LEDGER_TBL;
		 $reqData = getUserDataSet(DAILY_ACC_LEDGER_TBL);
		 $reqData['branch_id'] =getFromSession('branch_id');
		 $reqData['created_by'] = getFromSession('userid');
		 $reqData['expdate'] = formatDate4insert(getRequest('expdate'));
		 $info['data'] = $reqData; 
		 //$info['debug']  = true;
		 $res = insert($info);
		 return $res;
  }
  function EditIncome(){
  	$dlygl_id = getRequest('dlygl_id');
	 	 $info = array();
		 $reqData = array();
		 $info['table'] = DAILY_ACC_LEDGER_TBL;
		 $reqData = getUserDataSet(DAILY_ACC_LEDGER_TBL);
		 $reqData['expdate'] = formatDate4insert(getRequest('expdate'));
		 $info['data'] = $reqData; 
      	 $info['where']= "dlygl_id=$dlygl_id";
		 //$info['debug']  = true;
		 return $res = update($info);
  }
  
  function DeleteIncome()
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
			 echo 'deleted..';  	   
      	   header("location:?app=finance&cmd=Income");      	         	   
      	}      	
      	else
      	{ echo 'not deleted..';  	
      		 header("location:?app=finance&cmd=Income");      	   	
      	}      	
      }	
   }
   
   
   function IncomeFetch($start= null, $limit= null){
    $branch_id = getFromSession('branch_id');
	 $searchq =$_GET['searchq'];
	 $Page 	= getRequest('page');
		if($Page){
 	            $sql="SELECT dlygl_id,g.chart_id,account_name,expdate,particulars, cr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='0.00000' and branch_id=$branch_id order by expdate desc LIMIT $start, $limit";
	}
	else{
	     
		 $sql="SELECT dlygl_id,g.chart_id,account_name,expdate,particulars, cr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='0.00000' and branch_id=$branch_id order by expdate desc LIMIT 0, 29";
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass"  >
					<tr>
					<th align="left" nowrap="nowrap">Date</th>
					<th align="left" nowrap="nowrap">Incone Account</th>
					<th align="left" nowrap="nowrap">Particulars</th>
					<th align="left" nowrap="nowrap">Cr.</th>
					<th align="left">Delete</th>
					<th align="left">Edit</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particulars'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=finance&cmd=DeleteIncome&dlygl_id='.$row['dlygl_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateIncome(\''.$row['dlygl_id'].'\',
					\''.$row['chart_id'].'\',
					\''.$row['particulars'].'\',
					\''.$row['cr'].'\',
					\''.dateInputFormatDMY($row['expdate']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particulars'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=finance&cmd=DeleteIncome&dlygl_id='.$row['dlygl_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateIncome(\''.$row['dlygl_id'].'\',
					\''.$row['chart_id'].'\',
					\''.$row['particulars'].'\',
					\''.$row['cr'].'\',
					\''.dateInputFormatDMY($row['expdate']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
				
	
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
         }

/************************************************** Function for  Expence Save ************************************
********************************************************************************************************************/	
  function Expence(){
  $branch_id = getFromSession('branch_id');
 	 if ((getRequest('submit'))) {
	 	$msg = $this->SaveExpence();
	 }
   
					 
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
			$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=1\">1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=1\">1</a>";
				$pagination.= "<a href=\"?app=finance&cmd=Expence&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=finance&cmd=Expence&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-30-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 
	 
	 
	 $expence = $this->ExpenceFetch($start, $limit);
	 $Exp = $this->SelectEXpCht();
	 $SelectBankList = $this->SelectBankList();
	 require_once(EXPENCE_SAVE_SKIN); 
  }
 
/*  function SaveExpence(){
  	 
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addExpence();
		 
		 if($res['affected_rows']){
				//$msg = 'Successfully saved !!!';
				header("location:index.php?app=finance&cmd=Expence&msg=Successfully saved");
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
	 if($mode == 'edit'){
	 	$res = $this->EditExpence();
		 if($res){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }  
*/  

  function addExpence(){
 $branch_id = getFromSession('branch_id');
  $bankid = getRequest('bankid');
 $paytype = getRequest('paytype');
 $dr = getRequest('dr');
 $chart_id = getRequest('chart_id');
 $particulars = getRequest('particulars');
 $expdate = formatDate4insert(getRequest('expdate'));

  
 if($paytype=='Bank'){
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
		 $reqData['paytype'] = $paytype;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
		 if($res['newid']){
			$dlygl_id=$res['newid'];
				 $sql="INSERT INTO bank_trans(bankid, transtyp, withdrawal, trans_date,chart_id, dlygl_id)
				VALUES('$bankid', 'Withdraw', '$dr', '$expdate', '$chart_id','$dlygl_id')";
				mysql_query($sql);
				header("location:?app=finance&cmd=Expence&msg=Successfully saved");
			} 	         	   
			else
			{	
				header("location:?app=finance&cmd=Expence&msg=Not saved");
		
			}
	}
	
 if($paytype=='Cash'){
  $branch_id = getFromSession('branch_id');
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = DAILY_ACC_LEDGER_TBL;
		 $reqData = getUserDataSet(DAILY_ACC_LEDGER_TBL);
		 $reqData['created_by'] = getFromSession('userid');
		 $reqData['expdate'] = $expdate;
		 $reqData['chart_id'] = $chart_id;
		 $reqData['branch_id'] = $branch_id;
		 $reqData['dr'] = $dr;
		 $reqData['particulars'] = $particulars;
		 $reqData['paytype'] = $paytype;
		 $info['data'] = $reqData; 
		 //$info['debug']  = true;
		 $res = insert($info);
		 if($res){
				header("location:?app=finance&cmd=Expence&msg=Successfully saved");
			} 	         	   
			else
			{	
				header("location:?app=finance&cmd=Expence&msg=Not saved");
		
			}
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
						 
      	   header("location:?app=finance&cmd=Expence");      	         	   
      	}      	
      	else
      	{ 
      		 header("location:?app=finance&cmd=Expence");      	   	
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
				where dr !='0.00000' and g.branch_id=$branch_id order by expdate desc LIMIT $start, $limit";
	}
	else{
	     
		 $sql="SELECT dlygl_id,detail_accid,g.chart_id,a.account_name,expdate,particulars, dr,company_name,bank_name,account_no 
				FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a on a.chart_id = g.chart_id 
				left outer join ".INV_SUPPLIER_INFO_TBL." s on s.supplier_id=g.supplier_id
				left outer join bank_info b on b.bankid=g.bankid
		where dr !='0.00000' and g.branch_id=$branch_id order by expdate desc LIMIT 0, 29";
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass"  >
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
					
	$link2='<a href="?app=finance&cmd=DeleteExpence&dlygl_id='.$row['dlygl_id'].'" onclick="return confirmDelete();" title="Delete">
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



  
/************************************************** Function for SelectEXpCht************************************
********************************************************************************************************************/	
function SelectEXpCht($exp= null){ 
		$sql="SELECT chart_id,account_name FROM ".ACCOUNTS_CHART_TBL." where type='expence' ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='chart_id' size='1' id='chart_id' style='width:150px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border:2px solid; border-color:#00CCFF' >";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['chart_id'] == $exp){
			$country_name_select .= "<option value='".$row['chart_id']."' selected = 'selected'>".$row['account_name']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['chart_id']."'>".$row['account_name']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	


/************************************************** Function for SelectIncCht ************************************
********************************************************************************************************************/	
function SelectIncCht($inco= null){ 
		$sql="SELECT chart_id,account_name FROM ".ACCOUNTS_CHART_TBL." where type='income' ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='chart_id' size='1' id='chart_id' style='width:150px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border:2px solid; border-color:#00CCFF' >";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['chart_id'] == $inco){
			$country_name_select .= "<option value='".$row['chart_id']."' selected = 'selected'>".$row['account_name']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['chart_id']."'>".$row['account_name']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	


function ReportsSkin(){
require_once(ACC_REPORTS_SKIN);
}

// ===================================================  Expence report ===========================================================

 function ExpenceAllReportSkin(){
   
 	$fromexpall 	= formatDate4insert(getRequest('fromexpall'));
 	$toexpall 	= formatDate4insert(getRequest('toexpall'));
	$ExpReportAllFetch = $this->expencereportAll($fromexpall, $toexpall);
 	 require_once(EXPENCE_ALL_REPORTS_SKIN);
	}
	
 function expencereportAll($fromexpall, $toexpall){
		
		 $sql = "SELECT account_name,expdate,particulars, dr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
				on a.chart_id = g.chart_id where dr !='0.00000' and expdate between '$fromexpall' and '$toexpall' ";

	$res= mysql_query($sql);
	$speci = "<table width=700 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Account Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dr</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$dr=$row['dr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['account_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($dr,2)."</td>
		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['expdate'])."</td>
		</tr>";

$totalDr = $totalDr+$row['dr'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Expence : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalDr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

 function ExpenceGroupReportSkin(){
   
 	$fromexpgr 	= formatDate4insert(getRequest('fromexpgr'));
 	$toxpgr 	= formatDate4insert(getRequest('toxpgr'));
	$ExpReportGroupFetch = $this->expencereportGroup($fromexpgr, $toxpgr);
 	 require_once(EXPENCE_GROUP_REPORTS_SKIN);
	}
	
 function expencereportGroup($fromexpgr, $toxpgr){
	
			 $sql = "SELECT account_name,expdate,particulars, sum(dr) as dr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where dr !='' and expdate between '$fromexpgr' and '$toxpgr' group by g.chart_id";


	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Account Name</th>
<!--	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
-->	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dr</th>
<!--				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
-->	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$dr=$row['dr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['account_name']."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
-->		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($dr,2)."</td>
<!--		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['expdate'])."</td>
-->		</tr>";

$totalDr = $totalDr+$row['dr'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=2 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Expence : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalDr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc



// ===================================================  Income report ===========================================================
 function IncomeAllReportSkin(){
   
 	$slfrominall 	= formatDate4insert(getRequest('slfrominall'));
 	$sltoinall 	= formatDate4insert(getRequest('sltoinall'));
 	//$branch_id 	= getRequest('branch_id');
/*	$sql = "select branch_name from branch where branch_id=$branch_id";
	$res= mysql_query($sql);
	$row=mysql_fetch_array($res);
	$branch_name=$row['branch_name'];
*/	$IncAllReportFetch = $this->incomeAllreport($slfrominall, $sltoinall);
 	 require_once(INCOME_ALL_REPORTS_SKIN);
	}
	
 function incomeAllreport($slfrominall, $sltoinall){
		 
		 //if($slfrominall && $sltoinall && !$branch_id){
		 $sql = "SELECT account_name,expdate, cr, particulars FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='0.00000' and expdate between '$slfrominall' and '$sltoinall' ";
		// }
	
/*		 if($slfrominall && $sltoinall && $branch_id){
		 $sql = "SELECT account_name,expdate, cr, particulars FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='0.00' and expdate between '$slfrominall' and '$sltoinall' and branch_id=$branch_id";
		 }
*/	
	$res= mysql_query($sql);

	
	$speci = "<table width=550 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">income Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cr.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$cr=$row['cr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;".$row['account_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($cr,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;"._date($row['expdate'])."</td>
		</tr>";

$totalCr = $totalCr+$row['cr'];
	$i++;
	}

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Income : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalCr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc


 function IncomeGroupReportSkin(){
   
 	$slfromingr 	= formatDate4insert(getRequest('slfromingr'));
 	$sltoingr 	= formatDate4insert(getRequest('sltoingr'));
 	$branch_id 	= getRequest('branch_id');
	$sql = "select branch_name from branch where branch_id=$branch_id";
	$res= mysql_query($sql);
	$row=mysql_fetch_array($res);
	$branch_name=$row['branch_name'];
	$IncGrupReportFetch = $this->incomeGroupreport($slfromingr, $sltoingr, $branch_id);
 	 require_once(INCOME_GROUP_REPORTS_SKIN);
	}
	
 function incomeGroupreport($slfromingr, $sltoingr, $branch_id){
		 if($slfromingr && $sltoingr && !$branch_id){
		 $sql = "SELECT account_name,expdate, sum(cr) as cr, particulars FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='' and expdate between '$slfromingr' and '$sltoingr' group by g.chart_id";
		 }

		 if($slfromingr && $sltoingr && $branch_id){
		 $sql = "SELECT account_name,expdate, sum(cr) as cr, particulars FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where cr !='' and expdate between '$slfromingr' and '$sltoingr' and branch_id=$branch_id
					 group by g.chart_id";
		 }

	$res= mysql_query($sql);

	
	$speci = "<table width=550 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">income Name</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Cr.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$cr=$row['cr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;".$row['account_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($cr,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;"._date($row['expdate'])."</td>
		</tr>";

$totalCr = $totalCr+$row['cr'];
	$i++;
	}

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Income : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalCr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

// ================================== Bnak Deposit ========================================================================
 function BankDepoReportSkin(){
 	$slfrombd 	= formatDate4insert(getRequest('slfrombd'));
 	$sltobd 	= formatDate4insert(getRequest('sltobd'));
	$depositReport = $this->depositReport($slfrombd, $sltobd);
 	 require_once(BANK_DEPOSIT_REPORTS_SKIN);
	}
	
 function depositReport($slfrombd, $sltobd){
	
		$sqlBnkDpo = "SELECT
						bi.bank_name,
						deposit,
						trans_date,
						descrip,
						account_no
					FROM
						".BANK_TRANS_TBL." b inner join ".BANK_INFO_TBL." bi on bi.bankid=b.bankid 
						where deposit!='0.00' and trans_date between '$slfrombd' and '$sltobd' ";
	
	//echo $sql;
	$resBnkDpo= mysql_query($sqlBnkDpo);

	$speci = "<table width=600 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th align=left style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Bank/Acc.</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Description</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Deposit(tk.)</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($resBnkDpo)){
						$deposit=$row['deposit'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['bank_name']."<br>(".$row['account_no'].")</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['descrip']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($deposit,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>"._date($row['trans_date'])."</td>
		</tr>";

$totaldeposit = $totaldeposit+$row['deposit'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Deposit : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totaldeposit,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc


//====================================================== bank Withdrow ====================================================
 function BankWithdrawReportSkin(){
   
 	$slfrombw 	= formatDate4insert(getRequest('slfrombw'));
 	$sltobw 	= formatDate4insert(getRequest('sltobw'));
	$withdrawReport = $this->withdrawReport($slfrombw, $sltobw);
 	 require_once(BANK_WITHDRAW_REPORTS_SKIN);
	}
	
 function withdrawReport($slfrombw, $sltobw){
	
	
		$sqlBnkDpo = "SELECT
						bi.bank_name,
						withdrawal,
						trans_date,
						descrip,
						account_no
					FROM
						".BANK_TRANS_TBL." b inner join ".BANK_INFO_TBL." bi 
						on bi.bankid=b.bankid where withdrawal!='0.00' and trans_date between '$slfrombw' and '$sltobw' ";
	
	//echo $sql;
	$resBnkDpo= mysql_query($sqlBnkDpo);

	$speci = "<table width=600 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th align=left  style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Bank/Acc.</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Description</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Withdraw(tk.)</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($resBnkDpo)){
						$withdrawal=$row['withdrawal'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['bank_name']."<br>(".$row['account_no'].")</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['descrip']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($withdrawal,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>"._date($row['trans_date'])."</td>
		</tr>";

$totalwithdrawal = $totalwithdrawal+$row['withdrawal'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Wothdraw : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalwithdrawal,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

//====================================================== Bank Balance Sheet ====================================================
 function BankBlncShtSkin(){
   
 	$slfrombw 	= formatDate4insert(getRequest('slfromsht'));
 	$sltobw 	= formatDate4insert(getRequest('sltosht'));

	$BankBalanceReport = $this->BankBalanceReport($slfrombw, $sltobw);
 	 require_once(BANK_BALANCE_SHEET_REPORTS_SKIN);
	}
	
 function BankBalanceReport($slfrombw, $sltobw){
	
		$sqlBnkDpo = "SELECT
						bi.bank_name,
						deposit,
						withdrawal,
						trans_date,
						descrip,
						account_no
					FROM
						".BANK_TRANS_TBL." b inner join ".BANK_INFO_TBL." bi on bi.bankid=b.bankid
						where trans_date between '$slfrombw' and '$sltobw' ";
	
	//echo $sql;
	$resBnkDpo= mysql_query($sqlBnkDpo);

	$speci = "<table width=600 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th align=left  style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Bank/Acc.</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Description</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Deposit(tk.)</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Withdraw(tk.)</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($resBnkDpo)){
						$deposit=$row['deposit'];
						$withdrawal=$row['withdrawal'];

		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['bank_name']."<br>(".$row['account_no'].")</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['descrip']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($deposit,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($withdrawal,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=center>"._date($row['trans_date'])."</td>
		</tr>";

$Totaldeposit = $Totaldeposit+$row['deposit'];
$totalwithdrawal = $totalwithdrawal+$row['withdrawal'];
	$i++;
	}
		
$balance=($Totaldeposit-$totalwithdrawal);
	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($Totaldeposit,2)."</b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalwithdrawal,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr>
		<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Balance : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\" colspan=2><b>Tk. ".number_format($balance,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc


// ===================================================  GL report ===========================================================
 function GLReportSkin(){

 	$from 	= formatDate4insert(getRequest('disfrom2'));

 	$to 	= formatDate4insert(getRequest('disto2'));

	$GLReportFetch = $this->GLreport($from, $to);

 	 require_once(GENERAL_LEDGER_SKIN);

	}

	

 function GLreport($from, $to){

		if($from && $to){

		 $sql = "SELECT `account_name`, 

							`expdate`,

							 `dr`, 

							 `cr`, 

							 @rt := @rt +(`dr` -`cr`) as `balance` FROM `daily_acc_ledger` g inner join accounts_chart a on a.chart_id = g.chart_id , (SELECT @rt := 0 ) as tempName where expdate between '$from' and '$to' ";

		}

	//echo $sql;

	$res= mysql_query($sql);

	$speci = "<table width=740 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>

	            <tr>

				<th style=\"border:#000000 solid 1px\">SL</th>

				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Date</th>

	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Account Name</th>

	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Dr</th>

				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cr</th>

				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Balance</th>

	       </tr>";

                 $i=1; 

				 $rowcolor=0;

					while($row=mysql_fetch_array($res)){

						if($row['cr']=='0.00000'){ $cr='--'; }else{ $cr=number_format($row['cr'], 2); }

						if($row['dr']=='0.00000'){ $dr='--'; }else{ $dr=number_format($row['dr'], 2); }

						$balance=$row['balance'];

		 					if($rowcolor==0){ 

								$style = "oddClassStyle";$rowcolor=1;

			 				}else{

									$style = "evenClassStyle";$rowcolor=0;

			 						}

			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >

		

		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['expdate'])."</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['account_name']."</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$dr."</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$cr."</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($balance,2)."</td>

		</tr>";



	$totalCr = $totalCr+$row['cr'];

	$totalDr = $totalDr+$row['dr'];	

	$Nete = $totalDr-$totalCr;	

	$i++;

	}

	$speci .= "<tr height=25 >

		<td nowrap=nowrap colspan=3 style=\"border:#000000 solid 1px\" align=right><b>Total</b>&nbsp;</td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><b> Tk. ".number_format($totalDr,2)."</b></td>

		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right><b> Tk. ".number_format($totalCr,2)."</b></td>

		<td>&nbsp;</td>

		</tr><tr height=25 >

		<td nowrap=nowrap colspan=3 style=\"border:#000000 solid 1px\" align=right><b>Balance</b>&nbsp;</td>

		<td nowrap=nowrap colspan=2 style=\"border:#000000 solid 1px\" align=center><b> Tk. ".number_format($Nete,2)."</b></td>

		<td>&nbsp;</td>

		</tr></table>";

	return $speci;



}//end fnc


// ===================================================  Salary Month wise report ===========================================================

 function SalaryMonthWiseReportSkin(){
   
 	$salary_month 	= getRequest('salary_month');
 	$branch_id 	= getRequest('branch_id');
	$sql = "select branch_name from branch where branch_id=$branch_id";
	$res= mysql_query($sql);
	$row=mysql_fetch_array($res);
	$branch_name=$row['branch_name'];
	$SalaryMonthWiseFetch = $this->SalaryMonthWiseReport($salary_month,$branch_id);
 	 require_once(SALARY_MONTHWISE_REPORTS_SKIN);
	}
	
 function SalaryMonthWiseReport($salary_month,$branch_id){
	
		if($salary_month && !$branch_id){
			 $sql = "SELECT
				slinvoice_id,
				person_name,
				invc_title,
				descrip,
				amount,
				paid_date,
				salary_month
			FROM
				salary_invoice s inner join hrm_person p
				on s.person_id=p.person_id where salary_month='$salary_month'";
		}
	
		if($salary_month && $branch_id){
			 $sql = "SELECT
				slinvoice_id,
				person_name,
				invc_title,
				descrip,
				amount,
				paid_date,
				salary_month
			FROM
				salary_invoice s inner join hrm_person p
				on s.person_id=p.person_id where salary_month='$salary_month' and branch_id=$branch_id";
		}
	

	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Employee Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Invoice Title </th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Amount</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Paid Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$amount=$row['amount'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['person_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['invc_title']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($amount,2)."</td>
		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['paid_date'])."</td>
		</tr>";

$totalamount = $totalamount+$row['amount'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Salary : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalamount,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

// ===================================================  Salary Summary report ===========================================================

 function SalarySummaryReportSkin(){
   
 	$slfromsum 	= formatDate4insert(getRequest('slfromsum'));
 	$sltosum 	= formatDate4insert(getRequest('sltosum'));
 	$branch_id 	= getRequest('branch_id');
	$sql = "select branch_name from branch where branch_id=$branch_id";
	$res= mysql_query($sql);
	$row=mysql_fetch_array($res);
	$branch_name=$row['branch_name'];
	$SalarySummaryFetch = $this->SalarySummaryReport($slfromsum,$sltosum,$branch_id);
 	 require_once(SALARY_SUMMARY_REPORTS_SKIN);
	}
	
 function SalarySummaryReport($slfromsum,$sltosum,$branch_id){
	 
	 if($slfromsum && $sltosum && !$branch_id){
		 $sql = "SELECT	sum(amount) as sum_amount, salary_month FROM salary_invoice where paid_date between '$slfromsum' and '$sltosum'
		 group by salary_month ";
	 }

	 if($slfromsum && $sltosum && $branch_id){
		 $sql = "SELECT	sum(amount) as sum_amount, salary_month FROM salary_invoice where paid_date between '$slfromsum' and '$sltosum' 
		 and branch_id=$branch_id group by salary_month ";
	 }

	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th width=36 style=\"border:#000000 solid 1px\">SL</th>
	             <th width=242 align=left nowrap=nowrap style=\"border:#000000 solid 1px\">Salary Month</th>
	             <th width=270 align=right nowrap=nowrap style=\"border:#000000 solid 1px\">Amount</th>
		   </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$sum_amount=$row['sum_amount'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".substr(_date($row['salary_month']),3,8)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($sum_amount,2)."</td>
		</tr>";

$totalsum_amount = $totalsum_amount+$row['sum_amount'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=2 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Salary : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalsum_amount,2)."</b></td>
		</tr></table>";
	return $speci;

}//end fnc


// ===================================================  Fixed Assets report ===========================================================

 function FixedAssetAllReportSkin(){
   
 	$fromexpall 	= formatDate4insert(getRequest('fromexpall'));
 	$toexpall 	= formatDate4insert(getRequest('toexpall'));
	$ExpReportAllFetch = $this->FixedAssetreportAll($fromexpall, $toexpall);
 	 require_once(FIXED_ASSET_ALL_REPORTS_SKIN);
	}
	
 function FixedAssetreportAll($fromexpall, $toexpall){
		
		$sql = "SELECT account_name,expdate,particulars, dr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where dr !='' and expdate between '$fromexpall' and '$toexpall' ";

	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Account Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dr</th>
				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$dr=$row['dr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['account_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($dr,2)."</td>
		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['expdate'])."</td>
		</tr>";

$totalDr = $totalDr+$row['dr'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=3 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Expence : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalDr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

 function FixedAssetGroupReportSkin(){
   
 	$fromexpgr 	= formatDate4insert(getRequest('fromexpgr'));
 	$toxpgr 	= formatDate4insert(getRequest('toxpgr'));
	$ExpReportGroupFetch = $this->FixedAssetreportGroup($fromexpgr, $toxpgr);
 	 require_once(FIXED_ASSET_GROUP_REPORTS_SKIN);
	}
	
 function FixedAssetreportGroup($fromexpgr, $toxpgr){
	
			 $sql = "SELECT account_name,expdate,particulars, sum(dr) as dr FROM ".DAILY_ACC_LEDGER_TBL." g inner join ".ACCOUNTS_CHART_TBL." a
					 on a.chart_id = g.chart_id where dr !='' and expdate between '$fromexpgr' and '$toxpgr' group by g.chart_id";


	$res= mysql_query($sql);
	$speci = "<table width=550 cellpadding=\"4\" cellspacing=\"4\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Account Name</th>
<!--	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">particulars</th>
-->	             <th nowrap=nowrap align=right style=\"border:#000000 solid 1px\">Dr</th>
<!--				 <th nowrap=nowrap align=center style=\"border:#000000 solid 1px\">Date</th>
-->	       </tr>";
                 $i=1; 
				 $rowcolor=0;
					while($row=mysql_fetch_array($res)){
						$dr=$row['dr'];
		 					if($rowcolor==0){ 
								$style = "oddClassStyle";$rowcolor=1;
			 				}else{
									$style = "evenClassStyle";$rowcolor=0;
			 						}
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['account_name']."</td>
<!--		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['particulars']."</td>
-->		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($dr,2)."</td>
<!--		<td align=center nowrap=nowrap style=\"border:#000000 solid 1px\">"._date($row['expdate'])."</td>
-->		</tr>";

$totalDr = $totalDr+$row['dr'];
	$i++;
	}
		

	$speci .= "<tr height=25 >
		<td colspan=2 align=right nowrap=nowrap style=\"border:#000000 solid 1px\" ><b>Total Expence : </b></td>
		<td align=right nowrap=nowrap  style=\"border:#000000 solid 1px\"><b>Tk. ".number_format($totalDr,2)."</b></td>
		<td align=right nowrap=nowrap ></td>
		</tr></table>";
	return $speci;

}//end fnc

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


function DailyCahReportSkin(){
 $slfrom2 = formatDate4insert(getRequest('slfrom2'));
// $slto2 = formatDate4insert(getRequest('slto2'));
 $Fetch = $this->DailyCahReportFetch($slfrom2);
require_once(DAILY_CASH_BALANCE_SHEET_REPORTS_SKIN);
}


 function DailyCahReportFetch($slfrom2){
 $branch_id = getFromSession('branch_id');
	$sql = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sale_pay_id,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	discount,
	sum(totaldiscount) as totaldiscount,
	sum(discount_percent) as discount_percent,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date='$slfrom2' and pay_type='Cash'
	and s.branch_id=$branch_id and sales_type=1 group by itemcode";

	//echo $sql;
	$res= mysql_query($sql);



	$speci = "<table width=950 cellpadding=\"5\" cellspacing=\"0\" style='border-collapse:collapse'>
	            <tr>
				<th style=\"border:#000000 solid 1px\">SL</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Category</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Code</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Item Name</th>
	             <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Qnt.</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Cost</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Sales Price</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Total Sales</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Discount</th>
				 <th nowrap=nowrap align=left style=\"border:#000000 solid 1px\">Net Sales</th>
	       </tr>
		<tr><td colspan='11' align='center'></td></tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Retail Sales</b></td></tr>";

                 $i=1;        $rowcolor=0;
	while($row=mysql_fetch_array($res)){

	$return_amount=$row['return_amount'];
	$costprice=$row['costprice'];
	$item_qnt=($row['item_qnt']-$row['return_qnt']);
	$totalCost=($item_qnt*$costprice);

	$total_amount=$row['total_amount'];
	$totaldiscount=$row['totaldiscount'];
	$salePayId=$row['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $netSales=(($total_amount)-($return_amount+$totaldiscount));
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales,2)."</td>
		</tr>";
	
	$returnAmount=$returnAmount+$row['return_amount'];
	$totalValue = $totalValue+$netSales;
	$totalcostprice = $totalcostprice+$totalCost;
	$totalQnt = $totalQnt+$item_qnt;
	$netDisc = $netDisc+$totaldiscount;
	
	
	
	$i++;
	}
	
	$TotalSales=($totalValue);
	$profit=($TotalSales-$totalcostprice);
	$inPercnt=($profit/$totalcostprice)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalSales,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> <strong>Profit : ".$profit." (".$inPercnt.")%</strong></td>
		</tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Whole Sales</b></td></tr>";

//================================== For whole sales ==============================================================
	$sql2 = "SELECT
	sales_id,
	s.item_id,
	sub_item_category_name,
	item_name,
	itemcode,
	sale_pay_id,
	sum(item_qnt) as item_qnt,
	costprice,
	salesprice,
	discount,
	sum(totaldiscount) as totaldiscount,
	sum(discount_percent) as discount_percent,
	sum(total_amount) as total_amount,
	sum(return_qnt) as return_qnt,
	sum(return_amount) as return_amount
	
FROM
	inv_item_sales s inner join inv_iteminfo i on i.item_id=s.item_id
	inner join inv_item_category_sub c on c.sub_item_category_id=i.sub_item_category_id
	where sales_status='Not Pending' and sales_date='$slfrom2' and pay_type='Cash'
	and s.branch_id=$branch_id and sales_type=2 group by itemcode";

	//echo $sql;
	$res2= mysql_query($sql2);


                 $i2=1;        $rowcolor=0;
	while($row2=mysql_fetch_array($res2)){

	$return_amount2=$row2['return_amount'];
	$costprice2=$row2['costprice'];
	$item_qnt2=($row2['item_qnt']-$row2['return_qnt']);
	$totalCost2=($item_qnt2*$costprice2);

	$total_amount2=$row2['total_amount'];
	$totaldiscount2=$row2['totaldiscount'];
	$salePayId2=$row2['sale_pay_id'];

/*	 $sql2="SELECT count(sale_pay_id) as totsale_pay_id FROM ".INV_ITEM_SALES_TBL." where  sale_pay_id=$salePayId";
	 $res2= mysql_query($sql2);
	 $row2 = mysql_fetch_array($res2);
	 $totsale_pay_id=$row2['totsale_pay_id'];
	 
	 $NwDisc=($totaldiscount/$totsale_pay_id);
*/	 
	 $netSales2=(($total_amount2)-($return_amount2+$totaldiscount2));
	 //$netSales=($total_amount-$NwDisc);

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" 	onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['sub_item_category_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['itemcode']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$row2['item_name']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$item_qnt2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['costprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$totalCost2."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['salesprice']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".$row2['total_amount']."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($totaldiscount2,2)."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right>".number_format($netSales2,2)."</td>
		</tr>";
	
	$returnAmount2=$returnAmount2+$row2['return_amount'];
	$totalValue2 = $totalValue2+$netSales2;
	$totalcostprice2 = $totalcostprice2+$totalCost2;
	$totalQnt2 = $totalQnt2+$item_qnt2;
	$netDisc2 = $netDisc2+$totaldiscount2;
	
	$i2++;
	}
	
	$TotalSales2=($totalValue2);
	$profit2=($TotalSales2-$totalcostprice2);
	$inPercnt2=($profit2/$totalcostprice2)*100;
	
	$speci .= "<tr height=25 >
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td nowrap=nowrap ></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan=2> <strong>Total Qnt : ".$totalQnt2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$totalcostprice2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\">&nbsp;</td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".$netDisc2."</strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalSales2,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\" colspan='11'> <strong>Profit : ".$profit2." (".$inPercnt2.")%</strong></td>
		</tr>
		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Cash Received</b></td></tr>";


//================================== For Cash Received ==============================================================
	 $sql5 = "SELECT
	account_name,
	cr as crAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where  cr!='0.00000' and expdate='$slfrom2' and branch_id=$branch_id ";

	//echo $sql;
	$res5= mysql_query($sql5);

      $i5=1;        $rowcolor=0;
	while($row5=mysql_fetch_array($res5)){

	$account_name=$row5['account_name'];
	$crAmount5=$row5['crAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i5."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($crAmount5,2)."</td>
		</tr>";
	
	$totalIncome = $totalIncome+$crAmount5;
	
	$i5++;
	}
	
	$TotalAmount=($TotalSales+$TotalSales2+$totalIncome);
	
	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Sub Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalIncome,2)."</strong></td>
		</tr>

	  <tr>
	  <td colspan='10' align='right' style=\"border-top:#000000 solid 1px\"><strong>Total Amount : </strong></td>
	  <td  align='right' style=\"border-top:#000000 solid 1px\"><strong>".number_format($TotalAmount,2)."<hr></strong></td>
	  </tr>
	  <tr><td colspan='11' align='center'>&nbsp;</td></tr>

		<tr><td colspan='11' align='left' height='30' style='border-top:#000000 solid 1px;border-left:#000000 solid 1px;border-right:#000000 solid 1px;' >
		<b>&nbsp;Operating Cost</b></td></tr>";

//================================== For Operating Cost ==============================================================
	 $sql6 = "SELECT
	account_name,
	dr as drAmount
FROM
	daily_acc_ledger l inner join accounts_chart c on c.chart_id=l.chart_id
	where dr!='0.00000' and expdate='$slfrom2' and paytype='Cash' and branch_id=$branch_id";

	//echo $sql;
	$res6= mysql_query($sql6);

      $i6=1;        $rowcolor=0;
	while($row6=mysql_fetch_array($res6)){

	$account_name=$row6['account_name'];
	$drAmount6=$row6['drAmount'];

		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr  class=".$style." onMouseOver=\"this.className='highlight'\" onMouseOut=\"this.className='".$style."'\" height=25 >
		
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$i6."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\">".$account_name."</td>
		<td nowrap=nowrap style=\"border:#000000 solid 1px\" align=right colspan=9>".number_format($drAmount6,2)."</td>
		</tr>";
	
	$totalExpense = $totalExpense+$drAmount6;
	
	$i6++;
	}
	
	$TotalProfite=($TotalAmount-$totalExpense);

//===================== Calculation of last Day ============================

$sqlLstDay = "select DATE_SUB('".$slfrom2."', INTERVAL 1 DAY) as Last_Sales_Date";
$resLstDay= mysql_query($sqlLstDay);
$rowLstDay=mysql_fetch_array($resLstDay);
$Last_Sales_Date=$rowLstDay['Last_Sales_Date'];

	echo $sql7 = "SELECT sum(paid_amount) as paid_amount,sum(ret_amount) as ret_amount FROM
	inv_item_sales_payment where sales_date<'$slfrom2' and pay_type='Cash' and branch_id=$branch_id";

	//echo $sql;
	$res7= mysql_query($sql7);

	while($row7=mysql_fetch_array($res7)){
	$paid_amount7=($row7['paid_amount']-$row7['ret_amount']);
	}
	
	
	//================================== Cash Received Last Date==============================================================
	 $sql9 = "SELECT cr as crAmount FROM daily_acc_ledger where  cr!='0.00000' and expdate<'$slfrom2' and branch_id=$branch_id";
	$res9= mysql_query($sql9);
	while($row9=mysql_fetch_array($res9)){
	$totalIncome9 = $totalIncome9+$row9['crAmount'];
	}
	
	$LastDayIncome=($paid_amount7+$totalIncome9);

//================= Last Day Operating Cost =======================
 $sql10 = "SELECT dr as drAmount FROM	daily_acc_ledger where dr!='0.00000' and expdate<'$slfrom2' and paytype='Cash' and branch_id=$branch_id";
 $res10= mysql_query($sql10);

	while($row10=mysql_fetch_array($res10)){
	$totalExpense10 = $totalExpense10+$row10['drAmount'];
	}
	
	$LastDayCash=($LastDayIncome-$totalExpense10);	
	

//=====================  END============================

	$speci .= "<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Total : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($totalExpense,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Today's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($TotalProfite,2)."</strong></td>
		</tr>
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Last Day's Cash : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format($LastDayCash,2)."</strong></td>
		</tr>		
		<tr height=25 >
		<td align=right nowrap=nowrap colspan=10><strong>Cash in Hand : </strong></td>
		<td align=right nowrap=nowrap style=\"border:#000000 solid 1px\"><strong>".number_format(($LastDayCash+$TotalProfite),2)."</strong></td>
		</tr>		
		</table>";
	return $speci;

}//end fnc



} // End class

?>