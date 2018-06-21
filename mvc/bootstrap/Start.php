<?php
class Start{
	//用来保存自动加载对象
	static public $auto;
	public static function init(){
		self::$auto = new Psr4AutoLoad();
	}

	//路由方法
	public static function router(){
		//从url中获取要执行的那个控制器中的那个方法

		$m = empty($_GET['m']) ? 'index' : $_GET['m'];

		//得到方法名
		$a = empty($_GET['a']) ? 'index' : $_GET['a'];

		//始终保证get参数中有默认值

		$_GET['m'] = $m;
		$_GET['a'] = $a;


		//将index处理
		//完整的类名就是命名空间名再拼接类名 controller\IndexController
		$m = ucfirst(strtolower($m));
		$controller = 'controller\\'.$m.'Controller';
		//根据类名创建对象
		$obj = new $controller();

		//让对象去执行对应的方法即可
		call_user_func([$obj,$a]);
	}
}
Start::init();