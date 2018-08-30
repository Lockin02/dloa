<?php
/**
 * @author huangzf
 * @Date 2012年7月19日 星期四 10:43:44
 * @version 1.0
 * @description:系统管理配置枚举控制层 
 */
class controller_system_configenum_configenum extends controller_base_action {
	
	function __construct() {
		$this->objName = "configenum";
		$this->objPath = "system_configenum";
		parent::__construct ();
	}
	
	/**
	 * 跳转到系统管理配置枚举列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增系统管理配置枚举页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑系统管理配置枚举页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch ($_GET ['id']) {
			case 1 :
				$this->view ( "accessorder-edit" );
				break;
			case 2 :
				$this->view ( "repairapply-edit" );
				break;
			case 3 :
				$this->view ( "saftystock-edit" );
				break;
		}
	
		//		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看系统管理配置枚举页面
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
	 * 修改基础信息 
	 */
	function c_edit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, false )) {
			if (isset ( $_GET ['formType'] )) {
				msg ( "设置成功!" );
			} else {
				msgGo ( "设置成功！", "index1.php?model=system_configenum_configenum&action=toEdit&id=$object[id]" );
			
			}
		}
	}
	
	/**
	 * 
	 * 获取某一配置某一项值
	 */
	function c_getEnumFieldVal() {
		echo $this->service->getEnumFieldVal ( $_POST ['id'], $_POST ['fieldName'] );
	}
}
?>