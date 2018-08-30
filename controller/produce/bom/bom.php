<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:45:00
 * @version 1.0
 * @description:BOM表控制层 注:同一物料不能同时出现在同一BOM的父项物料与子项物料中
 */
class controller_produce_bom_bom extends controller_base_action {
	
	function __construct() {
		$this->objName = "bom";
		$this->objPath = "produce_bom";
		parent::__construct ();
	}
	
	/*
	 * 跳转到BOM表列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增BOM表页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑BOM表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "propertiesName", $this->getDataNameByCode ( $obj ['properties'] ) );
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看BOM表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "propertiesName", $this->getDataNameByCode ( $obj ['properties'] ) );
		$this->view ( 'view' );
	}
	
	/**
	 *
	 * 删除
	 */
	function c_ajaxdeletes() {
		//$this->permDelCheck ();
		try {
			$this->service->deletes_d ( $_GET ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		try {
			$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
			if ($id) {
				msg ( $msg );
			}
		} catch ( Exception $e ) {
			echo "<script>alert('" . $e->getMessage () . "');self.parent.tb_remove();</script>";
		}
	}
	
	/**
	 * 修改对象
	 */
	function c_edit() {
		try {
			$object = $_POST [$this->objName];
			if ($this->service->edit_d ( $object, true )) {
				msg ( '编辑成功！' );
			}
		} catch ( Exception $e ) {
			echo "<script>alert('" . $e->getMessage () . "');self.parent.tb_remove();</script>";
		}
	}
	
	/**
	 *
	 * 确认审核
	 */
	function c_audit() {
		$object = array ("docStatus" => "YSH", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
	
	/**
	 *
	 * 确认反审核
	 */
	function c_cancelAudit() {
		$object = array ("docStatus" => "WSH", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
	
	/**
	 * 
	 * 根据物料编号及版本判断是否已设置
	 */
	function c_checkBomReat() {
		$productCode = isset ( $_POST ['productCode'] ) ? $_POST ['productCode'] : false;
		$version = isset ( $_POST ['version'] ) ? $_POST ['version'] : false;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$searchArr = array ("yproductCode" => $productCode, "version" => $version );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	/**
	 * 
	 * 启用物料BOM设置
	 */
	function c_actUseStatus() {
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		if ($this->service->actUseStatus_d ( $id, $productId ))
			echo "0";
		else
			echo "1";
	
	}

}
?>