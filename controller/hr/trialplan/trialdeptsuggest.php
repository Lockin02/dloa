<?php

/**
 * @author Show
 * @Date 2012��9��12�� ������ 16:25:18
 * @version 1.0
 * @description:Ա��ʹ�ò��Ž������Ʋ�
 */
class controller_hr_trialplan_trialdeptsuggest extends controller_base_action {

	function __construct() {
		$this->objName = "trialdeptsuggest";
		$this->objPath = "hr_trialplan";
		parent :: __construct();
	}

	/**
	 * ��ת��Ա��ʹ�ò��Ž�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת������Ա��ʹ�ò��Ž����ҳ��
	 */
	function c_toAdd() {
		$this->view('add' ,true);
	}

	/**
	 * ��ת���༭Ա��ʹ�ò��Ž����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit' ,true);
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			if(isset($_GET['act']) && $_GET['act'] == 'audit'){
				if( $object['hrSalary']!= $object['afterSalary'] ||$object['personLevel'] != $object['beforePersonLv'] ){
					succ_show('controller/hr/trialplan/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'] . "&billDept=".$object['deptId']);
				}else{
					succ_show('controller/hr/trialplan/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . "&billDept=".$object['deptId']);
				}
			}else{
				msg('����ɹ�');
			}
		}
	}

	/**
	 * ��ת���鿴Ա��ʹ�ò��Ž����ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * ����
	 */
	function c_toAudit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('audit');
	}

	//������ִ��
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}
?>