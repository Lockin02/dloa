<?php
/**
 * @author Administrator
 * @Date 2013��5��29�� 10:08:38
 * @version 1.0
 * @description:�̻��豸Ӳ��������Ʋ�
 */
class controller_projectmanagent_hardware_hardware extends controller_base_action {

	function __construct() {
		$this->objName = "hardware";
		$this->objPath = "projectmanagent_hardware";
		parent::__construct ();
	 }

	/**
	 * ��ת���̻��豸Ӳ�������б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������̻��豸Ӳ������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�̻��豸Ӳ������ҳ��
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
	 * ��ת���鿴�̻��豸Ӳ������ҳ��
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
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}


	/**
	 * �Ƿ������豸
	 */
		function c_ajaxUseStatus(){
		try{
			$this->service->ajaxUseStatus_d($_POST ['id'],$_POST ['useStatus']);
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}


 }
?>