<?php
/**
 * @author huangzf
 * @Date 2012年2月2日 15:46:00
 * @version 1.0
 * @description:日志清单(oa_esm_worklog_detail)控制层 
 */
class controller_engineering_worklog_esmworklogdetail extends controller_base_action {
	
	function __construct() {
		$this->objName = "esmworklogdetail";
		$this->objPath = "engineering_worklog";
		parent::__construct ();
	}
	
	/*
	 * 跳转到日志清单(oa_esm_worklog_detail)列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增日志清单(oa_esm_worklog_detail)页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑日志清单(oa_esm_worklog_detail)页面
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
	 * 跳转到查看日志清单(oa_esm_worklog_detail)页面
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
	 * 工作日志
	 */
	function c_listByWorkLogId() {
		$service = $this->service;
		$service->searchArr ['worklogId'] = $_POST['worklogId'];
		$rows = $service->listBySqlId ();
		echo util_jsonUtil::encode ( $rows );
	
	}

}
?>