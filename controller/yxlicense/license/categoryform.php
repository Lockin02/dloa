<?php
/**
 * @author Administrator
 * @Date 2012年3月11日 15:15:40
 * @version 1.0
 * @description:产品分类信息控制层
 */
class controller_yxlicense_license_categoryform extends controller_base_action {

	function __construct() {
		$this->objName = "categoryform";
		$this->objPath = "yxlicense_license";
		parent::__construct ();
	}

	/*
	 * 跳转到表单功能信息列表
	 */
	function c_page() {
//		$this->assign('isUse',$_GET['isUse']);
    	$this->assign('itemId',$_GET['id']);
 //   	$this->assign('name',$_GET['name']);
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增功能页面
	 */
	function c_toAdd() {
		$this->assign('itemId',$_GET['id']);
		$this->view ( 'add' );
	}
	
	//新增
	function c_add() {
		$obj = $_POST[$this->objName];
		$id = $this->service->add_d ($obj);
		if ($id) {
			msg ( "添加成功" );
		} else {
			msg ( "添加失败" );
		}
	}
	
	/**
	 * 跳转到编辑功能信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	/**
	 * 跳转到详细配置信息页面
	 */
	function c_toDetailEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'detailedit' );
	}
	

	/**
	 * 跳转到查看功能信息页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		
		$this->view ( 'view' );
	}

	/**
	 * 根据licenseId产品类型信息
	 */
	function c_getTreeData() {
		$service = $this->service;
		$isSale = isset ( $_GET ['isSale'] ) ? $_GET ['isSale'] : 0;
		if($isSale == '1'){
			$service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.licenseId='11')";
		}
//		$licenseId = isset ( $_POST ['id'] ) ? $_POST ['id'] : 1;
//		$service->searchArr ['licenseId'] = $licenseId;
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
		$rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * 重新调整产品树的左右节点
	 */
	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}
	
	/**
	 * 跳转到预览页面
	 */
	function c_preview(){
		$this->assign('name',$_GET['licenseName']);
		$this->assign('id',$_GET['id']);
		$this->view('preview');
	}
	
// 	function c_returnHtml(){
// 		$id = $_REQUEST['id'] ? $_REQUEST['id'] : null;
		
		
// 	}
}
?>