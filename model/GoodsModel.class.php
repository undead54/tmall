<?php 
	class GoodsModel extends Model{
		protected $table='goods';
		protected $prikey='goods_id';

		//数据库中的goods表的所有字段
		protected $filed=array('goods_id','goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','click_count','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time','last_update');

		//需要填充的字段
		protected $_auto=array(
								array('is_best','value',0),
								array('is_hot','value',0),
								array('is_new','value',0),
								array('is_on_sale','value',0),
								array('add_time','function','time')
							);

		public function getTrash(){
			$sql='select * from '.$this->table.' where is_delete=1';
			return $this->db->getAll($sql);
		}


		//自动生成商品货号
		public function createSn(){
			$sn='BL'.date('Ymd').mt_rand(10000,99999);
			$sql='select count(*) from '.$this->table.' where goods_sn='.$sn;
			if($this->db->getOne($sql)){
				$sn=$this->createSn();
			}
			return $sn;
		}

		//查找指定条数的新品
		public function getNew($n=5){
			$sql="select goods_id,goods_name,shop_price,market_price,ori_img from ".$this->table." order by add_time limit ".$n;
			return $this->db->getAll($sql);
			
		}

		//查找指定栏目cat_id下的指定条数的商品,从$start取，取$num条
		public function getGoods($cat_id,$start=0,$num=5){
			$cat=new CatModel();
			$data=$cat->select();
			$list=$cat->getCatTree($data,$cat_id);
			$result[]=$cat_id;
			foreach ($list as $v) {
				$result[]=$v['cat_id'];
			}
			$str=implode(',', $result);
			$sql="select goods_id,goods_name,shop_price,market_price,ori_img from ".$this->table." where cat_id in (".$str.") order by add_time limit ".$start.','.$num;
			
			return $this->db->getAll($sql);
		}

		public function getGoodsNum($cat_id){
			$cat=new CatModel();
			$data=$cat->select();
			$list=$cat->getCatTree($data,$cat_id);
			$result[]=$cat_id;
			foreach ($list as $v) {
				$result[]=$v['cat_id'];
			}
			$str=implode(',', $result);
			$sql="select count(*) from ".$this->table." where cat_id in (".$str.")";
			
			return $this->db->getOne($sql);
		}

		
		public function getCartGoods($items){
			$newitems=array();
			foreach ($items as $k => $v) {
				
				$sql='select market_price,ori_img from '.$this->table.' where goods_id='.$k;
				$row=$this->db->getRow($sql);
				if($row!=false){
					$newitmes[$k]=$v;
					$newitems[$k]=array_merge($v,$row);
				}
			}
			return $newitems;
		}

		//修改goods表中的goods_number
		public function updatenum($num,$goods_id){
			$sql="update ".$this->table." set goods_number=(goods_number-".$num.") where goods_id=".$goods_id;
			return $this->db->query($sql);
		}
	}
?>