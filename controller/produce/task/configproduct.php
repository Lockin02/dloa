<?php
/**
 * @author yxin1
 * @Date 2014年8月26日 14:07:59
 * @version 1.0
 * @description:生产任务配置物料控制层 
 */
class controller_produce_task_configproduct extends controller_base_action {

	function __construct() {
		$this->objName = "configproduct";
		$this->objPath = "produce_task";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到生产任务配置物料列表
	 */
    function c_page() {
      $this->view('list');
    }
    
    /**
	 * 跳转到新增生产任务配置物料页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
    /**
	 * 跳转到编辑生产任务配置物料页面
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
	 * 跳转到查看生产任务配置物料页面
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
   function c_listJsonStatistics() {
	   	$service = $this->service;
	   	if(empty($_REQUEST['taskIds'])){
	   		unset($_REQUEST['taskIds']);
	   	}
	   	$service->getParam ( $_REQUEST );
	   	$rows = $service->list_d ('select_statistics');
	   	//数据加入安全码
	   	$rows = $this->sconfig->md5Rows ( $rows );
	   	echo util_jsonUtil::encode ( $rows );
   }
 }