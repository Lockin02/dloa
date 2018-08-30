<?php
/**
 * @author Michael
 * @Date 2014��4��24�� 9:50:35
 * @version 1.0
 * @description:�����ڼ��տ��Ʋ�
 */
class controller_hr_worktime_set extends controller_base_action {

	function __construct() {
		$this->objName = "set";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	}

	/**
	 * ��ת�������ڼ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�����������ڼ���ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ����
	 */
	function c_add() {
		$rs = $this->service->add_d($_POST[$this->objName]);
		if($rs) {
			msg( '����ɹ���' );
		} else{
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���༭�����ڼ���ҳ��
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
	 * �༭
	 */
	function c_edit() {
		$rs = $this->service->edit_d($_POST[$this->objName]);
		if($rs) {
			msg( '����ɹ���' );
		} else{
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴�����ڼ���ҳ��
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
	 * ��������Ƿ��Ѿ����ڼ�¼
	 */
	function c_checkYear() {
		$year = $_POST['year'];
		$rs = $this->service->findCount(array('year' => $year));
		if ($rs > 0) {
			echo "no";
		} else {
			echo "yes";
		}
	}
 }
?>