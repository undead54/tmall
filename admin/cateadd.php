<?php 
	require('../include/init.php');
	$cat=new CatModel();
	$catlist=$cat->select();
	//print_r($catlist);
	$cattree=$cat->getCatTree($catlist);
	//print_r($cattree);
	include(ROOT.'view/admin/templates/cateadd.html');
?>