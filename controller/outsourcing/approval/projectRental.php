<?php
/**
 * @author Administrator
 * @Date 2013��11��20�� ������ 9:24:09
 * @version 1.0
 * @description:������������ְ�����Ʋ�
 */
class controller_outsourcing_approval_projectRental extends controller_base_action {

	function __construct() {
		$this->objName = "projectRental";
		$this->objPath = "outsourcing_approval";
		parent::__construct ();
	 }

	/**
	 * ��ת��������������ְ����б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������������������ְ���ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭������������ְ���ҳ��
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
	 * ��ת���鿴������������ְ���ҳ��
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
	 * ��ȡ��������ҳ��
	 */
	function c_getAddPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getAddPage_d($_POST['projectId'])));
	}

	/**
	 * ��ȡ�����鿴ҳ��
	 */
	function c_getViewPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getViewPage_d($_POST['mainId'])));
	}

	/**
	 * ��ȡ�����༭ҳ��
	 */
	function c_getEditPage(){
		exit(util_jsonUtil::iconvGB2UTF($this->service->getEditPage_d($_POST['mainId'])));
	}
 }
?>