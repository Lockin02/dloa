<?php

/**
 * @author Show
 * @Date 2012��12��1�� ������ 9:53:08
 * @version 1.0
 * @description:��Ŀ����ָ����Ʋ�
 */
class controller_engineering_assess_esmasspro extends controller_base_action {

	function __construct() {
		$this->objName = "esmasspro";
		$this->objPath = "engineering_assess";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀ����ָ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �༭��Ŀ�Ŀ���ģ��
	 */
	function c_toProjectAssess(){
		$projectId = $_GET['projectId'];
		$projectObj = $this->service->getPorjectInfo_d($projectId);
		$this->assignFunc($projectObj);

		//����ģ���ȡ
		$obj = $this->service->find(array('projectId' => $projectId));
		if($obj){
			$this->assign('indexInfo',$this->service->initEdit_d($obj));
		}else{
			$obj = array(
				'templateId' => '',
				'templateName' => '',
				'score' => '',
				'indexIds' => '',
				'indexNames' => '',
				'needIndexIds' => '',
				'needIndexNames' => '',
				'baseScore' => '',
				'needScore' => '',
				'useScore' => '',
				'useIndexIds' => '',
				'useIndexNames' => '',
				'id' => '',
				'indexInfo' => ''
			);
		}
		$this->assignFunc($obj);
		$this->display('projectassess');
	}

	/**
	 * ���ÿ���ģ��
	 */
	function c_projectAssess(){
		$object = $_POST[$this->objName];
		if(empty($object['id'])){
			$rs = $this->service->add_d($object);
		}else{
			$rs = $this->service->edit_d($object);
		}
		if($rs){
			msgGo ( '����ɹ�','?model=engineering_assess_esmasspro&action=toProjectAssess&projectId='.$object['projectId']);
		}else{
			msgGo ( '����ʧ��','?model=engineering_assess_esmasspro&action=toProjectAssess&projectId='.$object['projectId']);
		}
	}

	//�鿴��־���ģ��
	function c_toViewProjectAssess(){
		$projectId = $_GET['projectId'];

		//����ģ���ȡ
		$obj = $this->service->find(array('projectId' => $projectId));
		if(!$obj){
			$obj = array(
				'baseScore' => '��δ����',
				'useIndexNames' => '��δ����',
				'indexInfo' => '<tr><td colspan="8">��δ����</td></tr>'
			);
		}else{
			$this->assign('indexInfo',$this->service->initView_d($obj));
		}
		$this->assignFunc($obj);

		$this->display('viewprojectassess');
	}

	/**
	 * ��ת��������Ŀ����ָ��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��Ŀ����ָ��ҳ��
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
	 * ��ת���鿴��Ŀ����ָ��ҳ��
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