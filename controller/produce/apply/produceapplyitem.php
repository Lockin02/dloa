<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 星期五 13:38:37
 * @version 1.0
 * @description:生产申请清单控制层
 */
class controller_produce_apply_produceapplyitem extends controller_base_action {

	function __construct() {
		$this->objName = "produceapplyitem";
		$this->objPath = "produce_apply";
		parent::__construct ();
	}

	/*
	 * 跳转到生产申请清单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增生产申请清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产申请清单页面
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
	 * 跳转到查看生产申请清单页面
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
	 * 获取生产清单editgrid数据
	 */
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
//		$arr = array ();
//		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取生产清单subgrid数据
	 */
	function c_subItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取所有数据返回json（关联了合同发货清单）
	 * (包含需求数量、已执行数量)
	 */
	function c_contractListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_contract');
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 重写listJson(带出库存数量和在途数量)
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['inventory'] = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //在途数量
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
//		echo "<pre>";
//		print_r($rows);
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 根据物料id和申请需求id求可下达任务数量
	 */
	function c_ajaxGetTaskNum() {
		$productId = $_POST['productId'];
		$mainId = $_POST['mainId'];
		$obj = $this->service->find(array('mainId' => $mainId ,'productId' => $productId));
		$taskNum = $obj['produceNum'] - $obj['exeNum'];
		if ($taskNum > 0) {
			echo $taskNum;
		} else {
			echo 0;
		}
	}

	/**
	 * 获取物料汇总需求数据转成Json
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.productId';
		$rows = $service->page_d('select_product');
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach ($rows as $key => $val) {
				$rows[$key]['inventory'] = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 物料下拉选择Json
	 */
	function c_productJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_product');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取关联主表数据转成Json
	 */
	function c_mainPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = ' c.id ';
		$rows = $service->page_d('select_main');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		// if (is_array($rows)) {
		// 	$taskDao = new model_produce_task_producetask();
		// 	$taskArr = array();
		// 	foreach ($rows as $key => $val) {
		// 		if ($val["docStatus"] == 1 || $val["docStatus"] == 2) { //生产申请为部分下达或全部下达
		// 			if (empty($taskArr[$val["id"]])) {
		// 				$taskObj = $taskDao->find("applyDocId=$val[mainId] AND productId=$val[productId] AND docStatus IN(0,1,2)" ,' id DESC ');
		// 				$taskArr[$val["id"]] = $taskObj;
		// 			} else {
		// 				$taskObj = $taskArr[$val["id"]];
		// 			}
		// 			if ($taskObj["id"]) {
		// 				if ($taskObj["docStatus"] == 0) {
		// 					$rows[$key]["taskId"] = -1; //未接收任务
		// 				} else if ($taskObj["docStatus"] == 1) {
		// 					$rows[$key]["taskId"] = 0; //已接收任务
		// 				} else {
		// 					$rows[$key]["taskId"] = $taskObj["id"]; //已制定计划
		// 				}
		// 			} else {
		// 				$rows[$key]["taskId"] = -2; //未下达任务
		// 			}
		// 		} else {
		// 			$rows[$key]["taskId"] = -3;
		// 		}
		// 	}
		// }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 下达生产任务时获取所有数据返回json
	 */
	function c_listJsonByTask() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listJsonByTask_d ( $_POST );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}