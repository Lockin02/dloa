<?php
/**
 * 操作记录控制层类
 */
class controller_log_operation_operation extends controller_base_action {

	function __construct() {
		$this->objName = "operation";
		$this->objPath = "log_operation";
		parent::__construct ();
	}

	/**
	 * @desription 操作记录列表
	 * @edit by zengzx 2011年10月8日 19:34:21 ｛$objId｝
	 *
	 */
	function c_page() {
		$objId = isset ( $_GET ['objId'] ) ? $_GET ['objId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$objId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$this->pageShowAssign (); //设置分页显示
		$this->show->assign ( 'objId', $objId );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //设置tab数组的js路径
		$this->show->assign ( 'varName', $_GET ['varName'] ); //设置tab数组的变量名称
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 正在开发中
	 */
	function c_developing(){
		$this->show->assign ( 'objId', $_GET ['objId'] );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //设置tab数组的js路径
		$this->show->assign ( 'varName', $_GET ['varName'] ); //设置tab数组的变量名称
		$this->show->display ( $this->objPath . '_' . $this->objName . '-developing' );
	}

	/**
	 * 正在开发
	 */
	function c_waitingD(){
		$this->show->display ( $this->objPath . '_' . $this->objName . '-waitingD' );
	}

	/**
	 * grid
	 */
	function c_listGrid(){
		$this->show->assign ( 'objId', $_GET ['objId'] );
		$this->show->assign ( 'objTable', $_GET ['objTable'] ); //tabId
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listgrid' );
	}

	/**
	 * pageJson
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		if(isset($_GET['objId'])){
			$service->searchArr['objId'] = $_GET['objId'];
			$service->searchArr['objTable'] = $_GET['objTable'];
		}

		$service->asc = false;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>