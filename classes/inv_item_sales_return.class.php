<?php
class inv_item_sales_return
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
	 
		 case 'SalesReturnSaveSkin' 		:  $this->SalesReturnSaveSkin();					break;
		 case 'SalesReturnListView' 		:  $this->SalesReturnListView();					break;
		 case 'SalesReturnSave' 		:  $this->SalesReturnSave();					break;
		 case 'ajaxModel'	   					: $this->SelectModel(); 				break;
		 case 'list'                : $this->getList();                       			break;
		 default                    : $cmd == 'list'; $this->getList();	       			break;
      }
 }
 
 
function getList(){
$SelectSalesMan=$this->SelectSalesMan();
$Customer = $this->SelectCustomer();
//$ItemIssue = $this->ItemIssueFetch();
$ItmCategory4Model=$this->ItmCategory4Model();
$SelectModel=$this->SelectModel();
	
	require_once(CURRENT_APP_SKIN_FILE);
  }


 function SelectCustomer($cust = null){ 
 $branch_id = getFromSession('branch_id');
		$sql="SELECT customer_id,name from customer_info where customer_type=1 and branch_id=".getFromSession('branch_id')." ORDER BY name";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='customer_id' size='1' id='customer_id' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['customer_id'] == $cust){
			$branch_select .= "<option value='".$row['customer_id']."' selected = 'selected'>".$row['name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['customer_id']."'>".$row['name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
	} // EOF

function SelectSalesMan(){ 

 $branch_id = getFromSession('branch_id');
		$sql="SELECT person_name, e.person_id FROM hrm_employee e inner join hrm_person p on p.person_id=e.person_id 
		where e.branch_id=$branch_id";
	    $result = mysql_query($sql);
	    $branch_select = "<select name='person_id' size='1' id='person_id' style='width:150px' class=\"textBox\">";
		$branch_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['person_id'] == $cust){
			$branch_select .= "<option value='".$row['person_id']."' selected = 'selected'>".$row['person_name']."</option>";
			}
			else{
			$branch_select .= "<option value='".$row['person_id']."'>".$row['person_name']."</option>";	
			}
		}
		$branch_select .= "</select>";
		return $branch_select;
}	

function ItmCategory4Model($cate = null){ 
$branch_id = getFromSession('branch_id');
		$sql="SELECT i.main_item_category_id, main_item_category_name FROM inv_iteminfo i inner join inv_item_category_main m 
		on m.main_item_category_id=i.main_item_category_id where branch_id=$branch_id group by i. main_item_category_id ORDER BY i. main_item_category_id";
	    $result = mysql_query($sql);
		$country_select = "<select name='main_item_category_id' size='1' id='main_item_category_id' class=\"textBox\" style='width:150px;' onchange=\"getModelId(this.value)\">";
		$country_select .= "<option value=''>Select</option>";
		while($row = mysql_fetch_array($result)) {
			if($row['main_item_category_id'] == $cate){
					   $country_select .= "<option value='".$row['main_item_category_id']."' selected='selected'>".$row['main_item_category_name'].
					   "</option>";
					   }else{
			     $country_select .= "<option value='".$row['main_item_category_id']."'>".$row['main_item_category_name']."</option>";
				 }
		   }
		$country_select .= "</select>";
		return $country_select;
  }


function SelectModel(){ 
$main_item_category_id=getRequest('main_item_category_id');

		 $sql="SELECT i.sub_item_category_id,sub_item_category_name FROM inv_iteminfo i inner join inv_item_category_sub s on i.sub_item_category_id=s.sub_item_category_id
		  where i.main_item_category_id=$main_item_category_id group by i.sub_item_category_id order by sub_item_category_name";
	    $result = mysql_query($sql);
			while($row = mysql_fetch_array($result)) {

	echo '<label style="font-weight:normal"><input name="sub_item_category_id" id="sub_item_category_id" type="radio" value="'.$row['sub_item_category_id'].'" />'.$row['sub_item_category_name'].'</label><br>';	
		}

}

function SalesReturnListView(){
$ItemIssue = $this->SalesReturnListViewFetch();
	require_once(SALES_RETURN_LIST_VIEW);
  }


