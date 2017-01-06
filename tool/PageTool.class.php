<?php
	//分页类
	class PageTool{
		//总条数
		protected $total=0;
		//当前页
		protected $page=1;
		//每页条数
		protected $perpage=10;
		

		public function __construct($total,$page=false,$perpage=false){
			$this->total=$total;
			if($perpage){
				$this->perpage=$perpage;
			}
			if($page){
				$this->page=$page;
			}
		}


		public function show(){
			//总页数$cnt
			$cnt=ceil($this->total/$this->perpage);

			//如果当前页面数字大于总页数，那么将当前页面自动变成最后一页
			if($this->page>$cnt){
				$this->page=$cnt;
			}
			//如果当前页面小于1，那么自动将页面变成第一页
			if($this->page<1){
				$this->page=1;
			}

			//获得地址栏中的信息
			$uri=$_SERVER['REQUEST_URI'];

			//解析地址栏信息
			$parse=parse_url($uri);

			//去掉地址栏中page单元,得到取出page的新地址栏uri
			$param=array();
			if(isset($parse['query'])){
				parse_str($parse['query'],$param);
			}
			unset($param['page']);

			$uri=$parse['path'].'?';
			if(!empty($param)){
				$param=http_build_query($param);
				$uri=$uri.$param.'&';
			}

			//echo $uri;


		//计算页码导航
			$nav=array();
			$nav[0]='<span class="page_now">'.$this->page.'</span>';

			//设置可显示的最大页数
			$num=5;

			for($left=$this->page-1,$right=$this->page+1;($left>=1 || $right<=$cnt) && count($nav)<$num;$left-=1,$right+=1 ){
				if($left >=1){
					array_unshift($nav, '<a href="'.$uri.'page='.$left.'" >['.$left.']</a>');
				}
				if($right <=$cnt){
					array_push($nav, '<a href="'.$uri.'page='.$right.'" >['.$right.']</a>');		
				}

			}
			//print_r($nav);
			return implode('', $nav);
		}
	}

	
 ?>