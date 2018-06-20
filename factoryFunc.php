<?php
interface Tell {
	public function call();
	public function receive();
}

class XiaoMi implements Tell{
	public function call(){
		echo "我在使用小米手机打电话";
	}

	public function receive(){
		echo "我在使用小米手机接电话";
	}
}

class HuaWei implements Tell {
	public function call(){
		echo "我在使用华为手机打电话";
	}

	public function receive(){
		echo "我在使用华为手机接电话";
	}
}

//工厂类是负责规定接口，具体的实现交给子类
interface Factory {
	static function createPhone();
}

class XiaoFactory implements Factory{
	static function createPhone(){
		return new XiaoMi();
	}
}

class HuaWeiFactory implements Factory{
	static function createPhone(){
		return new HuaWei();
	}
}