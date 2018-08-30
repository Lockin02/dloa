<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 14:23:25
 * @version 1.0
 * @description:ά������(����)�嵥���Ʋ�
 */
class controller_service_repair_applyitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "applyitem";
		$this->objPath = "service_repair";
		parent::__construct ();
	}
	
	/**
	 * ��ת��ά������(����)�嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��ά������δ�ύ�嵥�б�
	 */
	function c_toNauditPage() {
		$this->view ( 'naudit-list' );
	}
	
	/**
	 * ��ת��ά������������嵥�б�
	 */
	function c_toYauditPage() {
		$this->view ( 'yaudit-list' );
	}
	
	/**
	 * ��ת������ά������(����)�嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭ά������(����)�嵥ҳ��
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
	 * ��ת���鿴ά������(����)�嵥ҳ��
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
	 * ȡ����ȷ�ϱ����嵥
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
	 * �ж��Ƿ����´������
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
	 * �ж��Ƿ���ȷ�ϱ������
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
	 * �ж��Ƿ��Ѿ�ά�����
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