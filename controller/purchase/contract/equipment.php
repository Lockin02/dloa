<?php
/*
 * Created on 2011-1-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class controller_purchase_contract_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_contract";
		parent::__construct ();
	}
 	/**
	 * 外部接口
	 * 通过产品清单Id返回未下达数量
	 */
	function c_getAppProNotIssNum(){
		$purchAppProId = isset( $_GET['purchAppProId'] )?$_GET['purchAppProId']:exit;
		echo $this->service->getAppProNotIssNum_d($purchAppProId);
	}

	/**
	 * 在执行的订单物料pagejson
	 */
	function c_managPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$newRows=$service->dealExeRows_d($rows);
		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

		function c_toEditDate() {
			$service = $this->service;
			$rows = $service->get_d ( $_GET ['id'] );
			foreach ( $rows as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->display ( 'edit' );
		}
 }
?>
