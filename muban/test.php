<?php
include 'Tpl.php';
$tpl = new Tpl();
$title = '你好';
$data = ['科比','韦德'];
$tpl->assign('title',$title);
$tpl->assign('data',$data);
$tpl->display('test.html');