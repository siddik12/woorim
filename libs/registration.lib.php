<?php
  
 function loadStudentSubject4Intake($intakeid, $teacherid)
 {
    
    	// load subject 
      $info            = array();
      $info['table']   = CLASS_ASSIGN_TBL . ' ca,' . SUBJECT_TBL . ' s';
      $info['fields'] =  array('s.subjectid','s.subjectname');
      $info['where']   = "ca.subjectid = s.subjectid AND ca.intakeid = '$intakeid' AND ca.teacherid = '$teacherid'";
      //$info['debug']   = false;

      $result          = select($info);
      $data            = array();

      if(count($result))
      {
         foreach($result as $key=>$value)
         {
            $data[$key][]        = $value;
         }
      }
      
      // load student
      $info1            = array();
      $info1['table']   = STUDENT_COURSE_TBL.' sc,' . STUDENT_TBL . ' s ';
      $info1['fields'] =  array('sc.studentid','s.firstname','s.middlename','s.lastname');
      //$info1['where']   = "sc.studentid = s.studentid AND registered = 1 AND sc.intakeid = '$intakeid'";
      $info1['where']   = "sc.studentid = s.studentid AND registered = 1 AND sc.intakeid = '$intakeid'";
      $info1['debug']   = false;

      $result1          = select($info1);
      $data1            = array();

      if(count($result1))
      {
         foreach($result1 as $key1=>$value1)
         {
            $data1[$key1][]        = $value1;
         }
      }
      
      //$str = implode();
      //dBug($data);
      //return $data;
      foreach($data as $i=>$v)
      {
         $subject_idname .= $v[0]->subjectid.'$$$'.$v[0]->subjectname.'%%%';
      }
      
      foreach($data1 as $i1=>$v1)
      {
         $student_idname .= $v1[0]->studentid.'$$$'.$v1[0]->firstname.' '.$v1[0]->middlename.' '.$v1[0]->lastname.'%%%';
      }
      echo $subject_idname.'####'.$student_idname;
 }

 function loadSubject($intakeid)
 {
    //app=student_attendance&cmd=loadSubject&intakeid
      $info            = array();
      $info['table']   = INTAKE_TBL.' i,' . COURSE_TBL . ' c,' . COURSE_SUBJECT_TBL .' cs, ' . SUBJECT_TBL . ' s';
      $info['fields'] =  array('s.subjectid','s.subjectname');
      $info['where']   = "i.courseid = c.courseid AND c.courseid = cs.courseid AND cs.subjectid = s.subjectid AND intakeid = '$intakeid'";
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
      
      //$str = implode();
      //dBug($data);
      //return $data;
      foreach($data as $i=>$v)
      {
         $subject_idname .= $v[0]->subjectid.'- '.$v[0]->subjectname.',';
         //$subjectname.=$v[0]->subjectname.',';	
      }
      
      echo $subject_idname;
      
            
 }
 
 function loadStudent4Intake($intakeid)
 {
    	$data   = array();
    	$info   = array();
    	$info['table'] = STUDENT_TBL.' st, '.STUDENT_COURSE_TBL.' sct, '.INTAKE_TBL.' i ';
    	$info['fields']= array("st.studentid");
    	$info['where'] = "sct.studentid=st.studentid AND i.intakeid='$intakeid' AND sct.intakeid=i.intakeid";
    	//$info['debug'] = false;
    	$res           = select($info);
    	if(count($res))
    	{
          foreach($res as $i=>$v)
          {
             $data1.=$v->studentid.',';	            
          }
       }
       echo $data1;
       
 }
 //******* Intake *******
 
function loadIntake4Student($studentid)
{
			// load intake
			$info            = array();
      $info['table']   = INTAKE_TBL.' i,'.STUDENT_COURSE_TBL.' sc ';
      $info['fields']  =  array('i.intakeid','i.year','i.session','i.shift','i.batch');
      $info['where']   = "i.intakeid = sc.intakeid AND sc.studentid = '$studentid'";
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
      
      
      
      foreach($data as $i=>$v)
      {
         $intake_idname .= $v[0]->intakeid.'-'.$v[0]->year.' '.$v[0]->session.' '.$v[0]->shift.' '.$v[0]->batch.',';
      }
      echo $intake_idname;	
}
function loadCourse4Intake($intakeid)
{
			// load intake
			$info            = array();
      $info['table']   = INTAKE_TBL;
      $info['fields']  =  array('courseid');
      $info['where']   = "intakeid = '$intakeid'";
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
           
      
      foreach($data as $i=>$v)
      {
         $courseid .= $v[0]->courseid;
      }
      echo $courseid;	
      //loadSubject($intakeid);
}

 
 
 
?>
