<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:07:53
 * @version 1.0
 * @description:用车记录(oa_carrental_records)控制层
 */
class controller_carrental_records_carrecords extends controller_base_action {

	function __construct() {
		$this->objName = "carrecords";
		$this->objPath = "carrental_records";
		parent::__construct ();
	}

	/*
	 * 跳转到用车记录(oa_carrental_records)列表
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * 项目查看租车记录
     */
	function c_pageForProject(){
		$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
	}

   /**
	 * 跳转到新增用车记录(oa_carrental_records)页面
	 */
	function c_toAdd() {
     	$this->view ( 'add' );
	}

   /**
	 * 跳转到编辑用车记录(oa_carrental_records)页面
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
	 * 跳转到查看用车记录(oa_carrental_records)页面
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
    * 跳转查看车辆信息Tab
    */
    function c_toViewForCarinfo(){
    	$this->assign ( "carId", $_GET['id'] );
    	$this->view ( 'viewlist' );
    }

    /**
     * 跳转用车记录Tab
     */
	function c_viewTab(){
		$this->permCheck (); //安全校验
	 	$this->assign ( "id", $_GET['id'] );
	 	$this->view ( 'viewtab' );
	}
}
?>