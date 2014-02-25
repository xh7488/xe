<?php
namespace Dou\Model;
use Xe\Model;
class show extends Model {
	
	/**
	 * +----------------------------------------------------------
	 * 获取下级幻灯列表
	 * +----------------------------------------------------------
	 */
	function get_show_list() {
		$sql = "SELECT * FROM " . $this->db->getTable ( 'show' ) . " ORDER BY sort ASC, id ASC";
		$rs=$this->db->query($sql);
		$show_list=array();
		if(!$rs)return $show_list;
		foreach($rs as $row)
		{
			$image = explode ( ".", $row ['show_img'] );
			$thumb = $image [0] . "_thumb." . $image [1];
				
			$show_list [] = array (
					"id" => $row ['id'],
					"show_name" => $row ['show_name'],
					"show_link" => $row ['show_link'],
					"show_img" => $row ['show_img'],
					"thumb" => $thumb,
					"sort" => $row ['sort']
			);
		}
		
		return $show_list;
	}
	
	/**
	 * +----------------------------------------------------------
	 * 获取下级幻灯列表
	 * +----------------------------------------------------------
	 */
	function get_link_list() {
		$sql = "SELECT * FROM " . $this->db->getTable ( 'link' )  . " ORDER BY sort ASC, id ASC";
		$rs=$this->db->query($sql);
		$link_list=array();
		if(!$rs)return $link_list;
		foreach($rs as $row)
		{
			$link_list [] = array (
					"id" => $row ['id'],
					"link_name" => $row ['link_name'],
					"link_url" => $row ['link_url'],
					"sort" => $row ['sort'] 
			);
		
		}
		
		return $link_list;
	}
}