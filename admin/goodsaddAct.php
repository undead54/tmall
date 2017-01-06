<?php 
	require('../include/init.php');
	//print_r($_POST);


	$data=array();
	
	if($_POST['goods_name']==''){
		exit('商品名不能为空');
	}
	if($_POST['cat_id']==0){
		exit('请选择商品类型');
	}
	

	$model=new GoodsModel();
	//自动生成商品货号
	if(empty($_POST['goods_sn'])){
		$_POST['goods_sn']=$model->createSn();
	}


	//自动过滤,数据库中没有的字段
	$data=$model->filter($_POST);
	//自动填充数据库需要但$_POST没有的字段
	$data=$model->_autofill($data);

	
	//原图片图片上传
	$uptool=new UpTool();
	$res=$uptool->up('ori_img');
	if($res){
		$data['ori_img']=$res;
	}else{
		echo '添加失败';
		echo $uptool->get_error();
		exit();
	}
	


	


	
	//有上传图片的情况下才做处理
	if($res){
		//生成给网站显示的图片goods_img 宽高width=300，height=300
		//并生成新名称 goods_.$res
		$goods_img=dirname($res).'/goods_'.basename($res);
		if(ImageTool::thumb(ROOT.$res,ROOT.$goods_img,300,300)){
			$data['goods_img']=$goods_img;
		}

		//生成缩略图thumb_img 宽高width=100,height=100
		//并生成新名称 thumb_.$res
		$thumb_img=dirname($res).'/thumb_'.basename($res);
		if(ImageTool::thumb(ROOT.$res,ROOT.$thumb_img,100,100)){
			$data['thumb_img']=$thumb_img;
		}
	}
	

	//将$data的信息存入数据库
	if($model->add($data)){
		echo '添加成功';
	}else{
		echo '添加失败1';
	}
	
?>