var mode = 'add';
function deletePKG(pkginfoid){	
   var url_loc = "?app=package&cmd=delete&pkginfoid="+pkginfoid;
   window.location = url_loc;
}
function updatePKG(pkginfoid,pkcatgid,pkg_name,pkg_speed,pkg_price){
	document.getElementById('pkginfoid').value = pkginfoid;
	document.getElementById('pkcatgid').value = pkcatgid;
	document.getElementById('pkg_name').value = pkg_name;
	document.getElementById('pkg_speed').value = pkg_speed;
	document.getElementById('pkg_price').value = pkg_price;
	 mode = 'edit';
	document.getElementById('mode').value = mode;
	}
function validate_form( )
{
	valid = true;
     if ( document.contact_form.pkgcat_name.value == ""  )
        {
		        alert ( "Please fill up package name." );
				pkgcat_name.focus();
                valid = false;
        }
     else if ( document.contact_form.pkcatgid.value == ""  )
        {
		        alert ( "Please fill up category." );
				pkcatgid.focus();
                valid = false;
        }
     else if ( document.contact_form.pkg_price.value == ""  )
        {
		        alert ( "Please fill up Price." );
				pkg_price.focus();
                valid = false;
        }
		
        return valid;
}
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}

 function isNumberKey(evt)
       {
          var charCode = (evt.which) ? evt.which : event.keyCode
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
       }
