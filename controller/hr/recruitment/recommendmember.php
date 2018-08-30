<?php
/**
 * @author Administrator
 * @Date 2012��7��13�� ������ 14:05:50
 * @version 1.0
 * @description:Э���˿��Ʋ�
 */
class controller_hr_recruitment_recommendmember extends controller_base_action {

	function __construct() {
		$this->objName = "recommendmember";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת��Э�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Э����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭Э����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}
	 
	/**
	 * ��ת���鿴Э����ҳ��
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
	 * ��ȡ�ӱ���Ϣ
	 */
	function c_pageItemJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>