<?php
/**
 * @author tse
 * @Date 2014��1��4�� 9:23:34
 * @version 1.0
 * @description:��Ŀ�ĵ����Ϳ��Ʋ� 
 */
class controller_engineering_file_esmfiletype extends controller_base_action {
	function __construct() {
		$this->objName = "esmfiletype";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}
	
	/**
	 * ��ת����Ŀ�ĵ������б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת��������Ŀ�ĵ�����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭��Ŀ�ĵ�����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); // ��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴��Ŀ�ĵ�����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); // ��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * ��ȡ��
	 */
	function c_getTree() {
		$service = $this->service;
		$rows = $service->getTree_d($_GET ['projectId']);
		echo util_jsonUtil::encode ( $rows );
	}
	/**
	 * ����Ƿ���δ�ύ���ĵ�
	 */
	function c_checkFileSubmit(){
		if($this->service->checkFileSubmit_d($_POST)){
			echo 0;//������δ�ύ�ĸ���
		}else{
			echo 1;//����δ�ύ�ĸ���
		}
	}
}