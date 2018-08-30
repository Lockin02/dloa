<?php
/**
 * @author Michael
 * @Date 2014��9��29�� 19:22:05
 * @version 1.0
 * @description:�⳵��ͬ���ӷ��ÿ��Ʋ�
 */
class controller_outsourcing_contract_rentcarfee extends controller_base_action {

	function __construct() {
		$this->objName = "rentcarfee";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵��ͬ���ӷ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������⳵��ͬ���ӷ���ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭�⳵��ͬ���ӷ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴�⳵��ͬ���ӷ���ҳ��
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
	 * ���ݳ��ƻ�ȡ�������ݷ���json
	 */
	function c_listJsonByCar() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_car');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>