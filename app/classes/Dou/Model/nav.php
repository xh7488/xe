<?php
namespace Dou\Model;
use Xe\Model;
class nav extends Model {
	/**
	 * +----------------------------------------------------------
	 * 获取导航菜单
	 * $parent_id 默认获取一级导航
	 * $current_module 当前页面模型名称
	 * $current_id 当前页面分类ID
	 * +----------------------------------------------------------
	 */
	function get_nav($parent_id = 0, $current_controller = '', $current_action = '', $current_id = '', $type = 'middle') {
		$sql = "SELECT * FROM " . $this->db->getTable ( 'nav' ) . " WHERE parent_id =? AND type =? ORDER BY sort,id ASC";
		$rs = $this->db->query ( $sql, $parent_id, $type );
		foreach ( $rs as $row ) {
			$url = url ( $row ['controller'], $row ['action'], $row ['guide'] );
			$child = $this->get_nav_child ( $row ['id'] );
			$cur = $this->is_current ( $row ['controller'], $row ['action'], $row ['guide'], $current_controller, $current_action, $current_id );
			$nav_list [] = array (
					"id" => $row ['id'],
					"nav_name" => $row ['nav_name'],
					"url" => $url,
					"sort" => $row ['sort'],
					"cur" => $cur,
					"child" => $child 
			);
		}
		return $nav_list;
	}
	/**
	 * +----------------------------------------------------------
	 * 获取下级导航菜单
	 * +----------------------------------------------------------
	 */
	function get_nav_child($parent_id = 0) {
		$nav_list = array ();
		$sql = "SELECT * FROM " . $this->db->getTable ( 'nav' ) . " WHERE parent_id = '$parent_id' ORDER BY sort, id ASC";
		$rs = $this->db->query ( $sql );
		if (empty ( $rs ))
			return $nav_list;
		foreach ( $rs as $row ) {
			$url = url ( $row ['controller'], $row ['action'], $row ['guide'] );
			$nav_list [] = array (
					"id" => $row ['id'],
					"parent_id" => $row ['parent_id'],
					"nav_name" => $row ['nav_name'],
					"url" => $url 
			);
		}
		return $nav_list;
	}
	/**
	 * +----------------------------------------------------------
	 * 高亮当前菜单
	 * +----------------------------------------------------------
	 */
	function is_current($controller, $action, $id='', $current_controller, $current_action = 'index', $current_id = '') {
		if ($controller === $current_controller) {
			if (empty ( $action )) {
				return TRUE;
			} else {
				if ($action !== $current_action)
					return FALSE;
			}
			return (! $id) ? TRUE : ($id == $current_id);
		}
		return FALSE;
	}
	/**
	 * +----------------------------------------------------------
	 * 当前位置
	 * +----------------------------------------------------------
	 */
	
	
	function ur_here($controller, $cat_id = '', $title = '',$action='index') {
		// 如果是单页面，则只执行这一句
		if ($controller == 'page') {
			return $title;
			exit ();
		}
		// 模块名称
		$ur_here = "<a href=" . url ( $controller,$action,$cat_id) . ">" . getLang($controller.'_'.$action) . "</a>";
		// 如果存在分类
		if ($cat_id) {
			$table=$controller.'_category';
			$cat_name = $this->db->query ( "SELECT cat_name FROM " . $this->db->getTable ( $table ) . " WHERE cat_id = '" . $cat_id . "' LIMIT 1" );
			// 如果存在标题
			if ($title) {
				$category = "<b>></b><a href=" . url ( $controller,$action,$cat_id ) . ">" . $cat_name . "</a>";
			} else {
				//$category = "<b></b>$cat_name";
				$category = "<b></b><a href=" . url ( $controller,$action,$cat_id ) . ">" . $cat_name . "</a>";
			}
			$ur_here.=$category;
		}
		// 如果存在标题
		if ($title) {
			$ur_here.= "<b>. $title .</b>";
		}
		
		return $ur_here;
	}
}