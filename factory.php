<?php
//工厂模式
interface Skill{
	public function family();
	public function buy();
}

class Person implements Skill{
	public function family(){
		echo "伐木",'<br />';
	}

	public function buy(){
		echo "在买房子",'<br />';
	}
}

class JingLing implements Skill{
	public function family(){
		echo "精灵伐木",'<br />';
	}

	public function buy(){
		echo "精灵在买房子",'<br />';
	}
}

class Factory(){
	static function createHero($type){
		switch ($type) {
			case 'person':
				return new Person();
				break;
			
			case 'jingling':
				return new JingLing();
				break;
		}
	}
}

$person = Factory::createHero('person');