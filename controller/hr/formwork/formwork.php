<?php
/**
 * @author Administrator
 * @Date 2012-07-12 14:04:29
 * @version 1.0
 * @description:人资模板设置控制层
 */
class controller_hr_formwork_formwork extends controller_base_action {

	function __construct() {
		$this->objName = "formwork";
		$this->objPath = "hr_formwork";
		parent::__construct ();
	}

	/**
	 * 跳转到人资模板设置列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增人资模板设置页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑人资模板设置页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if ($obj ['isUse'] == '0') {
			$this->assign ( 'yes', 'checked' );
		} else if ($obj ['isUse'] == '1') {
			$this->assign ( 'no', 'checked' );
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看人资模板设置页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		if($obj['isUse'] == "0"){
			$obj['isUse'] = "启用";
		}else if($obj['isUse'] == "1"){
			$obj['isUse'] = "停止";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 根据id获取模板内同
	 */
	function c_formworkContent(){
		$info = $this->service->formworkContent_d($_POST ['id']);
		echo util_jsonUtil::iconvGB2UTF ($info);;
	}

	/**
	 * 模板配置
	 */
	function c_formworkdeploy(){
		$this->assign("type",$_GET['type']);
		$this->view ( 'formworkdeploy' );
	}

	/**
	 * 模板配置--存储所选模板id
	 */
	function c_formworkdeployEdit(){
		try {
			$this->service->formworkdeployEdit_d($_POST['ids'],$_POST['type']);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 判断模板
	 */
	function c_formworkLimit(){
		$ids = $this->service->formworkLimit_d($_POST['type']);
		echo $ids;
	}
 }
?>