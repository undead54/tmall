<?php
	abstract class db{
		//数据库链接
		public abstract function connect($h,$u,$p);

		//执行query语句，返回resources
		public abstract function query($sql);

		//查询多行数据
		public abstract function getAll($sql);

		//查询单行数据
		public abstract function getRow($sql);

		//查询单个数据
		public abstract function getOne($sql);

		//执行insert,update语句
		public abstract function autoExecute($table,$data,$act='insert',$where='');
	}


?>