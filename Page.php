<?php
/*
*准备工作
*$_SERVER 
*REQUEST_URL  除了协议主机端口号以外的所有东西
*SERVER_PORT 端口号  http:80 端口  https: 443  ftp:21 端口
*SERVER_NAME 主机名
*REQUEST_SCHEME 协议
* parse_url       path:文件的路径   query:请求的参数
*parse_str  将query字符串变成关联数组
*http_build_query  将关联数组转化为query字符串
*/

class Page {
	//每页显示的个数
	protected $number;
	//一共多少数据
	protected $totalCount;
	//一共多少页
	protected $totalPage;
	//当前页
	protected $page;
	//url
	protected $url;

	public function __construct($number, $totalCount){
		$this->number = $number;
		$this->totalCount = $totalCount;
		//得到总的页数
		$this->totalPage = $this->getTotalPage();
		//得到当前页数
		$this->page = $this->getPage();
	}

	//得到总的页数
	protected function getTotalPage(){
		return ceil($this->totalCount / $this->number);
	}

	//得到当前的页数
	protected function getTotalPage(){
		if (empty($_GET['page'])){
			$page = 1;
		}elseif ($_GET['page'] > $this->totalPage) {
			$page = $this->totalPage;
		}elseif ($_GET['page'] < 1) {
			$page = 1;
		}else {
			$page = $_GET['page'];
		}
		return $page;
	}

	public function allUrl(){

	}

	public function first(){

	}

	public function next(){

	}

	public function prev(){

	}
}