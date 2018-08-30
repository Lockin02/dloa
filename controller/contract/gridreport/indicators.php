<?php
/**
 * @author yxin1
 * @Date 2014年12月2日 14:42:10
 * @version 1.0
 * @description:指标值表控制层
 */
class controller_contract_gridreport_indicators extends controller_base_action {

	function __construct() {
		$this->objName = "indicators";
		$this->objPath = "contract_gridreport";
		parent::__construct ();
	}

	/**
	 * 跳转到指标值表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增指标值表页面
	 */
	function c_toAdd() {
		$gridDao = new model_contract_gridreport_gridindicators();
		$gridObj = $gridDao->get_d ( $_GET ['gridId'] );
		foreach ( $gridObj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑指标值表页面
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
	* 跳转到查看指标值表页面
	*/
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
}
?>