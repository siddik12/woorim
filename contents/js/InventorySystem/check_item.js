// JavaScript Document
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

//============= Start chkEmailExistence for Signup ============
function getUserId()
{    
	
		var item_code = document.getElementById('item_code').value
		var challan_no = document.getElementById('challan_no').value
		var mode = document.getElementById('mode').value
	//alert(catagory);
	  if(item_code!="" && challan_no!="" && mode=='add')
	  {  var url = "?app=godown_entry&cmd=ajaxcheckExistingItem&item_code="+item_code+"&challan_no="+challan_no;
	  	
	  	  httpCheckUid.open("GET", url, true);
		  httpCheckUid.onreadystatechange = handleUserIdResponse;
		  httpCheckUid.send(null);
	  }
}

function handleUserIdResponse()
{
    if(httpCheckUid.readyState == 4)
    {       
 	   var EmployerTxt 	 = httpCheckUid.responseText;
	   //alert(EmployerTxt);
	   var useridArr 		 = EmployerTxt.split('######');
	   //alert(useridArr);
	   if(useridArr[0]==0){
	   		document.getElementById('uidmsg').innerHTML="<span style='color:#009900;font-weight:bold'>You can use this code</span>";
	   }else if(useridArr[0]==1){
		   	document.getElementById('uidmsg').innerHTML="<span style='color:#FF0000; font-weight:bold'>You can't use this code</span>";
			//document.getElementById('Item_ID').value == "";
			document.getElementById('item_code').focus(); 
			document.getElementById('item_code').select(); 		

	   }
	}
	else{
		document.getElementById('uidmsg').innerHTML = "Checking Existence.Wait...";
		}
}
//=====End chkEmailExistence ======
