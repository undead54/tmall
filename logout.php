<?php
	require('./include/init.php');
	session_start();
	$_SESSION=array();
	include(ROOT.'view/front/index.html');
 ?>