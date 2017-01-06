<?php 
	class OIModel extends Model{
		protected $table='orderinfo';
		protected $prikey='order_id';
		protected $filed=array( 'order_id','order_sn','user_id','username','zone','address','zipcode','reciver','email','tel','mobile','building','best_time','add_time','order_amount','pay');
	
		protected $_valid=array(
						array('reciver',1,'收货人不能为空','require'),
						array('email',1,'email非法','email'),
						array('pay',1,'请选择支付方式','in','4,5')
			);
		protected $_auto=array(
								array('add_time','function','time')
							);

		public function orderSn(){
			$sn='OI'.date('Ymd').mt_rand(10000,99999);
			$sql="select count(*) from ".$this->table." where order_sn='".$sn."'";
			return $this->db->getOne($sql)?$this->orderSn():$sn;
		}

		public function insert_id(){
			return $this->db->insert_id();
		}

	}
?>