<?php
/**
 * @author jianjungki
 * @Date 2012年8月6日 15:23:48
 * @version 1.0
 * @description:员工考核项目细则控制层
 */
class controller_hr_permanent_schemelist extends controller_base_action {

	function __construct() {
		$this->objName = "schemelist";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * 跳转到员工考核项目细则列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增员工考核项目细则页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑员工考核项目细则页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看员工考核项目细则页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>