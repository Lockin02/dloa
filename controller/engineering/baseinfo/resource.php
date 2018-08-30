<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 13:59:48
 * @version 1.0
 * @description:资源目录(oa_esm_baseinfo_resource)控制层
 */
class controller_engineering_baseinfo_resource extends controller_base_action {

	function __construct() {
		$this->objName = "resource";
		$this->objPath = "engineering_baseinfo";
		parent::__construct ();
	}

	/*
	 * 跳转到资源目录(oa_esm_baseinfo_resource)
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
			$rows = array(array('id'=>PARENT_ID,'code' => 'root','name'=> '资源目录','isParent'=>'true'));
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
	 * 跳转到新增页面
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId));
        $this->assignFunc($row);
        $this->view('add');
	}

		/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign ( 'resourceNature', $this->getDataNameByCode ( $obj ['resourceNature'] ) );
			$this->view ( 'view' );
		} else {
			$this->showDatadicts ( array ('resourceNature' => 'GCXMZYXZ' ), $obj ['resourceNature'] );
			$this->view ( 'edit' );
		}
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

	/**
	 * 删除时判断是否被引用
	 */
	 function c_deleteCheck(){
		if($this->service->deleteCheck_d($_POST['rowData']) == 1){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	 }
}
?>