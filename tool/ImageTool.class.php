<?php
	class ImageTool{
		protected static $error_num=0;
		protected static $error_info=array(
				0=>'无错',
				1=>'图片或者水印图片不存在',
				2=>'打开的文件不是图片',
				3=>'水印图片的宽高不能大于图片',
				4=>'没有图片或者水印格式的画布'
			);

		//添加水印
		public static function addwater($path,$water,$newpath=null,$pos=0){

			//判断原图和水印图是否存在
			if(!file_exists($path) || !file_exists($water)){
				self::$error_num=1;
				return false;
			}

			//获取图片信息
			$image_rs=getimagesize($path);
			$water_rs=getimagesize($water);
			if(!$image_rs || !$water_rs){
				self::$error_num=2;
				return false;
			}

			//不允许水印图片大小超过原图
			if($image_rs[0]<$water_rs[0] || $image_rs[1]<$water_rs[1]){
				self::$error_num=3;
				return false;
			}

			//获取图片的后缀并确定使用创建画布的方法
			$image_type=substr($image_rs['mime'],strpos($image_rs['mime'],'/')+1);
			$water_type=substr($water_rs['mime'],strpos($water_rs['mime'],'/')+1);

			$ifunc='imagecreatefrom'.$image_type;
			$wfunc='imagecreatefrom'.$water_type;

			//判断是否有该图片类型的创建画布的方法
			if(!function_exists($ifunc) || !function_exists($wfunc)){
				self::$error_num=4;
				return false;
			}
			//echo 'imagecreatefrom'.$image_type;
			//echo 'imagecreatefrom'.$water_type;

			
			//创建画布
			$image_im=$ifunc($path);
			$water_im=$wfunc($water);

			//判断水印添加的位置，1为左上角，2为右上角，3为左下角，默认右下角
			switch ($pos) {
				case 1:
					$im_x=0;
					$im_y=0;
					break;
				case 2:
					$im_x=$image_rs[0]-$water_rs[0];
					$im_y=0;
					break;
				case 3:
					$im_x=0;
					$im_y=$image_rs[1]-$water_rs[1];
					break;	
				default:
					$im_x=$image_rs[0]-$water_rs[0];
					$im_y=$image_rs[1]-$water_rs[1];
					break;
			}

			//添加水印(画图)
			imagecopymerge($image_im, $water_im, $im_x, $im_y, 0, 0, $water_rs[0], $water_rs[1], 50);

			//设置保存画布的文件地址
			if(!isset($newpath)){
				$newpath=$path;
			}

			//保存画布
			$str='image'.$image_type;
			$str($image_im,$newpath);

			//销毁画布
			imagedestroy($image_im);
			imagedestroy($water_im);
		}

		//错误信息
		public static function errorinfo(){
			echo self::$error_info[self::$error_num];
		}

		//将图片缩略
		public static function thumb($path,$save=null,$width=200,$height=200){
			//判断图片是否存在
			if(!file_exists($path)){
				self::$error_num=1;
				return false;
			}

			//获取图片信息
			$rs=getimagesize($path);
			if(!$rs){
				self::$error_num=2;
				return false;
			}

			//获取图片后缀并确定使用创建画布的方法
			$type=substr($rs['mime'],strpos($rs['mime'],'/')+1);
			$func='imagecreatefrom'.$type;

			//判断是否有该图片类型的创建画布的方法
			if(!function_exists($func)){
				self::$error_num=4;
				return false;
			}
			//创建画布
			$des_im=imagecreatetruecolor($width, $height);
			$src_im=$func($path);

			//填充画布背景色
			$color=imagecolorallocate($des_im, 255, 255, 255);
			imagefill($des_im, 0, 0, $color);

			//计算图片缩小/放大的比例
			$scale=min($width/$rs[0],$height/$rs[1]);

			//计算等比例缩小/放大的宽高
			$src_w=round($rs[0]*$scale);
			$src_h=round($rs[1]*$scale);
			//echo $src_w.'-'.$src_h;

			//按照新的宽高在画布上拷贝图片(画图)
			imagecopyresampled ( $des_im, $src_im, (int)($width-$src_w)/2, (int)($height-$src_h)/2 , 0 , 0 , $src_w , $src_h , $rs[0] , $rs[1]);

			//设置保存画布的文件地址
			if(!$save){
				$save=$path;
				unlink($path);
			}

			//保存画布
			$save_func='image'.$type;
			$save_func($des_im,$save);

			//销毁画布
			imagedestroy($des_im);
			imagedestroy($src_im);

			return true;
		}
	}
 ?>