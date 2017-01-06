<?php
	//conf.class.php配置文件读写类
	class conf{
		protected static $ins=null;
		protected $data=array();
		final protected function __construct(){
				include(ROOT.'include/config.inc.php');
				$this->data=$_CFG;
				//echo ROOT;
			}

		final protected function __clone(){

		}

		public function __get($key){
			if(array_key_exists($key, $this->data)){
				return $this->data[$key];
			}else{
				return null;
			}
		}

		public function __set($key,$value){
			$this->data[$key]=$value;
		}

		public static function getIns(){
			if(self::$ins instanceof self){
				return self::$ins;
			}else{
				self::$ins=new self();
				return self::$ins;
			}
		}
	}

	/*
	$conf=conf::getIns();
	print_r($conf);
	//echo $conf->data['host'];
	echo $conf->user;
	*/

?>