<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:15:29
 * @version 1.0
 * @description:��ͬ�տ��ƻ�����Ʋ�
 */
class controller_contract_contract_financialplan extends controller_base_action {

	function __construct() {
		$this->objName = "financialplan";
		$this->objPath = "contract_contract";
		parent::__construct ();
	}

	/*
	 * ��ת����ͬ��ϵ����Ϣ���б�
	 */
    function c_page() {
    	$this->view('list');
    }

   /**
	 * ��ת��������ͬ��ϵ����Ϣ��ҳ��
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
	}

   /**
	 * ��ת���༭��ͬ��ϵ����Ϣ��ҳ��
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
	 * ��ת���鿴��ͬ��ϵ����Ϣ��ҳ��
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
	function c_listJsonLimit() {
		$service = $this->service;


		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>