function SalesReturnListViewFetch(){
$person_id=getRequest('person_id');
$customer_id=getRequest('customer_id');
$sub_item_category_id = getRequest('sub_item_category_id');
$branch_id = getFromSession('branch_id');

if($customer_id && $person_id && $sub_item_category_id){
	 $sql="select 
			sales_id,
			s.item_id,
			sub_item_category_name,
			main_item_category_name,
			costprice,
			salesprice,
			sales_date,
			item_qnt,
			return_qnt,
			total_amount,
			return_amount,
			discount_percent,
			totaldiscount,
			s.person_id,
			(total_amount - return_amount) AS totalAmount,
			(item_qnt -return_qnt) AS net_qnt,
			s.customer_id,
			name,
			s.sale_pay_id,
			s.branch_id,
			sales_status,
			sales_type,
			invoice as memo_no,
			i.sub_item_category_id,
			sb.main_item_category_id 
			from inv_item_sales s 
			inner join inv_iteminfo i on s.item_id = i.item_id 
			inner join inv_item_category_sub sb on sb.sub_item_category_id = i.sub_item_category_id 
			inner join inv_item_category_main m on m.main_item_category_id = sb.main_item_category_id 
			left join customer_info c on c.customer_id = s.customer_id 
			where sales_status='Not Pending' and s.customer_id='$customer_id' and i.sub_item_category_id=$sub_item_category_id
			and s.branch_id=$branch_id and sales_type=1 order by sales_date DESC";
		}

		$res= mysql_query($sql);
	      $type = '<table  cellspacing=0 class="tableGrid" width="100%">
	            <tr>
				<th align=left>SL.</th>
				<th align=left>Date</th>
				 <th nowrap=nowrap align=left>Brand</th>
	             <th nowrap=nowrap align=left>Model</th>
	             <th nowrap=nowrap align=left>Sales Price</th>
				 <th nowrap=nowrap align=left>Sales Qnt.</th>
				 <th nowrap=nowrap align=left>Ret Qnt</th>
				 <th nowrap=nowrap align=left>Net Qnt</th>
				 <th nowrap=nowrap align=left>Dis</th>
				 <th nowrap=nowrap align=left>Total</th>
				 <th nowrap=nowrap align=left>Ret. Amount</th>
				 <th nowrap=nowrap align=left>Net Amount</th>
				 <th nowrap=nowrap align=left>Invoice</th>
				 <th nowrap=nowrap align=left>Client</th>
				 <th nowrap=nowrap align=right>&nbsp;</th>
				
	       </tr>';
		$rowcolor=0;
		$i=1;
		while($row = mysql_fetch_array($res)){

	$sale_pay_id=$row['sale_pay_id'];
	$sales_id=$row['sales_id'];
	$main_item_category_id=$row['main_item_category_id'];
	$net_qnt=$row['net_qnt'];
	$totalAmount=$row['totalAmount'];
	$customer_id=$row['customer_id'];
	$totaldiscount=$row['totaldiscount'];
	$total_amount=$row['total_amount'];
	$return_amount=$row['return_amount'];
	$TotalAmount=($total_amount-$totaldiscount);
	$item_qnt=$row['item_qnt'];
	$return_qnt=$row['return_qnt'];
	$netQnt=($item_qnt-$return_qnt);
	$netAmnt=($TotalAmount-$return_amount);
	
		 
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			
			$type .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'">	
			<td nowrap=nowrap>'.$i.'</td>
			<td nowrap=nowrap>'._date($row['sales_date']).'</td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'</td>
			<td nowrap=nowrap>'.$row['salesprice'].'</td>
			<td nowrap=nowrap>'.$row['item_qnt'].'</td>
			<td nowrap=nowrap>'.$row['return_qnt'].'</td>
			<td nowrap=nowrap>'.$netQnt.'</td>
			<td nowrap=nowrap>'.$totaldiscount.'</td>
			<td nowrap=nowrap>'.$TotalAmount.'</td>
			<td nowrap=nowrap>'.$row['return_amount'].'</td>
			<td nowrap=nowrap>'.$netAmnt.'</td>
			<td nowrap=nowrap><a href="?app=inv_item_sales&cmd=ajaxInvoicePrintViewSkin&invoice='.$row['memo_no'].'">'.$row['memo_no'].'</td>
			<td nowrap=nowrap>'.$row['name'].'</td>
			<td nowrap=nowrap> 
			<a href="?app=inv_item_sales_return&cmd=SalesReturnSaveSkin&sales_id='.$row['sales_id'].'&sub_item_category_id='.$row['sub_item_category_id'].'&sale_pay_id='.$row['sale_pay_id'].'&customer_id='.$row['customer_id'].'&totalAmount='.$row['totalAmount'].'&net_qnt='.$row['net_qnt'].'&sub_item_category_name='.$row['sub_item_category_name'].'&sub_item_category_name='.$row['sub_item_category_name'].'"><span class="label label-danger" style="font-size:10px;">Return</label></a></td>
			</tr>';
		$i++; }
		$type .= '</table>';
		return $type;
}

