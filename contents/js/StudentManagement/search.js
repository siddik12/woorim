var mode = 'add';
var cmd = '';

function saveProfile(){
	cmd = 'save';
	document.getElementById('cmd').value = cmd;
	return true
}
function deleteEmployeeProfile(employee_id){	
   var url_loc = "index.php?app=search&cmd=delete&employee_id="+employee_id;
   window.location = url_loc;
}

function updateEmployeeProfile(employee_id){
	var ul_loc = "index.php?app=search&cmd=edit&employee_id="+employee_id; 
	window.location = ul_loc;
}
/*===========================Form Validation=============================*/
function validate_form( )
{
	valid = true;

     if ( document.contact_form.employee_id.value == ""  )
        {
		        alert ( "Please fill Employee ID the box." );
				employee_id.focus();
                valid = false;
        }
			else if(document.contact_form.current_branch.value =="")
			{
					alert("Please insert branch.");
					current_branch.focus();
					valid = false;
			}
			else if(document.contact_form.full_time.value =="")
			{
					alert("Please insert full time.");
					full_time.focus();
					valid = false;
			}
			else if(document.contact_form.current_dept_id.value =="")
			{
					alert("Please insert current department.");
					current_dept_id.focus();
					valid = false;
			}
			else if(document.contact_form.designation_id.value =="")
			{
					alert("Please insert designation.");
					designation_id.focus();
					valid = false;
			}
			else if(document.contact_form.join_date.value =="")
			{
					alert("Please insert Recent joining date.");
					join_date.focus();
					valid = false;
			}
			else if(document.contact_form.actual_join_date.value =="")
			{
					alert("Please insert actual joining date.");
					actual_join_date.focus();
					valid = false;
			}
		 return valid;
}//EoF

/*--------------------------------------End----------------------------------*/
function reqInput(){
	var searchq = document.getElementById('searchq');
	searchq.value == 'Enter here' ? searchq.value = '' : null;
	searchq.style.color = '#000000';
}
function Idle(){
	var searchq = document.getElementById('searchq');
	if(searchq.value == ''){ 
	 	searchq.value = 'Enter here';
		searchq.style.color = '#999999';
	}
}

/*function responseReceiver(){
	if(ajax.readyState == 4){
		//alert(ajax.responseText);
		document.getElementById('html').innerHTML = ajax.responseText;
		}
}*/

function searchData() {
	document.getElementById('loaderImg').style.visibility='visible';
		
	searchq=(document.getElementById('searchq').value);
	ajax.open('get', 'index.php?app=search&cmd=ajaxSearch&searchq='+searchq);
	ajax.onreadystatechange = searchDataReply4Employee;
	ajax.send(null);
}
function searchDataReply4Employee() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('html').innerHTML=response;
		document.getElementById('loaderImg').style.visibility='hidden';
	}
}
/*=============== Add new person if not found by search ==================*/
function showHideDivForAddNewPerson()
    {
		var AddPerson = document.getElementById("AddPerson");
		var person_id = document.getElementById("person_id");
		person_id.value = "";
		var lbl_PersonName = document.getElementById("lbl_PersonName");
		lbl_PersonName.innerHTML = "";
		//alert(CommentatorLookUp.style.display);
        //var divstyle = new String();
        divstyle = AddPerson.style.display;
        if(divstyle=="block")
        {
            AddPerson.style.display = "none";
        }
        else
        {
            AddPerson.style.display = "block";
        }
    }


/*==========For person id & name div show hiide====================*/
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

function addPersonId(person_id, ele_id, person_name, ele_lbl_id){
		 //alert(person_id+' - '+ele_id+' - '+person_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=person_id;
		 document.getElementById(ele_lbl_id).innerHTML=person_name;
}

function loadPerson(){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showHideDiv();
	//alert(id);
	  
		var ele_id = 'person_id';
		var ele_lbl_id = 'lbl_PersonName';
	   
	var url='index.php?app=search&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	//alert(url);
	ajax.onreadystatechange=searchDataReply;
	ajax.open("GET",url,true);
	ajax.send(null);
}

/*function EmployeeLoadResponse(){
	if (ajax.readyState==4){
		//alert(xmlhttp.responseText);
		document.getElementById("Employee").innerHTML=ajax.responseText;
	}
}
/*function responseReceiver(){
	if(ajax.readyState == 4){
		//alert(ajax.responseText);
		document.getElementById('Employee').innerHTML = ajax.responseText;
		}
}*/
function searchDataPerson() {
	var ele_id = 'person_id';
	var ele_lbl_id = 'lbl_PersonName';
	search=(document.getElementById('search').value);
	var url = 'index.php?app=employee_profile&cmd=ajaxPersonLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id+'&search='+search;
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

function userAvailability(val){
	//alert(val);
	var url = 'index.php?app=employee_profile&cmd=ajaxUIdExist&userid='+val;
	ajax.open('get', url);
	ajax.onreadystatechange = userAvailabilityResponse;
	ajax.send(null);
}

function userAvailabilityResponse() {
	if(ajax.readyState == 4){
		var response = ajax.responseText;
		//alert(ajax.responseText);
		document.getElementById('user_exists_id').innerHTML=response;
	}
}
