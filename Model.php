<?php 
class Model{
	//主机名
	protected $host;
	//用户名
	protected $user;
	//密码
	protected $pwd;
	//数据库名
	protected $dbname;
	//字符集
	protected $charset;
	//数据表前缀
	protected $prefix;
	//数据库连接资源
	protected $link;
	//数据表名  这里可以自己制定表名
	protected $tableName;
	//sql语句
	protected $sql;
	//操作数组 存放的就是所有的查询条件
	protected $options;
	//构造方法，对成员变量进行初始化
	public function __construct($config){
		//对成员变量一一进行初始化
		$this->host = $config['DB_HOST'];
		$this->user = $config['DB_USER'];
		$this->pwd = $config['DB_PWD'];
		$this->dbname = $config['DB_NAME'];
		$this->charset = $config['DB_CHARSET'];
		$this->prefix = $config['DB_PREFIX'];
		//连接数据库
		$this->link = $this->connect();
	}

	//连接数据库
	protected function connect(){
		$link = mysqli_connect($this->host,$this->user,$this->pwd);
		if(!$link){
			die('数据库连接失败');
		}
		//选择数据库
		mysqli_select_db($link,$this->dbname);
		//设置字符集
		mysqli_set_charset($link,$this->charset);
		return $link;
	}
	//filed方法
	//table方法
	//where方法
	//group方法
	//having方法
	//order方法
	//limit方法
	//select方法
	//query方法
	//exrc方法
}