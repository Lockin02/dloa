<?php
/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 14:19:20
 * @version 1.0
 * @description:�����豸�������Ͽ��Ʋ� 
 */
class controller_stock_extra_equipmentpro extends controller_base_action {
	
	function __construct() {
		$this->objName = "equipmentpro";
		$this->objPath = "stock_extra";
		parent::__construct ();
	}
	
	/**
	 * ��ת�������豸���������б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת�����������豸��������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭�����豸��������ҳ��
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
	 * ��ת���鿴�����豸��������ҳ��
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
	 * ��������id��ȡeditgrid�嵥Json
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}
}
?>