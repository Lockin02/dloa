<?php
/**
 * @author show
 * @Date 2013��10��17�� 15:38:39
 * @version 1.0
 * @description:��Ŀ��Ԥ������Ʋ�
 */
class controller_engineering_weekreport_weekfee extends controller_base_action {

	function __construct() {
		$this->objName = "weekfee";
		$this->objPath = "engineering_weekreport";
		parent :: __construct();
	}

	/**
	 * ��ȡ������Ϣ - �鿴���������༭ҵ���޲���
	 */
	function c_getWeek(){
		$projectId = isset($_REQUEST['projectId']) ? $_REQUEST['projectId'] : "";
		$weekNo = isset($_REQUEST['weekNo']) ? $_REQUEST['weekNo'] : "";
		$mainId = isset($_REQUEST['mainId']) ? $_REQUEST['mainId'] : "";
// 		$weeklogInfo = $this->service->getWeek_d($projectId,$weekNo,$mainId);
		$weeklogInfo = $this->service->getNew_d($projectId,$weekNo);
		//�༭���鿴ҳ��ִ��
		if(!empty($mainId)){
			//��ȡ�ܱ�����״̬
			$statusreportDao = new model_engineering_project_statusreport();
			$statusInfo = $statusreportDao->getExaStatus_d($mainId);
			if($statusInfo){
				//���±�����
				$this->service->update_d($mainId,$weeklogInfo);
			}
		}
		exit(util_jsonUtil::iconvGB2UTF($this->service->showWeek_d($weeklogInfo)));
	}
	
	/**
	 * ��ȡԤ������Ϣ_�´���
	 */
	function c_getNewWeek(){
		$this->assignFunc($_GET);
		$this->view('fee');		
	}
}