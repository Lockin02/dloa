<?php
/**
 * @author Administrator
 * @Date 2012-10-25 14:53:11
 * @version 1.0
 * @description:设备分类信息控制层
 */
class controller_equipment_budget_budgetType extends controller_base_action {

	function __construct() {
		$this->objName = "budgetType";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * 跳转到设备分类信息列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增设备分类信息页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑设备分类信息页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看设备分类信息页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   /******************设备分类树************************************/
	/**
	 * 根据parentId产品类型信息
	 */
	function c_getTreeData() {
		$service = $this->service;
		$parentId = isset ( $_POST ['id'] ) ? $_POST ['id'] : - 1;

		$service->searchArr ['parentId'] = $parentId;
		$service->sort = " c.orderNum";
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = array ();
		if (isset ( $service->searchArr ['parentId'] )) {
			$node = $service->get_d ( $service->searchArr ['parentId'] );
			unset ( $service->searchArr ['parentId'] );
			$service->searchArr ['dlft'] = $node ['lft'];
			$service->searchArr ['xrgt'] = $node ['rgt'];
			$rows = $service->page_d ( "select_default" );
		} else {
			$rows = $service->page_d ();
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>