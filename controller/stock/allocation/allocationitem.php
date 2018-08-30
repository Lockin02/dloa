<?php
/**
 * @author huangzf
 * @Date 2011年5月21日 16:09:16
 * @version 1.0
 * @description:调拨单物料清单控制层 
 */
class controller_stock_allocation_allocationitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "allocationitem";
		$this->objPath = "stock_allocation";
		parent::__construct ();
	}
	
	/*
	 * 跳转到调拨单物料清单
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	
	/**
	 * 归还物料信息
	 */
	function c_backItemPage() {
		$chanceDao = new model_projectmanagent_chance_chance ();
		
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : null;
		$relDocIdArr = $chanceDao->findBorrow ( $relDocId );
		$relDocIdStr = "-1";
		if (is_array ( $relDocIdArr ))
			$relDocIdStr = implode ( ",", $relDocIdArr );
		$this->assign ( "relDocId", $relDocIdStr );
		$this->view ( "back-list" );
	}
	
	/**
	 * 归还物料信息JSON
	 * 
	 */
	function c_pageBackGridJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->sort = "m.outEndDate";
		$rows = $service->page_d ( "select_back" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
}
?>