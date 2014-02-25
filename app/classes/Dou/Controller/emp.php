<?php
namespace Dou\Controller;
use Dou\Model\emp;

class emp extends Auth {
	function __construct() {
		parent::__construct();
	}
	function actionIndex() {
		$Model_emp = new emp ();
		$rs = $Model_emp->get_lists (10);
		view ( 'emp/index', $rs );
	}
	function actionAdd() {
		if (! empty ( $_POST )) {
			$Model_emp = new emp ();
			$rs = $Model_emp->add ( $_POST );
			redirect(($rs!==FALSE)?"添加成功!":"添加失败!","/emp");
		} else {
			view ( 'emp/add' );
		}
	}
	function actionModify() {
		$Model_emp = new emp ();
		if($_POST)
		{
			$rs=$Model_emp->modify($_POST);
			redirect(($rs!==FALSE)?"修改成功!":"修改失败!","/emp");
		}else{
			$id = !empty($_GET ['id'])?intval($_GET ['id']):0;
			// 通过ID来得到该用户的其他信息。
			$rs = $Model_emp->getByID ( $id );
			view('emp/modify',array('arr'=>$rs));
		}
	}
	function actionDel()
	{
		//接收用户要删除的ID
		$Model_emp=new emp();
		$id=!empty($_GET['id'])?intval($_GET['id']):0;
		if($id>0){
			$rs=$Model_emp->del($id);
			redirect(($rs!==FALSE)?"删除成功!":"删除失败!","/emp");
		}else{
			redirect("删除失败!","/emp");
		}
	}
	function actionLogout()
	{
		session_destroy();
		header("Location: /login");
		exit();
	}
}