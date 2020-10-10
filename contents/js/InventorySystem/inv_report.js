function ReceiveddReport()
{
	valid = true;
     if ( document.getElementById('rsfrom').value == "" || document.getElementById('rsto').value == "" || document.getElementById('challan_no').value == "" ){
        
		        alert ( "Please select any one" );
                valid = false;
        }else{

        return valid;
		}

}

function MovedReport()
{
	valid = true;
     if ( document.getElementById('mvfrom').value == "" )
        {
		        alert ( "Please select from date" );
				mvfrom.focus();
                valid = false;
        }
		else if ( document.getElementById('mvto').value == "" )
        {
		        alert ( "Please select to date" );
				mvto.focus();
                valid = false;
        }
		else{

        return valid;
		}
}

function indvReport()
{
	valid = true;
     if ( document.getElementById('slfrom').value == "" )
        {
		        alert ( "Please select from date" );
				slfrom.focus();
                valid = false;
        }
		else if ( document.getElementById('slto').value == "" )
        {
		        alert ( "Please select to date" );
				slto.focus();
                valid = false;
        }
		else if ( document.getElementById('slman_id').value == "" )
        {
		        alert ( "Please select salesman" );
				slman_id.focus();
                valid = false;
        }
		else{

        return valid;
		}
}

function MonthlyReport()
{
	valid = true;
     if ( document.getElementById('slfrom2').value == "" )
        {
		        alert ( "Please select from date" );
				slfrom2.focus();
                valid = false;
        }
		else if ( document.getElementById('slto2').value == "" )
        {
		        alert ( "Please select to date" );
				slto2.focus();
                valid = false;
        }
		else if ( document.getElementById('slman_id2').value == "" )
        {
		        alert ( "Please select salesman" );
				slman_id2.focus();
                valid = false;
        }
		else{

        return valid;
		}
}




function loadSalsManId(id){
	if (ajax==null)	  {
		  alert ("Browser does not support HTTP Request");
		  return;
	  }
	showElement(id);
	//alert(id);
	var ele_id = 'person_id';
	var ele_lbl_id = 'sls_name_lbl';
	var cmd='?app=inv_report_generator&cmd=ajaxSalesManLoad&ele_id='+ele_id+'&ele_lbl_id='+ele_lbl_id;
	ajaxCall('inv_report_generator',cmd,'SlsList')
}

function addSalsManId(person_id, ele_id, person_name, ele_lbl_id){
		 //alert(job_specialization_id+' - '+ele_id+' - '+job_specialization_name+' - '+ele_lbl_id);
		 document.getElementById(ele_id).value=person_id;
		 document.getElementById(ele_lbl_id).innerHTML=person_name;
}



function getModelId(main_item_category_id)
{ 

	//alert(main_item_category_id);
	  if(main_item_category_id!="")
	  {  var url = "?app=inv_report_generator_head&cmd=ajaxModel&main_item_category_id="+main_item_category_id;
	  	//alert(url);
	  	  ajax.open("GET", url, true);
		  ajax.onreadystatechange = getModelIdReply4Cat;
		  ajax.send(null);
	  }
}


function getModelIdReply4Cat() {
if(ajax.readyState == 4){
	var response = ajax.responseText;
	//alert(ajax.responseText);
	document.getElementById('catModel').innerHTML=response;
}
}

