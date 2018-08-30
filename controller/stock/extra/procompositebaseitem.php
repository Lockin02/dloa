<?php
/**
 * @author Administrator
 * @Date 2012��6��1�� ������ 16:54:00
 * @version 1.0
 * @description:��Ʒ���Ͽ��ɹ������ۺϱ��嵥���Ʋ� 
 */
class controller_stock_extra_procompositebaseitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "procompositebaseitem";
		$this->objPath = "stock_extra";
		parent::__construct ();
	}
	
	/**
	 * ��ת����Ʒ���Ͽ��ɹ������ۺϱ��嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��������Ʒ���Ͽ��ɹ������ۺϱ��嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭��Ʒ���Ͽ��ɹ������ۺϱ��嵥ҳ��
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
	 * ��ת���鿴��Ʒ���Ͽ��ɹ������ۺϱ��嵥ҳ��
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