<?php
/**
 * @author Administrator
 * @Date 2012��5��11�� ������ 13:38:37
 * @version 1.0
 * @description:���������嵥���Ʋ�
 */
class controller_produce_apply_produceapplyitem extends controller_base_action {

	function __construct() {
		$this->objName = "produceapplyitem";
		$this->objPath = "produce_apply";
		parent::__construct ();
	}

	/*
	 * ��ת�����������嵥�б�
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * ��ת���������������嵥ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���������嵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴���������嵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	/**
	 *
	 * ��ȡ�����嵥editgrid����
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
	 * ��ȡ�����嵥subgrid����
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
	 * ��ȡ�������ݷ���json�������˺�ͬ�����嵥��
	 * (����������������ִ������)
	 */
	function c_contractListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_contract');
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��дlistJson(���������������;����)
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['inventory'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //��;����
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
//		echo "<pre>";
//		print_r($rows);
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��������id����������id����´���������
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
	 * ��ȡ���ϻ�����������ת��Json
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.productId';
		$rows = $service->page_d('select_product');
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach ($rows as $key => $val) {
				$rows[$key]['inventory'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��������ѡ��Json
	 */
	function c_productJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_product');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ������������ת��Json
	 */
	function c_mainPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = ' c.id ';
		$rows = $service->page_d('select_main');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		// if (is_array($rows)) {
		// 	$taskDao = new model_produce_task_producetask();
		// 	$taskArr = array();
		// 	foreach ($rows as $key => $val) {
		// 		if ($val["docStatus"] == 1 || $val["docStatus"] == 2) { //��������Ϊ�����´��ȫ���´�
		// 			if (empty($taskArr[$val["id"]])) {
		// 				$taskObj = $taskDao->find("applyDocId=$val[mainId] AND productId=$val[productId] AND docStatus IN(0,1,2)" ,' id DESC ');
		// 				$taskArr[$val["id"]] = $taskObj;
		// 			} else {
		// 				$taskObj = $taskArr[$val["id"]];
		// 			}
		// 			if ($taskObj["id"]) {
		// 				if ($taskObj["docStatus"] == 0) {
		// 					$rows[$key]["taskId"] = -1; //δ��������
		// 				} else if ($taskObj["docStatus"] == 1) {
		// 					$rows[$key]["taskId"] = 0; //�ѽ�������
		// 				} else {
		// 					$rows[$key]["taskId"] = $taskObj["id"]; //���ƶ��ƻ�
		// 				}
		// 			} else {
		// 				$rows[$key]["taskId"] = -2; //δ�´�����
		// 			}
		// 		} else {
		// 			$rows[$key]["taskId"] = -3;
		// 		}
		// 	}
		// }
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �´���������ʱ��ȡ�������ݷ���json
	 */
	function c_listJsonByTask() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listJsonByTask_d ( $_POST );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}