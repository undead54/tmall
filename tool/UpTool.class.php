<?php
	//文件上传类
	class UpTool{
		//允许上传的文件类型
		protected $ext=array('jpg','png','bmp','gif','jpeg');
		protected $error_num=0;

		//允许上传文件的最大值，单位是M
		protected $max_size=1;
		protected $error_info=array(
				0=>'无错误',
				1=>'上传文件超出系统限制',
				2=>'上传文件大小超出网页表单页面',
				3=>'上传文件只有部分被上传',
				4=>'没有文件被上传',
				6=>'找不到临时文件夹',
				7=>'上传文件写入失败',
				8=>'上传文件格式不正确',
				9=>'上传文件大小超出$max_Size允许的范围'
			);
		public function get_error(){
			return $this->error_info[$this->error_num];
		}

		public function up($name){
			if(!isset($_FILES[$name])){
				$this->error_num=4;
				return false;
			}
			
			$file=$_FILES[$name];

			if($file['error']!=0){
				$this->error_num=$file['error'];
				return false;
			}

			if(!$this->check_Ext($file['name'])){
				$this->error_num=8;
				return false;
			}

			if(!$this->check_Size($file['size'])){
				$this->error_num=9;
				return flase;
			}

			$newfile=$this->get_Address().'/'.$this->get_name().'.'.$this->get_Ext($file['name']);

			if(move_uploaded_file($file['tmp_name'], $newfile)){
				return str_replace(ROOT, '', $newfile);
			}else{
				return false;
			}
			
			
		}

		//判断文件后缀/格式
		protected  function check_Ext($name){
			$ext=$this->get_Ext($name);
			if(!$ext){
				return false;
			}
			if(in_array($ext,$this->ext)){
				return true;
			}else{
				return false;
			}
		}

		//判断文件大小
		protected function check_Size($size){
			$maxsize=$this->max_size*1024*1024;
			if($size>$maxsize){
				return false;
			}else{
				return true;
			}
		}

		//生成的新地址
		protected function get_Address(){
			$dir=date('Ym/d');
			$dir=ROOT.'data/images/'.$dir;
			if(!is_dir($dir)){
				mkdir($dir,0777,true);
			}
			return $dir;
		}

		//自动生成文件名
		protected function get_name(){
			$str='abcdefghijkmnpqrstuvwxyz23456789';
			return substr(str_shuffle($str),0,6);
		}

		//获取文件后缀
		protected function get_Ext($name){
			$arr=explode('.', $name);
			if(count($arr)<2){
				return false;
			}
			return end($arr);
		}
	}

	
 ?>