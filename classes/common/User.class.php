<?php
/**
 * File: User.class.php
 * This class defines the user object needed in
 * almost all Web applications
 *
 * @package User
 * @author  php@DGITEL.com
 * @version $Id$
 * @copyright 2005 DGITEL, Inc.
 *
 */

class User
{
   /**
   * Default Constructor
   * @return none
   */
   function User($attributes = null)
   {

      // Default Constructor
      $this->entity_table = USER_TBL;
      $this->loaded       = false;


      // If UID is given load the user
      if ($attributes['userid'] > 0)
      {
           $this->loadUserByUID($attributes['userid']);
      }

      if ($attributes)
      {
         foreach($attributes as $key => $value)
         {
            $this->$key = $value;
         }
      }

      // If login ID field is defined,
      // set the login ID field
      // else set default login ID field to username
      
      //define('LOGIN_ID_FIELD','username');
      
      
      if (!defined('LOGIN_ID_FIELD'))
      {
         $this->setLoginIDField('employee_id');
         $this->userid = $this->userid;
      }
      else
      {
         $this->setLoginIDField(LOGIN_ID_FIELD);
      }
   }

   /**
   * Gets User Information
   * @return User Information
   */
   function getUserInfo()
   {
      return $this->getAttributes();
   }

   /**
   * Loads user info into session
   * @param user id
   * @return User Information
   */
   function loadUserByUID($uid = null)
   {
      return $this->loadUserByKeyValue(LOGIN_ID_FIELD, $uid);
   }

   /**
   * Loads user info into session
   * @param user email
   * @return User Information
   */
   function loadUserByEmail($email = null)
   {
      return $this->loadUserByKeyValue('email', q($email));
   }

   /**
   * Loads user info into session
   * @param key -> value
   * @return User Information
   */
   function loadUserByKeyValue($key = null, $value = null)
   {
      $info['table'] = $this->entity_table;
      $info['where'] = "$key = '$value'";
      //$info['debug'] = true;
      $rows = select($info);
            
      if (count($rows))
      {
         foreach($rows[0] as $key => $value)
         {
            $this->$key = $value;            
         }

         //$this->loadUserAddress();

         $this->loaded = true;
      }
   }

  
   /**
   * Checkes if user is looged in
   * @return boolean
   */
   function isLoggedIn()
   {
      return $this->isAuthenticated();
   }

   /**
   * Checkes if user is looged in
   * @return boolean
   */
   function isAuthenticated()
   {
      $uid = getFromSession('userid');

      return !empty($uid) ? true : false;
   }

   /**
   * Checkes user type
   * @return boolean
   */
   function sameTypeAs($userType)
   {
      return preg_match("/$userType/i", $this->user_type) ? true : false;
      //dBug( preg_match("/$userType/i", $this->user_type) ? true : false);
   }

   /**
   * Loads user from session
   * @return none
   */
   function loadFromSession()
   {      
      $this->setUsername(getFromSession('username'));
      $this->setUserType(getFromSession('user_type'));
      $this->setEmail(getFromSession('email'));
      $this->setFirstname(getFromSession('first_name'));
      $this->setLastname(getFromSession('last_name'));
   }

   /**
   * Redirects to login page
   * @return none
   */
   function goLogin()
   {
      header('Location: ' . LOGIN_URL);
      //header('Location: ' . HOME_URL);
   }

   /**
   * Redirects to home page
   * @return none
   */
   function goHome()
   {
      header('Location: ' . USER_HOME_URL);
   }

   function goABSHome()
   {
      header('Location: ' . HOME_URL);
   }
   /**
   * Sets user type
   * @param user type
   * @return none
   */
   function setUserType($type = null)
   {
      $this->user_type = $type;
   }

   /**
   * Gets user type
   * @return user type
   */
   function getUserType()
   {
      return $this->user_type;
   }

   /**
   * Sets login ID field
   * @param field name
   * @return none
   */
   function setLoginIDField($fieldName = null)
   {    
     $this->login_id_field = $fieldName;
   }

   /**
   * Gets login ID field
   * @return login ID field
   */
   function getLoginIDField()
   {
     return $this->login_id_field;
   }


   /**
   * Gets email
   * @return email
   */
   function getEmail()
   {
      return $this->email;
   }

   /**
   * Sets email
   * @param email
   * @return none
   */
   function setEmail($email = null)
   {
     $this->email = strtolower(trim($email));
   }

   /**
   * Sets password
   * @param password
   * @return none
   */
   function setPassword($passwd = null)
   {
      $this->password = $passwd;
   }


   /**
   * Gets first name
   * @return first name
   */
   function getFirstname()
   {
      return $this->first_name;
   }

   /**
   * Sets first name
   * @param first name
   * @return none
   */
   function setFirstname($first = null)
   {
      $this->first_name = $first;
   }

