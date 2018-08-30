<?php
/**
 * @author huangzf
 * @Date 2011年11月27日 14:36:58
 * @version 1.0
 * @description:零配件订单清单控制层 
 */
class controller_service_accessorder_accessorderitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "accessorderitem";
		$this->objPath = "service_accessorder";
		parent::__construct ();
	}
	
	/*
	 * 跳转到零配件订单清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增零配件订单清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑零配件订单清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看零配件订单清单页面
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
	 * 获取分页数据转成Json
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		
		foreach ( $rows as $key => $value ) {
			$tempArr = $service->getOutNum ( $value ['mainId'], $value ['productId'] );
			
			if (is_array ( $tempArr )) {
				$rows [$key] ['actOutNum'] = $tempArr [0] ['actOutNum'];
			} else {
				$rows [$key] ['actOutNum'] = 0;
			}
		}
		
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * 
	 * 判断是否已经填写发货单完毕
	 */
	function c_isShipAll() {
		$service = $this->service;
		echo $service->isShipAll_d ( $_POST ['mainId'] );
	
	}

}
?>