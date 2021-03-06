<?php
$function = require_once('function.php');
$db = require_once('db.php');
$config = array(

	//SESSION
	'SESSION_AUTO_START' => true, //是否开启session
	'SESSION_TIMEOUT' => 86400, //session保存时间

	//模板
	'TMPL_L_DELIM' => '<{',            // 模板引擎普通标签开始标记
	'TMPL_R_DELIM' => '}>',            // 模板引擎普通标签结束标记
	'TAG_NESTED_LEVEL' => 5,                // 标签嵌套级别
	'TMPL_CACHE_ON' => false,            //是否开启模板编译缓存
	'DB_FIELDS_CACHE' => false,            // 禁用字段缓存(不同库中有相同名字的表)
	'URL_MODEL' => 3,
	'URL_HTML_SUFFIX' => '',

	//日志
	'LOG_TYPE' => 'File',//日志记录类型
	'LOG_RECORD' => true,//开启了日志记录
	'LOG_LEVEL' => 'DEBUG,EMERG,ALERT,CRIT,ERR',

	//全局变量
	'G_ERROR' => true,//错误提示
	'G_SERVER' => 0,//错误提示
	'G_SQL' => array(),//trans过程中的所有SQL

	//超级管理员列表
	'SUPER_ADMIN' => array(1,),
	'ADMIN_PWD' => 'fgotl!23',
);
return array_merge($config, $function, $db);