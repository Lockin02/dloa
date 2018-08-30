<?php
/**
 * @author show
 * @Date 2013年11月25日 10:56:46
 * @version 1.0
 * @description:(新license)配置备注信息控制层
 */
class controller_yxlicense_license_categorytips extends controller_base_action {

	function __construct() {
		$this->objName = "categorytips";
		$this->objPath = "yxlicense_license";
		parent :: __construct();
	}

	/**
	 * 跳转到(新license)配置备注信息列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到备注功能信息页面
	 */
	function c_toTips() {
		$this->assign('formId',$_GET['id']);
		$this->view ( 'tips' );
	}
	
	//新增
	function c_tips() {
		$obj = $_POST[$this->objName];
		$id = $this->service->tips_d ($obj);
		if ($id) {
			msg ( "保存成功" );
		} else {
			msg ( "保存失败" );
		}
	}
}