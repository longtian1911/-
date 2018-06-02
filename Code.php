<?php
//验证码实例化
$code = new Code();
$code->outImage();
//验证码类的封装
class Code {
	//验证码个数
	protected $number;
	//验证码类型,0为纯数字验证码,1为纯字母验证码,2为字母和数字验证码
	protected $codeType;
	//图像宽度
	protected $width;
	//图像高度
	protected $height;
	//图像资源
	protected $image;
	//验证码 字符串
	protected $code;
	//验证码字体的大小
	protected $fontSize = 5;

	public function __construct($number = 4,$codeType = 2,$width = 100,$height = 50){
		//初始化成员属性
		$this->number = $number;
		$this->codeType = $codeType;
		$this->width = $width;
		$this->height = $height;
		//生成验证码
		$this->code = $this->createCode();
	}

	//读取验证码
	public function __get($name){
		if ($name = 'code') {
			return $this->code;
		}
		return false;
	}

	//生成验证码函数
	protected function createCode(){
		//通过验证码类型生成不同验证码
		switch ($this->codeType) {
			case 0: //纯数字验证码
				$code = $this->getNumberCode();
				break;
			case 1: //纯字母的
				$code = $this->getCharCode();
				break;

			case 2: // 字母和数字混合
				$code = $this->getNumCharCode();
				break;
			default:
				die('不支持这种验证码类型');
		}
		return $code;
	}

	//生成纯数字的验证码
	protected function getNumberCode(){
		//join()将数组以第一个参数连接,range(low,high,step)  生成low<= x<=high的数组  step是步长 可以省略 默认 步长为1
		$str = join('',range(0, 9));
		//str_shuffle(string) :随机地打乱字符串中的所有字符
		return substr(str_shuffle($str), 0,$this->number);
	}

	//生成纯字母的字符串
	protected function getCharCode(){
		$str = join('',range('a', 'z'));
		//strtoupper()将小写转为大写字母
		$str .=strtoupper($str);
		return substr(str_shuffle($str), 0,$this->number);
	}

	//生成数字混合的字符串
	protected function getNumCharCode(){
		$numStr = join('',range(0, 9));
		$str = join('',range('a', 'z'));
		$str = $numStr.$str.strtoupper($str);
		return substr(str_shuffle($str), 0,$this->number);
	}

	//创建画布
	protected function createImage(){
		$this->image = imagecreatetruecolor($this->width, $this->height);
	}

	//填充背景色
	public function fillBack(){
		imagefill($this->image, 0, 0, $this->lightColor());
	}

	//生成深色的颜色
	protected function lightColor(){
		return imagecolorallocate($this->image, mt_rand(130,255), mt_rand(130,255), mt_rand(130,255));
	}

	//生成浅色的颜色
	protected function darkColor(){
		return imagecolorallocate($this->image, mt_rand(0,120), mt_rand(0,120), mt_rand(0,120));
	}

	//将验证码写入画布中
	protected function drawChar(){
		$width = ceil($this->width / $this->number);
		for ($i = 0; $i < $this->number; $i++) {
			$x = mt_rand($i * $width + $this->fontSize, ($i + 1) * $width - $this->fontSize);
			$y = mt_rand(0 ,$this->height - 15);
			imagechar($this->image, $this->fontSize, $x, $y, $this->code[$i], $this->darkColor());
		}
	}

	//添加干扰元素
	protected function drawDisturb(){
		for ($i = 0; $i < 150; $i++ ){
			$x = mt_rand(0, $this->width);
			$y = mt_rand(0, $this->height);
			imagesetpixel($this->image, $x, $y, $this->lightColor());
		}
	}

	protected function show(){
		header('Content-Type:image/png');
		imagepng($this->image);
	}
	//显示验证码
	public function outImage(){
		//创建画布
		$this->createImage();
		//填充背景色
		$this->fillBack();
		//将验证码字符串画到画布中
		$this->drawChar();
		//添加干扰元素
		$this->drawDisturb();
		//输出并且显示
		$this->show();
	}

	//释放图片资源
	public function __destruct(){
		imagedestroy($this->image);
	}
}