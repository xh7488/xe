<?php
namespace Xe;
class Util {
	/**
	 * 判断是否为ajax调用
	 */
	static function isAjax() {
		return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) and strtolower ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest');
	}
	/**
	 * 获取客户端IP地址
	 * 
	 * @return string
	 */
	static function getClientIP() {
		if (getenv ( 'HTTP_CLIENT_IP' )) {
			$client_ip = getenv ( 'HTTP_CLIENT_IP' );
		} elseif (getenv ( 'HTTP_X_FORWARDED_FOR' )) {
			$client_ip = getenv ( 'HTTP_X_FORWARDED_FOR' );
		} elseif (getenv ( 'REMOTE_ADDR' )) {
			$client_ip = getenv ( 'REMOTE_ADDR' );
		} else {
			$client_ip = $_SERVER ['REMOTE_ADDR'];
		}
		return $client_ip;
	}
	/**
	 * 获取服务器端IP地址
	 * 
	 * @return string
	 */
	static function getServerIP() {
		if (isset ( $_SERVER )) {
			if ($_SERVER ['SERVER_ADDR']) {
				$server_ip = $_SERVER ['SERVER_ADDR'];
			} else {
				$server_ip = $_SERVER ['LOCAL_ADDR'];
			}
		} else {
			$server_ip = getenv ( 'SERVER_ADDR' );
		}
		return $server_ip;
	}
}