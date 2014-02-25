<?php
namespace Dou\Model;
use Xe\Model;
use Xe\Page;
use Xe\Strings;
class product extends Model{
	/**
	 +----------------------------------------------------------
	 * 获取商品列表
	 +----------------------------------------------------------
	 */
	function get_recommend_product($cat_id = '', $num = '',$lang)
	{
		$where='';
		if ($cat_id)
		{
			$where = " WHERE cat_id = $cat_id ";
		}
		if ($num)
		{
			$limit = " LIMIT $num";
		}
	
		$sql = "SELECT id, product_name, price, product_image FROM " . $this->db->getTable('product') . $where . "ORDER BY home_sort DESC, id DESC" . $limit;
		$rs = $this->db->query($sql);
		foreach($rs as $row)
		{
			$url = url('product','show', $row['id']);
			$image = explode(".", $row['product_image']);
			$thumb = ROOT_URL . $image[0] . "_thumb." . $image[1];
			$product['product_image'] = ROOT_URL . $row['product_image'];
			$price = $row['price'] > 0 ? $this->price_format($row['price'],2,$lang['price_format']) : $lang['price_discuss'];
			$product_list[] = array (
					"id" => $row['id'],
					"name" => $row['product_name'],
					"price" => $price,
					"thumb" => $thumb,
					"url" => $url
			);
		}
		return $product_list;
	}
	/**
	 +----------------------------------------------------------
	 * 格式化商品价格
	 +----------------------------------------------------------
	 */
	function price_format($price = '', $decimals = '2',$price_format='￥d% 元')
	{
		$price = number_format($price, $decimals);
		$price_format = preg_replace('/d%/Ums', $price, $price_format);
		return $price_format;
	}
	function getCateInfo($cid=0){
		if ($cid) {
			$sql = "SELECT * FROM " . $this->db->getTable ( 'product_category' ) . " WHERE cat_id = ? LIMIT 1";
			$cate_info = $this->db->query ( $sql, $cid );
			return $cate_info?:array();
		}else {
			return array();
		}
		
	}
	
	/**
	 +----------------------------------------------------------
	 * 分页显示整体产品图
	 +----------------------------------------------------------
	 */
	
	function getProductList($cid= 0 ,$page= 1,$page_size= 10,$price_format){
		
		$cate_info = array ();
		$where = $cid?" WHERE cat_id = {$cid}":'';
		$sql = "SELECT COUNT(id) FROM " . $this->db->getTable ( 'product' ) . $where . ' LIMIT 1';
		$rows = $this->db->query ( $sql );
		$pager = new Page ( $rows,"/product/index/{$cid}/%s", $page_size , $page );
		/* 产品列表 */
		$limit = " LIMIT $pager->limit";
		if ($cid) {
			$where = " WHERE p.cat_id = $cid ";
		}
		$sql = "SELECT p.id,p.cat_id, p.product_name, p.price, p.content,
				p.product_image, p.add_time, p.description,c.cat_name FROM ".
						$this->db->getTable ( 'product' ) . " p ".
						"LEFT JOIN ".$this->db->getTable ( 'product_category' ) . " c ON p.cat_id=c.cat_id ".
						$where . "ORDER BY p.id DESC" . $limit;
		$rs = $this->db->query ( $sql );
		$prodcut_list=array();
		if(!$rs)return $prodcut_list;
		foreach ( $rs as $row ) {
			$url = url( 'product','show', $row ['id'] );
			$add_time = date ( "Y-m-d", $row ['add_time'] );
			$description = $row ['description'] ? $row ['description'] : Strings::truncate($row ['content'], 150 );
			/* 生成缩略图的文件名 */
			$image = explode ( ".", $row ['product_image'] );
			$thumb = ROOT_URL . $image [0] . "_thumb." . $image [1];
			//debug($rs,$image,$thumb);
			if ($row ['price'] > 0) {
				$price=$this->price_format($row['price'],'2',$price_format);
			} else {
				$price = getLang('price_discuss');
			}
			$prodcut_list[] = array (
					"id" => $row ['id'],
					"cat_id" => $row ['cat_id'],
					"name" => $row ['product_name'],
					"price" => $price,
					"thumb" => $thumb,
					"cat_name" => $row ['cat_name'],
					"add_time" => $add_time,
					"description" => $description,
					"url" => $url
			);
		}
		return array($prodcut_list,$pager);
		
	}
	/**
	 +----------------------------------------------------------
	 * 标题
	 +----------------------------------------------------------
	 */
	function page_title($module, $cat_id = '', $title = '')
	{
		//如果是单页面，则只执行这一句
		if ($module == 'page')
		{
			return $title . " | " . getSiteCfg('site_name');
		}
		$page_title='';
		//如果存在标题
		if ($title)
		{
			$page_title .= $title . " | ";
		}
		//如果存在分类
		if ($cat_id)
		{
			$cat_name = $this->db->query("SELECT cat_name FROM " . $this->db->getTable($module) . " WHERE cat_id = '" . $cat_id . "' LIMIT 1");
			$page_title .= $cat_name . " | ";
		}
		//主栏目
		if (($main=\getLang($module)))
		{
			$page_title .= $main . " | ";
		}
		$page_title .=getSiteCfg('site_name');
		return $page_title;
	}
	/**
	 +----------------------------------------------------------
	 * 获取商品分类
	 +----------------------------------------------------------
	 */
	function get_product_category($current_id)
	{
		$sql = "SELECT cat_id, cat_name FROM " . $this->db->getTable('product_category') . " ORDER BY sort ASC,cat_id ASC";
		$rs = $this->db->query($sql);
		foreach ($rs as $row){
			
			$url = url('product','index', $row['cat_id']);
			$cur = (new nav)->is_current('product','index', $row['cat_id'], 'product','index', $current_id);
			$product_category[] = array (
					"cat_id" => $row['cat_id'],
					"cat_name" => $row['cat_name'],
					"cur" => $cur,
					"url" => $url
			);
		}
		return $product_category;
	}
	function getProduct($pid){
		$sql="SELECT * FROM  ". $this->db->getTable('product') ." WHERE id=? LIMIT 1";
		$rs= $this->db->query($sql,$pid);
		$image = explode ( ".", $rs ['product_image'] );
		$thumb = ROOT_URL . $image [0] . "_thumb." . $image [1];
		$price_format='￥d% 元';
	    $rs['price']=preg_replace('/d%/Ums', $rs['price'], $price_format);
	    $rs["thumb"] =$thumb;
		return $rs;
	}
	
}