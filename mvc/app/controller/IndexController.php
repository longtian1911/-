<?php
namespace controller;

class IndexController extends Controller{
	public function index(){
		$this->display();
	}

	public function demo(){
		echo "这是demo方法";
	}
}