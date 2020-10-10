<?php
class accounts_whole
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxSearchCollection'         : echo $this->WholeSalerCollectionFetch();				break;
		 case 'CollectionEnterSkin'         : echo $this->CollectionEnterSkin();					break;
		 case 'OpeningDuesEnterSkin'         : echo $this->OpeningDuesEnterSkin();					break;
		 //case 'ajaxInserCollection'         : echo $this->InsertCollection();						break;
		 case 'InserCollection'         : echo $this->InsertCollection();						break;
		 case 'ajaxEditCollect'         	: echo $this->EditCollection();							break;
		 
		 case 'InserOpeningDues'         : echo $this->InserOpeningDues();						break;
		 case 'ajaxEditOpeningDues'         	: echo $this->EditOpeningDues();							break;
		 case 'DelOpeningDues'       		: $screen = $this->DelOpeningDues();   					break;
		
		 case 'DelCollection'       		: $screen = $this->DelCollection();   					break;
 		 case 'ajaxSR'	   					: $this->SelectSR(); 							break;
	 	 case 'ajaxPersonLoad'     : echo $this->EmployeeFetch(getRequest('ele_id'), getRequest('ele_lbl_id'), getRequest('ele_lbl_id1'));        break;
         //case 'list'                  		: $this->getList();                       				break;
        // default                      		: $cmd == 'list'; $this->getList();	       				break;
      }
 }
 
 
 function CollectionEnterSkin(){
	$branch_id = getFromSession('branch_id');				 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM whole_saler_acc where branch_id=$branch_id and stat is NULL and invoice is NULL";
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
			$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts_whole&cmd=CollectionEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $Collection = $this->WholeSalerCollectionFetch($start, $limit);
 //$WholeSalerList=$this->SelectWholeSaler();
 $SelectBankList = $this->SelectBankList();
 $WholeSallerList=$this->SalesWholeSaller(getRequest('ele_id'), getRequest('ele_lbl_id'));
	$SelectCustomer=$this->SelectCustomer();
$curdate=dateInputFormatDMY(SelectCDate());
 
	require_once(WHOLE_SALER_COLLECTION_SKIN);
 } 


function InsertCollection(){

 	$customer_id = getRequest('customer_id');
 	$project = getRequest('project');
 	$person_id = getFromSession('person_id');
 	$cr_amount = getRequest('cr_amount');
 	$bankid = getRequest('bankid');
	$pay_type = getRequest('pay_type');
 	$paid_date = formatDate4insert(getRequest('paid_date'));
	$branch_id = getFromSession('branch_id');

 if($pay_type=='Bank'){
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = WHOLE_SALER_ACC_TBL;
		 $reqData = getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $reqData['customer_id']=$customer_id;
		 $reqData['person_id']=$person_id;
		 $reqData['cr_amount']=$cr_amount;
		 $reqData['branch_id']=$branch_id;
		 $reqData['bankid']=$bankid;
		 $reqData['pay_type']=$pay_type;
		 $reqData['paid_date']=$paid_date;
		 $reqData['project']=$project;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res['newid']){
			$whole_sales_accid=$res['newid'];
				 $sqlBnk="INSERT INTO bank_trans(bankid, transtyp, deposit, trans_date, customer_id,branch_id,whole_sales_accid)
						VALUES('$bankid', 'Deposit', '$cr_amount', '$paid_date', '$customer_id','$branch_id','$whole_sales_accid')";
			 	mysql_query($sqlBnk);
				header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Successfully Saved');
			} 	         	   
			else
			{	
				header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Not Saved');
		
			} 
 
 }

 if($pay_type=='Cash'){
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = WHOLE_SALER_ACC_TBL;
		 $reqData = getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $reqData['customer_id']=$customer_id;
		 $reqData['person_id']=$person_id;
		 $reqData['cr_amount']=$cr_amount;
		 $reqData['branch_id']=$branch_id;
		 $reqData['pay_type']=$pay_type;
		 $reqData['paid_date']=$paid_date;
		 //$reqData['project']=$project;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);
		 if($res){
				header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Successfully Saved');
			} 	         	   
			else
			{	
				header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Not Saved');
			} 
 
 }

