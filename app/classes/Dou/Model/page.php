<?php
namespace Dou\Model;
use Xe\Model;
class page extends Model {
	function get_page_info($id = 0) {
		$sql = "SELECT * FROM " . $this->db->getTable ( 'page' ) . " WHERE id=? limit 1";
		$page = $this->db->query ( $sql, $id );
		
		if ($page) {
			$page ['url'] = url ( 'page', 'index', $id );
		}
		
		return $page;
	}
	/**
	 * +----------------------------------------------------------
	 * 获取指定分类单页面列表
	 * +----------------------------------------------------------
	 */
	function get_page_list($parent_id = '', $current_id) {
		if ($parent_id) {
			$where = " where parent_id = $parent_id ";
		}
		$page_list = array ();
		$sql = "SELECT * FROM " . $this->db->getTable ( 'page' ) . $where ." ORDER BY id ASC";
		$rs = $this->db->query ( $sql );
		foreach ( $rs as $row ) {
			$url = url ( 'page', 'index', $row ['id'] );
			$cur = $row ['id'] === $current_id;
			// ( 'page', $row ['id'], 'page', $current_id );
			$page_list [] = array (
					"id" => $row ['id'],
					"parent_id" => $row ['parent_id'],
					"page_name" => $row ['page_name'],
					"cur" => $cur,
					"url" => $url 
			);
		}
		return $page_list;
	}
}