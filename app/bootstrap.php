<?php
define ( 'APP_PATH', __DIR__ );
define ( 'ROOT_PATH', dirname ( APP_PATH ) );
define ( 'SITE_PATH', ROOT_PATH );
define ( 'MODULES_PATH', ROOT_PATH . '/modules' );
define ( 'ROOT_URL', 'http://proj1/' );
$modules = array (
		'app' => APP_PATH,
		'system' => ROOT_PATH . '/modules/system' 
);
set_include_path ( implode ( PATH_SEPARATOR, $modules ) );
spl_autoload_register ( 'autoload' );

// 初始化
header ( "Content-type: text/html; charset=utf-8" );
date_default_timezone_set ( 'Asia/Shanghai' );
mb_internal_encoding ( 'utf-8' );

// ini_set('session.gc_divisor',1);
// session.save_handler = redis
// session.save_path = "tcp://192.168.1.60:6379?weight=1, tcp://host2:6379?weight=2&timeout=2.5, tcp://host3:6379?weight=2"

// 日志初始化
\Xe\Logger::init ();
// 异常错误处理
$ini_set = getCfg ( 'main', 'ini_set' );
$error_reporting = ! empty ( $ini_set ['error_reporting'] ) ? $ini_set ['error_reporting'] : 0;
error_reporting ( $error_reporting );
foreach ( $ini_set as $k => $v )
	ini_set ( $k, $v );

set_error_handler ( 'error_handler', $error_reporting );
set_exception_handler ( 'exception_handler' );
dispatch ($namespace_prefix);

/**
 * PHP错误接收函数，转为异常，异常再由异常处理接收
 *
 * @param $errno integer        	
 * @param $errstr string        	
 * @param $errfile string        	
 * @param $errline string        	
 * @throws ErrorException
 *
 *
 */
function error_handler($errno, $errstr, $errfile, $errline) {
	throw new ErrorException ( $errstr, 0, $errno, $errfile, $errline );
}

/**
 * 接收异常
 *
 * @param $e Exception        	
 *
 */
function exception_handler(Exception $e) {
	\Xe\ErrorHandler::exec ( $e );
}

