<?php
/**
 * @author sony
 * @Date 2013��9��9�� 11:12:38
 * @version 1.0
 * @description:���Ʋ�
 */
class controller_yxlicense_license_categoryitem extends controller_base_action {

	function __construct() {
		$this->objName = "categoryitem";
		$this->objPath = "yxlicense_license";
		parent :: __construct();
	}

	/**
	 * ��ת���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴ҳ��
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