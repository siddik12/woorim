<?php
class inv_item_cat_main
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
 $main_item_category_name = getRequest('main_item_category_name');
 $createdby = getFromSession('username');
 $createddate = date('Y-m-d');
    	 $sql = "INSERT INTO ".INV_CATEGORY_MAIN_TBL."(main_item_category_name,  createdby, createddate) 
			values('$main_item_category_name',  '$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
			echo $Category = $this->ItemCategoryInfoFetch();
			}
  }

function EditCategory(){
 $main_item_category_id =getRequest('main_item_category_id');
 $main_item_category_name = getRequest('main_item_category_name');
 $createdby = getFromSession('username');
 $createddate = date('Y-m-d H:i:s');
 
  $sql = "UPDATE ".INV_CATEGORY_MAIN_TBL." set main_item_category_name='$main_item_category_name', createdby='$createdby', createddate='$createddate' where main_item_category_id = '$main_item_category_id' ";
	$res = mysql_query($sql);
	if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
	}
}  

function DeleteCategory(){
	$main_item_category_id = getRequest('main_item_category_id');
  	if($main_item_category_id)  {
		$sql = "DELETE from ".INV_CATEGORY_MAIN_TBL." where main_item_category_id = '$main_item_category_id'";
		$res = mysql_query($sql);	
		if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
		}
  	}	
}  


function ItemCategoryInfoFetch(){
$searchq 	= getRequest('searchq');
	$sql=" SELECT main_item_category_id, main_item_category_name,  createdby, createddate FROM ".INV_CATEGORY_MAIN_TBL." ";
	$WhereSrch = " main_item_category_name LIKE '%".$searchq."%' "; 
	$ordr = " ORDER BY main_item_category_id  LIMIT 0,30";
	
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
	             <th nowrap=nowrap align=left>Category Name(main)</th>
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
			ajaxCall4EditCatagory(\''.$row['main_item_category_id'].'\',\''.
					 				$row['main_item_category_name'].'\');">
			<td><a href="javascript:ajaxCall4DeleteCategory(\''.$row['main_item_category_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'&nbsp;</td>
			<td>'.$row['createdby'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
			</tr>';
		}
		$category .= '</table>';
		return $category;
}//end fnc


} // End class
?>
