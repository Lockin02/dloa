<?php
/**
 * @author Bingo
 * @Date 2015年5月11日 
 * @version 1.0
 * @description:生产退料申请单清单控制层
 */
class controller_produce_plan_backitem extends controller_base_action {

	function __construct() {
		$this->objName = "backitem";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }
	 
	 /**
	  * 重写listJson
	  */
	 function c_listJson() {
	 	$service = $this->service;
	 	$service->getParam ( $_REQUEST );
	 	$rows = $service->list_d ();
	 	if (is_array($rows)) {
	 		$pickDao = new model_produce_plan_picking();
	 		foreach ($rows as $key => $val) {
	 			$numArr = $pickDao->getProductNum_d($val['productCode']);
	 			$rows[$key]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
	 			$rows[$key]['KCSP'] = $numArr['KCSP']; //库存商品仓数量
	 			$rows[$key]['SCC']  = $numArr['SCC'];  //生产仓数量
	 		}
	 	}
	 	if(isset($_REQUEST['pickingId']) && $_REQUEST['type'] == 'edit'){//编辑领料下推的退料申请单时处理
	 		$rows = $service->dataProcessAtEdit_d ($rows,$_REQUEST['pickingId']);
	 	}
	 	//数据加入安全码
	 	$rows = $this->sconfig->md5Rows ( $rows );
	 	echo util_jsonUtil::encode ( $rows );
	 }
 }