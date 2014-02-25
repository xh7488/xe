<?php
// //////////////////////////////////////////////////////////////////////////////
// 日志部分
//
// //////////////////////////////////////////////////////////////////////////////
// 如果开启优化optimzer，日志会在请求结束时才执行写入
$config ['logger'] = array (
		'writer' => 'file', // 日志写入类型，可选scribe,file
		'threshold' => 'INFO', // 日志级别
		'optmizer' => 0 
);
$config ['logger'] ['scribe'] = array (
		// 'layout_callback' => 'logger_layout_common',
		'host' => '10.10.5.136',
		'port' => 1463 
);
$config ['logger'] ['file'] = array (
		'layout_callback' => '\Xe\logger_layout_common',
		'path' => APP_PATH . "/logs/" 
);
return array (
		'default_controller'=>'welcome',
		'controller_group' => array (
				'admin'=>TRUE,
				'test'=>TRUE
				
		),
		'ini_set' => array (
				'error_reporting' => E_ALL | E_STRICT,
				'display_errors' => 1,
				'log_errors' => 1,
				'error_log' => APP_PATH . '/logs/php-fpm.log' 
		),
		'logger' => $config ['logger'],
		'db' => array (
				'main' => array (
						'dsn' => "mysql:host=127.0.0.1;dbname=douphp",
						'user' => 'root',
						'password' => '123456',
						'prefix' => 'dou_',
						'options' => array (
								PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'" 
						// PDO::ATTR_PERSISTENT => true
												) 
				) 
		) 
)
;


