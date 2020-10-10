
<?php

class forgot_passwd
{
	private $AuthenticateCode;
   
  function run()
  {
     $cmd = getRequest('cmd');
	//echo $this->userid;
      switch ($cmd)
      {
         case 'skin'				  :SetPasswd();												 break;
		 case 'changepassword'		  :$this->changePass(getRequest('employee_id'));			 break;
		 case 'list'                  : $this->getList();       								 break;
		 case 'passwd_send'           : $this->passwdSendSkin();       							 break;
         default                      : $cmd == 'list'; $this->getList();  						 break;
      }
 }
 


function getList(){
	$employee_id = getRequest('loginid');
	if($employee_id){
		$this->SendPassWd($employee_id);
	}
    if (getRequest('id')) {
		$employee_id = $this->validAuthenticateCode(getRequest('id'));
	 	if($employee_id){
			require_once(SET_PASSWD_SKIN);
		}else{
			$msg = "Invalid link...";
		}
	}
  	 //$html = $this->AcrFetch();
	  //$Employee = $this->SelectEmployeeIdName(getRequest(ele_id), getRequest(ele_lbl_id));
	  require_once(CURRENT_APP_SKIN_FILE); 
  }

function sendURL(){
	$this->AuthenticateCode = md5(md5(rand(100000,999999)));
	return 'http://'.$_SERVER['HTTP_HOST'].PROJECT_DIR.'/?app=forgot_passwd&id='.$this->AuthenticateCode;
	//return 'http://erp.daffodilvarsity.edu.bd'.PROJECT_DIR.'/?app=forgot_passwd&id='.$this->AuthenticateCode;

}

function passwdSendSkin(){
	require_once(FORGOT_PASSWD_INPUT_SKIN);
}


function SendPassWd($employee_id){

	$sql = " Select p.email, p.person_name from ".HRM_EMPLOYEE_TBL." e inner join ".HRM_PERSON_TBL." p on 
				e.person_id = p.person_id where e.employee_id = '".$employee_id."'"; 
	
	$res = mysql_query($sql);
	
	//echo $sql;
	/*while($rows = mysql_fetch_array($res))
	{
			$email = $rows['email'];
			$employee_id = $rows['employee_id'];
	}*/

		$count = mysql_num_rows($res);

		if($count==1)	{

			$rows=mysql_fetch_array($res);
			
			if(!$to=$rows['email']){
				$msg = "You don't have e-mail address!!!";
			}
			else{

				$subject="Set password";
				
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
				$headers .= 'From: DIU Software <diu.software@gmail.com>' . "\r\n";
				$headers .= 'Reply-To: diu.software@gmail.com' . "\r\n" .
							   'X-Mailer: PHP/' . phpversion();
				
				$messages .= "Dear ".$rows['person_name'].", \r\n";
				$messages .= "Click on the following link to reset your password: 
				".$this->sendURL()."&email=".$to." \r\n";
				//$messages .= "'http://erp.daffodilvarsity.edu.bd/vus/index.php?app=login' \r\n";
				//echo $messages;
				if(@mail($to,$subject,$messages,$header)){
					$ret=$this->setAuthenticateCode($employee_id);
					if($ret){
					
						$msg = "<br>A link has been sent to your email. If not found please check your Spam";
						$maildomain = 'http://www.'.substr( strstr($to, '@'), 1, strlen(strstr($to, '@')) );
					}
				}else {
					$msg = "Message sent faild to your e-mail address";
				}
			}	
		}
		else
		{
			$msg = "Invalid User!!!";
		}
		//echo "$messages<br>msg='.$msg.'&maildomain='.$maildomain";
		
		header('location:?app=forgot_passwd&msg='.$msg.'&maildomain='.$maildomain);
	} // End RetrivePassWd() fnc


function setAuthenticateCode($employee_id){
	$info = array();
	$info['table']   = HRM_EMPLOYEE_TBL;
	$requestdata['authenticate_code'] = $this->AuthenticateCode;
	$info['where']   = "employee_id = '$employee_id'";
	$info['data']    = $requestdata;	
	//$info['debug']   = true;
	//dBug($info);
	if(update($info)){
	   	return true;
	}else{ 
		return false;
	}
}

function validAuthenticateCode($AuthenticateCode){
	$sql = "select employee_id from ".HRM_EMPLOYEE_TBL." where authenticate_code ='".$AuthenticateCode."'";
	$res = mysql_query($sql);
	$rs = mysql_fetch_object($res);
	return $rs->employee_id;
}

function changePass($employee_id){
	 if( getRequest('new_password') != getRequest('confirm_password') ){
	 	
	 }else{
	   	$new_password =  md5(md5(getRequest('new_password')));
		$requestdata  = array();
		$info = array();
		$requestdata['authenticate_code'] = '';
  	 	$requestdata['password'] = $new_password;
		$info['table']   = HRM_EMPLOYEE_TBL;
		$info['data']    = $requestdata;	
		$info['where']   = "employee_id = '$employee_id'";
		//$info['debug']   = true;
		//dBug($info);
		if(update($info)){
			header('location:?app=forgot_passwd&msg=Successfully changed...');
		}else{ 
			header('location:?app=forgot_passwd&msg=Process failed. Do the same this again.');
		}
	 }
	 


}//end fnc


} // End class

?>
