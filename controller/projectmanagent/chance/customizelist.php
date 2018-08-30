<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:32:55
 * @version 1.0
 * @description:订单自定义清单控制层 产品清单

 */
class controller_projectmanagent_chance_customizelist extends controller_base_action {

	function __construct() {
		$this->objName = "customizelist";
		$this->objPath = "projectmanagent_chance";
		parent::__construct ();
	 }

	/*
	 * 跳转到订单自定义清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }



	/**
	 * 初始化对象
	 */
	function c_init() {
		//$returnObj = $this->objName;
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}

//	//跳转 临时物料处理页面
//    function c_handle(){
//        $obj = $this->service->get_d ( $_GET ['id'] );
//		foreach ( $obj as $key => $val ) {
//			$this->assign ( $key, $val );
//		}
//    	$this->display("handle");
//    }
 }
?>