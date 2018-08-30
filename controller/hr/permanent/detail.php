<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:45
 * @version 1.0
 * @description:员工转正考核明细表控制层
 */
class controller_hr_permanent_detail extends controller_base_action {

	function __construct() {
		$this->objName = "detail";
		$this->objPath = "hr_permanent";
		parent::__construct ();
	 }

	/*
	 * 跳转到员工转正考核明细表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增员工转正考核明细表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑员工转正考核明细表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看员工转正考核明细表页面
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