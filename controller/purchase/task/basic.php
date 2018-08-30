<?php
/**
 * 采购任务主表控制类
 */
class controller_purchase_task_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "purchase_task";
		parent::__construct ();
	}


/*****************************************显示页面开始********************************************/
	/**
	 * 由资产采购需求生成采购任务
	 *
	 */
	 function c_toAddTask(){
	 	$applyId=isset($_GET['id'])?$_GET['id']:null;
	 	$applyItemDao=new model_asset_purchase_apply_applyItem();
	 	//获取采购需求信息
	 	$applyItemRow=$applyItemDao->getItemByApplyId($applyId,'1');
		$this->assign('sendTime',date("Y-m-d"));
		//显示物料模板
		$taskEquDao=new model_purchase_task_equipment();
		$list=$taskEquDao->showAddTask_s($applyItemRow);
		$this->assign('list',$list);
	 	$this->display('apply-task-add');
	 }


	/**
	 * 由资产采购需求生成采购任务
	 *
	 */
	 function c_toAddTaskByIds(){
	 	$idArr=isset($_GET['idArr'])?$_GET['idArr']:null;
	 	$applyItemDao=new model_asset_purchase_apply_applyItem();
	 	//获取采购需求信息
	 	$applyItemRow=$applyItemDao->getItemByIdArr($idArr);
		$this->assign('sendTime',date("Y-m-d"));
		//显示物料模板
		$taskEquDao=new model_purchase_task_equipment();
		$list=$taskEquDao->showAddTask_s($applyItemRow);
		$this->assign('list',$list);
	 	$this->display('apply-task-add');
	 }

	/**
	 * 我的已关闭任务
	 */
	function myCloseList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//分页
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('allCheckBox', '');
		$this->show->assign('list', $this->service->showMyCloselist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**
	 * @exclude 变更通知列表
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 下午08:46:13
	 */
	function changeNotice ($clickNumb) {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$searchArr['state'] = $this->service->stateToSta("change");
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();

		//分页
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('list', $this->service->showChangeNotice($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-changeNotice');
		unset($this->show);
	}

	/*
	 * @desription 跳转到采购任务的Tab页
	 * @param tags
	 * @author qian
	 * @date 2010-12-15 下午02:48:43
	 */
	function c_taskList () {
		$this->show->display($this->objPath . '_' .$this->objName . '-tab' );
	}

	/**
	 * 执行中任务
	 */
	function c_executionList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr = array (
			"state" => $this->service->statusDao->statusEtoK('execute'),
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
//		$service->__SET('groupBy', "Id");
//		$service->__SET('sort', "id");
//		$service->__SET('asc', false);
		$service->__SET('searchArr', $searchArr);
		$service->sort = "dateReceive";
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListExecution');
		$this->show->assign('allCheckBox', '<input type="checkbox">');
		$this->show->assign('list', $this->service->showExecutionlist_s($rows));
		$this->show->assign('idsArry', $idsArry);
		$this->show->assign('btnGo', '&nbsp;<input type="button" class="txt_btn_a" value="生成采购询价单" onclick="purchForm()"/>');
		$this->show->assign('btnOrder', '&nbsp;<input type="button" class="txt_btn_a" value="下达采购订单" onclick="addOrder()"/>');
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * 已关闭任务
	 */
	function c_closeList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr = array (
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		//分页
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'closeList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('allCheckBox', '');
		//TODO:扩展 已关闭采购任务
		//$this->show->assign('list', $this->showMyCloselist($rows, $showpage));
		$this->show->assign('list',  $this->service->showExecutionlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-close-list');
		unset($this->show);
		unset($service);
	}

	/*
	 * @desription 我分配的采购任务管理-设备列表
	 * @param tags
	 * @author qian
	 * @date 2011-1-12 上午10:06:01
	 */
	function c_taskAssignList () {
		$this->display('tab-mytask');
	}

	/**
	 * 我分配的待接收任务
	 */
	function c_waitAssignList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		//"createName" => $_SESSION['USERNAME'],
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta('begin'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'waitAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		$this->show->assign('list', $this->service->showWaitlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * 我分配的执行中任务
	 */
	function c_executionAssignList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta('execute'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'executionAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		//TODO:扩展采购任务 我分配的执行任务
		$this->show->assign('list',  $this->service->showAllotExecutionlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**根据采购计划ID，查找相关的采购任务，显示列表.
	*author can
	*2011-2-14
	*/
	function c_readTaskByPlanId() {
		$planId=isset($_GET['basicId'])?$_GET['basicId']:'';
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$planNumb = isset( $_GET['basicNum'] )?$_GET['basicNum']:"";
		//根据计划ID,查找任务设备
		$taskEquDao=new model_purchase_task_equipment();
		$EquRows=$taskEquDao->findByPlanId($planId);
		if(is_array($EquRows)){     //判断该采购计划是否存在有关联的采购任务
			$taskIds=array();
			foreach($EquRows as $key=>$val){
				$taskIds[]=$val['basicId'];
			}
			//去掉值重复的元素
			$taskIds=array_unique($taskIds);
			$searchArr=array(
	//			"createId" => $_SESSION['USER_ID'],
				"ids"=>$taskIds
			);
		}else{
			$searchArr=array(
				"ids"=>""
			);
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->assign('allCheckBox', '');
		$this->assign('planNum', $planNumb);
		$this->assign('list',  $this->service->showAllotExecutionlist($rows, $showpage));
		$this->display('changetask');
	}

	/**
	 * 我分配的已关闭任务
	 */
	function c_closeAssignList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'closeAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		//TODO:扩展采购任务 我分配的关闭任务
		//$this->show->assign('list', $this->showMyCloselist($rows, $showpage));
		$this->show->assign('list',  $this->service->showWaitlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * 查看方法
	 */
	function c_read(){
		$this->permCheck ();//安全校验
		$id = isset( $_GET['id'] )? $_GET['id'] : exit ;
		$arr = $this->service->readTask_d($id);
		$this->arrToShow($arr);
		$this->assign('skey',$_GET['skey']);
		$this->show->assign('list', $this->service->showTaskRead($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

	/**
	 * 导出采购任务
	 *
	 */
	 function c_exportTask(){
		$id=isset($_GET['id'])?$_GET['id']:"";
		//获取主表数据
		$row = $this->service->readTask_d($id);
		$dao = new model_purchase_contract_purchaseUtil ();
		return $dao->exportTask ( $row); //导出Excel
	 }

	/**
	 * 重新分配采购任务页面
	 */
	function c_toAssignTask () {
		$this->permCheck ();//安全校验
		$id = isset( $_GET['id'] )? $_GET['id'] : exit ;
		$arr = $this->service->readTask_d($id);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-assign');
	}

	/**
	 * 跳转到新增采购申请单页面
	 */
	function c_toAdd() {
		$idsArry = isset($_GET['idsArry'])? substr( $_GET['idsArry'],1 ):exit;
		$this->service->getParam($_GET);
		$obj =  new model_purchase_task_equipment();
		$listEqu = $obj->getTaskEquAsEqu_d($idsArry);
		if($listEqu){
			$this->show->assign('userName', $_SESSION['USERNAME']);
			$this->show->assign('userId', $_SESSION['USER_ID']);
			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
			$this->show->assign('suppId', "0");
			$this->show->assign('list', $this->service->newApply($listEqu));

			$applyNumb = "pa-".date("YmdHis").rand(10,99);
			$this->show->assign('applyNumb', $applyNumb);
			$this->show->display($this->objPath . '_apply-new');
		}else {
			showmsg('错误','temp','button');
		}
		unset($this->show);
	}

	/**
	 * @exclude 跳转变更采购任务
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 下午03:52:20
	 */
	function c_toChange () {
		$this->permCheck ();//安全校验
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$numb = isset($_GET['numb'])?$_GET['numb']:exit;

		$arr = $this->service->toChange_d($id,$numb);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChange($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-change');
	}

	/**
	 * @exclude 查看变更通知
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 上午09:54:20
	 */
	function c_readReceive () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$clickNumb = isset( $_GET['clickNumb'] )?$_GET['clickNumb']:1;
		$arrayPanel = array("numb"=>2,
								"clickNumb"=> $clickNumb,
								"name1"=>"新采购任务",
								"title1"=>"新采购任务",
								"url1"=>"?model=purchase_task_basic&action=readReceive&clickNumb=1&id=".$id."&numb=".$numb,
								"name2"=>"原采购任务",
								"title2"=>"原采购任务",
								"url2"=>"?model=purchase_task_basic&action=readReceive&clickNumb=2&id=".$id."&numb=".$numb
							);
		$topPlan = parent::topPlan($arrayPanel);
		$this->show->assign('topPlan', $topPlan);

		if($clickNumb == '1'){
			$this->changeReadNew($id,$numb);
		}
		else if($clickNumb == '2'){
			$this->changeReadOld($id,$numb);
		}
	}

	/**
	 * @exclude 查看变更新的采购任务
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 上午09:43:30
	 */
	function changeReadNew ($id,$numb) {
		$arr = $this->service->readTaskByBasicId_d($id);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChangeRead($arr["0"]["childArr"]) );
		$this->show->assign('id', $id );
		$this->show->assign('numb', $numb );
		$this->show->display($this->objPath . '_' . $this->objName . '-changeRead');
	}

	/**
	 * @exclude 查看变更后旧的采购任务
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 上午09:45:18
	 */
	function changeReadOld ($id,$numb) {
		$searchArr['state'] = $this->service->stateToSta('Locking');
		$searchArr['taskNumb'] = $numb;
		$searchArr['isUse'] = '1';
		$service = $this->service;
		$service->__SET('searchArr', $searchArr);
		$arr = $this->service->pageList_d();
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChangeRead($arr["0"]["childArr"]) );
		$this->show->assign('id', $id );
		$this->show->assign('numb', $numb );
		$this->show->display($this->objPath . '_' . $this->objName . '-changeRead');
	}

	/**
	 * 跳转到新增采购任务页面
	 */
	function c_toAddInquiry() {
		$idsArry = isset($_GET['idsArry'])? substr( $_GET['idsArry'],1 ):exit;
		$this->service->getParam($_GET);
		$taskEquDao =  new model_purchase_task_equipment();
		$listEqu = $taskEquDao->getTaskEquAsEqu_d($idsArry);
		if($listEqu){
			$this->show->assign('userName', $_SESSION['USERNAME']);
			$this->show->assign('userId', $_SESSION['USER_ID']);
			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
			$this->show->assign('suppId', "0");
			$this->show->assign('list', $taskEquDao->newInquiry_s($listEqu));

			$applyNumb = "pa-".date("YmdHis").rand(10,99);
			$this->show->assign('applyNumb', $applyNumb);
			$this->show->display($this->objPath . '_inquiry-new');
		}else {
			showmsg('错误','temp','button');
		}
		unset($this->show);
	}

	/**
	 * @desription 我的待接收任务页面
	 * @param tags
	 * @date 2010-12-23 下午02:20:15
	 */
	function c_taskMyListReceive () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:'';
		$searchArr['state'] = $this->service->statusDao->statusEtoK('begin');
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr['sendUserId'] = $_SESSION['USER_ID'];
		if($taskNumb!=''){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);

		/*s:-----------------排序控制-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------排序控制-------------------*/
//		$service->__SET('groupBy', 'Id');
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListReceive');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showMyWaitlist_s( $rows ));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**
	 * 未执行的采购任务列表
	 */
	function c_waitList () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:'';
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr['state'] = $this->service->statusDao->statusEtoK('begin');
		if($taskNumb!=''){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', 'Id');
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListReceive');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showWaitlist_s( $rows ));
		$this->show->display($this->objPath . '_' . $this->objName . '-wait-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * @desription 我的执行中任务
	 * @param tags
	 * @date 2010-12-23 下午04:55:37
	 */
	function c_taskMyListExecution () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
			"state" => $this->service->statusDao->statusEtoK('execute'),
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->sort = "dateReceive DESC,updateTime";
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分

		/*s:-----------------排序控制-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------排序控制-------------------*/

		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		//对列表按照接收时间和更新时间来进行排序
//		if(is_array($rows)){
//			foreach($rows as $key=>$val){
//				$dateReceive[$key]=$val['dateReceive'];
//				$updateTime[$key]=$val['updateTime'];
//			}
//			array_multisort($dateReceive,SORT_DESC,$updateTime,SORT_DESC,$rows);
//		}
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListExecution');
		$this->show->assign('allCheckBox', '<input type="checkbox">');
		$this->show->assign('list', $this->service->showMyExecutionlist_s($rows));
		$this->show->assign('idsArry', $idsArry);
		$this->show->assign('btnGo', '&nbsp;<input type="button" class="txt_btn_a" value="生成采购询价单" onclick="purchForm()"/>');
		$this->show->assign('btnOrder', '&nbsp;<input type="button" class="txt_btn_a" value="下达采购订单" onclick="pushOrder()"/>');
		$this->show->display($this->objPath . '_' . $this->objName . '-list-mytask');
		unset($this->show);
		unset($service);
	}

	/**
	 * 跳转到执行中的物料汇总页面
	 *
	 */
	 function c_toExeEquList(){
		$equNumb = isset( $_GET['equNumb'] )?$_GET['equNumb']:"";
		$equName = isset( $_GET['equName'] )?$_GET['equName']:"";
		$idsArry = isset( $_GET['idsArry'] )?$_GET['idsArry']:"";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$searchArr = array();
		if($equNumb!=""){
			$searchArr['seachProductNumb'] = $equNumb;
		}
		if($equName!=""){
			$searchArr['productName'] = $equName;
		}
		if($purchType=="contract_sales"){
			$searchArr['purchTypeArr'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//借试用采购
			$searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//显示全部采购类型的物料汇总表
			//$searchArr['purchTypeArr'] = "" ;
		}else{
			$searchArr['purchTypeArr']=$purchType;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.productId,p.productNumb");

//		$rows = $service->pageEqu_d();
		$this->pageShowAssign();

		$this->assign('equNumb', $equNumb);
		$this->assign('equName', $equName);
		$this->assign('idsArry', $idsArry);
		$this->assign ( 'purchType', $purchType );
//		$this->assign('list', $this->service->showEqulist_s($rows));
		$this->display('list-equ');
		unset($this->show);
		unset($service);
	 }

	/**
	 * 我的采购任务
	 */
	function c_taskMyList(){
		$this->show->display($this->objPath . '_' .$this->objName . '-myTop');
	}

	/**
	 * 我的变更的采购任务列表
	 */
	function c_taskMyListChange(){
		$this->show->display($this->objPath . '_' .$this->objName . '-my-change-list');
	}

	/**
	 * @description 我的申请变更列表
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['sendUserId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ("list_page_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**我的已关闭采购任务列表
	*author can
	*2011-1-5
	*/function c_taskMyListClose(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$clickNumb = isset( $_GET['clickNumb'] )?$_GET['clickNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
//			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"stateInArr" => $this->service->statusDao->statusEtoK('close').",".$this->service->statusDao->statusEtoK('end'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->sort = "dateFact";
		$service->__SET('searchArr', $searchArr);

		/*s:-----------------排序控制-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------排序控制-------------------*/
//		$service->__SET('groupBy', "Id");
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListClose');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showMyCloselist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**跳转到任务反馈页面
	 * @author suxc
	 *
	 */
	 function c_toFeedBack(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$row=$this->service->get_d($id);
		//获取物料信息
		$arr = $this->service->readTask_d($id);
		//获取采购申请人
		$applyManArr=$this->service->getApplyMan_d($arr["0"]["childArr"]);
		array_push($applyManArr['sendId'],$row['createId']);
		array_push($applyManArr['sendName'],$row['createName']);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('sendId',implode(',',array_unique($applyManArr['sendId'])));
		$this->assign('sendName',implode(',',array_unique($applyManArr['sendName'])));
		$this->show->assign('list', $this->service->showFeedbackList($arr["0"]["childArr"]) );
		$this->display('feedback');

	 }

	 /**跳转到采购任务关闭页面
	 * @author Administrator
	 *
	 */
	 function c_toClose(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$type=isset( $_GET['type'] )?$_GET['type']:"";
		$row=$this->service->get_d($id);
		//获取物料信息
		$arr = $this->service->readTask_d($id);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('type',$type);
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->display('close');

	 }

	 /**跳转到采购任务关闭页面
	 * @author Administrator
	 *
	 */
	 function c_toCloseRead(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$readType=isset($_GET['actType'])?$_GET['actType']:"";
		$this->assign ('readType',$readType );
		$row=$this->service->get_d($id);
		//获取物料信息
		$arr = $this->service->readTask_d($id);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->display('close-read');
	 }
	 /*
	  * 采购任务，历史价格详细信息显示页面
	  */
	function c_detailPrice(){
		$productNumb = $_GET['num'] ;
		$createTime = $_GET['createTime'] ;
		$orderEquDao=new model_purchase_contract_equipment();
		$priceData=$orderEquDao->getHistoryInfo_d( $productNumb,$createTime);
		foreach($priceData as $key => $val){
			if($key =='applyPrice'){
				$val = sprintf("%.3f",$val);	//保留三位小数
			}
			$this->assign ( $key, $val );
		}
		$this->display('priceView');
	}
/*****************************************显示页面结束********************************************/

/*****************************************业务处理开始********************************************/

	/**
	 * @exclude 接收变更
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 上午10:12:05
	 */
	function c_receiveChange () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$obj = $this->service->receiveChange_d($id,$numb);
		if($obj){
			msgGo("接收成功！","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
		else{
			msgGo("接收失败！","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
	}

	/**
	 * @exclude 退回变更
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 上午10:13:05
	 */
	function c_returnChange () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$obj = $this->service->returnChange_d($id,$numb);
		if($obj){
			msgGo("退回成功！","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
		else{
			msgGo("退回失败！","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
	}

	/**
	 * @desription 新增采购任务对象操作
	 * @param tags
	 * @return
	 * @date 2010-12-22 下午02:29:49
	 */
	function c_add() {
		$object = $this->service->add_d( $_POST [$this->objName] );
		if ($object) {
			msgBack2('下达成功！');
		}else{
			msgBack2('下达失败！');
		}
	}

	/**
	 * @desription 重新分配采购任务
	 */
	function c_edit() {
		$object = $this->service->edit_d( $_POST [$this->objName] ,true);
		if ($object) {
			msgGo("保存成功！","?model=purchase_task_basic&action=waitList");
		}else{
			msgGo("保存成功！","?model=purchase_task_basic&action=waitList");
		}
	}

	/**
	 * @desription 接收采购任务
	 * @param tags
	 * @date 2010-12-23 下午04:28:06
	 */
	function c_stateBegin(){
		$id = isset( $_GET['sid'] )?$_GET['sid']:exit;
		if( $this->service->stateBegin_d($id) ){
			msgGo('接收成功');
		}else{
			msgGo('失败！！可能是服务器错误，请稍后再试');
		}
	}

	/**
	 * @exclude 变更保存
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 下午06:23:57
	 */
	function c_change () {
		try {
			$id = $this->service->change_d ( $_POST ['basic'] );
			if($id){
				msgBack2("变更成功！");
			}else{
				msgBack2("变更不成功！");
			}
		} catch ( Exception $e ) {
			msgBack2 ( "变更失败！失败原因：" . $e->getMessage () );
		}
	}

	/**
	 * @exclude 完成采购任务
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 下午07:40:19
	 */
	function c_end(){
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$url = "?model=purchase_task_basic&action=taskMyListExecution";
		$val = $this->service->end_d($id);
		if( $val==1 ){
			msgGo("操作成功");
		}
		else if($val==2){
			msgGo("存在未完成的物料，不能完成");
		}
		else{
			msgGo("操作失败！！可能是服务器错误，请稍后再试");
		}
	}

	 /**
	 * 批量更新采购任务的状态为完成
	 *
	 */
	 function c_updateStateEnd(){
		$flag = $this->service->updateData_d ();
		if($flag){
			echo "<script>alert('更新成功');this.location='?model=purchase_task_basic&action=executionList'</script>";
		}else{
			echo "<script>alert('更新失败');this.location='?model=purchase_task_basic&action=executionList'</script>";
		}

	 }

	/**
	 * @exclude 关闭采购任务
	 * @param
	 * @version 2010-8-10 下午07:42:08
	 */
/*	function c_close () {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = $this->service->findNumbById_d($id);
		if( $this->service->close_d($numb) ){
			msgGo("关闭成功");
		}else{
			msgGo("关闭失败！！可能是服务器错误，请稍后再试");
		}
	}*/
	function c_close () {
		$row= $_POST [$this->objName] ;
//		echo "<pre>";
//		print_r($row);
		$flag = $this->service->edit_d($row);
		if($row['type']=="exelist"){
			echo "<script>this.location='controller/purchase/task/ewf_close2.php?actTo=ewfSelect&examCode=oa_purch_task_basic&billId=" . $row['id'] . "'</script>";
		}else{
			echo "<script>this.location='controller/purchase/task/ewf_close.php?actTo=ewfSelect&examCode=oa_purch_task_basic&billId=" . $row['id'] . "'</script>";
		}
//		if( $this->service->close_d($flag) ){
//			msgBack2("提交成功");
//		}else{
//			msgBack2("提交失败");
//		}
	}

	/**
	 * @exclude 重新启动采购任务
	 * @param
	 */
	function c_startTask() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->startTask_d($id) ){
			msgGo("启动成功");
		}else{
			msgGo("启动失败！！可能是服务器错误，请稍后再试");
		}
	}

	/**
	 * 下达采购询价单或采购订单时，判断物料的采购类型是否一致
	 *
	 */
	 function c_isSameType(){
		$taskEquDao = new model_purchase_task_equipment();
	 	$idsArr=$_POST['ids'];
	 	$flag=$taskEquDao->isSameType_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

	 /**采购任务反馈
	 * @author Administrator
	 *
	 */
	 function c_feedback(){
		$flag = $this->service->feedback_d( $_POST [$this->objName] );
		if($flag){
			msgBack2("提交成功！");
		}else{
			msgBack2("提交失败！");
		}

	 }

	 /**
	 *判断是否已提交关闭申请
	 *
	 */
	 function c_isSubClose(){
	 	$id=$_POST['id'];
	 	$row=$this->service->get_d($id);
	 	if($row['ExaStatus']!='部门审批'){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }


/*****************************************业务处理结束********************************************/
}
?>
