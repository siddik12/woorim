<?php

function addDoc($abc)
 {
	
	  
	  $uploadDir = "/projects/atlas/inova/htdocs/documents/doc/";
	  	
	  $id = $_REQUEST['installed_system_id'];
	  $file = $_FILES['document_file']['name'];
	  $file_to_upload = 'doc-'.$id.'-'.$file;
	  
	  if(!empty($file))
	  {
	     if (!copy($_FILES['document_file']['tmp_name'], $uploadDir.$file_to_upload)) 
	     {
          echo '<script> alert("Failed to upload file");</script>';       
	     }
	     else 
	     {
	     	
	     	   updateServiceContractDoc($id, $file_to_upload);
	 
	         echo "<script> alert('File Uploading Process');</script>";      
	 
	         return $id."/".$file_to_upload."/1";
	     }
	  }
 }//EOFn

  function updateServiceContractDoc($id, $doc)
  {	  	
  	$info = array();
  	$data = array();
  	$info['table'] = INSTALLED_SYSTEM_TBL;  	
  	$data['service_contract_document'] = $doc; 
  	$info['data']  = $data;
  	$info['where'] = "id=$id";
  	$info['debug'] = false;
  	
  	$res = update($info);
  }
?>