<?php
/**
 * @author Show
 * @Date 2012��6��14�� ������ 20:39:00
 * @version 1.0
 * @description:����Ԥ����Ŀ(oa_esm_baseinfo_person)���Ʋ�
 */
class controller_engineering_baseinfo_eperson extends controller_base_action {

	function __construct() {
		$this->objName = "eperson";
		$this->objPath = "engineering_baseinfo";
		parent :: __construct();
	}

	/*
	 * ��ת������Ԥ����Ŀ
	 */
	function c_page() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$this->assign('parentId',$parentId);
		$this->view('list');
	}

    /**
	 * ������
	 */
	function c_getChildren(){
		$service = $this->service;

		$sqlKey = isset($_POST['rtParentType'])? 'select_treelistRtBoolean' : 'select_treelist';

		if(empty($_POST['id'])){
			$rows = array(array('id'=>PARENT_ID,'code' => 'root','name'=> 'Ԥ��Ŀ¼','isParent'=>'true'));
		}else{
			$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
			$service->searchArr['parentId'] = $parentId;
			$service->asc = false;
			$rows=$service->listBySqlId($sqlKey);
		}
		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * ����Ƿ���ڸ��ڵ㣬�����������һ��
	 */
	function c_checkParent(){
		if($this->service->checkParent_d()){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

	/**
	 * ��ת����������Ԥ����Ŀ
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId),null,'id,personLevel');
        $this->assignFunc($row);

		$this->view('add');
	}

	/**
	 * ��ת���༭����Ԥ����Ŀ
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
	 * ��ת���鿴����Ԥ����Ŀ
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
	 * �ı�����״̬
	 */
	function c_changeStatus() {
		if($this->service->edit_d(array('id' => $_POST['id'] , 'status' => $_POST['status']))){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
}
?>