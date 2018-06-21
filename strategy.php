<?php
//策略模式
interface Love {
	public function sajiao();
}
class KeAi implements Love{
	public function sajiao(){
		echo "不理你<br />";
	}
}

class Tiger implements Love{
	public function sajiao(){
		echo "给老娘过来 <br />";
	}
}

class GirlFriend {
	protected $xingge;
	public function __construct($xingge){
		$this->xingge = $xingge;
	}

	public function sajiao(){
		$this->xingge->sajiao();
	}
}

$keai = new KeAi();
$li = new GirlFriend($keai);
$li->sajiao();