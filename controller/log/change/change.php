<?php
/**
 * �����¼���Ʋ���
 */
class controller_log_change_change extends controller_base_action {

	function __construct() {
		$this->objName = "change";
		$this->objPath = "log_change";
		parent::__construct ();
	}

	/**
	 * @desription �����¼�б�
	 */
	function c_page() {
		$pjId = isset ( $_GET ['objId'] ) ? $_GET ['objId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$service = $this->service;
		$service->resetParam ();
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$this->pageShowAssign (); //���÷�ҳ��ʾ
		$this->show->assign ( 'objId', $pjId );
		$this->show->assign ( 'tabId', $_GET ['tabId'] ); //tabId
		$this->show->assign ( 'jsUrl', $_GET ['jsUrl'] ); //����tab�����js·��
		$this->show->assign ( 'varName', $_GET ['varName'] ); //����tab����ı�������
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

}
?>