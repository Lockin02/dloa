<?php
/**
 * @author Administrator
 * @Date 2011��12��3�� 10:33:06
 * @version 1.0
 * @description:ά�޷��ü����嵥���Ʋ� 
 */
class controller_service_reduce_reduceitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "reduceitem";
		$this->objPath = "service_reduce";
		parent::__construct ();
	}
	
	/*
	 * ��ת��ά�޷��ü����嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת������ά�޷��ü����嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭ά�޷��ü����嵥ҳ��
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
	 * ��ת���鿴ά�޷��ü����嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
}
?>