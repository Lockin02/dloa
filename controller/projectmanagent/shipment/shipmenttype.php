<?php
/**
 * @author Administrator
 * @Date 2012年5月23日 9:50:17
 * @version 1.0
 * @description:发货需求自定义类型控制层
 */
class controller_projectmanagent_shipment_shipmenttype extends controller_base_action {

	function __construct() {
		$this->objName = "shipmenttype";
		$this->objPath = "projectmanagent_shipment";
		parent::__construct ();
	 }

	/*
	 * 跳转到发货需求自定义类型列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增发货需求自定义类型页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑发货需求自定义类型页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * 跳转到查看发货需求自定义类型页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * grid列表下拉过滤
	 */
	function c_getSelection(){
		$rows = $this->service->list_d();
		$datas = array();
		foreach( $rows as $key=>$val ){
			$datas[$key]['text']=$val['type'];
			$datas[$key]['value']=$val['id'];
		}
		echo util_jsonUtil::encode ( $datas );
	}

 }
?>