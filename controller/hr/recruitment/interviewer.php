<?php
/**
 * @author Administrator
 * @Date 2012��7��18�� ������ 15:11:15
 * @version 1.0
 * @description:���Թٿ��Ʋ�
 */
class controller_hr_recruitment_interviewer extends controller_base_action {

	function __construct() {
		$this->objName = "interviewer";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת�����Թ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������Թ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭���Թ�ҳ��
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
	 * ��ת���鿴���Թ�ҳ��
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