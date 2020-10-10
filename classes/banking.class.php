<?php
class banking 
{
  function run()
  {
    $cmd = getRequest('cmd');
		
      switch ($cmd)
      {
      	 
		 case 'save'               : $this->addBank();   			           	break;
		 case 'edit'               : $this->EditBank();      					break;
		 case 'tmpUpdate'               : $this->tmpUpdate();      					break;
      	 case 'delete'             : $screen = $this->DeleteBank();				break;
		 case 'ajaxSearch'         : echo $this->BankNameFetch();  				break;
		 
		 case 'saveBkTrans'        : $this->addBankTrans();   			        break;
		 case 'editBkTrans'        : $this->EditBankTrans();      				break;
      	 case 'deleteBkTrans'      : $screen = $this->DeleteBankTrans();		break;
		 case 'BankTransFetchSkin' : $this->BankTransFetchSkin();      			break;
		 case 'ajaxSearchBankTrans': echo $this->BankTransFetch();  			break;
         
		 case 'list'               : $this->getList();   						break; 
         default                   : $cmd == 'list'; $this->getList();			break;
      }
 }
   
   function getList(){
	$BankNameFetch = $this->BankNameFetch();
	$ListFetch = $this->ListFetch();

	 require_once(CURRENT_APP_SKIN_FILE); 
	 
  }
  

  function addBank(){

  		 require_once(BANK_ADD_SKIN);
		 
 	 if ((getRequest('submit'))) {
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = BANK_INFO_TBL;
		 $reqData = getUserDataSet(BANK_INFO_TBL);
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
			 if($res){
				header("location:?app=banking&cmd=save&msg=Successfully Saved");
					} 	         	   
      	 		else
					{	
      				header("location:index.php?app=banking&cmd=save&msg=Not Saved");
      	   	
      	 			} 
	}  // end submit if
 }



  
function EditBank(){
  			$bankid = getRequest('bankid');
 	           $sql='SELECT
						bankid,
						bank_name,
						branch_name,
						account_name,
						account_no,
						bnk_address,
						phone
					FROM
						'.BANK_INFO_TBL.' where bankid='.$bankid;
		
	   	        $ros = mysql_query($sql);
			
		$res = mysql_fetch_array($ros);
				$bankid			= $res['bankid'];
				$bank_name		= $res['bank_name'];
				$branch_name	= $res['branch_name'];
				$account_name	= $res['account_name'];
				$account_no		= $res['account_no'];
				$bnk_address	= $res['bnk_address'];
				$phone			= $res['phone'];
	 require_once(BANK_EDIT_SKIN); 
	
	 if((getRequest('submit'))){

  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = BANK_INFO_TBL;
		 $reqData = getUserDataSet(BANK_INFO_TBL);
		 $info['data'] = $reqData; 
      	 $info['where']= 'bankid='.$bankid;
		 $info['debug']  = true;
		 $res = update($info);
		 if($res)  	{   
      	  header("location:?app=banking&EDmsg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=banking&cmd=edit&EDmsg=Not Edit");
      	   	
      	} 
  }// end submit if 
 }
  
function DeleteBank(){
	 $id = getRequest('bankid');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = BANK_INFO_TBL;
      	$info['where'] = "bankid=".$id;
      	$info['debug'] = true;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:index.php?app=banking&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		header("location:index.php?app=banking&msg=Not Deleted");      	   	
      	}      	
   }
 }  

 