// ==========================================================================================
function autoload($class) {
	$class = str_replace ( array (
			'_','\\'
	), '/', $class );
	include ($file = "classes/{$class}.php");
	 //debug(class_exists($class,FALSE),$file,get_include_path());;
	return class_exists ( $class, FALSE );
}
function url($c, $a = 'index', $args = array()) {
	if (! is_array ( $args ))
		$args = array (
				$args 
		);
	return ROOT_URL . "{$c}/{$a}/" . implode ( '/', $args );
}
function getCfg($pack, $key = NULL, $default = NULL) {
	static $confs = array ();
	if (! isset ( $confs [$pack] )) {
		$arr = include "conf/{$pack}.php";
		$confs [$pack] = $arr ? $arr : NULL;
	}
	if ($key === NULL) {
		return $confs [$pack];
	} else {
		return isset ( $confs [$pack] [$key] ) ? $confs [$pack] [$key] : $default;
	}
}
function getLang($key = NULL,$default=NULL,$file='i18n/zh_cn/web.lang') {
	static $langs;
	if (! isset ( $langs[$file] )) {
		$langs[$file] = getCfg ( $file );
		$lang=&$langs[$file];
		if(empty($_GET['g']))$lang ['copyright'] = preg_replace ( '/d%/Ums', getSiteCfg('site_name'), $lang ['copyright'] );
	}
	if(is_null($key))
	{
		return $langs[$file] ;
	}else{
		$lang=&$langs[$file];
		return isset($lang[$key])?$lang[$key]:$default;
	}
}
function getAdminLang($key = NULL,$default=NULL)
{
	return getLang($key,$default,'i18n/zh_cn/admin.lang');
}
function getSiteCfg($key=NULL,$default=NULL) {
	static $cfg;
	if (! isset($cfg)) {
		$db=getDB();
		$rs = $db->query ( "SELECT `name`,`value` FROM " . $db->getTable ( 'config' ) );
		if (! empty ( $rs )) {
			$ret = array ();
			foreach ( $rs as $v ) {
				$ret [$v ['name']] = $v ['value'];
			}
			$cfg = $ret;
		} else {
			$cfg=array();
		}
	}
	if(is_null($key))
	{
		return $cfg;
	}else{
		return isset($cfg[$key])?$cfg[$key]:$default;
	}
	
}
function view($__view, $__data = array()) {
	if (isset ( $GLOBALS ['global_theme_datas'] )) {
		extract ( $GLOBALS ['global_theme_datas'] );
	}
	extract ( $__data );
	$__view = 'views/' . str_replace ( array (
			'.',
			'_' 
	), '/', $__view ) . '.php';
	include $__view;
}
function dispatch($namespace_prefix='\\') {
	paseRemainArgs ();
	// controller
	$controler_name = strtolower ( isset ( $_GET ['c'] ) ? $_GET ['c'] : 'welcome' );
	$controller_class = (empty($_GET['g']))?"{$namespace_prefix}Controller\\{$controler_name}":"{$namespace_prefix}{$_GET['g']}\\Controller\\{$controler_name}";
// 	debug($controler_name,$_GET,$controller_class);
	if (! class_exists ( $controller_class )) {
		exit ( "page not found." );
	}
	$controller = new $controller_class ();
	// $controller=new Controller_login();
	// action
	$action_name = ! empty ( $_GET ['a'] ) ? $_GET ['a'] : 'index';
	$method = "action{$action_name}";
	if (! method_exists ( $controller, $method )) {
		exit ( "page not found." );
	}
	// $controller->$method();
	call_user_func_array ( array (
			$controller,
			$method 
	), $_GET ['args'] );
}
function paseRemainArgs() {
	if (count ( $_GET ) > 2) {
		next ( $_GET );
		next ( $_GET );
		while ( $args= key ( $_GET ) ) {
			$args2=trim ( $args, ' /' );
			unset ( $_GET [$args] );
			if($args2===''){
				$_GET ['args']=array();
				break;
			}
			$args2 = explode ( '/', $args2 );
			$_GET ['args'] = $args2;
			break;
		}
	}
	$controller_group=getCfg('main','controller_group');
	
	$c=isset($_GET['c'])?$_GET['c']:getCfg('main','default_controller');
	if(isset($controller_group[$c]))
	{
		$_GET['g']=$_GET['c'];
		$_GET['c']=!empty($_GET['a'])?$_GET['a']:'welcome';
		$_GET['a']=!empty($_GET['args'])?array_shift($_GET['args']):'index';
	}
	if (! isset ( $_GET ['args'] ))
		$_GET ['args'] = array ();
}
function getDB($name = 'main') {
	static $instances = array ();
	if (isset ( $instances [$name] ))
		return $instances [$name];
	$db_conf = getCfg ( 'main', 'db' );
	if (! isset ( $db_conf [$name] ))
		return FALSE;
	$conf = $db_conf [$name];
	return $db_conf [$name] = new \Xe\Dba ( $conf ['dsn'], $conf ['user'], $conf ['password'], $conf ['prefix'], $conf ['options'] );
}
function redirect($msg, $url = '/') {
	exit ( "<script type=\"text/javascript\">alert(\"{$msg}\");window.location=\"{$url}\";</script>" );
}
function msg($text, $url = '', $out = '', $time = '3', $check = '')
{
	$lang=getAdminLang();
	if (!$text)
	{
		$text = $lang['dou_msg_success'];
	}
	$data=array(
			'ur_here'=>$lang['dou_msg'],
			'text'=>$text,
			'url'=>$url,
			'out'=>$out,
			'time'=>$time,
			'check'=>$check,
			'cue'=>preg_replace('/d%/Ums', $time,$lang['dou_msg_cue'])
	);
	view('admin/dou_msg',$data);
	exit ();
}