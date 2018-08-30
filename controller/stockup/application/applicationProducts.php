<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:22:42
 * @version 1.0
 * @description:产品备货明细表控制层 
 */
class controller_stockup_application_applicationProducts extends controller_base_action {

	function __construct() {
		$this->objName = "applicationProducts";
		$this->objPath = "stockup_application";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到产品备货明细表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增产品备货明细表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑产品备货明细表页面
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
	 * 跳转到查看产品备货明细表页面
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
	 * 表格方法
	 */
	function c_pageItemJson(){
    	$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('pageItem');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
    }
 }
?>