//---------------------------------- Personal List Fetch -------------------------------------------
function BankNameFetch(){
   		$searchq =$_GET['searchq'];
		if($searchq){
 	           $sql='SELECT
						bankid,
						bank_name,
						branch_name,
						account_name,
						account_no,
						bnk_address,
						phone
					FROM
						'.BANK_INFO_TBL.' bank_name LIKE "%'.$searchq.'%" or account_no LIKE "%'.$searchq.'%" 
						ORDER BY bankid desc';
			  }else{
 	           $sql='SELECT
						bankid,
						bank_name,
						branch_name,
						account_name,
						account_no,
						bnk_address,
						phone
					FROM
						'.BANK_INFO_TBL.' ';
				}
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width="100%" border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	            <th  align="left">#</th>
	            <th  align="left">Bank Name</th>
	            <th  align="left">Branch</th>
				<th  align="left">Account name</th>
				<th  align="left">Account no</th>
	            <th  align="left">Phone</th>
	           	<th align="left">Address</th>
                <th colspan=2  align="center" nowrap>options</th>
           </tr>';
                          $i=1;
						  $rowcolor=0;
	while($row = mysql_fetch_array($res)){ 
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['bank_name'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_no'].'&nbsp;</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['phone'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['bnk_address'].'&nbsp;</td>

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="javascript:deleteBank(\''.$row['bankid'].'\')" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBank(\''.$row['bankid'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['bank_name'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_no'].'&nbsp;</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['phone'].'&nbsp;</td>
					<td  style="border-right:1px solid #cccccc;padding:2px;">'.$row['bnk_address'].'&nbsp;</td>

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="javascript:deleteBank(\''.$row['bankid'].'\')" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBank(\''.$row['bankid'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>
				</tr>';
	
			  $rowcolor=0;
			  } 
	$i++; }
	$html .= '</table>';
	
	return $html;
	
 }

/************************************************** Function for  Bank dropdown list  ************************************
********************************************************************************************************************/	
function SelectBankList($bankid=null){ 
		$sql="SELECT bankid, bank_name, account_no FROM ".BANK_INFO_TBL." ";
	    $result = mysql_query($sql);
	    $department_select = "<select name='bankid' size='1' id='bankid' style='width:200px' class=\"validate[required]\">";
		$department_select .= "<option value=''>Select</option>"; 
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


  function addBankTrans(){
	$SelectBankList = $this->SelectBankList();
  		 require_once(BANK_TRANS_ADD_SKIN);
		 
 	 if ((getRequest('submit'))) {
	 
  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = BANK_TRANS_TBL;
		 $reqData = getUserDataSet(BANK_TRANS_TBL);
		 $reqData['trans_date'] = formatDate4insert(getRequest('trans_date'));
	 		if(getRequest('transtyp')=='Deposit'){
		 		$reqData['deposit'] = getRequest('amount');
			}
	 		if(getRequest('transtyp')=='Withdraw'){
		 		$reqData['withdrawal'] = getRequest('amount');
			}
		 $reqData['transtyp'] = getRequest('transtyp');
		 $info['data'] = $reqData; 
		 $info['debug']  = true;
		 $res = insert($info);
			 if($res){
				header("location:?app=banking&cmd=saveBkTrans&msg=Successfully Saved");
					} 	         	   
      	 		else
					{	
      				header("location:index.php?app=banking&cmd=saveBkTrans&msg=Not Saved");
      	   	
      	 			} 
	}  // end submit if
 }


  
function EditBankTrans(){
  			$bnktransid = getRequest('bnktransid');
 	           $sql='SELECT
						bnktransid,
						bankid,
						deposit,
						withdrawal,
						descrip,
						transtyp,
						trans_date,
						branch_id
					FROM
						'.BANK_TRANS_TBL.' where bnktransid='.$bnktransid;
		
	   	        $ros = mysql_query($sql);
			
				$res = mysql_fetch_array($ros);
				$bnktransid			= $res['bnktransid'];
				$bankid		= $res['bankid'];
				$deposit	= $res['deposit'];
				$withdrawal	= $res['withdrawal'];
				$transtyp	= $res['transtyp'];
				$descrip		= $res['descrip'];
				$branch_id		= $res['branch_id'];
				$trans_date	= dateInputFormatDMY($res['trans_date']);
				
				if($transtyp=='Deposit'){ $amount = $deposit; }
				if($transtyp=='Withdraw'){ $amount = $withdrawal; }

				$SelectBankList = $this->SelectBankList($bankid);
				$SelectBranch=$this->SelectBranch($branch_id);
	 require_once(BANK_TRANS_EDIT_SKIN); 
	
	 if((getRequest('submit'))){

  	 	 $info = array();
		 $reqData = array();
 		 $info['table'] = BANK_TRANS_TBL;
		 $reqData = getUserDataSet(BANK_TRANS_TBL);
		 $reqData['trans_date'] = formatDate4insert(getRequest('trans_date'));
		 
	 		if(getRequest('transtyp')=='Deposit'){
		 		$reqData['deposit'] = getRequest('amount');
			}
	 		if(getRequest('transtyp')=='Withdraw'){
		 		$reqData['withdrawal'] = getRequest('amount');
			}
		 $reqData['transtyp'] = getRequest('transtyp');
		 $info['data'] = $reqData; 
      	 $info['where']= 'bnktransid='.$bnktransid;
		 $info['debug']  = true;//exit();
		 $res = update($info);
		 if($res)  	{   
      	  header("location:?app=banking&cmd=BankTransFetchSkin&EDmsg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=banking&cmd=BankTransFetchSkin&EDmsg=Not Edit");
      	   	
      	} 
  }// end submit if 
 }
  
function DeleteBankTrans(){
	 $id = getRequest('bnktransid');
      if($id)
      {
      	            	
      	$info = array();
      	$info['table'] = BANK_TRANS_TBL;
      	$info['where'] = "bnktransid=".$id;
      	$info['debug'] = true;      	
      	$res = delete($info);
      	dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=banking&cmd=BankTransFetchSkin&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		header("location:?app=banking&cmd=BankTransFetchSkin&msg=Not Deleted");      	   	
      	}      	
   }
 }  


/*SELECT
						bnktransid,
						bi.account_name,
						bi.account_no,
						bi.bank_name,
						bi.branch_name,
						transtyp,
						deposit,
						withdrawal,
						descrip,
						trans_date,
						company_name,
						@rt := @rt +(deposit-withdrawal) as balance
					FROM
						'.BANK_TRANS_TBL.' b inner join '.BANK_INFO_TBL.' bi
						on bi.bankid=b.bankid, (SELECT @rt := 0 ) as tempName 
						
						ORDER BY bnktransid*/
function BankTransFetch(){
$branch_id = getFromSession('branch_id');
   		$searchq =$_GET['searchq'];

		if($searchq){
 	           $sql='SELECT
						bnktransid,
						bi.account_name,
						bi.account_no,
						bi.bank_name,
						bi.branch_name,
						deposit,
						withdrawal,
						transtyp,
						descrip,
						trans_date,
						company_name,
						c.account_name
					FROM
						'.BANK_TRANS_TBL.' b inner join '.BANK_INFO_TBL.' bi 
						on bi.bankid=b.bankid left outer join '.INV_SUPPLIER_INFO_TBL.' s on s.supplier_id=b.supplier_id
						left outer join accounts_chart c on c.chart_id=b.chart_id
						Where bank_name LIKE "%'.$searchq.'%" or account_no LIKE "%'.$searchq.'%"
						or trans_date LIKE "%'.$searchq.'%"	ORDER BY trans_date DESC';
			  }else{
 	           $sql='SELECT
						bnktransid,
						bi.account_name,
						bi.account_no,
						bi.bank_name,
						bi.branch_name,
						deposit,
						withdrawal,
						transtyp,
						descrip,
						trans_date,
						company_name,
						c.account_name
					FROM
						'.BANK_TRANS_TBL.' b inner join '.BANK_INFO_TBL.' bi 
						on bi.bankid=b.bankid left outer join '.INV_SUPPLIER_INFO_TBL.' s on s.supplier_id=b.supplier_id
						left outer join accounts_chart c on c.chart_id=b.chart_id						
						ORDER BY trans_date DESC';
				}
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width=100% border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	           	<th align="left">Date</th>
				<th  align="left">Account name</th>
				<th  align="left">Account no</th>
				<th  align="left">Transaction</th>
	            <th  align="left">Bank Name</th>
	            <th  align="left">Branch</th>
	            <th  align="left">Desc</th>
	           	<th align="left">Deposit</th>
	           	<th align="left">withdrawal</th>
           		<!--<th align="left">Company</th>-->
                <th align="center" nowrap>options</th>
           </tr>';
                          $i=1;
						  $rowcolor=0;
	while($row = mysql_fetch_array($res)){ 
	$bnktransid=$row['bnktransid'];
	$bnktransid=$row['bnktransid'];
	$bnktransid=$row['bnktransid'];
					
                             if($rowcolor==0){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td style="border-right:1px solid #cccccc;padding:2px;">'._date($row['trans_date']).'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_no'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['transtyp'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['bank_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">Paid To - '.$row['company_name'].'&nbsp;'.$row['account_name'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['deposit'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['withdrawal'].'&nbsp;</td>
<!--					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['balance'].'&nbsp;</td>
-->					<!--<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['company_name'].'&nbsp;</td>-->

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="?app=banking&cmd=deleteBkTrans&bnktransid='.$row['bnktransid'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<!--<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBankTrans(\''.$row['bnktransid'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>-->
				</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .='<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					                onMouseOut="this.className=\'oddClassStyle\'">
					
					<td style="border-right:1px solid #cccccc;padding:2px;">'._date($row['trans_date']).'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['account_no'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['transtyp'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['bank_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['branch_name'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">Paid To - '.$row['company_name'].'&nbsp;'.$row['account_name'].'</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['deposit'].'&nbsp;</td>
					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['withdrawal'].'&nbsp;</td>
<!--					<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['balance'].'&nbsp;</td>
-->					<!--<td style="border-right:1px solid #cccccc;padding:2px;">'.$row['company_name'].'&nbsp;</td>-->

					<td width="20" align="left" onMouseOver="this.className=\'delete\'"
					 onMouseOut="this.className=\'oddClassStyle\'"style="border-right:1px solid #cccccc;padding:2px;">					
					<a href="?app=banking&cmd=deleteBkTrans&bnktransid='.$row['bnktransid'].'" onclick="return confirmDelete();" title="Delete">
		   			<img src="images/common/delete.gif" style="border:none"></a></td>  	
					<!--<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateBankTrans(\''.$row['bnktransid'].'\');" title="Edit">
					         <img src="images/common/edit.gif" style="border:none" ></a></td>-->
				</tr>';
	
			  $rowcolor=0;
			  } 
	$i++; }
	$html .= '</table>';
	
	return $html;
	
 }

function BankTransFetchSkin(){
$BankTransFetch=$this->BankTransFetch();
require_once(BANK_TRANS_LIST_SKIN);
}

/*function SelectBranch($brnc = null){ 
		 $sql="SELECT company_name FROM company_name ";
	    $result = mysql_query($sql);
	    $department_select = '<select name="company_name"  id="company_name" style=\'width:200px\' class="validate[required]">';
		//$department_select .= "<option value=''>Select Branch</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['company_name'] == $brnc){
			$department_select .= "<option value='".$row['company_name']."' selected = 'selected'>".$row['company_name']."</option>";
			}
			else{
			$department_select .= "<option value='".$row['company_name']."'>".$row['company_name']."</option>";	
			}
		}
		$department_select .= "</select>";
		return $department_select;
}	
*/

function ListFetch(){
   		
 	           $sql="SELECT
						t.sale_pay_id,
						discountTmp
					FROM view_tmp t inner join inv_item_sales s on s.sale_pay_id=t.sale_pay_id where ontm='0' and discountTmp!='0.000000' ";
				
	$res= mysql_query($sql);
	if(!$res){
		die('Invalid query: ' . mysql_error());
	}
	$html = '<table width="100%" border=0 cellspacing=0 class="tableGrid">
	            <tr> 
	            <th  align="left">#</th>
	            <th  align="left">id</th>
	            <th  align="left">dis</th>
                <th align="center" nowrap>options</th>
           </tr>';
                          $i=1;
						 
	while($row = mysql_fetch_array($res)){ 
					

				$html .='<tr>
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$i.'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['sale_pay_id'].'&nbsp;</td>
					<td nowrap="nowrap" style="border-right:1px solid #cccccc;padding:2px;">'.$row['discountTmp'].'&nbsp;</td>

					<td width="20" align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=banking&cmd=tmpUpdate&sale_pay_id='.$row['sale_pay_id'].'&discountTmp='.$row['discountTmp'].'" title="Edit"><img src="images/common/edit.gif" style="border:none" ></a></td>
				</tr>';
	 
	$i++; }
	$html .= '</table>';
	
	return $html;
	
 }
 
 function tmpUpdate(){
 $sale_pay_id = getRequest('sale_pay_id');
 $discountTmp = getRequest('discountTmp');

echo $sql="UPDATE
	inv_item_sales
SET	totaldiscount ='$discountTmp',ontm='1' WHERE sale_pay_id='$sale_pay_id' "; //exit();
$res= mysql_query($sql);
 if($res){   
      	  header("location:?app=banking&EDmsg=Successfully Edited");      	         	   
      	}      	
      	else
      	{	
      		header("location:?app=banking&EDmsg=Not Edit");
      	   	
      	} 
 }

} // End class

?>