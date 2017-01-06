<?php
	class CartTool{
		private static $ins=null;
		private $items=array();
		private $sign=null;
		final protected function __construct(){
			$this->sign=mt_rand(0,10000);
		}
		final protected function __clone(){

		}

		public static function getIns(){
			if(!(self::$ins instanceof self)){
				self::$ins=new self();
			}
			return self::$ins;
		}

		public static function getCart(){
			if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)){
				$_SESSION['cart']=self::getIns();
			}
			return $_SESSION['cart'];
		}

		//添加商品
		public function addItem($id,$name,$price,$num=1){

			if($this->hasItem($id)){
				$this->incNum($id,$num);
				return ;
			}

			$item=array();
			$item['name']=$name;
			$item['price']=$price;
			$item['num']=$num;
			$this->items[$id]=$item;
		}

		//商品数量增加
		public function incNum($id,$num=1){
			if($this->hasItem($id)){
				$this->items[$id]['num']+=$num;
			}
		}

		//商品数量减少
		public function decNum($id,$num=1){
			if($this->hasItem($id)){
				$this->items[$id]['num'] =$this->items[$id]['num']-$num;
			}

			if($this->items[$id]['num']<1){
				$this->delItem($id);
			}
		}

		//判断某商品是否存在
		public function hasItem($id){
			return array_key_exists($id, $this->items);
		}

		//修改购物车中的商品数量
		public function modNum($id,$num=1){
			if(!$this->hasItem($id)){
				return false;
			}
			$this->items[$id]['num']=$num;
		}

		//删除商品
		public function delItem($id){
			unset($this->items[$id]);
		}


		//查询购物车中商品种类
		public function getCnt(){
			return count($this->items);
		}

		//查询购物车中的商品个数
		public function getNum(){
			if($this->getCnt()==0){
				return 0;
			}
			$sum=0;
			foreach ($this->items as $v) {
				$sum+=$v['num'];
			}

			return $sum;
		}

		//查询购物车中商品的总金额
		public function getPrice(){
			if($this->getCnt()==0){
				return 0;
			}

			$price = 0.0;

			foreach ($this->items as $v) {
				$price += $v['num'] * $v['price'];
			}

			return $price;

		}

		//返回购物车中的所有商品
		public function all(){
			return $this->items;
		}


		//清空购物车
		public function clear(){
			$this->items=array();
		}
	}

	/*
	session_start();

	$cart=CartTool::getCart();

	if(isset($_GET['a']) && $_GET['a']=='add'){

		$cart->addItem(1,'商品',20,1);
		echo 'y';
	}else if(isset($_GET['a']) && $_GET['a']=='add1'){
		$cart->addItem(2,'奶瓶',50,1);
		echo '奶瓶y';
	}else if(isset($_GET['a']) && $_GET['a']=='clear'){
		$cart->clear();
		echo 'clear';
	}else{
		print_r($cart->all());
		echo '有'.$cart->getCnt().'种商品,','共'.$cart->getNum().'个,','共计'.$cart->getPrice();
	}
	*/	
 ?>