<?php
namespace Dou\Controller;
use Dou\Model\nav;
use Dou\Model\product;
use Dou\Model\article as ap;
class Article extends Ini{
	function actionIndex($cid='',$page='1'){
		$cid = intval ( $cid );
		$page = intval ( $page );
		if (empty ( $page ))
			$page = '1';
		$nav = new nav ();
		$mp=new ap();
		$product = new product ();
		$cate_info = $mp->getCateInfo ( $cid );
		if (! $cate_info) {
			$cate_info ['keywords'] = $this->cfg ['site_keywords'];
			$cate_info ['description'] = $this->cfg ['site_description'];
			$cid = 0;
		}
		$rs = $mp->getArticleList ( $cid, $page, $this->cfg ['display_product']);
		$pager = FALSE;
		$article_list = array ();
		if ($rs) {
			list ( $article_list, $pager ) = $rs;
		}
		$here = $nav->ur_here ( 'article', $cid, '', 'index' );
		$data=array(
			'article_list'=>$article_list,
			'pager'=>$pager,
			'keywords' => $cate_info ['keywords'],
			'description' => $cate_info ['description'],
			'ur_here' => $here,
			'article_category'=>$mp->get_article_category($cid)
		);
		view('article/index',$data);
}

function actionShow($pid = 0) {
	$pid = intval ( $pid );
	if (! $pid) {
		header ( "Location: /" );
		exit ();
	}
	$mp = new ap();
	$nav = new nav ();
	$article = $mp->getArticle ( $pid );
	if (! $article) {
		header ( "Location: /" );
		exit ();
	}
	$click = $article['click'] + 1;
	$sql = "update " . $this->db->getTable('article') . " SET click = ? WHERE id = ?";
	$rs=$this->db->exec($sql,$click,$pid);
	$defined=array();
	if (! empty ( $article ['defined'])) {
		$defined_article = explode ( ',', $article ['defined'] );
		foreach ( $defined_article as $row ) {
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
			'article' => $article,
			'article_category' => $mp->get_article_category ( $article ['cat_id'] ),
			'ur_here' => $nav->ur_here ( 'article', $article ['cat_id'], '', 'index' ),
			'defined' => $defined
	);
	view ( 'article/show', $data );
}
}