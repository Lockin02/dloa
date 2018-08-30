<?php


/**
 * @description: 项目任务action
 * @date 2010-9-14 下午02:45:13
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_task_rdtask extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-14 下午02:45:48
	 */
	function __construct() {
		$this->objName = "rdtask";
		$this->objPath = "rdproject_task";
		$this->operArr = array (
			"name" => "任务名称",
			"appraiseWorkload" => "估计工作量",
			"remark" => "任务描述",
			"markStoneName" => "标记的里程碑",
			"planBeginDate" => "计划开始时间",
			"planEndDate" => "计划结束时间"
		); //统一注册监控字段，如果不同方法有不同的监控字段，在各自方法里面更改此数组
		parent :: __construct();
	}

	/*
	 * 跳转到项目任务汇总信息列表
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->page_d();
		//分页
		$showpage = new includes_class_page();
		$showpage->show_page(array (
			'total' => $service->count
		));

		$this->show->assign('list', $service->showAllTasks($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-total-list');
	}

	/*
	 * 跳转到我接收的任务列表
	 * TODO:我接受的任务
	 */
	function c_pReceivedTaskPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET);
		//$searchArr['']
		$rows = $service->getPRecievedTasks_d($searchArr);
		//		echo "<pre>";
		//		print_r($rows);
		//分页显示，默认需要在Htm 加{pageDiv}
		$this->pageShowAssign();
		$this->show->assign('list', $service->showPReceivedTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-preceived-list');
	}

	/*
	 * @desription 跳转到我的任务的查看页面
	 * @param tags
	 * @date 2010-9-27 上午11:16:04
	 */
	function c_toMytaskReadPage() {
		$rows = $this->service->getTaskDetail_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		//$this->showDatadicts ( array ('taskType' => 'XMRWLX' ),$rows['taskType'] );
		//$this->showDatadicts ( array ('priority' => 'XMRMYXJ'),$rows['priority']  );
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}
	/*
	 * 跳转到我分配的任务列表
	 * TODO:我分配的任务
	 */
	function c_pAllotTaskPage() {
		$service = $this->service; //设置前台获取的参数信息
		$searchArr = $service->getParam($_GET);
		$rows = $service->getPAllotTasks_d($searchArr);
		//分页显示，默认需要在Htm 加{pageDiv}
		$this->pageShowAssign();

		$this->show->assign('list', $service->showPAllotTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-pallot-list');
	}
	/*
	 * 跳转到我审核的任务列表
	 * TODO:我审核的任务
	 */
	function c_pAuditTaskPage() {
		$service = $this->service;

		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->getPAuditTasks_d($searchArr);
		//分页显示，默认需要在Htm 加{pageDiv}
		//$service->count=count($rows);
		$this->pageShowAssign();
		$this->show->assign('list', $service->showPAuditTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-paudit-list');
	}

	/*
	 * 跳转到一键通项目任务列表
	 */
	function c_onekeyTaskPage() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息

		$rows = $service->page_d();
		$this->pageShowAssign();

		$this->show->assign('list', $service->showOnekeyTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-onekey-list');
	}
	/*
	  * 跳转到进度计划任务管理/查看列表
	  */
	function c_toPlanTaskPage() {
		$service = $this->service;
		$paramArr = $service->getParam($_GET); //设置前台获取的参数信息
		$this->show->assign("planId", $paramArr['pnId']);
		$this->show->assign("planName", $paramArr['pnName']);
		$this->show->assign("projectId", $paramArr['pjId']);
		$this->show->assign("projectCode", $paramArr['pjCode']);
		$this->show->assign("projectName", $paramArr['pjName']);
		if (isset ($_GET['viewTask']))
			$this->show->display($this->objPath . '_' . $this->objName . '-plan-view-list');
		else
			$this->show->display($this->objPath . '_' . $this->objName . '-plan-list');
	}
	/*
	 *跳转到新增一键通页面
	 */
	function c_toAddOneKey() {
		$enterType = $_GET['enterType'];
		$this->assign('enterType', $enterType);
		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		));
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		));
		$this->show->assign('auditId', $_SESSION['USER_ID']);
		$this->show->assign('auditName', $_SESSION['USERNAME']);
		$this->show->display($this->objPath . '_' . $this->objName . '-onekey-add');
	}
	/*
	   * 跳转到进度计划新增任务页面
	   */
	function c_toPlanAddTask() {
		if( isset($_GET['nodeId']) ){
			$nodeDao = new model_rdproject_task_tknode();
			$nodeObj = $nodeDao->get_d($_GET['nodeId']);
			$this->assign("belongNode",$nodeObj['nodeName']);
			$this->assign("belongNodeId",$nodeObj['id']);
			$this->assign("chargeName",$nodeObj['charegeName']);
			$this->assign("chargeId",$nodeObj['charegeId']);
		}else{
			$this->assign("belongNode",'');
			$this->assign("belongNodeId",'');
			$this->assign("chargeName",'');
			$this->assign("chargeId",'');
		}

		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		));
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		));
		//		$this->show->assign ( 'auditId', $_SESSION ['USER_ID'] );
		//	$this->show->assign ( 'auditName', $_SESSION ['USERNAME'] );
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($_GET['projectId'], '', '', false));
		$this->show->assign("planId", $_GET['planId']);
		$this->show->assign("planName", $_GET['planName']);
		$this->show->assign("projectId", $_GET['projectId']);
		$this->show->assign("projectName", $_GET['projectName']);
		$this->show->assign("projectCode", $_GET['projectCode']);
		$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-add');
	}

	/*
	  *跳转到一键通详细信息页面
	  */
	function c_toOneKeyDetail() {
		$rows = $this->service->getTaskDetail_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		), $rows['taskType']);
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		), $rows['priority']);
		$this->show->display($this->objPath . '_' . $this->objName . '-onekey-detail');

	}
	/*
	  *跳转到任务变更信息页面
	  */
	function c_toChangeTask() {
		$rows = $this->service->getEditTaskInfo_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		), $rows['taskType']);
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		), $rows['priority']);

		/*start:里程碑信息*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:里程碑信息*/

		$this->show->display($this->objPath . '_' . $this->objName . '-change');

	}
	/*
	 * 跳转到我的任务编辑页面
	 * @date 2010-9-28 上午10:22:21
	 */
	function c_toEditTask() {
		$rows = $this->service->getEditTaskInfo_d($_GET['id']);
		//附件处理
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		foreach ($rows as $key => $val) {
			if ($key == 'taskType') {
				$this->showDatadicts(array (
					'taskType' => 'XMRWLX'
				), $rows['taskType']);
			}
			elseif ($key == 'priority') {
				$this->showDatadicts(array (
					'priority' => 'XMRMYXJ'
				), $rows['priority']);
			} else
				$this->show->assign($key, $val);
		}
		/*start:里程碑信息*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:里程碑信息*/

		if ($_GET['actType'])
			$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-edit');
		else
			$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}

	/*
	 * @desription 跳转到我的任务的查看页面
	 * @param tags
	 * @date 2010-9-27 上午11:16:04
	 */
	function c_toReadTask() {
		$rows = $this->service->getTaskDetail_d($_GET['id']);
		foreach ($rows as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		), $rows['taskType']);
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		), $rows['priority']);
		$rdworklog = new model_rdproject_worklog_rdworklog();
		$rdworklog->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $rdworklog->worklogInTask($_GET['id']);
		$this->pageShowAssign();
		$this->show->assign('taskId', $_GET['id']);
		$this->show->assign('jsUrl', $_GET['jsUrl']);
		$this->show->assign('list', $rdworklog->showLogInTask($rows));
		//获取提交时间和审批时间
		$tkover = new model_rdproject_task_tkover();
		$tkover->searchArr = array (
			"taskId" => $_GET['id']
		);
		$arr = $tkover->listBySqlId();

		$this->show->assign('subDate', $arr[0]['subDate']);
		$this->show->assign('auditDate', $arr[0]['auditDate']);

		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

	/*
	   * 跳转到进度计划新增节点页面
	   */
	function c_toPlanAddNode() {
		$this->show->display($this->objPath . '_' . $this->objName . '-plan-node-add');
	}

	/*
	   * 跳转到查看项目任务页面
	   */
	function c_toTaskView() {
		$rdtask = $this->service->get_d($_GET['id']);
		foreach ($rdtask as $key => $val) {
			$this->show->assign($key, $val);
		}

		$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-view');

	}
	/*
	 * @desription 跳转到批量编辑页面
	 * @param tags
	 * @date 2010-9-26 上午09:21:52
	 */
	function c_toBatchEditor() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->page_d();
		$this->pageShowAssign();
		$this->showDatadicts(array (
			'taskType' => 'XMRWLX'
		));
		$this->showDatadicts(array (
			'priority' => 'XMRMYXJ'
		));
		$this->show->assign('list', $service->showBatchEditorTaskList($rows));
		//print_r($rows);
		$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-edit');

	}

	/*
	* 新增一键通项目任务
	 * @date 2010-9-20 下午02:06:22
	 */
	function c_addTask() {
		$oneKey = $_POST[$this->objName];
		if ($_GET['publish']) {
			$oneKey['status'] = 'WQD';
			$oneKey['publishId'] = $_SESSION['USER_ID'];
			$oneKey['publishName'] = $_SESSION['USERNAME'];
		}
		$id = $this->service->addTask_d($oneKey, true);

		if ($id) {
			$rdtask['id'] = $id;
			$rdtask['operType_'] = '新增任务';
			$this->behindMethod($rdtask);
			if ($_GET['publish']) {
				$rdtask['operType_'] = '发布任务';
				if ($oneKey['email']['issend'] == 'y') {
					$actuserStr = $oneKey['email']['TO_ID'];
					$this->service->mail_d($oneKey, $actuserStr,$id);
				}
				$this->behindMethod($rdtask);
			}
			if ($oneKey['enterType'] == 'enter')
				msgGo('新增任务成功！');
			else
				msg('新增任务成功！');
		}

	}
	/*
	 * 编辑保存任务信息
	 */
	function c_editTask() {
		$rdtask = $_POST[$this->objName];
		//		echo "<pre>";
		//		print_R( $rdtask );
		$this->beforeMethod($rdtask);
		if ($_GET['publish']) {
			$rdtask['status'] = 'WQD';
			$rdtask['publishId'] = $_SESSION['USER_ID'];
			$rdtask['publishName'] = $_SESSION['USERNAME'];
		}
		$id = $this->service->editTask_d($rdtask, true);
		if ($id) {
			$rdtask['operType_'] = '修改任务';
			$this->behindMethod($rdtask);
			if ($_GET['publish']) {
				$rdtask['operType_'] = '发布任务';
				if ($rdtask['email']['issend'] == 'y') {
					$actuserStr = $rdtask['email']['TO_ID'];
					$this->service->mail_d($rdtask, $actuserStr,$id);
				}
				$this->behindMethod($rdtask);
				msg('任务发布成功！');
			} else {
				msg('修改任务成功！');
			}
		}
	}

	/**
	 * @desription 保存批量编辑
	 * @date 2010-9-26 上午10:40:55
	 */
	function c_editBatchEditor() {
		$id = $this->service->editBatchEditor_d($_POST[$this->objName], true);
		if ($id) {
			msg('批量编辑任务成功！');
		}
	}

	/*
	 * 发布任务
	 */
	function c_publishTask() {
		$rdtask['id'] = $_GET['id'];
		$this->beforeMethod($rdtask);
		$result = $this->service->publishTask_d($_GET['id'], $_SESSION['USER_ID'], $_SESSION['USERNAME']);
		if (isset ($result)) {
			$rdtask['operType_'] = '发布任务';
			$this->operLog = '发布任务成功';
			$this->behindMethod($rdtask);
		}
		echo util_jsonUtil :: iconvGB2UTF($result);
	}
	/*
	  * 变更任务,同时保存变更信息
	  */
	function c_changeTask() {
		$rdtask = $_POST[$this->objName];
		$this->operArr = array (
			"name" => "任务名称",
			"appraiseWorkload" => "估计工作量",
			"remark" => "任务描述",
			"markStoneName" => "标记的里程碑",
			"planBeginDate" => "计划开始时间",
			"planEndDate" => "计划结束时间",
			"chargeName" => "责任人",
			"priority" => "优先级",
			"taskType" => "任务类型",
			"frontTaskName" => "前置任务"
		);
		$this->service->datadictFieldArr = array (
			"priority",
			"taskType",


		);
		$this->beforeMethod($rdtask);
		$id = $this->service->editTask_d($_POST[$this->objName], true);
		if ($id) {
			$this->behindMethod($rdtask, 'change');
			msg('任务变更成功！');

		}
	}

	/**************** BEGIN 工作进展部分********************/

	/*
	 * 填报工作日志-查询工作进展
	 */
	function c_workSchedule() {
		$this->show->display($this->objPath . '_' . $this->objName . '-workschedule');
	}

	/*
	 * 填报工作日志-查询工作进展结果
	 */
	function c_workScheduleResult() {
		$_GET = array_filter($_GET);
		$this->show->assign('tableHead', $this->service->changeHead($_GET));

		$rows = $this->service->getWorkScheduleRs($_GET);
		$this->pageShowAssign();

		$this->show->assign('list', $this->service->showWorkSchedule($rows, $_GET));
		$this->show->display($this->objPath . '_' . $this->objName . '-workScheduleResult');
	}

	/*
	 * 填报工作日志-查询工作进展
	 */
	function c_workScheduleForManager() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->show->display($this->objPath . '_' . $this->objName . '-workscheduleformanager');
	}

	/**
	 * 工作进展查询结果
	 */
	function c_workScheduleResultForManager(){
		$_GET = array_filter($_GET);
		$this->show->assign('tableHead', $this->service->changeHead($_GET));

		$rows = $this->service->getWorkScheduleRs($_GET);
		$this->pageShowAssign();

		$this->show->assign('list', $this->service->showWorkSchedule($rows, $_GET));
		$this->show->display($this->objPath . '_' . $this->objName . '-workscheduleresultformanager');
	}

	/**************** END 工作进展部分*********************/

	/**
	 * @desription ajax判断任务名称是否重复
	 * @date 2010-9-13 下午02:22:04
	 */
	function c_ajaxTaskName() {
		$taskName = isset ($_GET['name']) ? $_GET['name'] : false;
		$searchArr = array (
			"ajaxTaskName" => $taskName
		);
		$isRepeat = $this->service->isRepeat($searchArr, "");
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	/*
	 * ajax删除任务
	 */
	function c_ajaxDeleteByPk() {
		//	 	echo parent::deleteByPk($_GET['id']);
		$result = $this->service->deleteByPk($_GET['id']);
		echo $result;
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_myTaskpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		if ($_POST['user']) {
			$service->searchArr['actUserId'] = $_SESSION['USER_ID'];
		}
		$rows = $service->pageBySqlId('taskJsonForLoad');
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		echo util_jsonUtil :: encode($arr);
	}
	/*
	 *  跳转到查看项目任务列表
	 */
	function c_toProTkViewPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->getTkInfoByPId_d($searchArr);

		//$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign("projectId", $_GET['pjId']);
		$this->show->assign('list', $service->showProjectTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-vtask-list');
	}
	/*
	 * 跳转到项目任务管理列表
	 */
	function c_toProTkManagePage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->getTkInfoByPId_d($searchArr);
		//$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign("projectId", $_GET['pjId']);
		$this->show->assign("jsUrl", $_GET['jsUrl']);
		$this->show->assign('list', $service->showProjectTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-mtask-list');
	}

	/*
	 *跳转到项目任务进展视图列表
	 */
	function c_toProTkProcessPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		if (!isset ($searchArr['beginDate'])) {
			$searchArr['beginDate'] = date('Y-m-d', strtotime(date("Y-m-d")) - 86400 * 10);
			$searchArr['endDate'] = date('Y-m-d', strtotime(date("Y-m-d")) + 86400 * 10);
		}
		$rows = $service->getTkProcessPro_d($searchArr);
		$this->pageShowAssign();

		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign('tbhead', $service->showProTkProcessList($rows, $searchArr['beginDate'], $searchArr['endDate']));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-process-list');

	}
	/*
	 * 跳转到项目任务偏差视图
	 */
	function c_toProTkDeviationPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息

		$rows = $service->getTkDeviationPro_d($searchArr);
		$this->pageShowAssign();

		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign('list', $service->showProTkDeviationList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-deviation-list');

	}
	/*
	 * 跳转到项目任务框架页面
	 */
	function c_toProTkFrame() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign("pjId", $pjId);
		$this->show->assign("jsUrl", $_GET['jsUrl']);
		$this->show->display($this->objPath . '_' . $this->objName . '-project-frame');
	}

	/*
	 * 跳转到个人日历页面
	 */
	function c_toRdTaskCalendar() {
		$this->show->display($this->objPath . '_' . $this->objName . '-calendar');
	}
	/*
	 * 获取当前月份的个人任务数据
	 */
	function c_getCalendarTkData() {
		//	$goadDate=date();
		$rows = $this->service->getPerMonthTk_d("");
		//		$arr = array (array ("EventID" => 1, "Date" => "2010-10-12", "Title" => "10:00 pm - 项目1", "URL" => "#", "Description" => "需要开始做这个任务了", "CssClass" => "Birthday" ), array ("EventID" => 2, "StartDateTime" => "2010-10-12", "Title" => "10:00 pm - 项目1预计完成", "URL" => "?model=", "Description" => "这个任务在这里提交完成", "CssClass" => "Birthday" ), array ("EventID" => 3, "Date" => "2010-10-28", "Title" => "9:30 pm - 项目任务2", "URL" => "#", "Description" => "你的第二个任务-测试 预计结束", "CssClass" => "Meeting" ) );
		//		$testArr = array ();
		//	echo "<pre>";
		//	print_r($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/*
	 * 判断当前登录人是否是所选任务的负责人
	 */
	function c_isChargeUser() {
		$service = $this->service;
		echo $service->isChargeUser_d($_GET['id']);
	}
	/*
	 *判断任务是否存在责任人
	 */
	function c_exsitChargeUser() {
		$service = $this->service;
		echo $service->isChargeUser_d($_GET['id']);
	}

	/**
	 * 甘特图
	 */
	function c_showGantt() {
		$this->service->showGantt($_GET['planId']);
	}

	/*Start:-------------------------任务分组统计处理------------------------------------*/
	/*
	 * 1.跳转到项目任务按状态统计分组信息页面
	 */
	function c_toProTkGStatusList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByStatus_d($arr);
		} else {
			$rows = $service->getGroupTkByStatus_d($pjId);
		}

		$this->show->assign('list', $service->showProTkGStatusList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gstatus-list');
	}
	/*
	  *1.跳转到项目任务按状态统计分组信息图表
	  */
	function c_toProTkGStatusChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByStatus_d($arr);
		} else {
			$rows = $service->getGroupTkByStatus_d($pjId);
		} //任务统计信息
		$chartDao = new model_common_fusionCharts();
		//	print_r($rows);
		$i = 0;
		$rsrows = array ();
		foreach ($rows as $val) {
			$rsrows[$i][1] = $val['dataName'];
			$rsrows[$i][2] = $val['dataNum'];
			$i++;
		}

		//print_r($rsrows);
		$chartConf = array (
			'exportFileName' => "任务状态图",
			'caption' => "任务状态图",
			'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
			'exportAction'=>'download'//'exportAction' => "save"
		); //如果是服务器运行，支持浏览器下载or服务器保存

		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 2.跳转到项目任务按任务类型统计分组信息页面
	 */
	function c_toProTkGTypeList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByType_d($arr);
		} else {
			$rows = $service->getGroupTkByType_d($pjId);
		};

		$this->show->assign('list', $service->showProTkGTypeList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gtype-list');
	}
	/*
	  *2.跳转到项目任务按状态统计分组信息图表
	  */
	function c_toProTkGTypeChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByType_d($arr);
		} else {
			$rows = $service->getGroupTkByType_d($pjId);
		}; //任务统计信息
		$chartDao = new model_common_fusionCharts();
		//		 	echo "<pre>";
		//		 	print_r($rows);
		$i = 0;
		$rsrows = array ();
		foreach ($rows as $val) {
			$rsrows[$i][1] = $val['dataName'];
			$rsrows[$i][2] = $val['dataNum'];
			$i++;
		}
		//	print_r($rsrows);
		$chartConf = array (
			'exportFileName' => "任务类型透视图",
			'caption' => "任务类型透视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "个"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 3.跳转到项目任务按偏差率统计分组信息页面
	 */
	function c_toProTkGVariaList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByVaria_d($arr);
		} else {
			$rows = $service->getGroupTkByVaria_d($pjId);
		};
		$this->show->assign('list', $service->showProTkGVariaList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gvarial-list');
	}
	/*
	  *3.跳转到项目任务按偏差率统计分组信息图表
	  */
	function c_toProTkGVariaChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByVaria_d($arr);
		} else {
			$rows = $service->getGroupTkByVaria_d($pjId);
		}; //任务统计信息
		$chartDao = new model_common_fusionCharts();
		//	 	echo "<pre>";
		//	 	print_r($rows);
		$i = 0;
		$rsrows = array ();
		foreach ($rows as $val) {
			$rsrows[$i][1] = $val['dataName'];
			$rsrows[$i][2] = $val['dataNum'];
			$i++;
		}
		$chartConf = array (
			'exportFileName' => "任务偏差透视图",
			'caption' => "任务偏差透视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "个"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 4.跳转到项目任务按完成质量统计分组信息页面
	 */
	function c_toProTkGQualityList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByQuality_d($arr);
		} else {
			$rows = $service->getGroupTkByQuality_d($pjId);
		};
		$this->show->assign('list', $service->showProTkGQualityList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gquality-list');
	}
	/*
	  *4.跳转到项目任务按完成质量统计分组信息图表
	  */
	function c_toProTkGQualityChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByQuality_d($arr);
		} else {
			$rows = $service->getGroupTkByQuality_d($pjId);
		}; //任务统计信息

		$chartDao = new model_common_fusionCharts();
		$i = 0;
		$rsrows = array ();
		foreach ($rows as $val) {
			$rsrows[$i][1] = $val['dataName'];
			$rsrows[$i][2] = $val['dataNum'];
			$i++;
		}
		$chartConf = array (
			'exportFileName' => "任务完成质量透视图",
			'caption' => "任务完成质量透视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "个"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 5.跳转到项目任务按责任主体统计分组信息页面
	 */
	function c_toProTkGChargeList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByQuality_d($arr);
		} else {
			$rows = $service->getGroupTkByQuality_d($pjId);
		};
		$this->show->assign('list', $service->showProTkGChargeList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gcharge-list');
	}
	/*
	  *5.跳转到项目任务按责任主体统计分组信息图表
	  */
	function c_toProTkGChargeChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByQuality_d($arr);
		} else {
			$rows = $service->getGroupTkByQuality_d($pjId);
		}; //任务统计信息
		$i = 0;
		$rsrows = array ();
		if ($rows) {
			foreach ($rows as $val) {
				$rsrows[$i][1] = $val['dataName'];
				$rsrows[$i][2] = $val['dataNum'];
				$i++;
			}
		}
		$chartDao = new model_common_fusionCharts();
		$chartConf = array (
			'exportFileName' => "任务责任主体视图",
			'caption' => "任务责任主体视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "个"
		);
		echo $chartDao->showCharts($rsrows, "Column2D.swf", $chartConf);
	}
	/*
	 * 6.跳转到项目任务按成员任务进展统计分组信息页面
	 */
	function c_toProTkGActFinList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByActFin_d($arr);
		} else {
			$rows = $service->getGroupTkByActFin_d($pjId);
		};

		$this->show->assign('list', $service->showProTkGActFinList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gactfin-list');
	}
	/*
	  *6.跳转到项目任务按成员任务进展分组信息图表
	  */
	function c_toProTkGActFinChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByActFin_d($arr);
		} else {
			$rows = $service->getGroupTkByActFin_d($pjId);
		}; //任务统计信息
		$i = 0;
		$rsrows = array ();
		if ($rows) {
			foreach ($rows as $val) {
				$rsrows[$i][1] = $val['dataName'];
				$rsrows[$i][2] = $val['dataPercent'];
				$i++;
			}
		}
		$chartDao = new model_common_fusionCharts();
		$chartConf = array (
			'exportFileName' => "成员任务进展透视图",
			'caption' => "成员任务进展透视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "%"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 7.跳转到项目任务按成员任务偏差分组信息页面
	 */
	function c_toProTkGActVarList() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByActVar_d($arr);
		} else {
			$rows = $service->getGroupTkByActVar_d($pjId);
		};
		$this->show->assign('list', $service->showProTkGActVarList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-gactvar-list');
	}
	/*
	  *7.跳转到项目任务按任务偏差统计分组信息图表
	  */
	function c_toProTkGActVarChart() {
		$service = $this->service;
		$gpId = $_GET['gpId'];
		$pjId = $_GET['pjId'];
		if (!empty ($gpId)) {
			$rdNum = $service->getGroup_d($gpId);
			$arr = array ();
			foreach ($rdNum as $key => $val) {
				$arr[$key] = $val['id'];

			}
			$arr = implode(",", $arr);
			if (empty ($arr)) {
				$rows = $arr;
			} else
				$rows = $service->getGroupTkByActVar_d($arr);
		} else {
			$rows = $service->getGroupTkByActVar_d($pjId);
		}; //任务统计信息
		$i = 0;
		$rsrows = array ();
		if ($rows) {
			foreach ($rows as $val) {
				$rsrows[$i][1] = $val['dataName'];
				$rsrows[$i][2] = $val['dataPercent'];
				$i++;
			}
		}
		$chartDao = new model_common_fusionCharts();
		$chartConf = array (
			'exportFileName' => "成员任务偏差透视图",
			'caption' => "成员任务偏差透视图",
				'exportAtClient' => 0, //0: 服务器端运行， 1： 客户端运行
		'exportAction' => "download", //如果是服务器运行，支持浏览器下载or服务器保存
	'$numberSuffix' => "%"
		);
		echo $chartDao->showCharts($rsrows, "Column2D.swf", $chartConf);
	}

	/*End -------------------------任务分组统计处理------------------------------------*/

	/*
	 * 导出数据到EXCEL
	 */
	function c_exportExcel() {
		$selectField = substr($_GET['selectField'], 0, -1);
		$dataArr = $this->service->listBySql("select " . $selectField . "  from oa_rd_task ");
		$thArr = explode(",", substr($_GET['tdName'], 0, -1));
		//		return util_excelUtil::export2ExcelUtil ( $thArr, $dataArr );
		//		return util_excelUtil::exportPage2Excel($thArr,$dataArr);
		$imageName = $_GET['imageName'];
		return util_excelUtil :: exporTemplate($thArr, $dataArr, $imageName);
	}

	/**
	 * 研发任务导出过滤条件
	 * @param author zengzx
	 * 2011年10月21日 13:51:54
	 * TODO
	 */
	 function c_setSearchArr(){
		$service = $this->service;
		$searchArr = $service->getParam($_GET);
		if (isset ( $searchArr ['listType'] )) {
			unset ( $searchArr ['listType'] );
		}
		if (isset ( $searchArr ['selectField'] )) {
			unset ( $searchArr ['selectField'] );
		}
		if (isset ( $searchArr ['tdName'] )) {
			unset ( $searchArr ['tdName'] );
		}
		$listType = isset ( $_GET ['listType'] ) ? $_GET ['listType'] : null;
	 	$selectField = substr($_GET['selectField'], 0, -1);
		$thArr = explode(",", substr($selectField, 0));
		//由于auditUser不在本类表里。先unset掉auditUser.
		foreach ($thArr as $key => $val) {
			if ($val == 'auditUser') {
				unset ($thArr[$key]);
			}
			if ($val == 'ExaDT') {
				unset ($thArr[$key]);
			}
			if ($val == 'finishGrade') {
				unset ($thArr[$key]);
			}
		}
		foreach ( $thArr as $key => $val ){
			$thArr[$key]='c.'.$val;
		}
		$selectField = implode(",", $thArr);
		if( !$listType ){
			unset($service->searchArr['projectId']);
			$condition = " where projectId = " . $_GET['projectId'];
			$dataArr = $service->listBySql("select c.id," . $selectField . " from oa_rd_task c" . $condition);
		}else{
			if( $listType == 'pReceivedTaskPage' ){
				$rows = $service->getRrecievedRows_d($searchArr,$selectField);
			}if( $listType == 'pAllotTaskPage' ){
				$rows = $service->getAllotRows_d($searchArr,$selectField);
			}if( $listType == 'pAuditTaskPage' ){
				$rows = $service->getAuditRows_d($searchArr,$selectField);
			}
			foreach ( $rows as $key => $val ){
				$tempIdArr[$key] = $val['id'];
			}
			$tempIdStr = implode(',',$tempIdArr);
			if($tempIdStr){
				$dataArr = $service->listBySql("select c.id," . $selectField . " from oa_rd_task c where c.id in($tempIdStr) ");
			}else{
				echo "<script>alert('无相应数据！导出失败！');window.close();</script>";
				exit(0);
			}
		}
		$this->c_exportTaskExcel($dataArr,$listType);
	}

	/*
	 * 导出项目任务数据到EXCEL
	 */
	function c_exportTaskExcel($dataArr,$listType) {
		//判断是否有传入auditUser flag = 0 表示没有传入，flag = 1 表示有传入
		$flag = 0;
		$tkauditUserDao = new model_rdproject_task_tkaudituser();
		$tkoverDao = new model_rdproject_task_tkover();
		$selectField = substr($_GET['selectField'], 0, -1);
		$thArr = explode(",", substr($selectField, 0));
		//由于auditUser不在本类表里。先unset掉auditUser.
		foreach ($thArr as $key => $val) {
			if ($val == 'auditUser') {
				unset ($thArr[$key]);
				$auditUserflag = 1;
				$auditUsersite = $key;
			}
			if ($val == 'ExaDT') {
				unset ($thArr[$key]);
				$ExaDTflag = 1;
			}
			if ($val == 'finishGrade') {
				unset ($thArr[$key]);
				$finishGradeflag = 1;
			}
		}
		$audtiUser = '';
		foreach ($dataArr as $rdNum => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'id') {
					if ($ExaDTflag == 1) {
						$ExaDTRows = $tkoverDao->findBy('taskId', $val);
						$dataArr[$rdNum]['ExaDT'] = $ExaDTRows['auditDate'];
						$finishGrade = $dict = parent :: getDataNameByCode($ExaDTRows['finishGrade']);
						$dataArr[$rdNum]['finishGrade'] = $finishGrade;
					}
					if ($auditUserflag == 1) {
						//判断是否有传入auditUser，如果有，则取出auditUser信息。
						$auditArr = $tkauditUserDao->getNameByTaskId($val);
						foreach ($auditArr as $num => $row) {
							foreach ($row as $key => $val) {
								$audtiUser = $audtiUser . $val . ',';
							}
						}
						$dataArr[$rdNum]['audtiUser'] = substr($audtiUser, 0, -1);
						$audtiUser = '';
					}
				}
				elseif ($key == 'status' || $key == 'priority' || $key == 'taskType') {
					//将任务状态、优先级、任务类型由编码转化为对应的中文。
					$dict = parent :: getDataNameByCode($val);
					$dataArr[$rdNum][$key] = $dict;
				}
			}
		}
		foreach ($dataArr as $rdNum => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'id') {
					unset ($dataArr[$rdNum][$key]);
				}
			}
		}

		//将auditUser插入到与excel里顺序一致的位置。
		if ($auditUserflag == 1) {
			foreach ($dataArr as $rdNum => $row) {
				//将auditUser从数组尾部弹出
				$auditTempArr['auditUser'] = array_pop($dataArr[$rdNum]);
				//将auditUser插入到指定位置
				array_splice($dataArr[$rdNum], $auditUsersite, 0, $auditTempArr);
			}
		}
		$thArr = explode(",", substr($_GET['tdName'], 0, -1));
		//调用导出excel方法。
		if( !$listType ){
			return model_rdproject_task_rdExcelUtil :: export2ExcelUtil($thArr, $dataArr);
		}else{
			return model_rdproject_task_rdExcelUtil :: myTaskExcelUtil($thArr, $dataArr);
		}
	}

	/*
	 *跳转到excel上传页面 -- Demo
	 */
	function c_toExcelDemo() {

		$this->show->display($this->objPath . '_' . 'excelDemo');
	}

	/**
	 * 查看计划任务分配工作量情况
	 */
	function c_toViewPlanLoad() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		if (!isset ($searchArr['startDate'])) {
			$searchArr['startDate'] = date('Y-m-d', strtotime(date("Y-m-d")) - 86400 * 10);
			$searchArr['endDate'] = date('Y-m-d', strtotime(date("Y-m-d")) + 86400 * 10);
		}
		$names = $service->getNameAll($searchArr);
		$tasks = $service->getPlanAll($searchArr);
		$this->pageShowAssign();
		$rows = $service->edit($names, $tasks);

		$this->show->assign('tbhead', $service->showPlanAllView($rows, $searchArr['startDate'], $searchArr['endDate']));
		$this->show->display($this->objPath . '_' . $this->objName . '-plan-allview');

	}

	/*
	 *  跳转到excel上传页面
	 * create By ZZX 20110416
	 */
	function c_toImportExcel() {
		if (!$this->service->this_limit['项目任务导入']) {
			echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
			exit ();
		}
		$this->show->display($this->objPath . '_' . 'import-excel');
	}

	/*
	 * 上传EXCEL
	 */
	function c_upExcel() {
		$objNameArr = array (
			0 => 'projectCode',
			1 => 'projectName',
			2 => 'planName',
			3 => 'name',
			4 => 'remark',
			5 => 'chargeName',
			6 => 'userName',
			7 => 'auditName',
			8 => 'priority',
			9 => 'taskType',
			10 => 'planBeginDate',
			11 => 'planEndDate',
			12 => 'appraiseWorkload',


		); //字段数组

		$this->c_addExecelData($objNameArr);
		//		$this->display( 'upexcel' );
	}

	/**
	 * 上传EXCEl并导入其数据
	 *将数据做处理后传入添加方法
	 */
	function c_addExecelData($objNameArr, $planArr) {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];
		$projectDao = new model_rdproject_project_rdproject();
		$planDao = new model_rdproject_plan_rdplan();
		$datadictDao = new model_system_datadict_datadict();
		$menberDao = new model_rdproject_team_rdmember();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_rdproject_task_rdExcelUtil :: upReadExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			//判断是否传入的是有效数据
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					//将里程碑计划名称和任务名称格式化，删除多余的空格。如果任务名为空，则该条数据插入无效。
					$excelData[$rNum][2] = str_replace(' ', '', $row[2]);
					$temp = $row[3];
					if (str_replace(' ', '', $temp) == '')
						unset ($excelData[$rNum]);
				}
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						//将值赋给对应的字段
						$objectArr[$rNum][$fieldName] = $row[$index];
						if ($fieldName == 'projectName') {
							//传入projectName，这里强制将所有任务的projectName视为第一行的projectName。
							if ($excelData[$rNum][1] != '') {
								$objectArr[$rNum][$fieldName] = $excelData[$rNum][1];
							} else {
								$objectArr[$rNum][$fieldName] = $objectArr[$rNum -1][$fieldName];
							}
						} else
							if ($fieldName == 'projectCode') {
								//当projectCode为空时，将上一个数组的projectCode赋给此数组的projectCode。
								if ($excelData[$rNum][0] != '') {
									$objectArr[$rNum][$fieldName] = $excelData[$rNum][0];
								} else {
									$objectArr[$rNum][$fieldName] = $objectArr[$rNum -1][$fieldName];
								}
								//根据projectCode查询projectId。
								$projectId = $projectDao->getProjectIdByProjectCode_d($objectArr[$rNum][$fieldName]);
								if ($projectId == '') {
									msg("导入失败！系统中找不到此项目，请确认是否有此项目。由于导入过程中断，上传的数据将被撤销！");
									return false;
								}
								$objectArr[$rNum]['projectId'] = $projectId;
							}
						elseif ($fieldName == 'planName') {
							$objectArr[$rNum]['planName'] = $row[$index];
							//根据planName找planId。
							$plan = $planDao->getPlanIdByName($objectArr[$rNum]['projectId'], $row[$index]);
							$objectArr[$rNum]['planId'] = $plan['id'];
							//							$objectArr [$rNum] ['priority'] = $planArr['dataCode'];
						}
						elseif ($fieldName == 'priority') {
							//根据优先级的名称和parentCode找dataCode。
							$ptySearchArr = array (
								'parentCode' => 'XMRMYXJ',
								'dataName' => $row[$index]
							);
							$priorityArr = $datadictDao->find($ptySearchArr, null, 'dataCode');
							$objectArr[$rNum]['priority'] = $priorityArr['dataCode'];
						}
						elseif ($fieldName == 'taskType') {
							//根据任务类型的名称和parentCode找dataCode。
							$tktSearchArr = array (
								'parentCode' => 'XMRWLX',
								'dataName' => $row[$index]
							);
							$taskTypeArr = $datadictDao->find($tktSearchArr, null, 'dataCode');
							$objectArr[$rNum]['taskType'] = $taskTypeArr['dataCode'];
						}
						elseif ($fieldName == 'chargeName') {
							//根据projectId和chargeName到项目成员列表找chargeId。
							$chargeId = $menberDao->getMemberIdByName($objectArr[$rNum]['projectId'], $row[$index]);
							$objectArr[$rNum]['chargeId'] = $chargeId['memberId'];
						} else {
							$objectArr[$rNum]['belongNodeId'] = -1;
						}
					}
				}
				$auditObjs = array ();
				foreach ($objectArr as $index => $row) {
					$auditNameStr = str_replace('，', ',', $row['auditName']);
					$auditNameArr = explode(',', $auditNameStr);
					foreach ($auditNameArr as $key => $val) {
						if ($val != '') {
							$auditId = $menberDao->getMemberIdByName($row['projectId'], $auditNameArr[$key]);
							$auditObjs[$key]['auditUser'] = $auditNameArr[$key];
							$auditObjs[$key]['auditId'] = $auditId['memberId'];
						}
					}
					$objectArr[$index]['auditName'] = $auditObjs;
					$userNameStr = str_replace('，', ',', $row['userName']);
					$userNameArr = explode(',', $userNameStr);
					foreach ($userNameArr as $key => $val) {
						if ($val != '') {
							$actUserId = $menberDao->getMemberIdByName($row['projectId'], $userNameArr[$key]);
							$actUserObjs[$key]['userName'] = $userNameArr[$key];
							$actUserObjs[$key]['actUserId'] = $actUserId['memberId'];
						}
					}
					$objectArr[$index]['userName'] = $actUserObjs;
					//处理导入的时间，将天数转化为日期。
					$planBeginDate = mktime(0, 0, 0, 1, $objectArr[$index]['planBeginDate'] - 1, 1900);
					$objectArr[$index]['planBeginDate'] = date("Y-m-d", $planBeginDate);
					$planEndDate = mktime(0, 0, 0, 1, $objectArr[$index]['planEndDate'] - 1, 1900);
					$objectArr[$index]['planEndDate'] = date("Y-m-d", $planEndDate);
				}
				//循环调用插入方法
				$returnDate = $this->service->addBatchByExcel_d($objectArr);
				if ($returnDate == '')
					msg("导入成功!");
				//					echo "导入成功!";
				else {
					msg($returnDate);
					//					echo "$returnDate";
				}
			} else {
				msg("文件不存在可识别数据!");
			}
		} else {
			msg("上传文件类型不是EXCEL!");
		}

	}

	/*******************************根据项目导入任务--start**********************************************/

	/*
	 *  跳转到excel上传页面
	 * create By ZZX 2011年7月6日 13:56:25
	 */
	function c_toImportExcelbypro() {
		if (!empty ($_GET['pjId'])) {
			//权限判断
			$this->isCurUserPerm($_GET['pjId'], 'project_import_task');
		}
		$rdprojectDao = new model_rdproject_project_rdproject();
		$rdprojectObj = $rdprojectDao->get_d($_GET['pjId']);
		$this->assign('projectCode', $rdprojectObj['projectCode']);
		$this->assign('projectName', $rdprojectObj['projectName']);
		$this->show->display($this->objPath . '_' . 'import-excelbypro');
	}

	/*
	 * 上传EXCEL
	 */
	function c_upExcelbypro() {
		$objNameArr = array (
			0 => 'planName',
			1 => 'name',
			2 => 'remark',
			3 => 'chargeName',
			4 => 'userName',
			5 => 'auditName',
			6 => 'priority',
			7 => 'taskType',
			8 => 'planBeginDate',
			9 => 'planEndDate',
			10 => 'appraiseWorkload'
		); //字段数组

		$projectCode = $_GET['projectCode'];
		$projectName = $_GET['projectName'];
		$this->c_addExecelDatabypro($objNameArr, $projectCode, $projectName);
		//		$this->display( 'upexcel' );
	}

	/**
	 * 上传EXCEl并导入其数据
	 *将数据做处理后传入添加方法
	 */
	function c_addExecelDatabypro($objNameArr, $projectCode, $projectName) {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];
		$projectDao = new model_rdproject_project_rdproject();
		$planDao = new model_rdproject_plan_rdplan();
		$datadictDao = new model_system_datadict_datadict();
		$menberDao = new model_rdproject_team_rdmember();
		$projectId = 0;
		$projectId = $projectDao->getProjectIdByProjectCode_d($projectCode);
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_rdproject_task_rdExcelUtil :: upReadExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			//判断是否传入的是有效数据
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					//将里程碑计划名称和任务名称格式化，删除多余的空格。如果任务名为空，则该条数据插入无效。
					$excelData[$rNum][0] = str_replace(' ', '', $row[0]);
					$temp = $row[1];
					if (str_replace(' ', '', $temp) == '')
						unset ($excelData[$rNum]);
				}
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum]['projectId'] = $projectId;
						$objectArr[$rNum]['projectCode'] = $projectCode;
						$objectArr[$rNum]['projectName'] = $projectName;
						//将值赋给对应的字段
						$objectArr[$rNum][$fieldName] = $row[$index];
						if ($fieldName == 'planName') {
							$objectArr[$rNum]['planName'] = $row[$index];
							//根据planName找planId。
							$plan = $planDao->getPlanIdByName($projectId, $row[$index]);
							$objectArr[$rNum]['planId'] = $plan['id'];
							//							$objectArr [$rNum] ['priority'] = $planArr['dataCode'];
						}
						elseif ($fieldName == 'priority') {
							//根据优先级的名称和parentCode找dataCode。
							$ptySearchArr = array (
								'parentCode' => 'XMRMYXJ',
								'dataName' => $row[$index]
							);
							$priorityArr = $datadictDao->find($ptySearchArr, null, 'dataCode');
							$objectArr[$rNum]['priority'] = $priorityArr['dataCode'];
						}
						elseif ($fieldName == 'taskType') {
							//根据任务类型的名称和parentCode找dataCode。
							$tktSearchArr = array (
								'parentCode' => 'XMRWLX',
								'dataName' => $row[$index]
							);
							$taskTypeArr = $datadictDao->find($tktSearchArr, null, 'dataCode');
							$objectArr[$rNum]['taskType'] = $taskTypeArr['dataCode'];
						}
						elseif ($fieldName == 'chargeName') {
							//根据projectId和chargeName到项目成员列表找chargeId。
							$chargeId = $menberDao->getMemberIdByName($projectId, $row[$index]);
							$objectArr[$rNum]['chargeId'] = $chargeId['memberId'];
						} else {
							$objectArr[$rNum]['belongNodeId'] = -1;
						}
					}
				}
				$auditObjs = array ();
				foreach ($objectArr as $index => $row) {
					//拼装审核人
					$auditNameStr = str_replace('，', ',', $row['auditName']);
					$auditNameArr = explode(',', $auditNameStr);
					foreach ($auditNameArr as $key => $val) {
						if ($val != '') {
							$auditId = $menberDao->getMemberIdByName($projectId, $auditNameArr[$key]);
							$auditObjs[$key]['auditUser'] = $auditNameArr[$key];
							$auditObjs[$key]['auditId'] = $auditId['memberId'];
						}
					}
					$objectArr[$index]['auditName'] = $auditObjs;
					//拼装参与人
					$userNameStr = str_replace('，', ',', $row['userName']);
					$userNameArr = explode(',', $userNameStr);
					foreach ($userNameArr as $key => $val) {
						if ($val != '') {
							$actUserId = $menberDao->getMemberIdByName($projectId, $userNameArr[$key]);
							$actUserObjs[$key]['userName'] = $userNameArr[$key];
							$actUserObjs[$key]['actUserId'] = $actUserId['memberId'];
						}
					}
					$objectArr[$index]['userName'] = $actUserObjs;
					//处理导入的时间，将天数转化为日期。
					$planBeginDate = mktime(0, 0, 0, 1, $objectArr[$index]['planBeginDate'] - 1, 1900);
					$objectArr[$index]['planBeginDate'] = date("Y-m-d", $planBeginDate);
					$planEndDate = mktime(0, 0, 0, 1, $objectArr[$index]['planEndDate'] - 1, 1900);
					$objectArr[$index]['planEndDate'] = date("Y-m-d", $planEndDate);
				}
				//循环调用插入方法
				$returnDate = $this->service->addBatchByExcel_d($objectArr);
				if ($returnDate == '')
					msg("导入成功!");
