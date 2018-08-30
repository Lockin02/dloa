<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 14:23:25
 * @version 1.0
 * @description:维修申请(报价)清单控制层
 */
class controller_service_repair_applyitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "applyitem";
		$this->objPath = "service_repair";
		parent::__construct ();
	}
	
	/**
	 * 跳转到维修申请(报价)清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到维修申请未提交清单列表
	 */
	function c_toNauditPage() {
		$this->view ( 'naudit-list' );
	}
	
	/**
	 * 跳转到维修申请已审核清单列表
	 */
	function c_toYauditPage() {
		$this->view ( 'yaudit-list' );
	}
	
	/**
	 * 跳转到新增维修申请(报价)清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑维修申请(报价)清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看维修申请(报价)清单页面
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
	 * 
	 * 取消已确认报价清单
	 */
	function c_cancelQuote() {
		$service = $this->service;
		if ($service->cancelQuote_d ( $_POST ['id'] )) {
			echo 1;
		} else {
			echo 0;
		}
	}
	
	/**
	 * 
	 * 判断是否已下达检测完毕
	 */
	function c_isCheckAll() {
		$service = $this->service;
		$service->searchArr = array ("mainId" => $_POST ['mainId'], "isDetect" => "0" );
		$result = $service->listBySqlId ();
		if (is_array ( $result )) {
			echo 0;
		} else {
			echo 1;
		}
	}
	/**
	 * 
	 * 判断是否已确认报价完毕
	 */
	function c_isQuoteAll() {
		$service = $this->service;
		$service->searchArr = array ("mainId" => $_POST ['mainId'], "isQuote" => "0" );
		$result = $service->listBySqlId ();
		if (is_array ( $result )) {
			echo 0;
		} else {
			echo 1;
		}
	}
	
	/**
	 * 
	 * 判断是否已经维修完毕
	 */
	function c_isShipAll() {
		$service = $this->service;
		$service->searchArr = array ("mainId" => $_POST ['mainId'], "isShip" => "0" );
		$result = $service->listBySqlId ();
		if (is_array ( $result )) {
			echo 0;
		} else {
			echo 1;
		}
	}

}
?>