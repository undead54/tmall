<?php
	require('../include/init.php');

	$model=new GoodsModel();
	$goodslist=$model->select();



	include(ROOT.'view/admin/templates/goodslist.html');
 ?>