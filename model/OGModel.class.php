<?php
	class OGModel extends Model{
		protected $table='ordergoods';
		protected $prikey='og_id';
		protected $filed=array( 'og_id','order_id','order_sn','goods_id','goods_name','goods_number','shop_price','subtotal');
	

		public function revoke($order_id){
			$sql="delete from ".$this->table." where order_id=".$order_id;
			$this->db->query($sql);
			return $this->db->affected_rows();
		}
	}
 ?>