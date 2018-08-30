<?php
/**
 * @author Michael
 * @Date 2014��7��25�� 15:13:03
 * @version 1.0
 * @description:������Ϣ-������Ʋ�
 */
class controller_manufacture_basic_process extends controller_base_action {

	function __construct() {
		$this->objName = "process";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }

	/**
	 * ��ת��������Ϣ-�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������������Ϣ-����ҳ��
	 */
	function c_toAdd() {
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createTime' ,date("Y-m-d H:i:s"));
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭������Ϣ-����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴������Ϣ-����ҳ��
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
	 * ajax�ı�����״̬
	 */
	function c_ajaxEnable() {
		$ids = $_POST['ids'];
		$isEnable = util_jsonUtil::iconvUTF2GB ( $_POST['isEnable'] );
		if ($this->service->updateEnableByIds_d($ids ,$isEnable)) {
			echo 1;
		} else {
			echo 0;
		}
	}
}
?>