<?php
/**
 * @author Michael
 * @Date 2014年5月30日 10:13:44
 * @version 1.0
 * @description:交付加密锁任务从表控制层
 */
class controller_stock_delivery_encryptionequ extends controller_base_action {

	function __construct() {
		$this->objName = "encryptionequ";
		$this->objPath = "stock_delivery";
		parent::__construct ();
	}

	/**
	 * 跳转到交付加密锁任务从表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增交付加密锁任务从表页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑交付加密锁任务从表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看交付加密锁任务从表页面
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
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
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
 }
?>