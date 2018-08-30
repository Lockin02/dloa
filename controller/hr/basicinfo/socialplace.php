<?php
/**
 * @author Administrator
 * @Date 2012��8��11�� ������ 10:27:17
 * @version 1.0
 * @description:�籣����ؿ��Ʋ�
 */
class controller_hr_basicinfo_socialplace extends controller_base_action {

	function __construct() {
		$this->objName = "socialplace";
		$this->objPath = "hr_basicinfo";
		parent::__construct ();
	 }

	/**
	 * ��ת���籣������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������籣�����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�籣�����ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

	/*
	 * ��ת���籣������б�
	 */
	function c_toList(){
		$this->view('list');
	}

	/*
	 * ��д��������
	 */
	function c_add_d(){
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

   /**
	 * ��ת���鿴�籣�����ҳ��
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