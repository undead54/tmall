<?php 
	require('../include/init.php');
	$model=new GoodsModel();
	$trash=$model->getTrash();
	print_r($trash);

?>