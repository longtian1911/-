<?php
//观察者模式设计到两个来
//男人类和女朋友类
//男人类对象小明，  小花、小丽
class Man {
	//用来存放观察者
	protected $observers = [];

	public function addObserver($observer){
		$this->observers[] = $observer;
	}

	//花钱方法
	public function buy(){
		//当观察者做出这个行为的时候，让观察者得到通知，并且做出一定的反应
		foreach ($this->observers as $girl) {
			$girl->dongjie();
		}
	}
}

class GirlFriend{
	public function dongjie(){
		echo "你的男朋友正在花钱，马上冻结他的银行卡<br />";
	}
}

//创建对象
$xiaoming = new Man();
$xiaohua = new GirlFriend();
$xiaoli = new GirlFriend();

//添加观察者
$xiaoming->addObserver($xiaohua);
$xiaoming->addObserver($xiaoli);

$xiaoming->buy();