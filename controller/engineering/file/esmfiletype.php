<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:23:34
 * @version 1.0
 * @description:项目文档类型控制层 
 */
class controller_engineering_file_esmfiletype extends controller_base_action {
	function __construct() {
		$this->objName = "esmfiletype";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}
	
	/**
	 * 跳转到项目文档类型列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增项目文档类型页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑项目文档类型页面
	 */
	function c_toEdit() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看项目文档类型页面
	 */
	function c_toView() {
		$this->permCheck (); // 安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 * 获取树
	 */
	function c_getTree() {
		$service = $this->service;
		$rows = $service->getTree_d($_GET ['projectId']);
		echo util_jsonUtil::encode ( $rows );
	}
	/**
	 * 检测是否有未提交的文档
	 */
	function c_checkFileSubmit(){
		if($this->service->checkFileSubmit_d($_POST)){
			echo 0;//不存在未提交的附件
		}else{
			echo 1;//存在未提交的附件
		}
	}
}