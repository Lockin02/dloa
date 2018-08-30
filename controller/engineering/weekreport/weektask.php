<?php
/**
 * @author show
 * @Date 2013��9��22�� 14:45:40
 * @version 1.0
 * @description:��Ŀ��������ȿ��Ʋ�
 */
class controller_engineering_weekreport_weektask extends controller_base_action {

	function __construct() {
		$this->objName = "weektask";
		$this->objPath = "engineering_weekreport";
		parent :: __construct();
	}

	/**
	 * ��ȡ������Ϣ
	 */
	function c_getWeekTask(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
		$WeekTaskInfo = $this->service->getNewWeekTask_d($projectId,$weekNo);
		//�༭ҳ�棬���±�����
		if(!empty($mainId)){
			$this->service->update_d($mainId,$WeekTaskInfo);
		}
		echo (util_jsonUtil::iconvGB2UTF($this->service->showWeekTask_d($WeekTaskInfo)));
	}

	/**
	 * �鿴��״������
	 */
	function c_viewWeekTask(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
		//��ȡ�ܱ�����״̬
		$statusreportDao = new model_engineering_project_statusreport();
		$statusInfo = $statusreportDao->getExaStatus_d($mainId);
		if($statusInfo){
			$WeekTaskInfo = $this->service->getNewWeekTask_d($projectId,$weekNo);
			//���±�����
			$this->service->update_d($mainId,$WeekTaskInfo);
		}else{
			$WeekTaskInfo = $this->service->getWeekTask_d(null,null,$mainId);
		}
		echo (util_jsonUtil::iconvGB2UTF($this->service->viewWeekTask_d($WeekTaskInfo)));
	}
	
	/**
	 * ��ȡ������Ϣ_�´���
	 */
	function c_getNewWeekTask(){	
		$this->assignFunc($_GET);
		$this->view('task');		
	}
}