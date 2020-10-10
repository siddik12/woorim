<?php
/**
* Extracts and prepares EmployeeId
* @param String matched text by RegExp
* @return String padded with proper values
*/
function centerID($text)
{
	 $val = intval($text[1])+1;
	 $noOfFill = 3-strlen($val);
	 return "C".str_pad("",$noOfFill, "0").$val;
}
?>