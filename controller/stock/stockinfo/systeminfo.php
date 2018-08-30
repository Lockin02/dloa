<?php
/**
 * @author Administrator
 * @Date 2011��5��28�� 16:50:58
 * @version 1.0
 * @description:�ִ���������Ϣ���ÿ��Ʋ� 
 */
class controller_stock_stockinfo_systeminfo extends controller_base_action {
	
	function __construct() {
		$this->objName = "systeminfo";
		$this->objPath = "stock_stockinfo";
		parent::__construct ();
	}
	
	/*
	 * ��ת���ִ���������Ϣ����
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	
	/**
	 * ��ת�޸Ļ����ֿ�����
	 */
	function c_init() {
//		print_r($_SESSION);
		$service = $this->service;
		//$service->searchArr=array("")
		
		$this->showDatadicts(array('unitCode'=>'QYZT'));
		$obj = $service->get_d ( $_GET ['id'] );
//		echo "<pre>";
//		print_r($obj);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "edit" );
	}
	
	/**
	 * �޸Ļ�����Ϣ 
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, false )) {
			msgGo ( "���óɹ���", "index1.php?model=stock_stockinfo_systeminfo&action=init&id=$object[id]" );
		}
	}
	/**
	 * ��ȡ��װ�ֿ�id
	 */
	function c_getPackStockId() {
		$service = $this->service;
		$obj = $service->get_d ( $_POST ['id'] );
		$result = 0;
		if (is_array ( $obj ))
			$result = $obj ['packingStockId'];
		echo $result;
	}
	
	/**
	 * ��ȡĬ�ϲֿ���Ϣ
	 */
	function c_getDefaultStock() {
		$service = $this->service;
		$obj = $service->get_d ( $_POST ['id'] );
		$result = 0;
		if (is_array ( $obj ))
			echo util_jsonUtil::encode ( $obj );
		else
			echo $result;
	}
	
	/**
	 * �ִ������ͼ
	 */
	function c_toMap() {
		$this->view ( "map" );
	}
}
?>