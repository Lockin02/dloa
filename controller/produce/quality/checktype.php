<?php
/**
 * @author huangzf
 * @Date 2013��3��6�� ������ 17:29:18
 * @version 1.0
 * @description:���鷽ʽ���Ʋ�
 */
class controller_produce_quality_checktype extends controller_base_action {

	function __construct() {
		$this->objName = "checktype";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ת�����鷽ʽ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���������鷽ʽҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭���鷽ʽҳ��
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
	 * ��ת���鿴���鷽ʽҳ��
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