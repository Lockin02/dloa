<?php
/**
 * @author Administrator
 * @Date 2012-08-23 16:55:05
 * @version 1.0
 * @description:Ա�������ƻ���ϸ����Ʋ�
 */
class controller_hr_tutor_coachplaninfo extends controller_base_action {

	function __construct() {
		$this->objName = "coachplaninfo";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * ��ת��Ա�������ƻ���ϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת������Ա�������ƻ���ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭Ա�������ƻ���ϸ��ҳ��
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
	 * ��ת���鿴Ա�������ƻ���ϸ��ҳ��
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
		$service->asc = false;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>