   /**
   * Sets username
   * @param username
   * @return none
   */
   function setUserName($username = null)
   {
      $this->username = $username;
   }
   
   /**
   * Gets username
   * @return username
   */
    function getUserName()
   {
      return $this->username;
   }
   /**
   * Gets last name
   * @return last name
   */
   function getLastname()
   {
      return $this->last_name;
   }

   /**
   * Sets last name
   * @param last name
   * @return none
   */
   function setLastname($last = null)
   {
      $this->last_name = $last;
   }

   /**
   * Gets uid
   * @return uid
   */
   function getEmployee_id()
   {
      return $this->userid;
   }

   function getStudent_id()
   {
      return $this->userid;
   }
   /**
   * Gets login id
   * @return login id
   */
   function getLoginID()
   {
      $func = 'get' . ucfirst($this->login_id_field);
            
      return $this->$func();
   }

   /**
   * Gets resey key
   * @return resey key
   */
   function getResetKey()
   {
          return $this->reset_key;
   }

   
 //===================Change authenticate============================================  
   function authenticate()
   {      
      
      // If user is alraedy logged in, return true
      $uid = getFromSession('userid');
                  
      if (! empty($userid))
          return true;

      echo 'loginID : '.$loginID      = $this->getLoginID();
      $loginIDField = $this->getLoginIDField();
      $password     = $this->password;
            
      if (empty($loginID) || empty($password))
          return false;
      
      $info['table'] = $this->entity_table;
      $info['where'] = "$loginIDField = " . q($loginID)
                       . " AND password = " . q(md5(md5($password)))
                       . " AND active = " . q(Y)
                       . " AND blocked = " . q(N);
      
      //$info['debug'] = true;
	  //print_r($info);
      $userRecord = select($info);
      
      // No user found
      if (empty($userRecord))
      {
          return false;
      }

      $this->setUserSession($userRecord[0]);
      return true;
   }

   /**
   * Sets user information
   * @param user information
   * @return none
   */
   function setUserSession($info = null)
   {
      foreach($info as $key => $value)
      {
         $this->$key = $value;
         insertIntoSession($key, $value);
      }
		
      insertIntoSession('userid', $this->getLoginID());
   }

   /**
   * Makes Password reset key
   * @return Password reset key
   */
   function makePasswordResetKey()
   {
      // Make the reset password link
      $uid = $this->getUID();

      $resetKey = base64_encode($uid . ';' . rand(1,999999));
      return $resetKey;

   }

   /**
   * Sets Password reset key
   * @param Password reset key
   * @return none
   */
   function savePasswordResetKey($resetKey = null)
   {
      $info['table']  = $this->entity_table;
      $info['data']   = array('reset_key' => $resetKey);
      $info['where']  = "uid = " . $this->getUID();
      $info['debug']  = false;

      $ret = update($info);
   }

   /**
   * Encrypt password
   * @param password
   * @return encrypted password
   */
   function encryptPassword($passwd = null)
   {

      $this->password = md5($passwd);

      return $this->password;
   }

   /**
   * Adds user
   * @param none
   * @return uid
   */
   function addUser()
   {
      $data = getUserDataSet(USER_TBL);

      $data['password']    = $this->encryptPassword($data['password']);
      $data['create_date'] = date('Y-m-d');

      $info['table'] = $this->entity_table;
      $info['data']  = $data;
      $info['debug'] = false;

      $add = insert($info);

      return $add;
   }

   /**
   * Modifies user
   * @param user attributes
   * @return boolean
   */
   function modifyUser($userID = null)
   {
      $data = getUserDataSet(USER_TBL);

      if ($data['password'])
      {
         $data['password'] = $this->encryptPassword($data['password']);
      }

      $data['last_updated'] = date('Y-m-d');

      $info['table'] = $this->entity_table;
      $info['where'] = "uid = $userID";
      $info['debug'] = false;
      $info['data']  = $data;

      $update = update($info);

      return $update;
   }

   /**
   * Deletes user
   * @param none
   * @return uid
   */
   function deleteUser($userID = null)
   {
      $info['table'] = $this->entity_table;
      $info['where'] = "uid = $userID";
      $info['debug'] = false;

      $delete = delete($info);

      return $delete;
   }

   /**
   * Searchs user
   * @param criteria, operator
   * @return dataset
   */
   function search($criteria = null, $operator = nul)
   {
      $rows = $this->_search($criteria, $operator);

      $foundUsers = array();

      if (count($rows))
      {
        // Create new user objects
        $dummy = new User();

        foreach ($rows as $i => $rowObject)
        {
           foreach($rowObject as $key => $value)
           {
              $dummy->$key = $value;
           }

           $dummy->loaded = true;

           $foundUsers[] = $dummy;
        }
      }

      return $foundUsers;
   }

} // End of class
?>