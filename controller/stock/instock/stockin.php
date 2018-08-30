<?php

/**
 * @author huangzf
 * @Date 2011��5��9�� 22:10:00
 * @version 1.0
 * @description:��ⵥ������Ϣ���Ʋ� 1.��ⵥ���ͣ�A.�вɹ����B.��Ʒ���C.�������
 * 2.����״̬��  δ��ˡ������
 */
class controller_stock_instock_stockin extends controller_base_action
{

	function __construct() {
		$this->objName = "stockin";
		$this->objPath = "stock_instock";
		parent::__construct();
	}

	/**
	 * ��ת���б�ҳ
	 */
	function c_toList() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		if ($this->service->this_limit['�ִ���⵼��']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}

		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-list");
				break; //�⹺���
			case 'RKPRODUCT' :
				$this->view("product-list");
				break; //��Ʒ���
			case 'RKOTHER' :
				$this->view("other-list");
				break; //�������
			case 'RKPRODUCEBACK' :
				$this->view("produceback-list");
				break; //�������
            case 'RKDLBF' :
                $this->view("idleStockScrap-list");
                break; //���ϱ������
			default :
				break;
		}
	}

	/**
	 * ��ת��������ⵥ�б�ҳ
	 */
	function c_pageByProduce() {
		if ($this->service->this_limit['�ִ���⵼��']) {
			$this->assign ( "importLimit", "1" );
		} else {
			$this->assign ( "importLimit", "0" );
		}
		$this->view('product-produce-list');
	}

	/**
	 * ��ת���������ݲ鿴ҳ��-�������
	 */
	function c_toListForExchange() {
		if ($this->service->this_limit['�ִ���⵼��']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->assign('exchangeId', $_GET['id']);
		$this->view("other-listforexchange");
	}

	/**
	 * ��ת���б�ҳ
	 */
	function c_toBusinessList() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-business-list");
				break; //�⹺���
			case 'RKPRODUCT' :
				$this->view("product-business-list");
				break; //��Ʒ���
			case 'RKOTHER' :
				$this->view("other-business-list");
				break; //�������
			default :
				break;
		}
	}

	/**
	 * ��ת������ⵥ�����ɹ������б�ҳ
	 */
	function c_toUnionList() {
		$this->view("purchase-union-list");
	}

	/**
	 * ��ת������ⵥ�����ɹ���������ҳ��
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
	 * ��ת������ⵥ��������֪ͨ������ҳ��
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
	 * ��ⵥ �ɹ���������
	 */
	function c_unionOrder() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		if ($service->unionOrder($stockinObject)) {
			echo "<script>alert('���������ɹ�!'); window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('��������ʧ��!');window.opener.window.show_page();window.close();</script>";
		}

		//echo "<script>alert('���������ɹ�!');window.close();</script>";
	}

	/**
	 *
	 * ��ⵥ ����֪ͨ������
	 */
	function c_unionArrival() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		if ($service->unionArrival($stockinObject)) {
			echo "<script>alert('��������֪ͨ���ɹ�!'); window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('��������֪ͨ��ʧ��!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * ��ת��������ⵥҳ��
	 * @author huangzf
	 */
	function c_toAdd() {
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('auditDate', day_date);
		//���Ȩ���ж�
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
	 * ��ת�����ƺ�ɫ��ⵥҳ��
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
		//���Ȩ���ж�
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
	 * �������������ɫ��ⵥ
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
				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$eqpArr = $productinfoDao->k3CodeFormatter_d($eqpArr);
				$this->assign("itemscount", count($eqpArr));
				$this->assign("stockinItems", $noticeequpDao->showAddList($eqpArr));
				$this->view("product-blue-push" ,true);
				break;
			case 'RKOTHER' :
				// ID2295 Ҫ�󲹵�һ��Ĭ���ֶ�
				$this->assign("purchMethodStr", 'yes');
				$this->assign("purchMethod", '�������');

				$this->checkAuditLimit("RKPURCHASE");
				$this->assign("relDocId", $_GET['relDocId']);
				$this->assign("relDocCode", $_GET['relDocCode']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($_GET['relDocType']));
				$this->assign("relDocType", $_GET['relDocType']);
				$this->assign("relDocName", "");
				$this->assign("docType", $_GET['docType']);

				$noticeequpDao = new model_stock_withdraw_noticeequ();
				$eqpArr = $noticeequpDao->getItemByBasicIdUnstock_d($_GET['relDocId']);
				// k3������ش���
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
	 * �ʲ����-������ɫ��ⵥ
	 */
	function c_toAddBlueByAsset() {
		$id = $_GET['id'];    //���뵥id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//��ȡ���뵥��Ϣ����ʼ��
		$requireoutDao = new model_asset_require_requireout();
		$obj = $requireoutDao->get_d($id);
		$this->assign('relDocCode', $obj['requireCode']);
		//��ȡ��ϸ����
		$itemDao = new model_asset_require_requireoutitem();
		$item = $itemDao->getDetail_d($id);
		$this->assign('itemscount', count($item));
		//���Ȩ���ж�
		$this->checkAuditLimit("RKOTHER");
		$this->view("other-assetblue");
	}

	/**
	 * �ʲ����-������ɫ��ⵥ
	 */
	function c_toAddBlueByAssetNew() {
		$id = $_GET['id'];    //���뵥id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);

		// ��ȡ�ʲ����յ�������
		$result = util_curlUtil::getDataFromAWS('asset', 'getAssetTransferInfo', array(
			"assetTransferId" => $id
		));
		$assetTransferInfo = util_jsonUtil::decode($result['data'], true);

		// ����ֵ����
		if ($assetTransferInfo['data']['assetTransferInfo']) {
			$this->assign('relDocCode', $assetTransferInfo['data']['assetTransferInfo']['applyNo']);
		}

		$str = "";
		// �ӱ�ֵ����
		if ($assetTransferInfo['data']['details']) {
			$productIds = array();

			foreach ($assetTransferInfo['data']['details'] as $v) {
				$productIds[] = $v['productId'];
			}

			// �ڳ���ȡ
			$periodDao = new model_finance_period_period();
			$periodObj = $periodDao->rtThisPeriod_d();

			// ���������³���۸����
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

		//���Ȩ���ж�
		$this->checkAuditLimit("RKOTHER");
		$this->view("other-assetbluenew");
	}

	/**
	 *
	 * �����������9�·�ǰ��ⵥ
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
	 * �Ӳɹ����������⹺��ⵥ
	 */
	function c_toPush() {
		$this->permCheck($_GET['orderId'], 'purchase_contract_purchasecontract'); //��ȫУ��
		//���Ȩ���ж�
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
	 * ��ת���޸���ⵥҳ��
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
		//���Ȩ���ж�
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
	 * ��ת���鿴��ⵥҳ��
	 */
	function c_toView() {
		//		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
        $moneyLimit = $service->this_limit['�����'];
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('�����', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
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
	 * ͨ�����ݱ�Ų鿴��ⵥ��Ϣ
	 */
	function c_viewByDocCode() {
		$docCode = isset($_GET['docCode']) ? $_GET['docCode'] : null;
		$service = $this->service;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->findByDocCode($docCode, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('�����', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
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
	 *�߼�����ҳ��
	 */
	function c_toAdvancedSearch() {
		$this->assign("docType", $_GET['docType']);
		$this->showDatadicts(array('catchStatus' => 'CGFPZT'), null, true);
		$this->view("search-advanced");
	}

	/**
	 * ������ⵥ
	 * @author huangzf
	 */
	function c_add() {
		try {
			$service = $this->service;
			$stockinObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------���Ȩ�޿���,�����������----------------*/
			if ("audit" == $actType) {
				if ($stockinObject['isRed'] == 1) {
					if ($stockinObject['docType'] == "RKPURCHASE") {
						if (!$service->this_limit['�⹺������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCT") {
						if (!$service->this_limit['��Ʒ������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKOTHER") {
						if (!$service->this_limit['����������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCEBACK") {
						if (!$service->this_limit['����������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
				} else {
					if ($stockinObject['docType'] == "RKOTHER" && $stockinObject['relDocType'] == 'RHHRK') {
						if (!$service->this_limit['����������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					} else {
						if ($stockinObject['docType'] == "RKPURCHASE" && $stockinObject['relDocType'] == 'RSLTZD') {
							$checkResult = $this->service->checkAudit_d($stockinObject);//�������������ϵ��������Ƿ���д����
							if ($checkResult == 1) {
								if (!$service->this_limit['�⹺������']) {
									echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
									exit();
								}
							} else if ($checkResult == 2) {
								echo "<script>alert('��д����������ܴ����ʼ�ϸ���!');window.close();</script>";
								exit();
							} else {
								echo "<script>alert('�ջ��������ܴ��ڶ�������!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKPRODUCT") {
							if (!$service->this_limit['��Ʒ������']) {
								echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKOTHER") {
							if (!$service->this_limit['����������']) {
								echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
								exit();
							}
						}
						if ($stockinObject['docType'] == "RKPRODUCEBACK") {
							if (!$service->this_limit['����������']) {
								echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
								exit();
							}
						}
					}
				}
				$stockinObject['auditerName'] = $_SESSION['USERNAME'];
				$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���,�����������----------------*/
			$id = $service->add_d($stockinObject);

			if ($id) {
				if ("audit" == $actType) {
					$addObj = $service->find(array("id" => $id), null, 'docCode');
					//��ˣ����¼�ʱ���
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
					echo "<script>alert('�����ⵥ�ɹ�!���ݱ��Ϊ:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('������ⵥ�ɹ�!'); window.opener.window.show_page();window.close();  </script>";
				}
			} else {
				if ("audit" == $actType) {
					echo "<script>alert('������ʧ��,��ȷ�ϲֿ��Ƿ��ʼ��������!'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('������ⵥʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
				}
			}
		} catch (Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}
function c_test(){
    $applyId = $_REQUEST['applyId'];
    $qualityapplyDao = new model_produce_quality_qualityapply();
    $relClass = $qualityapplyDao->getStrategy_d($applyId);
    $relClassM = new $relClass(); //����ʵ��
    $applyObj = $qualityapplyDao->get_d($applyId);
    $qualityapplyDao->ctDealRelInfoCompleted($applyObj['relDocId'], $relClassM);
}
	/**
	 * ����9�·���ⵥ
	 * @author huangzf
	 */
	function c_addPre() {
		$service = $this->service;
		$stockinObject = $_POST[$this->objName];
		$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
		$docType = $stockinObject['docType'];

		/*s:--------------���Ȩ�޿���,�����������----------------*/
		if ("audit" == $actType) {
			if ($stockinObject['docType'] == "RKPURCHASE") {
				if (!$service->this_limit['�⹺������']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit();
				}
			}
			$stockinObject['auditerName'] = $_SESSION['USERNAME'];
			$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
		}
		/*e:--------------���Ȩ�޿���,�����������----------------*/

		$id = $service->addPre_d($stockinObject);

		if ($id) {
			if ("audit" == $actType) {
				$addObj = $service->find(array("id" => $id), null, 'docCode');
				echo "<script>alert('�����ⵥ�ɹ�!���ݱ��Ϊ:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('������ⵥ�ɹ�!'); window.opener.window.show_page();window.close();  </script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('������ʧ��,��ȷ�ϲֿ��Ƿ��ʼ��������!'); window.opener.window.show_page();window.close();  </script>";
			} else {
				echo "<script>alert('������ⵥʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
			}
		}

	}

	/**
	 * �޸���ⵥ
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$stockinObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;

			/*s:--------------���Ȩ�޿���,�����������----------------*/
			if ("audit" == $actType) {
				$checkResult = $service->checkAudit_d($stockinObject);//�������������ϵ��������Ƿ���д����
				if ($checkResult == 1) {
					if ($stockinObject['docType'] == "RKPURCHASE") {
						if (!$service->this_limit['�⹺������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKPRODUCT") {
						if (!$service->this_limit['��Ʒ������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
					if ($stockinObject['docType'] == "RKOTHER") {
						if (!$service->this_limit['����������']) {
							echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
							exit();
						}
					}
				} elseif ($checkResult == 4) {//�����ʲ����,��Ʒ�����֤
					echo "<script>alert('������������������������ʵ!');window.close();</script>";
					exit();
				}
				$stockinObject['auditerName'] = $_SESSION['USERNAME'];
				$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���,�����������----------------*/
			//��ֹ�����ظ����
			$lastObj = $service->find(array("id" => $stockinObject['id']));
			if ($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('�����Ѿ����,�����޸�,��ˢ���б�!');window.close();</script>";
				exit();
			}

			if($stockinObject['docType'] == 'RKDLBF' && $stockinObject['isRed'] == 1){// �����ϴ��Ϻ쵥�༭����
                // ����ô��ϱ�����ⵥ�����ĳ��ⵥ���ύ����,���޷���˺쵥
                $ItemsArr = $service->getRelativeItemsCount($stockinObject['relDocId'],"'YSH','SPZ'");

                if(is_array($ItemsArr) && count($ItemsArr) > 0){
                    $hasEmptyEquNum = 0;// ���ϵ����������Ϊ0�ĸ���
                    foreach ($ItemsArr as $k => $v){
                        $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    }
                    if($hasEmptyEquNum > 0){
                        echo "<script>alert('������������,��˶����й�������ⵥ�ĺ���������,���������еĳ��ⵥ!');window.close();</script>";
                        exit();
                    }
                }
            }

			if ($service->edit_d($stockinObject)) {
				if ("audit" == $actType) {
					//��ˣ����¼�ʱ���
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
					echo "<script>alert('�����ⵥ�ɹ�!���ݱ��Ϊ:" . $lastObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('�޸���ⵥ�ɹ�!'); window.opener.window.show_page();window.close();  </script>";
				}
			}
		} catch (Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * ��ⵥ�����
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
			echo "����ʧ��!�쳣:" . $e->getMessage();
		}
	}

	/**
	 * ������ɫ��ⵥ���ƺ�ɫ��ⵥģ��
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
	 * ��ⵥ���Ƴ��ⵥ��ʾģ��
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
	 * ��ת����ӡ��ⵥҳ��
	 */
	function c_toPrint() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$stockinStrategy = $service->stockinStrategyArr[$docType];
		$stockinObject = $service->get_d($id, new $stockinStrategy());
        if(!empty($stockinObject['items'])){
            $stockinObject['items'] = $service->filterWithoutField('�����', $stockinObject['items'], 'keyList', array('price', 'subPrice','unHookAmount'), 'stock_instock_stockin');
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
				//��ȡԴ����Ϣ
				$rs = $service->getRelInfo(array('relDocId' => $stockinObject['relDocId'],
					'relDocType' => $stockinObject['relDocType']), new $stockinStrategy());
				$applyName = $applyDeptName = '';
				if($rs){
					if($stockinObject['relDocType'] == 'RHHRK'){//����
						//���֪ͨ����ȡ��������Ϣ
						$exchangeDao = new model_projectmanagent_exchange_exchange();
						$rs = $exchangeDao->find(array('id' => $rs['docId']) ,null,'contractId');
						//��ȡ��ͬ��������Ϣ
						$contractDao = new model_contract_contract_contract();
						$rs = $contractDao->find(array('id' => $rs['contractId']) ,null,'prinvipalName,prinvipalDept');
						$applyName = $rs['prinvipalName'];
						$applyDeptName = $rs['prinvipalDept'];
					}else if($stockinObject['relDocType'] == 'RXSTH'){//�˻�
						//��ȡ��ͬ��������Ϣ
						$contractDao = new model_contract_contract_contract();
						$rs = $contractDao->find(array('id' => $rs['contractId']) ,null,'prinvipalName,prinvipalDept');
						$applyName = $rs['prinvipalName'];
						$applyDeptName = $rs['prinvipalDept'];
					}else if($stockinObject['relDocType'] == 'RZCRK'){//�ʲ����
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
	 * ��ȡ��ⵥ�б�ҳ������Json
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
	 * ��ȡ���ݲɹ�Ա���ƹ�����ⵥ����Json
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
	 * �ж�һ��ʱ�����Ƿ���δ��˵���
	 */
	function c_isExsitWsh() {
		$pYear = isset($_POST['year']) ? $_POST['year'] : null;
		$pMonth = isset($_POST['month']) ? $_POST['month'] : null;
		$service = $this->service;
		$totalDays = cal_days_in_month(CAL_GREGORIAN, $pMonth, $pYear); // 2010��1�·ݵ�����
		$service->searchArr = array("dAuditDate" => "$pYear-$pMonth-01", "xAuditDate" => "$pYear-$pMonth-$totalDays", 'docStatus' => "WSH");
		$resultObj = $service->listBySqlId();
		if (is_array($resultObj)) {
			echo 0; //������˵���
		} else {
			echo 1; //������δ��˵���
		}
	}

	/********************************���㲿��**********************************/

	/**
	 * ��ת���б�ҳ
	 */
	function c_toCalList() {
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$docType = isset($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('docType', $docType);
		switch ($docType) {
			case 'RKPURCHASE' :
				$this->view("purchase-listcal");
				break; //�⹺���
			case 'RKPRODUCT' :
				$this->view("product-list");
				break; //��Ʒ���
			case 'RKOTHER' :
				$this->view("other-listcal");
				break; //�������
			default :
				break;
		}
	}

	/**
	 * �����б�
	 */
	function c_calPageJson() {
		$service = $this->service;
		if($_POST['beginDate'] == '--01'){
			unset($_POST['beginDate']);
		}
		if($_POST['endDate'] == '--31'){
			unset($_POST['endDate']);
		}
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->list_d('select_callist');
		if ($rows && $_POST['ifshow'] == 0) {
			$rows = $service->filterRows_d($rows);
		}
		$rows = $this->sconfig->md5Rows($rows, 'mainId');
		// �ϼƴ���
		if ($rows) {
			$sum = array('id' => 'nocheck', 'actNum' => 0, 'subPrice' => '0', 'auditDate' => '�ϼ�');
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
	 * �޸������
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
	 * �޸���ⵥ
	 */
	function c_editPrice() {
		if ($this->service->editPrice_d($_POST[$this->objName])) {
			msgRf('�༭�ɹ�');
		} else {
			msgRf('�༭ʧ��');
		}
	}

	/**
	 * �ɱ���ϸ�� -ѡ��ҳ��
	 */
	function c_toDetailList() {
		//��ȡ��ǰ��������
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
	 * �ɱ���ϸ��
	 */
	function c_detailList() {
		//��ȡ��ǰ��������
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

	/*****************************���㲿�ֽ���**************************************/

	/**
	 *
	 * ������Ȩ���ж�
	 * @param  $docType
	 */
	function checkAuditLimit($docType) {
		switch ($docType) {
			case 'RKPURCHASE' :
				if ($this->service->this_limit['�⹺������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKPRODUCT' :
				//���Ȩ���ж�
				if ($this->service->this_limit['��Ʒ������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKOTHER' :
				//���Ȩ���ж�
				if ($this->service->this_limit['����������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'RKPRODUCEBACK' :
				//���Ȩ���ж�
				if ($this->service->this_limit['����������']) {
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
	 * �����Ȩ���ж�
	 * @param  $docType
	 */
	function c_cancelAuditLimit() {
		$docType = isset($_POST['docType']) ? $_POST['docType'] : null;
		switch ($docType) {
			case 'RKPURCHASE' :
				if ($this->service->this_limit['�⹺��ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKPRODUCT' :
				//���Ȩ���ж�
				if ($this->service->this_limit['��Ʒ��ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKOTHER' :
				//���Ȩ���ж�
				if ($this->service->this_limit['������ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'RKPRODUCEBACK' :
				//���Ȩ���ж�
				if ($this->service->this_limit['������ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
            case 'RKDLBF' :
                //���Ȩ���ж�
                if ($this->service->this_limit['�����ϴ�����ⷴ���']) {
                    echo 1;
                } else {
                    echo 0;
                }
                break;
			default :
				break;
		}
	}
	/*************************************������� �б���*********��ʼ***************************************************/
	/**
	 * �ɹ��ɱ���ϸ����
	 */
	function c_purchaseExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		//��ȡ��ͷ����
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//��ͷId����

		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "������");
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);

		$titleName = isset($_GET['titleName']) ? $_GET['titleName'] : '�����ϸ��';
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//��ȡ�б�����
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
				$rows[$key]['docStatus'] = "�����";
			} else {
				$rows[$key]['docStatus'] = "δ���";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "����";
			} else {
				$rows[$key]['isRed'] = "����";
			}
			//����״̬
			$rows[$key]['catchStatus'] = isset($catchStatusArr[$val['catchStatus']]) ? $catchStatusArr[$val['catchStatus']] : '';
			//�ɹ�����
			if (empty($rows[$key]['purchaserCode'])) {
				$rows[$key]['purchaserDept'] = $val['purchaserName'];
				$rows[$key]['purchaserName'] = "";
			} else {
				$rows[$key]['purchaserDept'] = "";
			}
			//����
			$rows[$key]['price'] = number_format($val['price'],2);
			$rows[$key]['subPrice'] = number_format($val['subPrice'],2);
			$rows[$key]['unHookAmount'] = number_format($val['unHookAmount'],2);
			$rows[$key]['hookAmount'] = number_format($val['hookAmount'],2);
		}
		//ƥ�䵼����
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
     * �����ϴ�����ⵥ���ϵ���
     */
    function c_idleStockItmesExportExcel() {
        // ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
        set_time_limit(0);

        $titleName = isset($_REQUEST['fileName'])? $_REQUEST['fileName'] : '�����ϴ���������ϸ';
        $stockinitemDao = new model_stock_instock_stockinitem();
        $sql = isset($_SESSION['pageListGridSql'])? $_SESSION['pageListGridSql'] : "";
        $idleStockInArr = $this->service->_db->getArray($sql);
        $dataArr = $outputArr = array();
        $colArr = array("mainId"=>"����ID","mainCode"=>"���ݱ��","mainStatus"=>"����״̬","isRed"=>"������","mainStockInName"=>"���ϲֿ�","id"=>"����ID","productCode"=>"���ϱ��","k3Code"=>"k3���","productName"=>"��������","pattern"=>"����ͺ�","actNum"=>"ʵ������","serialnoName"=>"���к�","remark"=>"��ע");
        // ��ȡ��ⵥ��Ӧ���ϲ�ƴ�ӵ�������
        foreach ($idleStockInArr as $idleStockIn){
            $itemsArr = $stockinitemDao->findAll(" mainId={$idleStockIn['id']}");
            foreach ($itemsArr as $k => $v){
                $itemsArr[$k]['mainCode'] = $idleStockIn['docCode'];
                $itemsArr[$k]['mainStatus'] = ($idleStockIn['docStatus'] == 'YSH')? '�����' : 'δ���';
                $itemsArr[$k]['mainRemark'] = $idleStockIn['remark'];
                $itemsArr[$k]['mainStockInName'] = $idleStockIn['inStockName'];
                $itemsArr[$k]['isRed'] = $idleStockIn['isRed'];
                $outputArr[] = $itemsArr[$k];
            }
        }

        // ƴ�ӵ�������
        foreach ($outputArr as $output){
            $data = array();
            foreach ($colArr as $colk => $colv){
                if($colk == 'isRed'){
                    $data[$colk] = ($output['isRed'] == 0)? '����' : '����';
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
	/*************************************������� �б���*********end***************************************************/
	/**
	 * �⹺��ⵥ���Ʋɹ���Ʊʱ,����嵥ģ��
	 */
	function c_showItemAtInp() {
		$orderId = isset($_POST['objId']) ? $_POST['objId'] : "";
		$itemDao = new model_stock_instock_stockinitem();
		$itemArr = $itemDao->getItemByMainId($orderId); //���ݶ���ID��ȡ������Ϣ
		$listStr = $itemDao->showItemAtInp($itemArr, $_POST); //��ȡ����ģ��
		echo util_jsonUtil::iconvGB2UTF($listStr);
	}

	/**
	 *
	 *��ת������2011��8��ǰ���⹺��ⵥҳ��
	 */
	function c_toUploadPurchaseExcel() {
		$this->display("purchase-import");
	}

	/**
	 *
	 *��ת������δ�����⹺��ⵥҳ��
	 */
	function c_toUploadWgjExcel() {
		$this->display("purchasewgj-import");
	}

	/**
	 *
	 *����2011��8��ǰ���⹺��ⵥ
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
				echo util_excelUtil::showResult($resultArr, "2011��8��ǰ��ⵥ������", array("��ʾ��Ϣ", "���"));
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 *����δ�����⹺��ⵥ
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
				echo util_excelUtil::showResult($resultArr, "������", array("��ʾ��Ϣ", "���"));
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 * ��Ʒ����ʼ�Ĭ������
	 */
	function setProInStockMail() {
		//��ȡĬ���ʼ��ռ���
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
	 * �ɹ���Ʊ������ȡ�⹺��ⵥ
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
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_stockJson() {
		$service = $this->service;
		$_REQUEST['limit'] = $service->this_limit;
		$service->getParam($_REQUEST);
		$service->searchArr['id'] = 1;
		// 		$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ���³������ĵ���
	 */
	function c_updateProductPrice() {
		set_time_limit(0);
		echo $this->service->updateProductPrice_d($_POST);
	}

    /**
     * ���ô��ϱ�����ⵥ�ܷ����뱨��
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
     * ����ϼ����ⵥ�Ƿ������ƺ쵥
     */
    function c_chkParentCanAddRed(){
        $parentId = isset($_REQUEST['parentId'])? $_REQUEST['parentId'] : '';
        $parentToUse = isset($_REQUEST['parentToUse'])? $_REQUEST['parentToUse'] : '';
        if($parentId != '' && $parentToUse != ''){
            $stockOutDao = new model_stock_outstock_stockout();
            switch ($parentToUse){
                // ���ϱ���
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
     * �����ϱ��ϳ���ⵥ���ܷ����ƺ쵥
     */
    function c_chkCanAddRed(){
        $service = $this->service;
        $objType = isset($_REQUEST['objType'])? $_REQUEST['objType'] : '';
        $relDocId = isset($_REQUEST['relDocId'])? $_REQUEST['relDocId'] : '';
        $result = 0;
        if($relDocId != ''){
            // ��ȡ���д�������˺������еĹ�������
            $ItemsArr = $service->getRelativeItemsCount($relDocId,"'YSH','SPZ'");

            // ͳ�Ƹõ��ݹ��������Գ���ⵥ���������������������,�������������û������0������,�Լ�������Ϊ0������
            $hasEmptyEquNum = $hasEquNum = 0;
            if(is_array($ItemsArr) && count($ItemsArr) > 0){
                foreach ($ItemsArr as $k => $v){
                    $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    $hasEquNum += ($v['Num'] > 0)? 1 : 0;
                }
            }

            switch($objType){
                case 'stockIn':// ��������Ϻ���������������0��,��ⵥ�����ƺ쵥
                    $result = ($hasEmptyEquNum > 0)? 0 : 1;
                    break;
                case 'stockOut':// ��������Ϻ�����������������0��,���ⵥ�����ƺ쵥
                    $result = ($hasEquNum > 0)? 0 : 1;
                    break;
            }
        }

        echo $result;
    }

    /**
     * ����������ⵥ��ʱ��,�����´������
     * �������δ��˵���ⵥ,��ʾ��ȥ����δ��˵���ⵥ
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