<?php
class share_holder
{
   function run()
  {
     $cmd = getRequest('cmd');
      switch ($cmd)
      {
		 case 'ajaxInsertCategory'          : echo $this->InsertItemCategory();														 		break;
		 case 'ajaxSearchCategory' 			: echo $this->ItemCategoryInfoFetch();  						               					break;
		 case 'ajaxEditCategory'            : echo $this->EditCategory();												 					break;
		 case 'ajaxDeleteCategory'          : echo $this->DeleteCategory();												 					break;
         case 'list'                  		: $this->getList();                       											 			break;
         default                      		: $cmd == 'list'; $this->getList();	       											 			break;
      }
 }
function getList(){
 	 $Category = $this->ItemCategoryInfoFetch();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }




function InsertItemCategory(){
 $holder_name = getRequest('holder_name');
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d');
    	 $sql = "INSERT INTO ".SHARE_HOLDER_TBL."(holder_name, createdby, createddate) 
			values('$holder_name',  '$createdby', '$createddate')";//exit();
	$res = mysql_query($sql);
		if($res){
			echo $Category = $this->ItemCategoryInfoFetch();
			}
  }

function EditCategory(){
 $holder_id =getRequest('holder_id');
 $holder_name = getRequest('holder_name');
 $createdby = getFromSession('userid');
 $createddate = date('Y-m-d H:i:s');
 
  $sql = "UPDATE ".SHARE_HOLDER_TBL." set holder_name='$holder_name', createdby='$createdby', createddate='$createddate' where holder_id = '$holder_id' ";
	$res = mysql_query($sql);
	if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
	}
}  

function DeleteCategory(){
	$holder_id = getRequest('holder_id');
  	if($holder_id)  {
		$sql = "DELETE from ".SHARE_HOLDER_TBL." where holder_id = '$holder_id'";
		$res = mysql_query($sql);	
		if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
		}
  	}	
}  


function ItemCategoryInfoFetch(){
$searchq 	= getRequest('searchq');
	$sql=" SELECT holder_id, holder_name,  createdby, createddate FROM ".SHARE_HOLDER_TBL." ";
	$WhereSrch = " holder_name LIKE '%".$searchq."%' "; 
	$ordr = " ORDER BY holder_id  LIMIT 0,30";
	
	if($searchq){
			  $sql .= " where ".$WhereSrch.$ordr." ";
		}else{
				 $sql .= $ordr;
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $category = '<table width=600 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Share Holder</th>
				 <th nowrap=nowrap align=left>Created By</th>
				 <th nowrap=nowrap align=left>Created Date</th>
	       </tr>';
		$rowcolor=0;
		while($row = mysql_fetch_array($res)){
		 if($rowcolor==0){ 
				$style = "oddClassStyle";$rowcolor=1;
			 }else{
				$style = "evenClassStyle";$rowcolor=0;
			 }
			$category .='<tr  class="oddClassStyle" onMouseOver="this.className=\'highlight\'" 
			onMouseOut="this.className=\''.$style.'\'" onClick="javascript:
			ajaxCall4EditCatagory(\''.$row['holder_id'].'\',\''.
					 				$row['holder_name'].'\');">
			<td><a href="javascript:ajaxCall4DeleteCategory(\''.$row['holder_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['holder_name'].'&nbsp;</td>
			<td>'.$row['createdby'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
			</tr>';
		}
		$category .= '</table>';
		return $category;
}//end fnc


} // End class
?>
