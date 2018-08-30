<?php
/**
 * @author Michael
 * @Date 2014年8月29日 14:40:34
 * @version 1.0
 * @description:生产计划控制层
 */
class controller_produce_plan_produceplan extends controller_base_action {

	function __construct() {
		$this->objName = "produceplan";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产计划列表
	 */
	function c_page() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 * 带出是否需要提示（已进行入库申请但未写完进度反馈）
	 * 0：表示非执行中或未申请质检（未反馈），1：表示反馈完整，2：表示反馈不全（进行提示）
	 */
	function c_pageJsonFeedback() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();

		if (is_array($rows)) {
			$feedbackDao = new model_produce_plan_planfeedback();
			$processDao = new model_produce_plan_planprocess();
			foreach ($rows as $key => $val) {
				if ($val['docStatus'] == 1 && $val['qualityNum'] > 0) { //执行中且已申请质检
					$conditions = " planId='$val[id]' GROUP BY process,processName ";
					$feedbackObjs = $feedbackDao->findAll($conditions);
					$feedbackNum = count($feedbackObjs);
					$processNum = $processDao->findCount(array('planId' => $val['id']));
					$rows[$key]['feedbackNum'] = $feedbackNum;
					$rows[$key]['processNum'] = $processNum;
					if ($feedbackNum == $processNum) {
						$rows[$key]['feedbackState'] = 1;
					} else {
						$rows[$key]['feedbackState'] = 2;
					}
				} else {
					$rows[$key]['feedbackState'] = 0;
				}
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
	 * 跳转到生产计划tab页面
	 */
	function c_pageTab() {
		$this->view('list-tab');
	}

	/**
	 * 跳转到生产计划tab页面(PMC)
	 */
	function c_pmcTab() {
		$this->view('pmc-tab');
	}

	/**
	 * 跳转到生产计划列表(PMC)
	 */
	function c_pagePmc() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list-pmc');
	}

	/**
	 * 跳转到生产计划tab页面(生产管理)
	 */
	function c_manageTab() {
		$this->view('manage-tab');
	}

	/**
	 * 跳转到生产计划列表(生产管理)
	 */
	function c_pageManage() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list-manage');
	}

	/**
	 * 跳转到生产计划列表（按生产任务显示）
	 */
	function c_pageTask() {
		$this->assign('taskId' ,isset($_GET['taskId']) ? $_GET['taskId'] : '');
		$this->view('list-task');
	}

	/**
	 * 跳转到发货计划列表
	 */
	function c_weekPage() {
		$applyDao = new model_produce_apply_produceapply();
		$weekDate = $applyDao->getWeekDate_d();
		$this->assign('startWeekDate' ,$weekDate['startDate']);
		$this->assign('endWeekDate' ,$weekDate['endDate']);
		$this->view ( 'list-week' );
	}

	/**
	 * 跳转到生产申请关联计划列表
	 */
	function c_toPageApply() {
		$this->assign("applyDocId" ,$_GET['applyDocId']);
		$this->view ( 'list-apply' );
	}

	/**
	 * 跳转到新增生产计划页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到新增生产计划页面
	 */
	function c_toAddByTask() {
		$this->permCheck (); //安全校验

		$taskDao = new model_produce_task_producetask();
		$taskObj = $taskDao->get_d($_GET['taskId']);
		$this->assignFunc($taskObj);

//		$planRows = $this->service->findAll(array('taskId' => $_GET['taskId']));
//		$planNum = 0;
//		if (is_array($planRows)) {
//			foreach ($planRows as $key => $val) {
//				$planNum += $val['planNum']; //累计已制定计划的数量
//			}
//		}
//		$planNumNow = $taskObj['taskNum'] - $planNum;
//		$this->assign('planNum' ,$planNumNow > 0 ? $planNumNow : 0);

		$outDao = new model_stock_outplan_outplan();
		$outProDao = new model_stock_outplan_outplanProduct();
		$outProObj = $outProDao->find(
			array(
				"docType" => 'oa_contract_contract' ,
				"docId" => $taskObj["relDocId"] ,
				"productId" => $taskObj["productId"]
			),
			' mainId DESC '
		);
		if ($outProObj) {
			$outObj = $outDao->get_d($outProObj["mainId"]);
		}
		$this->assign("planEndDate" ,$outObj["shipPlanDate"]); //反写发货日期到计划结束时间

		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ')); //优先级
		$this->assign('today' ,date('Y-m-d'));
		$this->view('add-task' ,true);
	}

	/**
	 * 制定计划
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->add_d($_POST[$this->objName])) {
			msg ( '制定成功！' );
		} else {
			msg ( '制定失败！' );
		}
	}

	/**
	 * 跳转到编辑生产计划页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,$obj['urgentLevelCode']); //优先级
		$this->view('edit' ,true);
	}

	/**
	 * 变更计划
	 */
	function c_edit() {
//		echo "<pre>";
//		print_R($_POST[$this->objName]);
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->edit_d($_POST[$this->objName])) {
			msg ( '变更成功！' );
		} else {
			msg ( '变更失败！' );
		}
	}

