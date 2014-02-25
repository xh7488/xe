<?php
// 提供一个函数可以获取共有多少页。
namespace Dou\Model;
use Xe\Model;
use Xe\Page;
use Xe\Sess;
class emp extends Model {
	function get_lists($pageSize) {
		$total = $this->db->query ( "select count(id) from emp limit 1" );
		$page = new Page ( $total, $pageSize, 6 );
		$rs = $this->db->query ( "select * from emp limit {$page->limit}" );
		return array (
				'data' => $rs,
				'page' => $page 
		);
	}
	function modify($data) {
		return $this->db->save ( 'emp', $data );
	}
	function getByID($id) {
		return $this->db->query ( "select * from emp where id=? limit 1", $id );
	}
	function add($name, $grade, $email, $salary) {
		$sql = "insert into emp (name,grade,email,salary) values(?,?,?,?)";
		$db = getDB ();
		$rs = $db->exec ( $sql, $name, $grade, $email, $salary );
		return $rs;
	}
	function del($id) {
		return $this->db->exec ( "DELETE FROM emp WHERE id=?", $id );
	}
	function login($name, $password) {
		$user = $this->db->query ( "select password,name from admin where name=? limit 1", $name );
		if (! $user || md5 ( $password ) !== $user ['password']) {
			return "账号或密码不正确！";
		}
		Sess::start();
		$_SESSION ['loginuser'] = $name;
		return TRUE;
	}
}