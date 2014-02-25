<?php
namespace Dou\admin\Model;
use Xe\Model;
class Check extends Model{
	/**
	 * 判断用户名是否规范
	 */
	function is_username($username) {
		if (preg_match ( "/^[a-zA-Z]{1}([0-9a-zA-Z]|[._]){3,19}$/", $username )) {
			return true;
		}
	}
	
	/**
	 * 判断密码是否规范
	 */
	function is_password($password) {
		if (preg_match ( "/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$/", $password )) {
			return true;
		}
	}
	
	/**
	 * 判断验证码是否规范
	 */
	function is_captcha($captcha) {
		if (preg_match ( "/^[A-Za-z0-9]{4}$/", $captcha )) {
			return true;
		}
	}
	
	/**
	 * 判断别名是否规范
	 */
	function is_unique_id($unique) {
		if (preg_match ( "/^[a-z0-9-]+$/", $unique )) {
			return true;
		}
	}
	
	/**
	 * 判断价格是否规范
	 */
	function is_price($price) {
		if (preg_match ( "/^[0-9.]+$/", $price )) {
			return true;
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 判断目录是否可写
	 * +----------------------------------------------------------
	 */
	function is_write($dir) {
		// 判断目录是否存在
		if (file_exists ( $dir )) {
			// 判断目录是否可写
			if ($fp = @fopen ( "$dir/test.txt", 'w' )) {
				@fclose ( $fp );
				@unlink ( "$dir/test.txt" );
				$writeable = 1;
			} else {
				$writeable = 0;
			}
		} else {
			$writeable = 2;
		}
		
		return $writeable;
	}
	//将指定的表名加上前缀后返回
	function table($str)
	{
		return '`' . $this->prefix . $str . '`';
	}
	/**
	 +----------------------------------------------------------
	 * 获取管理员日志
	 +----------------------------------------------------------
	 */
	function create_admin_log($action)
	{
		$create_time = time();
		$ip = $this->get_ip();
	
		$sql = "INSERT INTO " . $this->db->getTable('admin_log') . " (id, create_time, user_id, action ,ip)" .
				" VALUES (NULL, '$create_time', '$_SESSION[user_id]', '$action', '$ip')";
		$this->db->query($sql);
	}
	/**
	 +----------------------------------------------------------
	 * 获取真实IP地址
	 +----------------------------------------------------------
	 */
	function get_ip()
	{
		$ip = false;
		if (!empty ($_SERVER["HTTP_CLIENT_IP"]))
		{
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty ($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip)
			{
				array_unshift($ips, $ip);
				$ip = FALSE;
			}
			for ($i = 0; $i < count($ips); $i++)
			{
			if (!preg_match("/^(10|172\.16|192\.168)\./", $ips[$i]))
			{
			$ip = $ips[$i];
			break;
			}
			}
			}
			return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	
}