<?php
//门面模式实例：打开照相机为例
//两步：打开闪光灯，打开照相机
//关闭闪光灯、关闭照相机
class Light{
	public function turnOn(){
		echo "打开闪光灯<br />";
	}
	public function turnOff(){
		echo "关闭闪光灯<br />";
	}
}

class Camera {
	public function active(){
		echo "打开照相机<br />";
	}

	public function deactive(){
		echo "关闭照相机<br />";
	}
}

class Facade{
	protected $light;
	protected $camera;
	public function __construct(){
		$this->light = new Light();
		$this->camera = new Camera();
	}
	public function start(){
		$this->light->turnOn();
		$this->camera->active();
	}

	public function stop(){
		$this->light->turnOff();
		$this->light->deactive();
	}
}

$f = new Facade();
$f->start();
$f->stop();