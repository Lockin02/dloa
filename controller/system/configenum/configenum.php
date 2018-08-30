<?php
/**
 * @author huangzf
 * @Date 2012��7��19�� ������ 10:43:44
 * @version 1.0
 * @description:ϵͳ��������ö�ٿ��Ʋ� 
 */
class controller_system_configenum_configenum extends controller_base_action {
	
	function __construct() {
		$this->objName = "configenum";
		$this->objPath = "system_configenum";
		parent::__construct ();
	}
	
	/**
	 * ��ת��ϵͳ��������ö���б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * ��ת������ϵͳ��������ö��ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת���༭ϵͳ��������ö��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch ($_GET ['id']) {
			case 1 :
				$this->view ( "accessorder-edit" );
				break;
			case 2 :
				$this->view ( "repairapply-edit" );
				break;
			case 3 :
				$this->view ( "saftystock-edit" );
				break;
		}
	
		//		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴ϵͳ��������ö��ҳ��
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
	 * �޸Ļ�����Ϣ 
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, false )) {
			if (isset ( $_GET ['formType'] )) {
				msg ( "���óɹ�!" );
			} else {
				msgGo ( "���óɹ���", "index1.php?model=system_configenum_configenum&action=toEdit&id=$object[id]" );
			
			}
		}
	}
	
	/**
	 * 
	 * ��ȡĳһ����ĳһ��ֵ
	 */
	function c_getEnumFieldVal() {
		echo $this->service->getEnumFieldVal ( $_POST ['id'], $_POST ['fieldName'] );
	}
}
?>