<?php
/**
 * @author huangzf
 * @Date 2012��2��2�� 15:46:00
 * @version 1.0
 * @description:��־�嵥(oa_esm_worklog_detail)���Ʋ� 
 */
class controller_engineering_worklog_esmworklogdetail extends controller_base_action {
	
	function __construct() {
		$this->objName = "esmworklogdetail";
		$this->objPath = "engineering_worklog";
		parent::__construct ();
	}
	
	/*
	 * ��ת����־�嵥(oa_esm_worklog_detail)�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��������־�嵥(oa_esm_worklog_detail)ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭��־�嵥(oa_esm_worklog_detail)ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴��־�嵥(oa_esm_worklog_detail)ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	
	/**
	 * 
	 * ������־
	 */
	function c_listByWorkLogId() {
		$service = $this->service;
		$service->searchArr ['worklogId'] = $_POST['worklogId'];
		$rows = $service->listBySqlId ();
		echo util_jsonUtil::encode ( $rows );
	
	}

}
?>