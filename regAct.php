<?php

	//print_r($_POST);

	require('./include/init.php');

	

	$model=new UserModel();
	//自动过滤
	$data=$model->filter($_POST);

	//数据检测
	if(!$model->_validate($_POST)){
		$msg=implode(',', $model->getErr());
		include(ROOT.'view/front/msg.html');
		exit;
	}
	//检测username是否重复
	if(!$model->checkname($data)){
		$msg=implode(',', $model->getErr());
		include(ROOT.'view/front/msg.html');
		exit;
	}
	//自动添加
	$data=$model->_autofill($data);
	//给密码加密
	$data=$model->reg($data);

	//信息入库
	if($model->add($data)){
		$msg='注册成功';
	}else{
		$msg='注册失败';
	}
	
	include(ROOT.'view/front/msg.html');
?>