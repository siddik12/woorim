<?php
/**
* Extracts and prepares EmployeeId
* @param String matched text by RegExp
* @return String padded with proper values
*/
function employeeID($text)
{
	 $val = intval($text[1])+1;
	 $noOfFill = 4-strlen($val);
	 return "E".str_pad("",$noOfFill, "0").$val;
}
?>