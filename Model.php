<?php
$config = ['DB_HOST'=>'localhost', 'DB_USER'=>'root','DB_PWD'=>'123456','DB_NAME'=>'tt','DB_CHARSET'=> 'utf8','DB_PREFIX'=>''];
$m = new Model($config);
/*$m->limit('0,5')->table('user')->field('age,name')->order('money desc')->where('id>=0')->select();

//查询操作
var_dump($m->limit('0,5')->table('user')->field('age,name')->order('money desc')->where('id>=1')->select());*/

//删除操作
/*$data = ['age' =>30, 'name'=>'成龙', 'money'=> 2000];
echo $m->table('user')->insert($data);*/
/*var_dump($m->table('user')->where('id>=3')->delete());*/

//更新操作
/*$data =['name'=>'成龙', 'money'=>3000];
var_dump($m->table('user')->where('id=2')->update($data));*/
var_dump($m->table('user')->max('money'));

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
    public function limit($limit){
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
	function select(){
		//先预写一个带有占位符的sql语句
		$sql = 'select %FIELD% from %TABLE% %WHERE% %GROUP% %HAVING% %ORDER% %LIMIT%';
		//将options中对于的值依次的替换上面的占位符
		$sql = str_replace(['%FIELD%','%TABLE%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%'], [$this->options['field'], $this->options['table'],$this->options['where'],$this->options['group'],$this->options['having'],$this->options['order'],$this->options['limit']], $sql);
		//保存一份sql语句
		$this->sql = $sql;
		//执行sql语句
		return $this->query($sql);
	}

	//query方法
	public function query($sql){
		//清空options数组中的数据
		$this->initOptions();
		//执行sql语句
		$result = mysqli_query($this->link, $sql);
		//提取结果集存放在数组中
		if ($result && mysqli_affected_rows($this->link)) {
			while ($data = mysqli_fetch_assoc($result)) {
				$newData[] = $data;
			}
		}
		//返回结果集
		return $newData;
	}

	//exec方法
	public function exec($sql, $isInsert = false){
		//清空options数组中的数据
		$this->initOptions();
		//执行sql语句
		$result = mysqli_query($this->link, $sql);
		if ($result && mysqli_affected_rows($this->link)) {
			//判断是否是插入语句，根据不同的语句返回不同的结果
			if ($isInsert) {
				return mysqli_insert_id($this->link);
			}else{
				return mysqli_affected_rows($this->link);
			}
		}
		return false;
	}

	//执行sql语句
	public function __get($name){
		if($name == 'sql'){
			return $this->sql;
		}
		return false;
	}

	//insert函数
	//$data:关联数组  键就是字段名，值就是字段值
	public function insert($data){
		//处理字符串问题，两边需要添加单、双引号
		$data = $this->parseValue($data);
		//提取所有的键 ，即所有的字段
		$keys = array_keys($data);
		//提取所有的值
		$values = array_values($data);
		//增加数据的sql语句
		$sql = 'insert into %TABLE%(%FIELD%) values(%VALUES%)';
		$sql = str_replace(['%TABLE%', '%FIELD%', '%VALUES%'], [$this->options['table'], join(',', $keys), join(',', $values)], $sql);
		$this->sql = $sql;
		return $this->exec($sql, true);
	}

	//传递进来一个数组，将数组中值为字符串的两边加上引号
	//insert into table(字段) value(值)
	protected function parseValue($data){
		//遍历数组，判断是否为字符串，若是字符串，将其两边添加引号
		foreach ($data as $key => $value) {
			if (is_string($value)) {
				$value ='"'.$value.'"';
			}
			$newData[$key] = $value;
		}
		//返回处理后的数组
		return $newData;
	}

	//删除函数
	public function delete(){
		//拼接sql语句
		$sql = 'delete from %TABLE% %WHERE%';
		$sql = str_replace(['%TABLE%', '%WHERE%'], [$this->options['table'], $this->options['where']], $sql);
		//保存sql语句
		$this->sql = $sql;
		//执行sql 语句
		return $this->exec($sql);
	}

	//更新函数
	//update 表名  set 字段名=字段值，字段名=字段值 where
	public function update($data){
		//处理data数组中值为字符串加引号的问题
		$data = $this->parseValue($data);
		//将关联数组凭借为固定的格式 键=值，键=值
		$value = $this->parseUpdate($data);
		//准备sql语句
		$sql = 'update %TABLE% set %VALUE% %WHERE%';
		$sql = str_replace(['%TABLE%', '%VALUE%', '%WHERE%'], [$this->options['table'], $value, $this->options['where']], $sql);
		//保存sql语句
		$this->sql = $sql;
		//执行sql语句
		return $this->exec($sql);
	}

	protected function parseUpdate($data){
		foreach ($data as $key => $value) {
			$newData[] =$key . '=' .$value;
		}
		return join(',', $newData);
	}

	//max函数
	public function max($field){
		//通过调用自己封装的方法进行查询
		$result = $this->field('max(' .$field. ') as max')->select();
		//select方法返回的是一个二维数组
		return $result[0]['max'];
	}

	//析构方法
	public function __destruct(){
		mysqli_close($this->link);
	}

	//getByName getByAge
	function __call($name, $args){
		
	}
}