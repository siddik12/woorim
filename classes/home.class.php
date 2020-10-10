<?php

class HomeApp
{
  
  function run()
  {
     $cmd = getRequest('cmd');

      switch ($cmd)
      {
      	 case 'add'                : $this->addFaq($msg); break;         	
         case 'admission'          : $this->adminission(); 										break;
         case 'photo'              : $this->photo();  												break;
         case 'home'               : $screen = $this->home();   							break;
         case	'login'							 : $this->login(); 													break;
         case	'user_comments'			 : $this->user_Comments();									break;
         //case 'user_comments_view' : $this->user_comments_view();             break;
         case 'user_comments_view' : $this->comments_view();             break;
         case 'deleteCommentsList' : $this->deleteCommentsList();              break;
         case 'showCommentsDetail' : $this->showCommentsDetail(getRequest('commentsid')); break;         
         default                   : $cmd = 'home'; $screen = $this->home();	break;
      }
  }
   
  function home()
  {
     $info = array();
     $info['table'] = NEWS_TBL;
     $info['fields'] = array('newsid','newsimage','newstitle','newsdate');
     $info['orderby'] = array('newsdate desc');
     //$info['debug']  = false;
     $res = select($info);
     $data = array();
     
     if(count($res))
     {
     	foreach($res as $val)
     	{
     		$data[] = $val;
     	}
     }
     require_once(CURRENT_APP_SKIN_FILE);
  }

  function adminission()
  {
  	require_once(ADMISSION_SKIN);
  }
  
  function photo()
  {
  	require_once(PHOTO_SKIN);
  }
  
  function login()
  {
  	require_once(LOGIN_SKIN);
  }
  
  function user_Comments()
  {
  	require_once(USER_COMMENTS_SKIN);
  }
  
  //=======this function is used for comments view===========
  function comments_view()
  {
  	 $data = array();
  	 $data['list']  = $this->user_comments_view();
  	 $data['count'] = $this->count_unread_comments();
  	 require_once(USER_COMMENTS_VIEW_SKIN);	
  }
  
  
  function user_comments_view()
  {
  	$info = array();
  	$data = array();
  	$info['table']  = USER_COMMENTS_TBL;
  	$info['fields'] = array('commentsid','comments_title','viewed','created_time');
  	$info['where']  = "remove_from_list = 0";
  	//$info['groupby']= array('commentsid','comments_title','created_time');
  	//$info['debug'] = false;
  	$result = select($info);
  	//dBug($result);
  	if(count($result))
  	{
  		foreach($result as $val)
  		{
  			$data[] = $val;
  		}
  	}
  	return $data;
  	//$data[count] = $this->count_unread_comments();
  	
  	//require_once(USER_COMMENTS_VIEW_SKIN);	
  }
  
  //====This is used for view current comments==========
  
  
  function showCommentsDetail($commentsid)
  {
  	$info = array();
  	$info['table']  = USER_COMMENTS_TBL;
  	$info['fields'] = array('commentsid','email','commentator','comments_title','comments','created_time');
  	$info['where']  = "commentsid = '$commentsid'";
  	$result = select($info);
  	//dBug($result);
  	$data = array();
  	if(count($result))
  	{
  		foreach($result as $val)
  		{
  			$data[] = $val;
  		}
  		
  	}  	
  	require_once(USER_COMMENTS_DETAILS_SKIN);
  	$this->updateUserComments($commentsid);	
  }
  
  //======= This function is used for disable Viewed comments==========
  function updateUserComments($commentsid)
  {
  	$info1 = array();
  	$info1['table']  = USER_COMMENTS_TBL;
  	$info1['where']  = "commentsid = '$commentsid'";
  	$info1['data'] = array("viewed"=>"1");
  	//$info1['debug'] = false;	
    update($info1);
  }
  
  function count_unread_comments()
  {
  	$info = array();
  	$info['table']  = USER_COMMENTS_TBL;
  	$info['fields'] = array('count(viewed) as unread');
  	$info['where']  = "viewed = 0 AND remove_from_list= 0";
  	//$info['groupby']= array('commentsid','comments_title','created_time');
  	$result = select($info);
  	//dBug($result);
  	$data1 = array();
  	if(count($result))
  	{
  		foreach($result as $val)
  		{
  			$data1[] = $val;
  		}
  	}
  	return $data1;
  }
 //======This function is used for delet comments from list========= 
  function deleteCommentsList()
  {
  	 //update user_comments set remove_from_list = 0 where commentsid in(6,7,8,9)
  	 $commentsid = getRequest('commentsid');
  	 $commentsid = substr($commentsid,0,strlen($commentsid)-1);
  	 $info1 = array();
  	 $info1['table']  = USER_COMMENTS_TBL;
  	 $info1['where']  = "commentsid in ($commentsid)";
  	 $info1['data'] = array("remove_from_list"=>"1");
     if(update($info1))
     {
     		header("Location:index.php?app=home&cmd=user_comments_view");
     		//echo $commentsid;
     }
  }
    
  
} // End class

?>
