<?php


/**
 * @description: ��Ŀ����action
 * @date 2010-9-14 ����02:45:13
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_task_rdtask extends controller_base_action {

	/**
	 * @desription ���캯��
	 * @date 2010-9-14 ����02:45:48
	 */
	function __construct() {
		$this->objName = "rdtask";
		$this->objPath = "rdproject_task";
		$this->operArr = array (
			"name" => "��������",
			"appraiseWorkload" => "���ƹ�����",
			"remark" => "��������",
			"markStoneName" => "��ǵ���̱�",
			"planBeginDate" => "�ƻ���ʼʱ��",
			"planEndDate" => "�ƻ�����ʱ��"
		); //ͳһע�����ֶΣ������ͬ�����в�ͬ�ļ���ֶΣ��ڸ��Է���������Ĵ�����
		parent :: __construct();
	}

	/*
	 * ��ת����Ŀ���������Ϣ�б�
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d();
		//��ҳ
		$showpage = new includes_class_page();
		$showpage->show_page(array (
			'total' => $service->count
		));

		$this->show->assign('list', $service->showAllTasks($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-total-list');
	}

	/*
	 * ��ת���ҽ��յ������б�
	 * TODO:�ҽ��ܵ�����
	 */
	function c_pReceivedTaskPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET);
		//$searchArr['']
		$rows = $service->getPRecievedTasks_d($searchArr);
		//		echo "<pre>";
		//		print_r($rows);
		//��ҳ��ʾ��Ĭ����Ҫ��Htm ��{pageDiv}
		$this->pageShowAssign();
		$this->show->assign('list', $service->showPReceivedTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-preceived-list');
	}

	/*
	 * @desription ��ת���ҵ�����Ĳ鿴ҳ��
	 * @param tags
	 * @date 2010-9-27 ����11:16:04
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
	 * ��ת���ҷ���������б�
	 * TODO:�ҷ��������
	 */
	function c_pAllotTaskPage() {
		$service = $this->service; //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchArr = $service->getParam($_GET);
		$rows = $service->getPAllotTasks_d($searchArr);
		//��ҳ��ʾ��Ĭ����Ҫ��Htm ��{pageDiv}
		$this->pageShowAssign();

		$this->show->assign('list', $service->showPAllotTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-pallot-list');
	}
	/*
	 * ��ת������˵������б�
	 * TODO:����˵�����
	 */
	function c_pAuditTaskPage() {
		$service = $this->service;

		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getPAuditTasks_d($searchArr);
		//��ҳ��ʾ��Ĭ����Ҫ��Htm ��{pageDiv}
		//$service->count=count($rows);
		$this->pageShowAssign();
		$this->show->assign('list', $service->showPAuditTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-paudit-list');
	}

	/*
	 * ��ת��һ��ͨ��Ŀ�����б�
	 */
	function c_onekeyTaskPage() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->page_d();
		$this->pageShowAssign();

		$this->show->assign('list', $service->showOnekeyTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-onekey-list');
	}
	/*
	  * ��ת�����ȼƻ��������/�鿴�б�
	  */
	function c_toPlanTaskPage() {
		$service = $this->service;
		$paramArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 *��ת������һ��ͨҳ��
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
	   * ��ת�����ȼƻ���������ҳ��
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
	  *��ת��һ��ͨ��ϸ��Ϣҳ��
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
	  *��ת����������Ϣҳ��
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

		/*start:��̱���Ϣ*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:��̱���Ϣ*/

		$this->show->display($this->objPath . '_' . $this->objName . '-change');

	}
	/*
	 * ��ת���ҵ�����༭ҳ��
	 * @date 2010-9-28 ����10:22:21
	 */
	function c_toEditTask() {
		$rows = $this->service->getEditTaskInfo_d($_GET['id']);
		//��������
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
		/*start:��̱���Ϣ*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:��̱���Ϣ*/

		if ($_GET['actType'])
			$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-edit');
		else
			$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}

	/*
	 * @desription ��ת���ҵ�����Ĳ鿴ҳ��
	 * @param tags
	 * @date 2010-9-27 ����11:16:04
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
		$rdworklog->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $rdworklog->worklogInTask($_GET['id']);
		$this->pageShowAssign();
		$this->show->assign('taskId', $_GET['id']);
		$this->show->assign('jsUrl', $_GET['jsUrl']);
		$this->show->assign('list', $rdworklog->showLogInTask($rows));
		//��ȡ�ύʱ�������ʱ��
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
	   * ��ת�����ȼƻ������ڵ�ҳ��
	   */
	function c_toPlanAddNode() {
		$this->show->display($this->objPath . '_' . $this->objName . '-plan-node-add');
	}

	/*
	   * ��ת���鿴��Ŀ����ҳ��
	   */
	function c_toTaskView() {
		$rdtask = $this->service->get_d($_GET['id']);
		foreach ($rdtask as $key => $val) {
			$this->show->assign($key, $val);
		}

		$this->show->display($this->objPath . '_' . $this->objName . '-plan-task-view');

	}
	/*
	 * @desription ��ת�������༭ҳ��
	 * @param tags
	 * @date 2010-9-26 ����09:21:52
	 */
	function c_toBatchEditor() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	* ����һ��ͨ��Ŀ����
	 * @date 2010-9-20 ����02:06:22
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
			$rdtask['operType_'] = '��������';
			$this->behindMethod($rdtask);
			if ($_GET['publish']) {
				$rdtask['operType_'] = '��������';
				if ($oneKey['email']['issend'] == 'y') {
					$actuserStr = $oneKey['email']['TO_ID'];
					$this->service->mail_d($oneKey, $actuserStr,$id);
				}
				$this->behindMethod($rdtask);
			}
			if ($oneKey['enterType'] == 'enter')
				msgGo('��������ɹ���');
			else
				msg('��������ɹ���');
		}

	}
	/*
	 * �༭����������Ϣ
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
			$rdtask['operType_'] = '�޸�����';
			$this->behindMethod($rdtask);
			if ($_GET['publish']) {
				$rdtask['operType_'] = '��������';
				if ($rdtask['email']['issend'] == 'y') {
					$actuserStr = $rdtask['email']['TO_ID'];
					$this->service->mail_d($rdtask, $actuserStr,$id);
				}
				$this->behindMethod($rdtask);
				msg('���񷢲��ɹ���');
			} else {
				msg('�޸�����ɹ���');
			}
		}
	}

	/**
	 * @desription ���������༭
	 * @date 2010-9-26 ����10:40:55
	 */
	function c_editBatchEditor() {
		$id = $this->service->editBatchEditor_d($_POST[$this->objName], true);
		if ($id) {
			msg('�����༭����ɹ���');
		}
	}

	/*
	 * ��������
	 */
	function c_publishTask() {
		$rdtask['id'] = $_GET['id'];
		$this->beforeMethod($rdtask);
		$result = $this->service->publishTask_d($_GET['id'], $_SESSION['USER_ID'], $_SESSION['USERNAME']);
		if (isset ($result)) {
			$rdtask['operType_'] = '��������';
			$this->operLog = '��������ɹ�';
			$this->behindMethod($rdtask);
		}
		echo util_jsonUtil :: iconvGB2UTF($result);
	}
	/*
	  * �������,ͬʱ��������Ϣ
	  */
	function c_changeTask() {
		$rdtask = $_POST[$this->objName];
		$this->operArr = array (
			"name" => "��������",
			"appraiseWorkload" => "���ƹ�����",
			"remark" => "��������",
			"markStoneName" => "��ǵ���̱�",
			"planBeginDate" => "�ƻ���ʼʱ��",
			"planEndDate" => "�ƻ�����ʱ��",
			"chargeName" => "������",
			"priority" => "���ȼ�",
			"taskType" => "��������",
			"frontTaskName" => "ǰ������"
		);
		$this->service->datadictFieldArr = array (
			"priority",
			"taskType",


		);
		$this->beforeMethod($rdtask);
		$id = $this->service->editTask_d($_POST[$this->objName], true);
		if ($id) {
			$this->behindMethod($rdtask, 'change');
			msg('�������ɹ���');

		}
	}

	/**************** BEGIN ������չ����********************/

	/*
	 * �������־-��ѯ������չ
	 */
	function c_workSchedule() {
		$this->show->display($this->objPath . '_' . $this->objName . '-workschedule');
	}

	/*
	 * �������־-��ѯ������չ���
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
	 * �������־-��ѯ������չ
	 */
	function c_workScheduleForManager() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->show->display($this->objPath . '_' . $this->objName . '-workscheduleformanager');
	}

	/**
	 * ������չ��ѯ���
	 */
	function c_workScheduleResultForManager(){
		$_GET = array_filter($_GET);
		$this->show->assign('tableHead', $this->service->changeHead($_GET));

		$rows = $this->service->getWorkScheduleRs($_GET);
		$this->pageShowAssign();

		$this->show->assign('list', $this->service->showWorkSchedule($rows, $_GET));
		$this->show->display($this->objPath . '_' . $this->objName . '-workscheduleresultformanager');
	}

	/**************** END ������չ����*********************/

	/**
	 * @desription ajax�ж����������Ƿ��ظ�
	 * @date 2010-9-13 ����02:22:04
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
	 * ajaxɾ������
	 */
	function c_ajaxDeleteByPk() {
		//	 	echo parent::deleteByPk($_GET['id']);
		$result = $this->service->deleteByPk($_GET['id']);
		echo $result;
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myTaskpageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 *  ��ת���鿴��Ŀ�����б�
	 */
	function c_toProTkViewPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getTkInfoByPId_d($searchArr);

		//$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign("projectId", $_GET['pjId']);
		$this->show->assign('list', $service->showProjectTaskList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-vtask-list');
	}
	/*
	 * ��ת����Ŀ��������б�
	 */
	function c_toProTkManagePage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 *��ת����Ŀ�����չ��ͼ�б�
	 */
	function c_toProTkProcessPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ��ת����Ŀ����ƫ����ͼ
	 */
	function c_toProTkDeviationPage() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->getTkDeviationPro_d($searchArr);
		$this->pageShowAssign();

		$this->show->assign("pjId", $_GET['pjId']);
		$this->show->assign('list', $service->showProTkDeviationList($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-project-deviation-list');

	}
	/*
	 * ��ת����Ŀ������ҳ��
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
	 * ��ת����������ҳ��
	 */
	function c_toRdTaskCalendar() {
		$this->show->display($this->objPath . '_' . $this->objName . '-calendar');
	}
	/*
	 * ��ȡ��ǰ�·ݵĸ�����������
	 */
	function c_getCalendarTkData() {
		//	$goadDate=date();
		$rows = $this->service->getPerMonthTk_d("");
		//		$arr = array (array ("EventID" => 1, "Date" => "2010-10-12", "Title" => "10:00 pm - ��Ŀ1", "URL" => "#", "Description" => "��Ҫ��ʼ�����������", "CssClass" => "Birthday" ), array ("EventID" => 2, "StartDateTime" => "2010-10-12", "Title" => "10:00 pm - ��Ŀ1Ԥ�����", "URL" => "?model=", "Description" => "��������������ύ���", "CssClass" => "Birthday" ), array ("EventID" => 3, "Date" => "2010-10-28", "Title" => "9:30 pm - ��Ŀ����2", "URL" => "#", "Description" => "��ĵڶ�������-���� Ԥ�ƽ���", "CssClass" => "Meeting" ) );
		//		$testArr = array ();
		//	echo "<pre>";
		//	print_r($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/*
	 * �жϵ�ǰ��¼���Ƿ�����ѡ����ĸ�����
	 */
	function c_isChargeUser() {
		$service = $this->service;
		echo $service->isChargeUser_d($_GET['id']);
	}
	/*
	 *�ж������Ƿ����������
	 */
	function c_exsitChargeUser() {
		$service = $this->service;
		echo $service->isChargeUser_d($_GET['id']);
	}

	/**
	 * ����ͼ
	 */
	function c_showGantt() {
		$this->service->showGantt($_GET['planId']);
	}

	/*Start:-------------------------�������ͳ�ƴ���------------------------------------*/
	/*
	 * 1.��ת����Ŀ����״̬ͳ�Ʒ�����Ϣҳ��
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
	  *1.��ת����Ŀ����״̬ͳ�Ʒ�����Ϣͼ��
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
		} //����ͳ����Ϣ
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
			'exportFileName' => "����״̬ͼ",
			'caption' => "����״̬ͼ",
			'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
			'exportAction'=>'download'//'exportAction' => "save"
		); //����Ƿ��������У�֧�����������or����������

		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 2.��ת����Ŀ������������ͳ�Ʒ�����Ϣҳ��
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
	  *2.��ת����Ŀ����״̬ͳ�Ʒ�����Ϣͼ��
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
		}; //����ͳ����Ϣ
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
			'exportFileName' => "��������͸��ͼ",
			'caption' => "��������͸��ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "��"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 3.��ת����Ŀ����ƫ����ͳ�Ʒ�����Ϣҳ��
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
	  *3.��ת����Ŀ����ƫ����ͳ�Ʒ�����Ϣͼ��
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
		}; //����ͳ����Ϣ
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
			'exportFileName' => "����ƫ��͸��ͼ",
			'caption' => "����ƫ��͸��ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "��"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 4.��ת����Ŀ�����������ͳ�Ʒ�����Ϣҳ��
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
	  *4.��ת����Ŀ�����������ͳ�Ʒ�����Ϣͼ��
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
		}; //����ͳ����Ϣ

		$chartDao = new model_common_fusionCharts();
		$i = 0;
		$rsrows = array ();
		foreach ($rows as $val) {
			$rsrows[$i][1] = $val['dataName'];
			$rsrows[$i][2] = $val['dataNum'];
			$i++;
		}
		$chartConf = array (
			'exportFileName' => "�����������͸��ͼ",
			'caption' => "�����������͸��ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "��"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 5.��ת����Ŀ������������ͳ�Ʒ�����Ϣҳ��
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
	  *5.��ת����Ŀ������������ͳ�Ʒ�����Ϣͼ��
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
		}; //����ͳ����Ϣ
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
			'exportFileName' => "��������������ͼ",
			'caption' => "��������������ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "��"
		);
		echo $chartDao->showCharts($rsrows, "Column2D.swf", $chartConf);
	}
	/*
	 * 6.��ת����Ŀ���񰴳�Ա�����չͳ�Ʒ�����Ϣҳ��
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
	  *6.��ת����Ŀ���񰴳�Ա�����չ������Ϣͼ��
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
		}; //����ͳ����Ϣ
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
			'exportFileName' => "��Ա�����չ͸��ͼ",
			'caption' => "��Ա�����չ͸��ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "%"
		);
		echo $chartDao->showCharts($rsrows, "Pie2D.swf", $chartConf);
	}
	/*
	 * 7.��ת����Ŀ���񰴳�Ա����ƫ�������Ϣҳ��
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
	  *7.��ת����Ŀ��������ƫ��ͳ�Ʒ�����Ϣͼ��
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
		}; //����ͳ����Ϣ
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
			'exportFileName' => "��Ա����ƫ��͸��ͼ",
			'caption' => "��Ա����ƫ��͸��ͼ",
				'exportAtClient' => 0, //0: �����������У� 1�� �ͻ�������
		'exportAction' => "download", //����Ƿ��������У�֧�����������or����������
	'$numberSuffix' => "%"
		);
		echo $chartDao->showCharts($rsrows, "Column2D.swf", $chartConf);
	}

	/*End -------------------------�������ͳ�ƴ���------------------------------------*/

	/*
	 * �������ݵ�EXCEL
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
	 * �з����񵼳���������
	 * @param author zengzx
	 * 2011��10��21�� 13:51:54
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
		//����auditUser���ڱ�������unset��auditUser.
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
				echo "<script>alert('����Ӧ���ݣ�����ʧ�ܣ�');window.close();</script>";
				exit(0);
			}
		}
		$this->c_exportTaskExcel($dataArr,$listType);
	}

	/*
	 * ������Ŀ�������ݵ�EXCEL
	 */
	function c_exportTaskExcel($dataArr,$listType) {
		//�ж��Ƿ��д���auditUser flag = 0 ��ʾû�д��룬flag = 1 ��ʾ�д���
		$flag = 0;
		$tkauditUserDao = new model_rdproject_task_tkaudituser();
		$tkoverDao = new model_rdproject_task_tkover();
		$selectField = substr($_GET['selectField'], 0, -1);
		$thArr = explode(",", substr($selectField, 0));
		//����auditUser���ڱ�������unset��auditUser.
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
						//�ж��Ƿ��д���auditUser������У���ȡ��auditUser��Ϣ��
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
					//������״̬�����ȼ������������ɱ���ת��Ϊ��Ӧ�����ġ�
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

		//��auditUser���뵽��excel��˳��һ�µ�λ�á�
		if ($auditUserflag == 1) {
			foreach ($dataArr as $rdNum => $row) {
				//��auditUser������β������
				$auditTempArr['auditUser'] = array_pop($dataArr[$rdNum]);
				//��auditUser���뵽ָ��λ��
				array_splice($dataArr[$rdNum], $auditUsersite, 0, $auditTempArr);
			}
		}
		$thArr = explode(",", substr($_GET['tdName'], 0, -1));
		//���õ���excel������
		if( !$listType ){
			return model_rdproject_task_rdExcelUtil :: export2ExcelUtil($thArr, $dataArr);
		}else{
			return model_rdproject_task_rdExcelUtil :: myTaskExcelUtil($thArr, $dataArr);
		}
	}

	/*
	 *��ת��excel�ϴ�ҳ�� -- Demo
	 */
	function c_toExcelDemo() {

		$this->show->display($this->objPath . '_' . 'excelDemo');
	}

	/**
	 * �鿴�ƻ�������乤�������
	 */
	function c_toViewPlanLoad() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 *  ��ת��excel�ϴ�ҳ��
	 * create By ZZX 20110416
	 */
	function c_toImportExcel() {
		if (!$this->service->this_limit['��Ŀ������']) {
			echo "<script>alert('û��Ȩ�޽��в���!');self.parent.tb_remove();</script>";
			exit ();
		}
		$this->show->display($this->objPath . '_' . 'import-excel');
	}

	/*
	 * �ϴ�EXCEL
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


		); //�ֶ�����

		$this->c_addExecelData($objNameArr);
		//		$this->display( 'upexcel' );
	}

	/**
	 * �ϴ�EXCEl������������
	 *�����������������ӷ���
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
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_rdproject_task_rdExcelUtil :: upReadExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			//�ж��Ƿ��������Ч����
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					//����̱��ƻ����ƺ��������Ƹ�ʽ����ɾ������Ŀո����������Ϊ�գ���������ݲ�����Ч��
					$excelData[$rNum][2] = str_replace(' ', '', $row[2]);
					$temp = $row[3];
					if (str_replace(' ', '', $temp) == '')
						unset ($excelData[$rNum]);
				}
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						//��ֵ������Ӧ���ֶ�
						$objectArr[$rNum][$fieldName] = $row[$index];
						if ($fieldName == 'projectName') {
							//����projectName������ǿ�ƽ����������projectName��Ϊ��һ�е�projectName��
							if ($excelData[$rNum][1] != '') {
								$objectArr[$rNum][$fieldName] = $excelData[$rNum][1];
							} else {
								$objectArr[$rNum][$fieldName] = $objectArr[$rNum -1][$fieldName];
							}
						} else
							if ($fieldName == 'projectCode') {
								//��projectCodeΪ��ʱ������һ�������projectCode�����������projectCode��
								if ($excelData[$rNum][0] != '') {
									$objectArr[$rNum][$fieldName] = $excelData[$rNum][0];
								} else {
									$objectArr[$rNum][$fieldName] = $objectArr[$rNum -1][$fieldName];
								}
								//����projectCode��ѯprojectId��
								$projectId = $projectDao->getProjectIdByProjectCode_d($objectArr[$rNum][$fieldName]);
								if ($projectId == '') {
									msg("����ʧ�ܣ�ϵͳ���Ҳ�������Ŀ����ȷ���Ƿ��д���Ŀ�����ڵ�������жϣ��ϴ������ݽ���������");
									return false;
								}
								$objectArr[$rNum]['projectId'] = $projectId;
							}
						elseif ($fieldName == 'planName') {
							$objectArr[$rNum]['planName'] = $row[$index];
							//����planName��planId��
							$plan = $planDao->getPlanIdByName($objectArr[$rNum]['projectId'], $row[$index]);
							$objectArr[$rNum]['planId'] = $plan['id'];
							//							$objectArr [$rNum] ['priority'] = $planArr['dataCode'];
						}
						elseif ($fieldName == 'priority') {
							//�������ȼ������ƺ�parentCode��dataCode��
							$ptySearchArr = array (
								'parentCode' => 'XMRMYXJ',
								'dataName' => $row[$index]
							);
							$priorityArr = $datadictDao->find($ptySearchArr, null, 'dataCode');
							$objectArr[$rNum]['priority'] = $priorityArr['dataCode'];
						}
						elseif ($fieldName == 'taskType') {
							//�����������͵����ƺ�parentCode��dataCode��
							$tktSearchArr = array (
								'parentCode' => 'XMRWLX',
								'dataName' => $row[$index]
							);
							$taskTypeArr = $datadictDao->find($tktSearchArr, null, 'dataCode');
							$objectArr[$rNum]['taskType'] = $taskTypeArr['dataCode'];
						}
						elseif ($fieldName == 'chargeName') {
							//����projectId��chargeName����Ŀ��Ա�б���chargeId��
							$chargeId = $menberDao->getMemberIdByName($objectArr[$rNum]['projectId'], $row[$index]);
							$objectArr[$rNum]['chargeId'] = $chargeId['memberId'];
						} else {
							$objectArr[$rNum]['belongNodeId'] = -1;
						}
					}
				}
				$auditObjs = array ();
				foreach ($objectArr as $index => $row) {
					$auditNameStr = str_replace('��', ',', $row['auditName']);
					$auditNameArr = explode(',', $auditNameStr);
					foreach ($auditNameArr as $key => $val) {
						if ($val != '') {
							$auditId = $menberDao->getMemberIdByName($row['projectId'], $auditNameArr[$key]);
							$auditObjs[$key]['auditUser'] = $auditNameArr[$key];
							$auditObjs[$key]['auditId'] = $auditId['memberId'];
						}
					}
					$objectArr[$index]['auditName'] = $auditObjs;
					$userNameStr = str_replace('��', ',', $row['userName']);
					$userNameArr = explode(',', $userNameStr);
					foreach ($userNameArr as $key => $val) {
						if ($val != '') {
							$actUserId = $menberDao->getMemberIdByName($row['projectId'], $userNameArr[$key]);
							$actUserObjs[$key]['userName'] = $userNameArr[$key];
							$actUserObjs[$key]['actUserId'] = $actUserId['memberId'];
						}
					}
					$objectArr[$index]['userName'] = $actUserObjs;
					//�������ʱ�䣬������ת��Ϊ���ڡ�
					$planBeginDate = mktime(0, 0, 0, 1, $objectArr[$index]['planBeginDate'] - 1, 1900);
					$objectArr[$index]['planBeginDate'] = date("Y-m-d", $planBeginDate);
					$planEndDate = mktime(0, 0, 0, 1, $objectArr[$index]['planEndDate'] - 1, 1900);
					$objectArr[$index]['planEndDate'] = date("Y-m-d", $planEndDate);
				}
				//ѭ�����ò��뷽��
				$returnDate = $this->service->addBatchByExcel_d($objectArr);
				if ($returnDate == '')
					msg("����ɹ�!");
				//					echo "����ɹ�!";
				else {
					msg($returnDate);
					//					echo "$returnDate";
				}
			} else {
				msg("�ļ������ڿ�ʶ������!");
			}
		} else {
			msg("�ϴ��ļ����Ͳ���EXCEL!");
		}

	}

	/*******************************������Ŀ��������--start**********************************************/

	/*
	 *  ��ת��excel�ϴ�ҳ��
	 * create By ZZX 2011��7��6�� 13:56:25
	 */
	function c_toImportExcelbypro() {
		if (!empty ($_GET['pjId'])) {
			//Ȩ���ж�
			$this->isCurUserPerm($_GET['pjId'], 'project_import_task');
		}
		$rdprojectDao = new model_rdproject_project_rdproject();
		$rdprojectObj = $rdprojectDao->get_d($_GET['pjId']);
		$this->assign('projectCode', $rdprojectObj['projectCode']);
		$this->assign('projectName', $rdprojectObj['projectName']);
		$this->show->display($this->objPath . '_' . 'import-excelbypro');
	}

	/*
	 * �ϴ�EXCEL
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
		); //�ֶ�����

		$projectCode = $_GET['projectCode'];
		$projectName = $_GET['projectName'];
		$this->c_addExecelDatabypro($objNameArr, $projectCode, $projectName);
		//		$this->display( 'upexcel' );
	}

	/**
	 * �ϴ�EXCEl������������
	 *�����������������ӷ���
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
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_rdproject_task_rdExcelUtil :: upReadExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			//�ж��Ƿ��������Ч����
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					//����̱��ƻ����ƺ��������Ƹ�ʽ����ɾ������Ŀո����������Ϊ�գ���������ݲ�����Ч��
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
						//��ֵ������Ӧ���ֶ�
						$objectArr[$rNum][$fieldName] = $row[$index];
						if ($fieldName == 'planName') {
							$objectArr[$rNum]['planName'] = $row[$index];
							//����planName��planId��
							$plan = $planDao->getPlanIdByName($projectId, $row[$index]);
							$objectArr[$rNum]['planId'] = $plan['id'];
							//							$objectArr [$rNum] ['priority'] = $planArr['dataCode'];
						}
						elseif ($fieldName == 'priority') {
							//�������ȼ������ƺ�parentCode��dataCode��
							$ptySearchArr = array (
								'parentCode' => 'XMRMYXJ',
								'dataName' => $row[$index]
							);
							$priorityArr = $datadictDao->find($ptySearchArr, null, 'dataCode');
							$objectArr[$rNum]['priority'] = $priorityArr['dataCode'];
						}
						elseif ($fieldName == 'taskType') {
							//�����������͵����ƺ�parentCode��dataCode��
							$tktSearchArr = array (
								'parentCode' => 'XMRWLX',
								'dataName' => $row[$index]
							);
							$taskTypeArr = $datadictDao->find($tktSearchArr, null, 'dataCode');
							$objectArr[$rNum]['taskType'] = $taskTypeArr['dataCode'];
						}
						elseif ($fieldName == 'chargeName') {
							//����projectId��chargeName����Ŀ��Ա�б���chargeId��
							$chargeId = $menberDao->getMemberIdByName($projectId, $row[$index]);
							$objectArr[$rNum]['chargeId'] = $chargeId['memberId'];
						} else {
							$objectArr[$rNum]['belongNodeId'] = -1;
						}
					}
				}
				$auditObjs = array ();
				foreach ($objectArr as $index => $row) {
					//ƴװ�����
					$auditNameStr = str_replace('��', ',', $row['auditName']);
					$auditNameArr = explode(',', $auditNameStr);
					foreach ($auditNameArr as $key => $val) {
						if ($val != '') {
							$auditId = $menberDao->getMemberIdByName($projectId, $auditNameArr[$key]);
							$auditObjs[$key]['auditUser'] = $auditNameArr[$key];
							$auditObjs[$key]['auditId'] = $auditId['memberId'];
						}
					}
					$objectArr[$index]['auditName'] = $auditObjs;
					//ƴװ������
					$userNameStr = str_replace('��', ',', $row['userName']);
					$userNameArr = explode(',', $userNameStr);
					foreach ($userNameArr as $key => $val) {
						if ($val != '') {
							$actUserId = $menberDao->getMemberIdByName($projectId, $userNameArr[$key]);
							$actUserObjs[$key]['userName'] = $userNameArr[$key];
							$actUserObjs[$key]['actUserId'] = $actUserId['memberId'];
						}
					}
					$objectArr[$index]['userName'] = $actUserObjs;
					//�������ʱ�䣬������ת��Ϊ���ڡ�
					$planBeginDate = mktime(0, 0, 0, 1, $objectArr[$index]['planBeginDate'] - 1, 1900);
					$objectArr[$index]['planBeginDate'] = date("Y-m-d", $planBeginDate);
					$planEndDate = mktime(0, 0, 0, 1, $objectArr[$index]['planEndDate'] - 1, 1900);
					$objectArr[$index]['planEndDate'] = date("Y-m-d", $planEndDate);
				}
				//ѭ�����ò��뷽��
				$returnDate = $this->service->addBatchByExcel_d($objectArr);
				if ($returnDate == '')
					msg("����ɹ�!");
//				echo '�ɹ�';
				else {
					msg($returnDate);
					//echo 'ʧ��';
				}
			} else {
				msg("�ļ������ڿ�ʶ������!");
			}
		} else {
			msg("�ϴ��ļ����Ͳ���EXCEL!");
		}

	}

	/********************************��Ŀ�з�������--end*************************************************/

	/*
	 * ��ת����Ŀ��������б�
	 * 2011��6��29�� 09:14:34
	 * ���ڶԾ��з����ݵ��޸�
	 */
	function c_toProTkManagePageOld() {
		$service = $this->service;
		$searchArr = $service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ��ת���ҵ�����༭ҳ��
	 * @date 2011��6��29�� 09:35:01
	 * ���ڱ༭���з�����
	 */
	function c_toEditTaskOld() {
		$rows = $this->service->getEditTaskInfo_d($_GET['id']);
		//��������
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
		/*start:��̱���Ϣ*/
		$stoneMarkDao = new model_rdproject_milestone_rdmilespoint();
		//		echo "------".$rows['projectId'];
		$this->show->assign("markStoneOption", $stoneMarkDao->rmMilespointSelectId_d($rows['projectId'], $rows['stoneId'], '', false));
		/*end:��̱���Ϣ*/

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
	 * ��ת����Ŀ����ҳ��
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
	 *��ȡ��ǰ�·ݵ���Ŀ��������
	 * @linzx
	 */

	function c_getProjectCalendarTkData() {
		$porId = $_GET["projectId"];
        $rows = $this->service->getPorjectMonthTk_d($porId);
		echo util_jsonUtil :: encode($rows);
	}
}
?>
