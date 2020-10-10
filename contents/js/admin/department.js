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
     if (document.contact_form.department_name.value == "" ){
		        alert ( "Please fill  the field." );
                valid = false;
        }
        return valid;
}
