<?php
/**
 * @author Administrator
 * @Date 2012-06-29 10:15:12
 * @version 1.0
 * @description:销售助理操作记录控制层
 */
class controller_contract_contract_aidhandle extends controller_base_action {

	function __construct() {
		$this->objName = "aidhandle";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * 跳转到销售助理操作记录列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增销售助理操作记录页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑销售助理操作记录页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看销售助理操作记录页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

   /**
    * 销售助理功能按钮选择合同列表
    */
   function c_chooseContract(){
   	  $this->assign("handleType",$_GET['handleType']);
   	  $this->view("chooseContract");
   }

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		//$service->asc = false;
		$rows = $service->pageBySqlId('select_gridinfo');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}
 }
?>