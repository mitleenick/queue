<?php

return array(

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',

    ),
	'DB_TYPE'               => 'mysql',     // 数据库类型
	'DB_HOST'               => 'localhost', // 服务器地址
	'DB_NAME'               => 'queue',          // 数据库名
	'DB_USER'               => 'root',      // 用户名
	'DB_PWD'                => '4f70f5007d',          // 密码
	'DB_PORT'               => '3306',        // 端口
	'DB_PREFIX'             => 'q_',    // 数据库表前缀
	'LOG_RECORD'			=>	true,  // 进行日志记
	'SHOW_PAGE_TRACE' =>false, // 显示页面Trace信息
		
	'pre_num'=>'A',
			
);
