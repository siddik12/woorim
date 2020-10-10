<?php
class adjustment_entry
{
  
  function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
       	 case 'DeleteExpence'       	: $screen = $this->DeleteAsset();   					break;
        case 'list'                  	: $this->getList();                       					break;
         default                      	: $cmd == 'list'; $this->getList();	       					break;
      }
 }
 
 
function getList(){
     if ((getRequest('submit'))) {
	 	$msg = $this->SaveSalesmanInfo();
	 }
  	 $SalesmanInfoTab = $this->SalesmanInfoFetch();
	 $Exp = $this->SelectEXpCht();

	 require_once(CURRENT_APP_SKIN_FILE); 
  }
  
  function SaveSalesmanInfo(){
	 $mode = getRequest('mode');
	 if($mode == 'add'){
	 	$res = $this->addSalesmanInfo();
		 if($res['affected_rows']){
				$msg = 'Successfully saved !!!';
		 }else{
				$msg = 'UnSuccessfull!!!';
		 }
		 //header('location:?app=inv_supplierinfo');
	  }
	   if($mode == 'edit'){
	 	$res = $this->EditSalesmanInfo();
		 if($res){
				$msg = 'Successfully Edited !!!';
		 }else{
				$msg = 'Not Edit !!!';
		 }
	  }
		//dBug($res);
	return $msg;
  }
  
    
function addSalesmanInfo(){
  	 	 $info = array();
		 $info['table'] = ADJUSTMENT_ENTRY_TBL;
		 $reqData =  getUserDataSet(ADJUSTMENT_ENTRY_TBL);
		 $reqData['entry_date'] = formatDate4insert(getRequest('entry_date'));
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }


function EditSalesmanInfo(){
		 
		 $adjust_id = getRequest('adjust_id');
	 	 $info				 = array();
		 $info['table']     	= ADJUSTMENT_ENTRY_TBL;
		 $reqData        		 = getUserDataSet(ADJUSTMENT_ENTRY_TBL);
		 $reqData['entry_date'] = formatDate4insert(getRequest('entry_date'));
		 $info['data'] = $reqData;
      	 $info['where']= "adjust_id='$adjust_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteAsset(){
	$adjust_id = getRequest('adjust_id');
      if($adjust_id)
      {
      	            	
      	$info = array();
      	$info['table'] = ADJUSTMENT_ENTRY_TBL;
      	$info['where'] = "adjust_id='$adjust_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=adjustment_entry&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=adjustment_entry&msg=Not Edited");      	   	
      	}      	
      }	
   }  

function SalesmanInfoFetch(){
	 $searchq =$_GET['searchq'];
	 $Page 	= getRequest('page');
		if($Page){
 	            $sql="SELECT adjust_id,g.adjust_type_id,adjust_name,entry_date,particular,dr,cr FROM ".ADJUSTMENT_ENTRY_TBL." g inner join ".ADJUSTMENT_TYPE_TBL." a
					 on a.adjust_type_id = g.adjust_type_id order by entry_date desc LIMIT $start, $limit";
	}
	else{
	     
		 $sql="SELECT adjust_id,g.adjust_type_id,adjust_name,entry_date,particular,dr,cr FROM ".ADJUSTMENT_ENTRY_TBL." g inner join ".ADJUSTMENT_TYPE_TBL." a
					 on a.adjust_type_id = g.adjust_type_id order by entry_date desc LIMIT 0, 29";
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass">
					<tr>
					<th align="left" nowrap="nowrap">Date</th>
					<th align="left" nowrap="nowrap">Adjustment</th>
					<th align="left" nowrap="nowrap">particulars</th>
					<th align="left" nowrap="nowrap">Dr.</th>
					<th align="left" nowrap="nowrap">Cr.</th>
					<th align="left">Delete</th>
					<th align="left">Edit</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['entry_date']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['adjust_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particular'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=adjustment_entry&cmd=DeleteExpence&adjust_id='.$row['adjust_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateExpence(\''.$row['adjust_id'].'\',
					\''.$row['adjust_type_id'].'\',
					\''.$row['particular'].'\',
					\''.$row['dr'].'\',
					\''.$row['cr'].'\',
					\''.dateInputFormatDMY($row['entry_date']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['entry_date']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['adjust_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['particular'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['cr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=adjustment_entry&cmd=DeleteExpence&adjust_id='.$row['adjust_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateExpence(\''.$row['adjust_id'].'\',
					\''.$row['adjust_type_id'].'\',
					\''.$row['particular'].'\',
					\''.$row['dr'].'\',
					\''.$row['cr'].'\',
					\''.dateInputFormatDMY($row['entry_date']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
				
	
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
         }	 

function SelectEXpCht($exp= null){ 
		$sql="SELECT adjust_type_id,adjust_name FROM ".ADJUSTMENT_TYPE_TBL."  ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='adjust_type_id' size='1' id='adjust_type_id' style='width:150px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border:2px solid; border-color:#00CCFF' >";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['adjust_type_id'] == $exp){
			$country_name_select .= "<option value='".$row['adjust_type_id']."' selected = 'selected'>".$row['adjust_name']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['adjust_type_id']."'>".$row['adjust_name']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	


   
} // End class

?>