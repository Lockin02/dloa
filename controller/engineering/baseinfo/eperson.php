<?php
/**
 * @author Show
 * @Date 2012年6月14日 星期四 20:39:00
 * @version 1.0
 * @description:人力预算项目(oa_esm_baseinfo_person)控制层
 */
class controller_engineering_baseinfo_eperson extends controller_base_action {

	function __construct() {
		$this->objName = "eperson";
		$this->objPath = "engineering_baseinfo";
		parent :: __construct();
	}

	/*
	 * 跳转到人力预算项目
	 */
	function c_page() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$this->assign('parentId',$parentId);
		$this->view('list');
	}

    /**
	 * 构造树
	 */
	function c_getChildren(){
		$service = $this->service;

		$sqlKey = isset($_POST['rtParentType'])? 'select_treelistRtBoolean' : 'select_treelist';

		if(empty($_POST['id'])){
			$rows = array(array('id'=>PARENT_ID,'code' => 'root','name'=> '预算目录','isParent'=>'true'));
		}else{
			$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
			$service->searchArr['parentId'] = $parentId;
			$service->asc = false;
			$rows=$service->listBySqlId($sqlKey);
		}
		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * 检测是否存在根节点，不存在则添加一条
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
	 * 跳转到新增人力预算项目
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId),null,'id,personLevel');
        $this->assignFunc($row);

		$this->view('add');
	}

	/**
	 * 跳转到编辑人力预算项目
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看人力预算项目
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 改变启用状态
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