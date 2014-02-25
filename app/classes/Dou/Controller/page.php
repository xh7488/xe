<?php
namespace Dou\Controller;
use Dou\Model\page as pager;
use Dou\Model\nav;
class page extends Ini {
	function actionIndex($id = '1') {
		$show = new pager ();
		$nav=new nav();
		$page = $show->get_page_info ( $id );
		if (! $page) {
			header ( "Location: " . ROOT_URL . "\n" );
			exit ();
		}
		$top_id=($page['parent_id'] === '0')?$id:$page ['parent_id'];
		$data = array (
				'page'=> $page,
				'top' => $show->get_page_info ( $top_id ),
				'top_cur' => ($top_id === $id) ? 'top_cur' : '' ,
				'page_list'=>$show->get_page_list($top_id,$id),
				'ur_here'=>$nav->ur_here('page', '', $page['page_name'])
		);
		view ( 'page/index', $data );
	}
}
