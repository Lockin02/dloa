<?php
/**
 * @author Michael
 * @Date 2014��12��1�� 13:43:29
 * @version 1.0
 * @description:���ָ�����Ʋ�
 */
class controller_contract_gridreport_gridindicators extends controller_base_action {

	function __construct() {
		$this->objName = "gridindicators";
		$this->objPath = "contract_gridreport";
		parent::__construct ();
	}

	/**
	 * ��ת�����ָ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������ָ���ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���ָ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit');
	}

	/**
	 * ��ת���鿴���ָ���ҳ��
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