<?php
/**
 * @author Administrator
 * @Date 2012年6月1日 星期五 16:54:00
 * @version 1.0
 * @description:产品物料库存采购销售综合表清单控制层 
 */
class controller_stock_extra_procompositebaseitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "procompositebaseitem";
		$this->objPath = "stock_extra";
		parent::__construct ();
	}
	
	/**
	 * 跳转到产品物料库存采购销售综合表清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增产品物料库存采购销售综合表清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑产品物料库存采购销售综合表清单页面
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
	 * 跳转到查看产品物料库存采购销售综合表清单页面
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
	 * 
	 * 根据主表id获取editgrid清单Json
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}
}
?>