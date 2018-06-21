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

$luntai = new LunTai();
$bmw = new BMW($luntai);
$bmw->run();
