<?php

namespace Xe;

/**
 * 一个简化版本的log4php，支持scribe分布式日志与文件日志
 * log4php虽然很灵活，但太重了，
 *
 * @author 张军磊 <zhangjunlei@gmail.com>
 *        
 *        
 *         调用方式如下：
 *         Logger::Fatal(msg,group);
 *         Logger::error(msg,group);
 *         Logger::warn(msg,group);
 *         Logger::info(msg,group);
 *         Logger::debug(msg,group);
 *         Logger::trace(msg,group);
 *        
 *         msg为日志内容，group为日志的分组，如果分组在configure中没定义，转为默认组default
 *        
 */
class Logger {
	const ALL = 64;
	const FATAL = 32;
	const ERROR = 16;
	const WARN = 8;
	const INFO = 4;
	const DEBUG = 2;
	const TRACE = 1;
	const OFF = 0;
	public static $threshold = self::DEBUG;
	protected static $msgs = array ();
	protected static $optmizer = 0;
	protected static $writer;
	protected static $run = FALSE;
	public static function trace($msg, $group = 'default') {
		return self::_log ( 'trace', $msg, $group );
	}
	public static function debug($msg, $group = 'default') {
		return self::_log ( 'debug', $msg, $group );
	}
	public static function info($msg, $group = 'default') {
		return self::_log ( 'info', $msg, $group );
	}
	public static function warn($msg, $group = 'default') {
		return self::_log ( 'warn', $msg, $group );
	}
	public static function error($msg, $group = 'default') {
		return self::_log ( 'error', $msg, $group );
	}
	public static function fatal($msg, $group = 'default') {
		return self::_log ( 'fatal', $msg, $group );
	}
	
	/**
	 * 初始化日志类
	 *
	 * @param $config Yaf_Config_Ini        	
	 */
	public static function init($config = NULL) {
		if (! $config)
			$config = getCfg ( 'main', 'logger' );
		self::$optmizer = $config ['optmizer'];
		self::$threshold = constant ( "self::{$config['threshold']}" );
		$writer_name = $config ['writer'];
		$writer_class = __NAMESPACE__."\\Logger_Writer_{$writer_name}";
		if (! class_exists ( $writer_class )) {
			$writer_name = 'file';
			$writer_class = "Logger_Writer_File";
		}
		$writer_cfg = isset ( $config [$writer_name] ) ? $config [$writer_name] : array ();
		self::$writer = new $writer_class ( $writer_cfg );
		if (self::$optmizer) {
			register_shutdown_function ( array (
					__NAMESPACE__.'\\'.__CLASS__,
					'flush' 
			) );
		}
		self::$run = TRUE;
	}
	public static function _log($type, $msg, $group = 'default') {
		if (! self::$run)
			self::init ();
		$uptype = strtoupper ( $type );
		$threshold = constant ( "self::{$uptype}" );
		$event = new Logger_Logging_Event ( $type, $msg, $group, $threshold );
		if (! self::$optmizer) {
			if ($threshold < self::$threshold)
				return FALSE;
			self::write ( $event );
		} else {
			self::$msgs [$type] [] = $event;
		}
	}
	protected static function write($events) {
		// 低于日志等级，不记录
		if ($events instanceof Logger_Logging_Event && ($events->numLevel < self::$threshold))
			return TRUE;
		self::$writer->write ( $events );
	}
	public static function flush() {
		self::write_more ( self::$msgs );
	}
}
/**
 * 日志格式化器
 *
 * @param $event Logger_Logging_Event        	
 * @param $withGroup bolean        	
 */
function logger_layout_common(Logger_Logging_Event $event, $withGroup = FALSE) {
	$date = date ( 'c', $event->microtime );
	$level = strtoupper ( $event->level );
	$microtime = sprintf ( "%8f", $event->microtime );
	$group = $withGroup ? " GRP:{$event->group}" : '';
	$msg = $event->msg;
	if (is_array ( $msg )) {
		$msg = http_build_query ( $msg );
	} elseif (is_object ( $msg )) {
		$msg = serialize ( $msg );
	}
	$msg = "{$date} [HOST: {$event->host} PID:{$event->threadName}{$group}] [{$level}] {$msg}";
	return $msg;
}
/**
 * 日志事件
 *
 * @author leon
 *        
 */
