<?php
/**
 * @author Michael
 * @Date 2015��2��3�� 17:11:23
 * @version 1.0
 * @description:���������������ϼ�¼���Ʋ�
 */
class controller_produce_plan_pickingback extends controller_base_action {

	function __construct() {
		$this->objName = "pickingback";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�����������������ϼ�¼�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ������������ת�����ϼ�¼
	 */
	function c_pagePick() {
		$this->assign('pickId' ,$_GET['pickId']);
		$this->view('list-pick');
	}

	/**
	 * ��ת���������������������ϼ�¼ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���������������ϼ�¼ҳ��
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
	 * ��ת���鿴���������������ϼ�¼ҳ��
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