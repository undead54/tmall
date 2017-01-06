<?php 
	require('../include/init.php');
	//print_r($_GET);
	$id=$_GET['goods_id'];
	$model=new GoodsModel();
	$goods=$model->find($id);
	print_r($goods);
?>