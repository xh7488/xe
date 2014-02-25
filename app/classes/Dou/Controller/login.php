<?php
namespace Dou\Controller;
use \Xe\FormValidator;
use Dou\Model\emp;
class login extends Ini {
	function actionIndex() {
		if (! empty ( $_POST )) {
					$this->login();
		} else {
			view ( 'login/index' );
		}
	}
	protected function login()
	{
		if(empty($_POST['name']) || empty($_POST['password']))
		{
			redirect("登录信息不能为空！","/emp");
		}
		//表单验证
		$vd = new FormValidator();
		$vd->set_rules('name', '登录名',  'require|length(5,30)');
		$vd->set_rules('password', '密码',  'require|length(6,18)');
		
		//debug($vd->is_valid($_POST),$vd);
		//$vd->set_rules('p2', '密码验证', 'equa(p)', '两次密码输入不一致');
		if (!$vd->is_valid($_POST))
		{
			view ( 'login/index',array('errors'=>$vd->get_errors()));
			exit();
		}
		$model_emp=new emp();
		$id=$_POST['name'];//取得id。
		$password=$_POST['password'];//取得密码。
		//实例化一个adminservice方法。
		$rs=$model_emp->login($id, $password);
		if($rs===TRUE)
		{
			header("location:/");
		}else{
			redirect($rs,'/login');
		}
		exit();
	}
}