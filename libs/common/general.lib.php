<?php
/*function dbFavi() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_favi', $link);
			return $link;
	}
	
function dbSB() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_sb', $link);
			return $link;
	}
	
function dbJohnson() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_johnson', $link);
			return $link;
	}
	
function dbDabur() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_dabur', $link);
			return $link;
	}
	
function dbGroup() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_group', $link);
			return $link;
	}

function dbSBsuper() {
		 	$link = mysql_connect('localhost', 'sumon', '123');
	  		mysql_select_db('inventory_sbsuper', $link);
			return $link;
	}
*/
function dbFavi() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_favi', $link);
			return $link;
	}
	
function dbSB() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_aci', $link);
			return $link;
	}
	
function dbJohnson() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_johnson', $link);
			return $link;
	}
	
function dbDabur() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_dabur', $link);
			return $link;
	}
	
function dbGroup() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_group', $link);
			return $link;
	}

function dbSBsuper() {
		 	$link = mysql_connect('localhost', 'mszahirtraders', 'M@MO[eWBIdTa');
	  		mysql_select_db('mszahirt_sbsuper', $link);
			return $link;
	}
	

function sendMail($mailto, $subject, $message, $fromEmail, $fromName = NULL){
	// To send HTML mail, the Content-type header must be set
	if(!$fromName){$fromName = 'DIU Software';}
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Additional headers
	$headers .= 'From: '.$fromName.' <'.$fromEmail.'>' . "\r\n";
	//$headers .= 'Reply-To: diu.software@gmail.com' . "\r\n" .
	   'X-Mailer: PHP/' . phpversion();
	
	if(@mail($mailto, $subject, $message, $headers)){
		//echo "success email logging"; 
		$sql = "insert into userlog.email_log(recipient, sender, subject, mail_body, email_time) 
			values ('".$mailto."', '".$fromEmail."', '".$subject."', '".$message."', '".date('Y-m-d H:i:s')."' )";
		$res = mysql_query($sql);
		
	}else{
		// faild email loggin 
	}

}

function lastLogin($userid){
	$sql = "SELECT ip, logintime, logid FROM ".USER_LOG_TBL." WHERE logid = (
				SELECT max( logid )
				FROM ".USER_LOG_TBL."
				WHERE userid = '$userid' )";
	$res = mysql_query($sql);
	while($rs = mysql_fetch_object($res)){
		$lastlogin = "Last account activity: IP (".$rs->ip.") Login Time: ".showFullDateTime($rs->logintime);
	}
	return $lastlogin;
}
 
   //*******

function bloodgroup_lookup()
{
    	$info['table']  = BLOODGROUP_TBL; 
    	$res = select($info);
    	if(count($res))
    	{
    		foreach($res as $i=>$v)
    		{
    			$data[$i] = $v;
    		}
    		return $data;
    	}
}

