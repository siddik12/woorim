<?php
	$im = imagecreate(100,20);
  $bg = imagecolorallocate($im, 200, 200, 200);
  imagefilledrectangle($im,0,0,100,30,$bg);
	$textcolor = imagecolorallocate($im, 150, 0, 0);
	imagestring($im,4,5,2,"Loading...",$textcolor);
  header('Content-Type: image/gif');
	imagegif($im,"loading.gif");
?>
