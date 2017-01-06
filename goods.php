<?php 
	require('./include/init.php');
	session_start();

	if(!isset($_GET['goods_id']) || $_GET['goods_id']==0 ){
		header('location:index.php');
		exit ;
	}


	$goods=new GoodsModel();
	$row=$goods->find($_GET['goods_id']);

	if($row!=false){
		$cat=new CatModel();
		$catlist=$cat->select();
		$catlist=$cat->familytree($catlist,$row['cat_id']);

		//print_r($catlist);exit();
		include(ROOT.'view/front/shangpin.html');
	}else{
		header('location:index.php');
	}

	
?>