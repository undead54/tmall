<?php
	//model模型
	class Model{
		protected $table=null;
		protected $db=null;
		protected $prikey=null;
		protected $error=array();

		//自动过滤数组
		protected $filed=array();
		//自动填充数组
		protected $_auto=array();

		protected $_valid=array();

		public function __construct(){
			$this->db=mysql::getIns();
		}

		//自动添加数据
		public function add($data){
			return $this->db->autoExecute($this->table,$data);
		}

		//取出所有数据
		public function select(){
			$sql='select * from '.$this->table;
			return $this->db->getAll($sql);
		}

		//通过主键找到对应的数据
		public function find($id){
			$sql='select * from '.$this->table.' where '.$this->prikey.'='.$id;
			return $this->db->getRow($sql);
		}

		//修改数据
		public function update($data,$id){
			$this->db->autoExecute($this->table,$data,'update','where '.$this->prikey.'='.$id);
			return $this->db->affected_rows();
		}

		//通过主键删除数据
		public function delete($id){
			$sql='delete from '.$this->table.' where '.$this->prikey.'='.$id;
			$this->db->query($sql);
			return $this->db->affected_rows();
		}

		//自动过滤传入数组中，不存在于$filed的字段
		public function filter($arr=array()){
			$result=array();
			foreach ($arr as $k => $v) {
				if(in_array($k, $this->filed)){
					$result[$k]=$v;
				}
			}
			return $result;
		}

		//为数组自动填充需要的字段
		public function _autofill($data){
			foreach ($this->_auto as $k => $v) {
				if(!array_key_exists($v[0], $data)){
					switch ($v[1]) {
						case 'value':
							$data[$v[0]]=$v[2];
							break;
						
						case 'function':
							$data[$v[0]]=$v[2]();
							break;
					}
				}
			}
			return $data;
		}

		//自动检验
		public function _validate($data){
			if(empty($this->_valid)){
				return true;
			}

			foreach ($this->_valid as $k => $v) {
				switch($v[1]){
					case 1:
						if(!isset($data[$v[0]])){
							$this->error[]=$v[2];
							return false;
						}
						if(!isset($v[4])){
							$v[4]='';
						}
						if(!$this->check($data[$v[0]],$v[3],$v[4])){
							$this->error[]=$v[2];
							return false;
						}
						break;
					case 0:
						if(isset($data[$v[0]])){
							if(!$this->check($data[$v[0]],$v[3],$v[4])){
								$this->error[]=$v[2];
								return false;
							}
						}
						break;
					case 2:
						if(isset($data[$v[0]]) && !empty($data[$v[0]])){
							if(!$this->check($data[$v[0]],$v[3],$v[4])){
								$this->error[]=$v[2];
								return false;
							}
						}
						break;
				}
			}
			return true;
		}

		public function check($value,$rule='',$parm=''){
			switch ($rule) {
				case 'require':
					return !empty($value);
				case 'number':
					return is_numeric($value);
				case 'in':
					$tmp=explode(',', $parm);
					return in_array($value, $tmp);
				case 'between':
					list($min,$max)=explode(',', $parm);
					return $value>=$min && $value<=$max;
				case 'length':
				    list($min,$max)=explode(',', $parm);
				    return strlen($value) >= $min && strlen($value) <=$max;
				case 'email':
					return filter_var($value, FILTER_VALIDATE_EMAIL)!==false;
				default:
					return false;
			}
		}


		public function getErr(){
			return $this->error;
		}
	}
?>