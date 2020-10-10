var mode = 'add';
function deleteDepartment(department_id){	
   var url_loc = "index.php?app=department&cmd=delete&department_id="+department_id;
   window.location = url_loc;
}
function updateDepartment(department_id,department_name){
	document.getElementById('department_id').value = department_id;
	document.getElementById('department_name').value = department_name;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
}
function validate_form(){
	valid = true;
     if ( document.contact_form.department_id.value == "" || document.contact_form.department_name.value == "" ){
		        alert ( "Please fill All the field." );
                valid = false;
        }
        return valid;
}
function ajaxShowDept() {
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	var str = document.getElementById('faculty_id').value;
	var url='index.php?app=department&cmd=ajaxSearch&searchFaculty='+str;
	//alert(url);
	ajax.onreadystatechange=stateChanged;
	ajax.open("GET",url,true);
	ajax.send(null);
}
function stateChanged() {
	if (ajax.readyState==4){
		//alert(ajax.responseText);
		document.getElementById("html").innerHTML=ajax.responseText;
	}
}