function SalesReturnSaveSkin(){
$sales_id=getRequest('sales_id');
$customer_id=getRequest('customer_id');

	$sql="SELECT total_amount,totaldiscount,salesprice,item_qnt,sale_pay_id,return_amount,return_qnt,sub_item_category_name,s.sub_item_category_id,invoice
	FROM inv_item_sales s inner join inv_item_category_sub c on c.sub_item_category_id=s.sub_item_category_id where sales_id='$sales_id'";
	$res= mysql_query($sql);
	$row = mysql_fetch_array($res);
	$total_amount=$row['total_amount'];
	$invoice=$row['invoice'];
	$salesprice=$row['salesprice'];
	$item_qnt=$row['item_qnt'];
	$return_qnt=$row['return_qnt'];
	$return_amount=$row['return_amount'];
	$sale_pay_id=$row['sale_pay_id'];
	$sub_item_category_id=$row['sub_item_category_id'];
	$sub_item_category_name=$row['sub_item_category_name'];
	$totaldiscount=$row['totaldiscount'];
	$NetAmount=($total_amount-$totaldiscount);

	$sql2="SELECT name FROM customer_info where customer_id='$customer_id'";
	$res2= mysql_query($sql2);
	$row2 = mysql_fetch_array($res2);
	$company_name=$row2['name'];

	require_once(INV_ITEM_SALES_RETURN_SAVE_SKIN);
  }

function SalesReturnSave(){
 $customer_id = getRequest('customer_id');
 $sub_item_category_id = getRequest('sub_item_category_id');
 $salesprice = getRequest('salesprice');
 $sales_id = getRequest('sales_id');
 $sale_pay_id=getRequest('sale_pay_id');
 $quantity=getRequest('quantity');
 $total_amount=getRequest('total_amount');
 $invoice=getRequest('invoice');

// $sals_adjust=getRequest('sals_adjust');
 //$cash_return=getRequest('cash_return');
 
 $return_amount=($salesprice*$quantity);
 
 $branch_id = getFromSession('branch_id');
 $person_id = getFromSession('person_id');
 $return_date=date('Y-m-d');
 
			$sql = "UPDATE inv_item_sales SET return_qnt = '$quantity', return_amount = '$return_amount'
			WHERE sales_id = $sales_id";//exit();
			$res = mysql_query($sql);
			
			 $sql3 = "UPDATE inv_item_sales_payment SET ret_amount = '$return_amount' WHERE sale_pay_id = $sale_pay_id";//exit();
			 $res3 = mysql_query($sql3);

				if($res3)
				{
					$sqlSup = "INSERT INTO whole_saler_acc(customer_id,particulars,cr_amount,return_amount,paid_date,branch_id,pay_type,stat) 
					values('$customer_id','Item Return Adjustment',$return_amount,$return_amount,'$return_date','$branch_id','Cash','Return')";
					mysql_query($sqlSup);

				header("location:?app=inv_item_sales_list&cmd=SalesList2Skin&sub_item_category_id=$sub_item_category_id&customer_id=$customer_id&msg=Return Success!!");
				}else{
				header('location:?app=inv_item_sales_list&cmd=SalesList2Skin&sub_item_category_id=$sub_item_category_id&customer_id=$customer_id&msg=Not Returned');
				}
	
}

} // End class
?>