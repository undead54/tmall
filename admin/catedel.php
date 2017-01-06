<?php 
	//print_r($_GET);
	require('../include/init.php');
	$model=new CatModel();
	$tree=$model->select();
	$id=$_GET['cat_id'];
	$model->delTree($tree,$id);

	$data=$model->select();
	$cattree=$model->getCatTree($data);
	include(ROOT.'view/admin/templates/catelist.html');


	
	
?>