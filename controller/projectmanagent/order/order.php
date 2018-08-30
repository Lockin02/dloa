<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author LiuBo
 * @Date 2011��3��3�� 19:08:54
 * @version 1.0
 * @description:��ͬ������Ʋ� ��Ŀ����
 */
class controller_projectmanagent_order_order extends controller_base_action {

	function __construct() {
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->objName = "order";
		$this->objPath = "projectmanagent_order";

		parent :: __construct();
	}
	/*************************************************************************/
	/**
	 * ��ͬ������Ϣtabҳ
	 */
	function c_proinfotab() {
		$productId = isset ($_GET['productId']) ? $_GET['productId'] : null;
		$proRemarkId = isset ($_GET['proRemarkId']) ? $_GET['proRemarkId'] : null;
		$orderId = isset ($_GET['orderId']) ? $_GET['orderId'] : null;
		$type = isset ($_GET['type']) ? $_GET['type'] : null;
		$view = isset ($_GET['view']) ? $_GET['view'] : null;
		$this->assign("proId", $productId);
		$this->assign("proRemarkId", $proRemarkId);
		$this->assign("orderId", $orderId);
		$this->assign("type", $type);
		$this->assign("view", $view);
		$this->display('proinfotab');
	}
	/**
	 * ��ͬ������Ϣ ---��ע
	 */
	function c_proRemark() {
		$productId = isset ($_GET['proId']) ? $_GET['proId'] : null;
		$proRemarkId = isset ($_GET['proRemarkId']) ? $_GET['proRemarkId'] : null;
		$orderId = isset ($_GET['orderId']) ? $_GET['orderId'] : null;
		$type = isset ($_GET['type']) ? $_GET['type'] : null;
		$view = isset ($_GET['view']) ? $_GET['view'] : null;
		$remark = $this->service->proInfoRemark($productId, $orderId, $type);
		$this->assign("remark", $remark['remark']);
		$this->assign("proRemarkId", $proRemarkId);
		$this->assign("view", $view);
		$this->display('proremark');
	}
	/************************************************************************/
	/**
	 * �ӳٷ���֪ͨ�б�ҳ
	 */
	function c_delayShipments() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->display('delayShipments');
	}
	//�ӳٷ����б�ҳ Pagejson
	function c_delayShipmentsJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('select_zsporder');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	//�ӳٷ����б� --֪ͨ����
	function c_informShipments() {
		$orderttype = $_GET['orderType'];
		$orderId = $_GET['id'];
		$orderInfo = $this->service->findOrderInfo($orderId, $orderttype);
		foreach ($orderInfo[0] as $key => $val) {
			$this->assign($key, $val);
		}
		switch ($orderInfo[0]['tablename']) {
			case "oa_sale_order" :
				$this->assign("tablenameH", "���ۺ�ͬ");
				break;
			case "oa_sale_service" :
				$this->assign("tablenameH", "�����ͬ");
				break;
			case "oa_sale_lease" :
				$this->assign("tablenameH", "���޺�ͬ");
				break;
			case "oa_sale_rdproject" :
				$this->assign("tablenameH", "�з���ͬ");
				break;
		}
		$this->assign("orderid", $orderId);
		$this->display('informShipments');
	}
	//�ӳٷ����б� --֪ͨ���� ��������
	function c_informS() {
		$inform = $_POST["inform"];
		$flag = $this->service->inform_d($inform);
		msg("����֪ͨ�ѷ��ͣ�");
	}
	/**
	 * ѡ��ht
	 */
	function c_selectOrder() {
		$this->assign('showButton', $_GET['showButton']);
		$this->assign('showcheckbox', $_GET['showcheckbox']);
		$this->assign('checkIds', $_GET['checkIds']);
		$this->view('selectorder');
	}

	/**
	 * �߼�����
	 */
	function c_search() {
		$this->assign("gridName", $_GET['gridName']);
		$this->display('search');
	}
	/**
	 * �ҵĺ�ͬ--������Ϣ
	 */
	function c_toMyOrderInfo() {
		$this->assign('user', $_SESSION['USER_ID']);
		$this->display("myorderinfo");
	}
	//�ҵĺ�ͬ--��������ϢPageJson
	function c_myOrderJson() {
		//		if(isset($this->service->this_limit['��������'])){
		//			$_POST['areaCode'] = $this->service->this_limit['��������'];
		//		}
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//		 $export = isset ( $service->this_limit ['������ͬ����'] ) ? $service->this_limit ['������ͬ����'] : null;

		$rows = $service->pageBySqlId('select_orderinfo');
		//����������
		//$rows = $service->projectProcess_d($rows);
		$rows = $service->getInvoiceAndIncome_d($rows);
		//		$rows = $this->sconfig->md5Rows ( $rows );
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		//		foreach($rows as $k => $v){
		//             $rows[$k]['exportOrder'] = $export;
		//		   }
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	function c_orderContractInfoJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d();
		if ($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']) {
			$rows = $this->service->filterContractMoney_d($rows, $this->service->tbl_name);
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/***************************************************************************************************/
	/**
	 * �ҵĺ�ͬ--δ�ύ�����ĺ�ͬ��ҳ��
	 */
	function c_toWtjmain() {
		$this->display("myorder-wtjmain");
	}
	/**
	 * �ҵĺ�ͬ--δ�ύ�����ĺ�ͬ����
	 */
	function c_toWtjmenu() {
		$this->display("myorder-wtjmenu");
	}
	/**
	 * �ҵĺ�ͬ--��������ҳ��
	 */
	function c_toWspmain() {
		$this->display("myorder-wspmain");
	}
	/**
	 * �ҵĺ�ͬ---��������ͬ����
	 */
	function c_toWspmenu() {
		$this->display("myorder-wspmenu");
	}

	/**
	 * �ҵĺ�ͬ--��ִ����ҳ��
	 */
	function c_toZzxMain() {
		$this->display("myorder-zzxmain");
	}
	/**
	 * �ҵĺ�ͬ--��ִ�к�ͬ����
	 */
	function c_toZzxMenu() {
		$this->display("myorder-zzxmenu");
	}
	/**
	 * �ҵĺ�ͬ--����ɺ�ͬ��ҳ��
	 */
	function c_toYwcMain() {
		$this->display("myorder-ywcmain");
	}
	/**
	 * �ҵĺ�ͬ--����ɺ�ͬ����
	 */
	function c_toYwcMenu() {
		$this->display("myorder-ywcmenu");
	}
	/**
	 * �ҵĺ�ͬ--�ѹرպ�ͬ��ҳ��
	 */
	function c_toYwgMain() {
		$this->display("myorder-ywgmain");
	}
	/**
	 * �ҵĺ�ͬ--�ѹرպ�ͬ����
	 */
	function c_toYwgMenu() {
		$this->display("myorder-ywgmenu");
	}

	/**
	 * ��ͬ���
	 */
	function c_toSplit() {
		$this->permCheck(); //��ȫУ��
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		//��Ⱦ�ӱ�
		$rows = $this->service->initEdit($rows);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '��') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstateNo', 'checked');
			};
		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign("pId", $_GET['id']);
		$this->display("split");
	}

	/**
	 * תΪ��ʽ��ͬ
	 */
	function c_toBecomeOrder() {
		$this->permCheck(); //��ȫУ��
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
		//��Ⱦ�ӱ�
		$rows = $this->service->becomeEdit($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign("pId", $_GET['id']);
		$this->display("becomeorder");
	}

	/**
	* by maizp
	* ת��ʽ��ͬ
	*/
	function c_become($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$orderCodeDao = new model_common_codeRule();
		if ($object['orderInput'] == "1") {
			$object['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $object['customerId']);
			$this->service->becomeEdit_d($object);
			msgRF('��תΪ��ʽ��ͬ');
		} else
			if ($object['orderInput'] == "0") {
				$this->service->becomeEdit_d($object);
				msgRF('��תΪ��ʽ��ͬ');
			} else {
				msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
			}
	}
	/***************************************************************************************************/
	/*
	 * ��ת����ͬ����
	 */
	function c_page() {
		$this->display('list');
	}
	/**
	 * License ����
	 */
	function c_License() {
		$this->display("License");
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->assign('orderInput', ORDER_INPUT);
		$this->assign('prinvipalName', $_SESSION['USERNAME']);
		$this->assign('prinvipalId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('saleman', $_SESSION['USERNAME']);
		$this->assign('salemanId', $_SESSION['USER_ID']);
		$this->assign('createTime', date('Y-m-d'));
		$id = $_GET['id'];
		if ($id) {
			$this->permCheck($id, projectmanagent_chance_chance);
			$condiction = array (
				"id" => $id
			);
			$chanceDao = new model_projectmanagent_chance_chance();
			$rows = $chanceDao->get_d($id);

			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->customerProCity($rows['customerId']); //���ݿͻ���ȡ �ͻ���ʡ����Ϣ
			$this->showDatadicts(array (
				'customerType' => 'KHLX'
			), $rows['customerType']);
			$this->showDatadicts(array (
				'orderNature' => 'XSHTSX'
			), $rows['orderNature']);
			$orderChance = new model_projectmanagent_order_orderequ();
			$chanceequ = $orderChance->ChanceOrderEqu($rows['chanceequ']);
			$orderCus = new model_projectmanagent_order_customizelist();
			$customizelist = $orderCus->changeCusOrder($rows['customizelist']);

			$this->assign('chance', $chanceequ[0]);
			$this->assign('productNumber', $chanceequ[1]);
			$this->assign('customizelist', $customizelist[0]);
			$this->assign('PreNum', $customizelist[1]);
			$this->assign('chanceId', $id);
			$this->display('add-chance');
		} else {
			$this->assign('chanceId', $id);
			$this->display('add');
		}
	}
	/**
	 * ���ݿͻ�ID ��ȡ �ͻ���ʡ����Ϣ
	 */
	function customerProCity($customerId) {
		$dao = new model_customer_customer_customer();
		$proId = $dao->find(array (
			"id" => $customerId
		), null, "ProvId"); //ʡ��ID
		$cityId = $dao->find(array (
			"id" => $customerId
		), null, "CityId"); //����ID
		$this->assign("orderProvinceId", $proId['ProvId']);
		$this->assign("orderCityId", $cityId['CityId']);
	}
	/**
	 * �ҵ���ĿTab ҳ
	 */
	function c_myOrderTab() {
		$this->display('myorder-tab');
	}
	/**
	 * ���к�ͬ��Ϣ
	 */
	function c_orderInfo() {
		$this->display('orderinfo');
	}
	/**
	 * δ�����ĺ�ͬ
	 */
	function c_myOrderWtjsp() {
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-wtjsp');
	}
	/**
	 * �����еĺ�ͬ
	 */
	function c_myOrderWtj() {
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-wtj');
	}

	/**
	 * δ�����ĺ�ͬ
	 */
	function c_myorderWsp() {
		$this->display('myorder-wsp');
	}
	/**
	 * �����еĺ�ͬ
	 */
	function c_myOrderSpz() {
		$this->display('myorder-spz');
	}
	/**
	 * ִ���еĺ�ͬ
	 */
	function c_myOrderZxz() {
		$this->display('myorder-zxz');
	}
	/**
	 * ����ɵĺ�ͬ
	 */
	function c_myOrderYwc() {
		$this->display('myorder-ywc');
	}
	/**
	 * �ѹرյĺ�ͬ
	 */
	function c_myOrderYgb() {
		$this->display('myorder-Ygb');
	}
	/**
	 * �ҵĺ�ͬ---��������ͬ
	 */
	function c_myApplyOrder() {
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->display('myorder-apply');
	}
	/**
	 * �ҵĺ�ͬ--�ѹرպ�ͬ
	 */
	function c_myChargeOrder() {

		$this->assign('prinvipalId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-charge');
	}

	/**
	 * �ҵĺ�ͬ--��ִ�к�ͬ
	 */
	function c_myExecuteOrder() {
		$this->assign('executeId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-execute');
	}
	/**
	 * �ҵĺ�ͬ--����ɺ�ͬ
	 */
	function c_myCompleteOrder() {
		$this->assign('executeId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-complete');
	}
	/**
	 * ָ����ͬ������ҳ��
	 */
	function c_ChargeMan() {
		$this->assign('id', $_GET['id']);
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('chargeman');
	}

	/**
	 * ָ����ִͬ����ҳ��
	 */
	function c_ExecuteMan() {
		$this->assign('id', $_GET['id']);
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('executeman');
	}

	/**
	 * �ҵ����� - tab
	 */
	function c_auditTab() {
		$this->display('audittab');
	}

	/**
	 * �ҵ����� �� δ����ҳ��
	 */
	function c_toAuditNo() {
		$this->display('auditno');
	}

	/**
	 * �ҵ����� �� ��������ҳ��
	 */
	function c_toAuditYes() {
		$this->display('audityes');
	}
	function c_toAuditView() {
		$this->display('auditview');
	}
	/**δ�����ı����ͬ�б�
	 *author can
	 *2011-6-1
	 */
	function c_toChangeAuditNO() {
		$this->display('change-auditno');
	}

	/**�������ı����ͬ�б�
	 *author can
	 *2011-6-1
	 */
	function c_toChangeAuditYes() {
		$this->display('change-audityes');
	}

	/**
	 * ��ͬǩ����ͬҳ��
	 */
	function c_toSales() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->assign('userName', $_SESSION['USERNAME']);
		$this->assign('orderId', $_GET['id']);

		$rows = $this->service->get_d($_GET['id']);
		$row = $this->service->salesListByOrder($rows);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}

		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $row['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $row['invoiceType']);

		$this->display('sales-add');
	}
	/**
	 * �ϲ���ʱ��ͬ ��ȡ��ͬ����
	 */
	function c_ajaxList() {
		$rows = $this->service->get_d($_POST['id']);
		$rows = util_jsonUtil :: encode($rows);
		echo $rows;
	}

	/**
	 * ��ת����ͬ����Tabҳ
	 */
	function c_toLockStockTab() {
		$service = $this->service;
		$rows = $service->get_d($_GET['id']);
		$this->assign('orderId', $_GET['id']);
		$rows = $service->showDetaiInfo($rows);
		$this->assign("orderId", $_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('lockstock-tab');
	}

	/**
	 * ��ת��ͬ������ҳ��
	 */
	function c_toLokStockByOrder($id) {
		$service = $this->service;
		$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInfo($rows);
		$this->assign("id", $_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		//�õ�Ĭ�ϲֿ����Ϣ(���òֿ�ӿ�)
		$stockInfoDao = new model_stock_stockinfo_stockinfo();
		$stockInfo = $stockInfoDao->getDefaultStockInfo();
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['id']);
		$this->display('lockstock');

	}

	/**
	 * ѡ��Ʊ����
	 */
	function c_toInvoice() {
		$rows = $this->service->get_d($_GET['id'], 'none');
		$contractRows = $this->service->getContractByOrderCode($rows['orderCode'], 'ExaStatus');
		if (empty ($contractRows)) {
			$this->assign('contractCode', null);
			$this->assign('contractId', null);
			$this->assign('contractName', null);
		} else {
			$this->assign('contractCode', $contractRows[0]['contNumber']);
			$this->assign('contractId', $contractRows[0]['id']);
			$this->assign('contractName', $contractRows[0]['contName']);
		}
		$this->assignFunc($rows);
		$this->display('toinvoice');
	}

	/**
	 * ��ͬ��ǩ����ͬ��Ϣ
	 */
	function c_toSalesInfo() {
		$this->assign('id', $_GET['id']);
		$this->display('salesinfo');
	}

	/**********************��Ʊ�տ�Ȩ�޲���*********************************/
	/**
	 * ��Ʊtab
	 */
	function c_toInvoiceTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['���ۺ�ͬ��Ʊtab'])) {
			$url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
		}
	}

	/**
	 * ��Ʊ����tab
	 */
	function c_toInvoiceApplyTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['���ۺ�ͬ��Ʊtab'])) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
		}
	}

	/**
	 * ����tab
	 */
	function c_toIncomeTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['���ۺ�ͬ����tab'])) {
			$url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա';
		}
	}

	/**********************��Ʊ�տ�Ȩ�޲���*********************************/
	/****************************************************************************************/

	/**
	 * �رպ�ͬҳ��
	 */
	function c_CloseOrder() {
		$row = $this->service->get_d($_GET['id']);
		foreach ($row as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('userName', $_SESSION['USERNAME']);
		$this->assign('dateTime', date('Y-m-d'));
		$this->display('closeorder');
	}
	/**
	 * �رպ�ͬ
	 */
	function c_close($isEditInfo = false) {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$object = $_POST[$this->objName];
		$id = $this->service->close_d($object, $isEditInfo);
		if ($id && $_GET['actType'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
		} else {
			msg('�رճɹ���');
		}
	}
	/**
	 * ���ִ����/������
	 */
	function c_Execute() {
		$id = $this->service->ExecuteEdit_d($_POST[$this->objName], true);
		if ($id) {
			msgRF('�޸ĳɹ���');
		}
	}

	/**
	 * ǩ����ͬ---��Ӻ�ͬ��Ϣ
	 */
	function c_salesAdd() {
		$id = $this->service->salesAdd_d($_POST[$this->objName]);

		if ($id) {
			showmsg('����ɹ���', '?model=contract_sales_sales&action=myApplyTab');
		}
	}

	/**
	 * �����������
	 */
	function c_add() {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$pId = isset ($_GET['pId']) ? $_GET['pId'] : null;
		$orderInfo = $_POST[$this->objName];
		//		if ($orderInfo ['orderInput'] == "1") {
		//			$orderCodeDao = new model_common_codeRule ();
		//			if ($orderInfo ['sign'] == "��") {
		//				$orderInfo ['orderTempCode'] = $orderCodeDao->contractCode ( "oa_sale_order", $orderInfo ['customerId'] );
		//				$orderInfo ['orderTempCode'] = "LS".$orderInfo ['orderTempCode'];
		//			} else if ($orderInfo ['sign'] == "��") {
		//				$orderInfo ['orderCode'] = $orderCodeDao->contractCode ( "oa_sale_order", $orderInfo ['customerId'] );
		//			}
		//			$id = $this->service->add_d ( $orderInfo );
		//		} else if ($orderInfo ['orderInput'] == "0") {
		//			if(!empty($orderInfo['orderTempCode'])){
		//				$orderInfo['orderTempCode'] = $orderInfo['orderTempCode'];
		//			}
		//			$id = $this->service->add_d ( $orderInfo );
		//		} else {
		//			msgGo ( '���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ' );
		//		}
		$id = $this->service->add_d($orderInfo);
		//�ж��Ƿ�ֱ���ύ����
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_index2.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id && $actType == "goOn") {

				msgGO('�������');

			} else
				if ($id && $pId) {
					$this->service->thisUpdate_d($pId); //�ı��ֺ�ͬ��״̬Ϊ�Ѳ��

					msgRF('��ӳɹ�');
				} else
					if ($id) {
						msgGo('��ӳɹ���', '?model=projectmanagent_order_order&action=toAdd');
					}
		//$this->listDataDict ();
	}

	/**
	 * �̻�ת��ͬ����
	 */
	function c_chanceAdd() {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$pId = isset ($_GET['pId']) ? $_GET['pId'] : null;
		$orderInfo = $_POST[$this->objName];
		if ($orderInfo['orderInput'] == "1") {
			$orderCodeDao = new model_common_codeRule();
			if ($orderInfo['sign'] == "��") {
				$orderInfo['orderTempCode'] = $orderCodeDao->contractCode("oa_sale_order", $orderInfo['customerId']);
			} else
				if ($orderInfo['sign'] == "��") {
					$orderInfo['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $orderInfo['customerId']);
				}
			$id = $this->service->add_d($orderInfo);
		} else
			if ($orderInfo['orderInput'] == "0") {
				$id = $this->service->add_d($orderInfo);
			} else {
				msgGo('���ҹ���Աȷ�����ú�ͬ�����"ORDER_INPUT"ֵ�Ƿ���ȷ');
			}
		//�ж��Ƿ�ֱ���ύ����
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id) {
				msgRF('��ӳɹ���');
			}
		$this->listDataDict();
	}

	/**
	 * ��ͬ�鿴ҳ��
	 */
	function c_toViewTab() {
		//		$this->permCheck (); //��ȫУ��
		$this->assign('id', $_GET['id']);
		$isTemp = $this->service->isTemp($_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('orderCode', $rows['orderCode']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/**���������ͬ�Ĳ鿴ҳ��
	 *author can
	 *2011-6-1
	 */
	function c_toReadTab() {
		$this->permCheck();
		$this->assign('id', $_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$originalKey = $this->md5Row($rows['originalId']);
		$this->assign('orderCode', $rows['orderCode']);
		$this->assign('originalId', $rows['originalId']);
		$this->assign('originKey', $originalKey);
		$this->display('read-tab');
	}

	/**
	 * ��ͬ�鿴Tab---�ر���Ϣ
	 */
	function c_toCloseInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['state'] == '3' || $rows['state'] == '9') {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeinfo');

		} else {
			echo '<span>���������Ϣ</span>';
		}
	}
	/***************************************************************************************************************/
	/**
	 * ��дint
	 */
	function c_init() {
		//		$this->permCheck (); //��ȫУ��
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);
		if ($perm == 'view') {
			$rows['orderequ'] = $this->service->filterWithoutField('�����豸���', $rows['orderequ'], 'list', array (
				'price',
				'money'
			));
			//Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
			if ($rows['areaPrincipalId'] != $_SESSION['USER_ID'] && $rows['createId'] != $_SESSION['USER_ID'] && $rows['prinvipalId'] != $_SESSION['USER_ID']) {
				$rows = $this->service->filterWithoutField('���ۺ�ͬ���', $rows, 'form', array (
					'orderMoney',
					'orderTempMoney'
				));
				$rows['orderequ'] = $this->service->filterWithoutField('�����豸���', $rows['orderequ'], 'list', array (
					'price',
					'money'
				));
				$rows['linkman'] = $this->service->filterWithoutField('������ϵ�˿���', $rows['linkman'], 'list', array (
					'Email',
					'telephone'
				));
			}

			//����
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
			//��ͬ�ı�Ȩ�������ۺ�ͬ���Ȩ��һ�� add by chengl 2011-10-15

			if ($rows['areaPrincipalId'] != $_SESSION['USER_ID'] && $rows['createId'] != $_SESSION['USER_ID'] && $rows['prinvipalId'] != $_SESSION['USER_ID']) {
				if (!empty ($this->service->this_limit['���ۺ�ͬ���'])) {
					$rows['file2'] = $this->service->getFilesByObjId($rows['id'], false, 'oa_sale_order2');
				} else {
					$rows['file2'] = "";
				}
			} else {
				$rows['file2'] = $this->service->getFilesByObjId($rows['id'], false, 'oa_sale_order2');
			}
			$rows = $this->service->initView($rows);

			foreach ($rows as $key => $val) {
				$this->show->assign($key, $val);
			}

			if ($rows['sign'] == '��') {
				$this->assign('sign', '��');
			} else
				if ($rows['sign'] == '��') {
					$this->assign('sign', '��');
				}

			if ($rows['orderstate'] == '���ύ') {
				$this->assign('orderstate', '���ύ');
			} else
				if ($rows['orderstate'] == '���õ�') {
					$this->assign('orderstate', '���õ�');
				}
			if ($rows['shipCondition'] == '0') {
				$this->assign('shipCondition', '��������');
			} else
				if ($rows['shipCondition'] == '1') {
					$this->assign('shipCondition', '֪ͨ����');
				} else {
					$this->assign('shipCondition', '');
				}
			$orderTempCode = explode(',', $rows['orderTempCode']);
			$this->assign('orderTempCode', $this->service->TempOrderView($orderTempCode, $_GET['skey']));
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		} else {

			//����
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
			//��ͬ�ı�Ȩ�������ۺ�ͬ���Ȩ��һ�� add by chengl 2011-10-15
			$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
			$rows = $this->service->initEdit($rows);

			foreach ($rows as $key => $val) {
				$this->show->assign($key, $val);
			}

			if ($perm == 'signIn') {

				if ($rows['sign'] == '��') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('signNo', 'checked');
					}
				if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstateNo', 'checked');
					}
				$this->assign('signName', $_SESSION['USERNAME']);
				$this->assign('signNameId', $_SESSION['USER_ID']);
				$this->assign('signDate', date('Y-m-d'));
				$this->showDatadicts(array (
					'orderNature' => 'XSHTSX'
				), $rows['orderNature']);
				$this->showDatadicts(array (
					'customerType' => 'KHLX'
				), $rows['customerType']);
				$this->showDatadicts(array (
					'invoiceType' => 'FPLX'
				), $rows['invoiceType']);
				$this->display('signin');
			} else {
				if ($rows['sign'] == '��') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '��') {
						$this->assign('signNo', 'checked');
					}
				if ($rows['orderstate'] == '���ύ') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '���õ�') {
						$this->assign('orderstateNo', 'checked');
					}
				$this->showDatadicts(array (
					'customerType' => 'KHLX'
				), $rows['customerType']);
				$this->showDatadicts(array (
					'invoiceType' => 'FPLX'
				), $rows['invoiceType']);
				$this->showDatadicts(array (
					'orderNature' => 'XSHTSX'
				), $rows['orderNature']);
				$this->display('edit');
			}
		}
	}

	/**
	 * �����鿴ҳ��
	 */
	function c_toViewForAudit() {
		//		$this->permCheck (); //��ȫУ��
		$rows = $this->service->get_d($_GET['id']);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], false, 'oa_sale_order2');
		$rows = $this->service->initView($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}

		if ($rows['sign'] == '��') {
			$this->assign('sign', '��');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('sign', '��');
			}

		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstate', '���ύ');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstate', '���õ�');
			}
		if ($rows['shipCondition'] == '0') {
			$this->assign('shipCondition', '��������');
		} else
			if ($rows['shipCondition'] == '1') {
				$this->assign('shipCondition', '֪ͨ����');
			} else {
				$this->assign('shipCondition', '');
			}
		$orderTempCode = explode(',', $rows['orderTempCode']);
		$this->assign('orderTempCode', $this->service->TempOrderView($orderTempCode, $_GET['skey']));
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view');
	}

	/**
	 * �޸�����
	 */
	function c_productEdit() {
		if (!isset ($this->service->this_limit['�����޸�']) || $this->service->this_limit['�����޸�'] != 1) {
			echo "û�������޸ĵ�Ȩ�ޣ�����ϵOA����Ա��ͨ";
			exit ();
		}
		$this->permCheck(); //��ȫУ��
		$rows = $this->service->get_d($_GET['id']);
		//		foreach ($rows['orderequ'] as $k => $v){
		//        	 if($v['purchasedNum'] > 0 || $v['issuedShipNum'] > 0){
		//                   echo "�ú�ͬ��ִ�з�����ɹ���������ѡ���������޸�����";
		//                   exit();
		//        	 }
		//        }
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows = $this->service->editProduct($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '��') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstateNo', 'checked');
			}

		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('productedit');
	}
	/**��ת�����ۺ�ͬ���ҳ��
	 *author can
	 *2011-6-1
	 */
	function c_toChange() {
		$this->permCheck(); //��ȫУ��
		$changeLogDao = new model_common_changeLog('order');
		if ($changeLogDao->isChanging($_GET['id'])) {
			msgGo("�ú�ͬ���ڱ�������У��޷����.");
		}
		$changer = isset ($_GET['changer']) ? $_GET['changer'] : null;
		$changeC = isset ($_GET['changeC']) ? $_GET['changeC'] : null;
		$rows = $this->service->get_d($_GET['id']);
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '��') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstateNo', 'checked');
			};

		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->showDatadicts(array (
			'orderNature' => 'XSHTSX'
		), $rows['orderNature']);
		$this->assign('changer', $changer);
		$this->assign('changeC', $changeC);
		$this->display('change');

	}

	/**������ۺ�ͬ
	 *author can
	 *2011-6-1
	 */
	function c_change() {
		try {
			$changer = isset ($_GET['changer']) ? $_GET['changer'] : null;
			$changeC = isset ($_GET['changeC']) ? $_GET['changeC'] : null;
			$orderInfo = $_POST['order'];
			$isDel = "0";
			foreach ($orderInfo['orderequ'] as $key => $val) {
				if (empty ($val['productName'])) {
					unset ($orderInfo['orderequ'][$key]);
				}
				if (array_key_exists("isDel", $val)) {
					//�ж������Ƿ����´﷢�����´�ɹ����´�����
					$fsql = "select count(id) as isExe from oa_sale_order_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=" . $val['oldId'] . " ";
					$isExeArr = $this->service->_db->getArray($fsql);
					$isExe += $isExeArr[0]['isExe'];
					$isDel = "1";
					$purchaseArr[] = $this->service->purchaseMail($val['oldId']);
				}
			}
            //���ɾ�����������´�ɹ��ģ����ʼ����ɹ���
            if(!empty($purchaseArr[0])){
               //��ȡĬ�Ϸ�����
		       include (WEB_TOR."model/common/mailConfig.php");
		       $toMailId = $mailUser['contractChangepurchase']['sendUserId'];
		       $emailDao = new model_common_mail();
		       if(empty($orderInfo['orderCode'])){
		       	  $orderCode = $orderInfo['orderTempCode'];
		       }else{
		       	  $orderCode = $orderInfo['orderCode'];
		       }
		       //�����ˣ����������䣬title,�ռ���,������Ϣ
			   $emailInfo = $emailDao->contractChangepurchaseMail($_SESSION['USERNAME'],$_SESSION['EMAIL'],"���۱����������",$toMailId,$purchaseArr,$orderCode);
            }
			foreach ($orderInfo['orderequTemp'] as $key => $val) {
				if (empty ($val['productName'])) {
					unset ($orderInfo['orderequTemp'][$key]);
				}
			}
			if ($isDel == "1" && $isExe != "0") {
				$formName = "���ۺ�ͬ�����ɾ���ϣ�";
				//	         $formName="���۶�������";
			} else {
				$formName = "���۶�������";
			}
			$id = $this->service->change_d($orderInfo);
			if ($changer == "changer") {
				if ($changeC == "changeC") {
					echo "<script>this.location='controller/projectmanagent/order/ewf_mychangeC_index.php?actTo=ewfSelect&billId=" . $id . "&formName=" . $formName . "'</script>";
				} else {
					echo "<script>this.location='controller/projectmanagent/order/ewf_mychange_index.php?actTo=ewfSelect&billId=" . $id . "&formName=" . $formName . "'</script>";
				}
			} else {
				echo "<script>this.location='controller/projectmanagent/order/ewf_change_index.php?actTo=ewfSelect&billId=" . $id . "&formName=" . $formName . "'</script>";
			}
		} catch (Exception $e) {
			msgBack2("���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage());
		}
	}

	/**
	 * add by can 2011-06-01
	 * ȷ�Ϻ�ͬ���������ת��δ�����ĺ�ͬ�б�ҳ
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (!empty ($_GET['spid'])) {
			$this->service->confirmChange($_GET['spid']);
		}
		$urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if ($urlType) {
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		} else {
			echo "<script>this.location='?model=projectmanagent_order_order&action=allAuditingList'</script>";
		}
	}
	/**
	 * ȡ���������
	 */
	function c_cancelBecome() {
		$orderId = $_GET['id'];
		$sql = "update oa_sale_order set isBecome = 0 where id = $orderId";
		$this->service->query($sql);
		return $orderId;
	}
	/**
	 * ����ͨ�������ʼ��������б�
	 */
	function c_configOrder() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		$objId = $folowInfo['objId'];
		$this->service->configOrder_d($objId);
		$contract = $this->service->get_d($objId);
		if ($contract['ExaStatus'] == "���") {
			$toorderDao = new model_projectmanagent_borrow_toorder();
			$toorderDao->findLoan($objId, "order", "add");
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * δ����Json
	 */
	function c_pageJsonAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���۶�������';
		$rows = $service->pageBySqlId('auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ������Json
	 */
	function c_pageJsonAuditYes() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���۶�������';
		$rows = $service->pageBySqlId('audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**δ�����ı����ͬ
	 *author can
	 *2011-6-1
	 */
	function c_changeAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���۶�������';
		$rows = $service->pageBySqlId('change_auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**�������ı����ͬ
	 *author can
	 *2011-6-1
	 */
	function c_changeAuditYes() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '���۶�������';
		$rows = $service->pageBySqlId('change_audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ͬ��ǩ����ͬ��PageJson
	 */
	function c_salesInfoPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$service->searchArr['projectId'] = $_GET['id'];
		$rows = $service->pageBySqlId('select_sales');

		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$orderCodeDao = new model_common_codeRule();
		if ($object['sign'] == "��" && $object['orderCode'] == '') {
			$object['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $object['customerId']);
		}
		if ($this->service->edit_d($object, $isEditInfo)) {
			msgRF('�༭�ɹ�');
		}
	}
	/**
	 * �����޸�
	 */
	function c_proedit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$id = $this->service->proedit_d($object, $isEditInfo);

		if ($id) {
			$this->service->updateOrderShipStatus_d($id);
			msgRF('�༭�ɹ�');
		}
	}

	/**
	 * ����ɾ������
	 */
	function c_deletesInfo() {
		$deleteId = isset ($_GET['id']) ? $_GET['id'] : exit ();
		$delete = $this->service->deletesInfo_d($deleteId);
		if ($delete) {
			msg('ɾ���ɹ�');
		}
	}
	/******************************************��ͬǩ��*********************************************************/
	/**
	 * ��ͬǩ��tabҳ
	 */
	function c_toSinginTab() {
		$this->display("signintab");
	}
	/**
	 * δǩ�պ�ͬ
	 */
	function c_toSignIn() {
		$this->display("signinlist");
	}
	/**
	 * ��ǩ�պ�ͬ
	 */
	function c_comSignin() {
		$this->display("comsignin");
	}
	/**
	 * ��ͬǩ��
	 */
	function c_toSign() {
		$this->permCheck(); //��ȫУ��
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);

		//��Ⱦ�ӱ�

		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '��') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstateNo', 'checked');
			};
		$this->assign('signName', $_SESSION['USERNAME']);
		$this->assign('signNameId', $_SESSION['USER_ID']);
		$this->assign('signDate', day_date);

		$this->showDatadicts(array (
			'customerType' => 'KHLX'
		), $rows['customerType']);
		$this->showDatadicts(array (
			'invoiceType' => 'FPLX'
		), $rows['invoiceType']);
		$this->display('signin');
	}

	/**
	 * ��ͬǩ��
	 */
	function c_signInVerify($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->signin_d($object, $isEditInfo)) {
			msgRF('ǩ����ɣ�');
		}
	}

	/************************************************************************************************/
	/**
	 * @ ajax�ж���
	 * ��ʱ��ͬ��
	 */
	function c_ajaxOrderTempCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderTempCode']) ? $_GET['orderTempCode'] : false;
		$projectNameLS = "LS" . $projectName;
		$searchArr = array (
			"ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
		);

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	function c_ajaxOrderCode() {
		$service = $this->service;
		$projectName = isset ($_GET['orderCode']) ? $_GET['orderCode'] : false;
		$projectNameLS = "LS" . $projectName;
		$searchArr = array (
			"ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
		);

		$isRepeat = $service->isRepeatAll($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/*
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxdeletesOrder() {
		//$this->permDelCheck ();
		$orderId = $_POST['id'];
		$orderType = $_POST['type'];
		try {
			//�����Ƿ��� ������ת���۵Ĺ������� ����ɾ��
			$dao = new model_projectmanagent_borrow_toorder();
			$dao->getRelOrderequ($orderId, $orderType);
			$del = $this->service->deletes_d($orderId);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}
	/*********************************��ͬ����*********************************************************/
	function c_toShipmentsList() {
		$this->display("shipments-list");
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_shipmentsPageJson() {
		$rateDao = new model_stock_outplan_contractrate();
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//		$service->sort = 'c.ExaDT';
		$rows = $service->pageBySqlId('select_shipments');
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		//�ж� �������ʱ����ڵ���10����Ѻ�ɫ������� ȡ��
		foreach ($rows as $k => $v) {
			$ExaDT = $v['ExaDT'];
			$newDate = date('Y-m-d');
			$diff = (strtotime($newDate) - strtotime($ExaDT)) / 86400;

			if ($diff >= 10) {
				$orderId = $v['orgid'];
				switch ($v['tablename']) {
					case "oa_sale_order" :
						$sql = "update oa_sale_order set isBecome = 0 where id = $orderId";
						break;
					case "oa_sale_service" :
						$sql = "update oa_sale_service set isBecome = 0 where id = $orderId";
						break;
					case "oa_sale_lease" :
						$sql = "update oa_sale_lease set isBecome = 0 where id = $orderId";
						break;
					case "oa_sale_rdproject" :
						$sql = "update oa_sale_rdproject set isBecome = 0 where id = $orderId";
						break;
				}
				$this->service->query($sql);
			}
		}
		//����������ȱ�ע
		$orderIdArr = array ();
		foreach ($rows as $key => $val) {
			$orderIdArr[$key] = $rows[$key]['orgid'];
		}
		$orderIdStr = implode(',', $orderIdArr);
		$rateDao->searchArr['relDocIdArr'] = $orderIdStr;
		$rateDao->asc = false;
		$rateArr = $rateDao->list_d();
		if (is_array($rows) && count($rows) > 0) {
			foreach ($rows as $key => $val) {
				$rows[$key]['rate'] = "";
				if (is_array($rateArr) && count($rateArr)) {
					foreach ($rateArr as $index => $value) {
						if ($rows[$key]['orgid'] == $rateArr[$index]['relDocId'] && $rows[$key]['tablename'] == $rateArr[$index]['relDocType']) {
							$rows[$key]['rate'] = $rateArr[$index]['keyword'];
						}
					}
				}
			}
		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	function c_toShippedList() {
		$this->display("shipped-list");
	}
	/**************************��ͬ��Ϣ ����ͼ�� pagejson*********************************************/

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonMyProject() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->pageBySqlId('select_zsporder');
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**************************�ر���������**************************************/
	/**
	 * �رպ�ͬ�����鿴ҳ
	 */
	function c_toCloseView() {

		$rows = $this->service->get_d($_GET['id']);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('view', $this->service->closeOrderView($_GET['id']));
		$this->display('closeview');
	}

	/**
	 * �ر�����tab
	 */
	function c_toCloseAuditTab() {
		$this->display('closeaudittab');
	}

	/**
	 * δ�����Ĺر�
	 */
	function c_toCloseAuditUndo() {
		$this->display('closeauditundo');
	}
	/******************************************************************************/
	/***************************pageJson******************************************/
	/**
	 * δ����Json
	 */
	function c_jsonCloseAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '�����쳣�ر�����';
		$rows = $service->pageBySqlId('auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �������Ĺر�
	 */
	function c_toCloseAuditDone() {
		$this->display('closeauditdone');
	}

	/**
	 * ������Json
	 */
	function c_closeAuditYesJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '�����쳣�ر�����';
		$rows = $service->pageBySqlId('audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �´�ɹ��ƻ�
	 */
	function c_purchasePlan() {
		$orderId = $_GET['orderId'];
		$orderpurchase = $this->service->get_d($orderId);
		$contpurchaseDao = new model_purchase_external_contpurchase();
		foreach ($orderpurchase as $key => $val) {
			if ($key == 'orderequ') {
				$detail = $contpurchaseDao->showAddList($orderpurchase['orderequ'], false);
				$this->assign('detail', $detail);
			}
		}
		$sendTime = day_date;
		$this->assign('sendTime', $sendTime);
		$this->assign('sendName', $_SESSION['USERNAME']);
		$this->show->display("purchase_external_external-cont-add");
	}

	/**
	 * ����Ȩ�޺ϲ�
	 */
	function regionMerge($privlimit, $arealimit) {
		$str = null;
		if (!empty ($privlimit) || !empty ($arealimit)) {
			if ($privlimit) {
				$str .= $privlimit;
			}
			if (!empty ($str) && !empty ($arealimit)) {
				$str .= ',' . $arealimit;
			} else {
				$str .= $arealimit;
			}
			return $str;
		} else {
			return null;
		}
	}

	/**
	 * ��ͬ��Ϣ--δ������ͬJson
	 */
	function c_wspOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : null;
		//		$export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_zsporder');
			$rows = $service->filterContractMoney_d($rows);
			$rows = $this->sconfig->md5Rows($rows, "orgid");
			//			foreach($rows as $k => $v){
			//             $rows[$k]['exportOrder'] = $export;
			//		   }
		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ͬ��Ϣ--��������ͬJson
	 */
	function c_zspOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : null;
		//		$export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_zsporder');
			$rows = $service->filterContractMoney_d($rows);
			$rows = $this->sconfig->md5Rows($rows, "orgid");
			//			foreach($rows as $k => $v){
			//             $rows[$k]['exportOrder'] = $export;
			//		   }
		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ͬ��Ϣ--��ִ�к�ͬJson
	 */
	function c_zxzOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : null;
		$comlimit = isset ($this->service->this_limit['�ı��ͬ״̬']) ? $this->service->this_limit['�ı��ͬ״̬'] : null;
		//		$export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_zsporder');
			$rows = $service->getInvoiceAndIncome_d($rows);
			$rows = $this->sconfig->md5Rows($rows, "orgid");
			foreach ($rows as $k => $v) {
				if ($comlimit == '1') {
					$rows[$k]['com'] = "1";
				} else {
					$rows[$k]['com'] = "0";
				}
			}
			//			foreach($rows as $k => $v){
			//             $rows[$k]['exportOrder'] = $export;
			//		   }

		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ͬ��Ϣ--�ѹرպ�ͬJson
	 */
	function c_ygbOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['��������']) ? $this->service->this_limit['��������'] : null;
		//		$export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->pageBySqlId('select_zsporder');
			$rows = $service->getInvoiceAndIncome_d($rows);
			$rows = $this->sconfig->md5Rows($rows, "orgid");
			//			foreach($rows as $k => $v){
			//             $rows[$k]['exportOrder'] = $export;
			//		   }
		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ͬ��Ϣ--δǩ�պ�ͬJson
	 */
	function c_SignInJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('select_signIn');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$rows = $service->getRowsallMoney_d($rows, "select_orderinfo", $_SESSION['USER_ID']); //ͳ�ƽ��
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * �ҵĺ�ͬ-��xxx  �����ڵ���Ȩ�ޣ�
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//       $export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;

		//$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		//		foreach($rows as $k => $v){
		//             $rows[$k]['exportOrder'] = $export;
		//		   }
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * �ҵĺ�ͬpageJson
	 */
	function c_myOrderPageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//		$export = isset ( $this->service->this_limit ['������ͬ����'] ) ? $this->service->this_limit ['������ͬ����'] : null;
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');
		$rows = $service->getInvoiceAndIncome_d($rows, 0, $this->service->tbl_name);
		$rows = $service->isGoodsReturn($rows);
		//		foreach($rows as $k => $v){
		//             $rows[$k]['exportOrder'] = $export;
		//		   }
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	//TODO:
	/**
	 * ���к�ͬ��ϢPageJson
	 */
	function c_OrderInfoJson() {
        $rows = array ();
		$service = $this->service;
		$sortInfo = $_POST['sort'];
		$sortInfo = stripslashes(stripslashes($sortInfo));
		if($sortInfo == "invoiceDifferenceTemp"){
			unset($_POST['sort']);
		}
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//������Ȩ������
		$limit = $this->initLimit();

		if ($limit == true) {
			//            $service->groupBy= "c.orgid,c.tablename";
			if ($sortInfo == "gross1" || $sortInfo == "rateOfGross1") {
				$service->sort = "grossA desc," . substr($sortInfo, 0, strlen($sortInfo) - 1);
			}
            if ($sortInfo == "invoiceDifference") {
				$service->sort = "invoiceDifferenceA desc," . $sortInfo;
			}
			if ($sortInfo == "AffirmincomeDifference") {
				$service->sort = "AffirmincomeDifferenceA desc," . $sortInfo;
			}
			if ($sortInfo == "invoiceDifferenceTemp") {
				$service->sort = "if(c.signinType='service',(if(c.processMoney is null,'0.00',c.processMoney)-if(i.invoiceMoney is null,'0.00',i.invoiceMoney))/if(c.c.processMoney is null,'0.00',c.processMoney),'-')";
			}
			$rows = $service->pageBySqlId('select_orderinfo');


			if (!empty ($rows)) {
				//                //����������
				//                $rows = $service->projectProcess_d($rows);
				//��ͬ���Ȩ��
				$comLimit = isset ($this->service->this_limit['�ı��ͬ״̬']) ? $this->service->this_limit['�ı��ͬ״̬'] : null;
				if ($comLimit) {
					$rows = util_arrayUtil :: setItemMainId('com', 1, $rows);
				}
				//�������ֶ�Ȩ��
				$financeLimit = isset ($this->service->this_limit['������']) ? $this->service->this_limit['������'] : null;
				if ($financeLimit == '1') {
					foreach ($rows as $key => $val) {
						$rows[$key]['FinanceCon'] = 1;
					}
				}

				//����
				$rows = $this->sconfig->md5Rows($rows, "orgid");
                $service->sort = "";
				$rows = $service->getRowsallMoney_d($rows, "select_orderinfo", $_SESSION['USER_ID'], $financeLimit); //ͳ�ƽ��
				foreach ($rows as $k => $v) {
					if ($v['tablename'] == 'oa_sale_order' ) {
						//Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('���ۺ�ͬ���', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							));
							$rows[$k] = $this->service->filterWithoutField('���ۺ�ͬ��Ʊtab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							));
							$rows[$k] = $this->service->filterWithoutField('���ۺ�ͬ����tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							));
						}
					}
					if ($v['tablename'] == 'oa_sale_service' ) {
						//Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('�����ͬ���', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'engineering_serviceContract_serviceContract');
							$rows[$k] = $this->service->filterWithoutField('�����ͬ��Ʊtab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'engineering_serviceContract_serviceContract');
							$rows[$k] = $this->service->filterWithoutField('�����ͬ����tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							), 'engineering_serviceContract_serviceContract');
						}
					}
					if ($v['tablename'] == 'oa_sale_lease' ) {
						//Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('���޺�ͬ���', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'contract_rental_rentalcontract');
							$rows[$k] = $this->service->filterWithoutField('���޺�ͬ��Ʊtab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'contract_rental_rentalcontract');
							$rows[$k] = $this->service->filterWithoutField('���޺�ͬ����tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							), 'contract_rental_rentalcontract');
						}
					}
					if ($v['tablename'] == 'oa_sale_rdproject' ) {
						//Ȩ�޹���,����Ǻ�ͬ�����˺��������ˡ���ͬ�����ˣ��������ֶ�Ȩ�޹���
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('�з���ͬ���', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'rdproject_yxrdproject_rdproject');
							$rows[$k] = $this->service->filterWithoutField('�з���ͬ��Ʊtab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'rdproject_yxrdproject_rdproject');
							$rows[$k] = $this->service->filterWithoutField('�з���ͬ����tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							), 'rdproject_yxrdproject_rdproject');
						}
					}
				}

			}
		}
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * Ȩ������
	 * Ȩ�޷��ؽ������:
	 * �������Ȩ�ޣ�����true
	 * �����Ȩ��,����false
	 */
	function initLimit() {
		$service = $this->service;
		//Ȩ����������
		$limitConfigArr = array (
			'areaLimit' => 'areaCode',
			'deptLimit' => 'DEPT_ID',
			'customerTypeLimit' => 'customerType'
		);
		//Ȩ������
		$limitArr = array ();
		//Ȩ��ϵͳ
		if (isset ($this->service->this_limit['��������']) && !empty ($this->service->this_limit['��������']))
			$limitArr['areaLimit'] = $this->service->this_limit['��������'];
		if (isset ($this->service->this_limit['����Ȩ��']) && !empty ($this->service->this_limit['����Ȩ��']))
			$limitArr['deptLimit'] = $this->service->this_limit['����Ȩ��'];
		if (isset ($this->service->this_limit['�ͻ�����']) && !empty ($this->service->this_limit['�ͻ�����']))
			$limitArr['customerTypeLimit'] = $this->service->this_limit['�ͻ�����'];
		if (strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;')) {
			return true;
		} else {
			//�������˻�ȡ�������
			$regionDao = new model_system_region_region();
			$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
			if (!empty ($areaPri)) {
				//����Ȩ�޺ϲ�
				$limitArr['areaLimit'] = implode(array_filter(array (
					$limitArr['areaLimit'],
					$areaPri
				)), ',');
			}
			//			print_r($limitArr);
			if (empty ($limitArr)) {
				return false;
			} else {
				//���û��Ȩ��
				$i = 0;
				$sqlStr = "sql:and ( ";
				foreach ($limitArr as $key => $val) {
					if ($i == 0) {
						$sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
					} else {
						$sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
					}
					$i++;
				}
				$sqlStr .= ")";

				$service->searchArr['mySearchCondition'] = $sqlStr;
				return true;
			}
		}
	}

	/**
	 * ��������ѡ����ϢPagejson
	 */
	function c_presentInfoJson() {
		$rows = null;
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('select_zsporder');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*********************������ͬ�����������**********************/

	/**
	 * ȫ����ͬ���tabҳ��
	 */
	function c_allAuditTab() {
		$this->display('allaudittab');
	}
	/**
	 * ȫ��δ��˺�ͬ�б�
	 */
	function c_allAuditingList() {
		$this->display('allauditing');
	}

	/**
	 * ȫ��δ��˺�ͬ
	 * 2011-08-10
	 * createBy Show
	 */
	function c_allAuditingPagejson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('all_auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ȫ������˺�ͬ�б�
	 */
	function c_allAuditedList() {
		$this->display('allaudited');
	}

	/**
	 * ȫ����������ͬ
	 */
	function c_allAuditedPagejson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('all_audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*******************************************************************************************************/

	/**
	 * �����ⵥʱ����̬��Ӵӱ�ģ��
	 */
	function c_getItemList() {
		$orderId = isset ($_POST['orderId']) ? $_POST['orderId'] : null;
		$list = $this->service->getEquList_d($orderId);
		echo $list;
	}

	/*******************************************************************************************************/
	/**
	 * ��������鿴
	 */
	function c_toShipView() {
		$rows = $this->service->get_d($_GET['id']);
		//��Ⱦ�ӱ�
		//����
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows = $this->service->initView($rows);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$orderTempCode = explode(',', $rows['orderTempCode']);
		if ($rows['sign'] == '��') {
			$this->assign('sign', '��');
		} else
			if ($rows['sign'] == '��') {
				$this->assign('sign', '��');
			}
		if ($rows['orderstate'] == '���ύ') {
			$this->assign('orderstate', '���ύ');
		} else
			if ($rows['orderstate'] == '���õ�') {
				$this->assign('orderstate', '���õ�');
			};
		$this->assign('orderTempCodeV', $rows['orderTempCode']);
		$this->assign('orderTempCode', $this->service->TempOrderView($orderTempCode));
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view-ship');
	}
	/*******************************************************************************************************/
	/**
	 * �Զ����嵥��ʱ���� ��ͼ json
	 */
	function c_customizelistJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->pageBySqlId('select_customizelist');
		//		echo "<pre>";
		//		print_r($rows);
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*******************************************************************************************************/

	/**
	 * ��ת--��ͬ����
	 */
	function c_toShare() {
		$orderId = $_GET['id'];
		$orderType = $_GET['type'];
		$orderInfoDao = new model_contract_common_allcontract();
		$rows = $orderInfoDao->orderInfo($orderId, $orderType);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->assign('type', $orderType);
		$this->assign('shareName', $_SESSION['USERNAME']);
		$this->assign('shareNameId', $_SESSION['USER_ID']);
		$this->assign('shareDate', date('y-m-d'));
		$this->display('share');
	}

	/*******************************************************************************************************/

	/************************ excel���벿��*****************************/

	/**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}

	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		//		echo "===============�ϴ��ļ���Ϣ��ʼ================<br/>";
		//		echo "Upload: " . $_FILES ["inputExcel"] ["name"] . "<br />";
		//		echo "Type: " . $_FILES ["inputExcel"] ["type"] . "<br />";
		//		echo "Size: " . ($_FILES ["inputExcel"] ["size"] / 1024) . " Kb<br />";
		//		echo "Stored in: " . $_FILES ["inputExcel"] ["tmp_name"] . "<br />";
		//		echo "===============�ϴ��ļ���Ϣ����================<br/>";

		$ExaDT = $_POST['import'];
		$objNameArr = array (
				0 => 'orderType', //��ͬ����
		1 => 'orderNature', //��ͬ����
		2 => 'sign', //�Ƿ�ǩ��
		3 => 'orderTempCode', //��ʱ��ͬ��
		4 => 'orderCode', //������ͬ��
		5 => 'orderProvince', //�ͻ�����ʡ��
		6 => 'orderCity', //�ͻ�����ʡ��
		7 => 'customerType', //�ͻ�����
		8 => 'customerName', //�ͻ�����
		9 => 'orderName', //��ͬ����
		10 => 'area', //��������
		11 => 'state', //��ͬ״̬
		12 => 'orderMoney', //��ͬ���
		13 => 'received', //���պϼƣ����
		14 => 'invoiceMoney', //��Ʊ���
		15 => 'softMoney', //�����Ʊ���
		16 => 'hardMoney', //Ӳ����Ʊ���
		17 => 'repairMoney', //ά�޽��
		18 => 'serviceMoney', //������
		19 => 'invoiceType', //��Ʊ����
		20 => 'prinvipalName', //��ͬ������
	21 => 'remark'
			) //��ע
	;

		$this->c_addExecel($objNameArr, $ExaDT);

	}

	//ת����ͬ״̬��ֵ
	function state($state) {
		switch ($state) {
			case "δ�ύ" :
				$states = "0";
				break;
			case "������" :
				$states = "1";
				break;
			case "��ִ��" :
				$states = "2";
				break;
			case "�����" :
				$states = "4";
				break;
			case "�ѹر�" :
				$states = "3";
				break;
		}
		return $states;
	}
	//��ȡ����������������
	function areaName($area) {
		$Numa = strpos($area, '��');
		$Numb = strpos($area, '��');
		if ($Numa == '0') {
			return $area;
		} else {
			$areaName = substr($area, '0', $Numa);
			return $areaName;
		}

	}
	function areaMan($area) {
		$Numa = strpos($area, '��');
		$Numb = strpos($area, '��');
		if ($Numa == '0') {
			$areaMan = "��";
			return $areaMan;
		} else {
			$areaMan = substr($area, $Numa +2, -2);
			return $areaMan;
		}
	}

	//�����ֵ�ת��
	function datadict($type, $name) {

		$datadictDao = new model_system_datadict_datadict();
		switch ($type) {
			case "order" :
				$code = $datadictDao->getCodeByName("XSHTSX", $name);
				break;
			case "service" :
				$code = $datadictDao->getCodeByName("FWHTSX", $name);
				break;
			case "rental" :
				$code = $datadictDao->getCodeByName("ZLHTSX", $name);
				break;
			case "rdproject" :
				$code = $datadictDao->getCodeByName("YFHTSX", $name);
				break;
			case "invoice" :
				$code = $datadictDao->getCodeByName("FPLX", $name);
				break; //��Ʊ����
			case "cusType" :
				$code = $datadictDao->getCodeByName("KHLX", $name);
				break; //�ͻ�����
		}

		return $code;
	}
	//���ж����������Ƿ���ڣ����������򱣴沢����ID
	function beArea($areaName, $areaMan, $areaManId) {
		$regionDao = new model_system_region_region();
		$areaId = $regionDao->region($areaName);
		if (!empty ($areaId)) {
			foreach ($areaId as $key => $val) {
				$areaId[$key] = $val;
			}
			$areaId = implode(",", $areaId[$key]);
		}
		//		 else {
		//			$object = array ();
		//			$object ['areaName'] = $areaName;
		//			$object ['areaPrincipal'] = $areaMan;
		//			$object ['areaPrincipalId'] = $areaManId;
		//			$areaId = $regionDao->add_d ( $object );
		//		}

		return $areaId;

	}
	//�������ֲ��Ҷ�ӦId
	function user($userName) {
		$userDao = new model_deptuser_user_user();
		$user = $userDao->getUserByName("$userName");
		$userId = $user['USER_ID'];
		return $userId;
	}

	//����ʡ�����Ʋ���ID
	function province($proName) {
		$dao = new model_system_procity_province();
		$proIdArr = $dao->find(array (
			"provinceName" => $proName
		), null, "id");
		return $proIdArr['id'];
	}
	//���ݳ������ƻ�ȡ����ID
	function city($cityName) {
		$dao = new model_system_procity_city();
		$cityIdArr = $dao->find(array (
			"cityName" => $cityName
		), null, "id");
		return $cityIdArr['id'];
	}
	//�����ͬ��Ϣ
	function addOrder($orderType, $orderInfo) {
		try {
			$this->service->start_d();
			//ʵ�������ֺ�ͬ����
			$allContract = new model_contract_common_allcontract();
			$orderDao = new model_projectmanagent_order_order(); //���ۺ�ͬ
			$serviceDao = new model_engineering_serviceContract_serviceContract(); //�����ͬ
			$rentalDao = new model_contract_rental_rentalcontract(); //���޺�ͬ
			$rdprojectDao = new model_rdproject_yxrdproject_rdproject(); //�з���ͬ
			foreach ($orderInfo as $key => $val) {
				$orderInfo = $val;
			}
			switch ($orderType) {
				case "order" :
					$orderId = $orderDao->create($orderInfo);
					break;
				case "service" :
					$orderId = $serviceDao->create($orderInfo);
					break;
				case "rental" :
					$orderId = $rentalDao->create($orderInfo);
					break;
				case "rdproject" :
					$orderId = $rdprojectDao->create($orderInfo);
					break;
			}
			$this->service->commit_d();
			return $orderId;
		} catch (exception $e) {
			$this->service->rollBack();
			return false;
		}

	}
	//���浽�
	function income($income) {
		foreach ($income as $key => $val) {
			$income = $val;
		}
		$incomeDao = new model_finance_income_income();
		$incomeId = $incomeDao->create($income);
		return $incomeId;
	}
	//�ж��Ƿ�Ϊ��ʱ��ͬ�������ؿ�Ʊ������ĺ�ͬ����
	function isTemp($type, $code) {
		if (!empty ($code)) {
			switch ($type) {
				case "order" :
					$orderType = "KPRK-01";
					break;
				case "service" :
					$orderType = "KPRK-03";
					break;
				case "rental" :
					$orderType = "KPRK-05";
					break;
				case "rdproject" :
					$orderType = "KPRK-07";
					break;
			}
		} else {
			switch ($type) {
				case "order" :
					$orderType = "KPRK-02";
					break;
				case "service" :
					$orderType = "KPRK-04";
					break;
				case "rental" :
					$orderType = "KPRK-06";
					break;
				case "rdproject" :
					$orderType = "KPRK-08";
					break;
			}
		}

		return $orderType;
	}
	//ͨ���жϿ�Ʊ������ĺ�ͬ���� ���ر���� ��ʽ����ʱ��ͬ��
	function orderCode($type, $code, $tempcode) {
		if ($type == "KPRK-01" || $type == "KPRK-03" || $type == "KPRK-05" || $type == "KPRK-07") {
			return $code;
		} else
			if ($type == "KPRK-02" || $type == "KPRK-04" || $type == "KPRK-06" || $type == "KPRK-08") {
				return $tempcode;
			}
	}

	//�жϵ����ͬ��ʽ�Ƿ���ȷ
	function infoSuc($row) {
		//�жϺ�ͬ���Ƿ����
		if (empty ($row['orderTempCode']) && empty ($row['orderCode'])) {
			$orderCode = '';
		} else {
			$orderCode = $this->service->orderBe($row['orderTempCode'], $row['orderCode']);
		}
		//�жϿͻ��Ƿ����
		$customerDao = new model_customer_customer_customer();
		$customerId = $customerDao->findCus($row['customerName']);
		foreach ($customerId as $key => $val) {
			$customerId[$key] = $val;
		}
		$customerId = implode(",", $customerId[$key]);
		//�ж������Ƿ����
		$areaName = $this->areaName($row['area']);
		$regionDao = new model_system_region_region();
		$areaId = $regionDao->region($areaName);
		if (!empty ($areaId)) {
			foreach ($areaId as $key => $val) {
				$areaId[$key] = $val;
			}
			$areaId = implode(",", $areaId[$key]);
		}
		//�ж�ʡ��
		$dao = new model_system_procity_city();
		$proCityIdArr = $dao->find(array (
			"cityName" => $row['orderCity']
		), null, "provinceId");
		$proCityId = $proCityIdArr['provinceId'];
		$proId = $this->province($row['orderProvince']);
		$cityId = $this->city($row['orderCity']);
		if (!empty ($proId) && !empty ($cityId) && $proCityId == $proId) {
			$proCityTip = 1;
		}
		if (empty ($row['orderType']) || !empty ($orderCode) || empty ($areaId) || empty ($customerId) || $proCityTip != 1) {
			return "1";
		} else
			if ($row['orderType'] == '���ۺ�ͬ' || $row['orderType'] == '�����ͬ' || $row['orderType'] == '���޺�ͬ' || $row['orderType'] == '�з���ͬ') {
				return "0";
			} else {
				return "1";
			}

	}
	//���ݿͻ����Ʋ���ID
	function cusId($cName) {
		$customerDao = new model_customer_customer_customer();
		$customerId = $customerDao->findCid($cName);
		foreach ($customerId as $key => $val) {
			$customerId[$key] = $val;
		}
		$customerId = implode(",", $customerId[$key]);
		return $customerId;
	}
	/**
	 * �ϴ�EXCEl������������
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = $upexcel->upExcelData($filename, $temp_name);
			spl_autoload_register('__autoload'); //�ı������ķ�ʽ
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$err = array (); //ʧ�ܵ�����
				$suc = array (); //�ɹ�������
				$sucArray = array ();
				$errArray = array ();

				foreach ($objectArr as $key => $val) {
					$flag = $this->infoSuc($objectArr[$key]);
					if ($flag == '1') {
						$errArray[$key] = $objectArr[$key];
					} else
						if ($flag == '0') {
							$sucArray[$key] = $objectArr[$key];
						}

				}
				//���ֺ�ͬ��Ϣ����
				foreach ($sucArray as $key => $val) {
					try {
						$this->service->start_d();
						switch ($sucArray[$key]['orderType']) {
							case "���ۺ�ͬ" :
								$order = array ();
								$order[$key]['sign'] = $sucArray[$key]['sign']; //�Ƿ�ǩԼ
								$order[$key]['orderCode'] = $sucArray[$key]['orderCode']; //������ͬ��
								$order[$key]['orderTempCode'] = $objectArr[$key]['orderTempCode']; //��ʱ��ͬ��
								$order[$key]['orderName'] = $objectArr[$key]['orderName']; //��ͬ����
								$order[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //��������
								$order[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //��������
								$order[$key]['areaPrincipalId'] = $this->user($order[$key]['areaPrincipal']); //��������Id
								//�Զ�����ҵ�����
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$order[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_order_objCode", $dept['Code']);

								$order[$key]['areaCode'] = $this->beArea($order[$key]['areaName'], $order[$key]['areaPrincipal']); //������루id��
								$order[$key]['state'] = $this->state($sucArray[$key]['state']); //��ͬ״̬
								$order[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //��ͬ���
								$order[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //��Ʊ����  invoice
								$order[$key]['remark'] = $sucArray[$key]['remark']; //��ע
								$order[$key]['ExaDT'] = $ExaDT['ExaDT']; //����ʱ��
								$order[$key]['createTime'] = date('Y-m-d'); //����ʱ��
								$order[$key]['createName'] = $_SESSION['USERNAME'];
								$order[$key]['createId'] = $_SESSION['USER_ID'];

								$order[$key]['orderNature'] = $this->datadict("order", $sucArray[$key]['orderNature']); //��ͬ���� Code
								$order[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //��ͬ���� Name
								$order[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //����ʡ��
								$order[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //����ʡ��Id
								$order[$key]['orderCity'] = $sucArray[$key]['orderCity']; //��������
								$order[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //��������Id
								$order[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //�ͻ�����
								$order[$key]['customerName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$order[$key]['customerId'] = $this->cusId($sucArray[$key]['customerName']); //�ͻ�����cusId
								$order[$key]['prinvipalName'] = $sucArray[$key]['prinvipalName']; //��ͬ������ �����ֺ�ͬ��ͬ��
								$order[$key]['prinvipalId'] = $this->user($order[$key]['prinvipalName']); //��ͬ������Id
								$order[$key]['ExaStatus'] = "���"; //��ͬ����״̬

								$orderId = $this->addOrder("order", $order); //�����ͬ��Ϣ������ú�ͬId
								$invoiceM = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$incomeM = $sucArray[$key]['received']; //������
								if (!empty ($orderId)) {
									//��Ʊ
									$orderInvoice[$key]['objId'] = $orderId; //��ͬID
									$orderInvoice[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['orderCode']); //��Ʊ�ĺ�ͬ����
									$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //������ͬ��
									$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //��ͬ����
									$orderInvoice[$key]['invoiceUnitName'] = $order[$key]['customerName']; //�ͻ�����
									$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //��Ʊʱ�䣬��ʱȡ�����ʱ�䴦��
									$orderInvoice[$key]['invoiceType'] = $order[$key]['invoiceType']; //��Ʊ����
									$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //��Ʊ���
									$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //������
									$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //Ӳ�����
									$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //ά�޽��
									$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //������
									$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //��ͬ������
									$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
									//����
									//���
									$income[$key]['formType'] = "YFLX-DKD"; //�������
									$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
									$income[$key]['incomeDate'] = date("Y-m-d"); //��д������ڣ���ȡ��������
									$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //������
									$income[$key]['allotAble'] = "0"; //
									$income[$key]['incomeType'] = "DKFS1"; //��������
									$income[$key]['sectionType'] = "DKLX-HK"; //
									$income[$key]['status'] = "DKZT-YFP"; //
									//���䵥
									$allot[$key]['incomeId'] = $this->income($income); //���Id
									$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //��ͬId
									$allot[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['orderCode']); //����ĺ�ͬ����
									$allot[$key]['money'] = $sucArray[$key]['received']; //���������
									$allot[$key]['allotDate'] = date("Y-m-d"); //���������
									//���浽����䵥
									if (!empty ($incomeM)) {
										$allotDao = new model_finance_income_incomeAllot();
										foreach ($allot as $key => $val) {
											$allot = $val;
										}
										$allotId = $allotDao->create($allot);
									}

									//���濪Ʊ
									if (!empty ($invoiceM)) {
										$invoiceDao = new model_finance_invoice_invoice();
										foreach ($orderInvoice as $key => $val) {
											$orderInvoice = $val;
										}
										if (empty ($orderInvoice['objId'])) {
											throw new Exception("��ͬ��Ϣ����");
											$err[] = $sucArray[$key];
										} else {
											$orderId = $invoiceDao->create($orderInvoice);
										}
									}

								}

								break;
							case "�����ͬ" :
								$service = array ();
								$service[$key]['sign'] = $sucArray[$key]['sign']; //�Ƿ�ǩԼ
								$service[$key]['orderCode'] = $sucArray[$key]['orderCode']; //������ͬ��
								$service[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //��ʱ��ͬ��
								$service[$key]['orderName'] = $sucArray[$key]['orderName']; //��ͬ����
								$service[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //��������
								$service[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //��������
								$service[$key]['areaPrincipalId'] = $this->user($service[$key]['areaPrincipal']); //��������Id

								//�Զ�����ҵ�����
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$service[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_service_objCode", $dept['Code']);

								$service[$key]['areaCode'] = $this->beArea($service[$key]['areaName'], $service[$key]['areaPrincipal'], $service[$key]['areaPrincipalId']); //������루id��
								$service[$key]['state'] = $this->state($sucArray[$key]['state']); //��ͬ״̬
								$service[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //��ͬ���
								$service[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //��Ʊ����  invoice
								$service[$key]['remark'] = $sucArray[$key]['remark']; //��ע
								$service[$key]['ExaDT'] = $ExaDT['ExaDT']; //����ʱ��
								$service[$key]['createTime'] = date('Y-m-d'); //����ʱ��
								$service[$key]['createName'] = $_SESSION['USERNAME'];
								$service[$key]['createId'] = $_SESSION['USER_ID'];

								$service[$key]['orderNature'] = $this->datadict("service", $sucArray[$key]['orderNature']); //��ͬ����
								$service[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //��ͬ���� Name
								$service[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //����ʡ��
								$service[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //����ʡ��Id
								$service[$key]['orderCity'] = $sucArray[$key]['orderCity']; //��������
								$service[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //��������Id
								$service[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //�ͻ�����
								$service[$key]['cusName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$service[$key]['cusNameId'] = $this->cusId($sucArray[$key]['customerName']); //�ͻ�����$this->cusId
								$service[$key]['orderPrincipal'] = $sucArray[$key]['prinvipalName']; //��ͬ������ �����ֺ�ͬ��ͬ��
								$service[$key]['orderPrincipalId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
								$service[$key]['ExaStatus'] = "���"; //��ͬ����״̬

								$serviceId = $this->addOrder("service", $service); //�����ͬ��Ϣ������ú�ͬId
								$invoiceM = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$incomeM = $sucArray[$key]['received']; //������
								if (!empty ($serviceId)) {
									//��Ʊ
									$orderInvoice[$key]['objId'] = $serviceId;
									$orderInvoice[$key]['objType'] = $this->isTemp("service", $sucArray[$key]['orderCode']); //��Ʊ�ĺ�ͬ����
									$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //������ͬ��
									$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //��ͬ����
									$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
									$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //��Ʊʱ�䣬��ʱȡ�����ʱ�䴦��
									$orderInvoice[$key]['invoiceType'] = $service[$key]['invoiceType']; //��Ʊ����
									$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //��Ʊ���
									$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //������
									$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //Ӳ�����
									$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //ά�޽��
									$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //������
									$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //��ͬ������
									$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
									//����
									//���
									$income[$key]['formType'] = "YFLX-DKD"; //�������
									$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
									$income[$key]['incomeDate'] = date("Y-m-d"); //��д������ڣ���ȡ��������
									$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //������
									$income[$key]['allotAble'] = "0"; //
									$income[$key]['incomeType'] = "DKFS1"; //��������
									$income[$key]['sectionType'] = "DKLX-HK"; //
									$income[$key]['status'] = "DKZT-YFP"; //
									//���䵥
									$allot[$key]['incomeId'] = $this->income($income); //���Id
									$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //��ͬId
									$allot[$key]['objType'] = $this->isTemp("service", $sucArray[$key]['orderCode']); //����ĺ�ͬ����
									$allot[$key]['money'] = $sucArray[$key]['received']; //���������
									$allot[$key]['allotDate'] = date("Y-m-d"); //���������
									//���浽����䵥
									if (!empty ($incomeM)) {
										$allotDao = new model_finance_income_incomeAllot();
										foreach ($allot as $key => $val) {
											$allot = $val;
										}
										$allotId = $allotDao->create($allot);
									}

									//���濪Ʊ
									if (!empty ($invoiceM)) {
										$invoiceDao = new model_finance_invoice_invoice();
										foreach ($orderInvoice as $key => $val) {
											$orderInvoice = $val;
										}
										$orderId = $invoiceDao->create($orderInvoice);
									}
								}

								break;
							case "���޺�ͬ" :
								$rental = array ();
								$rental[$key]['sign'] = $sucArray[$key]['sign']; //�Ƿ�ǩԼ
								$rental[$key]['orderCode'] = $sucArray[$key]['orderCode']; //������ͬ��
								$rental[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //��ʱ��ͬ��
								$rental[$key]['orderName'] = $sucArray[$key]['orderName']; //��ͬ����
								$rental[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //��������
								$rental[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //��������
								$rental[$key]['areaPrincipalId'] = $this->user($rental[$key]['areaPrincipal']); //��������Id

								//�Զ�����ҵ�����
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$rental[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_lease_objCode", $dept['Code']);

								$rental[$key]['areaCode'] = $this->beArea($rental[$key]['areaName'], $rental[$key]['areaPrincipal'], $rental[$key]['areaPrincipalId']); //������루id��
								$rental[$key]['state'] = $this->state($sucArray[$key]['state']); //��ͬ״̬
								$rental[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //��ͬ���
								$rental[$key]['remark'] = $sucArray[$key]['remark']; //��ע
								$rental[$key]['ExaDT'] = $ExaDT['ExaDT']; //����ʱ��
								$rental[$key]['createTime'] = date('Y-m-d'); //����ʱ��
								$rental[$key]['createName'] = $_SESSION['USERNAME'];
								$rental[$key]['createId'] = $_SESSION['USER_ID'];

								$rental[$key]['orderNature'] = $this->datadict("rental", $sucArray[$key]['orderNature']); //��ͬ����
								$rental[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //��ͬ���� Name
								$rental[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //����ʡ��
								$rental[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //����ʡ��Id
								$rental[$key]['orderCity'] = $sucArray[$key]['orderCity']; //��������
								$rental[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //��������Id
								$rental[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //�ͻ�����
								$rental[$key]['tenant'] = $sucArray[$key]['customerName']; //�ͻ�����
								$rental[$key]['tenantId'] = $this->cusId($sucArray[$key]['customerName']); //�ͻ�����$this->cusId
								$rental[$key]['hiresName'] = $sucArray[$key]['prinvipalName']; //��ͬ������ �����ֺ�ͬ��ͬ��
								$rental[$key]['hiresId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
								$rental[$key]['ExaStatus'] = "���"; //��ͬ����״̬

								$rentalId = $this->addOrder("rental", $rental); //�����ͬ��Ϣ������ú�ͬId
								$invoiceM = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$incomeM = $sucArray[$key]['received']; //������
								//��Ʊ
								$orderInvoice[$key]['objId'] = $rentalId;
								$orderInvoice[$key]['objType'] = $this->isTemp("rental", $sucArray[$key]['orderCode']); //��Ʊ�ĺ�ͬ����
								$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //������ͬ��
								$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //��ͬ����
								$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //��Ʊʱ�䣬��ʱȡ�����ʱ�䴦��
								$orderInvoice[$key]['invoiceType'] = $rental[$key]['invoiceType']; //��Ʊ����
								$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //������
								$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //Ӳ�����
								$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //ά�޽��
								$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //������
								$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //��ͬ������
								$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
								//����
								//���
								$income[$key]['formType'] = "YFLX-DKD"; //�������
								$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$income[$key]['incomeDate'] = date("Y-m-d"); //��д������ڣ���ȡ��������
								$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //������
								$income[$key]['allotAble'] = "0"; //
								$income[$key]['incomeType'] = "DKFS1"; //��������
								$income[$key]['sectionType'] = "DKLX-HK"; //
								$income[$key]['status'] = "DKZT-YFP"; //
								//���䵥
								$allot[$key]['incomeId'] = $this->income($income); //���Id
								$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //��ͬId
								$allot[$key]['objType'] = $this->isTemp("rental", $sucArray[$key]['orderCode']); //����ĺ�ͬ����
								$allot[$key]['money'] = $sucArray[$key]['received']; //���������
								$allot[$key]['allotDate'] = date("Y-m-d"); //���������
								//���浽����䵥
								if (!empty ($incomeM)) {
									$allotDao = new model_finance_income_incomeAllot();
									foreach ($allot as $key => $val) {
										$allot = $val;
									}
									$allotId = $allotDao->create($allot);
								}

								//���濪Ʊ
								if (!empty ($invoiceM)) {
									$invoiceDao = new model_finance_invoice_invoice();
									foreach ($orderInvoice as $key => $val) {
										$orderInvoice = $val;
									}
									$orderId = $invoiceDao->create($orderInvoice);
								}

								break;
							case "�з���ͬ" :
								$rdproject = array ();
								$rdproject[$key]['sign'] = $sucArray[$key]['sign']; //�Ƿ�ǩԼ
								$rdproject[$key]['orderCode'] = $sucArray[$key]['orderCode']; //������ͬ��
								$rdproject[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //��ʱ��ͬ��
								$rdproject[$key]['orderName'] = $sucArray[$key]['orderName']; //��ͬ����
								$rdproject[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //��������
								$rdproject[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //��������
								$rdproject[$key]['areaPrincipalId'] = $this->user($rdproject[$key]['areaPrincipal']); //��������Id

								//�Զ�����ҵ�����
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$rdproject[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_rdproject_objCode", $dept['Code']);

								$rdproject[$key]['areaCode'] = $this->beArea($rdproject[$key]['areaName'], $rdproject[$key]['areaPrincipal'], $rdproject[$key]['areaPrincipalId']); //������루id��
								$rdproject[$key]['state'] = $this->state($sucArray[$key]['state']); //��ͬ״̬
								$rdproject[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //��ͬ���
								$rdproject[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //��Ʊ����  invoice
								$rdproject[$key]['remark'] = $sucArray[$key]['remark']; //��ע
								$rdproject[$key]['ExaDT'] = $ExaDT['ExaDT']; //����ʱ��
								$rdproject[$key]['createTime'] = date('Y-m-d'); //����ʱ��
								$rdproject[$key]['createName'] = $_SESSION['USERNAME'];
								$rdproject[$key]['createId'] = $_SESSION['USER_ID'];

								$rdproject[$key]['orderNature'] = $this->datadict("service", $sucArray[$key]['orderNature']); //��ͬ����
								$rdproject[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //��ͬ���� Name
								$rdproject[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //����ʡ��
								$rdproject[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //����ʡ��Id
								$rdproject[$key]['orderCity'] = $sucArray[$key]['orderCity']; //��������
								$rdproject[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //��������Id
								$rdproject[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //�ͻ�����
								$rdproject[$key]['cusName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$rdproject[$key]['cusNameId'] = $this->cusId($sucArray[$key]['customerName']); //�ͻ�����$this->cusId
								$rdproject[$key]['orderPrincipal'] = $sucArray[$key]['prinvipalName']; //��ͬ������ �����ֺ�ͬ��ͬ��
								$rdproject[$key]['orderPrincipalId'] = $this->user($rdproject[$key]['orderPrincipal']); //��ͬ������Id
								$rdproject[$key]['ExaStatus'] = "���"; //��ͬ����״̬

								$rdprojectId = $this->addOrder("rdproject", $rdproject); //�����ͬ��Ϣ������ú�ͬId
								$invoiceM = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$incomeM = $sucArray[$key]['received']; //������
								//��Ʊ
								$orderInvoice[$key]['objId'] = $rdprojectId;
								$orderInvoice[$key]['objType'] = $this->isTemp("rdproject", $sucArray[$key]['orderCode']); //��Ʊ�ĺ�ͬ����
								$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //������ͬ��
								$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //��ͬ����
								$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //��Ʊʱ�䣬��ʱȡ�����ʱ�䴦��
								$orderInvoice[$key]['invoiceType'] = $rdproject[$key]['invoiceType']; //��Ʊ����
								$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //��Ʊ���
								$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //������
								$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //Ӳ�����
								$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //ά�޽��
								$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //������
								$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //��ͬ������
								$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //��ͬ������Id
								//����
								//���
								$income[$key]['formType'] = "YFLX-DKD"; //�������
								$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //�ͻ�����
								$income[$key]['incomeDate'] = date("Y-m-d"); //��д������ڣ���ȡ��������
								$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //������
								$income[$key]['allotAble'] = "0"; //
								$income[$key]['incomeType'] = "DKFS1"; //��������
								$income[$key]['sectionType'] = "DKLX-HK"; //
								$income[$key]['status'] = "DKZT-YFP"; //
								//���䵥
								$allot[$key]['incomeId'] = $this->income($income); //���Id
								$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //��ͬId
								$allot[$key]['objType'] = $this->isTemp("rdproject", $sucArray[$key]['orderCode']); //����ĺ�ͬ����
								$allot[$key]['money'] = $sucArray[$key]['received']; //���������
								$allot[$key]['allotDate'] = date("Y-m-d"); //���������
								//���浽����䵥
								if (!empty ($incomeM)) {
									$allotDao = new model_finance_income_incomeAllot();
									foreach ($allot as $key => $val) {
										$allot = $val;
									}
									$allotId = $allotDao->create($allot);
								}

								//���濪Ʊ
								if (!empty ($invoiceM)) {
									$invoiceDao = new model_finance_invoice_invoice();
									foreach ($orderInvoice as $key => $val) {
										$orderInvoice = $val;
									}
									$orderId = $invoiceDao->create($orderInvoice);
								}

								break;
						}
						$suc[] = $sucArray[$key];
						$this->service->commit_d();
					} catch (Exception $e) {
						$sucArray[$key]['errMsg'] = $e->getMessage();
						echo $e->getMessage();
						$this->service->rollBack();
						return false;
					}

				}
				$arrinfo = array ();
				//�жϺ�ͬ�����Ƿ�Ϊ��
				//				foreach ( $suc as $k => $v ) {
				//					if (empty ( $suc [$k] ['orderName'] )) {
				//						unset ( $suc [$k] );
				//					}
				//				}
				foreach ($suc as $k => $v) {
					array_push($arrinfo, array (
						"orderCode" => $suc[$k]['orderCode'],
						"cusName" => $suc[$k]['customerName'],
						"result" => "����ɹ�"
					));
				}

				foreach ($errArray as $k => $v) {
					if (empty ($errArray[$k]['orderTempCode']) && empty ($errArray[$k]['orderCode'])) {
						array_push($arrinfo, array (
							"orderCode" => $errArray[$k]['orderCode'],
							"cusName" => $errArray[$k]['customerName'],
							"result" => "����ʧ�ܣ���ͬ��Ϊ��"
						));
					} else {
						$orderCode = $this->service->orderBe($errArray[$k]['orderTempCode'], $errArray[$k]['orderCode']);
						if (!empty ($orderCode)) {
							array_push($arrinfo, array (
								"orderCode" => $errArray[$k]['orderCode'],
								"cusName" => $errArray[$k]['customerName'],
								"result" => "����ʧ�ܣ���ͬ���Ѵ���"
							));
						} else {
							$areaName = $this->areaName($errArray[$k]['area']);
							$regionDao = new model_system_region_region();
							$areaId = $regionDao->region($areaName);
							if (empty ($areaId)) {
								array_push($arrinfo, array (
									"orderCode" => $errArray[$k]['orderCode'],
									"cusName" => $errArray[$k]['customerName'],
									"result" => "����ʧ�ܣ��������򲻴���!"
								));
							} else {
								$customerDao = new model_customer_customer_customer();
								$customerId = $customerDao->findCus($errArray[$k]['customerName']);
								if (empty ($customerId)) {
									array_push($arrinfo, array (
										"orderCode" => $errArray[$k]['orderCode'],
										"cusName" => $errArray[$k]['customerName'],
										"result" => "����ʧ�ܣ��ͻ���Ϣ������!"
									));
								} else {
									//�ж�ʡ��
									$dao = new model_system_procity_city();
									$proCityIdArr = $dao->find(array (
										"cityName" => $errArray[$k]['orderCity']
									), null, "provinceId");
									$proCityId = $proCityIdArr['provinceId'];
									$proId = $this->province($errArray[$k]['orderProvince']);
									$cityId = $this->city($row['orderCity']);
									if (empty ($proId) || empty ($cityId) || $proCityId != $proId) {
										array_push($arrinfo, array (
											"orderCode" => $errArray[$k]['orderCode'],
											"cusName" => $errArray[$k]['customerName'],
											"result" => "����ʧ�ܣ�ʡ����Ϣ����"
										));
									} else {
										array_push($arrinfo, array (
											"orderCode" => $errArray[$k]['orderCode'],
											"cusName" => $errArray[$k]['customerName'],
											"result" => "����ʧ�ܣ���ͬ���ʹ���"
										));
									}
								}
							}
						}
					}
				}

				if ($arrinfo) {
					echo util_excelUtil :: showResultOrder($arrinfo, "������", array (
						"��ͬ��",
						"�ͻ�����",
						"���"
					));
				}
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}

	}
	/**************************************��������˵�************************************************************************/
	/**
	 * ��ͬ��Ϣ---���ۺ�ͬ
	 */
	function c_toOrderIn() {
		$this->display('orderin');
	}

	/**************************************************************************************************************/
	/******************start��ͬ��Ϣ�б���**************************/
	/**
	 * ��ͬ����-��ͬ��Ϣ-��ͬ��Ϣ����
	 */
	function c_exportExcel() {

		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
	  //�������˻�ȡ�������
		$regionDao = new model_system_region_region();
		$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
		if (!isset ($this->service->this_limit['��ͬ��Ϣ����']) && empty($areaPri)) {
			showmsg('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
		}
		$signinTypeArr = array (
			'order' => '������',
			'service' => '������',
			'lease' => '������',
			'rdproject' => '�з���'
		);
		$tablenameArr = array (
			'oa_sale_order' => '���ۺ�ͬ',
			'oa_sale_service' => '�����ͬ',
			'oa_sale_lease' => '���ú�ͬ',
			'oa_sale_rdproject' => '�з���ͬ'
		);
		$stateArr = array (
			'0' => 'δ�ύ',
			'1' => '������',
			'2' => 'ִ����',
			'3' => '�ѹر�',
			'4' => '�����',
			'5' => '�Ѻϲ�',
			'�Ѳ��'
		);
		$signinArr = array (
			'0' => 'δǩ��',
			'1' => '��ǩ��'
		);

		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['state'];
		$ExaStatus = $_GET['ExaStatus'];
		$beginDate = $_GET['beginDate']; //��ʼʱ��
		$endDate = $_GET['endDate']; //��ֹʱ��
		$ExaDT = $_GET['ExaDT']; //����ʱ��
		$areaNameArr = $_GET['areaNameArr']; //��������
		$orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //��ͬ���
		$prinvipalName = $_GET['prinvipalName']; //��ͬ������
		$customerName = $_GET['customerName']; //�ͻ�����
		$orderProvince = $_GET['orderProvince']; //����ʡ��
		$customerType = $_GET['customerType']; //�ͻ�����
		$orderNatureArr = $_GET['orderNatureArr']; //��ͬ����
		$isShip = $_GET['isShip']; //�Ƿ���Ҫ����
		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		//		$searchCondition = "$searchConditionKey = $searchConditionVal";
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		//��������
		$searchArr['signinType'] = $type;
		if ($state == null || $state == "" || $state == "undefined") {
			$searchArr['states'] = "1,2,3,4,5,6";
		} else {
			$searchArr['state'] = $state;
		}
		$searchArr['ExaStatus'] = $ExaStatus;
		$searchArr['beginDate'] = $beginDate; //��ʼʱ��
		$searchArr['endDate'] = $endDate; //��ֹʱ��
		$searchArr['createTime'] = $ExaDT; //����ʱ��
		$searchArr['areaNameArr'] = $areaNameArr; //��������
		$searchArr['orderCodeOrTempSearch'] = $orderCodeOrTempSearch; //��ͬ���
		$searchArr['prinvipalName'] = $prinvipalName; //��ͬ������
		$searchArr['customerName'] = $customerName; //�ͻ�����
		$searchArr['orderProvince'] = $orderProvince; //����ʡ��
		$searchArr['customerType'] = $customerType; //�ͻ�����
		$searchArr['orderNatureArr'] = $orderNatureArr; //��ͬ����
		$searchArr['DeliveryStatusArr'] = $isShip; //�Ƿ���Ҫ����
		$searchArr[$searchConditionKey] = $searchConditionVal;
		//		$privlimit = isset ( $this->service->this_limit ['��������'] ) ? $this->service->this_limit ['��������'] : null;
		//		$arealimit = $this->service->getArea_d ();
		//		$thisAreaLimit = $this->regionMerge ( $privlimit, $arealimit );
		//		if (! empty ( $thisAreaLimit )) {
		//			$searchArr ['areaCode'] = $thisAreaLimit;
		//		}
		//������Ȩ������1
		$limit = $this->initLimit();
		foreach ($searchArr as $key => $val) {
			if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == 'undefined') {
				unset ($searchArr[$key]);
			}
		}
		$mySearchCondition = $this->service->searchArr;
		if (!empty ($mySearchCondition)) {
			$searchArr = array_merge($mySearchCondition, $searchArr);
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_orderinfo');
		//����������
		$rows = $this->service->projectProcess_d($rows);
		//		$rows = $this->service->getInvoiceAndIncome_d ( $rows );
		//        $rows = $this->service->getMoneyControl_d ( $rows );//����������
		foreach ($rows as $index => $row) {
			foreach ($row as $key => $val) {
				$rows[$index]['surOrderMoney'] = $rows[$index]['orderMoney'] - $rows[$index]['incomeMoney'];
				$rows[$index]['surincomeMoney'] = $rows[$index]['invoiceMoney'] - $rows[$index]['incomeMoney'];
				if ($key == 'tablename') {
					$rows[$index][$key] = $tablenameArr[$val];
				} else
					if ($key == 'state') {
						$rows[$index][$key] = $stateArr[$val];
					} else
						if ($key == 'signIn') {
							$rows[$index][$key] = $signinArr[$val];
						} else
							if ($key == 'signinType') {
								$rows[$index][$key] = $signinTypeArr[$val];
							}
			}
		}
		//���ϼ�
		$this->service->searchArr = $searchArr;
		$rowMoney = $this->service->listBySqlId('select_orderinfo_sumMoney');
		$rowMoney[0]['tablename'] = "���ϼ�";
		$rows[] = $rowMoney[0];
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		//������
		$finLimit = isset ($this->service->this_limit['������']) ? $this->service->this_limit['������'] : null;
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			if ($val['orderMoney'] * 1 != 0) {
				$dataArr[$key]['orderTempMoney'] = 0;
			}
			//���������Ȩ����ʾ
			if ($finLimit != "1") {
				if (isset ($dataArr[$key]['serviceconfirmMoneyAll'])) {
					$dataArr[$key]['serviceconfirmMoneyAll'] = "******";
				}
				if (isset ($dataArr[$key]['financeconfirmPlan'])) {
					$dataArr[$key]['financeconfirmPlan'] = "******";
				}
				if (isset ($dataArr[$key]['financeconfirmMoneyAll'])) {
					$dataArr[$key]['financeconfirmMoneyAll'] = "******";
				}
				if (isset ($dataArr[$key]['gross1'])) {
					$dataArr[$key]['gross1'] = "******";
				}
				if (isset ($dataArr[$key]['rateOfGross1'])) {
					$dataArr[$key]['rateOfGross1'] = "******";
				}
				if (isset ($dataArr[$key]['feeAll'])) {
					$dataArr[$key]['feeAll'] = "******";
				}
				if (isset ($dataArr[$key]['AffirmincomeDifference'])) {
					$dataArr[$key]['AffirmincomeDifference'] = "******";
				}
			}
		}
		return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);

	}

	/**
	 * ��ͬ����-�ҵĺ�ͬ-��ͬ��Ϣ����
	 */
	function c_myExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$tablenameArr = array (
			'oa_sale_order' => '���ۺ�ͬ',
			'oa_sale_service' => '�����ͬ',
			'oa_sale_lease' => '���ú�ͬ',
			'oa_sale_rdproject' => '�з���ͬ'
		);
		$stateArr = array (
			'0' => 'δ�ύ',
			'1' => '������',
			'2' => 'ִ����',
			'3' => '�ѹر�',
			'4' => '�����'
		);
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['state'];
		$ExaStatus = $_GET['ExaStatus'];
		//��¼��
		$appId = $_SESSION['USER_ID'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		$searchArr['tablename'] = $type;
		$searchArr['state'] = $state;
		$searchArr['ExaStatus'] = $ExaStatus;
		$searchArr['prinvipalId'] = $appId;
		//    if(isset($this->service->this_limit['��������'])){
		//        $searchArr['areaCode'] = $this->service->this_limit['��������'];
		//    }
		foreach ($searchArr as $key => $val) {
			if ($searchArr[$key] === null || $searchArr[$key] === '') {
				unset ($searchArr[$key]);
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_myorderinfo');
		$rows = $this->service->getInvoiceAndIncome_d($rows);
		foreach ($rows as $index => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'tablename') {
					$rows[$index][$key] = $tablenameArr[$val];
				} else
					if ($key == 'state') {
						$rows[$index][$key] = $stateArr[$val];
					}
			}
		}
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			if ($val['orderMoney'] * 1 != 0) {
				$dataArr[$key]['orderTempMoney'] = 0;
			}
		}
		return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}

	/**
	 * ��ͬ���� - ��ͬǩ��-��ͬ��Ϣ����
	 */
	function c_singInExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$tablenameArr = array (
			'oa_sale_order' => '���ۺ�ͬ',
			'oa_sale_service' => '�����ͬ',
			'oa_sale_lease' => '���ú�ͬ',
			'oa_sale_rdproject' => '�з���ͬ'
		);
		$signinTypeArr = array (
			'order' => '������',
			'service' => '������',
			'lease' => '������',
			'rdproject' => '�з���'
		);
		$stateArr = array (
			'0' => 'δ�ύ',
			'1' => '������',
			'2' => 'ִ����',
			'3' => '�ѹر�',
			'4' => '�����'
		);
		$signInArr = array (
			'0' => 'δǩ��',
			'1' => '��ǩ��'
		);
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['states'];
		$ExaStatus = $_GET['ExaStatus'];
		$beginDate = $_GET['beginDate']; //��ʼʱ��
		$endDate = $_GET['endDate']; //��ֹʱ��
		$ExaDT = $_GET['ExaDT']; //����ʱ��
		$areaNameArr = $_GET['areaNameArr']; //��������
		$orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //��ͬ���
		$prinvipalName = $_GET['prinvipalName']; //��ͬ������
		$customerName = $_GET['customerName']; //�ͻ�����
		$orderProvince = $_GET['orderProvince']; //����ʡ��
		$customerType = $_GET['customerType']; //�ͻ�����
		$orderNatureArr = $_GET['orderNatureArr']; //��ͬ����
		$sign = $_GET['sign'];
		$signIn = $_GET['signIn'];
		//��¼��
		$appId = $_SESSION['USER_ID'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		$searchArr['tablename'] = $type;
		$searchArr['states'] = $state;
		$searchArr['ExaStatusArr'] = $ExaStatus;
		$searchArr['beginDate'] = $beginDate; //��ʼʱ��
		$searchArr['endDate'] = $endDate; //��ֹʱ��
		$searchArr['ExaDT'] = $ExaDT; //����ʱ��
		$searchArr['areaNameArr'] = $areaNameArr; //��������
		$searchArr['orderCodeOrTempSearch'] = $orderCodeOrTempSearch; //��ͬ���
		$searchArr['prinvipalName'] = $prinvipalName; //��ͬ������
		$searchArr['customerName'] = $customerName; //�ͻ�����
		$searchArr['orderProvince'] = $orderProvince; //����ʡ��
		$searchArr['customerType'] = $customerType; //�ͻ�����
		$searchArr['orderNatureArr'] = $orderNatureArr; //��ͬ����
		$searchArr['sign'] = $sign;
		$searchArr['signIn'] = $signIn;
		//		$searchArr ['appId'] = $appId;
		//    if(isset($this->service->this_limit['��������'])){
		//        $searchArr['areaCode'] = $this->service->this_limit['��������'];
		//    }
		foreach ($searchArr as $key => $val) {
			if ($searchArr[$key] === null || $searchArr[$key] === '' || $searchArr[$key] == "undefined") {
				unset ($searchArr[$key]);
			}
		}
		$this->service->searchArr = $searchArr;
		$this->service->sort = 'c.createTime';
		$this->service->asc = true;
		$rows = $this->service->listBySqlId('select_signIn');
		$rows = $this->service->getInvoiceAndIncome_d($rows);
		foreach ($rows as $index => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'tablename') {
					$rows[$index][$key] = $tablenameArr[$val];
				} else
					if ($key == 'state') {
						$rows[$index][$key] = $stateArr[$val];
					} else
						if ($key == 'signIn') {
							$rows[$index][$key] = $signInArr[$val];
						} else
							if ($key == 'signinType') {
								$rows[$index][$key] = $signinTypeArr[$val];
							}
			}
		}
		//���ϼ�
		$this->service->searchArr = $searchArr;
		$rowMoney = $this->service->listBySqlId('select_orderinfo_sumMoney');
		$rowMoney[0]['tablename'] = "���ϼ�";
		$rows[] = $rowMoney[0];
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			if ($val['orderMoney'] * 1 != 0) {
				$dataArr[$key]['orderTempMoney'] = 0;
			}
		}
		return model_contract_common_contExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}

	/****************************************************************/
	/**
	 * ���������ϴ�ҳ��
	 */
	function c_toUploadFile() {
		$this->assign("serviceId", $_GET['id']);
		$this->assign("serviceType", $_GET['type']);

		$text = $_GET['type'] . "2";
		$this->assign("serviceType2", $text);
		$this->display('uploadfile');
	}
	/**
	 * ���������ϴ�����
	 */
	function c_uploadfile() {
		$row = $_POST[$this->objName];
		$id = $this->service->uploadfile_d($row);
		if ($id) {
			msg('��ӳɹ���');
		}
	}
	/*************************end**********************************/
	/**
	 * ��ʱ ��������
	 */
	function c_exportLicenseExcel() {
		$orderDao = new model_projectmanagent_order_orderequ();
		$license = $orderDao->licenseId($_GET['id']);
		foreach ($license as $key => $val) {
			$tempDao = new model_yxlicense_license_tempKey();
			$licAdress[$key] = $tempDao->licenseAdress($license[$key]['license']);
		}

		foreach ($licAdress as $key => $val) {
			$cont = $licAdress[$key]['Path'];
			$type = $licAdress[$key]['licenseType'];
			$proId = $licAdress[$key]['extId'];
			$Dao = new model_contract_common_contExcelUtil();
			$licenseinfo[$proId][$type] = $Dao->htmlLicense($cont, $type);
		}
		//	    echo "<pre>";
		//	    print_r($licenseinfo);
		$licArr = array ();
		foreach ($licenseinfo as $key => $val) {
			//$key : ������Ϣ��Ӧ���ϵ�id
			foreach ($val as $k => $v) {
				//$k : ������Ϣ������
				switch ($k) {
					case "WT" :
						foreach ($v as $a => $b) {
							$licArr['WT'][$b] = "��";
						}
						break;
					case "RCU" :
						foreach ($v as $a => $b) {
							$licArr['RCU'][$b] = "��";

							//                          $licArr['licType']= 'RCU';
						}

						break;
					case "FL" :
						foreach ($v as $a => $b) {
							$licArr['FL'][$b] = "��";

							//                          $licArr['licType']= 'RCU';
						}
						break;
				}

			}

		}
		//                       echo "<pre>";
		//                       print_r($licArr);
		set_time_limit(0);
		return model_contract_common_contExcelUtil :: exportLicense($licArr);

		//         return $license;exportLicense

	}
	/**
	 *
	 * ��ͬ������Ϣ����ҳ��
	 * @author huangzf
	 */
	function c_toImportOrderPro() {
		$this->view("equipe-import");
	}

	/**
	 *
	 * ��ͬ������Ϣ����
	 * @author huangzf
	 */
	function c_importOrderProExcel() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_contract_common_orderExcelUtil();
			$excelData = $dao->readExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');

			$orderequDao = new model_projectmanagent_order_orderequ();
			$resultArr = $orderequDao->importProInfo($excelData);
			if ($resultArr)
				echo util_excelUtil :: showResult($resultArr, "��ͬ������Ϣ������", array (
					"��������",
					"���"
				));
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d();
		if ($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']) {
			$rows = $this->service->filterContractMoney_d($rows);
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��������ú�ͬgrid
	 */
	function c_orderForIncomePj() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d();
		$rows = $this->service->getInvoiceAndIncome_d($rows, 2, $service->tbl_name);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��������ú�ͬgrid
	 */
	function c_allOrderForIncomePj() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d('financeview_orderinvoice');
		$rows = $this->service->getInvoiceAndIncome_d($rows, 2, $service->tbl_name);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �ӱ�ѡ�����ϴ�������
	 */
	function c_ajaxorder() {
		$isEdit = isset ($_GET['isEdit']) ? $_GET['isEdit'] : null;
		$configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId'], $isEdit);
		echo $configInfo[0];
	}

	/**
	 * ���� ��ͬ�ڿͻ�����û�пͻ�ID ����
	 */
	function c_disposeCus() {
		$info = $this->service->disCustomer();
		foreach ($info as $key => $val) {
			if (empty ($val['AreaLeader'])) {
				$val['Area'] = $val['AreaName'];
			} else {
				$val['Area'] = $val['AreaName'] . "��" . $val['AreaLeader'] . "��";
			}
			$val['TypeOne'] = $this->getDataNameByCode($val['TypeOne']);
			$infoCus[$key]['Area'] = $val['Area'];
			$infoCus[$key]['TypeOne'] = $val['TypeOne'];
			$infoCus[$key]['Name'] = $val['Name'];
		}

		if (count($infoCus) == 0) {
			echo "<script>alert('�������!');window.close();</script>";
			return 0;
		}
		set_time_limit(0);
		return model_contract_common_contExcelUtil :: exportDisCus($infoCus);
	}

	/**
	* ����������ȡҵ���������
	*/
	function c_getCountByNameForView() {
		$val = util_jsonUtil :: iconvUTF2GB($_REQUEST['nameVal']);
		$sql = "SELECT COUNT(orgid) as sp_counter FROM view_oa_order where isTemp = 0 and (orderCode = '$val' or orderTempCode = '$val')";
		$result = $this->service->_db->getArray($sql);
		echo $result[0]['sp_counter'];
	}

	/**
	 *
	 * ���ݺ�ͬid ��ͬ���ͻ�ȡ��ͬ������
	 */
	function c_ajaxPrincipal() {
		$result = $this->service->findPrincipal($_POST['contractId'], $_POST['contractType']);
		echo util_jsonUtil :: encode($result);

	}

	/**********************************************************************************************/
	/**
	 * ��֤��ͬ�ŵ�Ψһ��
	 */
	function c_ajaxCode() {
		$service = $this->service;
		$projectName = isset ($_GET['ajaxOrderCode']) ? $_GET['ajaxOrderCode'] : false;
		$orderId = isset ($_GET['id']) ? $_GET['id'] : false;
		$projectNameLS = "LS" . $projectName;
		$searchArr = array (
			"ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
		);
		$isRepeat = $service->isRepeat($searchArr, $orderId);

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		}
	}
	function c_ajaxTempCode() {

		$service = $this->service;
		$projectName = isset ($_GET['ajaxOrderTempCode']) ? $_GET['ajaxOrderTempCode'] : false;
		$orderId = isset ($_GET['id']) ? $_GET['id'] : false;
		$projectNameLS = "LS" . $projectName;
		$searchArr = array (
			"ajaxCodeChecking" => "sql: and (orderCode='$projectName' or orderTempCode='$projectNameLS' or orderTempCode='$projectName')"
		);
		$isRepeat = $service->isRepeat($searchArr, $orderId);

		if ($isRepeat) {
			echo "1";
		} else {
			echo "0";
		}
	}
	/**********************************************************************************************/
	/**
	 * �ֶ��ı��ͬ״̬ (ִ���� ---> �����)
	 */
	function c_completeOrder() {
		$orderId = $_GET['id'];
		$type = $_GET['type'];
		$date = date("Y-m-d");
		switch ($type) {
			case "oa_sale_order" :
				$sql = "update oa_sale_order set state = 4,DeliveryStatus = 8,completeDate='".$date."' where id=" . $orderId . "";
				break;
			case "oa_sale_service" :
				$sql = "update oa_sale_service set state = 4,DeliveryStatus = 8,completeDate='".$date."' where id=" . $orderId . "";
				break;
			case "oa_sale_lease" :
				$sql = "update oa_sale_lease set state = 4,DeliveryStatus = 8,completeDate='".$date."' where id=" . $orderId . "";
				break;
			case "oa_sale_rdproject" :
				$sql = "update oa_sale_rdproject set state = 4,DeliveryStatus = 8,completeDate='".$date."' where id=" . $orderId . "";
				break;
		}
		$this->service->query($sql);
		$this->service->updateProjectProcess(array (
			"id" => $orderId,
			"tablename" => $type
		)); //���¹��������ȡ������������Ⱥ�ͬ��
	}
	/**
	 * �ֶ��ı��ͬ״̬ (����� --> ִ����)
	 */
	function c_exeOrder() {
		$orderId = $_GET['id'];
		$type = $_GET['type'];
		switch ($type) {
			case "oa_sale_order" :
				$orderInfo = $this->service->get_d($orderId);
				$flag = array (); //�ж� ����������־����
				foreach ($orderInfo as $k => $v) {
					$sql1 = "SELECT id FROM oa_sale_order_equ where orderId = '" . $orderId . "' and isTemp = '0' and isDel = '0'  and (number - executedNum) > 0";
					$isR = $this->service->_db->getArray($sql1);
					if (!empty ($isR)) {
						$flag[$k]['flag'] = "1";
					}
				}
				if (!empty ($flag)) {
					$sql = "update oa_sale_order set state = 2,DeliveryStatus = 10 where id=" . $orderId . "";
					$this->service->query($sql);
					echo 1;
					break;
				} else {
					echo 0;
					break;
				}

			case "oa_sale_service" :
				$serviceDao = new model_engineering_serviceContract_serviceContract();
				$orderInfo = $serviceDao->get_d($orderId);
				$flag = array (); //�ж� ����������־����
				foreach ($orderInfo as $k => $v) {
					$sql1 = "SELECT id FROM oa_service_equ where orderId = '" . $orderId . "' and isTemp = '0' and isDel = '0'  and (number - executedNum) > 0";
					$isR = $this->service->_db->getArray($sql1);
					if (!empty ($isR)) {
						$flag[$k]['flag'] = "1";
					}
				}
				if (!empty ($flag)) {
					$sql = "update oa_sale_service set state = 2,DeliveryStatus = 10 where id=" . $orderId . "";
					$this->service->query($sql);
					echo 1;
					break;
				} else {
					echo 0;
					break;
				}

			case "oa_sale_lease" :
				$rentalDao = new model_contract_rental_rentalcontract();
				$orderInfo = $rentalDao->get_d($orderId);
				$flag = array (); //�ж� ����������־����
				foreach ($orderInfo as $k => $v) {
					$sql1 = "SELECT id FROM oa_lease_equ where orderId = '" . $orderId . "' and isTemp = '0' and isDel = '0'  and (number - executedNum) > 0";
					$isR = $this->service->_db->getArray($sql1);
					if (!empty ($isR)) {
						$flag[$k]['flag'] = "1";
					}
				}
				if (!empty ($flag)) {
					$sql = "update oa_sale_lease set state = 2,DeliveryStatus = 10 where id=" . $orderId . "";
					$this->service->query($sql);
					echo 1;
					break;
				} else {
					echo 0;
					break;
				}

			case "oa_sale_rdproject" :
				$rdDao = new model_rdproject_yxrdproject_rdproject();
				$orderInfo = $rdDao->get_d($orderId);
				$flag = array (); //�ж� ����������־����
				foreach ($orderInfo as $k => $v) {
					$sql1 = "SELECT id FROM oa_rdproject_equ where orderId = '" . $orderId . "' and isTemp = '0' and isDel = '0'  and (number - executedNum) > 0";
					$isR = $this->service->_db->getArray($sql1);
					if (!empty ($isR)) {
						$flag[$k]['flag'] = "1";
					}
				}
				if (!empty ($flag)) {
					$sql = "update oa_sale_rdproject set state = 2,DeliveryStatus = 10 where id=" . $orderId . "";
					$this->service->query($sql);
					echo 1;
					break;
				} else {
					echo 0;
					break;
				}
		}
		$this->service->updateProjectProcess(array (
			"id" => $orderId,
			"tablename" => $type
		)); //���¹��������ȡ������������Ⱥ�ͬ��
	}

	/**
	 * ��ͬͼ�α���
	 */
	function c_contractReport() {
		$service = $this->service;
		$reportTypeCn = "��ͬ����";
		$countField = "count(o.id)";
		if ($_GET['reportType'] == "money") {
			$reportTypeCn = "��ͬ���";
			$countField = "sum(o.orderMoney)";
			$this->assign("reportType", $_GET['reportType']);
		} else {
			$this->assign("reportType", "");
		}

		//ʱ�䴦��
		$sqlPlus = "";
		if (!empty ($_GET['startTime'])) {
			$sqlPlus .= " and o.createTime>='" . $_GET['startTime'] . " 00:00:00.0'";
			$this->assign("startTime", $_GET['startTime']);
		} else {
			$this->assign("startTime", "");
		}
		if (!empty ($_GET['endTime'])) {
			$sqlPlus .= " and o.createTime<='" . $_GET['endTime'] . " 00:00:00.0'";
			$this->assign("endTime", $_GET['endTime']);
		} else {
			$this->assign("endTime", "");
		}

		//����Ĭ������
		$areaChartConf = array (
			'exportFileName' => "��������ͳ��$reportTypeCn",
			'caption' => "��������ͳ��$reportTypeCn",
			'formatNumberScale' => 0

				//'exportAtClient'=>'0',               //0: �����������У� 1�� �ͻ�������
		//'exportAction'=>"download"             //����Ƿ��������У�֧�����������or����������

		);
		$charts = new model_common_fusionCharts();
		$rows1 = $service->getContractByArea($countField, $sqlPlus);
		$result1 = $charts->showCharts($rows1, '', $areaChartConf, 960);
		$this->assign('resultArea', $result1);

		//����Ĭ������
		$typeChartConf = array (
			'exportFileName' => "����ͬ����ͳ��$reportTypeCn",
			'caption' => "����ͬ����ͳ��$reportTypeCn"
		);

		$rows2 = $service->getContractByType($countField, $sqlPlus);
		$result2 = $charts->showCharts($rows2, '', $typeChartConf);
		$this->assign('resultType', $result2);

		//��ͬ״̬Ĭ������
		$statusChartConf = array (
			'exportFileName' => "����ͬ״̬ͳ��$reportTypeCn",
			'caption' => "����ͬ״̬ͳ��$reportTypeCn"
		);
		//$service->groupBy="tablename";
		$rows3 = $service->getContractByStatus($countField, $sqlPlus);

		$result3 = $charts->showCharts($rows3, '', $typeChartConf);
		$this->assign('resultStatus', $result3);

		$this->view('charts');
	}

	/***************************�������************************************************************/
	/**
	* ������ ����
	*/
	function c_FinancialImportexcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display("FinancialImportexcel");
	}

	/**
	 * �ϴ�EXCEL
	 */
	function c_finalceMoneyImport() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		$import = $_POST['import'];
		//		$objNameArr = array (
		//			0 => 'contractType',//��ͬ����
		//			1 => 'contractCode', //��ͬ���
		//			2 => 'serviceconfirmMoney', //����ȷ������
		//			3 => 'financeconfirmMoney', //����ȷ���ܳɱ�
		//			4 => 'deductMoney'//�ۿ���
		//		   ) ;
		$objNameArr = array (
				0 => 'contractCode', //��ͬ���
		1 => 'money', //���

		);
		if ($import['importType'] == "��ʼ������") {
			$this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
		} else {
			//������Ϣ ����
			$infoArr = array (
				"importMonth" => date("Y"
				) . $import['Month'], //�����·�
		"moneyType" => $import['importInfo'], //�������
		"importName" => $_SESSION['USERNAME'], //������
		"importNameId" => $_SESSION['USER_ID'], //������ID
		"importDate" => date("Y-m-d"), //����ʱ��
	);

			$this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);
		}
	}

	/**
	 * ��֤����Ľ���Ƿ��Ѵ���
	 */
	function c_getFimancialImport() {
		$importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
		$importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
		$importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

		if ($importType == "���µ���") {
			$num = $this->service->getFimancialImport_d($importMonth, $importSub);
			echo $num;
		} else {
			echo "0";
		}
	}

	/**
	 * ��������ϸ�б�Tab
	 */
	function c_financialDetailTab() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialDetailTab");
	}
	/**
	 * ��������Ϣ
	 */
	function c_financialDetail() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialDetail");
	}
	function c_financialdetailpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$conId = $_GET['id'];
		$tablename = $_GET['tablename'];
		$moneyType = $_GET['moneyType'];
		$rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
		//echo "<pre>";
		//print_R($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * ��������Ϣ
	 */
	function c_financialImportDetail() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialImportDetail");
	}
	function c_financialImportDetailpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$conId = $_GET['id'];
		$tablename = $_GET['tablename'];
		$moneyType = $_GET['moneyType'];
		$rows = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);

		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ���¹��������ȼ����
	 */
	function c_updateProjectProcess() {
		try {
			$rows = $this->service->listBySqlId('select_orderinfo');
			$this->service->projectProcess_d($rows);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		echo 1;
	}
	/**
	 * ���º�ͬ״̬
	 */
	function c_updateContractState(){
       try {
			$rows = $this->service->listBySqlId('select_orderinfo');
			$this->service->updateContractState_d($rows);
		} catch (Exception $e) {
		   echo $e->getMessage();
		}
		   echo 1;
	}

	/***************************************************************************************/

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits() {
		$limitName = util_jsonUtil :: iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}


	function c_toUpdateEquInfo(){
		$this->view('updateEquInfo');
	}
}
?>