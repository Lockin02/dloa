<?php
/**
 * @author Administrator
 * @Date 2012��7��14�� ������ 14:10:24
 * @version 1.0
 * @description:��Ա����Э���˿��Ʋ�
 */
class controller_hr_recruitment_applymember extends controller_base_action {

	function __construct() {
		$this->objName = "applymember";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת����Ա����Э�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ա����Э����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭��Ա����Э����ҳ��
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
	 * ��ת���鿴��Ա����Э����ҳ��
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