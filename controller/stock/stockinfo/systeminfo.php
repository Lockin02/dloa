<?php
/**
 * @author Administrator
 * @Date 2011年5月28日 16:50:58
 * @version 1.0
 * @description:仓存管理基础信息设置控制层 
 */
class controller_stock_stockinfo_systeminfo extends controller_base_action {
	
	function __construct() {
		$this->objName = "systeminfo";
		$this->objPath = "stock_stockinfo";
		parent::__construct ();
	}
	
	/*
	 * 跳转到仓存管理基础信息设置
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	
	/**
	 * 跳转修改基础仓库设置
	 */
	function c_init() {
//		print_r($_SESSION);
		$service = $this->service;
		//$service->searchArr=array("")
		
		$this->showDatadicts(array('unitCode'=>'QYZT'));
		$obj = $service->get_d ( $_GET ['id'] );
//		echo "<pre>";
//		print_r($obj);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "edit" );
	}
	
	/**
	 * 修改基础信息 
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, false )) {
			msgGo ( "设置成功！", "index1.php?model=stock_stockinfo_systeminfo&action=init&id=$object[id]" );
		}
	}
	/**
	 * 获取包装仓库id
	 */
	function c_getPackStockId() {
		$service = $this->service;
		$obj = $service->get_d ( $_POST ['id'] );
		$result = 0;
		if (is_array ( $obj ))
			$result = $obj ['packingStockId'];
		echo $result;
	}
	
	/**
	 * 获取默认仓库信息
	 */
	function c_getDefaultStock() {
		$service = $this->service;
		$obj = $service->get_d ( $_POST ['id'] );
		$result = 0;
		if (is_array ( $obj ))
			echo util_jsonUtil::encode ( $obj );
		else
			echo $result;
	}
	
	/**
	 * 仓存管理导航图
	 */
	function c_toMap() {
		$this->view ( "map" );
	}
}
?>