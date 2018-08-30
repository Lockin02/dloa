<?php
/**
 * @author tse
 * @Date 2014��1��4�� 9:23:11
 * @version 1.0
 * @description:��Ŀ�ĵ����ÿ��Ʋ� 
 */
class controller_engineering_file_esmfilesetting extends controller_base_action {
	function __construct() {
		$this->objName = "esmfilesetting";
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
		if($obj['isNeedUpload'] == 1)
			$isNeedUpload = '��';
		else 
			$isNeedUpload = '��';
		$this->assign ( 'isNeedUpload',$isNeedUpload);
		$this->view ( 'view' );
	}
}