<?php
	class CatModel extends Model{
		//要操作的表
		protected $table='categery';
		//要操作的主键
		protected $prikey='cat_id';

		//通过cat_id查找子孙树
		public function getCatTree($data,$id=0,$lev=1){
			$list=array();
			foreach ($data as $v) {
				if($v['parent_id']==$id){
					$v['lev']=$lev;
					$list[]=$v;
					if($arr=$this->getCatTree($data,$v['cat_id'],$lev+1)){
						$list=array_merge($list,$arr);
					}
				}
			}
			return $list;
		}


		//级联删除
		public function delTree($tree,$id){
			foreach ($tree as $k=>$v) {
				if($v[$this->prikey]==$id){
					$sql='delete from '.$this->table.' where '.$this->prikey.'='.$id;
					$this->db->query($sql);
					unset($tree[$k]);
				}
				if($v['parent_id']==$id){
					$this->delTree($tree,$v[$this->prikey]);
				}
			}
		}




		//通过cat_id找家谱树
		public function familytree($data,$id){
			$list=array();
			foreach ($data as $k => $v) {
				if($v[$this->prikey]==$id){				
					unset($data[$k]);
					$list=array_merge($this->familytree($data,$v['parent_id']),$list);
					$list[]=$v;
				}
			}
			return $list;
		}


	}
 ?>