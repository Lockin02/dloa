<?php

/**
 * �̵�������Ʋ���
 *  @author chenzb
 */
class controller_asset_checktask_checktask extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "checktask";
		$this->objPath = "asset_checktask";
		parent::__construct ();
	}
	/**
	 * ��ת���̵�������Ϣ�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת������ҳ��
	 */

	function c_toAdd() {

		$this->view ( 'add' );
	}

 /**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}




}
?>