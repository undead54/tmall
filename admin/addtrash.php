<?php
	require('../include/init.php');
	$id=$_GET['goods_id'];
	$trush=array('is_delete'=>1);
	$model=new GoodsModel();
	if($model->update($trush,$id)){
		echo '添加回收站成功';
	}else{
		echo '添加回收站失败';
	}

 ?>