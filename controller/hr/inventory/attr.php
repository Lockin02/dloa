<?php
/**
 * @author zengq
 * @Date 2012��8��20�� 10:57:17
 * @version 1.0
 * @description:�̵����->���Թ��� ���Ʋ�
 */
class controller_hr_inventory_attr extends controller_base_action {

	function __construct() {
			$this->objName = "attr";
			$this->objPath = "hr_inventory";
			parent :: __construct();
		}
	/*
	 * ��ת�����Թ����б�
	 */
	function c_page() {
		$this->view('list');
	}
	/*
	 * ��ת���鿴����ҳ��
	 */
	function c_toView() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['attrType'] = $obj['attrType']==0?"�ı���":"������";
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	/*
	 * ��ת����������ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}
	/*
	 * ��ת�����Ա༭ҳ��
	 */
	function c_toEdit() {
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}
}
?>