	/**
	 * 跳转到查看生产计划页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //显示合同或者源单
		$this->view ( 'view' );
	}

		/**
	 * 跳转到查看生产计划页面
	 */
	function c_toCloseView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'close-view' );
	}

	/**
	 * 跳转到查看生产计划tab页面
	 */
	function c_toViewTab() {
		$this->permCheck (); //安全校验
		$this->assign('id' ,$_GET ['id']);
		$this->view('view-tab');
	}

	/**
	 * 跳转到确定工序页面
	 */
	function c_toSureProcess() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('proSureName' ,$_SESSION['USERNAME']);
		$this->assign('proSureId' ,$_SESSION['USER_ID']);
		$this->view('sureprocess' ,true);
	}

	/**
	 * 确定工序
	 */
	function c_sureProcess() {
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->sureProcess_d($_POST[$this->objName])) {
			msg ( '确定成功！' );
		} else {
			msg ( '确定失败！' );
		}
	}

	/**
	 * 跳转到进度反馈页面
	 */
	function c_toFeedback() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('feedbackName' ,$_SESSION['USERNAME']);
		$this->assign('feedbackId' ,$_SESSION['USER_ID']);
		$this->assign('feedbackDate' ,date('Y-m-d'));

		$this->view('feedback' ,true);
	}

	/**
	 * 进度反馈
	 */
	function c_feedback() {
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->feedback_d($_POST[$this->objName])) {
			msg ( '反馈成功！' );
		} else {
			msg ( '反馈失败！' );
		}
	}

	/**
	 * 跳转到质检申请页面
	 */
	function c_toAddQuality() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$productDao = new model_stock_productinfo_productinfo();
//		$productObj = $productDao->get_d($obj['productId']);
//		$this->showDatadicts(array('checkType' => 'ZJFS') ,$productObj['checkType']);  //质检方式

