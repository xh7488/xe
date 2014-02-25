<?php
namespace Xe;
class Page {
	private $total; // 总数量
	private $limit; // 返回mysql的limit语句
	private $pageStart; // 开始的数值
	private $pageStop; // 结束的数值
	private $pageNumber; // 显示分页数字的数量
	private $page; // 当前页
	private $pageSize; // 每页显示的数量
	private $pageToatl; // 分页的总数量
	private $uri; // URL参数
	private $links;
	/**
	 * 分页设置样式 不区分大小写
	 * %pageToatl% //总页数
	 * %page%		//当前页
	 * %pageSize% //当前页显示数据条数
	 * %pageStart%	//本页起始条数
	 * %pageStop%	//本页结束条数
	 * %total%		//总数据条数
	 * %first%		//首页
	 * %ending%		//尾页
	 * %up%			//上一页
	 * %down%		//下一页
	 * %F%			//起始页
	 * %E%			//结束页
	 * %omitFA%		//前省略加跳转
	 * %omitEA%		//后省略加跳转
	 * %omitF%		//前省略
	 * %omitE%		//后省略
	 * %numberF%	//固定数量的数字分页
	 * %numberD%	//左右对等的数字分页
	 * %input%		//跳转输入框
	 * %GoTo%			//跳转按钮
	 */
	// 显示值设置
	private $pageShow = array (
			'first' => '首页',
			'ending' => '尾页',
			'up' => '上一页',
			'down' => '下一页',
			'GoTo' => 'GO' 
	);
	
	/**
	 * 初始化数据,构造方法
	 * 
	 * @access public
	 * @param int $total
	 *        	数据总数量
	 * @param int $pageSize
	 *        	每页显示条数
	 * @param int $pageNumber
	 *        	分页数字显示数量(使用%numberF%和使用%numberD%会有不同的效果)
	 * @param string $pageParam        	
	 * @return void
	 */
	public function __construct($total, $uri, $pageSize = 10, $page = 1) {
		$this->page =intval($page ?  : 1);
		$this->total = $total < 0 ? 0 : $total;
		$this->pageSize = $pageSize < 0 ? 0 : $pageSize;
		$this->uri = $uri;
		$this->calculate ();
	}
	/**
	 * 设置limit方法及计算起始条数和结束条数
	 */
	private function calculate() {
		$this->pageToatl = $pageToatl=intval(ceil ( $this->total / $this->pageSize ));
		$this->page =$page= $this->page >= 1 ? $this->page > $this->pageToatl ? $this->pageToatl : $this->page : 1;
		$this->pageStart = ($this->page - 1) * $this->pageSize;
		$this->pageStop = $this->pageStart + $this->pageSize;
		$this->pageStop = $this->pageStop > $this->total ? $this->total : $this->pageStop;
		$this->limit = $this->pageStart . ',' . $this->pageSize;
		$uri=$this->uri;
		$viod="#";
		$this->links = array (
				'first' => ($pageToatl>1 && $page!==1)?sprintf($uri,1):$viod,
				'ending' => ($pageToatl!=$page && $pageToatl!==1)?sprintf($uri,$pageToatl):$viod, 
				'up' => ($page>1)?sprintf($uri,($page-1)):$viod,
				'down' => ($pageToatl>1 && $page!==$pageToatl)?sprintf($uri,($page+1)):$viod,
		);
		//debug($pageToatl,$page,$this->links,$pageToatl>1 && $page===$pageToatl);
	}
	
	/**
	 * 设置过滤器
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'pageType' :
			case 'uri' :
				$this->$name = $value;
				return;
			case 'pageShow' :
				if (is_array ( $value )) {
					$this->pageShow = array_merge ( $this->pageShow, $value );
				}
				return;
		}
	}
	
	/**
	 * 取值过滤器
	 */
	public function __get($name) {
		return $this->$name;
	}
}