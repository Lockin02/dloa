<?php
/**
 * @author yxin1
 * @Date 2014��12��2�� 14:42:10
 * @version 1.0
 * @description:ָ��ֵ����Ʋ�
 */
class controller_contract_gridreport_indicators extends controller_base_action {

	function __construct() {
		$this->objName = "indicators";
		$this->objPath = "contract_gridreport";
		parent::__construct ();
	}

	/**
	 * ��ת��ָ��ֵ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ָ��ֵ��ҳ��
	 */
	function c_toAdd() {
		$gridDao = new model_contract_gridreport_gridindicators();
		$gridObj = $gridDao->get_d ( $_GET ['gridId'] );
		foreach ( $gridObj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭ָ��ֵ��ҳ��
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
	* ��ת���鿴ָ��ֵ��ҳ��
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