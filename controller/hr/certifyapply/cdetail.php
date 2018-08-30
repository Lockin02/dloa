<?php
/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:38
 * @version 1.0
 * @description:任职资格等级认证评价表明细控制层
 */
class controller_hr_certifyapply_cdetail extends controller_base_action {

	function __construct() {
		$this->objName = "cdetail";
		$this->objPath = "hr_certifyapply";
		parent :: __construct();
	}

	/**
	 * 跳转到任职资格等级认证评价表明细列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到新增任职资格等级认证评价表明细页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑任职资格等级认证评价表明细页面
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
	 * 跳转到查看任职资格等级认证评价表明细页面
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