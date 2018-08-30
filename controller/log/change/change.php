<?php
/**
 * 变更记录控制层类
 */
class controller_log_change_change extends controller_base_action {

	function __construct() {
		$this->objName = "change";
		$this->objPath = "log_change";
		parent::__construct ();
	}

	/**
	 * @desription 变更记录列表
	 */
	function c_page() {
		$pjId = isset ( $_GET ['objId'] ) ? $_GET ['objId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'objId', $pjId );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //设置tab数组的js路径
		$this->show->assign ( 'varName', $_GET ['varName'] ); //设置tab数组的变量名称
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

}
?>