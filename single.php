<?php 
//单例模式实现方法
class Dog{
	private function __construct(){}
	//静态属性保存单列对象
	static private $instance;
	//通过静态方法来创建单例对象
	static public function getInstance(){
		//判断$instance 是否为空，如果为空则new一个对象，如果不为空，则直接返回
		if (!self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __clone(){}
}