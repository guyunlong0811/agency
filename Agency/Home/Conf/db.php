<?php
return array(
	//数据库部分
	'DB_TYPE' => DB_TYPE,
	'DB_DEPLOY_TYPE' => 0,                //数据库部署方式 0 集中式 1 分布式(主从)
	'DB_RW_SEPARATE' => false,            //是否读写分离
	'DB_HOST' => DB_HOST,
	'DB_USER' => DB_USER,
	'DB_PWD' => DB_PWD,
	'DB_PORT' => DB_PORT,
	'DB_NAME' => DB_NAME,
	'DB_PREFIX' => '',                  //数据库表名前缀
	'DB_CHARSET' => 'utf8',             //数据库字符类型
	'DB_FIELDS_CACHE' => false,            // 禁用字段缓存(不同库中有相同名字的表)
);