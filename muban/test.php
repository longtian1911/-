<?php
include 'Tpl.php';
$tpl = new Tpl();
$title = '你好';
$data = ['科比','韦德'];
$abc = '天气不错';
$tpl->assign('abc',$abc);
$tpl->assign('title',$title);
$tpl->assign('data',$data);
$tpl->display('test.html');