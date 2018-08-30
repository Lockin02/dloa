<?php
/**
 * @author Show
 * @Date 2013��7��15�� 15:15:40
 * @version 1.0
 * @description:�ؿ���ڼ���Ʋ�
 */
class controller_contract_config_periodconfig extends controller_base_action {

	function __construct() {
		$this->objName = "periodconfig";
		$this->objPath = "contract_config";
		parent :: __construct();
	}

	/**
	 * ��ת���ؿ���ڼ��б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�������ؿ���ڼ�ҳ��
	 */
	function c_toAdd() {
        $this->showDatadicts ( array ('periodType' => 'HKQJLX' ));
		$this->view('add');
	}

	/**
	 * ��ת���༭�ؿ���ڼ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
        $this->showDatadicts ( array ('periodType' => 'HKQJLX' ), $obj ['periodType']);
		$this->view('edit');
	}

	/**
	 * ��ת���鿴�ؿ���ڼ�ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
}
?>