<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:45
 * @version 1.0
 * @description:ְλ�����-��ͥ��Ա���Ʋ�
 */
class controller_hr_recruitment_family extends controller_base_action {

	function __construct() {
		$this->objName = "family";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת��ְλ�����-��ͥ��Ա�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ְλ�����-��ͥ��Աҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭ְλ�����-��ͥ��Աҳ��
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
	 * ��ת���鿴ְλ�����-��ͥ��Աҳ��
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