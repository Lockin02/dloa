<?php
/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知物料清单控制层
 */
class controller_stock_withdraw_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	 }

	/*
	 * 跳转到收货通知物料清单列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增收货通知物料清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑收货通知物料清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看收货通知物料清单页面
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
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($rows);
//		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonInnotice() {
		$service = $this->service;
		$_REQUEST['numSql'] = 'sql:and (c.executedNum < (c.qPassNum + c.qBackNum))';
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($rows);
//		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>