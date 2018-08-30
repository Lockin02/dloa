<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:52:42
 * @version 1.0
 * @description:�豸����-���滻�豸������Ʋ�
 */
class controller_engineering_resources_replaced extends controller_base_action {

	function __construct() {
		$this->objName = "replaced";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	 }

	/**
	 * ��ת���豸����-���滻�豸�����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������豸����-���滻�豸����ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�豸����-���滻�豸����ҳ��
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
	 * ��ת���鿴�豸����-���滻�豸����ҳ��
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
	function c_add($isAddInfo = false) {
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
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * �ж��豸�Ƿ����
	 */
	function c_checkIsRepeat(){
		$deviceId = $_POST['deviceId'];
		$rs = $this->service->find(array('deviceId' => $deviceId),null,'id');
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

 }
?>