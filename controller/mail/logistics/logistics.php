<?php
/**
 * @author Show
 * @Date 2011��5��27�� ������ 9:35:54
 * @version 1.0
 * @description:������˾������Ϣ���Ʋ�
 */
class controller_mail_logistics_logistics extends controller_base_action {

	function __construct() {
		$this->objName = "logistics";
		$this->objPath = "mail_logistics";
		parent :: __construct();
	}

	/*
	 * ��ת��������˾������Ϣ
	 */
	function c_page() {
		$this->display('list');
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
			$this->assign('isDefault',$this->service->rtYesOrNo($obj['isDefault']));
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
}
?>