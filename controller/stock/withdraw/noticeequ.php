<?php
/**
 * @author Administrator
 * @Date 2012年11月20日 10:22:14
 * @version 1.0
 * @description:入库通知单清单控制层 
 */
class controller_stock_withdraw_noticeequ extends controller_base_action {

	function __construct() {
		$this->objName = "noticeequ";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	 }
    
	/*
	 * 跳转到入库通知单清单列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增入库通知单清单页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑入库通知单清单页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看入库通知单清单页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
   
   /**
    * 生产计划单入库时，获取实际可入库的物料数量
    */
   function c_getNumAtInStock(){
   	$rs = $this->service->find(array('mainId' => $_POST['mainId'],'productId' => $_POST['productId']),null,'number,executedNum');
   	echo $rs['number'] - $rs['executedNum'];
   }
 }