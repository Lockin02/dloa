<?php
/**
 * @author Administrator
 * @Date 2012-07-12 14:04:29
 * @version 1.0
 * @description:����ģ�����ÿ��Ʋ�
 */
class controller_hr_formwork_formwork extends controller_base_action {

	function __construct() {
		$this->objName = "formwork";
		$this->objPath = "hr_formwork";
		parent::__construct ();
	}

	/**
	 * ��ת������ģ�������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����������ģ������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭����ģ������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if ($obj ['isUse'] == '0') {
			$this->assign ( 'yes', 'checked' );
		} else if ($obj ['isUse'] == '1') {
			$this->assign ( 'no', 'checked' );
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴����ģ������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isUse'] == "0"){
			$obj['isUse'] = "����";
		}else if($obj['isUse'] == "1"){
			$obj['isUse'] = "ֹͣ";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ����id��ȡģ����ͬ
	 */
	function c_formworkContent(){
		$info = $this->service->formworkContent_d($_POST ['id']);
		echo util_jsonUtil::iconvGB2UTF ($info);;
	}

	/**
	 * ģ������
	 */
	function c_formworkdeploy(){
		$this->assign("type",$_GET['type']);
		$this->view ( 'formworkdeploy' );
	}

	/**
	 * ģ������--�洢��ѡģ��id
	 */
	function c_formworkdeployEdit(){
		try {
			$this->service->formworkdeployEdit_d($_POST['ids'],$_POST['type']);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * �ж�ģ��
	 */
	function c_formworkLimit(){
		$ids = $this->service->formworkLimit_d($_POST['type']);
		echo $ids;
	}
 }
?>