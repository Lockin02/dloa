<?php
/**
 * @author huangzf
 * @Date 2011��11��27�� 14:36:58
 * @version 1.0
 * @description:����������嵥���Ʋ� 
 */
class controller_service_accessorder_accessorderitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "accessorderitem";
		$this->objPath = "service_accessorder";
		parent::__construct ();
	}
	
	/*
	 * ��ת������������嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת����������������嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭����������嵥ҳ��
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
	 * ��ת���鿴����������嵥ҳ��
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
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		
		foreach ( $rows as $key => $value ) {
			$tempArr = $service->getOutNum ( $value ['mainId'], $value ['productId'] );
			
			if (is_array ( $tempArr )) {
				$rows [$key] ['actOutNum'] = $tempArr [0] ['actOutNum'];
			} else {
				$rows [$key] ['actOutNum'] = 0;
			}
		}
		
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * 
	 * �ж��Ƿ��Ѿ���д���������
	 */
	function c_isShipAll() {
		$service = $this->service;
		echo $service->isShipAll_d ( $_POST ['mainId'] );
	
	}

}
?>