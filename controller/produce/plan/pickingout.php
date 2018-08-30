<?php
/**
 * @author Michael
 * @Date 2015��1��14�� 16:07:08
 * @version 1.0
 * @description:�����������������ֳ����¼���Ʋ�
 */
class controller_produce_plan_pickingout extends controller_base_action {

	function __construct() {
		$this->objName = "pickingout";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�������������������ֳ����¼�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ������������ת�������ֳ����¼
	 */
	function c_pagePick() {
		$this->assign('pickId' ,$_GET['pickId']);
		$this->view('list-pick');
	}

	/**
	 * ��ת�����������������������ֳ����¼ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�����������������ֳ����¼ҳ��
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
	 * ��ת���鿴�����������������ֳ����¼ҳ��
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