<?php
	require('../include/init.php');
	
	$model=new CatModel();
	$data=$model->select();
	$tree=$model->getCatTree($data);

	include(ROOT.'view/admin/templates/goodsadd.html');
 ?>