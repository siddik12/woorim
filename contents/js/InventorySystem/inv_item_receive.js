var mode = 'add';

function validate_4ItemReceive()
{
	valid = true;
     if ( document.getElementById('Item_ID').value == "" )
        {
		        alert ( "Item name will not be empty" );
				Item_ID.focus();
                valid = false;
        }
		else if ( document.getElementById('unit_price').value == "" )
        {
		        alert ( "Unit will not be empty" );
				unit_price.focus();
                valid = false;
        }
		else if ( document.getElementById('issu_unit_cost').value == "" )
        {
		        alert ( "Sales will not be empty" );
				issu_unit_cost.focus();
                valid = false;
        }
		else if ( document.getElementById('totalpcs').value == "" )
        {
		        alert ( "Quantity will not be empty" );
				totalpcs.focus();
                valid = false;
        }
		else if ( document.getElementById('receive_date').value == "" )
        {
		        alert ( "Receive date will not be empty" );
				receive_date.focus();
                valid = false;
        }
		else if ( document.getElementById('challan_no').value == "" )
        {
		        alert ( "Challan no will not be empty" );
				challan_no.focus();
                valid = false;
        }
		else{

        return valid;
		}
}

//ajax add edit delete Batch Info---------------------------------------------------------------
function ajaxCall4ItemReceiveAddEdit(id){
	//alert(mode);
	if(mode == 'add'){
	var Item_ID = document.getElementById('Item_ID').value;	
	var unit_price = document.getElementById('unit_price').value;	
	var issu_unit_cost = document.getElementById('issu_unit_cost').value;	
	var totalpcs = document.getElementById('totalpcs').value;	
	var unit_value = document.getElementById('unit_value').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var remark = document.getElementById('remark').value;	
	var grm1 = document.getElementById('grm').value;	
	if(grm1==''){
		var grm = 0;
	}else{ var grm = grm1; }
	var cmd = "ajaxInsertItemReceive&Item_ID="+Item_ID+"&unit_price="+unit_price+"&issu_unit_cost="+issu_unit_cost+"&totalpcs="+totalpcs+"&unit_value="+unit_value+"&receive_date="+receive_date+"&challan_no="+challan_no+"&remark="+remark+"&grm="+grm;
	document.getElementById('Item_ID').value='';
	document.getElementById('unit_price').value='';
	document.getElementById('issu_unit_cost').value='';
	document.getElementById('totalpcs').value='';
	document.getElementById('speci_name_lbl').innerHTML='';
	document.getElementById('uidmsg').innerHTML = '';

	}
	if (mode == "edit"){
	var receive_dtlid = document.getElementById('receive_dtlid').value;	
	var mst_receiv_id = document.getElementById('mst_receiv_id').value;	
	var Item_ID = document.getElementById('Item_ID').value;	
	var unit_price = document.getElementById('unit_price').value;	
	var issu_unit_cost = document.getElementById('issu_unit_cost').value;	
	var totalpcs = document.getElementById('totalpcs').value;	
	var unit_value = document.getElementById('unit_value').value;	
	var receive_date = document.getElementById('receive_date').value;	
	var challan_no = document.getElementById('challan_no').value;	
	var remark = document.getElementById('remark').value;	
	var grm1 = document.getElementById('grm').value;	
	if(grm1==''){
		var grm = 0;
	}else{ var grm = grm1; }
	var cmd = "ajaxEditItemReceive&receive_dtlid="+receive_dtlid+"&mst_receiv_id="+mst_receiv_id+"&Item_ID="+Item_ID+"&unit_price="+unit_price+"&issu_unit_cost="+issu_unit_cost+"&totalpcs="+totalpcs+"&unit_value="+unit_value+"&receive_date="+receive_date+"&challan_no="+challan_no+"&remark="+remark+"&grm="+grm;
	document.getElementById('receive_dtlid').value='';
	document.getElementById('mst_receiv_id').value='';
	document.getElementById('challan_no').value='';
	document.getElementById('receive_date').value='';
	document.getElementById('Item_ID').value='';
	document.getElementById('unit_price').value='';
	document.getElementById('unit_value').value='';
	document.getElementById('remark').value='';
	document.getElementById('issu_unit_cost').value='';
	document.getElementById('totalpcs').value='';
	document.getElementById('speci_name_lbl').innerHTML='';
	document.getElementById('uidmsg').innerHTML = '';
	}
	if (mode == "del"){
	var cmd = "ajaxDeleteItemReceive&receive_dtlid="+id;
	}
	mode = 'add';
	document.getElementById('mode').value = mode;
	//alert(cmd);
	ajaxCall('inv_item',cmd,'ItemTypeDiv');

}

