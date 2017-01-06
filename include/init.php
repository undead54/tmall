<?php
	//init.php框架初始化



	//报错级别设置
	define('DEBUG',true);

	if(defined('DEBUG')){
		error_reporting(E_ALL);
	}else{
		error_reporting(0);
	}

	//初始化当前绝对路径,linux不支持反斜线
	define('ROOT', str_replace('\\','/',dirname(dirname(__FILE__))).'/');
	//echo ROOT;


	/*
	//包含数据库类
	require(ROOT.'include/db.class.php');
	require(ROOT.'include/mysql.class.php');
	//包含读取配置类
	require(ROOT.'include/conf.class.php');
	//包含写日志,备份日志类
	require(ROOT.'include/log.class.php');
	//包含model模型类
	require(ROOT.'model/Model.class.php');
	*/

	//包含参数过滤的方法
	require(ROOT.'include/lib_base.php');

	//自动加载类
	function __autoload($class){
		if(substr(strtolower($class),-5)=='model'){
			require(ROOT.'model/'.$class.'.class.php');
		}else if(substr(strtolower($class),-4)=='tool'){
			require(ROOT.'tool/'.$class.'.class.php');
		}else{
			require(ROOT.'include/'.$class.'.class.php');
		}
	}

	


	//过滤参数，用递归方式过滤$_GET,$_POST,$_COOKIE
	$_GET = _addslashes($_GET);
	$_POST = _addslashes($_POST);
	$_COOKIE = _addslashes($_COOKIE);
	
	//$mysql=mysql::getIns();
	//var_dump($mysql);
	
	
?>