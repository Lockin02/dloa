<?php
/**
 * @author huangzf
 * @Date 2012年5月12日 星期六 14:05:49
 * @version 1.0
 * @description:生产任务控制层
 */
class controller_produce_task_producetask extends controller_base_action {

	function __construct() {
		$this->objName = "producetask";
		$this->objPath = "produce_task";
		parent::__construct ();
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
	
		//$service->asc = false;
		$rows = $service->page_d ();
		//是否纳入发货计划提醒处理
		$rows = $service->addOutPlanInfo($rows);
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
	 * 跳转到生产任务Tab
	 */
	function c_pageTab() {
		$this->view ( 'list-tab' );
	}

	/**
	 * 跳转到生产任务列表
	 */
	function c_page() {
		$taskType = isset ( $_GET ['taskType'] ) ? $_GET ['taskType'] : "";
		$this->assign ( "taskType", $taskType );
		$this->assign ( "userId", $_SESSION['USER_ID'] );
		//待执行生产任务 - 权限
		if (isset($this->service->this_limit['待执行生产任务']) && !empty($this->service->this_limit['待执行生产任务'])) {
			$this->assign('allTask', "1");
		} else {
			$this->assign('allTask', "0");
		}
		$this->view ( 'list' );
	}

	/**
	 * 跳转到PMC生产任务列表
	 */
	function c_pagePmc() {
		$this->view ( 'pmc-list' );
	}

	/**
	 * 跳转到订单需求未完成的任务列表
	 */
	function c_pageNeed() {
		$this->view ( 'need-list' );
	}
	
	/**
	 * 跳转到PMC入库单列表
	 */
	function c_pagePmcRkd() {
		$this->view ( 'pmc-rkdlist' );
	}

	/**
	 * 跳转到生产任务Tab
	 */
	function c_toMyTab() {
		$this->view ( 'mylist-tab' );
	}

	/**
	 * 跳转到我的生产任务列表
	 */
	function c_toMyTaskList() {
		$taskType = isset ( $_GET ['taskType'] ) ? $_GET ['taskType'] : "";
		$this->assign ( "taskType", $taskType );
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'mylist' );
	}

	/**
	 * 跳转到申请单关联生产任务列表
	 */
	function c_toPageApply() {
		$this->assign("applyDocId" ,$_GET['applyDocId']);
		$this->view ( 'list-apply' );
	}

	/**
	 * 跳转到生产计划列表(生产任务-生产计划tab)
	 */
	function c_pagePlan() {
		$this->view('list-plan');
	}

