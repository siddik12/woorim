var mode = 'add';
function deleteBranch(branch_id){	
   var url_loc = "index.php?app=branch&cmd=delete&branch_id="+branch_id;
   window.location = url_loc;
}
function updateBranch(branch_id,branch_name,stat){
	document.getElementById('branch_id').value = branch_id;
	document.getElementById('branch_name').value = branch_name;
	document.getElementById('stat').value = stat;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
}
function validate_form(){
	valid = true;
     if (document.contact_form.branch_name.value == "" ){
		        alert ( "Please fill  the field." );
                valid = false;
        }
        return valid;
}
