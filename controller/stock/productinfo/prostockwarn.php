<?php
/**
 * @author huangzf
 * @Date 2012��4��20�� ������ 9:57:12
 * @version 1.0
 * @description:���Ͽ��Ԥ����Ϣ���ÿ��Ʋ� 
 */
class controller_stock_productinfo_prostockwarn extends controller_base_action {
	
	function __construct() {
		$this->objName = "prostockwarn";
		$this->objPath = "stock_productinfo";
		parent::__construct ();
	}
	
	/*
	 * ��ת�����Ͽ��Ԥ����Ϣ�����б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת���������Ͽ��Ԥ����Ϣ����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭���Ͽ��Ԥ����Ϣ����ҳ��
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
	 * ��ת���鿴���Ͽ��Ԥ����Ϣ����ҳ��
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