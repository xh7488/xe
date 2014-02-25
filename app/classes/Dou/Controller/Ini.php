<?php
namespace Dou\Controller;
use Xe\Strings;
use Xe\Controller\Controller;
use Dou\Model\nav;
use Dou\Model\show;
class Ini extends Controller {
	protected $db;
	protected $cfg;
	protected $lang;
	function __construct() {
		parent::__construct ();
		$this->db = \getDB ();
		$this->cfg=\getSiteCfg();
		$this->lang=\getLang();
		$this->init ();
	}
	function init() {
		// 判断是否关闭站点
		if ($this->cfg ['site_closed']) {
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><div style=\"margin: 200px; text-align: center; font-size: 14px\"><p>" . $this->lang ['site_closed'] . "</p><p></p></div>";
			exit ();
		}
		$this->renderTheme1();
	}
	protected function renderTheme1() {
		$about = $this->db->query ( "SELECT * FROM " . $this->db->getTable ( 'page' ) . " WHERE id = 1 LIMIT 1" );
		/* 写入到index数组 */
		$index ['about_name'] = $about ['page_name'];
		$index ['about_content'] = Strings::substr ( $about ['content'], 300 );
		$index ['about_link'] = url ( 'page', 'index', '1' );
		$index ['product_link'] = url ( 'product' );
		$index ['article_link'] = url ( 'article' );
		$nav = new nav ();
		$show=new show();
		$data = array (
				'cfg' => $this->cfg,
				'lang' => $this->lang,
				'nav_top_list' => $nav->get_nav ( 0, 'welcome', '', '', 'top' ),
				'nav_list' => $nav->get_nav ( 0 ),
				'nav_bottom_list' => $nav->get_nav ( '0', '', '', '', 'bottom' ),
				'links' => $show->get_link_list (),
				'index' => $index 
		);
		$GLOBALS['global_theme_datas']=$data;
	}
}
