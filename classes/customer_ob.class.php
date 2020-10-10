<?php
class customer_ob
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
 		 case 'ajaxPersonLoad'     : echo $this->EmployeeFetch(getRequest('ele_id'), getRequest('ele_lbl_id'));        break;
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
	 $query = "SELECT COUNT(*) as num FROM whole_saler_acc where stat='OB' ";
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
			$pagination.= "<a href=\"?app=customer_ob&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=customer_ob&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=customer_ob&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=customer_ob&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=customer_ob&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=customer_ob&page=1\">1</a>";
				$pagination.= "<a href=\"?app=customer_ob&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=customer_ob&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=customer_ob&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=customer_ob&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=customer_ob&page=1\">1</a>";
				$pagination.= "<a href=\"?app=customer_ob&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=customer_ob&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=customer_ob&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $DataFetch = $this->DataFetch($start, $limit);
 $CustomerList=$this->SelectCustomer();
 //$SelectBankList = $this->SelectBankList();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
function SavePayment(){
 $dr_amount = getRequest('dr_amount');
 $customer_id = getRequest('customer_id');
 $particulars = getRequest('particulars');
 $paid_date = formatDate4insert(getRequest('paid_date'));
 $createdby = getFromSession('username');
 $person_id = getFromSession('person_id');
 
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = WHOLE_SALER_ACC_TBL;
		 $reqData = getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $reqData['customer_id']=$customer_id;
		 $reqData['dr_amount']=$dr_amount;
		 $reqData['stat']='OB';
		 $reqData['person_id']=$person_id;
		 $reqData['particulars']=$particulars;
		 $reqData['paid_date']=$paid_date;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res){
				header("location:?app=customer_ob&msg=Saved");
			} 	         	   
			else
			{	
				header("location:?app=customer_ob&msg=Not Saved");
		
			} 
 

}// EOF =====================


 function Delete(){
	$whole_sales_accid = getRequest('whole_sales_accid');
      if($whole_sales_accid)
      {
      	            	
      	$info = array();
      	$info['table'] = WHOLE_SALER_ACC_TBL;
      	$info['where'] = "whole_sales_accid='$whole_sales_accid'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res){  
			 	   
      	   header("location:?app=customer_ob&Dmsg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{  	
      		 header("location:?app=customer_ob&Dmsg=Not Deleted");      	   	
      	}      	
      }	
 }
  
  
 
  
 function DataFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
if($searchq){
	$sql='SELECT
	whole_sales_accid,
	particulars,
	dr_amount,
	paid_date,
	store_name
FROM
	whole_saler_acc a inner join customer_info s on s.customer_id=a.customer_id
	where stat="OB" and name LIKE "%'.$searchq.'%" ORDER BY paid_date desc LIMIT 0, 29';
	}
if($Page){
 $sql="SELECT
	whole_sales_accid,
	particulars,
	dr_amount,
	paid_date,
	store_name
FROM
	whole_saler_acc a inner join customer_info s on s.customer_id=a.customer_id
	where stat='OB' ORDER BY paid_date desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
  $sql="SELECT
	whole_sales_accid,
	particulars,
	dr_amount,
	paid_date,
	store_name
FROM
	whole_saler_acc a inner join customer_info s on s.customer_id=a.customer_id
	where stat='OB' ORDER BY paid_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=700 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Date</th>
                 <th align=left nowrap=nowrap>Shop Name</th>
                 <th align=left nowrap=nowrap>Amount</th>
                 <th nowrap=nowrap align=left>Comments</th>
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
			<td><a href="?app=customer_ob&cmd=Delete&whole_sales_accid='.$row['whole_sales_accid'].'" onClick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'._date($row['paid_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['store_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['dr_amount'].'&nbsp;</td>
			<td>'.$row['particulars'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>
	      ';
		return $type;
}//end fnc



function SelectCustomer($sup = null){ 
//$branch_id = getFromSession('branch_id');
//echo $customerid;exit();
		$sql="SELECT customer_id,store_name from customer_info ORDER BY store_name";
	    $result = mysql_query($sql);
		$country_select = "<select name='customer_id' size='1' id='customer_id' class=\"textBox\" style='width:180px;' onchange=\"Redirect(this.value)\">";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
		
		//$customer_id=$row['customer_id'];
		//echo $customerid;
	
			if($row['customer_id'] == $customerid){
					   $country_select .= "<option value='".$row['customer_id']."' selected='selected'>".$row['store_name']."</option>";
					   }else{
			     $country_select .= "<option value='".$row['customer_id']."'> ".$row['store_name']."</a>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }


  	 function EmployeeFetch($ele_id, $ele_lbl_id){
  	// Number of records to show per page
		$recordsPerPage = 20;  
		
		// default startup page
		$pageNum = 1;
		
		/*if(isset($_GET['p'])) {
		  $pageNum = $_GET['p'];
		  settype($pageNum, 'integer');
		}*/
		
		$offset = ($pageNum - 1) * $recordsPerPage;
  
	 $search =getRequest('search');
		if($search){
 	            $sql="SELECT customer_id,store_name from customer_info
				WHERE store_name LIKE '%".$search."%' "."
				ORDER BY store_name ASC ";
	}
	else{
	     
		 $sql="SELECT customer_id,store_name from customer_info ORDER BY store_name ASC ";
		 }
		 //echo $sql;
	$res= mysql_query($sql);
	$Person = '<table width="200">';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
			$Person .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlightDivSearchCourse\'" 
				onMouseOut="this.className=\'oddClassStyle\'" onClick="javascript:addPersonId(\''.$row['customer_id'].'\',\''.$ele_id.'\',\''.$row['store_name'].'\',\''.$ele_lbl_id.'\');showHideDiv();" style="cursor:pointer">
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;">'.$row['store_name'].'</td>
				
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$Person .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlightDivSearchCourse\'" 
				onMouseOut="this.className=\'evenClassStyle\'" onClick="javascript:addPersonId(\''.$row['customer_id'].'\',\''.$ele_id.'\',\''.$row['store_name'].'\',\''.$ele_lbl_id.'\');showHideDiv();" style="cursor:pointer">
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;">'.$row['store_name'].'</td>
				
				</tr>';
			  $rowcolor=0;
			  }
	}
	$Person .= '</table>';
	
	return $Person;
         }//EOF


} // End class

?>