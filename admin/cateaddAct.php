<?php
	/*
	建表语句
	create database tmall;
	use tmall;
	create table categery(
		cat_id int primary key auto_increment,
		cat_name varchar(20) not null default '',
		intro varchar(100) not null default '' ,
		parent_id int not null default 0
	)engine=myisam charset=utf8;
	*/
	require('../include/init.php');

	$data=array();
	if(empty($_POST['cat_name'])){
		exit('分类名称不能为空');
	}
	$data['cat_name']=$_POST['cat_name'];
	$data['intro']=$_POST['intro'];
	$data['parent_id']=$_POST['parent_id'];
	print_r($data);
	$catmodel=new CatModel();
	$catmodel->add($data);
	//echo 'yes';
?>