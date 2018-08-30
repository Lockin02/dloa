<?php
/**
 * @author Administrator
 * @Date 2012年3月11日 15:15:40
 * @version 1.0
 * @description:产品分类信息控制层
 */
class controller_goods_goods_goodstype extends controller_base_action {

	function __construct() {
		$this->objName = "goodstype";
		$this->objPath = "goods_goods";
		parent::__construct ();
	}

	/*
	 * 跳转到产品分类信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增产品分类信息页面
	 */
	function c_toAdd() {
	   $rowsId = $_GET['parentId'];
       $this->assign('parentId' , $_GET['parentId']);
       $rows = $this->service->find(array('id' => $rowsId));
       if( empty ($rows)){
        	$this->show->assign('parentName','');
       }else{
       	   $this->show->assign('parentName',$rows['goodsType']);
       }
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑产品分类信息页面
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
	 * 跳转到查看产品分类信息页面
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
	 * 根据parentId产品类型信息
	 */
	function c_getTreeData() {
		$service = $this->service;
	$isSale = isset ( $_GET ['isSale'] ) ? $_GET ['isSale'] : 0;
	if($isSale == '1'){
		$service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.parentId='11')";
	}
		$parentId = isset ( $_POST ['id'] ) ? $_POST ['id'] : -1;
		$service->searchArr ['parentId'] = $parentId;
        // 如果有传入typeId,则只显示相关的选项
        if($parentId == -1 && isset($_REQUEST['typeId']) && !empty($_REQUEST['typeId'])){
            $service->searchArr ['id'] = $_REQUEST['typeId'];
        }
		$service->sort = " c.orderNum";
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}

/**
 *海外获取国内OA产品信息
 */
	function c_getOATreeData($obj) {
		$service = $this->service;
	if($obj ['isSale'] == '1'){
		$service->searchArr ['mySearch'] = "sql: and ( c.id='11' or c.parentId='11')";
	}
		$parentId = isset ( $obj['parentId'] ) ? $obj['parentId'] : - 1;
		$service->searchArr ['parentId'] = $parentId;
		$service->sort = " c.orderNum";
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		return util_jsonUtil :: iconvGB2UTFArr ( $rows );
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

	/**
	 * hw获取分页数据转成Json
	 */
	function c_HWgetTypepageJson($objArr) {
		$service = $this->service;
		$service->getParam ( $objArr['object'] );
		$rows = array ();
		if (!empty ( $objArr['parentId'] )) {
			$node = $service->get_d ($objArr['parentId'] );
			unset ( $objArr['parentId'] );
			$service->searchArr ['dlft'] = $node ['lft'];
			$service->searchArr ['xrgt'] = $node ['rgt'];
			$rows = $service->page_d ( "select_default" );
		} else {
			$rows = $service->page_d ();
		}
		return util_jsonUtil :: iconvGB2UTFArr ( $rows );
	}

	/**
	 *
	 * 重新调整产品树的左右节点
	 */
	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}

	function c_ajaxdeletes(){
		try {
			$check = $this->service->isHaveSon_d( $_POST ['id'] );
			if($check == 0){
				$this->service->deletes_d ( $_POST ['id'] );
				echo 1;
			}
			else {
				echo 2;
			}
		} catch ( Exception $e ) {
			echo 0;
		}
	}
}
?>