<?php
// $the_host = $_SERVER['HTTP_HOST'];//取得当前域名
// $the_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';//判断地址后面部分
// $the_url = strtolower($the_url);//将英文字母转成小写
// if($the_url=="/index.php")//判断是不是首页
// {
// $the_url="";//如果是首页,赋值为空
// }
// if($the_host == 'woaimm.cc')//如果域名是不带www的网址那么进行下面的301跳转
// {
// header('HTTP/1.1 301 Moved Permanently');//发出301头部
// header('Location:http://www.woaimm.cc'.$the_url);//跳转到带www的网址
// }
define('APP_NAME', 'FHPIC');
define('APP_PATH', './Lib/');
define('APP_DEBUG', true);
require './ThinkPHP/ThinkPHP.php';