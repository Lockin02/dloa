<?php
/**
 * @author Administrator
 * @Date 2013��5��6�� ����һ 13:34:00
 * @version 1.0
 * @description:��ְ���������嵥���Ʋ�
 */
class controller_hr_leave_handitem extends controller_base_action {

	function __construct() {
		$this->objName = "handitem";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ���������嵥�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ְ���������嵥ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ְ���������嵥ҳ��
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
	 * ��ת���鿴��ְ���������嵥ҳ��
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
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>