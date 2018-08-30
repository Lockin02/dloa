<?php
/**
 * ������¼���Ʋ���
 */
class controller_log_operation_operation extends controller_base_action {

	function __construct() {
		$this->objName = "operation";
		$this->objPath = "log_operation";
		parent::__construct ();
	}

	/**
	 * @desription ������¼�б�
	 * @edit by zengzx 2011��10��8�� 19:34:21 ��$objId��
	 *
	 */
	function c_page() {
		$objId = isset ( $_GET ['objId'] ) ? $_GET ['objId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$objId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'objId', $objId );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //����tab�����js·��
		$this->show->assign ( 'varName', $_GET ['varName'] ); //����tab����ı�������
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * ���ڿ�����
	 */
	function c_developing(){
		$this->show->assign ( 'objId', $_GET ['objId'] );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //����tab�����js·��
		$this->show->assign ( 'varName', $_GET ['varName'] ); //����tab����ı�������
		$this->show->display ( $this->objPath . '_' . $this->objName . '-developing' );
	}

	/**
	 * ���ڿ���
	 */
	function c_waitingD(){
		$this->show->display ( $this->objPath . '_' . $this->objName . '-waitingD' );
	}

	/**
	 * grid
	 */
	function c_listGrid(){
		$this->show->assign ( 'objId', $_GET ['objId'] );
		$this->show->assign ( 'objTable', $_GET ['objTable'] ); //tabId
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listgrid' );
	}

	/**
	 * pageJson
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		if(isset($_GET['objId'])){
			$service->searchArr['objId'] = $_GET['objId'];
			$service->searchArr['objTable'] = $_GET['objTable'];
		}

		$service->asc = false;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>