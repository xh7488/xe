<?php

namespace Dou\admin\Controller;

use Xe\Controller\Controller;

class Ini extends Controller {
	protected $db;
	protected $cfg;
	protected $lang;
	function __construct() {
		parent::__construct ();
		$this->db =\getDB ();
		$this->cfg = \getSiteCfg ();
		$this->lang = \getLang (NULL,NULL,'i18n/zh_cn/admin.lang');
		$this->init ();
		//debug($this->lang);
	}
	function init() {
		$data = array (
				'cfg' => $this->cfg,
				'lang' => $this->lang 
		);
		$GLOBALS ['global_theme_datas'] = $data;
		
	}
}