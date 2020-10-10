<?php
function getIntakeList1($centerid)
   {
      $info            = array();
      $info['table']   = INTAKE_TBL;
      $info['where']   = "intakeid!='' AND centerid='$centerid'";
      $info['debug']   = false;

      $result          = select($info);
      $data            = array();           
      
      if(count($result))
      {
         foreach($result as $key=>$value)
         {
            $data[$key][]        = $value;
         }
      }
      return $data;
   }

 
 function loadStudent4Intake($intakeid)
 {	
 	  	// load student
      $info            = array();
      $info['table']   = STUDENT_COURSE_TBL.' sc,' . STUDENT_TBL . ' s ';
      $info['fields']  =  array('sc.student_intake_id','s.firstname','s.middlename','s.lastname');
      $info['where']   = "sc.studentid = s.studentid AND sc.intakeid = '$intakeid'";
      $info['debug']   = false;

      $result          = select($info);
      //$dBug($result);
      $data           = array();
      if(count($result))
      {
         foreach($result as $key => $value)
         {
            $data[$key][]        = $value;
         }
      }
      //dBug($data);
      //return $data;
      foreach($data as $i=>$v)
      {
         $student_name .=$v[0]->student_intake_id.'#$%'.$v[0]->firstname.' '.$v[0]->middlename.' '.$v[0]->lastname.',';
      }
      echo $student_name;
 }
 
?>