/*		if($pay_type=='Bank'){
			$sql = "INSERT INTO whole_saler_acc(customer_id, cr_amount, paid_date, bankid, pay_type,branch_id) 
			values('$customer_id', '$cr_amount', '$paid_date', '$bankid','$pay_type','$branch_id')"; //exit();
			 mysql_query($sql);
			 
			 $sqlBnk="INSERT INTO bank_trans(bankid, transtyp, deposit, trans_date, customer_id,branch_id)
						VALUES('$bankid', 'Deposit', '$cr_amount', '$paid_date', '$customer_id','$branch_id')";
			 $res = mysql_query($sqlBnk);
				
				if($res){
					header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Successfully Saved');
				}else{
					header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Not Saved');
				}
		 }else{
			$sql = "INSERT INTO whole_saler_acc(customer_id, cr_amount, paid_date, pay_type,branch_id) 
			values('$customer_id', '$cr_amount', '$paid_date', '$pay_type','$branch_id')"; //exit();
			 $res = mysql_query($sql);
				
				if($res){
					header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Successfully Saved');
				}else{
					header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Not Saved');
				}
		 }

*/
}// EOF =====================

	function EditCollection(){
	$whole_sales_accid = getRequest('whole_sales_accid');
 $customer_id = getRequest('customer_id');
 $cr_amount = getRequest('cr_amount');
 $particulars = getRequest('particulars');
 $pay_type = getRequest('pay_type');
 $bankid = getRequest('bankid');
 $paid_date = formatDate4insert(getRequest('paid_date'));
	
	$sql = "UPDATE	whole_saler_acc SET customer_id ='$customer_id', cr_amount ='$cr_amount', particulars ='$particulars',	
			paid_date = '$paid_date',pay_type = '$pay_type',bankid = '$bankid' WHERE whole_sales_accid = $whole_sales_accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $Collection = $this->WholeSalerCollectionFetch();
		}
	}
  
function DelCollection(){
	$whole_sales_accid = getRequest('whole_sales_accid');
  	if($whole_sales_accid)  {
		 $sql = "DELETE from whole_saler_acc where whole_sales_accid = '$whole_sales_accid'";
		mysql_query($sql);	
		 
		 $sql = "DELETE from bank_trans where whole_sales_accid = '$whole_sales_accid'";
		$res = mysql_query($sql);	
		if($res){
		header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Successfully Deleted');
		}else{
		header('location:?app=accounts_whole&cmd=CollectionEnterSkin&msg=Not Deleted');
		}
  	}	
}  



 function WholeSalerCollectionFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
$branch_id = getFromSession('branch_id');
if($searchq && !$Page){
	 $sql='SELECT
	whole_sales_accid,
	pay_type,
	particulars,
	cr_amount,
	paid_date,
	name,
	person_name,
	w.customer_id,
	bank_name,
	account_no,
	pay_type
FROM
	whole_saler_acc w inner join customer_info c on c.customer_id=w.customer_id
	inner join hrm_person p on p.person_id=w.person_id 
	left outer join bank_info b on b.bankid=w.bankid
	where w.branch_id='.$branch_id.' and sales_type="0" and store_name LIKE "%'.$searchq.'%" ORDER BY paid_date desc LIMIT 0, 29';
	}
