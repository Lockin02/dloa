<?php
/**
 * @author Administrator
 * @Date 2012-07-31 14:36:06
 * @version 1.0
 * @description:商机产品清单控制层
 */
class controller_projectmanagent_chance_goods extends controller_base_action {

	function __construct() {
		$this->objName = "goods";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * 跳转到商机简单产品表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增商机简单产品表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑商机简单产品表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看商机简单产品表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

   /**
    * 定时保存信息列表主从表个从表数据
    */
   	function c_timingPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		$service->asc = true;
		$service->searchArr['timingDate'] = $_GET['timingDate'];
		$rows = $service->pageBySqlId("select_timing");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
 }
?>