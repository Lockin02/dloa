<?php
/**
 * @author Administrator
 * @Date 2012-07-23 14:04:07
 * @version 1.0
 * @description:ְλ�����-��Ŀ�������Ʋ�
 */
class controller_hr_recruitment_project extends controller_base_action {

	function __construct() {
		$this->objName = "project";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת��ְλ�����-��Ŀ�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ְλ�����-��Ŀ����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭ְλ�����-��Ŀ����ҳ��
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
	 * ��ת���鿴ְλ�����-��Ŀ����ҳ��
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