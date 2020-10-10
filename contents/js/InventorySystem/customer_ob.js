function loadPerson(){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showHideDiv();
	//alert(id);
	  
		var ele_id = 'customer_id';
		var ele_lbl_id = 'lbl_PersonName';
	   
	var url='index.php?app=customer_ob&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	//alert(url);
	ajax.onreadystatechange=searchDataReply;
	ajax.open("GET",url,true);
	ajax.send(null);
}

function addPersonId(customer_id, ele_id, store_name, ele_lbl_id){
		 //alert(customer_id+' - '+ele_id+' - '+store_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=customer_id;
		 document.getElementById(ele_lbl_id).innerHTML=store_name;
}

function searchDataPerson() {
	var ele_id = 'customer_id';
	var ele_lbl_id = 'lbl_PersonName';
	search=(document.getElementById('search').value);
	var url = 'index.php?app=customer_ob&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id+'&search='+search;
	ajax.open('get', url);
	ajax.onreadystatechange = searchDataReply;
	ajax.send(null);
}

function searchDataReply() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('PersonDropdown').innerHTML=response;
	}
}

function showHideDiv()
    {
		var PersonLookup = document.getElementById("PersonLookup");
		//alert(CommentatorLookUp.style.display);
        //var divstyle = new String();
        divstyle = PersonLookup.style.display;
        if(divstyle=="block")
        {
            PersonLookup.style.display = "none";
        }
        else
        {
            PersonLookup.style.display = "block";
        }
    }
	
function reqInput4Person(){
	 search = document.getElementById('search');
	search.value == 'Search here' ? search.value = '' : null;
	search.style.color = '#000000';
}
function Idle4Person(){
	 search = document.getElementById('search');
	if(search.value == ''){ 
	 	search.value = 'Search here';
		search.style.color = '#999999';
	}
}
function validate_4Enterchk()
{
	valid = true;
     if ( document.getElementById('customer_id').value == "" )
        {
		        alert ( "Please select Customer" );
				customer_id.focus();
                return false;
        }
     else if ( document.getElementById('dr_amount').value == "" )
        {
		        alert ( "Enter amount" );
				dr_amount.focus();
                return false;
        }
		else if ( document.getElementById('paid_date').value == "" )
        {
		        alert ( "Select Receive Date" );
				paid_date.focus();
                return false;
        }
        else{

        return true;
		}
}

function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
