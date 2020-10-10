function deletePersonalProfile(person_name,person_id){
	var conBox = confirm("Are you sure you want to delete:"+ person_name);
	if(conBox){
   var url_loc = "index.php?app=personal_profile&cmd=delete&person_id="+person_id;
   window.location = url_loc;
	}else{
		return;
	}
}
function updatePersonalProfile(person_id){
	var ul_loc = "index.php?app=personal_profile&cmd=editAllPerson&person_id="+person_id; 
	window.location = ul_loc;
}
function reqInput(){
	var searchq = document.getElementById('searchq');
	searchq.value == 'Search here' ? searchq.value = '' : null;
	searchq.style.color = '#000000';
}
function Idle(){
	var searchq = document.getElementById('searchq');
	if(searchq.value == ''){ 
	 	searchq.value = 'Search here';
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
searchq=(document.getElementById('searchq').value);
ajax.open('get', 'index.php?app=personal_profile&cmd=ajaxSearch&searchq='+searchq);
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





function validate_form()
{
	valid = true;

     if ( document.contact_form.person_name.value == ""  )
        {
		        alert ( "Please enter person name!" );
				person_name.focus();
                valid = false;
        }
		else if(document.contact_form.gender.value == "" )
		{
			alert ( "Please select gender!" );
			gender.focus();
            valid = false;
		}
		else if(document.contact_form.birth_date.value == "" )
		{
			alert ( "Please select date of birth!" );
			birth_date.focus();
            valid = false;
		}
		else if(document.contact_form.blood_group.value == "" )
		{
			alert ( "Please select blood group!" );
			blood_group.focus();
            valid = false;
		}
		else if(document.contact_form.person_type.value == "" )
		{
			alert ( "Please select person type!" );
			person_type.focus();
            valid = false;
		}
		else if(document.contact_form.mobile.value == "" )
		{
			alert ( "Please enter mobile number!" );
			mobile.focus();
            valid = false;
		}
		else if(document.contact_form.email.value == "" )
		{
			alert ( "Please enter Email address!" );
			email.focus();
            valid = false;
		  }
		 else if (document.contact_form.email.value.indexOf("@", 0) < 0)
					{
					  window.alert("Please enter a valid e_mail address, missing '@'");
					  email.focus();
					  return false;
				   }
		   else if (document.contact_form.email.value.indexOf(".", 0) < 0)
				   {
				   window.alert("Please enter a valid e-mail address, missing '.'");
				   email.focus();
				   return false;
				   }
		
		else if(document.contact_form.photo.value == "" )
		{
			alert ( "Please select photo!" );
			photo.focus();
             valid = false;
		}
		  return valid;
}// End form validation check