if($Page && !$searchq){
 $sql="SELECT
	whole_sales_accid,
	pay_type,
	particulars,
	cr_amount,
	paid_date,
	store_name,
	person_name,
	w.customer_id,
	bank_name,
	account_no,
	pay_type,
	project
FROM
	whole_saler_acc w inner join customer_info c on c.customer_id=w.customer_id
	inner join hrm_person p on p.person_id=w.person_id 
	left outer join bank_info b on b.bankid=w.bankid
	where w.branch_id=$branch_id and sales_type='0' ORDER BY paid_date desc LIMIT $start, $limit";
	}
	if(!$searchq && !$Page ){
  $sql="SELECT
	whole_sales_accid,
	pay_type,
	particulars,
	cr_amount,
	paid_date,
	store_name,
	person_name,
	w.customer_id,
	bank_name,
	account_no,
	pay_type,
	project
FROM
	whole_saler_acc w inner join customer_info c on c.customer_id=w.customer_id
	inner join hrm_person p on p.person_id=w.person_id 
	left outer join bank_info b on b.bankid=w.bankid
	where w.branch_id=$branch_id and sales_type='0' ORDER BY paid_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Received Date</th>
				 <th nowrap=nowrap align=left>Amount</th>
	             <th nowrap=nowrap align=left>Customer Name</th>
<!--	             <th nowrap=nowrap align=left>SR Name</th>
-->	             <th nowrap=nowrap align=left>Particular</th>
				 <th nowrap=nowrap align=left>Pay Type</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }


			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" >
			<!--<td><a onClick="javascript:ajaxCall4EditCollect(\''.$row['whole_sales_accid'].'\',\''.
														  $row['customer_id'].'\',\''.
														  $row['cr_amount'].'\',\''.
														  $row['particulars'].'\',\''.
														  $row['pay_type'].'\',\''.
						                                  dateInputFormatDMY($row['paid_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>-->
			<td nowrap=nowrap><a href="?app=accounts_whole&cmd=DelCollection&whole_sales_accid='.$row['whole_sales_accid'].'" 
			onclick="return confirmDelete();" ><span class="label label-danger">Delete</label></a></td>
			<td nowrap=nowrap>'._date($row['paid_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['cr_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['store_name'].'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['person_name'].'&nbsp;</td>
-->			<td nowrap=nowrap>Paid by '.$row['pay_type'].' '.$row['bank_name'].' '.$row['account_no'].'</td>
		<td nowrap=nowrap>'.$row['pay_type'].'&nbsp;</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc


function SelectCustomer($whl = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT customer_id, store_name FROM customer_info ORDER BY store_name ";
	    $result = mysql_query($sql);
		$country_select = "<select name='customer_id' size='1' id='customer_id' class=\"textBox\" style='width:150px;'>";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['customer_id'] == $whl){
					   $country_select .= "<option value='".$row['customer_id']."' selected='selected'>".$row['store_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['customer_id']."'>".$row['store_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }



function SelectBankList(){ 
		$sql="SELECT bankid, bank_name, account_no FROM ".BANK_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $department_select = "<select name='bankid' size='1' id='bankid' style='width:120px'>";
		$department_select .= "<option value=''>Select Bank</option>"; 
			while($row = mysql_fetch_array($result)) {
			$department_select .= "<option value='".$row['bankid']."'>".$row['bank_name']."&nbsp;:&nbsp;".$row['account_no']."</option>";	
		}
		$department_select .= "</select>";
		return $department_select;
}	


function SalesWholeSaller($ele_id, $ele_lbl_id){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT person_name, e.person_id FROM hrm_employee e inner join hrm_person p on p.person_id=e.person_id 
		where e.branch_id=$branch_id";
	//echo $sql;
	$res= mysql_query($sql);
	$speci = "<table width=300 cellpadding=\"5\" cellspacing=\"0\" >";
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$speci .= "<tr class=".$style." onMouseOver=\"this.className='highlight'\" 
		onMouseOut=\"this.className='".$style."'\" 
		onClick=\"javascript:addSalsManId('".$row['person_id']."','".$ele_id."','".$row['person_name']."','".$ele_lbl_id."');
		hideElement('slsManLookUp');\" style=\"cursor:pointer\">
		<td nowrap=nowrap style=\"border-right:1px solid #cccccc;padding:2px;\">".$row['person_name']."</td>
		</tr>";

	}
	$speci .= "</table>";
	return $speci;
   }

function SelectSR(){ 
//$person_id=getRequest('person_id');

		$sql="SELECT customer_id,mobile,name from customer_info 
		where customer_type=1 and branch_id=".getFromSession('branch_id')." ORDER BY name";
	    $result = mysql_query($sql);
		//echo '<label style="font-weight:normal">';
			while($row = mysql_fetch_array($result)) {
	echo '<label style="font-weight:normal"><input name="customer_id" type="radio" value="'.$row['customer_id'].'" />'.$row['name'].'</label><br>';
		}
		//$rad.='<input name="person_id" type="radio" value="0" style="display:none"/></label>';
		//return $rad;

}
	
//================== opening Dues====================================
 function OpeningDuesEnterSkin(){
	$branch_id = getFromSession('branch_id');				 
   	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	 $query = "SELECT COUNT(*) as num FROM whole_saler_acc where branch_id=$branch_id and ob_dues='YES'";
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
			$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$prev\">&laquo; previous</a>";
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
					$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$counter\">$counter</a>";					
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
						$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=1\">1</a>";
				$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1){
			$pagination.= "<a href=\"?app=accounts_whole&cmd=OpeningDuesEnterSkin&page=$next\">next &raquo;</a>";
		}else{
			$pagination.= "<span class=\"disabled\">next</span>";
		 $pagination.= "</div>\n";	
		 }
		 
		 $Rec= getRequest('page');
		 if($page){ $res1 = $Rec*30-29-1;  $res2 = $Rec*30;}else { $res3=0; $res3=29;}
	
	} 

 $Collection = $this->OpeningDuesFetch($start, $limit);
 $WholeSallerList=$this->SalesWholeSaller(getRequest('ele_id'), getRequest('ele_lbl_id'));
	$SelectCustomer=$this->SelectCustomer();

	require_once(OPENING_DUES_COLLECTION_SKIN);
 } 


function InserOpeningDues(){

 	$project = getRequest('project');
 	$customer_id = getRequest('customer_id');
 	$person_id = getFromSession('person_id');
 	$dr_amount = getRequest('dr_amount');
 	$paid_date = formatDate4insert(getRequest('paid_date'));
	$branch_id = getFromSession('branch_id');

  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = WHOLE_SALER_ACC_TBL;
		 $reqData = getUserDataSet(WHOLE_SALER_ACC_TBL);
		 $reqData['customer_id']=$customer_id;
		 $reqData['person_id']=$person_id;
		 $reqData['dr_amount']=$dr_amount;
		 $reqData['branch_id']=$branch_id;
		 $reqData['pay_type']='Cash';
		 $reqData['ob_dues']='YES';
		// $reqData['project']=$project;
		 $reqData['paid_date']=$paid_date;
		 $info['data'] = $reqData; 
		 $info['debug']  = true;//exit();
		 $res = insert($info);exit();
		 if($res){
				header('location:?app=accounts_whole&cmd=OpeningDuesEnterSkin&msg=Successfully Saved');
			} 	         	   
			else
			{	
				header('location:?app=accounts_whole&cmd=OpeningDuesEnterSkin&msg=Not Saved');
			} 
 

}// EOF =====================

function EditOpeningDues(){
$whole_sales_accid = getRequest('whole_sales_accid');
 $customer_id = getRequest('customer_id');
 $project = getRequest('project');
 $dr_amount = getRequest('dr_amount');
 $particulars = getRequest('particulars');
 $bankid = getRequest('bankid');
 $paid_date = formatDate4insert(getRequest('paid_date'));
	
	$sql = "UPDATE	whole_saler_acc SET customer_id ='$customer_id', dr_amount ='$dr_amount', particulars ='$particulars',	
			paid_date = '$paid_date' WHERE whole_sales_accid = $whole_sales_accid";
	$res =mysql_query($sql);
	
		if($res){
			echo $Collection = $this->OpeningDuesFetch();
		}
	}
  
function DelOpeningDues(){
	$whole_sales_accid = getRequest('whole_sales_accid');
  	if($whole_sales_accid)  {
		 $sql = "DELETE from whole_saler_acc where whole_sales_accid = '$whole_sales_accid'";
		mysql_query($sql);	
		 
		if($res){
		header('location:?app=accounts_whole&cmd=OpeningDuesEnterSkin&msg=Successfully Deleted');
		}else{
		header('location:?app=accounts_whole&cmd=OpeningDuesEnterSkin&msg=Not Deleted');
		}
  	}	
}  



 function OpeningDuesFetch($start=null, $limit=null){
$searchq 	= getRequest('searchq');
$Page 	= getRequest('page');
$branch_id = getFromSession('branch_id');

if($Page){
 $sql="SELECT
	whole_sales_accid,
	pay_type,
	particulars,
	dr_amount,
	paid_date,
	name,
	person_name,
	w.customer_id,
	pay_type,
	project
FROM
	whole_saler_acc w inner join customer_info c on c.customer_id=w.customer_id
	inner join hrm_person p on p.person_id=w.person_id 
	where w.branch_id=$branch_id and ob_dues='YES' ORDER BY paid_date desc LIMIT $start, $limit";
	}
if(!$Page ){
  $sql="SELECT
	whole_sales_accid,
	pay_type,
	particulars,
	dr_amount,
	paid_date,
	name,
	person_name,
	w.customer_id,
	pay_type,
	project
FROM
	whole_saler_acc w inner join customer_info c on c.customer_id=w.customer_id
	inner join hrm_person p on p.person_id=w.person_id 
	where w.branch_id=$branch_id and ob_dues='YES' ORDER BY paid_date desc LIMIT 0, 29";	
	
	}
		//echo $sql;
		$res= mysql_query($sql);
	      $type = '<table width=900 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Received Date</th>
				 <th nowrap=nowrap align=left>Amount</th>
	             <th nowrap=nowrap align=left>Customer Name</th>
<!--	             <th nowrap=nowrap align=left>SR Name</th>
-->	             <th nowrap=nowrap align=left>Particular</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }


			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 

			onMouseOut="this.className=\''.$style.'\'" >
			<!--<td><a onClick="javascript:ajaxCall4EditCollect(\''.$row['whole_sales_accid'].'\',\''.
														  $row['customer_id'].'\',\''.
														  $row['dr_amount'].'\',\''.
														  $row['particulars'].'\',\''.
						                                  dateInputFormatDMY($row['paid_date']).'\');" title="Edit">
			<img src="images/common/edit.gif" style="border:none"></a></td>-->
			<td nowrap=nowrap><a href="?app=accounts_whole&cmd=DelOpeningDues&whole_sales_accid='.$row['whole_sales_accid'].'" 
			onclick="return confirmDelete();" ><span class="label label-danger">Delete</label></a></td>
			<td nowrap=nowrap>'.$row['dr_amount'].'&nbsp;</td>
			<td nowrap=nowrap>'._date($row['paid_date']).'&nbsp;</td>
			<td nowrap=nowrap>'.$row['name'].'&nbsp;</td>
<!--			<td nowrap=nowrap>'.$row['person_name'].'&nbsp;</td>
-->			<td nowrap=nowrap>'.$row['particulars'].'</td>
			</tr>';
		}
		$type .= '</table>';
		return $type;
}//end fnc


	 function EmployeeFetch($ele_id, $ele_lbl_id, $ele_lbl_id1){
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
				ORDER BY store_name ASC LIMIT 0, 30";
			}else{
		 		 $sql="SELECT customer_id,store_name from customer_info ORDER BY store_name ASC LIMIT 0, 30";
		 	}
		 //echo $sql;
	$res= mysql_query($sql);
	$Person = '<table width="350">';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
	
	
		$customer_id=$row['customer_id'];

	$sql2="SELECT sum(dr_amount-cr_amount) as Sabek_dues from whole_saler_acc where customer_id=$customer_id ";
	$res2= mysql_query($sql2);
	$row2=mysql_fetch_array($res2);
	$Sabek_dues=$row2['Sabek_dues'];

            if($rowcolor==0){
			$Person .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlightDivSearchCourse\'" 
				onMouseOut="this.className=\'oddClassStyle\'" onClick="javascript:addPersonId(\''.$row['customer_id'].'\',\''.$ele_id.'\',\''.$row['store_name'].'\',\''.$ele_lbl_id.'\',\''.$Sabek_dues.'\',\''.$ele_lbl_id1.'\');showHideDiv();" style="cursor:pointer" >
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;">'.$row['store_name'].'</td>
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;"><strong>'.$Sabek_dues.'</strong></td>
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$Person .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlightDivSearchCourse\'" 
				onMouseOut="this.className=\'evenClassStyle\'"  onClick="javascript:addPersonId(\''.$row['customer_id'].'\',\''.$ele_id.'\',\''.$row['store_name'].'\',\''.$ele_lbl_id.'\',\''.$Sabek_dues.'\',\''.$ele_lbl_id1.'\');showHideDiv();" style="cursor:pointer">
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;">'.$row['store_name'].'</td>
				<td nowrap="nowrap"  style="border-right:1px solid #cccccc;padding:2px; border:hidden;"><strong>'.$Sabek_dues.'</strong></td>
				</tr>';
			  $rowcolor=0;
			  }
	}
	$Person .= '</table>';
	
	return $Person;
         }//EOF


} // End class

?>