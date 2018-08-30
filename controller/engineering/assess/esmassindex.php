<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 11:40:15
 * @version 1.0
 * @description:����ָ�����Ʋ�
 */
class controller_engineering_assess_esmassindex extends controller_base_action {

	function __construct() {
		$this->objName = "esmassindex";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * ��ת������ָ����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����������ָ���ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭����ָ���ҳ��
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
	 * ��ת���鿴����ָ���ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	//ajax��ȡָ����Ϣ
	function c_ajaxGetIndex(){
		$indexIds = $_POST['indexIds'];
		$needIndexIds = $_POST['needIndexIds'];
		$indexArr = $this->service->getIndexs_d($indexIds);
		$indexStr = $this->service->initEdit_d($indexArr,$needIndexIds);
		echo util_jsonUtil::iconvGB2UTF($indexStr);
	}
}
?>