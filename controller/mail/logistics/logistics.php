<?php
/**
 * @author Show
 * @Date 2011年5月27日 星期五 9:35:54
 * @version 1.0
 * @description:物流公司基本信息控制层
 */
class controller_mail_logistics_logistics extends controller_base_action {

	function __construct() {
		$this->objName = "logistics";
		$this->objPath = "mail_logistics";
		parent :: __construct();
	}

	/*
	 * 跳转到物流公司基本信息
	 */
	function c_page() {
		$this->display('list');
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
			$this->assign('isDefault',$this->service->rtYesOrNo($obj['isDefault']));
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
}
?>