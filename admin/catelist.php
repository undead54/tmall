<?php
	require('../include/init.php');
	$model=new CatModel();
	$data=$model->select();
	//print_r($data);
	$cattree=$model->getCatTree($data);
	include(ROOT.'view/admin/templates/catelist.html');
?>