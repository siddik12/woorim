var mode = 'add';

function validate_4ItemIssue()
{
	valid = true;
     if ( document.getElementById('Item_ID').value == "" )
        {
		        alert ( "SKU name will not be empty" );
				Item_ID.focus();
                valid = false;
        }
		else if ( document.getElementById('slman_id').value == "" )
        {
		        alert ( "Sales man will not be empty" );
				slman_id.focus();
                valid = false;
        }
		else if ( document.getElementById('issue_qnt').value == "" )
        {
		        alert ( "Quantity will not be empty" );
				issue_qnt.focus();
                valid = false;
        }
		else if ( document.getElementById('issu_unit_cost').value == "" )
        {
		        alert ( "Issue price will not be empty" );
				issu_unit_cost.focus();
                valid = false;
        }
		else if ( document.getElementById('issue_date').value == "" )
        {
		        alert ( "Issue date will not be empty" );
				issue_date.focus();
                valid = false;
        }
		else{

        return valid;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4ItemIssueAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var Item_ID = document.getElementById('Item_ID').value;	
	var slman_id = document.getElementById('slman_id').value;	
	var issu_unit_cost = document.getElementById('issu_unit_cost').value;	
	var unit_value = document.getElementById('unit_value').value;	
	var issue_qnt = document.getElementById('issue_qnt').value;	
	var issue_date = document.getElementById('issue_date').value;	
	var free_qnt = document.getElementById('free_qnt').value;
	var total_comm = document.getElementById('total_comm').value;
	var grm1 = document.getElementById('grm').value;	
	var damage_qnt = document.getElementById('damage_qnt').value;
	var damage_price = document.getElementById('damage_price').value;
	if(grm1==''){
		var grm = 0;
	}else{ var grm = grm1; }

	var cmd = "ajaxInsertItemIssue&Item_ID="+Item_ID+"&slman_id="+slman_id+"&issu_unit_cost="+issu_unit_cost+"&unit_value="+unit_value+"&issue_qnt="+issue_qnt+"&issue_date="+issue_date+"&free_qnt="+free_qnt+"&total_comm="+total_comm+"&grm="+grm+"&damage_qnt="+damage_qnt+"&damage_price="+damage_price;
	document.getElementById('Item_ID').value='';
	document.getElementById('issue_qnt').value='';
	document.getElementById('free_qnt').value='';
	document.getElementById('total_comm').value='';
	document.getElementById('issu_unit_cost').value='';
	document.getElementById('damage_qnt').value='';
	document.getElementById('damage_price').value='';
	document.getElementById('unit_value').value='';
	document.getElementById('speci_name_lbl').innerHTML='';
	document.getElementById('free_qnt').value='0';
	document.getElementById('total_comm').value='0.00';
	document.getElementById('damage_qnt').value='0';
	document.getElementById('damage_price').value='0.00';
	document.getElementById('grm').value='';

}
	if (mode == "edit"){
	var issue_id = document.getElementById('issue_id').value;
	var issue_subid = document.getElementById('issue_subid').value;
	var issue_qnt = document.getElementById('issue_qnt').value;	
	var issu_unit_cost = document.getElementById('issu_unit_cost').value;	
	var free_qnt = document.getElementById('free_qnt').value;	
	var total_comm = document.getElementById('total_comm').value;	
	var Item_ID = document.getElementById('Item_ID').value;	
	var unit_value = document.getElementById('unit_value').value;	
	var slman_id = document.getElementById('slman_id').value;	
	var issue_date = document.getElementById('issue_date').value;
	var grm1 = document.getElementById('grm').value;	
	var damage_qnt = document.getElementById('damage_qnt').value;
	var damage_price = document.getElementById('damage_price').value;
	if(grm1==''){
		var grm = 0;
	}else{ var grm = grm1; }

	
	var cmd = "ajaxEditItemIssue&issue_id="+issue_id+"&issue_subid="+issue_subid+"&Item_ID="+Item_ID+"&slman_id="+slman_id+"&issu_unit_cost="+issu_unit_cost+"&unit_value="+unit_value+"&issue_qnt="+issue_qnt+"&issue_date="+issue_date+"&free_qnt="+free_qnt+"&total_comm="+total_comm+"&grm="+grm+"&damage_qnt="+damage_qnt+"&damage_price="+damage_price;
	document.getElementById('Item_ID').value='';
	document.getElementById('issue_qnt').value='';
	document.getElementById('free_qnt').value='';
	document.getElementById('total_comm').value='';
	document.getElementById('issu_unit_cost').value='';
	document.getElementById('damage_qnt').value='';
	document.getElementById('damage_price').value='';
	document.getElementById('unit_value').value='';
	document.getElementById('speci_name_lbl').innerHTML='';
	document.getElementById('free_qnt').value='0';
	document.getElementById('total_comm').value='0.00';
	document.getElementById('grm').value='';
	document.getElementById('issue_id').value='';
	document.getElementById('issue_subid').value='';
	document.getElementById('slman_id').value='';
	document.getElementById('damage_qnt').value='0';
	document.getElementById('damage_price').value='0.00';
	}

	if (mode == "del"){
	var cmd = "ajaxDeleteItemIssue&issue_subid="+id;
	}
	mode = 'add';
	//alert(cmd);
	document.getElementById('mode').value = mode;
	ajaxCall('inv_item_issue',cmd,'ItemTypeDiv');

}

