<?php
/**
* Extracts and prepares EmployeeId
* @param String matched text by RegExp
* @return String padded with proper values
*/
function studentID($text)
{
	 $val = intval($text[1])+1;
	 $noOfFill = 7-strlen($val);
	 return "D".str_pad("",$noOfFill, "0").$val;
}
?>