function city_lookup()
{
    	$info['table']  = CITY_TBL; 
    	$info['where']	= "countrycode='BD'";
    	$res = select($info);
    	if(count($res))
    	{
    		foreach($res as $i=>$v)
    		{
    			$data[$i] = $v;
    		}
    		return $data;
    	}
}
	//========= produce iFrame for dtPicker=============
	
	function dateIFrame($PATH)
	{
		$html .= "<iframe width=174 height=189 name='gToday:normal:agenda.js' id='gToday:normal:agenda.js' ";
		$html .= " src='".$PATH."/date/ipopeng.htm' ";
		$html .= " scrolling='no' frameborder='0' "; 
		$html .= "style='visibility:visible; z-index:999; position:absolute; left:-500px; top:0px;'> ";
		$html .= "</iframe>";
		echo $html;
  }
  //=============function dateFormat()=========================
  function formatDate4insert($dt)
  {
  	if(trim($dt))
  	{
    	$day   = substr($dt,0,2);
    	$month = substr($dt,3,2);
    	$year  = substr($dt,6,4);
    	return $year."-".$month."-".$day;
    }
  }
  
  
   function formatDate($dt)
  {
  	if(trim($dt))
  	{
  		//echo $dt; 01-02-2007 09:08:00 PM
    	$day   = substr($dt,0,2);
    	$month = substr($dt,3,2);
    	$year  = substr($dt,6,4);
    	//echo $ampm;
    	
    		return $year."/".$month."/".$day;	
    	
    		
    	
    	
    }
  }
	function formatDateDMY($val)
	{
		if($val)
		{
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			return $dd.'/'.$mm.'/'.$yy;
		}
	}

	function dateInputFormatDMY($val)
	{
		if($val)
		{
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			return $dd.'-'.$mm.'-'.$yy;
		}
	}
	
	function _date($val){
		//echo $val;
		//echo '<br>';
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			$hh = substr($val,11,2);
			$mi = substr($val,14,2);
			$ss = substr($val,17,2);
			//return $dd.'-'.$mm.'-'.$yy;
			return date('d M Y',mktime(0,0,0,$mm,$dd,$yy));
		
	}
	function _time($val){
		//echo $val;
		//echo '<br>';
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			$hh = substr($val,11,2);
			$mi = substr($val,14,2);
			$ss = substr($val,17,2);
			return date('h:i:s A',mktime($hh,$mi,$ss,0,0,0));
		
	}
	function showFullDateTime($val){
		//echo $val;
		//echo '<br>';
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			$hh = substr($val,11,2);
			$mi = substr($val,14,2);
			$ss = substr($val,17,2);
			return date('d M Y h:i:s A',mktime($hh,$mi,$ss,$mm,$dd,$yy));
		
	}
	function ShowDynDT($val){
		//echo $val;
		//echo '<br>';
			$yy = substr($val,0,4);
			$mm = substr($val,5,2);
			$dd = substr($val,8,2);
			$hh = substr($val,11,2);
			$mi = substr($val,14,2);
			$ss = substr($val,17,2);
			
			$Y 	= date('Y');
			$M 	= date('m');
			$D 	= date('d');
			$h 	= date('H');
			$m 	= date('i');
			$s 	= date('s');
			
			//return $dd.'-'.$mm.'-'.$yy;
			if($Y == $yy && $M == $mm && $D == $dd){ $format = 'g:i a';}
			elseif($Y == $yy){ $format = 'M d';}
			
			if($Y != $yy){ $format = 'd/m/Y';}
			
			return date($format,mktime($hh,$mi,$ss,$mm,$dd,$yy));
		
	}
 
//echo dateTime('2009-09-14 11:05:33');
	
  //============function regCodeGeneration()==================== 
  function regCodeGeneration()
	{
		return rand(100000,999999);
	}

   function getUserName_PhotoPath_Email($TBL, $WHERE)
   {
    	$data 				= array();
      $info         = array();
		
   		$info['table']= $TBL;
	 		$info['where']= $WHERE;

	 		$info['fields'] = array('firstname','middlename','lastname','photopath','email');
   		$res = select($info);
      if(count($res))
      {
     		foreach($res as $k => $v)
     		{
     			$data[$k][] = $v;
     		}
  		}   	
      foreach($data as $k => $v)
      {
      	$namePhotoEmail .= $v[0]->firstname.' '.$v[0]->middlename.' '.$v[0]->lastname.'###'.$v[0]->photopath.'###'.$v[0]->email;
      }
   	return $namePhotoEmail;	
   }


	function generateID($priFix, $maxId, $len)
	{
		$nextIdNum = trim($maxId,$priFix) + 1;
		$padlen = $len - (strlen($priFix) + strlen($nextIdNum)) +1 ;
    	$nextID = str_pad($priFix, $padlen, "0").$nextIdNum;	
		if	(strlen($nextID) <= $len)
			return $nextID;
		else
			return "ID over flow !!!";
   	}
   	
 	function SelectCDate()
	{
		$selectCdate = "select curdate() as Cdate";
		$rowCdate =mysql_fetch_object(mysql_query($selectCdate));
		return $rowCdate->Cdate;
	}
 	

?>