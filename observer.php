<?php
//观察者模式涉及到两个类
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

	//删除观察者方法
	public function delObserver($observer){
		//查找对于值在数组中的键，array_search 函数在数组中搜索某个键值，并返回对应的键名。
		$key = array_search($observer, $this->observers);
		//根据键删除之，并且数组重新索引，array_splice() 函数从数组中移除选定的元素，并用新元素取代它。该函数也将返回包含被移除元素的数组。
		array_splice($this->observers, $key, 1);
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

$xiaoming->delObserver($xiaohua);
$xiaoming->buy();