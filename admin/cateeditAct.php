<?php 
	//print_r($_POST);
	require('../include/init.php');
	$data=array();
	if(!$_POST['cat_name']){
		exit('分类名称不能为空');
	}
	$cat_id=$_POST['cat_id'];

	$data['parent_id']=$_POST['parent_id'];
	$model=new CatModel();

	//找出所有分类的信息
	$list=$model->select();
	//通过上级分类的id找出上级分类的家谱分类
	$list=$model->familytree($list,$data['parent_id']);
	//print_r($list);

	//判断该分类是否为上级分类家谱树中的一员
	$autho=true;
	foreach ($list as $k => $v) {
		
		if($cat_id==$v['cat_id']){
			$autho=false;
		}
	}

	if(!$autho){
		exit('上级分类不合法');
	}
	
	$data['cat_name']=$_POST['cat_name'];
	$data['intro']=$_POST['intro'];
	
	
	
	if($model->update($data,$cat_id)){
		echo '修改成功';
	}else{
		echo '修改失败';
	}
	
?>