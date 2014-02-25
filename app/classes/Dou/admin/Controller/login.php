<?php

namespace Dou\admin\Controller;

use Dou\admin\Model\Check;
class login extends Ini {
	
	function actionIndex() {
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Cache-Control: no-cache, must-revalidate" );
		header ( "Pragma: no-cache" );
		view ( 'admin/login' );
	}
	function actionPost() {
		debug($_POST);
		$check=new Check();
		if ($check->is_captcha ( trim ( $_POST ['vcode'] ) ) && $this->cfg ['captcha']) {
			$_POST ['vcode'] = strtoupper ( trim ( $_POST ['vcode'] ) );
		}
		if (! $_POST ['user_name']) {
			$check->dou_msg ( $this->lang ['login_input_wrong'], 'login.php', 'out' );
			exit ();
		} elseif (md5 ( $_POST ['vcode'] . DOU_SHELL ) != $_SESSION ['captcha'] && $this->cfg ['captcha']) {
			$check->dou_msg ( $this->lang ['captcha_wrong'], 'login.php', 'out' );
			exit ();
		}
		
		$_POST ['user_name'] = $check->is_username ( trim ( $_POST ['user_name'] ) ) ? trim ( $_POST ['user_name'] ) : '';
		$_POST ['password'] = $check->is_password ( trim ( $_POST ['password'] ) ) ? trim ( $_POST ['password'] ) : '';
		$query = $check->select ( $check->table ( admin ), '*', "user_name = '$_POST[user_name]'" );
		$user = $check->fetch_array ( $query );
		if (! is_array ( $user )) {
			$check->create_admin_log ( $this->lang ['login_action'] . ": " . $_POST ['user_name'] . " ( " . $this->lang ['login_user_name_wrong'] . " ) " );
			$check->dou_msg ( $this->lang ['login_input_wrong'], 'login.php', 'out' );
			exit ();
		} elseif (md5 ( $_POST ['password'] ) != $user ['password']) {
			if ($_POST ['password']) {
				$check->create_admin_log ( $this->lang ['login_action'] . ": " . $_POST ['user_name'] . " ( " . $this->lang ['login_password_wrong'] . " ) " );
			}
			$check->dou_msg ( $this->lang ['login_input_wrong'], 'login.php', 'out' );
			exit ();
		}
		
		$_SESSION ['user_id'] = $user ['user_id'];
		$_SESSION ['user_name'] = $user ['user_name'];
		$_SESSION ['shell'] = md5 ( $user ['user_name'] . $user ['password'] . DOU_SHELL );
		$_SESSION ['ontime'] = time ();
		
		$last_login = time ();
		$last_ip = $check->get_ip ();
		$sql = "update " . $check->table ( 'admin' ) . " SET last_login = '$last_login', last_ip = '$last_ip' WHERE user_id = " . $user ['user_id'];
		$check->query ( $sql );
		
		$check->create_admin_log ( $this->lang ['login_action'] . ": " . $this->lang ['login_success'] );
		
		header ( "Location: " . ROOT_URL . ADMIN_PATH . "/index.php\n" );
		exit ();
	}
	function actionLogout()
	{
		\Xe\Sess::start();
		session_destroy();
		header('Location: /admin/login');
		exit();
	}
	function actionCaptcha() {
		// echo ' ';
		// 清除之前出现的多余输入
		// ob_end_clean();
		// 实例化验证码
		\Xe\Sess::start ();
		$captcha = new \Xe\Captcha ( 70, 25 );
		$captcha->create_captcha ();
	}
}