	/**
	 * 跳转到新增生产任务页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到变更生产任务页面
	 */
	function c_toChange() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$taskactorDao = new model_produce_task_taskactor ();
		$actorObj = $taskactorDao->getActorsByTaskId ( $_GET ['id'] );
		$this->assign ( "actorNames", $actorObj ['actorNames'] );
		$this->assign ( "actorIds", $actorObj ['actorIds'] );
		$this->view ( 'change' );
	}

	/**
	 * 变更处理
	 */
	function c_change() {
		if ($this->service->change_d ( $_POST [$this->objName] )) {
			msg ( "变更成功!" );
		}
	}

	/**
	 * 跳转到编辑生产任务页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$taskactorDao = new model_produce_task_taskactor ();
		$actorObj = $taskactorDao->getActorsByTaskId ( $_GET ['id'] );
		$this->assign ( "actorNames", $actorObj ['actorNames'] );
		$this->assign ( "actorIds", $actorObj ['actorIds'] );
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看生产任务页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //显示合同或者源单
		$this->assign('docStatus',$this->service->getStatusVal_d($obj['docStatus']));
		$this->assign('isFirstInspection',$obj['isFirstInspection'] == '1' ? '是' : '否');
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看生产任务信息页面
	 */
	function c_toViewTab() {
		$this->assign ( "id", $_GET ['id'] );
		$relDocId = $_GET ['relDocId'];
		if(!isset($relDocId)){
			$obj = $this->service->get_d($_GET ['id']);
			$relDocId = $obj['relDocId'];
		}
		$this->assign ( "relDocId", $relDocId );
		$this->view ( 'view-tab' );
	}

	/**
	 * 跳转到下达任务页面
	 */
	function c_toIssued() {
		$this->permCheck (); //安全校验
		$applyDao = new model_produce_apply_produceapply ();
		$applyObj = $applyDao->get_d ( $_GET ['applyId'] );
		foreach ( $applyObj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtIssued ( $applyObj ) );
		$this->assign ( "docDate", date ( "Y-m-d" ) );
		$this->view ( 'issued' );
	}

	/**
	 * 跳转到进度汇报页面
	 */
	function c_toReport() {
		//$this->permCheck (); //安全校验
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "docDate", date ( "Y-m-d" ) );
		$this->view ( 'report' );
	}

	/**
	 * 保存进度汇报信息
	 */
	function c_report() {
		if ($this->service->report ( $_POST [$this->objName] )) {
			msg ( '保存成功！' );
		} else {
			msg ( '保存失败！' );
		}
	}

	/**
	 * 个人生产任务Json
	 */
	function c_personPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		// $service->searchArr ['userId'] = $_SESSION ['USER_ID'];
		$rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 接收生产任务
	 */
	function c_receiveTask() {
		$service = $this->service;
		$obj = array(
			"id" => $_POST ['id']
			,"docStatus" => '1'
			,"recipient" => $_SESSION['USERNAME']
			,"recipientId" => $_SESSION['USER_ID']
			,"recipientDate" => date('Y-m-d')
		);
		if ($service->updateById($obj)) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * 关闭生产任务
	 */
	function c_closedTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "3" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * 开启生产任务
	 */
	function c_openTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "1" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * 完成生产任务
	 */
	function c_finishTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "2" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * 跳转到下达生产任务页面
	 */
	function c_toAddByNeed() {
		$this->permCheck (); //安全校验
		$applyDao = new model_produce_apply_produceapply();
		$applyObj = $applyDao->get_d( $_GET['applyId'] );
		$showRelDoc = $applyDao->getShowRelDoc_d($applyObj['relDocTypeCode']);
		$this->assign('showRelDoc' ,$showRelDoc); //显示合同或者源单
		//合同超链接
		if($showRelDoc == '合同'){
			$contractCode = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toViewTab&id=" .
			$applyObj['relDocId'] .'",1,'.$applyObj['relDocId'].")'>" . $applyObj['relDocCode'] . "</a>";
		}
		$this->assign("contractCode" ,isset($contractCode) ? $contractCode : $applyObj['relDocCode']);
		//申请单编号超链接
		$urlStr = 'toViewTab';//默认方法
		if($applyObj['docStatus'] == '8'){//变更审批中
			$urlStr .= '&noSee=true';
		}
		$this->assign("applyDocCode" ,
			"<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_apply_produceapply&action=" . $urlStr . 
			"&id=" . $applyObj['id'] .'",1,'.$applyObj['id'].")'>" . $applyObj['docCode'] . "</a>");
		foreach ($applyObj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->showDatadicts(array("purposeCode" => 'SCYT')); //用途
		$this->showDatadicts(array("technologyCode" => 'SCGY')); //工艺
		$this->assign("docUserName" ,$_SESSION['USERNAME']); //下单人
		$this->assign("docUserId" ,$_SESSION['USER_ID']); //下单人ID
		$this->assign("docDate" ,date("Y-m-d")); //下单日期
		$this->assign("proType" ,$_GET['proType']); //物料类型
		$this->assign("proTypeId" ,$_GET['proTypeId']); //物料类型id
		$this->assign("applyDocItemId" ,$_GET['applyItemIds']); //需求单明细id
		$this->assign("taskTypeCode" ,isset($_GET['taskTypeCode']) ? $_GET['taskTypeCode'] : 'RWLX-SCRW'); //任务类型，默认为生产任务
		$this->assign("taskTypeName" ,isset($_GET['taskTypeName']) ? $_GET['taskTypeName'] : '生产任务');

		$this->view('add-need' ,true);
	}

	/**
	 * 下达生产任务
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->add_d($_POST[$this->objName])) {
			msg ( '下达成功！' );
		} else {
			msg ( '下达失败！' );
		}
	}

	/**
	 * 跳转到生产任务导出页面
	 */
	function c_toExcelOut() {
		$this->view('excelout');
	}

	/**
	 * 跳转到物料汇总页面
	 */
	function c_pageProduct() {
		$this->view('list-product');
	}

	/**
	 * 获取物料汇总数据转成Json
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.productId';
		$rows = $service->page_d('select_product_config');
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
	 * 跳转到查看物料汇总信息页面
	 */
	function c_toViewProduct() {
		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->get_d($_GET['productId']);
		$this->assignFunc($productObj);
		$this->view('view-product');
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonProduct() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_product_view');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 导出excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['docCode'])) //单据编号
			$this->service->searchArr['docCode'] = $formData['docCode'];
		if(!empty($formData['fileNo'])) //文件编号
			$this->service->searchArr['fileNo'] = $formData['fileNo'];

		if(!empty($formData['productCode'])) //物料编号
			$this->service->searchArr['productCode'] = $formData['productCode'];
		if(!empty($formData['productName'])) //物料名称
			$this->service->searchArr['productName'] = $formData['productName'];

		if(!empty($formData['customerName'])) //客户名称
			$this->service->searchArr['customerName'] = $formData['customerName'];
		if(!empty($formData['relDocCode'])) //合同编号
			$this->service->searchArr['relDocCode'] = $formData['relDocCode'];

		if(!empty($formData['productionBatch'])) //生产批次
			$this->service->searchArr['productionBatch'] = $formData['productionBatch'];
		if(!empty($formData['saleUserName'])) //销售代表
			$this->service->searchArr['saleUserName'] = $formData['saleUserName'];

		if(!empty($formData['docUserName'])) //下单者
			$this->service->searchArr['docUserName'] = $formData['docUserName'];
		if(!empty($formData['docDate'])) //下单日期
			$this->service->searchArr['docDateSea'] = $formData['docDate'];

		if(!empty($formData['recipient'])) //接收人
			$this->service->searchArr['recipient'] = $formData['recipient'];
		if(!empty($formData['recipientDate'])) //接收日期
			$this->service->searchArr['recipientDate'] = $formData['recipientDate'];

		if(!empty($formData['docStatus'])) { //单据状态
			$docStatus = implode(',' ,$formData['docStatus']);
			$this->service->searchArr['docStatusIn'] = $docStatus;
		}

		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['docCode'] = $v['docCode'];
			$rowData[$k]['docStatus'] = $this->service->getStatusVal_d($v['docStatus']);
			$rowData[$k]['productCode'] = $v['productCode'];
			$rowData[$k]['productName'] = $v['productName'];
			$rowData[$k]['taskNum'] = $v['taskNum'];
			$rowData[$k]['purpose'] = $v['purpose'];
			$rowData[$k]['technology'] = $v['technology'];
			$rowData[$k]['fileNo'] = $v['fileNo'];
			$rowData[$k]['customerName'] = $v['customerName'];
			$rowData[$k]['relDocCode'] = $v['relDocCode'];
			$rowData[$k]['productionBatch'] = $v['productionBatch'];
			$rowData[$k]['docUserName'] = $v['docUserName'];
			$rowData[$k]['docDate'] = $v['docDate'];
			$rowData[$k]['recipient'] = $v['recipient'];
			$rowData[$k]['recipientDate'] = $v['recipientDate'];
			$rowData[$k]['saleUserName'] = $v['saleUserName'];
			$rowData[$k]['remark'] = $v['remark'];
		}

		$colArr  = array();
		$modelName = '生产-生产任务信息';
		return model_produce_basic_produceExcelUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	function c_product() {
		$datas = $this->service->get_product( $_GET['id'] , $_GET['code'] );
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $datas );
		echo util_jsonUtil::encode ( $rows );
	}

	function c_templateConf(){
		$datas = $this->service->get_templateConf( $_POST['id'] );
		echo util_jsonUtil::encode ( $datas );

	}

	/**
	 * 跳转到物料汇总计算页面
	 */
	function c_toStatisticsProduct() {
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,null ,true);  //优先级
		$this->assign('idStr', $_GET['idStr']);
		$this->view('statistics-product' ,true);
	}

	/**
	 * 跳转到物料计算
	 */
	function c_statistics() {
		$this->assign('idStr', $_GET['idStr']);
		$this->assign('codeStr', $_GET['codeStr']);
		$this->view('statistics' ,true);
	}

	/**
	 * 物料计算页面Json
	 */
	function c_caculateListJson() {
		$service = $this->service;
		$datas = $service->get_classify_produce($_POST);
		$produce = array();
		if(!empty($datas)){
			foreach($datas as $val){
				$produce[$val['productCode']]['relDocCode'] = $val['relDocCode'];
				$produce[$val['productCode']]['productionBatch'] = $val['productionBatch'];
				$produce[$val['productCode']]['taskId'] = $val['taskId'];
				$produce[$val['productCode']]['productCode'] = $val['productCode'];
				$produce[$val['productCode']]['id'] = $val['productCode'];
				$produce[$val['productCode']]['productName'] = $val['productName'];
				$produce[$val['productCode']]['productId'] = $val['productId'];
				$produce[$val['productCode']]['proType'] = $val['proType'];
				$produce[$val['productCode']]['pattern'] = $val['pattern'];
				$produce[$val['productCode']]['unitName'] = $val['unitName'];
				$produce[$val['productCode']]['number'] += $val['num'];

				$pickDao = new model_produce_plan_picking();
				$numArr = $pickDao->getProductNum_d($val['productCode']);

				$produce[$val['productCode']]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
				$produce[$val['productCode']]['KCSP'] = $numArr['KCSP']; //库存商品仓数量
				//				$produce[$val['productCode']]['SCC']  = $numArr['SCC']; //生产仓数量
				$stockoutNum = ( $numArr['JSBC'] + $numArr['KCSP']) - $produce[$val['productCode']]['number'];
				$produce[$val['productCode']]['stockoutNum'] = ($stockoutNum>=0) ? 0 : abs($stockoutNum);

			}
		}
		//数据排序
		sort($produce);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $produce );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_codelistJson(){
		$datas = $this->service->get_produceTask($_REQUEST['productCode'],$_REQUEST['ids']);
		$pickDao = new model_produce_plan_picking();
		foreach($datas as $key => $val){
			$produce[$key]['id'] = $val['taskId'];//任务单id
			$produce[$key]['taskCode'] = $val['docCode'];//任务单号
			$produce[$key]['relDocCode'] = $val['relDocCode'];
			$produce[$key]['productionBatch'] = $val['productionBatch'];
			$produce[$key]['number'] = $val['num'];
			$produce[$key]['productId'] = $val['productId'];
			$produce[$key]['productCode'] = $val['productCode'];

			$numArr = $pickDao->getProductNum_d($val['productCode']);
			$produce[$key]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
			$produce[$key]['KCSP'] = $numArr['KCSP']; //库存商品仓数量

			$stockoutNum = ( $numArr['JSBC'] + $numArr['KCSP']) - $produce[$key]['number'];
			$produce[$key]['stockoutNum'] = ($stockoutNum>=0)?0:abs($stockoutNum);
		}

		$arr ['collection'] = $produce;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到标记确认页面
	 */
	function c_toMark() {
		$service = $this->service;
		$this->assign('productCodes', $_GET['productCodes']);
		$this->assign('taskIds', $_GET['taskIds']);
		$this->view('mark');
	}

	/**
	 * 标记确认
	 */
	function c_mark() {
		if ($this->service->mark_d($_POST[$this->objName])) {
			msg ( '标记成功！' );
		} else {
			msg ( '标记失败！' );
		}
	}

	/**
	 * 物料计算-满足生产
	 */
	function c_isMeetProduction() {
		if($this->service->isMeetProduction_d($_POST['productCodes'],$_POST['taskIds'])){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 物料计算-不满足生产
	 */
	function c_isNotMeetProduction() {
		if($this->service->isNotMeetProduction_d($_POST['productCodes'],$_POST['taskIds'])){
			echo 1;
		}else{
			echo 0;
		}
	}
}