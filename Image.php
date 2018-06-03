<?php
class Image {
    //路径
    protected $path;
    //是否启用随机名字
    protected $isRandName;
    //要保存的图像类型
    protected $type;

    //通过构造方法对成员属性进行初始化
    public function __construct($path = './', $isRandName = true, $type = 'png'){
        $this->path = $path;
        $this->isRandName = $isRandName;
        $this->type = $type;
    }

    //对外公开的水印方法
    //$image:原图片   $water:水印图片   $postion:水印图片的位置  $tmd:水印图片的透明度 $prefix:图片前缀
    public function water($image, $water, $postion, $tmd = 100, $prefix = 'water_'){
        //判断这两个图片是否存在
        if((!file_exists($image)) || (!file_exists($water))){
            die('图片资源不存在');
        }
        //得到原图片的宽度和高度 以及水印图片的宽度和高度
        $imageInfo = self::getImageInfo($image);
        $waterInfo = self::getImageInfo($water);
        //判断水印图片能否贴上来
        if(!$this->checkImage($imageInfo, $waterInfo)){
            exit('水印图片太大');
        }
        //打开图片
        //根据水印图片的位置计算水印图片的坐标
        //将水印图片贴过来
        //得到要保存图片的文件名
        //得到保存图片的路径
        //保存图片
        //销毁资源
    }

    //对外公开的缩放方法
    public function suofang(){

    }

    //静态方法,根据图片的路径得到图片的信息,宽度 高度 mime类型
    public static function getImageInfo($imagePath){
        //getimagesize() 函数用于获取图像大小及相关信息
        $info = getimagesize($imagePath);
        $data['width'] = $info[0];
        $data['height'] = $info[1];
        $data['mime'] = $info['mime'];
        return $data;
    }

    //判断水印图片是否大于原图片
    protected function checkImage($imageInfo, $waterInfo){
        if(($waterInfo['width'] > $imageInfo['width']) || ($waterInfo['height'] > $imageInfo['height'])){
            return false;
        }
        return true;
    }

    //根据图片类型打开任意图片
    public static function openAayImage($imagePath){
        //得到图像的mime类型
        $mime = self::getImageInfo($imagePath)['mime'];
        //根据不同的mime类型来使用不同的函数来打开图像
        switch ($mime) {
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/wbmp':
                $image = imagecreatefromwbmp($imagePath);
                break;
        }
        return $image;
    }
}
$image = new Image();