<?php
/**
 * @author Show
 * @Date 2012��8��22�� ������ 17:25:00
 * @version 1.0
 * @description:��ְ�ʸ�-��רҵ���������Ʋ�
 */
class controller_hr_personnel_certifyapplyexp extends controller_base_action {

	function __construct() {
		$this->objName = "certifyapplyexp";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�ʸ�-��רҵ�������б�
	 */
    function c_page() {
      $this->view('list');
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

   /**
	 * ��ת��������ְ�ʸ�-��רҵ������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��ְ�ʸ�-��רҵ������ҳ��
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
	 * ��ת���鿴��ְ�ʸ�-��רҵ������ҳ��
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