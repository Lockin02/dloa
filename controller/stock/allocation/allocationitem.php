<?php
/**
 * @author huangzf
 * @Date 2011��5��21�� 16:09:16
 * @version 1.0
 * @description:�����������嵥���Ʋ� 
 */
class controller_stock_allocation_allocationitem extends controller_base_action {
	
	function __construct() {
		$this->objName = "allocationitem";
		$this->objPath = "stock_allocation";
		parent::__construct ();
	}
	
	/*
	 * ��ת�������������嵥
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	
	/**
	 * �黹������Ϣ
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
	 * �黹������ϢJSON
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