<?php
/**
 *
 * ʹ��״̬���Ʋ���
 * @author chris
 *
 */
class controller_asset_basic_useStatus extends controller_base_action {

	function __construct() {
		$this->objName = "useStatus";
		$this->objPath = "asset_basic";
		parent::__construct ();
	}

	/*
	 * ��ת��ʹ��״̬
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assign ( 'createName', $_SESSION ['USERNAME'] );
		$this->assign ( 'createId', $_SESSION ['USER_ID'] );
		$this->assign ( 'createTime', date("Y-m-d") );
		$this->display ( 'add' );
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
			$this->display ( 'view' );
		} else {
			$this->assign ( 'updateName', $_SESSION ['USERNAME'] );
			$this->assign ( 'updateId', $_SESSION ['USER_ID'] );
			$this->assign ( 'updateTime', date("Y-m-d") );
			$this->display ( 'edit' );
		}
	}
}

?>