function ajaxCall4EditItemReceive(receive_dtlid, mst_receiv_id, Item_ID, Item_Name, totalpcs, unit_price,issu_unit_cost,challan_no,receive_date,remark,grm, unit_value){
	document.getElementById('receive_dtlid').value = receive_dtlid;
	document.getElementById('mst_receiv_id').value = mst_receiv_id;
	document.getElementById('Item_ID').value = Item_ID;
	document.getElementById('speci_name_lbl').innerHTML = Item_Name;
	document.getElementById('totalpcs').value = totalpcs;	
	document.getElementById('unit_price').value = unit_price;	
	document.getElementById('issu_unit_cost').value = issu_unit_cost;	
	document.getElementById('challan_no').value = challan_no;	
	document.getElementById('receive_date').value = receive_date;	
	document.getElementById('remark').value = remark;	
	document.getElementById('grm').value = grm;	
	document.getElementById('unit_value').value = unit_value;	
	mode = 'edit';
	document.getElementById('mode').value = mode;
	//ajaxCall4BatchInfoAddEdit();
}
function ajaxCall4DeleteItemReceive(id){
	//alert(id);
	mode = 'del';
	ajaxCall4ItemReceiveAddEdit(id);
}
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
function showItemCondense(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemCindense(id){
	var ele = document.getElementById(id);
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
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Cond&ele_id='+ele_id+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item',cmd,'ItemListCondense')
}
/// -------------------------------------------------end condense

//--------------------------- for Powder
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
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Powd&ele_id='+ele_id+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item',cmd,'ItemListPowder')
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
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Tea&ele_id='+ele_id+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	//alert(cmd);
	ajaxCall('inv_item',cmd,'ItemListTea')
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
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Bev&ele_id='+ele_id+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item',cmd,'ItemListBev')
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
	var uni_vel = 'unit_value';
	var grm_vel = 'grm';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Snak&ele_id='+ele_id+'&uni_vel='+uni_vel+'&grm_vel='+grm_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item',cmd,'ItemListSnak')
}
/// -------------------------------------------------end Beverage


//--------------------------- for Candy 
function showItemCandy(id){
	var ele = document.getElementById(id);
	//alert(ele);
	ele.style.visibility = 'visible';
}

function hideItemCandy(id){
	var ele = document.getElementById(id);
	ele.style.visibility = 'hidden';
}


function loadItemIdCand(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showItemCandy(id);
	//alert(id);
	var ele_id = 'Item_ID';
	var uni_vel = 'unit_value';
	var ele_lbl_id = 'speci_name_lbl';
	var cmd='index.php?app=inv_item&cmd=ajaxItemLoad4Candy&ele_id='+ele_id+'&uni_vel='+uni_vel+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_item',cmd,'ItemListCandy')
}
/// -------------------------------------------------end condense


function addItmId(Item_ID, ele_id,  unit_value, uni_vel, Item_Name, ele_lbl_id){
		 //alert(job_specialization_id+' - '+ele_id+' - '+job_specialization_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}

function addItmId4Pwder(Item_ID, ele_id,  unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}
function addItmId4Tea(Item_ID, ele_id,  unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}

function addItmId4Snak(Item_ID, ele_id,  unit_value, uni_vel, grm, grm_vel, Item_Name, ele_lbl_id){
		 //alert(Item_ID+' - '+ele_id+' - '+unit_value+' - '+uni_vel+' - '+grm+' - '+grm_vel+' - '+Item_Name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=Item_ID;
		 document.getElementById(uni_vel).value=unit_value;
		 document.getElementById(grm_vel).value=grm;
		 document.getElementById(ele_lbl_id).innerHTML=Item_Name;
}


function CleateGrmVal(){
document.getElementById('grm').value='';
}


