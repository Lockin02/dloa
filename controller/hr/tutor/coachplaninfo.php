<?php
/**
 * @author Administrator
 * @Date 2012-08-23 16:55:05
 * @version 1.0
 * @description:员工辅导计划详细表控制层
 */
class controller_hr_tutor_coachplaninfo extends controller_base_action {

	function __construct() {
		$this->objName = "coachplaninfo";
		$this->objPath = "hr_tutor";
		parent::__construct ();
	 }

	/**
	 * 跳转到员工辅导计划详细表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增员工辅导计划详细表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑员工辅导计划详细表页面
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
	 * 跳转到查看员工辅导计划详细表页面
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
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->asc = false;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>