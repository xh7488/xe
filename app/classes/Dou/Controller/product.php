<?php
namespace Dou\Controller;
use Dou\Model\product as mp;
use Dou\Model\nav;
class product extends Ini {
	function actionIndex($cid = '', $page = '1') {
		$cid = intval ( $cid );
		$page = intval ( $page );
		if (empty ( $page ))
			$page = '1';
		$product = new mp ();
		$nav = new nav ();
		$cate_info = $product->getCateInfo ( $cid );
		if (! $cate_info) {
			$cate_info ['keywords'] = $this->cfg ['site_keywords'];
			$cate_info ['description'] = $this->cfg ['site_description'];
			$cid = 0;
		}
		$rs = $product->getProductList ( $cid, $page, $this->cfg ['display_product'], $this->lang ['price_format'] );
		$pager = FALSE;
		$product_list = array ();
		if ($rs) {
			list ( $product_list, $pager ) = $rs;
		}
		$title = $product->page_title ( 'product_category', $cid );
		$here = $nav->ur_here ( 'product', $cid, '', 'index' );
		$category = $product->get_product_category ( $cid );
		$data = array (
				'product_list' => $product_list,
				'pager' => $pager,
				'page_title' => $title,
				'keywords' => $cate_info ['keywords'],
				'description' => $cate_info ['description'],
				'ur_here' => $here,
				'product_category' => $category 
		);
		view ( 'product/index', $data );
	}
	function actionShow($pid = 0) {
		$pid = intval ( $pid );
		if (! $pid) {
			header ( "Location: /" );
			exit ();
		}
		$mp = new mp ();
		$nav = new nav ();
		$product = $mp->getProduct ( $pid );
		if (! $product) {
			header ( "Location: /" );
			exit ();
		}
		$defined=array();
		if (! empty ( $product ['defined'])) {
			$defined_product = explode ( ',', $product ['defined'] );
			foreach ( $defined_product as $row ) {
				$row = explode ( '：', str_replace ( ":", "：", $row ) );
				if ($row ['1']) {
					$defined [] = array (
							"key" => $row ['0'],
							"value" => $row ['1'] 
					);
				}
			}
		}
		$data = array (
				'product' => $product,
				'product_category' => $mp->get_product_category ( $product ['cat_id'] ),
				'ur_here' => $nav->ur_here ( 'product', $product ['cat_id'], '', 'index' ),
				'defined' => $defined 
		);
		view ( 'product/show', $data );
	}
}