<?php
/**
 * @author Administrator
 * @Date 2013年6月13日 星期四 19:54:04
 * @version 1.0
 * @description:人员技术等级控制层
 */
class controller_hr_basicinfo_level extends controller_base_action {

	function __construct() {
		$this->objName = "level";
		$this->objPath = "hr_basicinfo";
		parent::__construct ();
	}

	/**
	 * 跳转到人员技术等级列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增人员技术等级页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑人员技术等级页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看人员技术等级页面
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
	 * ajax获取级别中文
	 */
	function c_ajaxGetName() {
		$id = $_POST['id'];
		$obj = $this->service->get_d( $id );
		echo util_jsonUtil::iconvGB2UTF($obj['personLevel']);
	}
}
?>