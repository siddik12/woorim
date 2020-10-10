<?php
function getStudentName($studentid)
{
	//echo $id;
   //list($intakeid, $studentid) = explode("D", $studentid);
   
   //$studentid="D".$studentid;
   //echo " $intake : ".$in." $stID : ".$id."<br>";
   
   $info = array();
   $info['table']  = STUDENT_TBL;
   $info['fields'] = array("CONCAT_WS(' ',firstname,middlename,lastname) as studentname");
   $info['where'] = "studentid='$studentid'";
   $info['debug']  = false;
   
   $res = select($info);
   
   $studentname = "";
   
   if(count($res))
   {
      foreach($res as $v)
      {
         $studentname = $v->studentname;
         //echo "  student name : ".$studentname;
         break;	
      }
      return $studentname;	
   }
}

function sendWelcomeMail($id)
{
   $data['senderName']       = getFromSession('username');
   $data['senderEmail']      = getFromSession('email');
   $data['subject']          = "Congratulations !!";
   $data['filename']         = $_SERVER['DOCUMENT_ROOT']. '/' .PROJECT_DIR.'/misc/mail_tpl.html'; 
   $data['to_address']       = getStudentEmailById($id); 
   $data['to_name']          = getStudentNameById($id); 
   $data['cc_address_name']  = array("admin@diit.com"=>"Name 1","test@test.com"=>"Name 2");
      
   sendHtmlMail($data);	
}

function getStudentNameById($id)
{
   
   $info = array();
   $info['table']  = STUDENT_TBL;
   $info['fields'] = array("CONCAT_WS(' ',firstname,middlename,lastname) as studentname");
   $info['where'] = "studentid='$id'";
   $info['debug']  = false;
   
   $res = select($info);
   
   $studentname = "";
   
   if(count($res))
   {
      foreach($res as $v)
      {
         $studentname = $v->studentname;
         break;	
      }
      
      return $studentname;	
   }
}

function getStudentEmailById($id)
{
   $info = array();
   $info['table']  = STUDENT_TBL;
   $info['fields'] = array("email");
   $info['where'] = "studentid='$id'";
   $info['debug']  = false;
   
   $res = select($info);
   
   $email = "";
   
   if(count($res))
   {
      foreach($res as $v)
      {
         $email = $v->email;
         break;	
      }
      
      return $email;	
   }

}
?>