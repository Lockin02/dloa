<?php

/**
 * @author huangzf
 * @Date 2011年5月9日 22:10:00
 * @version 1.0
 * @description:入库单基本信息控制层 1.入库单类型：A.有采购入库B.产品入库C.其它入库
 * 2.单据状态：  未审核、已审核
 */
class controller_stock_instock_stockin extends controller_base_action
{

	function __construct() {
		$this->objName = "stockin";
		$this->objPath = "stock_instock";
		parent::__construct();
	}

	/**
	 * 跳转到列表页
	 */
	function c_toList() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		if ($this->service->this_limit['仓存入库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}

		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-list");
				break; //外购入库
			case 'RKPRODUCT' :
				$this->view("product-list");
				break; //产品入库
			case 'RKOTHER' :
				$this->view("other-list");
				break; //其他入库
			case 'RKPRODUCEBACK' :
				$this->view("produceback-list");
				break; //退料入库
            case 'RKDLBF' :
                $this->view("idleStockScrap-list");
                break; //呆料报废入库
			default :
				break;
		}
	}

	/**
	 * 跳转到生产入库单列表页
	 */
	function c_pageByProduce() {
		if ($this->service->this_limit['仓存入库导入']) {
			$this->assign ( "importLimit", "1" );
		} else {
			$this->assign ( "importLimit", "0" );
		}
		$this->view('product-produce-list');
	}

	/**
	 * 跳转到换货单据查看页面-其他入库
	 */
	function c_toListForExchange() {
		if ($this->service->this_limit['仓存入库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->assign('exchangeId', $_GET['id']);
		$this->view("other-listforexchange");
	}

	/**
	 * 跳转到列表页
	 */
	function c_toBusinessList() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-business-list");
				break; //外购入库
			case 'RKPRODUCT' :
				$this->view("product-business-list");
				break; //产品入库
			case 'RKOTHER' :
				$this->view("other-business-list");
				break; //其他入库
			default :
				break;
		}
	}

	/**
	 * 跳转处理入库单关联采购订单列表页
	 */
	function c_toUnionList() {
		$this->view("purchase-union-list");
	}

	/**
	 * 跳转处理入库单关联采购订单具体页面
	 */
	function c_toUnionOrderForm() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($_GET['id'], new $stockinStrategy());
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemView($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->view("purchase-unionorder");
	}

	/**
	 * 跳转处理入库单关联收料通知单具体页面
	 */
	function c_toUnionArrivalForm() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($_GET['id'], new $stockinStrategy());
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemView($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->view("purchase-unionarrival");
	}

	/**
	 *
	 * 入库单 采购订单关联
	 */
	function c_unionOrder() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		if ($service->unionOrder($stockinObject)) {
			echo "<script>alert('关联订单成功!'); window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('关联订单失败!');window.opener.window.show_page();window.close();</script>";
		}

