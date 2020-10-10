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
function issueItmeId()
{    
	
		var Item_ID = document.getElementById('Item_ID').value
		var slman_id = document.getElementById('slman_id').value
		var issue_date = document.getElementById('issue_date').value
	//alert(catagory);
	  if(Item_ID!='' && slman_id!='' && issue_date !='')
	  {  var url = "?app=inv_item_issue&cmd=ajaxExistingItemIssue&Item_ID="+Item_ID+"&slman_id="+slman_id+"&issue_date="+issue_date;
	  	alert(url);
	  	  httpCheckUid.open("GET", url, true);
		  httpCheckUid.onreadystatechange = handleUserIdResponse2;
		  httpCheckUid.send(null);
	  }
}

function handleUserIdResponse2()
{
    if(httpCheckUid.readyState == 4)
    {       
 	   var EmployerTxt 	 = httpCheckUid.responseText;
	   //alert(EmployerTxt);
	   var useridArr 		 = EmployerTxt.split('######');
	   //alert(useridArr);
	   if(useridArr[0]==0){
	   		document.getElementById('itmid').innerHTML="<span style='color:#009900;font-weight:bold'>Not Available</span>";
	   }else if(useridArr[0]==1){
		   	document.getElementById('itmid').innerHTML="<span style='color:#FF0000; font-weight:bold'>Available</span>";
			//document.getElementById('Item_ID').value == "";
			document.getElementById('issue_date').focus(); 
			document.getElementById('issue_date').select(); 		

	   }
	}
	else{
		document.getElementById('itmid').innerHTML = "Checking Wait...";
		}
}
//=====End chkEmailExistence ======
