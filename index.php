<?php
	require('./include/init.php');
	session_start();
	$goods=new GoodsModel();

	//取出是新品的商品
	$newgoods=$goods->getNew();
	
	//取出是男士正装栏目下的商品
	$cat_id=1;
	$mangoods=$goods->getGoods($cat_id);

	//取出时女士正装栏目下的商品
	$cat_id=4;
	$womangoods=$goods->getGoods($cat_id);

	include(ROOT.'view/front/index.html');
 ?>