		//echo "<script>alert('关联订单成功!');window.close();</script>";
	}

	/**
	 *
	 * 入库单 收料通知单关联
	 */
	function c_unionArrival() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		if ($service->unionArrival($stockinObject)) {
			echo "<script>alert('关联收料通知单成功!'); window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('关联收料通知单失败!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 跳转到新增入库单页面
	 * @author huangzf
	 */
	function c_toAdd() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('auditDate', day_date);
		//审核权限判断
		$this->checkAuditLimit($docType);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->showDatadicts(array('purchMethod' => 'cgfs'));
				$this->showDatadicts(array('accountingCode' => 'KJKM'));
				$this->showDatadicts(array('relDocType' => 'RKDYDLX'), null, true);
				$this->view("purchase-add");
				break;
			case 'RKPRODUCT' :
				$this->setProInStockMail();
				$this->showDatadicts(array('relDocType' => 'RKDYDLX2'), null, true);
				$this->view("product-add");
				break;
			case 'RKOTHER' :
				$this->showDatadicts(array('relDocType' => 'RKDYDLX3'), null, true);
				$this->view("other-add");
				break;
			default :
				break;
		}
	}

	/**
	 * 跳转到下推红色入库单页面
	 * @author huangzf
	 */
	function c_toAddRed() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemAddRed($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
        $this->assign('orgId', $id);
		$this->assign('auditDate', day_date);
		$this->show->assign("itemscount", count($stockinObject['items']));
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($stockinObject['relDocType']));
		//审核权限判断
		$this->checkAuditLimit($docType);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->showDatadicts(array('purchMethod' => 'cgfs'), $stockinObject['purchMethod']);
				$this->showDatadicts(array('accountingCode' => 'KJKM'), $stockinObject['accountingCode']);
				$this->view("purchase-red");
				break;
			case 'RKPRODUCT' :
				$this->view("product-red");
				break;
			case 'RKOTHER' :
				$this->view("other-red");
				break;
            case 'RKDLBF' :
                $this->view("idleStock-red");
                break;
			case 'RKPRODUCEBACK' :
				$this->view("produceback-red");
				break;
		    case 'RXSTH' :
				$this->view("other-red");
				break;
			default :
				break;
		}
	}

	/**
	 *
	 * 入库任务下推蓝色入库单
	 * @author huangzf
	 */
	function c_toBluePush() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		//echo $docType;
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->checkAuditLimit("RKPURCHASE");
				$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
				$arrivalDao = new model_purchase_arrival_arrival();
				$arrivalObj = $arrivalDao->get_d($_GET['relDocId']);
				$arrivalEqupDao = new model_purchase_arrival_equipment();
				$eqpArr = $arrivalEqupDao->getItemByBasicIdUnstock_d($_GET['relDocId']);
				//
				$this->assign("relDocId", $arrivalObj['id']);
				$this->assign("purOrderId", $arrivalObj['id']);
				$this->assign("relDocName", "");
				$this->assign("relDocCode", $arrivalObj['arrivalCode']);
				$this->assign("purOrderCode", $arrivalObj['purchaseCode']);
				$this->assign("purOrderId", $arrivalObj['purchaseId']);
				$this->assign("relDocType", $relDocType);
				$this->assign("docType", $docType);
				$this->assign("supplierId", $arrivalObj['supplierId']);
				$this->assign("supplierName", $arrivalObj['supplierName']);
				$this->assign("purchaserName", $arrivalObj['purchManName']);
				$this->assign("purchaserCode", $arrivalObj['purchManId']);
				$this->assign("inStockId", $arrivalObj['stockId']);
				$this->assign("inStockName", $arrivalObj['stockName']);
				$this->assign("purchMethod", $arrivalObj['purchMode']);
				$this->assign("auditDate", day_date);
				$this->assign("itemscount", count($eqpArr));
				$this->showDatadicts(array('purchMethod' => 'cgfs'), $arrivalObj['purchMode']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->showDatadicts(array('accountingCode' => 'KJKM'));
				$this->assign("stockinItems", $arrivalEqupDao->showAddList($eqpArr));
				$this->view("purchase-blue-push");

				break;
			case 'RKPRODUCT' :
				$this->checkAuditLimit("RKPRODUCT");
				$planDao = new model_produce_plan_produceplan();
				$planObj = $planDao->get_d($_GET['docId']);
				$this->assign("docId" ,$_GET['docId']);
				$this->assign("relDocId" ,$_GET['relDocId']);
				$this->assign("relDocCode" ,$_GET['relDocCode']);
				$this->assign("rObjCode" ,$_GET['rObjCode']);
				$this->assign("contractCode" ,$planObj['relDocCode']);
				$this->assign("contractId" ,$planObj['relDocId']);
				$this->assign("contractType" ,$planObj['relDocTypeCode']);
				$this->assign("contractName" ,$planObj['relDocName']);
				$this->assign("contractObjCode" ,$planObj['objCode']);
				$this->assign("clientName" ,$planObj['customerName']);
				$this->assign("clientId" ,$planObj['customerId']);
				$this->assign("auditDate" ,day_date);
				$this->assign("relDocType" ,$relDocType);
				$this->assign("relDocTypeName" ,$this->getDataNameByCode($relDocType));
				$this->assign("purchaserName" ,$_SESSION['DEPT_NAME']);
				$this->assign("purchaserCode" ,$_SESSION['DEPT_ID']);
				$this->assign("docType" ,$docType);
				$noticeequpDao = new model_stock_withdraw_noticeequ();
				$eqpArr = $noticeequpDao->getItemByBasicIdUnstock_d($_GET['relDocId']);
				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$eqpArr = $productinfoDao->k3CodeFormatter_d($eqpArr);
				$this->assign("itemscount", count($eqpArr));
				$this->assign("stockinItems", $noticeequpDao->showAddList($eqpArr));
				$this->view("product-blue-push" ,true);
				break;
			case 'RKOTHER' :
				// ID2295 要求补的一个默认字段
				$this->assign("purchMethodStr", 'yes');
				$this->assign("purchMethod", '其他入库');

				$this->checkAuditLimit("RKPURCHASE");
				$this->assign("relDocId", $_GET['relDocId']);
				$this->assign("relDocCode", $_GET['relDocCode']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($_GET['relDocType']));
				$this->assign("relDocType", $_GET['relDocType']);
				$this->assign("relDocName", "");
				$this->assign("docType", $_GET['docType']);

				$noticeequpDao = new model_stock_withdraw_noticeequ();
				$eqpArr = $noticeequpDao->getItemByBasicIdUnstock_d($_GET['relDocId']);
				// k3编码加载处理
                $productinfoDao = new model_stock_productinfo_productinfo();
                $eqpArr = $productinfoDao->k3CodeFormatter_d($eqpArr);
				$this->assign("itemscount", count($eqpArr));
				$this->assign("stockinItems", $noticeequpDao->showAddList($eqpArr));

				$this->assign("auditDate", day_date);
				$this->view("other-blue-push");
				break;
			case 'RKPRODUCEBACK' :
				$this->checkAuditLimit("RKPRODUCEBACK");
				$this->assign("relDocId" ,$_GET['relDocId']);
				$this->assign("relDocCode" ,$_GET['relDocCode']);
				$this->assign("auditDate" ,day_date);
				$this->assign("docType" ,$docType);
				$this->view("produceback-blue-push" ,true);
				break;
			case 'RXSTH' :
				$this->checkAuditLimit("RKPURCHASE");
				$this->assign("relDocId", $_GET['relDocId']);
				$this->assign("relDocCode", $_GET['relDocCode']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($_GET['relDocType']));
				$this->assign("relDocType", $_GET['relDocType']);
				$this->assign("relDocName", "");
				$this->assign("docType", $_GET['docType']);

				$noticeequpDao = new model_projectmanagent_return_returnequ();
				$eqpArr = $noticeequpDao->getDetailbyOther_d($_GET['relDocId']);
				$this->assign("itemscount", count($eqpArr));
				$this->assign("stockinItems", $noticeequpDao->showAddList($eqpArr));

				$this->assign("auditDate", day_date);
				$this->view("saleReturn-blue-push");
				break;
			default :
				break;
		}
	}

	/**
	 * 资产入库-下推蓝色入库单
	 */
	function c_toAddBlueByAsset() {
		$id = $_GET['id'];    //申请单id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//获取申请单信息并初始化
		$requireoutDao = new model_asset_require_requireout();
		$obj = $requireoutDao->get_d($id);
		$this->assign('relDocCode', $obj['requireCode']);
		//获取明细数量
		$itemDao = new model_asset_require_requireoutitem();
		$item = $itemDao->getDetail_d($id);
		$this->assign('itemscount', count($item));
		//审核权限判断
		$this->checkAuditLimit("RKOTHER");
		$this->view("other-assetblue");
	}

	/**
	 * 资产入库-下推蓝色入库单
	 */
	function c_toAddBlueByAssetNew() {
		$id = $_GET['id'];    //申请单id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);

		// 获取资产验收单的内容
		$result = util_curlUtil::getDataFromAWS('asset', 'getAssetTransferInfo', array(
			"assetTransferId" => $id
		));
		$assetTransferInfo = util_jsonUtil::decode($result['data'], true);

		// 主表赋值处理
		if ($assetTransferInfo['data']['assetTransferInfo']) {
			$this->assign('relDocCode', $assetTransferInfo['data']['assetTransferInfo']['applyNo']);
		}

		$str = "";
		// 子表赋值处理
		if ($assetTransferInfo['data']['details']) {
			$productIds = array();

			foreach ($assetTransferInfo['data']['details'] as $v) {
				$productIds[] = $v['productId'];
			}

			// 期初获取
			$periodDao = new model_finance_period_period();
			$periodObj = $periodDao->rtThisPeriod_d();

			// 以上期最新出库价格更新
			$outstockDao = new model_stock_outstock_stockout();
			$priceArr = $outstockDao->getLastOutPrice_d($periodObj['thisYear'], $periodObj['thisMonth'], $productIds);

			foreach ($assetTransferInfo['data']['details'] as $k => $v) {
				$price = $priceArr[$v['productId']];
				if($price && $price != 0) {
					$assetTransferInfo['data']['details'][$k]['price'] = $price;
					$assetTransferInfo['data']['details'][$k]['subPrice'] =
						abs(round(bcmul($price, $v['applyNum'] - $v['inStorageNum'] ,6), 2));
				}
			}

			$requireoutitemDao = new model_asset_require_requireoutitem();
			$str = $requireoutitemDao->showProAtStockInNew_d($assetTransferInfo['data']['details']);
		}

		$this->assign('itemscount', count($assetTransferInfo['data']['details']));
		$this->assign('itemsbody', $str);

		//审核权限判断
		$this->checkAuditLimit("RKOTHER");
		$this->view("other-assetbluenew");
	}

	/**
	 *
	 * 入库任务下推9月份前入库单
	 * @author huangzf
	 */
	function c_toPreSepPush() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		//echo $docType;
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->checkAuditLimit("RKPURCHASE");
				$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
				$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
				$service = $this->service;
				$arrivalDao = new model_purchase_arrival_arrival();
				$arrivalObj = $arrivalDao->get_d($_GET['relDocId']);
				$arrivalEqupDao = new model_purchase_arrival_equipment();
				$eqpArr = $arrivalEqupDao->getItemByBasicIdId_d($_GET['relDocId']);
				//
				$this->assign("relDocId", $arrivalObj['id']);
				$this->assign("purOrderId", $arrivalObj['id']);
				$this->assign("relDocName", "");
				$this->assign("relDocCode", $arrivalObj['arrivalCode']);
				$this->assign("purOrderCode", $arrivalObj['purchaseCode']);
				$this->assign("purOrderId", $arrivalObj['purchaseId']);
				$this->assign("relDocType", $relDocType);
				$this->assign("docType", $docType);
				$this->assign("supplierId", $arrivalObj['supplierId']);
				$this->assign("supplierName", $arrivalObj['supplierName']);
				$this->assign("purchaserName", $arrivalObj['purchManName']);
				$this->assign("purchaserCode", $arrivalObj['purchManId']);
				$this->assign("inStockId", $arrivalObj['stockId']);
				$this->assign("inStockName", $arrivalObj['stockName']);
				$this->assign("purchMethod", $arrivalObj['purchMode']);
				$this->assign("auditDate", day_date);
				$this->assign("itemscount", count($eqpArr));
				$this->showDatadicts(array('purchMethod' => 'cgfs'), $arrivalObj['purchMode']);
				$this->show->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->showDatadicts(array('accountingCode' => 'KJKM'));
				$this->show->assign("stockinItems", $arrivalEqupDao->showAddList($eqpArr));
				$this->view("purchase-september-push");

				break;
			case 'RKPRODUCT' :
				$this->view("product-blue-push");
				break;
			case 'RKOTHER' :
				$this->view("other-blue-push");
				break;
			default :
				break;
		}
	}

	/**
	 *
	 * 从采购订单下推外购入库单
	 */
	function c_toPush() {
		$this->permCheck($_GET['orderId'], 'purchase_contract_purchasecontract'); //安全校验
		//审核权限判断
		$this->checkAuditLimit("RKPURCHASE");
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$purConDao = new model_purchase_contract_purchasecontract();
		$purConObj = $purConDao->get_d($_GET['orderId']);
		$purConEqupDao = new model_purchase_contract_equipment();
		$eqpArr = $purConEqupDao->getEqusByContractId($_GET['orderId']);
		$this->assign("relDocId", $purConObj['id']);
		$this->assign("purOrderId", $purConObj['id']);
		$this->assign("relDocName", "");
		$this->assign("relDocCode", $purConObj['hwapplyNumb']);
		$this->assign("purOrderCode", $purConObj['hwapplyNumb']);
		$this->assign("relDocType", $relDocType);
		$this->assign("docType", $docType);
		$this->assign("supplierId", $purConObj['suppId']);
		$this->assign("supplierName", $purConObj['suppName']);
		$this->assign("purchaserName", $purConObj['sendName']);
		$this->assign("purchaserCode", $purConObj['sendUserId']);
		$this->assign("auditDate", day_date);
		$this->assign("itemscount", count($eqpArr));
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
		$this->showDatadicts(array('purchMethod' => 'cgfs'));
		$this->showDatadicts(array('accountingCode' => 'KJKM'));

		$this->show->assign("stockinItems", $purConEqupDao->showAddList($eqpArr));
		$this->view("purchase-push");
	}

	/**
	 * 跳转到修改入库单页面
	 * @author huangzf
	 */
	function c_toEdit() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemEdit($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("itemscount", count($stockinObject['items']));
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($stockinObject['relDocType']));
		//审核权限判断
		$this->checkAuditLimit($docType);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->showDatadicts(array('purchMethod' => 'cgfs'), $stockinObject['purchMethod']);
				$this->showDatadicts(array('accountingCode' => 'KJKM'), $stockinObject['accountingCode']);
				$this->view("purchase-edit");
				break;
			case 'RKPRODUCT' :
				$this->setProInStockMail();
				$this->view("product-edit");
				break;
			case 'RKOTHER' :
				$this->view("other-edit");
				break;
            case 'RKDLBF' :
                $this->view("idleStock-edit");
                break;
			case 'RKPRODUCEBACK' :
				$this->setProInStockMail();
				$this->view("produceback-edit");
				break;
			case 'RXSTH' :
				$this->setProInStockMail();
				$this->view("saleother-edit");
				break;
			default :
				break;
		}
	}

	/**
	 * 跳转到查看入库单页面
	 */
	function c_toView() {
		//		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
        $moneyLimit = $service->this_limit['入库金额'];
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('入库金额', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
        }
		if($docType=='RKOTHER'){
			if($stockinObject['relDocType']=='RHHRK'){
				$stockinObject['relDocCode'] = "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=stock_withdraw_innotice&action=toView&id=" . $stockinObject['relDocId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850","",'. $stockinObject['relDocId'].')\'>' . $stockinObject['relDocCode'] ."</a>";
			}else if($stockinObject['relDocType']=='RZCRK'){
				$stockinObject['relDocCode'] = "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requireout&action=toView&id=" . $stockinObject['relDocId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850","",'. $stockinObject['relDocId'].')\'>' . $stockinObject['relDocCode'] ."</a>";
			}
		}
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemView($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("relDocType", $this->getDataNameByCode($stockinObject['relDocType']));
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->show->assign("purchMethod", $this->getDataNameByCode($stockinObject['purchMethod']));
				$this->show->assign("accountingCode", $this->getDataNameByCode($stockinObject['accountingCode']));
				$this->view("purchase-view");
				break;
			case 'RKPRODUCT' :
				$this->view("product-view");
				break;
			case 'RKOTHER' :
				$this->view("other-view");
				break;
            case 'RKDLBF' :
                $this->view("idleStock-view");
                break;
			case 'RKPRODUCEBACK' :
				$this->view("produceback-view");
				break;
			default :
				break;
		}
	}

	/**
	 * 通过单据编号查看入库单信息
	 */
	function c_viewByDocCode() {
		$docCode = isset($_GET['docCode']) ? $_GET['docCode'] : null;
		$service = $this->service;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->findByDocCode($docCode, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('入库金额', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
        }
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemView($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("relDocType", $this->getDataNameByCode($stockinObject['relDocType']));
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->show->assign("purchMethod", $this->getDataNameByCode($stockinObject['purchMethod']));
				$this->show->assign("accountingCode", $this->getDataNameByCode($stockinObject['accountingCode']));
				$this->view("purchase-view");
				break;
			case 'RKPRODUCT' :
				$this->view("product-view");
				break;
			case 'RKOTHER' :
				$this->view("other-view");
				break;
			default :
				break;
		}
	}

	/**
	 *高级搜索页面
	 */
	function c_toAdvancedSearch() {
		$this->assign("docType", $_GET['docType']);
		$this->showDatadicts(array('catchStatus' => 'CGFPZT'), null, true);
		$this->view("search-advanced");
	}

	/**
	 * 新增入库单
	 * @author huangzf
	 */
	function c_add() {
		try {
			$service = $this->service;
			$stockinObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------审核权限控制,并设置审核人----------------*/
			if ("audit" == $actType) {
				if ($stockinObject['isRed'] == 1) {
					if ($stockinObject['docType'] == "RKPURCHASE") {
						if (!$service->this_limit['外购入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCT") {
						if (!$service->this_limit['产品入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKOTHER") {
						if (!$service->this_limit['其他入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCEBACK") {
						if (!$service->this_limit['退料入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
				} else {
					if ($stockinObject['docType'] == "RKOTHER" && $stockinObject['relDocType'] == 'RHHRK') {
						if (!$service->this_limit['其他入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					} else {
						if ($stockinObject['docType'] == "RKPURCHASE" && $stockinObject['relDocType'] == 'RSLTZD') {
							$checkResult = $this->service->checkAudit_d($stockinObject);//检查入库数和收料单物料数是否填写错误
							if ($checkResult == 1) {
								if (!$service->this_limit['外购入库审核']) {
									echo "<script>alert('没有权限进行审核!');window.close();</script>";
									exit();
								}
							} else if ($checkResult == 2) {
								echo "<script>alert('填写的入库数不能大于质检合格数!');window.close();</script>";
								exit();
							} else {
								echo "<script>alert('收货数量不能大于订单数量!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKPRODUCT") {
							if (!$service->this_limit['产品入库审核']) {
								echo "<script>alert('没有权限进行审核!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKOTHER") {
							if (!$service->this_limit['其他入库审核']) {
								echo "<script>alert('没有权限进行审核!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKPRODUCEBACK") {
							if (!$service->this_limit['退料入库审核']) {
								echo "<script>alert('没有权限进行审核!');window.close();</script>";
								exit();
							}
						}
					}
				}
				$stockinObject['auditerName'] = $_SESSION['USERNAME'];
				$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制,并设置审核人----------------*/
			$id = $service->add_d($stockinObject);

			if ($id) {
				if ("audit" == $actType) {
					$addObj = $service->find(array("id" => $id), null, 'docCode');
					//审核，更新即时库存
					if ($stockinObject['items']) {
						if ($stockinObject['isRed'] == 1) {
							$state = true;
						} else {
							$state = false;
						}
						if ($stockinObject['relDocType'] == 'RSLTZD' && $stockinObject['isRed'] == 1) {
							$conditions['arrivalId'] = $stockinObject['relDocId'];
							foreach ($stockinObject['items'] as $key => $val) {
								if($val['isDelTag'] == '1'){
									continue;
								}
								$conditions['id'] = $val['relDocId'];
								$this->service->updateArrivalNum_d($conditions, $val, $state);
							}
						}
						if ($stockinObject['relDocType'] == 'RTLTZD') {
							$arrivalId = $this->service->get_table_fields('oa_purchase_delivered', "id='" . $stockinObject['relDocId'] . "'", 'sourceId');
							$conditions['arrivalId'] = $arrivalId;
							foreach ($stockinObject['items'] as $key => $val) {
								$conditions['productId'] = $val['productId'];
								$this->service->updateArrivalNum_d($conditions, $val, $state);
							}
						}
					}
					echo "<script>alert('审核入库单成功!单据编号为:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('新增入库单成功!'); window.opener.window.show_page();window.close();  </script>";
				}
			} else {
				if ("audit" == $actType) {
					echo "<script>alert('审核入库失败,请确认仓库是否初始化该物料!'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('新增入库单失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
				}
			}
		} catch (Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}
function c_test(){
    $applyId = $_REQUEST['applyId'];
    $qualityapplyDao = new model_produce_quality_qualityapply();
    $relClass = $qualityapplyDao->getStrategy_d($applyId);
    $relClassM = new $relClass(); //策略实例
    $applyObj = $qualityapplyDao->get_d($applyId);
    $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
}
	/**
	 * 新增9月份入库单
	 * @author huangzf
	 */
	function c_addPre() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
		$docType = $stockinObject['docType'];

		/*s:--------------审核权限控制,并设置审核人----------------*/
		if ("audit" == $actType) {
			if ($stockinObject['docType'] == "RKPURCHASE") {
				if (!$service->this_limit['外购入库审核']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit();
				}
			}
			$stockinObject['auditerName'] = $_SESSION['USERNAME'];
			$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
		}
		/*e:--------------审核权限控制,并设置审核人----------------*/

		$id = $service->addPre_d($stockinObject);

		if ($id) {
			if ("audit" == $actType) {
				$addObj = $service->find(array("id" => $id), null, 'docCode');
				echo "<script>alert('审核入库单成功!单据编号为:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('新增入库单成功!'); window.opener.window.show_page();window.close();  </script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审核入库失败,请确认仓库是否初始化该物料!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('新增入库单失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
			}
		}

	}

	/**
	 * 修改入库单
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$stockinObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;

			/*s:--------------审核权限控制,并设置审核人----------------*/
			if ("audit" == $actType) {
				$checkResult = $service->checkAudit_d($stockinObject);//检查入库数和收料单物料数是否填写错误
				if ($checkResult == 1) {
					if ($stockinObject['docType'] == "RKPURCHASE") {
						if (!$service->this_limit['外购入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCT") {
						if (!$service->this_limit['产品入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKOTHER") {
						if (!$service->this_limit['其他入库审核']) {
							echo "<script>alert('没有权限进行审核!');window.close();</script>";
							exit();
						}
					}
				} elseif ($checkResult == 4) {//用于资产入库,产品入库验证
					echo "<script>alert('入库数量大于申请数量，请核实!');window.close();</script>";
					exit();
				}
				$stockinObject['auditerName'] = $_SESSION['USERNAME'];
				$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制,并设置审核人----------------*/
			//防止单据重复审核
			$lastObj = $service->find(array("id" => $stockinObject['id']));
			if ($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('单据已经审核,不可修改,请刷新列表!');window.close();</script>";
				exit();
			}

			if($stockinObject['docType'] == 'RKDLBF' && $stockinObject['isRed'] == 1){// 待报废呆料红单编辑处理
                // 如果该呆料报废入库单关联的出库单已提交审批,则无法审核红单
                $ItemsArr = $service->getRelativeItemsCount($stockinObject['relDocId'],"'YSH','SPZ'");

                if(is_array($ItemsArr) && count($ItemsArr) > 0){
                    $hasEmptyEquNum = 0;// 物料抵消数量最后为0的个数
                    foreach ($ItemsArr as $k => $v){
                        $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    }
                    if($hasEmptyEquNum > 0){
                        echo "<script>alert('物料数量有误,请核对所有关联出入库单的红蓝单数量,包括审批中的出库单!');window.close();</script>";
                        exit();
                    }
                }
            }

			if ($service->edit_d($stockinObject)) {
				if ("audit" == $actType) {
					//审核，更新即时库存
					if ($stockinObject['items']) {
						if ($stockinObject['isRed'] == 1) {
							$state = true;
						} else {
							$state = false;
						}
						if ($stockinObject['relDocType'] == 'RSLTZD' && $stockinObject['isRed'] == 1) {
							$service->get_table_fields('oa_purchase_arrival_info', "id='" . $stockinObject['relDocId'] . "'", 'purchaseId');
							$conditions['arrivalId'] = $stockinObject['relDocId'];
							foreach ($stockinObject['items'] as $key => $val) {
								if($val['isDelTag'] == '1'){
									continue;
								}
								$conditions['id'] = $val['relDocId'];
								$service->updateArrivalNum_d($conditions, $val, $state);
							}
						} else if ($stockinObject['relDocType'] == 'RTLTZD') {
							$arrivalId = $service->get_table_fields('oa_purchase_delivered', "id='" . $stockinObject['relDocId'] . "'", 'sourceId');
							$conditions['arrivalId'] = $arrivalId;
							foreach ($stockinObject['items'] as $key => $val) {
								$conditions['productId'] = $val['productId'];
								$service->updateArrivalNum_d($conditions, $val, $state);
							}
						}
					}
					echo "<script>alert('审核入库单成功!单据编号为:" . $lastObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('修改入库单成功!'); window.opener.window.show_page();window.close();  </script>";
				}
			}
		} catch (Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 入库单反审核
	 */
	function c_cancelAudit() {
		try {
			$service = $this->service;
			$id = isset($_POST['id']) ? $_POST['id'] : null;
			$docType = isset($_POST['docType']) ? $_POST['docType'] : null;
			$stockinStrategy = $service->stockinStrategyArr[$docType];

			if ($service->ctCancelAudit($id, new $stockinStrategy())) {
				echo 1;
			}
		} catch (Exception $e) {
			echo "操作失败!异常:" . $e->getMessage();
		}
	}

	/**
	 * 根据蓝色入库单下推红色入库单模板
	 */
	function c_showRelItem() {
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$docType = isset($_POST['docType']) ? $_POST['docType'] : null;
		$service = $this->service;
		$itemDao = new model_stock_instock_stockinitem();
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$itemObjs = $itemDao->getItemByMainId($id);
		$itemStr = $this->service->showRelItem($itemObjs, new $stockinStrategy());
		echo $itemStr;
	}

	/**
	 * 入库单下推出库单显示模板
	 */
	function c_showItemAtOutStock() {
		$id = isset($_POST['id']) ? $_POST['id'] : null;
		$docType = isset($_POST['docType']) ? $_POST['docType'] : null;
		$service = $this->service;
		$itemDao = new model_stock_instock_stockinitem();
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$itemObjs = $itemDao->getItemByMainId($id);
		$itemStr = $this->service->showItemAtOutStock($itemObjs, new $stockinStrategy());
		echo $itemStr;
	}

	/**
	 * 跳转到打印入库单页面
	 */
	function c_toPrint() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('入库金额', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
        }
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				if($stockinObject['isRed'] == '1'){
					$this->show->assign("stockinItems", $service->showRedItemPrint($stockinObject['items'], new $stockinStrategy()));
				}else{
					$this->show->assign("stockinItems", $service->showItemPrint($stockinObject['items'], new $stockinStrategy()));
				}
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("relDocType", $this->getDataNameByCode($stockinObject['relDocType']));
        $this->show->assign("year",date("Y",strtotime($stockinObject['auditDate'])));
        $this->show->assign("sortyear",date("y",strtotime($stockinObject['auditDate'])));
        $this->show->assign("month",date("m",strtotime($stockinObject['auditDate'])));
        $this->show->assign("day",date("d",strtotime($stockinObject['auditDate'])));
        $this->show->assign("itemCount",count($stockinObject['items']));
        $this->show->assign("photoPath",str_replace('\\','/',WEB_TOR));
        $this->show->assign("stockMan",$_SESSION['USERNAME']);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->show->assign("purchMethod", $this->getDataNameByCode($stockinObject['purchMethod']));
				$this->show->assign("accountingCode", $this->getDataNameByCode($stockinObject['accountingCode']));
				$this->view("purchase-print");
				break;
			case 'RKPRODUCT' :
				$this->view("product-print");
				break;
			case 'RKOTHER' :
				//获取源单信息
				$rs = $service->getRelInfo(array('relDocId' => $stockinObject['relDocId'],
					'relDocType' => $stockinObject['relDocType']), new $stockinStrategy());
				$applyName = $applyDeptName = '';
				if($rs){
					if($stockinObject['relDocType'] == 'RHHRK'){//换货
						//入库通知单获取换货单信息
						$exchangeDao = new model_projectmanagent_exchange_exchange();
						$rs = $exchangeDao->find(array('id' => $rs['docId']) ,null,'contractId');
						//获取合同负责人信息
						$contractDao = new model_contract_contract_contract();
						$rs = $contractDao->find(array('id' => $rs['contractId']) ,null,'prinvipalName,prinvipalDept');
						$applyName = $rs['prinvipalName'];
						$applyDeptName = $rs['prinvipalDept'];
					}else if($stockinObject['relDocType'] == 'RXSTH'){//退货
						//获取合同负责人信息
						$contractDao = new model_contract_contract_contract();
						$rs = $contractDao->find(array('id' => $rs['contractId']) ,null,'prinvipalName,prinvipalDept');
						$applyName = $rs['prinvipalName'];
						$applyDeptName = $rs['prinvipalDept'];
					}else if($stockinObject['relDocType'] == 'RZCRK'){//资产入库
						$applyName = $rs['applyName'];
						$applyDeptName = $rs['applyDeptName'];
					}
				}
				$this->assign("applyName", $applyName);
				$this->assign("applyDeptName", $applyDeptName);
				$this->view("other-print");
				break;
            case 'RKDLBF' :
                $this->view("idleStock-print");
                break;
			default :
				break;
		}
	}

	/**
	 * 获取入库单列表页面数据Json
	 */
	function c_pageListGridJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d("select_listgrid");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
        $_SESSION['pageListGridSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 获取根据采购员名称过滤入库单数据Json
	 */
	function c_pageBusinessListJson() {
		$service = $this->service;
		if ($_REQUEST['dealStatus'] == 'done') {
			$_REQUEST['done'] = 1;
		} else if ($_REQUEST['dealStatus'] == 'undo') {
			$_REQUEST['undo'] = 1;
		}
		$service->getParam($_REQUEST);
		$service->searchArr['purchaserCode'] = $_SESSION['USER_ID'];
		$rows = $service->page_d("select_purchgrid");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * 判断一段时间内是否有未审核单据
	 */
	function c_isExsitWsh() {
		$pYear = isset($_POST['year']) ? $_POST['year'] : null;
		$pMonth = isset($_POST['month']) ? $_POST['month'] : null;
		$service = $this->service;
		$totalDays = cal_days_in_month(CAL_GREGORIAN, $pMonth, $pYear); // 2010年1月份的天数
		$service->searchArr = array("dAuditDate" => "$pYear-$pMonth-01", "xAuditDate" => "$pYear-$pMonth-$totalDays", 'docStatus' => "WSH");
		$resultObj = $service->listBySqlId();
		if (is_array($resultObj)) {
			echo 0; //存在审核单据
		} else {
			echo 1; //不存在未审核单据
		}
	}

	/********************************核算部分**********************************/

	/**
	 * 跳转到列表页
	 */
	function c_toCalList() {
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('docType', $docType);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-listcal");
				break; //外购入库
			case 'RKPRODUCT' :
				$this->view("product-list");
				break; //产品入库
			case 'RKOTHER' :
				$this->view("other-listcal");
				break; //其他入库
			default :
				break;
		}
	}

	/**
	 * 核算列表
	 */
	function c_calPageJson() {
		$service = $this->service;
		if($_POST['beginDate'] == '--01'){
			unset($_POST['beginDate']);
		}
		if($_POST['endDate'] == '--31'){
			unset($_POST['endDate']);
		}
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->list_d('select_callist');
		if ($rows && $_POST['ifshow'] == 0) {
			$rows = $service->filterRows_d($rows);
		}
		$rows = $this->sconfig->md5Rows($rows, 'mainId');
		// 合计处理
		if ($rows) {
			$sum = array('id' => 'nocheck', 'actNum' => 0, 'subPrice' => '0', 'auditDate' => '合计');
			foreach ($rows as $v) {
				$sum['actNum'] = bcadd($v['actNum'], $sum['actNum']);
				$sum['subPrice'] = bcadd($v['subPrice'], $sum['subPrice'], 2);
			}
			$rows[] = $sum;
		}
		$arr = array();
		$arr['collection'] = $rows;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 修改入库金额
	 */
	function c_toEditPrice() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
		foreach ($stockinObject as $key => $val) {
			if ($key == 'items') {
				$this->show->assign("stockinItems", $service->showItemEditPrice($stockinObject['items'], new $stockinStrategy()));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("itemscount", count($stockinObject['items']));
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($stockinObject['relDocType']));
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->show->assign("purchMethod", $this->getDataNameByCode($stockinObject['purchMethod']));
				$this->show->assign("accountingCode", $this->getDataNameByCode($stockinObject['accountingCode']));
				$this->view("purchase-editprice");
				break;
			case 'RKPRODUCT' :
				$this->view("product-edit");
				break;
			case 'RKOTHER' :
				$this->view("other-editprice");
				break;
			default :
				break;
		}
	}

	/**
	 * 修改入库单
	 */
	function c_editPrice() {
		if ($this->service->editPrice_d($_POST[$this->objName])) {
			msgRf('编辑成功');
		} else {
			msgRf('编辑失败');
		}
	}

	/**
	 * 成本明细账 -选择页面
	 */
	function c_toDetailList() {
		//获取当前财务周期
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$docType = isset($_GET['docType']) ? $_GET['docType'] : 'CKSALES';
		$this->assign('docType', $docType);
		$this->showDatadicts(array('catchStatus' => 'CGFPZT'));
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view('purchase-todetail');
				break;
			case 'RKPRODUCT' :
				$this->view("product-todetail");
				break;
			case 'RKOTHER' :
				$this->view("other-todetail");
				break;
			default :
				break;
		}
	}

	/**
	 * 成本明细账
	 */
	function c_detailList() {
		//获取当前财务周期
		$this->assignFunc($_GET);
		$docType = isset($_GET['docType']) ? $_GET['docType'] : 'CKSALES';
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view('purchase-listdetail');
				break;
			case 'RKPRODUCT' :
				$this->view("product-listdetail");
				break;
			case 'RKOTHER' :
				$this->view("other-listdetail");
				break;
			default :
				break;
		}
	}

	/*****************************核算部分结束**************************************/

	/**
	 *
	 * 入库审核权限判断
	 * @param  $docType
	 */
	function checkAuditLimit($docType) {
		switch ($docType) {
			case 'RKPURCHASE' :
				if ($this->service->this_limit['外购入库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKPRODUCT' :
				//审核权限判断
				if ($this->service->this_limit['产品入库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKOTHER' :
				//审核权限判断
				if ($this->service->this_limit['其他入库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKPRODUCEBACK' :
				//审核权限判断
				if ($this->service->this_limit['退料入库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			default :
				break;
		}
	}

	/**
	 *
	 * 反审核权限判断
	 * @param  $docType
	 */
	function c_cancelAuditLimit() {
		$docType = isset($_POST['docType']) ? $_POST['docType'] : null;
		switch ($docType) {
			case 'RKPURCHASE' :
				if ($this->service->this_limit['外购入库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKPRODUCT' :
				//审核权限判断
				if ($this->service->this_limit['产品入库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKOTHER' :
				//审核权限判断
				if ($this->service->this_limit['其他入库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKPRODUCEBACK' :
				//审核权限判断
				if ($this->service->this_limit['退料入库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
            case 'RKDLBF' :
                //审核权限判断
                if ($this->service->this_limit['待报废呆料入库反审核']) {
                    echo 1;
                } else {
                    echo 0;
                }
                break;
			default :
				break;
		}
	}
	/*************************************存货分析 列表导出*********开始***************************************************/
	/**
	 * 采购成本明细表导出
	 */
	function c_purchaseExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		//获取表头数据
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//表头Id数组

		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "红蓝字");
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);

		$titleName = isset($_GET['titleName']) ? $_GET['titleName'] : '入库明细表';
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//获取列表内容
		$searchArr['docStatus'] = $_GET['docStatus'];
		$searchArr['docType'] = $_GET['docType'];
		$searchArr['beginDate'] = $_GET['beginDate'];
		$searchArr['endDate'] = $_GET['endDate'];
		$searchArr['supplierId'] = $_GET['supplierId'];
		$searchArr['iProductId'] = $_GET['iProductId'];
		$searchArr['docCode'] = $_GET['docCode'];
		$searchArr['iInStockId'] = $_GET['iInStockId'];
		$searchArr['iPattern'] = $_GET['iPattern'];
		$searchArr['iActNum'] = $_GET['iActNum'];
		$searchArr['iPrice'] = $_GET['iPrice'];
		$searchArr['iSubPrice'] = $_GET['iSubPrice'];
		$searchArr['createId'] = $_GET['createId'];
		$searchArr['purchaserCode'] = $_GET['purchaserCode'];
		$searchArr['auditerCode'] = $_GET['auditerCode'];
		$searchArr['auditDate'] = $_GET['auditDate'];
		foreach ($searchArr as $k => $v) {
			if (empty($searchArr[$k])) {
				unset($searchArr[$k]);
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_callist');
		$datadictDao = new model_system_datadict_datadict();
		$catchStatusArr = $datadictDao->getDataDictList_d('CGFPZT', array('isUse' => 0));
		foreach ($rows as $key => $val) {
			if ($val['docStatus'] == 'YSH') {
				$rows[$key]['docStatus'] = "已审核";
			} else {
				$rows[$key]['docStatus'] = "未审核";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "红字";
			} else {
				$rows[$key]['isRed'] = "蓝字";
			}
			//钩稽状态
			$rows[$key]['catchStatus'] = isset($catchStatusArr[$val['catchStatus']]) ? $catchStatusArr[$val['catchStatus']] : '';
			//采购部门
			if (empty($rows[$key]['purchaserCode'])) {
				$rows[$key]['purchaserDept'] = $val['purchaserName'];
				$rows[$key]['purchaserName'] = "";
			} else {
				$rows[$key]['purchaserDept'] = "";
			}
			//金额处理
			$rows[$key]['price'] = number_format($val['price'],2);
			$rows[$key]['subPrice'] = number_format($val['subPrice'],2);
			$rows[$key]['unHookAmount'] = number_format($val['unHookAmount'],2);
			$rows[$key]['hookAmount'] = number_format($val['hookAmount'],2);
		}
		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, $titleName);
	}

    /**
     * 待报废呆料入库单物料导出
     */
    function c_idleStockItmesExportExcel() {
        // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
        set_time_limit(0);

        $titleName = isset($_REQUEST['fileName'])? $_REQUEST['fileName'] : '待报废呆料物料明细';
        $stockinitemDao = new model_stock_instock_stockinitem();
        $sql = isset($_SESSION['pageListGridSql'])? $_SESSION['pageListGridSql'] : "";
        $idleStockInArr = $this->service->_db->getArray($sql);
        $dataArr = $outputArr = array();
        $colArr = array("mainId"=>"单据ID","mainCode"=>"单据编号","mainStatus"=>"单据状态","isRed"=>"红蓝字","mainStockInName"=>"收料仓库","id"=>"物料ID","productCode"=>"物料编号","k3Code"=>"k3编号","productName"=>"物料名称","pattern"=>"规格型号","actNum"=>"实收数量","serialnoName"=>"序列号","remark"=>"备注");
        // 获取入库单对应物料并拼接导出数组
        foreach ($idleStockInArr as $idleStockIn){
            $itemsArr = $stockinitemDao->findAll(" mainId={$idleStockIn['id']}");
            foreach ($itemsArr as $k => $v){
                $itemsArr[$k]['mainCode'] = $idleStockIn['docCode'];
                $itemsArr[$k]['mainStatus'] = ($idleStockIn['docStatus'] == 'YSH')? '已审核' : '未审核';
                $itemsArr[$k]['mainRemark'] = $idleStockIn['remark'];
                $itemsArr[$k]['mainStockInName'] = $idleStockIn['inStockName'];
                $itemsArr[$k]['isRed'] = $idleStockIn['isRed'];
                $outputArr[] = $itemsArr[$k];
            }
        }

        // 拼接导出数据
        foreach ($outputArr as $output){
            $data = array();
            foreach ($colArr as $colk => $colv){
                if($colk == 'isRed'){
                    $data[$colk] = ($output['isRed'] == 0)? '蓝字' : '红字';
                }else if($colk == 'actNum'){
                    $data[$colk] = ($output['isRed'] == 0)? $output[$colk] : '-'.$output[$colk];
                }else{
                    $data[$colk] = $output[$colk];
                }
            }
            $dataArr[] = $data;
        }
        return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, $titleName);
    }
	/*************************************存货分析 列表导出*********end***************************************************/
	/**
	 * 外购入库单下推采购发票时,组成清单模板
	 */
	function c_showItemAtInp() {
		$orderId = isset($_POST['objId']) ? $_POST['objId'] : "";
		$itemDao = new model_stock_instock_stockinitem();
		$itemArr = $itemDao->getItemByMainId($orderId); //根据订单ID获取物料信息
		$listStr = $itemDao->showItemAtInp($itemArr, $_POST); //获取物料模板
		echo util_jsonUtil::iconvGB2UTF($listStr);
	}

	/**
	 *
	 *跳转到导入2011年8月前的外购入库单页面
	 */
	function c_toUploadPurchaseExcel() {
		$this->display("purchase-import");
	}

	/**
	 *
	 *跳转到导入未勾稽外购入库单页面
	 */
	function c_toUploadWgjExcel() {
		$this->display("purchasewgj-import");
	}

	/**
	 *
	 *导入2011年8月前的外购入库单
	 */
	function c_importPurchaseStockin() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil();
			$excelData = $dao->readExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			$resultArr = $service->importPurchaseStockin($excelData);
			if ($resultArr)
				echo util_excelUtil::showResult($resultArr, "2011年8月前入库单导入结果", array("提示信息", "结果"));
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 *导入未勾稽外购入库单
	 */
	function c_importWgjStockin() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil();
			$excelData = $dao->readExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			$resultArr = $service->importWgjDoc($excelData);
			if ($resultArr)
				echo util_excelUtil::showResult($resultArr, "导入结果", array("提示信息", "结果"));
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 * 产品入库邮件默认设置
	 */
	function setProInStockMail() {
		//获取默认邮件收件人
		include(WEB_TOR . "model/common/mailConfig.php");
		if (isset($mailUser['productinstock']) && is_array($mailUser['productinstock'])) {
			$TO_IDArr = array();
			$TO_NAMEArr = array();
			foreach ($mailUser['productinstock'] as $key => $value) {
				array_push($TO_IDArr, $value['sendUserId']);
				array_push($TO_NAMEArr, $value['sendName']);
			}
			$this->assign("TO_ID", implode($TO_IDArr, ","));
			$this->assign("TO_NAME", implode($TO_NAMEArr, ","));
		} else {
			$this->assign("TO_ID", "");
			$this->assign("TO_NAME", "");
		}
	}

	/**
	 * 采购发票钩稽获取外购入库单
	 * createBy Show
	 * createOn 2012-01-03
	 */
	function c_getPurchaseOutstock() {
		if ($rs = $this->service->getPurchaseOutstock_d($_POST['supplierId'])) {
			echo util_jsonUtil::iconvGB2UTF($rs);
		} else {
			echo 0;
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_stockJson() {
		$service = $this->service;
		$_REQUEST['limit'] = $service->this_limit;
		$service->getParam($_REQUEST);
		$service->searchArr['id'] = 1;
		// 		$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 更新出库核算的单价
	 */
	function c_updateProductPrice() {
		set_time_limit(0);
		echo $this->service->updateProductPrice_d($_POST);
	}

    /**
     * 检查该呆料报废入库单能否申请报废
     */
	function c_chkCanScrap(){
        $id = isset($_REQUEST['id'])? $_REQUEST['id'] : '';
        $extSql = isset($_REQUEST['extSql'])? $_REQUEST['extSql'] : '';
        if($id != ''){
            $stockOutDao = new model_stock_outstock_stockout();
            $condition = "relDocId={$id} and relDocType='RKDLBF' ".$extSql;
            $resultArr = $stockOutDao->findAll($condition);
            $result = 0;
            if($resultArr){
                $result = count($resultArr);
            }
            echo $result;
        }
    }

    /**
     * 检查上级出库单是否能下推红单
     */
    function c_chkParentCanAddRed(){
        $parentId = isset($_REQUEST['parentId'])? $_REQUEST['parentId'] : '';
        $parentToUse = isset($_REQUEST['parentToUse'])? $_REQUEST['parentToUse'] : '';
        if($parentId != '' && $parentToUse != ''){
            $stockOutDao = new model_stock_outstock_stockout();
            switch ($parentToUse){
                // 呆料报废
                case 'CHUKUDLBF':
                    $condition = "relDocId={$parentId} and docStatus='YSH'";
                    $resultArr = $this->service->find($condition);
                    $parentRedObj = $stockOutDao->findAll(array("relDocId"=>$parentId,"relDocType"=>'QTCKQTCK',"isRed"=>1));
                    if($parentRedObj && count($parentRedObj)>0){
                        echo "false";
                    }else{
                        if(isset($resultArr['id']) && $resultArr['id'] != ''){
                            $condition = "relDocId={$resultArr['id']} and relDocType='RKDLBF' and docStatus<>'WSH' ";
                            $resultArr = $stockOutDao->findAll($condition);
                            $num = ($resultArr)? count($resultArr) : 0;
                            echo ($num > 0)? 'false' : 'ok';
                        }else{
                            echo 'false';
                        }
                    }
                    break;
            }
        }else{
            echo 'false';
        }
    }

    /**
     * 检查呆料报废出入库单据能否下推红单
     */
    function c_chkCanAddRed(){
        $service = $this->service;
        $objType = isset($_REQUEST['objType'])? $_REQUEST['objType'] : '';
        $relDocId = isset($_REQUEST['relDocId'])? $_REQUEST['relDocId'] : '';
        $result = 0;
        if($relDocId != ''){
            // 读取所有处于已审核和审批中的关联单据
            $ItemsArr = $service->getRelativeItemsCount($relDocId,"'YSH','SPZ'");

            // 统计该单据关联的所以出入库单红蓝单抵消后的物料数量,并检查物料中有没数量是0的物料,以及数量不为0的物料
            $hasEmptyEquNum = $hasEquNum = 0;
            if(is_array($ItemsArr) && count($ItemsArr) > 0){
                foreach ($ItemsArr as $k => $v){
                    $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    $hasEquNum += ($v['Num'] > 0)? 1 : 0;
                }
            }

            switch($objType){
                case 'stockIn':// 如果有物料红蓝抵消后数量是0的,入库单不能推红单
                    $result = ($hasEmptyEquNum > 0)? 0 : 1;
                    break;
                case 'stockOut':// 如果有物料红蓝抵消后数量大于0的,出库单不能推红单
                    $result = ($hasEquNum > 0)? 0 : 1;
                    break;
            }
        }

        echo $result;
    }

    /**
     * 下推其他入库单的时候,检查可下达的数量
     * 如果已有未审核的入库单,提示先去处理未审核的入库单
     */
    function c_ajaxChkAvailableEquNum(){
        $relDocId = isset($_POST['relDocId'])? $_POST['relDocId'] : '';
        $relDocCode = isset($_POST['relDocCode'])? $_POST['relDocCode'] : '';
        $products = isset($_POST['products'])? $_POST['products'] : array();
        $notInIds = isset($_POST['notInIds'])? $_POST['notInIds'] : '';
        $backArr = array(
            "result" => "",
            "msg" => ""
        );

        $productIds = implode(",",array_keys($products));

        $extSql = ($notInIds == '')? '' : " AND s.Id not in ({$notInIds})";
        $sql = "
            SELECT
                *, (t.number - t.actNum) AS availableNum
            FROM
            (
                SELECT
                    s.relDocId,
                    i.relDocId AS equId,
                    i.productCode,
                    e.number,
                    sum(i.actNum) AS actNum
                FROM
                    oa_stock_instock_item i
                LEFT JOIN oa_stock_instock s ON i.mainId = s.id
                LEFT JOIN oa_stock_innotice_equ e ON i.relDocId = e.id
                WHERE
                    s.relDocId = '{$relDocId}'
                    AND s.relDocCode = '{$relDocCode}'
                    AND i.relDocId in ({$productIds})
                    {$extSql}
                GROUP BY
                    i.relDocId
            ) t
        ";
        $result = $this->service->_db->getArray($sql);
        $result = ($result)? $result : array();
        foreach ($result as $v){
            if(isset($products[$v['equId']]) && $products[$v['equId']]['num'] > $v['availableNum']){
                if($backArr['msg'] == ""){
                    $backArr['msg'] = $v['productCode'];
                }else{
                    $backArr['msg'] .= ','.$v['productCode'];
                }
            }
        }
        $backArr['result'] = ($backArr['msg'] != "")? "" : "ok";

        echo util_jsonUtil::encode($backArr);
    }
}