<?php
	class UserModel extends Model{
		protected $table='user';
		protected $prikey='user_id';
		protected $filed=array('user_id','username','email','passwd','regtime','lastlogin');
	
		protected $_valid=array(
						array('username',1,'用户名必须存在','require'),
						array('username',0,'用户名必须在4-16字符内','length','4,16'),
						array('email',1,'email非法','email'),
						array('passwd',1,'passwd不能为空','require'),
						array('agreement',1,'必须遵守用户协议','require')
			);
		protected $_auto=array(
								array('regtime','function','time')
							);

		//加密
		protected function encrypt($str){
			return md5($str);
		}
		public function reg($data){
			if(isset($data['passwd'])){
				$data['passwd']=$this->encrypt($data['passwd']);
			}
			return $data;
		}

		//检测用户名是否存在
		public function checkname($data){
			if(!isset($data['username'])){
				$this->error[]='用户名不能为空';
				return false;
			}
			$sql="select count(*) from ".$this->table." where username='".$data['username']."'";
			if($this->db->getOne($sql)){
				$this->error[]='用户名已存在';
				return false;
			}
			return true;
		}
		public function checkuser($name,$passwd){
			$sql="select user_id,username,passwd,email from ".$this->table." where username='".$name."'";
			$row=$this->db->getRow($sql);
			if($row!==false){
				if($this->encrypt($passwd)==$row['passwd']){
					return $row;
				}
				return false;
			}else{
				return false;
			}
		}
	}
 ?>