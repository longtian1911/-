<?php
//轮胎类==》汽车类
class LunTai{
	public function roll(){
		echo "轮胎在滚动 <br />";
	}
}

class BMW {
	protected $luntai;
	public function run(){
		$this->luntai->roll();
		echo "开着宝马吃烤串<br />";
	}

	//注入方式
	public function __construct($luntai){
		$this->luntai = $luntai;
	}
}

class Container{
	//存放所绑定类
	public static $register = [];
	//绑定函数
	public static function bind($name,Closure $col){
		self::$register[$name] = $col;
	} 

	//创建对象函数
	public static function make($name){
		$col = self::$register[$name];
		return $col();
	}
}

Container::bind('luntai',function(){
	return new LunTai();
});

Container::bind('bmw',function(){
	return new BMW(Container::make('luntai'));
});

$bmw = Container::make('bmw');
$bmw->run();