function ajaxCall4EditItemIssue(issue_id, issue_subid, issue_qnt, issu_unit_cost, free_qnt, total_comm,damage_qnt, damage_price, Item_ID, Item_Name, unit_value, grm, name, slman_id, issue_date ){
	//alert(issue_id);
	document.getElementById('issue_id').value = issue_id;
	document.getElementById('issue_subid').value = issue_subid;
	document.getElementById('issue_qnt').value = issue_qnt;	
	document.getElementById('issu_unit_cost').value = issu_unit_cost;	
	document.getElementById('free_qnt').value = free_qnt;	
	document.getElementById('total_comm').value = total_comm;	
	document.getElementById('damage_qnt').value = damage_qnt;	
	document.getElementById('damage_price').value = damage_price;	
	document.getElementById('Item_ID').value = Item_ID;	
	document.getElementById('speci_name_lbl').innerHTML = Item_Name;
	document.getElementById('unit_value').value = unit_value;	
	document.getElementById('grm').value = grm;	
	document.getElementById('sls_name_lbl').innerHTML = name;
	document.getElementById('slman_id').value = slman_id;	
	document.getElementById('issue_date').value = issue_date;	
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteItemIssue(id){
	//alert(id);
	mode = 'del';
	ajaxCall4ItemIssueAddEdit(id);
}
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
//--------------------------- for condense ========================================================
function showItemCondense(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemCindense(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'hidden';
}


function loadItemIdCond(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemCondense(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Cond&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	//alert(cmd);
	ajaxCall('inv_item_issue',cmd,'ItemListCondense')
}
/// -------------------------------------------------end condense

//--------------------------- for Powder ===========================================================
function showItemPowder(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemPowder(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}


function loadItemIdPowder(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemPowder(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Powd&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item_issue',cmd,'ItemListPowder')
}
/// -------------------------------------------------end Powder

//--------------------------- for Tea
function showItemTea(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemTea(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}


function loadItemIdTea(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemTea(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Tea&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	//alert(cmd);
	ajaxCall('inv_item_issue',cmd,'ItemListTea')
}
/// -------------------------------------------------end Tea

//--------------------------- for Beverage
function showItemBev(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemBev(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}


function loadItemIdBev(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemBev(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Bev&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item_issue',cmd,'ItemListBev')
}
/// -------------------------------------------------end Beverage

//--------------------------- for Snack
function showItemSnak(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemSnak(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}


function loadItemIdSnak(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemSnak(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Snak&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item_issue',cmd,'ItemListSnak')
}
/// -------------------------------------------------end snacks

//--------------------------- for Candy ========================================================
function showItemCandy(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemCandy(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'hidden';
}


function loadItemIdCandy(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemCandy(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var sls = 'issu_unit_cost';
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxItemLoad4Candy&ele_id='+ele_id+'&sls='+sls+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	//alert(cmd);
	ajaxCall('inv_item_issue',cmd,'ItemListCandy')
}
/// -------------------------------------------------end Candy



function addItmId(Item_ID, ele_id, issu_unit_cost, sls, unit_value, uni_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(sls).value=issu_unit_cost;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}

function addItmId4Powder(Item_ID, ele_id, issu_unit_cost, sls, unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(sls).value=issu_unit_cost;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}


function addItmId4Tea(Item_ID, ele_id, issu_unit_cost, sls, unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(sls).value=issu_unit_cost;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}

function addItmId4Snak(Item_ID, ele_id, issu_unit_cost, sls, unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(sls).value=issu_unit_cost;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}

function CleateGrmVal(){
document.getElementById('grm').value='';
}

function loadSalsManId(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showElement(id);
	//alert(id);
	var ele_id = 'slman_id';
	var ele_lbl_id = 'sls_name_lbl';
	var cmd='index.php?app=inv_item_issue&cmd=ajaxSalesManLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item_issue',cmd,'SlsList')
}

function addSalsManId(slman_id, ele_id, name, ele_lbl_id){
		 //alert(job_specialization_id+' - '+ele_id+' - '+job_specialization_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=slman_id;
		 document.getElementById(ele_lbl_id).innerHTML=name;
}

function SetFreeUnitValue()
{
document.getElementById('free_unit').value='0';
//alert(abc);
}