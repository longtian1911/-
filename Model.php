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
        //得到数据表名 
        $this->tableName = $this->getTableName();
        //初始化options数组
        $this->initOptions();
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

    //得到数据库表明
    protected function getTableName(){
        //第一种，如果设置了成员变量，那么通过成员变量来得到表名
        if (!empty($this->tableName)) {
            //表前缀+表名
            return $this->prefix.$this->tableName;
        }
        //第二种，如果没有设置成员变量，通过类名得到表名
        //得到当前类的类名字符串 如 GoodsModel  UserModel
        $className = get_class($this);
        $table = strtolower(substr($className, 0, -5)); 
        return $this->prefix.$table;

    }

    //初始化操作的数组
    protected function initOptions(){
        $arr = ['where', 'table', 'field', 'order', 'group', 'having', 'limit'];
        //将options数组中这些键对应的值全部清空
        foreach($arr as $value){
            $this->options[$value] = '';
            //将table默认设置为tableName
            if($value == 'table'){
                $this->options[$value] = $this->tableName;
            }
        }
    }
	//field方法
    public function field($field){
        if(!empty($field)){
           if (is_string($field)) {
               $this->options['field'] = $field;
           }elseif (is_array($field)) {
               $this->options['field'] = join(',', $field);
           }
        }
        return $this;
    }
	//table方法
    public function table($table){
        if(!empty($table)){
            $this->options['table'] = $table;
        }
        return $this;
    }
	//where方法
    public function where($where){
        if(!empty($where)){
            $this->options['where'] = 'where ' . $where;
        }
        return $this;
    }
	//group方法
    public function group($group){
        if(!empty($group)){
            $this->options['group'] = 'group by ' . $group;
        }
        return $this;
    }
	//having方法
    public function having($having){
        if(!empty($having)){
            $this->options['having'] = 'having ' . $having;
        }
        return $this;
    }
	//order方法
    public function order($order){
        if(!empty($order)){
            $this->options['order'] = 'order by ' . $order;
        }
        return $this;
    }
	//limit方法
    public function order($limit){
        if(!empty($limit)){
            if (is_string($limit)) {
                $this->options['limit'] = 'limit ' . $limit;
            }elseif (is_array($limit)) {
                $this->options['limit'] = 'limit ' .join(',', $limit);
            }
        }
        return $this;
    }
	//select方法
	//query方法
	//exrc方法
}