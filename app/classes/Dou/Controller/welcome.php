<?php
namespace Dou\Controller;
use Dou\Model\show;
use Dou\Model\product;
use Dou\Model\article;
class welcome  extends Ini
{
	function actionIndex()
	{
		
		$show=new show();
		$product=new product();
		$article=new article();
		
		$data=array(
			
			'links'=>$show->get_link_list(),
			'is_home'=>1,
			'show_list'=>$show->get_show_list(),
		    'recommend_article'=>$article->get_recommend_article('', $this->cfg['home_display_article']),
			'recommend_product'=>$q=$product->get_recommend_product('', $this->cfg['home_display_product'],$this->lang)
		);
// 		debug($data['recommend_product']);
		
		/* 获取meta和title信息 */
		/* $smarty->assign('page_title', $_CFG['site_title']);
		$smarty->assign('keywords', $_CFG['site_keywords']);
		$smarty->assign('description', $_CFG['site_description']);
		 */
	
		
		/* 初始化数据 */
		//$smarty->assign('index', 'index'); // 是否为首页的标志
		view('welcome/index',$data);
		
		//$smarty->display('index.dwt');
		

		
		
		//view('welcome/index');
	}
}

