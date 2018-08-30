<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @author LiuBo
 * @Date 2011年3月3日 19:08:54
 * @version 1.0
 * @description:合同主表控制层 项目主表
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
	 * 合同物料信息tab页
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
	 * 合同物料信息 ---备注
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
	 * 延迟发货通知列表页
	 */
	function c_delayShipments() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->display('delayShipments');
	}
	//延迟发货列表页 Pagejson
	function c_delayShipmentsJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('select_zsporder');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	//延迟发货列表 --通知发货
	function c_informShipments() {
		$orderttype = $_GET['orderType'];
		$orderId = $_GET['id'];
		$orderInfo = $this->service->findOrderInfo($orderId, $orderttype);
		foreach ($orderInfo[0] as $key => $val) {
			$this->assign($key, $val);
		}
		switch ($orderInfo[0]['tablename']) {
			case "oa_sale_order" :
				$this->assign("tablenameH", "销售合同");
				break;
			case "oa_sale_service" :
				$this->assign("tablenameH", "服务合同");
				break;
			case "oa_sale_lease" :
				$this->assign("tablenameH", "租赁合同");
				break;
			case "oa_sale_rdproject" :
				$this->assign("tablenameH", "研发合同");
				break;
		}
		$this->assign("orderid", $orderId);
		$this->display('informShipments');
	}
	//延迟发货列表 --通知发货 操作方法
	function c_informS() {
		$inform = $_POST["inform"];
		$flag = $this->service->inform_d($inform);
		msg("发货通知已发送！");
	}
	/**
	 * 选择ht
	 */
	function c_selectOrder() {
		$this->assign('showButton', $_GET['showButton']);
		$this->assign('showcheckbox', $_GET['showcheckbox']);
		$this->assign('checkIds', $_GET['checkIds']);
		$this->view('selectorder');
	}

	/**
	 * 高级搜索
	 */
	function c_search() {
		$this->assign("gridName", $_GET['gridName']);
		$this->display('search');
	}
	/**
	 * 我的合同--所有信息
	 */
	function c_toMyOrderInfo() {
		$this->assign('user', $_SESSION['USER_ID']);
		$this->display("myorderinfo");
	}
	//我的合同--所有信信息PageJson
	function c_myOrderJson() {
		//		if(isset($this->service->this_limit['销售区域'])){
		//			$_POST['areaCode'] = $this->service->this_limit['销售区域'];
		//		}
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		//		 $export = isset ( $service->this_limit ['单条合同导出'] ) ? $service->this_limit ['单条合同导出'] : null;

		$rows = $service->pageBySqlId('select_orderinfo');
		//工作量进度
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
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d();
		if ($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']) {
			$rows = $this->service->filterContractMoney_d($rows, $this->service->tbl_name);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/***************************************************************************************************/
	/**
	 * 我的合同--未提交审批的合同主页面
	 */
	function c_toWtjmain() {
		$this->display("myorder-wtjmain");
	}
	/**
	 * 我的合同--未提交审批的合同导航
	 */
	function c_toWtjmenu() {
		$this->display("myorder-wtjmenu");
	}
	/**
	 * 我的合同--在审批主页面
	 */
	function c_toWspmain() {
		$this->display("myorder-wspmain");
	}
	/**
	 * 我的合同---在审批合同导航
	 */
	function c_toWspmenu() {
		$this->display("myorder-wspmenu");
	}

	/**
	 * 我的合同--正执行主页面
	 */
	function c_toZzxMain() {
		$this->display("myorder-zzxmain");
	}
	/**
	 * 我的合同--正执行合同导航
	 */
	function c_toZzxMenu() {
		$this->display("myorder-zzxmenu");
	}
	/**
	 * 我的合同--已完成合同主页面
	 */
	function c_toYwcMain() {
		$this->display("myorder-ywcmain");
	}
	/**
	 * 我的合同--已完成合同导航
	 */
	function c_toYwcMenu() {
		$this->display("myorder-ywcmenu");
	}
	/**
	 * 我的合同--已关闭合同主页面
	 */
	function c_toYwgMain() {
		$this->display("myorder-ywgmain");
	}
	/**
	 * 我的合同--已关闭合同导航
	 */
	function c_toYwgMenu() {
		$this->display("myorder-ywgmenu");
	}

	/**
	 * 合同拆分
	 */
	function c_toSplit() {
		$this->permCheck(); //安全校验
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		//渲染从表
		$rows = $this->service->initEdit($rows);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '是') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '已拿到') {
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
	 * 转为正式合同
	 */
	function c_toBecomeOrder() {
		$this->permCheck(); //安全校验
		$this->assign('orderInput', ORDER_INPUT);
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
		//渲染从表
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
	* 转正式合同
	*/
	function c_become($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$orderCodeDao = new model_common_codeRule();
		if ($object['orderInput'] == "1") {
			$object['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $object['customerId']);
			$this->service->becomeEdit_d($object);
			msgRF('已转为正式合同');
		} else
			if ($object['orderInput'] == "0") {
				$this->service->becomeEdit_d($object);
				msgRF('已转为正式合同');
			} else {
				msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
			}
	}
	/***************************************************************************************************/
	/*
	 * 跳转到合同主表
	 */
	function c_page() {
		$this->display('list');
	}
	/**
	 * License 配置
	 */
	function c_License() {
		$this->display("License");
	}
	/**
	 * 跳转到新增页面
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
			$this->customerProCity($rows['customerId']); //根据客户获取 客户的省市信息
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
	 * 根据客户ID 获取 客户的省市信息
	 */
	function customerProCity($customerId) {
		$dao = new model_customer_customer_customer();
		$proId = $dao->find(array (
			"id" => $customerId
		), null, "ProvId"); //省份ID
		$cityId = $dao->find(array (
			"id" => $customerId
		), null, "CityId"); //城市ID
		$this->assign("orderProvinceId", $proId['ProvId']);
		$this->assign("orderCityId", $cityId['CityId']);
	}
	/**
	 * 我的项目Tab 页
	 */
	function c_myOrderTab() {
		$this->display('myorder-tab');
	}
	/**
	 * 所有合同信息
	 */
	function c_orderInfo() {
		$this->display('orderinfo');
	}
	/**
	 * 未审批的合同
	 */
	function c_myOrderWtjsp() {
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-wtjsp');
	}
	/**
	 * 审批中的合同
	 */
	function c_myOrderWtj() {
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-wtj');
	}

	/**
	 * 未审批的合同
	 */
	function c_myorderWsp() {
		$this->display('myorder-wsp');
	}
	/**
	 * 审批中的合同
	 */
	function c_myOrderSpz() {
		$this->display('myorder-spz');
	}
	/**
	 * 执行中的合同
	 */
	function c_myOrderZxz() {
		$this->display('myorder-zxz');
	}
	/**
	 * 已完成的合同
	 */
	function c_myOrderYwc() {
		$this->display('myorder-ywc');
	}
	/**
	 * 已关闭的合同
	 */
	function c_myOrderYgb() {
		$this->display('myorder-Ygb');
	}
	/**
	 * 我的合同---在审批合同
	 */
	function c_myApplyOrder() {
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->display('myorder-apply');
	}
	/**
	 * 我的合同--已关闭合同
	 */
	function c_myChargeOrder() {

		$this->assign('prinvipalId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-charge');
	}

	/**
	 * 我的合同--在执行合同
	 */
	function c_myExecuteOrder() {
		$this->assign('executeId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-execute');
	}
	/**
	 * 我的合同--已完成合同
	 */
	function c_myCompleteOrder() {
		$this->assign('executeId', $_SESSION['USER_ID']);
		$this->assign("userId", $_SESSION['USER_ID']);
		$this->display('myorder-complete');
	}
	/**
	 * 指定合同负责人页面
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
	 * 指定合同执行人页面
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
	 * 我的审批 - tab
	 */
	function c_auditTab() {
		$this->display('audittab');
	}

	/**
	 * 我的审批 － 未审批页面
	 */
	function c_toAuditNo() {
		$this->display('auditno');
	}

	/**
	 * 我的审批 － 已审批的页面
	 */
	function c_toAuditYes() {
		$this->display('audityes');
	}
	function c_toAuditView() {
		$this->display('auditview');
	}
	/**未审批的变更合同列表
	 *author can
	 *2011-6-1
	 */
	function c_toChangeAuditNO() {
		$this->display('change-auditno');
	}

	/**已审批的变更合同列表
	 *author can
	 *2011-6-1
	 */
	function c_toChangeAuditYes() {
		$this->display('change-audityes');
	}

	/**
	 * 合同签订合同页面
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
	 * 合并临时合同 获取合同内容
	 */
	function c_ajaxList() {
		$rows = $this->service->get_d($_POST['id']);
		$rows = util_jsonUtil :: encode($rows);
		echo $rows;
	}

	/**
	 * 跳转到合同处理Tab页
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
	 * 跳转合同出处理页面
	 */
	function c_toLokStockByOrder($id) {
		$service = $this->service;
		$rows = $service->get_d($_GET['id']);
		$rows = $service->showDetaiInfo($rows);
		$this->assign("id", $_GET['id']);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		//拿到默认仓库的信息(调用仓库接口)
		$stockInfoDao = new model_stock_stockinfo_stockinfo();
		$stockInfo = $stockInfoDao->getDefaultStockInfo();
		$this->assign('stockName', $stockInfo['stockName']);
		$this->assign('stockId', $stockInfo['id']);
		$this->display('lockstock');

	}

	/**
	 * 选择开票对象
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
	 * 合同已签订合同信息
	 */
	function c_toSalesInfo() {
		$this->assign('id', $_GET['id']);
		$this->display('salesinfo');
	}

	/**********************开票收款权限部分*********************************/
	/**
	 * 开票tab
	 */
	function c_toInvoiceTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['销售合同开票tab'])) {
			$url = '?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo '没有权限,需要开通权限请联系oa管理员';
		}
	}

	/**
	 * 开票申请tab
	 */
	function c_toInvoiceApplyTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['销售合同开票tab'])) {
			$url = '?model=finance_invoiceapply_invoiceapply&action=getInvoiceapplyList&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo '没有权限,需要开通权限请联系oa管理员';
		}
	}

	/**
	 * 到款tab
	 */
	function c_toIncomeTab() {
		$obj = $_GET['obj'];
		if ($this->service->isKeyMan_d($obj['objId']) || !empty ($this->service->this_limit['销售合同到款tab'])) {
			$url = '?model=finance_income_incomeAllot&action=orderIncomeAllot&obj[objType]=' . $obj['objType'] . '&obj[objCode]=' . $obj['objCode'] . '&obj[objId]=' . $obj['objId'] . '&skey=' . $_GET['skey'];
			succ_show($url);
		} else {
			echo '没有权限,需要开通权限请联系oa管理员';
		}
	}

	/**********************开票收款权限部分*********************************/
	/****************************************************************************************/

	/**
	 * 关闭合同页面
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
	 * 关闭合同
	 */
	function c_close($isEditInfo = false) {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$object = $_POST[$this->objName];
		$id = $this->service->close_d($object, $isEditInfo);
		if ($id && $_GET['actType'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_close.php?actTo=ewfSelect&billId=' . $object['id']);
		} else {
			msg('关闭成功！');
		}
	}
	/**
	 * 添加执行人/负责人
	 */
	function c_Execute() {
		$id = $this->service->ExecuteEdit_d($_POST[$this->objName], true);
		if ($id) {
			msgRF('修改成功！');
		}
	}

	/**
	 * 签订合同---添加合同信息
	 */
	function c_salesAdd() {
		$id = $this->service->salesAdd_d($_POST[$this->objName]);

		if ($id) {
			showmsg('保存成功！', '?model=contract_sales_sales&action=myApplyTab');
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$pId = isset ($_GET['pId']) ? $_GET['pId'] : null;
		$orderInfo = $_POST[$this->objName];
		//		if ($orderInfo ['orderInput'] == "1") {
		//			$orderCodeDao = new model_common_codeRule ();
		//			if ($orderInfo ['sign'] == "否") {
		//				$orderInfo ['orderTempCode'] = $orderCodeDao->contractCode ( "oa_sale_order", $orderInfo ['customerId'] );
		//				$orderInfo ['orderTempCode'] = "LS".$orderInfo ['orderTempCode'];
		//			} else if ($orderInfo ['sign'] == "是") {
		//				$orderInfo ['orderCode'] = $orderCodeDao->contractCode ( "oa_sale_order", $orderInfo ['customerId'] );
		//			}
		//			$id = $this->service->add_d ( $orderInfo );
		//		} else if ($orderInfo ['orderInput'] == "0") {
		//			if(!empty($orderInfo['orderTempCode'])){
		//				$orderInfo['orderTempCode'] = $orderInfo['orderTempCode'];
		//			}
		//			$id = $this->service->add_d ( $orderInfo );
		//		} else {
		//			msgGo ( '请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确' );
		//		}
		$id = $this->service->add_d($orderInfo);
		//判断是否直接提交审批
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_index2.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id && $actType == "goOn") {

				msgGO('继续拆分');

			} else
				if ($id && $pId) {
					$this->service->thisUpdate_d($pId); //改变拆分合同的状态为已拆分

					msgRF('添加成功');
				} else
					if ($id) {
						msgGo('添加成功！', '?model=projectmanagent_order_order&action=toAdd');
					}
		//$this->listDataDict ();
	}

	/**
	 * 商机转合同操作
	 */
	function c_chanceAdd() {
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$pId = isset ($_GET['pId']) ? $_GET['pId'] : null;
		$orderInfo = $_POST[$this->objName];
		if ($orderInfo['orderInput'] == "1") {
			$orderCodeDao = new model_common_codeRule();
			if ($orderInfo['sign'] == "否") {
				$orderInfo['orderTempCode'] = $orderCodeDao->contractCode("oa_sale_order", $orderInfo['customerId']);
			} else
				if ($orderInfo['sign'] == "是") {
					$orderInfo['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $orderInfo['customerId']);
				}
			$id = $this->service->add_d($orderInfo);
		} else
			if ($orderInfo['orderInput'] == "0") {
				$id = $this->service->add_d($orderInfo);
			} else {
				msgGo('请找管理员确认配置合同输入的"ORDER_INPUT"值是否正确');
			}
		//判断是否直接提交审批
		if ($id && $_GET['act'] == "app") {
			succ_show('controller/projectmanagent/order/ewf_index.php?actTo=ewfSelect&billId=' . $id);
		} else
			if ($id) {
				msgRF('添加成功！');
			}
		$this->listDataDict();
	}

	/**
	 * 合同查看页面
	 */
	function c_toViewTab() {
		//		$this->permCheck (); //安全校验
		$this->assign('id', $_GET['id']);
		$isTemp = $this->service->isTemp($_GET['id']);
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('orderCode', $rows['orderCode']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/**审批变更合同的查看页面
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
	 * 合同查看Tab---关闭信息
	 */
	function c_toCloseInfo() {
		$rows = $this->service->get_d($_GET['id']);
		if ($rows['state'] == '3' || $rows['state'] == '9') {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('closeinfo');

		} else {
			echo '<span>暂无相关信息</span>';
		}
	}
	/***************************************************************************************************************/
	/**
	 * 重写int
	 */
	function c_init() {
		//		$this->permCheck (); //安全校验
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);
		if ($perm == 'view') {
			$rows['orderequ'] = $this->service->filterWithoutField('销售设备金额', $rows['orderequ'], 'list', array (
				'price',
				'money'
			));
			//权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
			if ($rows['areaPrincipalId'] != $_SESSION['USER_ID'] && $rows['createId'] != $_SESSION['USER_ID'] && $rows['prinvipalId'] != $_SESSION['USER_ID']) {
				$rows = $this->service->filterWithoutField('销售合同金额', $rows, 'form', array (
					'orderMoney',
					'orderTempMoney'
				));
				$rows['orderequ'] = $this->service->filterWithoutField('销售设备金额', $rows['orderequ'], 'list', array (
					'price',
					'money'
				));
				$rows['linkman'] = $this->service->filterWithoutField('销售联系人控制', $rows['linkman'], 'list', array (
					'Email',
					'telephone'
				));
			}

			//附件
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
			//合同文本权限与销售合同金额权限一致 add by chengl 2011-10-15

			if ($rows['areaPrincipalId'] != $_SESSION['USER_ID'] && $rows['createId'] != $_SESSION['USER_ID'] && $rows['prinvipalId'] != $_SESSION['USER_ID']) {
				if (!empty ($this->service->this_limit['销售合同金额'])) {
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

			if ($rows['sign'] == '是') {
				$this->assign('sign', '是');
			} else
				if ($rows['sign'] == '否') {
					$this->assign('sign', '否');
				}

			if ($rows['orderstate'] == '已提交') {
				$this->assign('orderstate', '已提交');
			} else
				if ($rows['orderstate'] == '已拿到') {
					$this->assign('orderstate', '已拿到');
				}
			if ($rows['shipCondition'] == '0') {
				$this->assign('shipCondition', '立即发货');
			} else
				if ($rows['shipCondition'] == '1') {
					$this->assign('shipCondition', '通知发货');
				} else {
					$this->assign('shipCondition', '');
				}
			$orderTempCode = explode(',', $rows['orderTempCode']);
			$this->assign('orderTempCode', $this->service->TempOrderView($orderTempCode, $_GET['skey']));
			$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
			$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
			$this->display('view');
		} else {

			//附件
			$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
			//合同文本权限与销售合同金额权限一致 add by chengl 2011-10-15
			$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
			$rows = $this->service->initEdit($rows);

			foreach ($rows as $key => $val) {
				$this->show->assign($key, $val);
			}

			if ($perm == 'signIn') {

				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					}
				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
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
				if ($rows['sign'] == '是') {
					$this->assign('signYes', 'checked');
				} else
					if ($rows['sign'] == '否') {
						$this->assign('signNo', 'checked');
					}
				if ($rows['orderstate'] == '已提交') {
					$this->assign('orderstateYes', 'checked');
				} else
					if ($rows['orderstate'] == '已拿到') {
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
	 * 审批查看页面
	 */
	function c_toViewForAudit() {
		//		$this->permCheck (); //安全校验
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], false, 'oa_sale_order2');
		$rows = $this->service->initView($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}

		if ($rows['sign'] == '是') {
			$this->assign('sign', '是');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('sign', '否');
			}

		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstate', '已提交');
		} else
			if ($rows['orderstate'] == '已拿到') {
				$this->assign('orderstate', '已拿到');
			}
		if ($rows['shipCondition'] == '0') {
			$this->assign('shipCondition', '立即发货');
		} else
			if ($rows['shipCondition'] == '1') {
				$this->assign('shipCondition', '通知发货');
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
	 * 修改物料
	 */
	function c_productEdit() {
		if (!isset ($this->service->this_limit['物料修改']) || $this->service->this_limit['物料修改'] != 1) {
			echo "没有物料修改的权限，请联系OA管理员开通";
			exit ();
		}
		$this->permCheck(); //安全校验
		$rows = $this->service->get_d($_GET['id']);
		//		foreach ($rows['orderequ'] as $k => $v){
		//        	 if($v['purchasedNum'] > 0 || $v['issuedShipNum'] > 0){
		//                   echo "该合同已执行发货或采购操作，请选择变更流程修改物料";
		//                   exit();
		//        	 }
		//        }
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows = $this->service->editProduct($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '是') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '已拿到') {
				$this->assign('orderstateNo', 'checked');
			}

		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('productedit');
	}
	/**跳转到销售合同变更页面
	 *author can
	 *2011-6-1
	 */
	function c_toChange() {
		$this->permCheck(); //安全校验
		$changeLogDao = new model_common_changeLog('order');
		if ($changeLogDao->isChanging($_GET['id'])) {
			msgGo("该合同已在变更审批中，无法变更.");
		}
		$changer = isset ($_GET['changer']) ? $_GET['changer'] : null;
		$changeC = isset ($_GET['changeC']) ? $_GET['changeC'] : null;
		$rows = $this->service->get_d($_GET['id']);
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '是') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '已拿到') {
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

	/**变更销售合同
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
					//判断物料是否有下达发货，下达采购，下达生产
					$fsql = "select count(id) as isExe from oa_sale_order_equ where (issuedPurNum > 0 or issuedProNum >0 or issuedShipNum>0) and id=" . $val['oldId'] . " ";
					$isExeArr = $this->service->_db->getArray($fsql);
					$isExe += $isExeArr[0]['isExe'];
					$isDel = "1";
					$purchaseArr[] = $this->service->purchaseMail($val['oldId']);
				}
			}
            //如果删除的物料有下达采购的，发邮件给采购部
            if(!empty($purchaseArr[0])){
               //获取默认发送人
		       include (WEB_TOR."model/common/mailConfig.php");
		       $toMailId = $mailUser['contractChangepurchase']['sendUserId'];
		       $emailDao = new model_common_mail();
		       if(empty($orderInfo['orderCode'])){
		       	  $orderCode = $orderInfo['orderTempCode'];
		       }else{
		       	  $orderCode = $orderInfo['orderCode'];
		       }
		       //发送人，发送人邮箱，title,收件人,附加信息
			   $emailInfo = $emailDao->contractChangepurchaseMail($_SESSION['USERNAME'],$_SESSION['EMAIL'],"销售变更物料提醒",$toMailId,$purchaseArr,$orderCode);
            }
			foreach ($orderInfo['orderequTemp'] as $key => $val) {
				if (empty ($val['productName'])) {
					unset ($orderInfo['orderequTemp'][$key]);
				}
			}
			if ($isDel == "1" && $isExe != "0") {
				$formName = "销售合同变更（删物料）";
				//	         $formName="销售订单审批";
			} else {
				$formName = "销售订单审批";
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
			msgBack2("变更失败！失败原因：" . $e->getMessage());
		}
	}

	/**
	 * add by can 2011-06-01
	 * 确认合同变更并且跳转到未审批的合同列表页
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (!empty ($_GET['spid'])) {
			$this->service->confirmChange($_GET['spid']);
		}
		$urlType = isset ($_GET['urlType']) ? $_GET['urlType'] : null;
		//防止重复刷新
		if ($urlType) {
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		} else {
			echo "<script>this.location='?model=projectmanagent_order_order&action=allAuditingList'</script>";
		}
	}
	/**
	 * 取消变更提醒
	 */
	function c_cancelBecome() {
		$orderId = $_GET['id'];
		$sql = "update oa_sale_order set isBecome = 0 where id = $orderId";
		$this->service->query($sql);
		return $orderId;
	}
	/**
	 * 审批通过后发送邮件并跳回列表
	 */
	function c_configOrder() {
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		$objId = $folowInfo['objId'];
		$this->service->configOrder_d($objId);
		$contract = $this->service->get_d($objId);
		if ($contract['ExaStatus'] == "完成") {
			$toorderDao = new model_projectmanagent_borrow_toorder();
			$toorderDao->findLoan($objId, "order", "add");
		}
		echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
	}
	/**
	 * 未审批Json
	 */
	function c_pageJsonAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售订单审批';
		$rows = $service->pageBySqlId('auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 已审批Json
	 */
	function c_pageJsonAuditYes() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售订单审批';
		$rows = $service->pageBySqlId('audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**未审批的变更合同
	 *author can
	 *2011-6-1
	 */
	function c_changeAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售订单审批';
		$rows = $service->pageBySqlId('change_auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**已审批的变更合同
	 *author can
	 *2011-6-1
	 */
	function c_changeAuditYes() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售订单审批';
		$rows = $service->pageBySqlId('change_audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 合同已签订合同的PageJson
	 */
	function c_salesInfoPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		//$service->asc = false;
		$service->searchArr['projectId'] = $_GET['id'];
		$rows = $service->pageBySqlId('select_sales');

		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$orderCodeDao = new model_common_codeRule();
		if ($object['sign'] == "是" && $object['orderCode'] == '') {
			$object['orderCode'] = $orderCodeDao->contractCode("oa_sale_order", $object['customerId']);
		}
		if ($this->service->edit_d($object, $isEditInfo)) {
			msgRF('编辑成功');
		}
	}
	/**
	 * 物料修改
	 */
	function c_proedit($isEditInfo = false) {
		$object = $_POST[$this->objName];
		$id = $this->service->proedit_d($object, $isEditInfo);

		if ($id) {
			$this->service->updateOrderShipStatus_d($id);
			msgRF('编辑成功');
		}
	}

	/**
	 * 批量删除对象
	 */
	function c_deletesInfo() {
		$deleteId = isset ($_GET['id']) ? $_GET['id'] : exit ();
		$delete = $this->service->deletesInfo_d($deleteId);
		if ($delete) {
			msg('删除成功');
		}
	}
	/******************************************合同签收*********************************************************/
	/**
	 * 合同签收tab页
	 */
	function c_toSinginTab() {
		$this->display("signintab");
	}
	/**
	 * 未签收合同
	 */
	function c_toSignIn() {
		$this->display("signinlist");
	}
	/**
	 * 已签收合同
	 */
	function c_comSignin() {
		$this->display("comsignin");
	}
	/**
	 * 合同签收
	 */
	function c_toSign() {
		$this->permCheck(); //安全校验
		$perm = isset ($_GET['perm']) ? $_GET['perm'] : null;
		$tablename = isset ($_GET['tablename']) ? $_GET['tablename'] : null;
		$rows = $this->service->get_d($_GET['id']);

		//渲染从表

		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], true);
		$rows['file2'] = $this->service->getFilesByObjId($rows['id'], true, 'oa_sale_order2');
		$rows = $this->service->initChange($rows);

		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		if ($rows['sign'] == '是') {
			$this->assign('signYes', 'checked');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('signNo', 'checked');
			};

		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstateYes', 'checked');
		} else
			if ($rows['orderstate'] == '已拿到') {
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
	 * 合同签收
	 */
	function c_signInVerify($isEditInfo = false) {
		$object = $_POST[$this->objName];
		if ($this->service->signin_d($object, $isEditInfo)) {
			msgRF('签收完成！');
		}
	}

	/************************************************************************************************/
	/**
	 * @ ajax判断项
	 * 临时合同号
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
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletesOrder() {
		//$this->permDelCheck ();
		$orderId = $_POST['id'];
		$orderType = $_POST['type'];
		try {
			//查找是否有 借试用转销售的关联物料 有则删除
			$dao = new model_projectmanagent_borrow_toorder();
			$dao->getRelOrderequ($orderId, $orderType);
			$del = $this->service->deletes_d($orderId);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}
	/*********************************合同发货*********************************************************/
	function c_toShipmentsList() {
		$this->display("shipments-list");
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_shipmentsPageJson() {
		$rateDao = new model_stock_outplan_contractrate();
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		//		$service->sort = 'c.ExaDT';
		$rows = $service->pageBySqlId('select_shipments');
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		//判断 如果审批时间大于等于10天则把红色变更提醒 取消
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
		//发货需求进度备注
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
	/**************************合同信息 （视图） pagejson*********************************************/

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonMyProject() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->pageBySqlId('select_zsporder');
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**************************关闭审批部分**************************************/
	/**
	 * 关闭合同审批查看页
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
	 * 关闭审批tab
	 */
	function c_toCloseAuditTab() {
		$this->display('closeaudittab');
	}

	/**
	 * 未审批的关闭
	 */
	function c_toCloseAuditUndo() {
		$this->display('closeauditundo');
	}
	/******************************************************************************/
	/***************************pageJson******************************************/
	/**
	 * 未审批Json
	 */
	function c_jsonCloseAuditNo() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售异常关闭审批';
		$rows = $service->pageBySqlId('auditing');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 已审批的关闭
	 */
	function c_toCloseAuditDone() {
		$this->display('closeauditdone');
	}

	/**
	 * 已审批Json
	 */
	function c_closeAuditYesJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['workFlow'] = '销售异常关闭审批';
		$rows = $service->pageBySqlId('audited');
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 下达采购计划
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
	 * 区域权限合并
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
	 * 合同信息--未审批合同Json
	 */
	function c_wspOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['销售区域']) ? $this->service->this_limit['销售区域'] : null;
		//		$export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 合同信息--在审批合同Json
	 */
	function c_zspOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['销售区域']) ? $this->service->this_limit['销售区域'] : null;
		//		$export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 合同信息--在执行合同Json
	 */
	function c_zxzOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['销售区域']) ? $this->service->this_limit['销售区域'] : null;
		$comlimit = isset ($this->service->this_limit['改变合同状态']) ? $this->service->this_limit['改变合同状态'] : null;
		//		$export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 合同信息--已关闭合同Json
	 */
	function c_ygbOrderJson() {
		$rows = null;
		$privlimit = isset ($this->service->this_limit['销售区域']) ? $this->service->this_limit['销售区域'] : null;
		//		$export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;
		$arealimit = $this->service->getArea_d();
		$thisAreaLimit = $this->regionMerge($privlimit, $arealimit);
		if (!empty ($thisAreaLimit)) {
			$_POST['areaCode'] = $thisAreaLimit;
			$service = $this->service;
			$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 合同信息--未签收合同Json
	 */
	function c_SignInJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('select_signIn');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$rows = $service->getRowsallMoney_d($rows, "select_orderinfo", $_SESSION['USER_ID']); //统计金额
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * 我的合同-》xxx  （用于导出权限）
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//       $export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;

		//$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		//		foreach($rows as $k => $v){
		//             $rows[$k]['exportOrder'] = $export;
		//		   }
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * 我的合同pageJson
	 */
	function c_myOrderPageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//		$export = isset ( $this->service->this_limit ['单条合同导出'] ) ? $this->service->this_limit ['单条合同导出'] : null;
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->pageBySqlId('select_default');
		$rows = $service->getInvoiceAndIncome_d($rows, 0, $this->service->tbl_name);
		$rows = $service->isGoodsReturn($rows);
		//		foreach($rows as $k => $v){
		//             $rows[$k]['exportOrder'] = $export;
		//		   }
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	//TODO:
	/**
	 * 所有合同信息PageJson
	 */
	function c_OrderInfoJson() {
        $rows = array ();
		$service = $this->service;
		$sortInfo = $_POST['sort'];
		$sortInfo = stripslashes(stripslashes($sortInfo));
		if($sortInfo == "invoiceDifferenceTemp"){
			unset($_POST['sort']);
		}
		$service->getParam($_POST); //设置前台获取的参数信息

		//过滤型权限设置
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
				//                //工作量进度
				//                $rows = $service->projectProcess_d($rows);
				//合同完成权限
				$comLimit = isset ($this->service->this_limit['改变合同状态']) ? $this->service->this_limit['改变合同状态'] : null;
				if ($comLimit) {
					$rows = util_arrayUtil :: setItemMainId('com', 1, $rows);
				}
				//财务金额字段权限
				$financeLimit = isset ($this->service->this_limit['财务金额']) ? $this->service->this_limit['财务金额'] : null;
				if ($financeLimit == '1') {
					foreach ($rows as $key => $val) {
						$rows[$key]['FinanceCon'] = 1;
					}
				}

				//加密
				$rows = $this->sconfig->md5Rows($rows, "orgid");
                $service->sort = "";
				$rows = $service->getRowsallMoney_d($rows, "select_orderinfo", $_SESSION['USER_ID'], $financeLimit); //统计金额
				foreach ($rows as $k => $v) {
					if ($v['tablename'] == 'oa_sale_order' ) {
						//权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('销售合同金额', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							));
							$rows[$k] = $this->service->filterWithoutField('销售合同开票tab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							));
							$rows[$k] = $this->service->filterWithoutField('销售合同到款tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							));
						}
					}
					if ($v['tablename'] == 'oa_sale_service' ) {
						//权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('服务合同金额', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'engineering_serviceContract_serviceContract');
							$rows[$k] = $this->service->filterWithoutField('服务合同开票tab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'engineering_serviceContract_serviceContract');
							$rows[$k] = $this->service->filterWithoutField('服务合同到款tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							), 'engineering_serviceContract_serviceContract');
						}
					}
					if ($v['tablename'] == 'oa_sale_lease' ) {
						//权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('租赁合同金额', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'contract_rental_rentalcontract');
							$rows[$k] = $this->service->filterWithoutField('租赁合同开票tab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'contract_rental_rentalcontract');
							$rows[$k] = $this->service->filterWithoutField('租赁合同到款tab', $rows[$k], 'keyForm', array (
								'incomeMoney',
								'surOrderMoney',
								'surincomeMoney'
							), 'contract_rental_rentalcontract');
						}
					}
					if ($v['tablename'] == 'oa_sale_rdproject' ) {
						//权限过滤,如果是合同负责人和区域负责人、合同创建人，则不限制字段权限过滤
						if ($v['areaPrincipalId'] != $_SESSION['USER_ID'] && $v['createId'] != $_SESSION['USER_ID'] && $v['prinvipalId'] != $_SESSION['USER_ID']) {
							$rows[$k] = $this->service->filterWithoutField('研发合同金额', $v, 'form', array (
								'orderMoney',
								'orderTempMoney'
							), 'rdproject_yxrdproject_rdproject');
							$rows[$k] = $this->service->filterWithoutField('研发合同开票tab', $rows[$k], 'keyForm', array (
								'invoiceMoney',
								'surplusInvoiceMoney'
							), 'rdproject_yxrdproject_rdproject');
							$rows[$k] = $this->service->filterWithoutField('研发合同到款tab', $rows[$k], 'keyForm', array (
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
	 * 权限设置
	 * 权限返回结果如下:
	 * 如果包含权限，返回true
	 * 如果无权限,返回false
	 */
	function initLimit() {
		$service = $this->service;
		//权限配置数组
		$limitConfigArr = array (
			'areaLimit' => 'areaCode',
			'deptLimit' => 'DEPT_ID',
			'customerTypeLimit' => 'customerType'
		);
		//权限数组
		$limitArr = array ();
		//权限系统
		if (isset ($this->service->this_limit['销售区域']) && !empty ($this->service->this_limit['销售区域']))
			$limitArr['areaLimit'] = $this->service->this_limit['销售区域'];
		if (isset ($this->service->this_limit['部门权限']) && !empty ($this->service->this_limit['部门权限']))
			$limitArr['deptLimit'] = $this->service->this_limit['部门权限'];
		if (isset ($this->service->this_limit['客户类型']) && !empty ($this->service->this_limit['客户类型']))
			$limitArr['customerTypeLimit'] = $this->service->this_limit['客户类型'];
		if (strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') || strstr($limitArr['customerTypeLimit'], ';;')) {
			return true;
		} else {
			//区域负责人获取相关区域
			$regionDao = new model_system_region_region();
			$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
			if (!empty ($areaPri)) {
				//区域权限合并
				$limitArr['areaLimit'] = implode(array_filter(array (
					$limitArr['areaLimit'],
					$areaPri
				)), ',');
			}
			//			print_r($limitArr);
			if (empty ($limitArr)) {
				return false;
			} else {
				//配置混合权限
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
	 * 赠送下拉选择信息Pagejson
	 */
	function c_presentInfoJson() {
		$rows = null;
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('select_zsporder');
		$rows = $service->getInvoiceAndIncome_d($rows);
		$rows = $this->sconfig->md5Rows($rows, "orgid");
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*********************新增合同混合审批部分**********************/

	/**
	 * 全部合同审核tab页面
	 */
	function c_allAuditTab() {
		$this->display('allaudittab');
	}
	/**
	 * 全部未审核合同列表
	 */
	function c_allAuditingList() {
		$this->display('allauditing');
	}

	/**
	 * 全部未审核合同
	 * 2011-08-10
	 * createBy Show
	 */
	function c_allAuditingPagejson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 全部已审核合同列表
	 */
	function c_allAuditedList() {
		$this->display('allaudited');
	}

	/**
	 * 全部已审批合同
	 */
	function c_allAuditedPagejson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 添加入库单时，动态添加从表模板
	 */
	function c_getItemList() {
		$orderId = isset ($_POST['orderId']) ? $_POST['orderId'] : null;
		$list = $this->service->getEquList_d($orderId);
		echo $list;
	}

	/*******************************************************************************************************/
	/**
	 * 发货需求查看
	 */
	function c_toShipView() {
		$rows = $this->service->get_d($_GET['id']);
		//渲染从表
		//附件
		$rows['file'] = $this->service->getFilesByObjId($rows['id'], false);
		$rows = $this->service->initView($rows);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$orderTempCode = explode(',', $rows['orderTempCode']);
		if ($rows['sign'] == '是') {
			$this->assign('sign', '是');
		} else
			if ($rows['sign'] == '否') {
				$this->assign('sign', '否');
			}
		if ($rows['orderstate'] == '已提交') {
			$this->assign('orderstate', '已提交');
		} else
			if ($rows['orderstate'] == '已拿到') {
				$this->assign('orderstate', '已拿到');
			};
		$this->assign('orderTempCodeV', $rows['orderTempCode']);
		$this->assign('orderTempCode', $this->service->TempOrderView($orderTempCode));
		$this->assign('customerType', $this->getDataNameByCode($rows['customerType']));
		$this->assign('invoiceType', $this->getDataNameByCode($rows['invoiceType']));
		$this->display('view-ship');
	}
	/*******************************************************************************************************/
	/**
	 * 自定义清单临时物料 视图 json
	 */
	function c_customizelistJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$rows = $service->pageBySqlId('select_customizelist');
		//		echo "<pre>";
		//		print_r($rows);
		$rows = $this->sconfig->md5Rows($rows, "orderId");
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*******************************************************************************************************/

	/**
	 * 跳转--合同共享
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

	/************************ excel导入部分*****************************/

	/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display('importexcel');
	}

	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		//		echo "===============上传文件信息开始================<br/>";
		//		echo "Upload: " . $_FILES ["inputExcel"] ["name"] . "<br />";
		//		echo "Type: " . $_FILES ["inputExcel"] ["type"] . "<br />";
		//		echo "Size: " . ($_FILES ["inputExcel"] ["size"] / 1024) . " Kb<br />";
		//		echo "Stored in: " . $_FILES ["inputExcel"] ["tmp_name"] . "<br />";
		//		echo "===============上传文件信息结束================<br/>";

		$ExaDT = $_POST['import'];
		$objNameArr = array (
				0 => 'orderType', //合同类型
		1 => 'orderNature', //合同属性
		2 => 'sign', //是否签收
		3 => 'orderTempCode', //临时合同号
		4 => 'orderCode', //鼎力合同号
		5 => 'orderProvince', //客户所属省份
		6 => 'orderCity', //客户所属省份
		7 => 'customerType', //客户性质
		8 => 'customerName', //客户名称
		9 => 'orderName', //合同名称
		10 => 'area', //所属区域
		11 => 'state', //合同状态
		12 => 'orderMoney', //合同金额
		13 => 'received', //已收合计（到款）
		14 => 'invoiceMoney', //开票金额
		15 => 'softMoney', //软件开票金额
		16 => 'hardMoney', //硬件开票金额
		17 => 'repairMoney', //维修金额
		18 => 'serviceMoney', //服务金额
		19 => 'invoiceType', //发票类型
		20 => 'prinvipalName', //合同负责人
	21 => 'remark'
			) //备注
	;

		$this->c_addExecel($objNameArr, $ExaDT);

	}

	//转换合同状态的值
	function state($state) {
		switch ($state) {
			case "未提交" :
				$states = "0";
				break;
			case "审批中" :
				$states = "1";
				break;
			case "在执行" :
				$states = "2";
				break;
			case "已完成" :
				$states = "4";
				break;
			case "已关闭" :
				$states = "3";
				break;
		}
		return $states;
	}
	//截取销售区域、区域负责人
	function areaName($area) {
		$Numa = strpos($area, '（');
		$Numb = strpos($area, '）');
		if ($Numa == '0') {
			return $area;
		} else {
			$areaName = substr($area, '0', $Numa);
			return $areaName;
		}

	}
	function areaMan($area) {
		$Numa = strpos($area, '（');
		$Numb = strpos($area, '）');
		if ($Numa == '0') {
			$areaMan = "无";
			return $areaMan;
		} else {
			$areaMan = substr($area, $Numa +2, -2);
			return $areaMan;
		}
	}

	//数据字典转换
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
				break; //开票类型
			case "cusType" :
				$code = $datadictDao->getCodeByName("KHLX", $name);
				break; //客户类型
		}

		return $code;
	}
	//先判断所属区域是否存在，若不存在则保存并返回ID
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
	//根据名字查找对应Id
	function user($userName) {
		$userDao = new model_deptuser_user_user();
		$user = $userDao->getUserByName("$userName");
		$userId = $user['USER_ID'];
		return $userId;
	}

	//根据省份名称查找ID
	function province($proName) {
		$dao = new model_system_procity_province();
		$proIdArr = $dao->find(array (
			"provinceName" => $proName
		), null, "id");
		return $proIdArr['id'];
	}
	//根据城市名称获取城市ID
	function city($cityName) {
		$dao = new model_system_procity_city();
		$cityIdArr = $dao->find(array (
			"cityName" => $cityName
		), null, "id");
		return $cityIdArr['id'];
	}
	//保存合同信息
	function addOrder($orderType, $orderInfo) {
		try {
			$this->service->start_d();
			//实例化四种合同的类
			$allContract = new model_contract_common_allcontract();
			$orderDao = new model_projectmanagent_order_order(); //销售合同
			$serviceDao = new model_engineering_serviceContract_serviceContract(); //服务合同
			$rentalDao = new model_contract_rental_rentalcontract(); //租赁合同
			$rdprojectDao = new model_rdproject_yxrdproject_rdproject(); //研发合同
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
	//保存到款单
	function income($income) {
		foreach ($income as $key => $val) {
			$income = $val;
		}
		$incomeDao = new model_finance_income_income();
		$incomeId = $incomeDao->create($income);
		return $incomeId;
	}
	//判断是否为临时合同，并返回开票、到款的合同类型
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
	//通过判断开票、到款的合同类型 返回保存的 正式或临时合同号
	function orderCode($type, $code, $tempcode) {
		if ($type == "KPRK-01" || $type == "KPRK-03" || $type == "KPRK-05" || $type == "KPRK-07") {
			return $code;
		} else
			if ($type == "KPRK-02" || $type == "KPRK-04" || $type == "KPRK-06" || $type == "KPRK-08") {
				return $tempcode;
			}
	}

	//判断导入合同格式是否正确
	function infoSuc($row) {
		//判断合同号是否存在
		if (empty ($row['orderTempCode']) && empty ($row['orderCode'])) {
			$orderCode = '';
		} else {
			$orderCode = $this->service->orderBe($row['orderTempCode'], $row['orderCode']);
		}
		//判断客户是否存在
		$customerDao = new model_customer_customer_customer();
		$customerId = $customerDao->findCus($row['customerName']);
		foreach ($customerId as $key => $val) {
			$customerId[$key] = $val;
		}
		$customerId = implode(",", $customerId[$key]);
		//判断区域是否存在
		$areaName = $this->areaName($row['area']);
		$regionDao = new model_system_region_region();
		$areaId = $regionDao->region($areaName);
		if (!empty ($areaId)) {
			foreach ($areaId as $key => $val) {
				$areaId[$key] = $val;
			}
			$areaId = implode(",", $areaId[$key]);
		}
		//判断省市
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
			if ($row['orderType'] == '销售合同' || $row['orderType'] == '服务合同' || $row['orderType'] == '租赁合同' || $row['orderType'] == '研发合同') {
				return "0";
			} else {
				return "1";
			}

	}
	//根据客户名称查找ID
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
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = $upexcel->upExcelData($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$err = array (); //失败的数据
				$suc = array (); //成功的数据
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
				//四种合同信息分类
				foreach ($sucArray as $key => $val) {
					try {
						$this->service->start_d();
						switch ($sucArray[$key]['orderType']) {
							case "销售合同" :
								$order = array ();
								$order[$key]['sign'] = $sucArray[$key]['sign']; //是否签约
								$order[$key]['orderCode'] = $sucArray[$key]['orderCode']; //鼎利合同号
								$order[$key]['orderTempCode'] = $objectArr[$key]['orderTempCode']; //临时合同号
								$order[$key]['orderName'] = $objectArr[$key]['orderName']; //合同名称
								$order[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //销售区域
								$order[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //区域负责人
								$order[$key]['areaPrincipalId'] = $this->user($order[$key]['areaPrincipal']); //区域负责人Id
								//自动产生业务编码
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$order[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_order_objCode", $dept['Code']);

								$order[$key]['areaCode'] = $this->beArea($order[$key]['areaName'], $order[$key]['areaPrincipal']); //区域编码（id）
								$order[$key]['state'] = $this->state($sucArray[$key]['state']); //合同状态
								$order[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //合同金额
								$order[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //开票类型  invoice
								$order[$key]['remark'] = $sucArray[$key]['remark']; //备注
								$order[$key]['ExaDT'] = $ExaDT['ExaDT']; //建立时间
								$order[$key]['createTime'] = date('Y-m-d'); //创建时间
								$order[$key]['createName'] = $_SESSION['USERNAME'];
								$order[$key]['createId'] = $_SESSION['USER_ID'];

								$order[$key]['orderNature'] = $this->datadict("order", $sucArray[$key]['orderNature']); //合同属性 Code
								$order[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //合同属性 Name
								$order[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //所属省份
								$order[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //所属省份Id
								$order[$key]['orderCity'] = $sucArray[$key]['orderCity']; //所属城市
								$order[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //所属城市Id
								$order[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //客户类型
								$order[$key]['customerName'] = $sucArray[$key]['customerName']; //客户名称
								$order[$key]['customerId'] = $this->cusId($sucArray[$key]['customerName']); //客户名称cusId
								$order[$key]['prinvipalName'] = $sucArray[$key]['prinvipalName']; //合同负责人 （四种合同不同）
								$order[$key]['prinvipalId'] = $this->user($order[$key]['prinvipalName']); //合同负责人Id
								$order[$key]['ExaStatus'] = "完成"; //合同审批状态

								$orderId = $this->addOrder("order", $order); //保存合同信息，并获得合同Id
								$invoiceM = $sucArray[$key]['invoiceMoney']; //开票金额
								$incomeM = $sucArray[$key]['received']; //到款金额
								if (!empty ($orderId)) {
									//开票
									$orderInvoice[$key]['objId'] = $orderId; //合同ID
									$orderInvoice[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['orderCode']); //开票的合同类型
									$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //鼎利合同号
									$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //合同名称
									$orderInvoice[$key]['invoiceUnitName'] = $order[$key]['customerName']; //客户名称
									$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //开票时间，暂时取导入的时间处理
									$orderInvoice[$key]['invoiceType'] = $order[$key]['invoiceType']; //开票类型
									$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //开票金额
									$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //软件金额
									$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //硬件金额
									$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //维修金额
									$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //服务金额
									$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //合同负责人
									$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
									//到款
									//到款单
									$income[$key]['formType'] = "YFLX-DKD"; //到款单类型
									$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //客户名称
									$income[$key]['incomeDate'] = date("Y-m-d"); //填写到款单日期，暂取导入日期
									$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //到款金额
									$income[$key]['allotAble'] = "0"; //
									$income[$key]['incomeType'] = "DKFS1"; //到款类型
									$income[$key]['sectionType'] = "DKLX-HK"; //
									$income[$key]['status'] = "DKZT-YFP"; //
									//分配单
									$allot[$key]['incomeId'] = $this->income($income); //到款单Id
									$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //合同Id
									$allot[$key]['objType'] = $this->isTemp("order", $sucArray[$key]['orderCode']); //到款的合同类型
									$allot[$key]['money'] = $sucArray[$key]['received']; //到款分配金额
									$allot[$key]['allotDate'] = date("Y-m-d"); //到款分配金额
									//保存到款分配单
									if (!empty ($incomeM)) {
										$allotDao = new model_finance_income_incomeAllot();
										foreach ($allot as $key => $val) {
											$allot = $val;
										}
										$allotId = $allotDao->create($allot);
									}

									//保存开票
									if (!empty ($invoiceM)) {
										$invoiceDao = new model_finance_invoice_invoice();
										foreach ($orderInvoice as $key => $val) {
											$orderInvoice = $val;
										}
										if (empty ($orderInvoice['objId'])) {
											throw new Exception("合同信息有误");
											$err[] = $sucArray[$key];
										} else {
											$orderId = $invoiceDao->create($orderInvoice);
										}
									}

								}

								break;
							case "服务合同" :
								$service = array ();
								$service[$key]['sign'] = $sucArray[$key]['sign']; //是否签约
								$service[$key]['orderCode'] = $sucArray[$key]['orderCode']; //鼎利合同号
								$service[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //临时合同号
								$service[$key]['orderName'] = $sucArray[$key]['orderName']; //合同名称
								$service[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //销售区域
								$service[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //区域负责人
								$service[$key]['areaPrincipalId'] = $this->user($service[$key]['areaPrincipal']); //区域负责人Id

								//自动产生业务编码
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$service[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_service_objCode", $dept['Code']);

								$service[$key]['areaCode'] = $this->beArea($service[$key]['areaName'], $service[$key]['areaPrincipal'], $service[$key]['areaPrincipalId']); //区域编码（id）
								$service[$key]['state'] = $this->state($sucArray[$key]['state']); //合同状态
								$service[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //合同金额
								$service[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //开票类型  invoice
								$service[$key]['remark'] = $sucArray[$key]['remark']; //备注
								$service[$key]['ExaDT'] = $ExaDT['ExaDT']; //建立时间
								$service[$key]['createTime'] = date('Y-m-d'); //创建时间
								$service[$key]['createName'] = $_SESSION['USERNAME'];
								$service[$key]['createId'] = $_SESSION['USER_ID'];

								$service[$key]['orderNature'] = $this->datadict("service", $sucArray[$key]['orderNature']); //合同属性
								$service[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //合同属性 Name
								$service[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //所属省份
								$service[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //所属省份Id
								$service[$key]['orderCity'] = $sucArray[$key]['orderCity']; //所属城市
								$service[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //所属城市Id
								$service[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //客户类型
								$service[$key]['cusName'] = $sucArray[$key]['customerName']; //客户名称
								$service[$key]['cusNameId'] = $this->cusId($sucArray[$key]['customerName']); //客户名称$this->cusId
								$service[$key]['orderPrincipal'] = $sucArray[$key]['prinvipalName']; //合同负责人 （四种合同不同）
								$service[$key]['orderPrincipalId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
								$service[$key]['ExaStatus'] = "完成"; //合同审批状态

								$serviceId = $this->addOrder("service", $service); //保存合同信息，并获得合同Id
								$invoiceM = $sucArray[$key]['invoiceMoney']; //开票金额
								$incomeM = $sucArray[$key]['received']; //到款金额
								if (!empty ($serviceId)) {
									//开票
									$orderInvoice[$key]['objId'] = $serviceId;
									$orderInvoice[$key]['objType'] = $this->isTemp("service", $sucArray[$key]['orderCode']); //开票的合同类型
									$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //鼎利合同号
									$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //合同名称
									$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //客户名称
									$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //开票时间，暂时取导入的时间处理
									$orderInvoice[$key]['invoiceType'] = $service[$key]['invoiceType']; //开票类型
									$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //开票金额
									$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //软件金额
									$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //硬件金额
									$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //维修金额
									$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //服务金额
									$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //合同负责人
									$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
									//到款
									//到款单
									$income[$key]['formType'] = "YFLX-DKD"; //到款单类型
									$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //客户名称
									$income[$key]['incomeDate'] = date("Y-m-d"); //填写到款单日期，暂取导入日期
									$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //到款金额
									$income[$key]['allotAble'] = "0"; //
									$income[$key]['incomeType'] = "DKFS1"; //到款类型
									$income[$key]['sectionType'] = "DKLX-HK"; //
									$income[$key]['status'] = "DKZT-YFP"; //
									//分配单
									$allot[$key]['incomeId'] = $this->income($income); //到款单Id
									$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //合同Id
									$allot[$key]['objType'] = $this->isTemp("service", $sucArray[$key]['orderCode']); //到款的合同类型
									$allot[$key]['money'] = $sucArray[$key]['received']; //到款分配金额
									$allot[$key]['allotDate'] = date("Y-m-d"); //到款分配金额
									//保存到款分配单
									if (!empty ($incomeM)) {
										$allotDao = new model_finance_income_incomeAllot();
										foreach ($allot as $key => $val) {
											$allot = $val;
										}
										$allotId = $allotDao->create($allot);
									}

									//保存开票
									if (!empty ($invoiceM)) {
										$invoiceDao = new model_finance_invoice_invoice();
										foreach ($orderInvoice as $key => $val) {
											$orderInvoice = $val;
										}
										$orderId = $invoiceDao->create($orderInvoice);
									}
								}

								break;
							case "租赁合同" :
								$rental = array ();
								$rental[$key]['sign'] = $sucArray[$key]['sign']; //是否签约
								$rental[$key]['orderCode'] = $sucArray[$key]['orderCode']; //鼎利合同号
								$rental[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //临时合同号
								$rental[$key]['orderName'] = $sucArray[$key]['orderName']; //合同名称
								$rental[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //销售区域
								$rental[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //区域负责人
								$rental[$key]['areaPrincipalId'] = $this->user($rental[$key]['areaPrincipal']); //区域负责人Id

								//自动产生业务编码
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$rental[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_lease_objCode", $dept['Code']);

								$rental[$key]['areaCode'] = $this->beArea($rental[$key]['areaName'], $rental[$key]['areaPrincipal'], $rental[$key]['areaPrincipalId']); //区域编码（id）
								$rental[$key]['state'] = $this->state($sucArray[$key]['state']); //合同状态
								$rental[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //合同金额
								$rental[$key]['remark'] = $sucArray[$key]['remark']; //备注
								$rental[$key]['ExaDT'] = $ExaDT['ExaDT']; //建立时间
								$rental[$key]['createTime'] = date('Y-m-d'); //创建时间
								$rental[$key]['createName'] = $_SESSION['USERNAME'];
								$rental[$key]['createId'] = $_SESSION['USER_ID'];

								$rental[$key]['orderNature'] = $this->datadict("rental", $sucArray[$key]['orderNature']); //合同属性
								$rental[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //合同属性 Name
								$rental[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //所属省份
								$rental[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //所属省份Id
								$rental[$key]['orderCity'] = $sucArray[$key]['orderCity']; //所属城市
								$rental[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //所属城市Id
								$rental[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //客户类型
								$rental[$key]['tenant'] = $sucArray[$key]['customerName']; //客户名称
								$rental[$key]['tenantId'] = $this->cusId($sucArray[$key]['customerName']); //客户名称$this->cusId
								$rental[$key]['hiresName'] = $sucArray[$key]['prinvipalName']; //合同负责人 （四种合同不同）
								$rental[$key]['hiresId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
								$rental[$key]['ExaStatus'] = "完成"; //合同审批状态

								$rentalId = $this->addOrder("rental", $rental); //保存合同信息，并获得合同Id
								$invoiceM = $sucArray[$key]['invoiceMoney']; //开票金额
								$incomeM = $sucArray[$key]['received']; //到款金额
								//开票
								$orderInvoice[$key]['objId'] = $rentalId;
								$orderInvoice[$key]['objType'] = $this->isTemp("rental", $sucArray[$key]['orderCode']); //开票的合同类型
								$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //鼎利合同号
								$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //合同名称
								$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //客户名称
								$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //开票时间，暂时取导入的时间处理
								$orderInvoice[$key]['invoiceType'] = $rental[$key]['invoiceType']; //开票类型
								$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //开票金额
								$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //软件金额
								$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //硬件金额
								$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //维修金额
								$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //服务金额
								$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //合同负责人
								$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
								//到款
								//到款单
								$income[$key]['formType'] = "YFLX-DKD"; //到款单类型
								$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //客户名称
								$income[$key]['incomeDate'] = date("Y-m-d"); //填写到款单日期，暂取导入日期
								$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //到款金额
								$income[$key]['allotAble'] = "0"; //
								$income[$key]['incomeType'] = "DKFS1"; //到款类型
								$income[$key]['sectionType'] = "DKLX-HK"; //
								$income[$key]['status'] = "DKZT-YFP"; //
								//分配单
								$allot[$key]['incomeId'] = $this->income($income); //到款单Id
								$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //合同Id
								$allot[$key]['objType'] = $this->isTemp("rental", $sucArray[$key]['orderCode']); //到款的合同类型
								$allot[$key]['money'] = $sucArray[$key]['received']; //到款分配金额
								$allot[$key]['allotDate'] = date("Y-m-d"); //到款分配金额
								//保存到款分配单
								if (!empty ($incomeM)) {
									$allotDao = new model_finance_income_incomeAllot();
									foreach ($allot as $key => $val) {
										$allot = $val;
									}
									$allotId = $allotDao->create($allot);
								}

								//保存开票
								if (!empty ($invoiceM)) {
									$invoiceDao = new model_finance_invoice_invoice();
									foreach ($orderInvoice as $key => $val) {
										$orderInvoice = $val;
									}
									$orderId = $invoiceDao->create($orderInvoice);
								}

								break;
							case "研发合同" :
								$rdproject = array ();
								$rdproject[$key]['sign'] = $sucArray[$key]['sign']; //是否签约
								$rdproject[$key]['orderCode'] = $sucArray[$key]['orderCode']; //鼎利合同号
								$rdproject[$key]['orderTempCode'] = $sucArray[$key]['orderTempCode']; //临时合同号
								$rdproject[$key]['orderName'] = $sucArray[$key]['orderName']; //合同名称
								$rdproject[$key]['areaName'] = $this->areaName($sucArray[$key]['area']); //销售区域
								$rdproject[$key]['areaPrincipal'] = $this->areaMan($sucArray[$key]['area']); //区域负责人
								$rdproject[$key]['areaPrincipalId'] = $this->user($rdproject[$key]['areaPrincipal']); //区域负责人Id

								//自动产生业务编码
								$orderCodeDao = new model_common_codeRule();
								$deptDao = new model_deptuser_dept_dept();
								$dept = $deptDao->getDeptByUserId($this->user($sucArray[$key]['prinvipalName']));
								$rdproject[$key]['objCode'] = $orderCodeDao->getObjCode("oa_sale_rdproject_objCode", $dept['Code']);

								$rdproject[$key]['areaCode'] = $this->beArea($rdproject[$key]['areaName'], $rdproject[$key]['areaPrincipal'], $rdproject[$key]['areaPrincipalId']); //区域编码（id）
								$rdproject[$key]['state'] = $this->state($sucArray[$key]['state']); //合同状态
								$rdproject[$key]['orderMoney'] = $sucArray[$key]['orderMoney']; //合同金额
								$rdproject[$key]['invoiceType'] = $this->datadict("invoice", $sucArray[$key]['invoiceType']); //开票类型  invoice
								$rdproject[$key]['remark'] = $sucArray[$key]['remark']; //备注
								$rdproject[$key]['ExaDT'] = $ExaDT['ExaDT']; //建立时间
								$rdproject[$key]['createTime'] = date('Y-m-d'); //创建时间
								$rdproject[$key]['createName'] = $_SESSION['USERNAME'];
								$rdproject[$key]['createId'] = $_SESSION['USER_ID'];

								$rdproject[$key]['orderNature'] = $this->datadict("service", $sucArray[$key]['orderNature']); //合同属性
								$rdproject[$key]['orderNatureName'] = $sucArray[$key]['orderNature']; //合同属性 Name
								$rdproject[$key]['orderProvince'] = $sucArray[$key]['orderProvince']; //所属省份
								$rdproject[$key]['orderProvinceId'] = $this->province($sucArray[$key]['orderProvince']); //所属省份Id
								$rdproject[$key]['orderCity'] = $sucArray[$key]['orderCity']; //所属城市
								$rdproject[$key]['orderCityId'] = $this->city($sucArray[$key]['orderCity']); //所属城市Id
								$rdproject[$key]['customerType'] = $this->datadict("cusType", $sucArray[$key]['customerType']); //客户类型
								$rdproject[$key]['cusName'] = $sucArray[$key]['customerName']; //客户名称
								$rdproject[$key]['cusNameId'] = $this->cusId($sucArray[$key]['customerName']); //客户名称$this->cusId
								$rdproject[$key]['orderPrincipal'] = $sucArray[$key]['prinvipalName']; //合同负责人 （四种合同不同）
								$rdproject[$key]['orderPrincipalId'] = $this->user($rdproject[$key]['orderPrincipal']); //合同负责人Id
								$rdproject[$key]['ExaStatus'] = "完成"; //合同审批状态

								$rdprojectId = $this->addOrder("rdproject", $rdproject); //保存合同信息，并获得合同Id
								$invoiceM = $sucArray[$key]['invoiceMoney']; //开票金额
								$incomeM = $sucArray[$key]['received']; //到款金额
								//开票
								$orderInvoice[$key]['objId'] = $rdprojectId;
								$orderInvoice[$key]['objType'] = $this->isTemp("rdproject", $sucArray[$key]['orderCode']); //开票的合同类型
								$orderInvoice[$key]['objCode'] = $this->orderCode($orderInvoice[$key]['objType'], $sucArray[$key]['orderCode'], $sucArray[$key]['orderTempCode']); //鼎利合同号
								$orderInvoice[$key]['objName'] = $sucArray[$key]['orderName']; //合同名称
								$orderInvoice[$key]['invoiceUnitName'] = $sucArray[$key]['customerName']; //客户名称
								$orderInvoice[$key]['invoiceTime'] = date("Y-m-d"); //开票时间，暂时取导入的时间处理
								$orderInvoice[$key]['invoiceType'] = $rdproject[$key]['invoiceType']; //开票类型
								$orderInvoice[$key]['invoiceMoney'] = $sucArray[$key]['invoiceMoney']; //开票金额
								$orderInvoice[$key]['softMoney'] = $sucArray[$key]['softMoney']; //软件金额
								$orderInvoice[$key]['hardMoney'] = $sucArray[$key]['hardMoney']; //硬件金额
								$orderInvoice[$key]['repairMoney'] = $sucArray[$key]['repairMoney']; //维修金额
								$orderInvoice[$key]['serviceMoney'] = $sucArray[$key]['serviceMoney']; //服务金额
								$orderInvoice[$key]['salesman'] = $sucArray[$key]['prinvipalName']; //合同负责人
								$orderInvoice[$key]['salesmanId'] = $this->user($sucArray[$key]['prinvipalName']); //合同负责人Id
								//到款
								//到款单
								$income[$key]['formType'] = "YFLX-DKD"; //到款单类型
								$income[$key]['incomeUnitName'] = $sucArray[$key]['customerName']; //客户名称
								$income[$key]['incomeDate'] = date("Y-m-d"); //填写到款单日期，暂取导入日期
								$income[$key]['incomeMoney'] = $sucArray[$key]['received']; //到款金额
								$income[$key]['allotAble'] = "0"; //
								$income[$key]['incomeType'] = "DKFS1"; //到款类型
								$income[$key]['sectionType'] = "DKLX-HK"; //
								$income[$key]['status'] = "DKZT-YFP"; //
								//分配单
								$allot[$key]['incomeId'] = $this->income($income); //到款单Id
								$allot[$key]['objId'] = $orderInvoice[$key]['objId']; //合同Id
								$allot[$key]['objType'] = $this->isTemp("rdproject", $sucArray[$key]['orderCode']); //到款的合同类型
								$allot[$key]['money'] = $sucArray[$key]['received']; //到款分配金额
								$allot[$key]['allotDate'] = date("Y-m-d"); //到款分配金额
								//保存到款分配单
								if (!empty ($incomeM)) {
									$allotDao = new model_finance_income_incomeAllot();
									foreach ($allot as $key => $val) {
										$allot = $val;
									}
									$allotId = $allotDao->create($allot);
								}

								//保存开票
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
				//判断合同名称是否为空
				//				foreach ( $suc as $k => $v ) {
				//					if (empty ( $suc [$k] ['orderName'] )) {
				//						unset ( $suc [$k] );
				//					}
				//				}
				foreach ($suc as $k => $v) {
					array_push($arrinfo, array (
						"orderCode" => $suc[$k]['orderCode'],
						"cusName" => $suc[$k]['customerName'],
						"result" => "导入成功"
					));
				}

				foreach ($errArray as $k => $v) {
					if (empty ($errArray[$k]['orderTempCode']) && empty ($errArray[$k]['orderCode'])) {
						array_push($arrinfo, array (
							"orderCode" => $errArray[$k]['orderCode'],
							"cusName" => $errArray[$k]['customerName'],
							"result" => "导入失败，合同号为空"
						));
					} else {
						$orderCode = $this->service->orderBe($errArray[$k]['orderTempCode'], $errArray[$k]['orderCode']);
						if (!empty ($orderCode)) {
							array_push($arrinfo, array (
								"orderCode" => $errArray[$k]['orderCode'],
								"cusName" => $errArray[$k]['customerName'],
								"result" => "导入失败，合同号已存在"
							));
						} else {
							$areaName = $this->areaName($errArray[$k]['area']);
							$regionDao = new model_system_region_region();
							$areaId = $regionDao->region($areaName);
							if (empty ($areaId)) {
								array_push($arrinfo, array (
									"orderCode" => $errArray[$k]['orderCode'],
									"cusName" => $errArray[$k]['customerName'],
									"result" => "导入失败，所属区域不存在!"
								));
							} else {
								$customerDao = new model_customer_customer_customer();
								$customerId = $customerDao->findCus($errArray[$k]['customerName']);
								if (empty ($customerId)) {
									array_push($arrinfo, array (
										"orderCode" => $errArray[$k]['orderCode'],
										"cusName" => $errArray[$k]['customerName'],
										"result" => "导入失败，客户信息不存在!"
									));
								} else {
									//判断省市
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
											"result" => "导入失败，省市信息错误"
										));
									} else {
										array_push($arrinfo, array (
											"orderCode" => $errArray[$k]['orderCode'],
											"cusName" => $errArray[$k]['customerName'],
											"result" => "导入失败，合同类型错误"
										));
									}
								}
							}
						}
					}
				}

				if ($arrinfo) {
					echo util_excelUtil :: showResultOrder($arrinfo, "导入结果", array (
						"合同号",
						"客户名称",
						"结果"
					));
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}
	/**************************************新增需求菜单************************************************************************/
	/**
	 * 合同信息---销售合同
	 */
	function c_toOrderIn() {
		$this->display('orderin');
	}

	/**************************************************************************************************************/
	/******************start合同信息列表导出**************************/
	/**
	 * 合同管理-合同信息-合同信息导出
	 */
	function c_exportExcel() {

		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
	  //区域负责人获取相关区域
		$regionDao = new model_system_region_region();
		$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
		if (!isset ($this->service->this_limit['合同信息导出']) && empty($areaPri)) {
			showmsg('没有权限,需要开通权限请联系oa管理员');
		}
		$signinTypeArr = array (
			'order' => '销售类',
			'service' => '服务类',
			'lease' => '租用类',
			'rdproject' => '研发类'
		);
		$tablenameArr = array (
			'oa_sale_order' => '销售合同',
			'oa_sale_service' => '服务合同',
			'oa_sale_lease' => '租用合同',
			'oa_sale_rdproject' => '研发合同'
		);
		$stateArr = array (
			'0' => '未提交',
			'1' => '审批中',
			'2' => '执行中',
			'3' => '已关闭',
			'4' => '已完成',
			'5' => '已合并',
			'已拆分'
		);
		$signinArr = array (
			'0' => '未签收',
			'1' => '已签收'
		);

		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['state'];
		$ExaStatus = $_GET['ExaStatus'];
		$beginDate = $_GET['beginDate']; //开始时间
		$endDate = $_GET['endDate']; //截止时间
		$ExaDT = $_GET['ExaDT']; //建立时间
		$areaNameArr = $_GET['areaNameArr']; //归属区域
		$orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //合同编号
		$prinvipalName = $_GET['prinvipalName']; //合同负责人
		$customerName = $_GET['customerName']; //客户名称
		$orderProvince = $_GET['orderProvince']; //所属省份
		$customerType = $_GET['customerType']; //客户类型
		$orderNatureArr = $_GET['orderNatureArr']; //合同属性
		$isShip = $_GET['isShip']; //是否需要发货
		$searchConditionKey = $_GET['searchConditionKey']; //普通搜索的Key
		$searchConditionVal = $_GET['searchConditionVal']; //普通搜索的Val
		//		$searchCondition = "$searchConditionKey = $searchConditionVal";
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		//搜索条件
		$searchArr['signinType'] = $type;
		if ($state == null || $state == "" || $state == "undefined") {
			$searchArr['states'] = "1,2,3,4,5,6";
		} else {
			$searchArr['state'] = $state;
		}
		$searchArr['ExaStatus'] = $ExaStatus;
		$searchArr['beginDate'] = $beginDate; //开始时间
		$searchArr['endDate'] = $endDate; //截止时间
		$searchArr['createTime'] = $ExaDT; //建立时间
		$searchArr['areaNameArr'] = $areaNameArr; //归属区域
		$searchArr['orderCodeOrTempSearch'] = $orderCodeOrTempSearch; //合同编号
		$searchArr['prinvipalName'] = $prinvipalName; //合同负责人
		$searchArr['customerName'] = $customerName; //客户名称
		$searchArr['orderProvince'] = $orderProvince; //所属省份
		$searchArr['customerType'] = $customerType; //客户类型
		$searchArr['orderNatureArr'] = $orderNatureArr; //合同属性
		$searchArr['DeliveryStatusArr'] = $isShip; //是否需要发货
		$searchArr[$searchConditionKey] = $searchConditionVal;
		//		$privlimit = isset ( $this->service->this_limit ['销售区域'] ) ? $this->service->this_limit ['销售区域'] : null;
		//		$arealimit = $this->service->getArea_d ();
		//		$thisAreaLimit = $this->regionMerge ( $privlimit, $arealimit );
		//		if (! empty ( $thisAreaLimit )) {
		//			$searchArr ['areaCode'] = $thisAreaLimit;
		//		}
		//过滤型权限设置1
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
		//工作量进度
		$rows = $this->service->projectProcess_d($rows);
		//		$rows = $this->service->getInvoiceAndIncome_d ( $rows );
		//        $rows = $this->service->getMoneyControl_d ( $rows );//导出金额控制
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
		//金额合计
		$this->service->searchArr = $searchArr;
		$rowMoney = $this->service->listBySqlId('select_orderinfo_sumMoney');
		$rowMoney[0]['tablename'] = "金额合计";
		$rows[] = $rowMoney[0];
		//匹配导出列
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		//财务金额
		$finLimit = isset ($this->service->this_limit['财务金额']) ? $this->service->this_limit['财务金额'] : null;
		foreach ($dataArr as $key => $val) {
			$dataArr[$key]['customerType'] = $this->getDataNameByCode($val['customerType']);
			if ($val['orderMoney'] * 1 != 0) {
				$dataArr[$key]['orderTempMoney'] = 0;
			}
			//处理财务金额权限显示
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
	 * 合同管理-我的合同-合同信息导出
	 */
	function c_myExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		$tablenameArr = array (
			'oa_sale_order' => '销售合同',
			'oa_sale_service' => '服务合同',
			'oa_sale_lease' => '租用合同',
			'oa_sale_rdproject' => '研发合同'
		);
		$stateArr = array (
			'0' => '未提交',
			'1' => '审批中',
			'2' => '执行中',
			'3' => '已关闭',
			'4' => '已完成'
		);
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['state'];
		$ExaStatus = $_GET['ExaStatus'];
		//登录人
		$appId = $_SESSION['USER_ID'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		$searchArr['tablename'] = $type;
		$searchArr['state'] = $state;
		$searchArr['ExaStatus'] = $ExaStatus;
		$searchArr['prinvipalId'] = $appId;
		//    if(isset($this->service->this_limit['销售区域'])){
		//        $searchArr['areaCode'] = $this->service->this_limit['销售区域'];
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
		//匹配导出列
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
	 * 合同管理 - 合同签收-合同信息导出
	 */
	function c_singInExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		$tablenameArr = array (
			'oa_sale_order' => '销售合同',
			'oa_sale_service' => '服务合同',
			'oa_sale_lease' => '租用合同',
			'oa_sale_rdproject' => '研发合同'
		);
		$signinTypeArr = array (
			'order' => '销售类',
			'service' => '服务类',
			'lease' => '租用类',
			'rdproject' => '研发类'
		);
		$stateArr = array (
			'0' => '未提交',
			'1' => '审批中',
			'2' => '执行中',
			'3' => '已关闭',
			'4' => '已完成'
		);
		$signInArr = array (
			'0' => '未签收',
			'1' => '已签收'
		);
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$type = $_GET['type'];
		$state = $_GET['states'];
		$ExaStatus = $_GET['ExaStatus'];
		$beginDate = $_GET['beginDate']; //开始时间
		$endDate = $_GET['endDate']; //截止时间
		$ExaDT = $_GET['ExaDT']; //建立时间
		$areaNameArr = $_GET['areaNameArr']; //归属区域
		$orderCodeOrTempSearch = $_GET['orderCodeOrTempSearch']; //合同编号
		$prinvipalName = $_GET['prinvipalName']; //合同负责人
		$customerName = $_GET['customerName']; //客户名称
		$orderProvince = $_GET['orderProvince']; //所属省份
		$customerType = $_GET['customerType']; //客户类型
		$orderNatureArr = $_GET['orderNatureArr']; //合同属性
		$sign = $_GET['sign'];
		$signIn = $_GET['signIn'];
		//登录人
		$appId = $_SESSION['USER_ID'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		$searchArr['tablename'] = $type;
		$searchArr['states'] = $state;
		$searchArr['ExaStatusArr'] = $ExaStatus;
		$searchArr['beginDate'] = $beginDate; //开始时间
		$searchArr['endDate'] = $endDate; //截止时间
		$searchArr['ExaDT'] = $ExaDT; //建立时间
		$searchArr['areaNameArr'] = $areaNameArr; //归属区域
		$searchArr['orderCodeOrTempSearch'] = $orderCodeOrTempSearch; //合同编号
		$searchArr['prinvipalName'] = $prinvipalName; //合同负责人
		$searchArr['customerName'] = $customerName; //客户名称
		$searchArr['orderProvince'] = $orderProvince; //所属省份
		$searchArr['customerType'] = $customerType; //客户类型
		$searchArr['orderNatureArr'] = $orderNatureArr; //合同属性
		$searchArr['sign'] = $sign;
		$searchArr['signIn'] = $signIn;
		//		$searchArr ['appId'] = $appId;
		//    if(isset($this->service->this_limit['销售区域'])){
		//        $searchArr['areaCode'] = $this->service->this_limit['销售区域'];
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
		//金额合计
		$this->service->searchArr = $searchArr;
		$rowMoney = $this->service->listBySqlId('select_orderinfo_sumMoney');
		$rowMoney[0]['tablename'] = "金额合计";
		$rows[] = $rowMoney[0];
		//匹配导出列
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
	 * 单独附件上传页面
	 */
	function c_toUploadFile() {
		$this->assign("serviceId", $_GET['id']);
		$this->assign("serviceType", $_GET['type']);

		$text = $_GET['type'] . "2";
		$this->assign("serviceType2", $text);
		$this->display('uploadfile');
	}
	/**
	 * 单独附件上传方法
	 */
	function c_uploadfile() {
		$row = $_POST[$this->objName];
		$id = $this->service->uploadfile_d($row);
		if ($id) {
			msg('添加成功！');
		}
	}
	/*************************end**********************************/
	/**
	 * 临时 导出加密
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
			//$key : 加密信息对应物料的id
			foreach ($val as $k => $v) {
				//$k : 加密信息的类型
				switch ($k) {
					case "WT" :
						foreach ($v as $a => $b) {
							$licArr['WT'][$b] = "√";
						}
						break;
					case "RCU" :
						foreach ($v as $a => $b) {
							$licArr['RCU'][$b] = "√";

							//                          $licArr['licType']= 'RCU';
						}

						break;
					case "FL" :
						foreach ($v as $a => $b) {
							$licArr['FL'][$b] = "√";

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
	 * 合同物料信息导入页面
	 * @author huangzf
	 */
	function c_toImportOrderPro() {
		$this->view("equipe-import");
	}

	/**
	 *
	 * 合同物料信息导入
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
				echo util_excelUtil :: showResult($resultArr, "合同物料信息导入结果", array (
					"物料名称",
					"结果"
				));
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d();
		if ($_REQUEST['prinvipalId'] != $_SESSION['USER_ID']) {
			$rows = $this->service->filterContractMoney_d($rows);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 到款分配用合同grid
	 */
	function c_orderForIncomePj() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d();
		$rows = $this->service->getInvoiceAndIncome_d($rows, 2, $service->tbl_name);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 到款分配用合同grid
	 */
	function c_allOrderForIncomePj() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d('financeview_orderinvoice');
		$rows = $this->service->getInvoiceAndIncome_d($rows, 2, $service->tbl_name);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 从表选择物料处理配置
	 */
	function c_ajaxorder() {
		$isEdit = isset ($_GET['isEdit']) ? $_GET['isEdit'] : null;
		$configInfo = $this->service->c_configuration($_GET['id'], $_GET['Num'], $_GET['trId'], $isEdit);
		echo $configInfo[0];
	}

	/**
	 * 处理 合同内客户名称没有客户ID 问题
	 */
	function c_disposeCus() {
		$info = $this->service->disCustomer();
		foreach ($info as $key => $val) {
			if (empty ($val['AreaLeader'])) {
				$val['Area'] = $val['AreaName'];
			} else {
				$val['Area'] = $val['AreaName'] . "（" . $val['AreaLeader'] . "）";
			}
			$val['TypeOne'] = $this->getDataNameByCode($val['TypeOne']);
			$infoCus[$key]['Area'] = $val['Area'];
			$infoCus[$key]['TypeOne'] = $val['TypeOne'];
			$infoCus[$key]['Name'] = $val['Name'];
		}

		if (count($infoCus) == 0) {
			echo "<script>alert('处理完成!');window.close();</script>";
			return 0;
		}
		set_time_limit(0);
		return model_contract_common_contExcelUtil :: exportDisCus($infoCus);
	}

	/**
	* 根据条件获取业务对象数量
	*/
	function c_getCountByNameForView() {
		$val = util_jsonUtil :: iconvUTF2GB($_REQUEST['nameVal']);
		$sql = "SELECT COUNT(orgid) as sp_counter FROM view_oa_order where isTemp = 0 and (orderCode = '$val' or orderTempCode = '$val')";
		$result = $this->service->_db->getArray($sql);
		echo $result[0]['sp_counter'];
	}

	/**
	 *
	 * 根据合同id 合同类型获取合同负责人
	 */
	function c_ajaxPrincipal() {
		$result = $this->service->findPrincipal($_POST['contractId'], $_POST['contractType']);
		echo util_jsonUtil :: encode($result);

	}

	/**********************************************************************************************/
	/**
	 * 验证合同号的唯一性
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
	 * 手动改变合同状态 (执行中 ---> 已完成)
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
		)); //更新工作量进度、按工作量进度合同额
	}
	/**
	 * 手动改变合同状态 (已完成 --> 执行中)
	 */
	function c_exeOrder() {
		$orderId = $_GET['id'];
		$type = $_GET['type'];
		switch ($type) {
			case "oa_sale_order" :
				$orderInfo = $this->service->get_d($orderId);
				$flag = array (); //判断 发货条件标志数组
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
				$flag = array (); //判断 发货条件标志数组
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
				$flag = array (); //判断 发货条件标志数组
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
				$flag = array (); //判断 发货条件标志数组
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
		)); //更新工作量进度、按工作量进度合同额
	}

	/**
	 * 合同图形报表
	 */
	function c_contractReport() {
		$service = $this->service;
		$reportTypeCn = "合同数量";
		$countField = "count(o.id)";
		if ($_GET['reportType'] == "money") {
			$reportTypeCn = "合同金额";
			$countField = "sum(o.orderMoney)";
			$this->assign("reportType", $_GET['reportType']);
		} else {
			$this->assign("reportType", "");
		}

		//时间处理
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

		//区域默认设置
		$areaChartConf = array (
			'exportFileName' => "按区域经理统计$reportTypeCn",
			'caption' => "按区域经理统计$reportTypeCn",
			'formatNumberScale' => 0

				//'exportAtClient'=>'0',               //0: 服务器端运行， 1： 客户端运行
		//'exportAction'=>"download"             //如果是服务器运行，支持浏览器下载or服务器保存

		);
		$charts = new model_common_fusionCharts();
		$rows1 = $service->getContractByArea($countField, $sqlPlus);
		$result1 = $charts->showCharts($rows1, '', $areaChartConf, 960);
		$this->assign('resultArea', $result1);

		//类型默认设置
		$typeChartConf = array (
			'exportFileName' => "按合同类型统计$reportTypeCn",
			'caption' => "按合同类型统计$reportTypeCn"
		);

		$rows2 = $service->getContractByType($countField, $sqlPlus);
		$result2 = $charts->showCharts($rows2, '', $typeChartConf);
		$this->assign('resultType', $result2);

		//合同状态默认设置
		$statusChartConf = array (
			'exportFileName' => "按合同状态统计$reportTypeCn",
			'caption' => "按合同状态统计$reportTypeCn"
		);
		//$service->groupBy="tablename";
		$rows3 = $service->getContractByStatus($countField, $sqlPlus);

		$result3 = $charts->showCharts($rows3, '', $typeChartConf);
		$this->assign('resultStatus', $result3);

		$this->view('charts');
	}

	/***************************财务金额导入************************************************************/
	/**
	* 财务金额 导入
	*/
	function c_FinancialImportexcel() {
		$this->assign("dateTime", date("Y-m-d"));
		$this->display("FinancialImportexcel");
	}

	/**
	 * 上传EXCEL
	 */
	function c_finalceMoneyImport() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		$import = $_POST['import'];
		//		$objNameArr = array (
		//			0 => 'contractType',//合同类型
		//			1 => 'contractCode', //合同编号
		//			2 => 'serviceconfirmMoney', //服务确认收入
		//			3 => 'financeconfirmMoney', //财务确认总成本
		//			4 => 'deductMoney'//扣款金额
		//		   ) ;
		$objNameArr = array (
				0 => 'contractCode', //合同编号
		1 => 'money', //金额

		);
		if ($import['importType'] == "初始化导入") {
			$this->service->addFinalceMoneyExecel_d($objNameArr, $import['importInfo'], $import['normType']);
		} else {
			//共用信息 单独
			$infoArr = array (
				"importMonth" => date("Y"
				) . $import['Month'], //导入月份
		"moneyType" => $import['importInfo'], //金额类型
		"importName" => $_SESSION['USERNAME'], //导入人
		"importNameId" => $_SESSION['USER_ID'], //导入人ID
		"importDate" => date("Y-m-d"), //导入时间
	);

			$this->service->addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $import['normType']);
		}
	}

	/**
	 * 验证导入的金额是否已存在
	 */
	function c_getFimancialImport() {
		$importType = util_jsonUtil :: iconvUTF2GB($_POST['importType']);
		$importMonth = util_jsonUtil :: iconvUTF2GB($_POST['importMonth']);
		$importSub = util_jsonUtil :: iconvUTF2GB($_POST['importSub']);

		if ($importType == "按月导入") {
			$num = $this->service->getFimancialImport_d($importMonth, $importSub);
			echo $num;
		} else {
			echo "0";
		}
	}

	/**
	 * 导入金额明细列表Tab
	 */
	function c_financialDetailTab() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialDetailTab");
	}
	/**
	 * 导入金额信息
	 */
	function c_financialDetail() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialDetail");
	}
	function c_financialdetailpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		//$service->asc = false;
		$conId = $_GET['id'];
		$tablename = $_GET['tablename'];
		$moneyType = $_GET['moneyType'];
		$rows = $service->getFinancialDetailInfo($conId, $tablename, $moneyType);
		//echo "<pre>";
		//print_R($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * 导入金额信息
	 */
	function c_financialImportDetail() {
		$this->assign("conId", $_GET['id']);
		$this->assign("tablename", $_GET['tablename']);
		$this->assign("moneyType", $_GET['moneyType']);
		$this->display("financialImportDetail");
	}
	function c_financialImportDetailpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		//$service->asc = false;
		$conId = $_GET['id'];
		$tablename = $_GET['tablename'];
		$moneyType = $_GET['moneyType'];
		$rows = $service->getFinancialImportDetailInfo($conId, $tablename, $moneyType);

		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 更新工作量进度及金额
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
	 * 更新合同状态
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
	 * 获取权限
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