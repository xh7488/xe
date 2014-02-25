<?php
namespace Xe;
class DBSession extends \SessionHandler
{
	protected $db;
	
	public function __construct(){
		//session_name('sess');
	}
	
	public function open($save_path, $session_name)
	{
		$this->db=getDB();
		return TRUE;
	}
	public function close()
	{
		unset($this->db);
	}
	public function read($key)
	{
		$rs=$this->db->query("SELECT * FROM `sessions` WHERE sess_id=? LIMIT 1",$key);
		
		return $rs['data'];
	}
	public function write($key,$data)
	{
		$ip=ip2long(Util::getClientIP());
		$expire="(NOW()+INTERVAL ".ini_get('session.gc_maxlifetime')." SECOND)";
		$rs=$this->db->exec("INSERT INTO sessions VALUES(?,?,?,{$expire}) ON DUPLICATE KEY UPDATE `data`=?,expire={$expire};",$key,$ip,$data,$data);
		return ($rs!==FALSE)?TRUE:FALSE;
	}
	public function gc($expireTime)
	{
		$rs=$this->db->exec("DELETE FROM `sessions` WHERE expire<NOW()");
		return ($rs!==FALSE)?TRUE:FALSE;
	}
	public function destroy($key)
	{
		$rs=$this->db->exec("DELETE FROM sessions WHERE sess_id=?",$key);
		return ($rs!==FALSE)?TRUE:FALSE;
	}
	
}