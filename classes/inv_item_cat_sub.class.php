<?php
class inv_item_cat_sub
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
	 $SelectMainCat = $this->SelectMainCategory();
	 $SelectMainCat2 = $this->SelectMainCategory2();
	 require_once(CURRENT_APP_SKIN_FILE); 
  }




function InsertItemCategory(){

 $sub_item_category_name = getRequest('sub_item_category_name');
 $main_item_category_id = getRequest('main_item_category_id');
 $createdby = getFromSession('username');
 $createddate = date('Y-m-d H:i:s');
    	 $sql = "INSERT INTO ".INV_CATEGORY_SUB_TBL."(sub_item_category_name,main_item_category_id,  createdby, createddate) 
			values('$sub_item_category_name','$main_item_category_id',  '$createdby', '$createddate')";
	$res = mysql_query($sql);
		if($res){
			echo $Category = $this->ItemCategoryInfoFetch();
			}
  }

function EditCategory(){
 $sub_item_category_id =getRequest('sub_item_category_id');
 $sub_item_category_name = getRequest('sub_item_category_name');
 $main_item_category_id = getRequest('main_item_category_id');
 $createdby = getFromSession('username');
 $createddate = date('Y-m-d H:i:s');
 
  $sql = "UPDATE ".INV_CATEGORY_SUB_TBL." set sub_item_category_name='$sub_item_category_name',main_item_category_id='$main_item_category_id', createdby='$createdby', createddate='$createddate' where sub_item_category_id = '$sub_item_category_id' ";
	$res = mysql_query($sql);
	if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
	}
}  

function DeleteCategory(){
	$sub_item_category_id = getRequest('sub_item_category_id');
  	if($sub_item_category_id)  {
		$sql = "DELETE from ".INV_CATEGORY_SUB_TBL." where sub_item_category_id = '$sub_item_category_id'";
		$res = mysql_query($sql);	
		if($res){
		echo $Category = $this->ItemCategoryInfoFetch();
		}
  	}	
}  


function ItemCategoryInfoFetch(){
$searchq 	= getRequest('searchq');
	 $sql=" SELECT sub_item_category_id, sub_item_category_name, s.main_item_category_id, main_item_category_name,s.createddate,s.createdby
	FROM ".INV_CATEGORY_SUB_TBL." s inner join ".INV_CATEGORY_MAIN_TBL." m on m.main_item_category_id=s.main_item_category_id ";
	$WhereSrch = " sub_item_category_name LIKE '%".$searchq."%' "; 
	$ordr = " ORDER BY sub_item_category_id  LIMIT 0,30";
	
	if($searchq){
			  $sql .= " where ".$WhereSrch.$ordr." ";
		}else{
				 $sql .= $ordr;
		}
		//echo $sql;
		$res= mysql_query($sql);
	      $category = '<table width=700 cellspacing=0 class="tableGrid" >
	            <tr>
				<th></th>
	             <th nowrap=nowrap align=left>Category Name(sub)</th>
	             <th nowrap=nowrap align=left>Category(main)</th>
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
			ajaxCall4EditCatagory(\''.$row['sub_item_category_id'].'\',\''.
					 				$row['main_item_category_id'].'\',\''.
									$row['sub_item_category_name'].'\');">
			<td><a href="javascript:ajaxCall4DeleteCategory(\''.$row['sub_item_category_id'].'\');" onclick="return confirmDelete();" title="Delete">
			<img src="images/common/delete.gif" style="border:none"></a></td>
			<td nowrap=nowrap>'.$row['sub_item_category_name'].'&nbsp;</td>
			<td nowrap=nowrap>'.$row['main_item_category_name'].'&nbsp;</td>
			<td>'.$row['createdby'].'&nbsp;</td>
			<td>'._date($row['createddate']).'&nbsp;</td>
			</tr>';
		}
		$category .= '</table>';
		return $category;
}//end fnc



function SelectMainCategory($mainitem = null){ 
		$sql="SELECT main_item_category_id, main_item_category_name  FROM ".INV_CATEGORY_MAIN_TBL." ";
	    $result = mysql_query($sql);
	     $Supplier_select = "<select name='main_item_category_id' size='1' id='main_item_category_id' class=\"textBox\" style='width:150px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['main_item_category_id'] == $mainitem){
			$Supplier_select .= "<option value='".$row['main_item_category_id']."' selected = 'selected'>".$row['main_item_category_name']."</option>";
			}
			else{
			$Supplier_select .= "<option value='".$row['main_item_category_id']."'>".$row['main_item_category_name']."</option>";	
			}
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }//EOF


function SelectMainCategory2($mainitem = null){ 
		$sql="SELECT main_item_category_id, main_item_category_name  FROM ".INV_CATEGORY_MAIN_TBL." ";
	    $result = mysql_query($sql);
	     $Supplier_select = "<select name='main_item_category_id1' size='1' id='main_item_category_id1' class=\"textBox\" style='width:150px'>";
		$Supplier_select .= "<option value=''>Select</option>"; 
			while($row = mysql_fetch_array($result)) {
			if($row['main_item_category_id'] == $mainitem){
			$Supplier_select .= "<option value='".$row['main_item_category_id']."' selected = 'selected'>".$row['main_item_category_name']."</option>";
			}
			else{
			$Supplier_select .= "<option value='".$row['main_item_category_id']."'>".$row['main_item_category_name']."</option>";	
			}
		}
		$Supplier_select .= "</select>";
		return $Supplier_select;
   }//EOF


} // End class
?>