//				echo '成功';
				else {
					msg($returnDate);
					//echo '失败';
				}
			} else {
				msg("文件不存在可识别数据!");
			}
		} else {
			msg("上传文件类型不是EXCEL!");
		}

	}

	/********************************项目研发任务导入--end*************************************************/

	/*
	 * 跳转到项目任务管理列表
	 * 2011年6月29日 09:14:34
	 * 用于对旧研发数据的修改
	 */
	function c_toProTkManagePageOld() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //设置前台获取的参数信息
		$rows = $service->getTkInfoByPId_d($searchArr);

		//$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign("projectId", $_GET['pjId']);
		$this->show->assign("jsUrl", $_GET['jsUrl']);
		$this->show->assign('list', $service->showProjectTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-oldmtask-list');
	}

	/*
	 * 跳转到我的任务编辑页面
	 * @date 2011年6月29日 09:35:01
	 * 用于编辑旧研发任务
	 */
	function c_toEditTaskOld() {
		$rows = $this->service->getEditTaskInfo_d($_GET['id']);
		//附件处理
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		foreach ($rows as $key => $val) {
			if ($key == 'taskType') {
				$this->showDatadicts(array (
					'taskType' => 'XMRWLX'
				), $rows['taskType']);
			}
			elseif ($key == 'priority') {
				$this->showDatadicts(array (
					'priority' => 'XMRMYXJ'
				), $rows['priority']);
			} else
				$this->show->assign($key, $val);
		}
		/*start:里程碑信息*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:里程碑信息*/

		if ($_GET['actType'])
			$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-editold');
		else
			$this->show->display($this->objPath . '_' . $this->objName . '-editold');
	}
	function c_timetask() {
		echo 111111;
		$this->service->sendMailForExpiredTask();
	}

	/*
	 * 跳转到项目日历页面
	 * @linzx
	 */
	function c_toProjectTaskCalendar() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->assign("jsUrl", $_GET['jsUrl']);
		$this->assign("pjId", $pjId);
		$this->show->display($this->objPath . '_' . $this->objName . '-project-calendar');
	}

	/*
	 *获取当前月份的项目任务数据
	 * @linzx
	 */

	function c_getProjectCalendarTkData() {
		$porId = $_GET["projectId"];
        $rows = $this->service->getPorjectMonthTk_d($porId);
		echo util_jsonUtil :: encode($rows);
	}
}
?>
