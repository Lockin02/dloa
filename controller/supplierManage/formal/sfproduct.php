<?php
/**
 * @description: 供应商产品控制层类
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_formal_sfproduct extends controller_base_action {
	function __construct() {
		$this->objName = "sfproduct";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	}

	/*
 * 跳转到正式库供应商产品列表
 */
	function c_tosfprolist() {
		$this->show->assign ( 'parentId', $_GET ['parentId'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/*
 * 正式库联系人列表数据获取
 */
	function c_pageJson() {
		$service = $this->service;
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$this->searchVal ( 'productName' );
		$rows = $this->service->proInSupp ( $parentId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 跳转到编辑页面供应产品修改页面
	 */
	function c_toProEdit() {
		$this->permCheck ($_GET['parentId'],'supplierManage_formal_flibrary');//安全校验

		$service = $this->service;
		$service->searchArr = array ("parentId" => $_GET ['parentId'] );
		$arr = $service->list_d ();
		$productIds = array ();
		$productNames = array ();
		if (is_array ( $arr )) {
			foreach ( $arr as $key => $value ) {
				$productIds [] = $value ['productId'];
				$productNames [] = $value ['productName'];
			}
		}
		$ids = implode ( ",", $productIds );
		$names = implode ( ",", $productNames );
		$this->show->assign ( 'productIds', $ids );
		$this->show->assign ( 'productNames', $names );
		$this->show->assign ( 'parentCode', $_GET ['parentCode'] );
		$this->show->assign ( 'parentId', $_GET ['parentId'] );
		$this->show->assign ( 'perm', $_GET ['perm'] );
		$this->display ('proE',true);
	}

	/**
	 * @desription 跳转正式库新增选择供应商产品
	 * @param tags
	 * @date 2010-11-8 下午02:18:04
	 */
	function c_toAdd() {
		$sysCode = generatorSerial ();
		$objCode = generatorSerial ();
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( 'objCode', $objCode );
		$this->show->assign ( 'systemCode', $sysCode );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * 更改供应产品
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			showmsg ( '更改供应产品成功！' );
		}
	}

}
?>
