<?php
/**
 * @author Administrator
 * @Date 2013年7月12日 9:47:40
 * @version 1.0
 * @description:随行人员表控制层
 */
class controller_flights_require_requiresuite extends controller_base_action {

	function __construct() {
		$this->objName = "requiresuite";
		$this->objPath = "flights_require";
		parent :: __construct();
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$rows = array();
		$service = $this->service;
		//获取权限字段
		$cardNoLimit = isset($_REQUEST['cardNoLimit']) ? $_REQUEST['cardNoLimit'] : '';

		$service->getParam ( $_REQUEST );//设置前台获取的参数信息

		$detailRows = $service->page_d ();
		if($detailRows){
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $detailRows );
		}
		//证件编码处理
		$rows = $this->cardFilter($rows,$cardNoLimit);

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;

		//获取权限字段
		$cardNoLimit = isset($_REQUEST['cardNoLimit']) ? $_REQUEST['cardNoLimit'] : '';
		
		$service->getParam($_REQUEST);
		$rows = $service->list_d();

		//证件编码处理
		$rows = $this->cardFilter($rows,$cardNoLimit);

		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);

		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 证件编码过滤
	 */
	function cardFilter($rows,$cardNoLimit){
		foreach($rows as $key => $val){
			//权限过滤
	        if( $val['cardType'] == 'JPZJLX-01' && ($cardNoLimit == '1'|| $_SESSION['USER_ID'] == $val['airId'])){
	        	$rows[$key]['cardNo'] = $val['cardNoHidden'];
	        }
		}
		return $rows;
	}

	/**
	 * 跳转到随行人员表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增随行人员表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑随行人员表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看随行人员表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>