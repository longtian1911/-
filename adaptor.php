<?php
interface PerfectMan{
	public function cook();
	public function writePhp();
}

class Wife {
	public function cook(){
		echo "我会做满汉全席";
	}
}

class Man implements PerfectMan{
	protected $wife;
	//在创建对象的时候保存传递进来的对象
	public function __construct($wife){
		$this->wife = $wife;
	}
	public function writePhp(){
		echo "我会写php代码";
	}

	public function cook(){
		$this->wife->cook();
	}

}

$li = new Wife();
$ming = new Man($li);
$ming->writePhp();
$ming->cook();