function responseReceiver(){
	if(ajax.readyState == 4){
		//alert(ajax.responseText);
		document.getElementById('html').innerHTML = ajax.responseText;
		}
}
function searchData() {
searchq=(document.getElementById('searchq').value);
ajax.open('get', 'index.php?app=designation&cmd=ajaxSearch&searchq='+searchq);
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
function deleteDesignation(desig_id){	
   var url_loc = "index.php?app=designation&cmd=delete&desig_id="+desig_id;
   window.location = url_loc;
}
function updateDesignation(designation,desig_id){
	document.getElementById('designation').value = designation;
	document.getElementById('desig_id').value = desig_id;
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
     if ( document.contact_form.designation.value == ""  )
        {
		        alert ( "Please fill up designation." );
                valid = false;
        }
		
        return valid;
}

