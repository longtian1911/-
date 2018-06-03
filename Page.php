<?php
/*
*准备工作
*$_SERVER 
*REQUEST_URL  除了协议主机端口号以外的所有东西
*SERVER_PORT 端口号  http:80 端口  https: 443  ftp:21 端口
*SERVER_NAME 主机名
*REQUEST_SCHEME 协议 如 http  https
* parse_url       path:文件的路径   query:请求的参数
*parse_str  将query字符串变成关联数组
*http_build_query  将关联数组转化为query字符串 键作为name  值作为value 用& 连接 如 name1=value1&name2=value2
*/
$page = new Page(5,61);
var_dump($page->allUrl());
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
		//得到url
        $this->url = $this->getUrl();
	}

	//得到总的页数
	protected function getTotalPage(){
		return ceil($this->totalCount / $this->number);
	}

	//得到当前的页数
	protected function getPage(){
		if (empty($_GET['page']) || $_GET['page'] < 1){
			$page = 1;
		}elseif ($_GET['page'] > $this->totalPage) {
			$page = $this->totalPage;
		}else {
			$page = $_GET['page'];
		}
		return $page;
	}

	protected function getUrl(){
        //得到协议名
	    $scheme = $_SERVER['REQUEST_SCHEME'];
	    //得到主机名
        $host = $_SERVER['SERVER_NAME'];
        //得到端口号
        $port = $_SERVER['SERVER_PORT'];
        //得到路径和请求字符串
        $uri = $_SERVER['REQUEST_URI'];
        //将page=5等这种字符串拼接到url中,所以如果原来url中有page这个参数,首先需要将原来的page参数清空
        $uriArray = parse_url($uri);
        $path = $uriArray['path'];
        if(!empty($uriArray['query'])){
            //首先将请求字符串变为关联数组
            parse_str($uriArray['query'], $array);
            //清除掉关联数组中的page键值对
            unset($array['page']);
            //将剩下的参数拼接为请求字符串
            $query = http_build_query($array);
            //再将请求字符串拼接到路径的后面
            if($query != ''){
                $path = $path.'?'.$query;
            }
        }
        return $scheme.'://'.$host.':'.$port.$path;
    }

    protected function setUrl($str){
	    if(strstr($this->url,'?')){
            $url = $this->url.'&'.$str;
        }else{
	        $url = $this->url.'?'.$str;
        }
        return $url;
    }

	public function allUrl(){
        return [
          'first' => $this->first(),
          'prev' => $this->prev(),
          'next' => $this->next(),
          'end' => $this->end()
        ];
	}

	public function first(){
        return $this->setUrl('page=1');
	}

	public function next(){
	    //根据当前page得到下一页的页码
        if($this->page + 1 > $this->totalPage){
            $page = $this->totalPage;
        }else{
            $page = $this->page + 1;
        }
        return $this->setUrl('page='.$page);
	}

	public function prev(){
        if($this->page - 1 < 1){
            $page = 1;
        }else{
            $page = $this->page - 1;
        }
        return $this->setUrl('page='.$page);
	}

	public function end(){
        return $this->setUrl('page='.$this->totalPage);
    }

    //数据库中limit的两个参数
    public function limit(){
	    $offset = ($this->page - 1) * $this->number;
	    return $offset.','.$this->number;
    }
}