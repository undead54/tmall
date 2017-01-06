<?php 
	require('./include/init.php');
	session_start();
	$act=isset($_GET['act'])?$_GET['act']:'buy';

	$goods=new GoodsModel();
	$cart=CartTool::getCart();

	if($act=='buy'){
		$goods_id=isset($_GET['goods_id'])?$_GET['goods_id']+0:0;

		if($goods_id){
			$row=$goods->find($goods_id);
			if($row!=false){

				//判断商品是否已下架或者被删除
				if($row['is_on_sale']==0 || $row['is_delete']==1){
					$msg='此商品已下架';
					include(ROOT.'view/front/msg.html');
					exit;
				}


				$num=isset($_GET['num'])?$_GET['num']+0:1;
				$cart->addItem($goods_id,$row['goods_name'],$row['shop_price'],$num);

				$items=$cart->all();

				//判断购买的数量是否已超出了库存量
				if($items[$goods_id]['num']>$row['goods_number']){
					$msg='存货不足';
					include(ROOT.'view/front/msg.html');
					$cart->decNum($goods_id,$num);
					print_r($cart->all());
					exit;
				}

			}
		}
	
	}else if($act=='delete'){
		$goods_id=isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
		if($goods_id){
			$cart->delItem($goods_id);
		}
	}else if($act=='clear'){
		$cart->clear();
	}

	$items=$cart->all();

	if(empty($items)){
		$msg='购物车中没有商品，请选购';
		include(ROOT.'view/front/msg.html');
		exit;
	}

	//通过购物车信息，取出并添加对应的图片和市场价
	$items=$goods->getCartGoods($items);
	//print_r($items);

	//购物车总金额
	$total=$cart->getPrice();

	//市场价总金额
	$market_total=0.00;
	foreach ($items as $k => $v) {
		$market_total+=$v['market_price']*$v['num'];
	}

	//省钱百分比
	$discount=$market_total-$total;
	$bili=round(($discount/$market_total)*100,2);

	//商品提交页面
	if($act=='tijiao'){
		include(ROOT.'view/front/tijiao.html');
		exit;
	}

	//订单入库
	if($act=='done'){
		$oimodel=new OIModel();

		//自动验证
		if(!$oimodel->_validate($_POST)){
			$msg=implode(',', $oimodel->getErr());
			include(ROOT.'view/front/msg.html');
			exit;
		}

		//自动过滤
		$oidata=$oimodel->filter($_POST);
		//自动填充
		$oidata=$oimodel->_autofill($oidata);
		//写入金额
		$oidata['order_amount']=$total;
		//从seesion读取用户信息
		$oidata['user_id']=isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
		$oidata['username']=isset($_SESSION['username'])?$_SESSION['username']:'匿名';
		//创建订单号
		$oidata['order_sn']=$oimodel->orderSn();
		//print_r($oidata);


		//订单入库
		if(!$oimodel->add($oidata)){
			echo '订单表添加失败';
			exit;
		}
		
		//订单商品入库
		/*
		 og_id
		 order_id
		 order_sn
		 goods_id
		 goods_name
		 goods_number
		 shop_price
		 subtotal
 		*/
		//获取订单号
		$order_id=$oimodel->insert_id();

		$ogmodel=new OGModel();
		foreach($items as $k=>$v){
			$order=array();
			$order['order_id']=$order_id;
			$order['order_sn']=$oidata['order_sn'];
			$order['goods_id']=$k;
			$order['goods_name']=$v['name'];
			$order['goods_number']=$v['num'];
			$order['shop_price']=$v['price'];
			$order['subtotal']=$v['price']*$v['num'];
			//print_r($order);

		//只要有一条商品信息没插入，撤销订单
			if(!$ogmodel->add($order)){
				$msg='下订单失败';
				$msg.=$ogmodel->revoke($order['order_id'])?'商品撤销成功':'商品撤销失败';
				$msg.=$oimodel->delete($order['order_id'])?'订单撤销成功':'订单撤销失败';
				include(ROOT.'view/front/msg.html');
				exit;
			}
		}

		//减少商品库存goods_number
		foreach ($items as $k => $v) {
			
			if(!$goods->updatenum($v['num'],$k)){
				$msg='数据库商品数量修改失败';
				include(ROOT.'view/front/msg.html');
				exit;
			};
		}

		//清空购物车
		$cart->clear();
	
		//支付方式
		$paymsg='未知';
		if($oidata['pay']==4){
			$paymsg='货到付款';
		}else if($oidata['pay']==5){
			$paymsg='网上支付';
		}
		
		include(ROOT.'view/front/order.html');

		exit;
	}

	//exit;
	include(ROOT.'view/front/jiesuan.html');
?>