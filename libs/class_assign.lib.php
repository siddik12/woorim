<?php
function loadIntakeTeacher4Center($centerid)
{
			// load intake
			$info            = array();
      $info['table']   = INTAKE_TBL.' i,'.COURSE_TBL.' c ';
      $info['fields']  =  array('i.intakeid','i.year','i.session','i.shift','i.batch','c.coursename');
      $info['where']   = "i.courseid = c.courseid AND i.centerid = '$centerid'";
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
      
      // load teacher
      $info1            = array();
      $info1['table']   = EMPLOYEE_TBL.' e ';
      $info1['fields'] =  array('e.empid','e.firstname','e.lastname');
      $info1['where']   = "e.centerid = '$centerid'";
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
      
      foreach($data as $i=>$v)
      {
         $intake_idname .= $v[0]->intakeid.'-'.$v[0]->year.' '.$v[0]->session.' '.$v[0]->shift.' '.$v[0]->batch.' '.$v[0]->coursename.',';
      }
      foreach($data1 as $i1=>$v1)
      {
         $teacher_idname .= $v1[0]->empid.'-'.$v1[0]->firstname.' '.$v1[0]->lastname.',';
      }
      echo $intake_idname.'####'.$teacher_idname;	
}
?>