<?php
/**
 * �ʼ�ǩ�տ��Ʋ���
 */
class controller_mail_mailsign extends controller_base_action {

	function __construct() {
		$this->objName = "mailsign";
		$this->objPath = "mail";
		parent::__construct ();
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assignFunc($_GET);
		$mailObjInfo = $this->service->getInvoiceInfo($_GET['docId']);
		unset($mailObjInfo['id']);
		$this->assignFunc($mailObjInfo);

		$mailmanArr = $this->service->getMailman($mailObjInfo);

		$this->assign('salesmanId',$mailmanArr[0]);
		$this->assign('salesman',$mailmanArr[1]);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/**
	 * ��ʼ������
	 */
	function c_init() {
		//$returnObj = $this->objName;
		$obj = $this->service->get_d ( $_GET ['id'] );
		//����
		$obj['file'] = $this->service->getFilesByObjNo($obj['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$deptName=$this->service->getDeptByUserId($_SESSION['USER_ID']);
		if($deptName=="����"){
			$service->searchArr['invoiceapply']="invoiceapply";
		}else{
			$service->searchArr['notInvoiceapply']="invoiceapply";
		}
		$service->asc = true;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ʼ������
	 */
	function c_read() {
		$condition = array( 'mailinfoId'=>$_GET['docId'] );
		$obj = $this->service->find ( $condition );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display('read');
	}


}
?>