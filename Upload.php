<?php
$up = new Upload();
$up->uploadFile('fm');
var_dump($up->errorNumber);
var_dump($up->errorInfo);
class Upload{
    //文件上传保存路径
    protected $path = './upload/';
    //允许文件上传的格式
    protected $allowSuffix = ['jpg','jpeg','gif','png','wbmp'];
    //允许文件上传的类型
    protected $allowMime = ['image/jpeg','image/gif','image/wbmp','image/png'];
    //允许文件的大小
    protected $maxSize = 2000000;
    //是否开启随机的名称
    protected $isRandName = true;
    //文件的前缀
    protected $prefix = 'up_';
    //错误号码和错误信息
    protected $errorNumber;
    protected $errorInfo;
    //上传文件的信息
    protected $oldName;
    //上传文件后缀
    protected $suffix;
    //上传文件大小
    protected $size;
    //上传文件的类型
    protected $mime;
    //上传临时文件名
    protected $tmpName;
    //文件的新名字
    protected $newName;

    public function __construct($arr = []){
        foreach ($arr as $key => $value) {
            $this->setOption($key,$value);
        }
    }

    //判断$key是不是成员属性,如果是则设置
    protected function setOption($key, $value){
        //得到所有的成员属性get_class_vars — 返回由类的默认属性组成的数组     get_class -- 返回对象的类名
        $keys = array_keys(get_class_vars(__CLASS__));
        //如果$key是我的成员属性,那么设置值
        if (in_array($key, $keys)){
            $this->$key = $value;
        }
    }

    //文件上传函数
    //$key 就是你input框中的name属性的值
    public function uploadFile($key){
        //判断有没有设置路径 path
        if (empty($this->path)){
            $this->setOption('errorNumber', -1);
            return false;
        }
        //判断路径是否存在/是否可写
        if (!$this->check()) {
            $this->setOption('errorNumber', -2);
            return false;
        }
        //判断$_FILES里面的erroe信息是否为0 如果为0 说明文件信息在服务器端可以直接获取,提取信息保存到成员属性
        $error = $_FILES[$key]['error'];
        if ($error){
            $this->setOption('errorNumber',$error);
            return false;
        }else{
            //提取文件相关信息并且保存到成员属性中
            $this->getFileInfo($key);
        }
        //判断文件的大小/mime/后缀 是否符合
        if (!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()) {
            return false;
        }
        //得到新的文件名字,判断是否启用随机名字
        $this->newName = $this->createNewName();
        //判断是否是上传文件,并且移动上传文件
        if(is_uploaded_file($this->tmpName)){
            if(move_uploaded_file($this->tmpName, $this->path.$this->newName)){
                return $this->path.$this->newName;
            }else{
            	$this->setOption('errorNumber', -7);
                return false;
            }
        }else{
            $this->setOption('errorNumber',-6);
            return false;
        }
    }

    //检验文件夹是否存在 / 可写
    protected function check(){
        //文件夹不存在或者不是目录,则我们创建文件夹
        if (!file_exists($this->path) || !is_dir($this->path)) {
            return mkdir($this->path, 0777, true); //创建成功返回true
        }

        //判断文件是否可写
        if (!is_writeable($this->path)) {
            return chmod($this->path, 0777); //修改成功返回true
        }
        return true;
    }


    //根据$key得到文件信息
    protected function getFileInfo($key){
        //得到文件名字
        $this->oldName = $_FILES[$key]['name'];
        //得到文件的mime类型
        $this->mime = $_FILES[$key]['type'];
        //得到文件临时路径
        $this->tmpName = $_FILES[$key]['tmp_name'];
        //得到文件的大小
        $this->size = $_FILES[$key]['size'];
        //得到文件后缀 pathinfo() 函数以数组的形式返回文件路径的信息。
        $this->suffix = pathinfo($this->oldName)['extension'];//可以通过这个函数拿到文件的后缀
    }

    //验证文件大小
    protected function checkSize(){
        if ($this->size > $this->maxSize) {
            $this->setOption('errorNumber',-3);
            return false;
        }
        return true;
    }

    //验证文件是否是我们允许上传的mime
    protected function checkMime(){
        if (!in_array($this->mime, $this->allowMime)) {
            $this->setOption('errorNumber', -4);
            return false;
        }
        return true;
    }

    //验证文件后缀是否允许上传的后缀
    protected function checkSuffix(){
        if (!in_array($this->suffix, $this->allowSuffix)) {
            $this->setOption('errorNumber',-5);
            return false;
        }
        return true;
    }

    //创建上传文件的新的名字
    protected function createNewName(){
        if ($this->isRandName) {
            $name = $this->prefix.uniqid().'.'.$this->suffix;
        }else{
            $name = $this->prefix.$this->oldName;
        }
        return $name;
    }

    public function __get($name){
    	if ($name == 'errorNumber') {
    		return $this->errorNumber;
    	}elseif ($name == 'errorInfo') {
    		return $this->getErrorInfo();
    	}
    }

    //错误信息
    protected function getErrorInfo(){
    	switch ($this->errorNumber) {
    		case -1:
    			$str = '文件路径没有设置';
    			break;
    		case -2:
    			$str = '文件路径不是目录或者没有权限';
    			break;
    		case -3:
    			$str = '文件大小超过指定的范围';
    			break;
    		case -4:
    			$str = '文件mime类型不符合';
    			break;
    		case -5:
    			$str = '文件的后缀不符合';
    			break;
    		case -6:
    			$str = '不是上传文件';
    			break;
    		case -7:
    			$str = '文件上传失败';
    			break;
    		case 1:
    			$str = '文件超过php.ini设置的大小';
    			break;
    		case 2:
    			$str = '上传文件大小超出html设置的大小';
    			break;
    		case 3:
    			$str = '文件部分上传';
    			break;
    		case 4:
    			$str = '没有文件上传';
    			break;
    		case 6:
    			$str = '找不到临时文件';
    			break;
    		case 7:
    			$str = '文件写入失败';
    			break;
    	}
    	return $str;
    }
}