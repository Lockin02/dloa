<?php
/**
 * @author Administrator
 * @Date 2012-10-29 14:46:46
 * @version 1.0
 * @description:�豸���ÿ��Ʋ�
 */
class controller_equipment_budget_deploy extends controller_base_action {

	function __construct() {
		$this->objName = "deploy";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * ��ת���豸�����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������豸����ҳ��
	 */
	function c_toAdd() {
	  $this->assign('equId' , $_GET['equId']);
      $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�豸����ҳ��
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
	 * ��ת���鿴�豸����ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
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
    * �༭����
    */
   	function c_toEditConfig() {
		$this->assign ( "equId", $_GET ['equId'] );
		$this->view ( "config-edit" );
	}
	 /**
    * �༭����toViewConfig
    */
   	function c_toViewConfig() {
		$equId = $_GET ['equId'];
		$baseDao = new model_equipment_budget_budgetbaseinfo();
        $baseinfo = $baseDao->get_d($equId);
        foreach ( $baseinfo as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$list = $this->service->deployList($equId);
		$this->assign("list",$list);
		$this->view ( "config-view" );
	}
 }
?>