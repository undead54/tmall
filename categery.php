<?php 
	require('./include/init.php');
	session_start();

	$cat=new CatModel();

	//取出所有的栏目信息
	$catdata=$cat->select();
	$catdata=$cat->getCatTree($catdata);

	//通过地址栏的cat_id得到其所有上级栏目
	$cat_id=isset($_GET['cat_id'])?$_GET['cat_id']+0:0;
	$flist=$cat->familytree($catdata,$cat_id);

	//实例化GoodsModel
	$goods=new GoodsModel();

	//通过cat_id取出对应栏目下的所有商品的个数
	$goodsnum=$goods->getGoodsNum($cat_id);

	//每页显示$num条数据
	$num=4;

	//地址栏取出page
	$page=isset($_GET['page'])?$_GET['page']+0:1;
	//如果地址栏的page小于1，让page=1
	if($page<1){
		$page=1;
	}
	//如果地址栏的page超出了最大页面数，则让page等于最大页
	if($page>$goodsnum){
		$page=ceil($goodsnum/$num);
	}

	

	//实例化分页类PageTool
	$pagetool=new PageTool($goodsnum,$page,$num);
	
	$pagecode=$pagetool->show();


	//从第$start条开始取数据
	$start=($page-1)*$num;

	//取出cat_id对应栏目下的从$start开始,$num个商品作为分页中的展示
	$goodslist=$goods->getGoods($cat_id,$start,$num);
	

	$cat_info=$cat->find($cat_id);
	if($cat_info!=false){
		$cat_name=$cat_info['cat_name'];
	}else{
		$cat_name='tmall正装商城';
	}

	//print_r($goodslist);exit;

	
	include(ROOT.'view/front/lanmu.html');

?>