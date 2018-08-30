<?php
/**
 * @description: 供应商临时库产品信息
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_temporary_stproduct extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-9 下午08:53:05
	 */
	function __construct() {
		$this->objName = "stproduct";
		$this->objPath = "supplierManage_temporary";
		parent::__construct ();
	}

	/**
	 * @desription 跳转新增选择供应商产品
	 */
	function c_stpToAdd() {
		//TODO:连接到数据
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;

		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription 跳转编辑供应商产品
	 */
	function c_stpToEdit() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->service->searchArr = array ("parentId" => $parentId );
		$arr = $this->service->list_d ();
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
		$this->show->assign ( "parentId", $parentId );
		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( "perm", $_GET ['perm'] );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * 更改供应产品
	 */
	function c_add() {
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ($object , true );
		if ($id) {
			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $object['parentId'] .'&parentCode=' .$object['parentCode']);
		}
	}
	/**
	 * 编辑更改供应产品
	 */
	function c_edadd() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
//			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $_GET['parentId'] .'&parentCode=' .$_GET['parentCode']);
			showmsg ( '更改供应产品成功！' );
		}
	}


	/**
	 * @desription 跳转到产品列表页面
	 * @param tags
	 * @date 2010-11-8 下午02:18:04
	 */
	function c_toRdconlist() {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->show->assign ( 'parentId', $parentId );
		$this->show->assign ( 'parentCode', $parentCode );

		$this->show->display ( $this->objPath . '_' . $this->objName . '-Rdprolist' );
	}

	/**
	 * @desription 返回上一页--从注册的评价页面往前跳转到产品添加页面，并处于可编辑状态
	 * @param tags
	 * @date 2010-11-25 上午10:11:21
	 */
	function c_toEditProd () {
		$parentId = isset ( $_GET ['parentId'] ) ? $_GET ['parentId'] : null;
//		$parentCode = isset ( $_GET ['parentCode'] ) ? $_GET ['parentCode'] : null;
		$this->service->searchArr = array ("parentId" => $parentId );
		$arr = $this->service->list_d ();
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
		$this->show->assign ( "parentId", $parentId );
//		$this->show->assign ( "parentCode", $parentCode );
		$this->show->assign ( "perm", $_GET ['perm'] );
		$this->show->display( $this->objPath . '_' . $this->objName . '-edit1' );
	}

}
?>
