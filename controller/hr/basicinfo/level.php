<?php
/**
 * @author Administrator
 * @Date 2013��6��13�� ������ 19:54:04
 * @version 1.0
 * @description:��Ա�����ȼ����Ʋ�
 */
class controller_hr_basicinfo_level extends controller_base_action {

	function __construct() {
		$this->objName = "level";
		$this->objPath = "hr_basicinfo";
		parent::__construct ();
	}

	/**
	 * ��ת����Ա�����ȼ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������Ա�����ȼ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭��Ա�����ȼ�ҳ��
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
	 * ��ת���鿴��Ա�����ȼ�ҳ��
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
	 * ajax��ȡ��������
	 */
	function c_ajaxGetName() {
		$id = $_POST['id'];
		$obj = $this->service->get_d( $id );
		echo util_jsonUtil::iconvGB2UTF($obj['personLevel']);
	}
}
?>