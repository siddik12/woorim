RE_NUMBER   = new RegExp(/^[0-9]+$/);
RE_EMAIL    = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
RE_NAME     = new RegExp(/[^A-Z^a-z^ ^\.\^]$/);

var httpCheckUid				= getHTTPObject();


function getHTTPObject()
{

  var xmlhttp;
  if (!xmlhttp )
  {

    if(window.XMLHttpRequest) 
    {
    	try {
			      xmlhttp = new XMLHttpRequest();
          } 
          catch(e) {
			               xmlhttp = false;
                   }
     }
     else if(window.ActiveXObject)
     {
       	try {
        	    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
      	    }
            catch(E) {
				 try {
					   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					 } catch(e) {
								  xmlhttp = false;
								}
					 }
     }

  }  
 
  return xmlhttp;

}


function deleteAdminProfile(person_id){	
   var url_loc = "index.php?app=admin&cmd=delete&person_id="+person_id;
   window.location = url_loc;
}

function updateAdminProfile(person_id){
	var ul_loc = "?app=admin&cmd=edit&person_id="+person_id; 
	window.location = ul_loc;
}


function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}

function searchData() {
searchq=(document.getElementById('searchq').value);
ajax.open('get', '?app=admin&cmd=ajaxSearch&searchq='+searchq);
ajax.onreadystatechange = searchDataReply4Teacher;
ajax.send(null);
}
function searchDataReply4Teacher() {
if(ajax.readyState == 4){
	var response = ajax.responseText;
	//alert(ajax.responseText);
	document.getElementById('html').innerHTML=response;
}
}
