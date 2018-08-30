<?php
/**
 * @author Administrator
 * @Date 2012年7月13日 星期五 14:05:50
 * @version 1.0
 * @description:协助人控制层
 */
class controller_hr_recruitment_recommendmember extends controller_base_action {

	function __construct() {
		$this->objName = "recommendmember";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到协助人列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增协助人页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * 跳转到编辑协助人页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}
	 
	/**
	 * 跳转到查看协助人页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * 获取子表信息
	 */
	function c_pageItemJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>