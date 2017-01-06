<?php

	
	class mysql extends db{
		private static $ins=null;
		private $conn=null;
		private $conf=array();
		protected function __construct(){
			$this->conf=conf::getIns();
			$this->connect($this->conf->host,$this->conf->user,$this->conf->pwd);
			$this->select_db($this->conf->db);
			$this->setChar($this->conf->char);

		}

		public static function getIns(){
			if(self::$ins instanceof self){
				return self::$ins;
			}
			self::$ins=new self();
			return self::$ins;
		}
		//选择数据库
		public function select_db($db){
			$sql='use '.$db;
			$this->query($sql);
		}

		//选择字符类型
		public function setChar($char){
			$sql='set names '.$char;
			return $this->query($sql);
		}

		//数据库链接
		public function connect($h,$u,$p){
			$this->conn=mysql_connect($h,$u,$p);
			//var_dump($this->conn);
		}

		//执行query语句，返回resources
		public function query($sql){
			//将每次执行的sql语句写入日志
			Log::write($sql);
			return mysql_query($sql,$this->conn);
		}

		//查询多行数据
		public function getAll($sql){
			$resource=$this->query($sql);
			$arr=array();
			while(($row=mysql_fetch_assoc($resource))!=false){
				$arr[]=$row;
			}
			return $arr;
		}

		//查询单行数据
		public function getRow($sql){
			$resource=$this->query($sql);
			return mysql_fetch_assoc($resource);
		}

		//查询单个数据
		public function getOne($sql){
			$resource=$this->query($sql);
			if(!$resource){
				return false;
			}
			return mysql_fetch_row($resource)[0];
		}

		//执行insert,update语句
		public function autoExecute($table,$data,$act='insert',$where='where 1 limit 1'){
			if($act=='insert'){
				$str1=implode(',',array_keys($data));
				$str2=implode("','",array_values($data));		
				$sql=$act.' into '.$table.' ('.$str1.") values ('".$str2."')";
				//echo $sql;
				return $this->query($sql);
			}else if($act=='update'){
				$sql='';
				foreach ($data as $k => $v) {
					$sql.=$k."='".$v."',";
				}
				$sql='update '.$table.' set '.substr($sql, 0,-1).$where;
				
				//echo $sql;
				return $this->query($sql);
			}
			return false;
		}

		//取得前一次 MySQL 操作所影响的记录行数
		public function affected_rows(){
			return mysql_affected_rows($this->conn);
		}

		//上一步 INSERT 查询中产生的 AUTO_INCREMENT 的 ID 号,如果没有产生，则返回0
		public function insert_id() {
        	return mysql_insert_id($this->conn);
    	}


	}
?>