class Logger_Logging_Event {
	public $level;
	public $msg;
	public $group;
	public $microtime;
	public $threadName;
	public $numLevel;
	public $host;
	public function __construct($level, $msg, $group, $numLevel) {
		$this->microtime = microtime ( TRUE );
		$this->level = $level;
		$this->msg = $msg;
		$this->group = $group;
		$this->threadName = ( string ) getmypid ();
		$this->numLevel = $numLevel;
		$this->host = isset ( $_SERVER ['SERVER_ADDR'] ) ? $_SERVER ['SERVER_ADDR'] : '127.0.0.1';
	}
}
/**
 * 日志写入器基类
 *
 * @author leon
 *        
 */
abstract class Logger_Writer {
	protected $layout_callback;
	protected $run = FALSE;
	public function __construct($options = array()) {
		if ($options) {
			foreach ( $options as $k => $v ) {
				$this->$k = $v;
			}
		}
		if(!isset($this->layout_callback))
		{
			$this->layout_callback= __NAMESPACE__.'\\logger_layout_common';
		}
	}
}
/**
 * 基于文件的日志写入器
 *
 * @author leon
 *        
 */
class Logger_Writer_File extends Logger_Writer {
	protected $handles;
	protected $path = '/tmp';
	protected $file = 'all.log';
	public function init() {
		$this->run = TRUE;
	}
	public function get_handler($group) {
		if (! isset ( $this->handles [$group] )) {
			$dir = "{$this->path}/{$group}/";
			if (! is_dir ( $dir ))
				mkdir ( $dir, 0777, TRUE );
			$date = date ( 'Y-m-d' );
			$file = "{$date}.log";
			
			$this->handles [$group] = fopen ( "{$dir}{$file}", 'a+' );
		}
		return $this->handles [$group];
	}
	public function write(Logger_Logging_Event $event) {
		if (! $this->run)
			$this->init ();
		if ($event->numLevel < Logger::$threshold)
			return TRUE;
		$layout_callback = $this->layout_callback;
		$messages = $layout_callback ( $event, FALSE );
		$handler = $this->get_handler ( $event->group );
		if ($handler)
			fwrite ( $handler, "{$messages}\r\n" );
	}
	public function write_more($events) {
		$layout_callback = $this->layout_callback;
		$messages = NULL;
		if (is_array ( $events )) {
			$messages = array ();
			foreach ( $events as $type => $arr ) {
				if (! $arr)
					continue;
				foreach ( $arr as $event ) {
					$this->write ( $event );
				}
			}
		}
	}
	public function __destruct() {
		if ($this->run && $this->handles) {
			foreach ( $this->handles as $handle )
				fclose ( $handle );
		}
	}
}
/**
 * 基于scribe的日志写入器
 *
 * @author leon
 *        
 */
class Logger_Writer_Scribe extends Logger_Writer {
	protected $transport;
	protected $scribe_client;
	protected $host;
	protected $port = 1463;
	public function init() {
		$GLOBALS ['THRIFT_ROOT'] = THRIFT_ROOT;
		include_once THRIFT_ROOT . '/scribe.php';
		include_once THRIFT_ROOT . '/transport/TSocket.php';
		include_once THRIFT_ROOT . '/transport/TFramedTransport.php';
		include_once THRIFT_ROOT . '/protocol/TBinaryProtocol.php';
		$socket = new \TSocket ( $this->host, $this->port, true );
		$this->transport = new \TFramedTransport ( $socket );
		// $protocol = new TBinaryProtocol($trans, $strictRead=false,
		// $strictWrite=true)
		$protocol = new \TBinaryProtocol ( $this->transport, false, false );
		// $scribe_client = new scribeClient($iprot=$protocol, $oprot=$protocol)
		$this->scribe_client = new \scribeClient ( $protocol, $protocol );
		$this->transport->open ();
		$this->run = TRUE;
	}
	public function write(Logger_Logging_Event $events) {
		if (! $this->run)
			$this->init ();
		$layout_callback = $this->layout_callback;
		$msg = array (
				'category' => $events->group,
				'message' => $layout_callback ( $events ) 
		);
		$messages = array (
				new LogEntry ( $msg ) 
		);
		return $this->scribe_client->Log ( $messages );
	}
	public function write_more($events) {
		if (! $this->run)
			$this->init ();
		$layout_callback = $this->layout_callback;
		$messages = array ();
		foreach ( $events as $type => $arr ) {
			if (! $arr)
				continue;
			foreach ( $arr as $event ) {
				if ($event->numLevel < Logger::$threshold)
					continue;
				$messages [] = new LogEntry ( array (
						'category' => $event->group,
						'message' => $layout_callback ( $event ) 
				) );
			}
		}
		return $this->scribe_client->Log ( $messages );
	}
	public function __destruct() {
		if ($this->run)
			$this->transport->close ();
	}
}
