<?php
/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_produce_document_documenttype extends controller_base_action {

	function __construct() {
		$this->objName = "documenttype";
		$this->objPath = "produce_document";
		parent::__construct ();
	}

	/**
	 * 物料类型列表页面
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if (isset ( $service->searchArr ['parentId'] )) {
            $service->searchArr['customCondition'] = "sql: and (c.parentId = '".$service->searchArr ['parentId']."' or id = '".$service->searchArr ['parentId']."')";
			unset ( $service->searchArr ['parentId'] );
		}
		$service->sort = 'c.orderNum';
		$service->asc = false;
        $rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 根据parentId获取物料树形数据
	 */
	function c_getTreeDataByParentId() {
		$service = $this->service;
		if(isset ( $_POST ['id'] )){//用于用户点击展开树
			$service->searchArr ['parentId'] = $_POST ['id'];
		}else{//用于第一次加载
			if(isset ( $_GET ['typeId'] ) && !empty($_GET ['typeId'])){//限定某个分类
				$service->searchArr ['id'] = $_GET ['typeId'];
			}else{//不限定某个分类，显示除了最顶级分类外的其它分类
				$service->searchArr ['parentId'] = -1;
				$service->searchArr ['nid'] = -1;
			}
		}
		$service->asc = false;
		$rows = $service->listBySqlId ( 'select_treeinfo' );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 新增页面
	 */
	function c_toAdd() {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : "";
		$parentName = isset ( $_GET ['parentId'] ) ? $_GET ['parentName'] : "";
	
		if ($parentId == "") {
			$parentId = - 1;
			$parentName = "文档分类";
		}
	
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentName", $parentName );
	
		$this->display ( "add" );
	}
	
	/**
	 * 修改页面
	 */
	function c_toEdit() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		$this->assign ( "type", $_GET['type'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display ( "edit" );
	}
	
	/**
	 * 查看页面
	 */
	function c_toView() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('isUse',$obj['isUse'] == 1 ? '是' : '否');
	
		$this->display ( "view" );
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName] );
		if ($id) {
			msg ( "新增物料分类成功！" );
		} else {
			msg ( "新增物料分类信息失败！" );
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msg ( "修改文档分类成功！" );
		} else {
			msg ( "修改文档分类失败！" );
		}
	}

	/**
	 * 检查物料类型是否重复
	 */
	function c_checktype() {
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("ntype" => $type );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	function c_ajustNode(){
		echo $this->service->createTreeLRValue();
	}
}
