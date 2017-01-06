<?php
	//写日志备份日志类
	class Log{
		//写日志
		public static function write($cont){
			$cont.="\r\n";
			$log=self::isBak();

			$fh=fopen($log, 'a');
			fwrite($fh, $cont);
			fclose($fh);
		}

		//备份日志
		public static function bak(){
			$log=ROOT.'data/log/curr.log';
			$bak=ROOT.'data/log/'.date('ymd').mt_rand(1000,9999).'.bak';
			return rename($log, $bak);
		}

		//读取并判断日志的大小
		public static function isBak(){
			$log=ROOT.'data/log/curr.log';
			//判断日志文件是否存在
			if(!file_exists($log)){
				touch($log);
				return $log;
			}

			//清除缓存
			clearstatcache(true,$log);
			//判断日志大小
			if(filesize($log)<=1024*1024){
				return $log;
			}

			//备份日志并创建新的日志文件
			if(self::bak()){
				touch($log);
				return $log;
			}else{
				return $log;
			}
		}
	}
	
	
?>