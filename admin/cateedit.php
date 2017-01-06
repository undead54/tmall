<?php
	require('../include/init.php');
	//print_r($_GET);
	$cat_id=$_GET['cat_id'];
	$model=new CatModel();
	$catinfo=$model->find($cat_id);
	//print_r($catinfo);
	$cattree=$model->select();
	$cattree=$model->getCatTree($cattree);
	include(ROOT.'view/admin/templates/cateedit.html');

?>