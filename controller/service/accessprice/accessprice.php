<?php
/**
 * @author huangzf
 * @Date 2011��12��1�� 9:52:49
 * @version 1.0
 * @description:������۸����Ʋ� 
 */
class controller_service_accessprice_accessprice extends controller_base_action {

	function __construct() {
		$this->objName = "accessprice";
		$this->objPath = "service_accessprice";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��������۸���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������������۸��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭������۸��ҳ��
	 */
	function c_toEdit() {
  	 	//$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * ��ת���鿴������۸��ҳ��
	 */
	function c_toView() {
      //$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit() {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '�༭�ɹ���' );
		}
	}
 }
?>