//		$qualityApplyNum = 0; //质检申请数量
//		$qualityDao = new model_produce_quality_qualityapply();
//		$qualityObj = $qualityDao->findAll(array('relDocCode' => $obj['docCode'] ,'status' => '0'));
//		if (is_array($qualityObj)) {
//			$applyItemDao = new model_produce_quality_qualityapplyitem();
//			foreach ($qualityObj as $key => $val) {
//				$applyItemObj = $applyItemDao->find(array('mainId' => $val['id']));
//				if ($applyItemObj) {
//					$qualityApplyNum += $applyItemObj['qualityNum'];
//				}
//			}
//		}

		$planNum = $obj['planNum'] - $obj['qualifiedNum'] ; //可申请数量=计划数量-质检合格的数量-质检申请数量
		$this->assign('qualifiedNumNew' ,$planNum > 0 ? $planNum : 0);
		$this->view('add-quality' ,true);
	}

	/**
	 * 跳转到关闭申请页面
	 */
	function c_toClose() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('close' ,true);
	}

	/**
	 * 跳转到关闭申请页面
	 */
	function c_close() {
		$this->checkSubmit(); //验证是否重复提交
		if($this->service->editSimple_d($_POST[$this->objName])) {
				succ_show('controller/produce/plan/ewf_close.php?actTo=ewfSelect&billId='.$_POST[$this->objName]["id"].'&billDept='.$_SESSION["DEPT_ID"]);
		} else {
				msg( '提交失败！' );
		}
	}


	  /**
	   * ajax关闭生产计划
	   */
	   function c_toCancel(){
		   	try{
//		   		$this->permCheck($_POST['id']);
//			 	if( !isset($this->service->this_limit['生产计划']) ){
//			 		echo 2;
//			 		return 0;
//			 	}
			  	$planId = $_POST['id'];
			 	$planInfo = $this->service->get_d($planId);

			  	$planArr = array(
			  		'id' => $planId,
			  		'isCancel' => '1',
			  		'docStatus' => '6'
			  	);
			  	$flag = $this->service->updateById( $planArr );
			  	if ($flag){
				  	$taskDao = new model_produce_task_producetask();
					$configDao = new model_produce_task_configproduct();
					$configDao ->updateById(array('id' => $planInfo['configId'] ,'planNum' => 0));
				  	$taskDao->dealDocStatus_d($planInfo['taskId']); //处理生产计划单据状态

					$changeDao = new model_produce_plan_producechange();
					$changeArr = array(
						'createName'=>$_SESSION['USERNAME'],
						'createId'=>$_SESSION['USER_ID'],
						'planId'=>$planInfo['id'],
						'changeType'=>'cancel',
						'remark'=>$planInfo['remark'],
						'planCode'=>$planInfo['docCode'],
						'createTime'=>date('y-m-d h:i:s',time())
					);
					$changeDao->add_d($changeArr);
			  	}
				echo $flag;
		   	}catch( Exception $e ){
		   		echo 0;
		   	}
	   }


	/**
	 * 质检申请
	 */
	function c_addQuality() {
		$this->checkSubmit(); //验证是否重复提交
		$_POST[$this->objName]['qualifiedNum']=$_POST['qualifiedNumOld']+$_POST['qualifiedNumNew'];
		if ($this->service->editSimple_d($_POST[$this->objName])) {
			msg ( '确认成功！' );
		} else {
			msg ( '确认失败！' );
		}
	}

	/**
	 * 生产计划报表
	 */
	function c_toProduceplanReport(){
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		if($beginDate){//判断是否传入查询条件
		 	$this->assign("beginDate",$beginDate);
		}else{
		 	$beginDate=date("Y-m")."-01";
		 	$this->assign("beginDate",$beginDate);
		}
		$this->display('produceplan');
	}

	/**
	 * 关闭生产计划
	 */
	function c_closePlan() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "3" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * 跳转到生产任务导出页面
	 */
	function c_toExcelOut() {
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,null ,true);  //优先级
		$this->view('excelout' ,true);
	}

	/**
	 * 导出excel
	 */
	function c_excelOut() {
		$this->checkSubmit();
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['docCode'])) //单据编号
			$this->service->searchArr['docCode'] = $formData['docCode'];
		if(!empty($formData['docDate'])) //单据日期
			$this->service->searchArr['docDate'] = $formData['docDate'];

		if(!empty($formData['productCode'])) //物料编号
			$this->service->searchArr['productCode'] = $formData['productCode'];
		if(!empty($formData['productName'])) //物料名称
			$this->service->searchArr['productName'] = $formData['productName'];

		if(!empty($formData['urgentLevelCode'])) //优先级
			$this->service->searchArr['urgentLevelCode'] = $formData['urgentLevelCode'];
		if(!empty($formData['productionBatch'])) //生产批次
			$this->service->searchArr['productionBatch'] = $formData['productionBatch'];

		if(!empty($formData['taskCode'])) //生产任务编号
			$this->service->searchArr['taskCode'] = $formData['taskCode'];
		if(!empty($formData['relDocCode'])) //合同编号
			$this->service->searchArr['relDocCode'] = $formData['relDocCode'];

		if(!empty($formData['applyDocCode'])) //生产申请单号
			$this->service->searchArr['applyDocCode'] = $formData['applyDocCode'];
		if(!empty($formData['customerName'])) //客户名称
			$this->service->searchArr['customerName'] = $formData['customerName'];

		if(!empty($formData['planStartDate'])) //计划开始时间
			$this->service->searchArr['planStartDate'] = $formData['planStartDate'];
		if(!empty($formData['planEndDate'])) //计划结束时间
			$this->service->searchArr['planEndDate'] = $formData['planEndDate'];

		if(!empty($formData['chargeUserName'])) //责任人
			$this->service->searchArr['chargeUserName'] = $formData['chargeUserName'];
		if(!empty($formData['saleUserName'])) //销售代表
			$this->service->searchArr['saleUserName'] = $formData['saleUserName'];

		if(!empty($formData['deliveryDate'])) //交货日期
			$this->service->searchArr['deliveryDate'] = $formData['deliveryDate'];

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
			$rowData[$k]['urgentLevel'] = $v['urgentLevel'];
			$rowData[$k]['docDate'] = $v['docDate'];
			$rowData[$k]['proType'] = $v['proType'];
			$rowData[$k]['productName'] = $v['productName'];
			$rowData[$k]['productCode'] = $v['productCode'];
			$rowData[$k]['planNum'] = $v['planNum'];
			$rowData[$k]['qualifiedNum'] = $v['qualifiedNum'];
			$rowData[$k]['stockNum'] = $v['stockNum'];
			$rowData[$k]['taskCode'] = $v['taskCode'];
			$rowData[$k]['relDocCode'] = $v['relDocCode'];
			$rowData[$k]['applyDocCode'] = $v['applyDocCode'];
			$rowData[$k]['customerName'] = $v['customerName'];
			$rowData[$k]['productionBatch'] = $v['productionBatch'];
			$rowData[$k]['planStartDate'] = $v['planStartDate'];
			$rowData[$k]['planEndDate'] = $v['planEndDate'];
			$rowData[$k]['chargeUserName'] = $v['chargeUserName'];
			$rowData[$k]['saleUserName'] = $v['saleUserName'];
			$rowData[$k]['deliveryDate'] = $v['deliveryDate'];
			$rowData[$k]['remark'] = $v['remark'];
		}

		$colArr  = array();
		$modelName = '生产-生产计划信息';
		return model_produce_basic_produceExcelUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * 导出单个excel
	 */
	function c_excelOutOne() {
		set_time_limit(0);

		$obj = $this->service->get_d($_GET['id']);

		if ($obj) {
			//配置
			$taskDao = new model_produce_task_producetask();
			$taskObj = $taskDao->get_d($obj['taskId']);
			$obj['purpose'] = $taskObj['purpose']; //用途
			$obj['technology'] = $taskObj['technology']; //工艺
			$obj['salesExplain'] = $taskObj['salesExplain']; //销售说明

			$configDao = new model_produce_task_configproduct();
			$configObjs = $configDao->findAll(array('taskId' => $obj['taskId']));
			$obj['config'] = ''; //配置名称

			$taskconDao = new model_produce_task_taskconfig(); //配置表头
			$conItemDao = new model_produce_task_taskconfigitem(); //配置内容

			foreach ($configObjs as $key => $val) {
				$obj['config'] .= ($key + 1)."、$val[productCode]\r\n"; //配置名称

				$obj['productInfo'][$key]['tableHead'] = $taskconDao->findAll(array('taskId' => $obj['taskId'] ,'configId' => $val['productId']) ,'id ASC');
				foreach ($obj['productInfo'][$key]['tableHead'] as $ke => $va) {
					$conItemObjs = $conItemDao->findAll(array('parentId' => $va['id'] ,'colCode' => $va['colCode']) ,'id ASC');
					foreach ($conItemObjs as $k => $v) {
						$obj['productInfo'][$key]['tableBody'][$k][$ke] = $v;
					}
				}
			}

			//工序
			$processDao = new model_produce_plan_planprocess();
			$obj['process'] = $processDao->findAll(array('planId' => $obj['id']));

			return model_produce_basic_produceExcelUtil::exportProduce($obj);
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}
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
		$this->assign('planIdStr', $_GET['planIdStr']);
		$this->view('statistics' ,true);
	}

	function c_codelistJson(){
		$datas = $this->service->get_produceTask($_REQUEST['productCode'],$_REQUEST['ids'],$_REQUEST['planIds']);
		$pickDao = new model_produce_plan_picking();
		foreach($datas as $key => $val){
			$produce[$key]['id'] = $val['planId'];//计划单id
			$produce[$key]['planCode'] = $val['planCode'];//计划单号
			$produce[$key]['relDocCode'] = $val['relDocCode'];
			$produce[$key]['productionBatch'] = $val['productionBatch'];
			$produce[$key]['number'] = $val['num'];
			$produce[$key]['productId'] = $val['productId'];

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

	function c_classify(){
		$datas = $this->service->get_classify( $_POST['id'],$_POST['productId'],$_POST['productCode'] );
		echo util_jsonUtil::encode ( $datas );
	}

	/**
	 * pmc下推领料申请时获取所有数据返回json(单个计划单)
	 */
	function c_classifyByPicking(){
		$service = $this->service;
		$taskId = $_POST['taskId'];
		$planId = $_POST['planId'];
		$productId = isset($_POST['productId']) ? $_POST['productId'] : null;
		$rs = $service->get_classify( $taskId , $productId);
		if(!empty($rs)){
			$planObj = $service->get_d($planId);
			$rows = array();
			// 只获取当前计划单的物料
			foreach ($rs as $v){
				if($v['code'] == $planObj['productCode']){
					array_push($rows, $v);
				}
			}
			if(!empty($rows)){
				$pickingitemDao = new model_produce_plan_pickingitem();
				$rtArr = $pickingitemDao->getRowsByPlan_d($rows,$taskId,$planId,$productId);
				echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	/**
	 * pmc下推补料申请时获取所有数据返回json
	 */
	function c_classifyByPickingPlus(){
		$service = $this->service;
		$taskId = $_POST['taskId'];
		$planId = $_POST['planId'];
		$productId = isset($_POST['productId']) ? $_POST['productId'] : null;
		$rs = $service->get_classify( $taskId , $productId);
		if(!empty($rs)){
			$planObj = $service->get_d($planId);
			$rows = array();
			// 只获取当前计划单的物料
			foreach ($rs as $v){
				if($v['code'] == $planObj['productCode']){
					array_push($rows, $v);
					break;
				}
			}
			if(!empty($rows)){
				$pickingitemDao = new model_produce_plan_pickingitem();
				$rtArr = $pickingitemDao->getRowsByPlanPlus_d($rows,$taskId,$planId,$productId);
				echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	/**
	 * pmc下推领料申请时获取所有数据返回json(多个计划单)
	 */
	function c_classifyByPickingMulti(){
		if(empty($_POST['planId']) || empty($_POST['productId'])){
			echo '';
		}else{
			$planIdArr = explode(',', $_POST['planId']);
			$productIdArr = explode(',', $_POST['productId']);
			if(count($planIdArr) != count($productIdArr)){// 计划单id与物料id必须一一对应
				echo '';
			}else{
				$service = $this->service;
				$rows = array();
				foreach ($planIdArr as $k => $v){
					$rs = $service->find(array('id' => $v));
					if(!empty($rs)){
						$row = $service->get_classify( $rs['taskId'] , $productIdArr[$k]);
						if(!empty($row)){
							$val = $row[0];
							$val['relDocId'] = $rs['relDocId'];
							$val['relDocCode'] = $rs['relDocCode'];
							$val['relDocName'] = $rs['relDocName'];
							$val['relDocType'] = $rs['relDocType'];
							$val['relDocTypeCode'] = $rs['relDocTypeCode'];
							$val['applyDocId'] = $rs['applyDocId'];
							$val['applyDocItemId'] = $rs['applyDocItemId'];
							$val['taskId'] = $rs['taskId'];
							$val['taskCode'] = $rs['taskCode'];
							$val['planId'] = $rs['id'];
							$val['planCode'] = $rs['docCode'];
							$val['productionBatch'] = $rs['productionBatch'];
							$val['customerId'] = $rs['customerId'];
							$val['customerName'] = $rs['customerName'];
							array_push($rows, $val);
						}
					}
				}
				if(!empty($rows)){
					$pickingitemDao = new model_produce_plan_pickingitem();
					$rtArr = $pickingitemDao->getRowsByPlanMutil_d($rows);
					echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
				}else{
					echo '';
				}
			}
		}
	}

	/**
	 * pmc下推入库通知单时获取所有数据返回json
	 */
	function c_listJsonInnotice() {
		$rows = $this->service->listJsonInnotice_d ($_POST['id']);
		if(!empty($rows)){
			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );
			echo util_jsonUtil::encode ( $rows );
		}else{
			echo '';
		}
	}

	/**
	 * 下达领料申请时验证
	 */
	function c_pickCheck() {
		echo util_jsonUtil::encode($this->service->pickCheck_d($_POST['productCodes'],$_POST['taskIds']));
	}

	/**
	 * 下达领料申请时处理
	 */
	function c_pickDeal() {
		echo util_jsonUtil::encode($this->service->pickDeal_d($_POST['productCodes'],$_POST['taskIds']));
	}

	/**
	 * 确认领料
	 */
	function c_meetPick() {
		if($this->service->meetPick_d ( $_POST ['ids'] )){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		if(empty($_REQUEST['ids'])){
			unset($_REQUEST['ids']);
		}
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 跳转到修改物料模板
	 */
	function c_toEditClassify() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //显示合同或者源单
		$this->view('editclassify');
	}
	
	/**
	 * 修改物料模板
	 */
	function c_editClassify() {
		if ($this->service->editClassify_d($_POST[$this->objName])) {
			msg('修改成功');
		}else{
			msg('修改失败');
		}
	}
	
	/**
	 * 增加已打印数量
	 */
	function c_changePrintCountId() {
		echo $this->service->changePrintCountId_d($_POST['id']);
	}
 }