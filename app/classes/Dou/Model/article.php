<?php
namespace Dou\Model;
use Xe\Model;
class article extends Model {
	/**
	 * +----------------------------------------------------------
	 * 获取文章列表
	 * +----------------------------------------------------------
	 */
	function get_recommend_article($cat_id = '', $num = '') {
		$where=$limit="";
		if ($cat_id) {		
			$where = " WHERE cat_id = $cat_id ";
		}
		if ($num) {
			$limit = " LIMIT $num";
		}
		
		$sql = "SELECT id, title, add_time FROM " . $this->db->getTable ( 'article' ) .$where."ORDER BY home_sort DESC, id DESC" . $limit;
		$rs = $this->db->query ( $sql );
		foreach ($rs as $row) {
			$url =url ( 'article','show', $row ['id'] );
			$add_time_short = date ( "m-d", $row ['add_time'] );
			
			$article_list [] = array (
					"id" => $row ['id'],
					"title" => $row ['title'],
					"add_time_short" => $add_time_short,
					"url" => $url 
			);
		}
		return $article_list;
	}
	/**
	 +----------------------------------------------------------
	 * 获取文章分类
	 +----------------------------------------------------------
	 */
	function get_article_category($cid='')
	{
		$sql = "SELECT cat_id, cat_name FROM " . $this->db->getTable('article_category') . " ORDER BY sort ASC,cat_id ASC";
		$rs = $this->db->query($sql);
		foreach ($rs as $row)
		{
			$url =url('article','index', $row['cat_id']);
			$cur = (new nav)->is_current('article','index', $row['cat_id'], 'article','index', $cid);
			$article_category[] = array (
					"cat_id" => $row['cat_id'],
					"cat_name" => $row['cat_name'],
					"cur" => $cur,
					"url" => $url
			);
		}
		return $article_category;
	}
	
	
	function getCateInfo($cid=''){
		if ($cid) {
			$sql = "SELECT * FROM " . $this->db->getTable ( 'article_category' ) . " WHERE cat_id = ? LIMIT 1";
			$cate_info = $this->db->query ( $sql, $cid );
			return $cate_info?:array();
		}else {
			return array();
		}
	}
	
	
	function getArticleList($cid=0 ,$page= 1,$page_size= 10){
		$where = $cid?" WHERE cat_id = {$cid}":'';
		$sql = "SELECT COUNT(id) FROM " . $this->db->getTable ( 'article' ) . $where . ' LIMIT 1';
		$rows = $this->db->query ( $sql );
		$pager = new \Xe\Page ( $rows,"/article/index/{$cid}/%s", $page_size , $page );
		$limit = " LIMIT $pager->limit";
		if ($cid) {
			$where = " WHERE cat_id = $cid ";
		}
		$sql = "SELECT id, title, content, cat_id, add_time, click, description FROM " . $this->db->getTable('article') . $where . " ORDER BY id DESC" . $limit;
		$rs = $this->db->query($sql);
		$article_list=array();
		if(!$rs)return $article_list;
	    foreach ($rs as $row){
	    	$url = url( 'article','show', $row ['id'] );
	    	$add_time = date("Y-m-d", $row['add_time']);
	    	$add_time_short = date("m-d", $row['add_time']);
	    	$description = $row ['description'] ? $row ['description'] : \Xe\Strings::truncate($row ['content'], 150 );
	    	$article_list[]=array(
	    		"id"=>$row['id'],
	    		"cat_id" => $row['cat_id'],
	    		"title" => $row['title'],
	    		"add_time" => $add_time,
	    		"add_time_short" => $add_time_short,
	    		"click" => $row['click'],
	    		"description" => $description,
	    		"url"=>$url
	    	);
	    	
	    }
	    return array($article_list,$pager);
	}
	    function getArticle($cid){
	    	$sql="SELECT * FROM  ". $this->db->getTable('article') ." WHERE id=? LIMIT 1";
	    	$rs= $this->db->query($sql,$cid);
	    	return $rs;
	    }
	
	
}