<?php
/**
 * @author Administrator
 * @Date 2012-08-09 09:35:57
 * @version 1.0
 * @description:��ְ�嵥ģ����Ʋ�
 */
class controller_hr_leave_formwork extends controller_base_action {

	function __construct() {
		$this->objName = "formwork";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�嵥ģ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ְ�嵥ģ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ְ�嵥ģ��ҳ��
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
	 * ��ת���鿴��ְ�嵥ģ��ҳ��
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
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$service->sort='sort';
		$rows = $service->list_d ("select_default");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_addItemList() {
		$count = isset ($_POST['count']) ? $_POST['count'] : '';
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$service->sort='sort';
		$rows = $service->list_d ("select_default");
		$fromworkList = $this->service->fromworkInfo_d($rows,$count);
		echo $fromworkList;
	}
 }
?>