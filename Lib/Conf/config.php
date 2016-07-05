<?php
$config = require './Runtime/Conf/config.php';
$array = array(
	'APP_GROUP_LIST' => 'Home,Admin',
    'DEFAULT_GROUP'  => 'Home',
	'TMPL_FILE_DEPR' => '_',
	'URL_MODEL'      => 0,
	'admin_ads_file' => 'Public/Js',
	'USER_AUTH_KEY'=>'woaimm',// 用户认证SESSION标记
	// 'MEMECACHED_WEIGHT' => array(33,67),//可选
	'LANG_SWITCH_ON'=>true,// 多语言包功能
	'URL_CASE_INSENSITIVE'=>true,//URL是否不区分大小写 默认区分大小写
	'LANG_AUTO_DETECT'=>false,//是否自动侦测浏览器语言
	//'DATA_CACHE_SUBDIR'=>true,//哈希子目录动态缓存的方式
	//'DATA_PATH_LEVEL'=>2,
	'TMPL_STRIP_SPACE' => false,
	'DB_TYPE'        => 'mysql',
    'DB_HOST'        => ':/tmp/mysql.sock',
    'DB_NAME'        => 'tp_tupian',
    'DB_USER'        => 'root',
    'DB_PWD'         => 'chaonima',
    'DB_PORT'        => '',
    'DB_PREFIX'      => 'fh_',
);
return array_merge($config,$array);
?>