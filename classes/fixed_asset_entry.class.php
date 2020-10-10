<?php
class fixed_asset_entry
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
		 $info['table'] = FIXED_ASSET_ENTRY_TBL;
		 $reqData =  getUserDataSet(FIXED_ASSET_ENTRY_TBL);
		 $reqData['expdate'] = formatDate4insert(getRequest('expdate'));
		 $info['data'] = $reqData;
		 $info['debug']  = false;
		 $res = insert($info);
		 return $res;
  }


function EditSalesmanInfo(){
		 
		 $asset_id = getRequest('asset_id');
	 	 $info				 = array();
		 $info['table']     	= FIXED_ASSET_ENTRY_TBL;
		 $reqData        		 = getUserDataSet(FIXED_ASSET_ENTRY_TBL);
		 $reqData['expdate'] = formatDate4insert(getRequest('expdate'));
		 $info['data'] = $reqData;
      	 $info['where']= "asset_id='$asset_id'";
		 //$info['debug']  = true;
		 return $res = update($info);
}  
   
function DeleteAsset(){
	$asset_id = getRequest('asset_id');
      if($asset_id)
      {
      	            	
      	$info = array();
      	$info['table'] = FIXED_ASSET_ENTRY_TBL;
      	$info['where'] = "asset_id='$asset_id'";
      	$info['debug'] = true;      	
      	$res = delete($info);
      	//dBug($res);
      	if($res)  	{   
			// echo 'deleted..';  	   
      	   header("location:?app=fixed_asset_entry&msg=Successfully Deleted");      	         	   
      	}      	
      	else
      	{ //echo 'not deleted..';  	
      		 header("location:?app=fixed_asset_entry&msg=Not Edited");      	   	
      	}      	
      }	
   }  

function SalesmanInfoFetch(){
	 $searchq =$_GET['searchq'];
	 $Page 	= getRequest('page');
		if($Page){
 	            $sql="SELECT asset_id,g.fixed_asset_id,asset_name,expdate,parti, dr FROM ".FIXED_ASSET_ENTRY_TBL." g inner join ".FIXED_ASSET_TBL." a
					 on a.fixed_asset_id = g.fixed_asset_id order by expdate desc LIMIT $start, $limit";
	}
	else{
	     
		 $sql="SELECT asset_id,g.fixed_asset_id,asset_name,expdate,parti, dr FROM ".FIXED_ASSET_ENTRY_TBL." g inner join ".FIXED_ASSET_TBL." a
					 on a.fixed_asset_id = g.fixed_asset_id order by expdate desc LIMIT 0, 29";
		 }
	$res= mysql_query($sql);
	$html = '<table  border=0 class="tableStyleClass"  >
					<tr>
					<th align="left" nowrap="nowrap">Date</th>
					<th align="left" nowrap="nowrap">Assets Name</th>
					<th align="left" nowrap="nowrap">parti</th>
					<th align="left" nowrap="nowrap">Dr.</th>
					<th align="left">Delete</th>
					<th align="left">Edit</th>
					</tr>';
                         $rowcolor=0;
	while($row=mysql_fetch_array($res)){
                             if($rowcolor==0){
				$html .= '<tr class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'oddClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['asset_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['parti'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=fixed_asset_entry&cmd=DeleteExpence&asset_id='.$row['asset_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateExpence(\''.$row['asset_id'].'\',
					\''.$row['fixed_asset_id'].'\',
					\''.$row['parti'].'\',
					\''.$row['dr'].'\',
					\''.dateInputFormatDMY($row['expdate']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
	
			  $rowcolor=1;
			  }
			  elseif($rowcolor==1){
				$html .= '<tr class="evenClassStyle" onMouseOver="this.className=\'highlight\'" 
					onMouseOut="this.className=\'evenClassStyle\'">
					
					<td   style="border-right:1px solid #cccccc;padding:2px;">'._date($row['expdate']).'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['asset_name'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.$row['parti'].'</td>
					<td   style="border-right:1px solid #cccccc;padding:2px;">'.number_format($row['dr'],2).'</td>
		   			<td  align="left" style="border-right:1px solid #cccccc;padding:2px;">
					<a href="?app=fixed_asset_entry&cmd=DeleteExpence&asset_id='.$row['asset_id'].'" onclick="return confirmDelete();" title="Delete">
		   			<span class="label label-danger">Delete</span></a></td>	  	
					<td  align="left"  style="border-right:1px solid #cccccc;padding:2px;">
					<a href="javascript:updateExpence(\''.$row['asset_id'].'\',
					\''.$row['fixed_asset_id'].'\',
					\''.$row['parti'].'\',
					\''.$row['dr'].'\',
					\''.dateInputFormatDMY($row['expdate']).'\');" title="Edit">
					<span class="label label-success">Edit</span></a></td>
					</tr>';
				
	
			  $rowcolor=0;
			  }
	}
	$html .= '</table>';
	
	return $html;
         }	 

function SelectEXpCht($exp= null){ 
		$sql="SELECT fixed_asset_id,asset_name FROM ".FIXED_ASSET_TBL."  ";
	    $result = mysql_query($sql);
	    $country_name_select = "<select name='fixed_asset_id' size='1' id='fixed_asset_id' style='width:150px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border:2px solid; border-color:#00CCFF' >";
		$country_name_select .= "<option value=''>select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['fixed_asset_id'] == $exp){
			$country_name_select .= "<option value='".$row['fixed_asset_id']."' selected = 'selected'>".$row['asset_name']."   </option>";
			}
			else{
			$country_name_select .= "<option value='".$row['fixed_asset_id']."'>".$row['asset_name']."</option>";	
			}
		}
		$country_name_select .= "</select>";
		return $country_name_select;
	}	


   
} // End class

?>