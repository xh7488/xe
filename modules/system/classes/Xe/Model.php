<?php
namespace Xe;
class Model{
	protected $db;
	public function __construct()
	{
		$this->db=getDB();
	}
}