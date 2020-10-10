
function confirmDelete()
{
    return confirm("Are you sure you wish to delete this entry?");
}
/*--------------------------End--------------------*/

function ajax4AddMeasurement(){
	//alert(mode);
		var measurementunit = document.getElementById('measurementunit').value;
		var measurementname = document.getElementById('measurementname').value;
		
		var cmd = "ajaxInsertMeasurement&measurementunit="+measurementunit+"&measurementname="+measurementname;
		//alert(cmd);
	   ajaxCall('inv_supplierinfo',cmd, 'MeasurementInfoDiv');
	
}

function ajaxCallDelMeasurement(id){
	
	var cmd = "ajaxDeleteMeasurement&measurementunit="+id;
	//alert(cmd);
	ajaxCall('inv_supplierinfo',cmd,'MeasurementInfoDiv');
	
}
function validate_form()
{
	valid = true;
     if ( document.getElementById('measurementunit').value == ""   )
        {
		        alert ( "unit will not be emply." );
                valid = false;
        }
		else if ( document.getElementById('measurementname').value  == ""   )
        {
		        alert ( "Name Will not be empty." );
                valid = false;
        }
        return valid;
}
function clearInputValue(){
		document.getElementById('measurementunit').value="";
		document.getElementById('measurementname').value="";
	}
