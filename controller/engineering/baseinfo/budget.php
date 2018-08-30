<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 9:38:59
 * @version 1.0
 * @description:预算项目(oa_esm_baseinfo_budget)控制层 status
                                                0 启用
                                                1.禁用
 */
class controller_engineering_baseinfo_budget extends controller_base_action {

	function __construct() {
		$this->objName = "budget";
		$this->objPath = "engineering_baseinfo";
		parent::__construct ();
	}

	/*
	 * 跳转到预算目录
	 */
    function c_page() {
		$parentId = isset($_POST['id'])? $_POST['id'] : PARENT_ID;
		$this->assign('parentId',$parentId);
       	$this->view('list');
    }

	/**
	 * 获取鼎利的预算数据
	 */
	function c_pageJsonDL() {
		$service = $this->service;

		$_POST['isNewDL'] = 1;
		$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->asc = false;
		$service->sort = 'c.ParentCostTypeID,c.CostTypeID';
		$rows = $service->page_d ('select_budgetdl');

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
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
	 * 跳转到新增页面
	 */
	function c_toAdd() {
        $parentId = $_GET['parentId'];
        $row = $this->service->find(array('id' => $parentId),null,'id,budgetCode,budgetName');
        $this->assignFunc($row);
		$this->showDatadicts ( array ('budgetType' => 'FYLX' ));
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
			$TypeOne = $this->getDataNameByCode ( $obj ['budgetType'] );
			$this->assign ( 'budgetType', $TypeOne );
			$this->view ( 'view' );
		} else {
			$this->showDatadicts ( array ('budgetType' => 'FYLX' ), $obj ['budgetType'] );
			$this->view ( 'edit' );
		}
	}
	/**
	 * @ ajax判断项
	 *
	 */
    function c_ajaxDudgetCode() {
    	$service = $this->service;
		$projectName = isset ( $_GET ['budgetCode'] ) ? $_GET ['budgetCode'] : false;

		$searchArr = array ("budgetCode" => $projectName );

		$isRepeat =$service->isRepeat ( $searchArr, "" );

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
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