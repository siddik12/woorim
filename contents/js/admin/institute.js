function responseReceiver(){
	if(ajax.readyState == 4){
		//alert(ajax.responseText);
		document.getElementById('html').innerHTML = ajax.responseText;
		}
}
function searchData() {
searchq=(document.getElementById('searchq').value);
ajax.open('get', 'index.php?app=institute&cmd=ajaxSearch&searchq='+searchq);
ajax.onreadystatechange = searchDataReply;
ajax.send(null);
}

function searchDataReply() {
if(ajax.readyState == 4){
	var response = ajax.responseText;
	//alert(ajax.responseText);
	document.getElementById('html').innerHTML=response;
}
}
var mode = 'add';
function deleteInstitute(company_id){	
//alert(class_id);
   var url_loc = "index.php?app=institute&cmd=delete&company_id="+company_id;
   window.location = url_loc;
}
function updateInstitute(company_id,company_name,address,mobile,email,web,branch_id){
	document.getElementById('company_id').value = company_id;
	document.getElementById('company_name').value = company_name;
	document.getElementById('address').value = address;
	document.getElementById('mobile').value = mobile;
	document.getElementById('email').value = email;
	document.getElementById('web').value = web;
	document.getElementById('branch_id').value = branch_id;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
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
function validate_form( )
{
	valid = true;
     if ( document.contact_form.company_name.value == "" || document.contact_form.mobile.value == ""  || document.contact_form.branch_id.value == ""  )
        {
		        alert ( "Please Enter Company, Branch and Mobile Number." );
                valid = false;
        }
		
        return valid;
}

