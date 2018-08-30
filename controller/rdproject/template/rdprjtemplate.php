<?php
/**
 * @desription ����Ŀ�ġ�ģ�塱���и���صĲ���
 * @param tags
 * @date 2010-10-22 ����10:05:34
 */
class controller_rdproject_template_rdprjtemplate extends controller_base_action {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-10-22 ����10:06:43
	 */
	function __construct () {
		$this->objName = "rdprjtemplate";
		$this->objPath = "rdproject_template";
		parent::__construct();
	}

	/**
	 * -----------------------------------����Ϊ��ͨaction����-----------------------------------
	 */
	/*
	 * @desription Ĭ����תҳ��
	 * @param tags
	 * @date 2010-9-26 ����09:35:49
	 */
	function c_projectlist () {
		$this->show->display($this->objPath . '_' . $this->objName . '-main-list');
	}

	/*
	 * ��ʾ�󵼺���
	 */
	function c_menulist(){
		$this->show->display($this->objPath . '_' . $this->objName . '-menu-list');
	}

	/**
	 * ģ������б�
	 */
	function c_showTemplates(){
		$service = $this->service;
//		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->templatePage_d();
//		print_r($rows);
		$auditArr = array(
	 		"createId" => $_SESSION['USER_ID'],
//	 		"id"=>$_GET['id']
	 	);
	 	$this->pageShowAssign();
		$this->show->assign ( 'templateList', $service->showTemplateList ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/*
	 * ��ʾĬ����ҳ��
	 * Ĭ��Ϊ����Ŀ��̱���Tab��ǩ�µ���ʾ�б�ҳ��
	 */
//	 function c_tabTemplateList(){
//	 	$service = $this->service;
//	 	$auditArr = array(
//	 		"createId" => $_SESSION['USER_ID']
//	 	);
//
//	 	if(!isset($_GET['projectType'])){
//	 		$_GET['projectType'] = null;
//	 	}
//
//	 	$rows = $service->page_d($_GET['projectType']);
//
//	 	$this->pageShowAssign();
//
//	 	$this->showDatadicts(array('projectType'=>'YFXMGL'));
//	 	$this->show->assign('templateList',$service->showTemplateList($rows));
//	 	$this->show->display($this->objPath . '_' . $this->objName . '-list');
//	 }

	/*
	 * @desription ģ�������ʾ�б�ҳ
	 * @param tags
	 * @date 2010-10-21 ����03:58:55
	 */
	function c_toTemplateList () {
		$service = $this->service;
		if(!isset($_GET['prjid'])){
			$_GET['prjid'] = null;
		}
//		$projectType = $_GET['projectType'];
//		$service->searchArr = array("projectType" => $projectType);
		$rows = $service->page_d();
//		echo "<pre>";
//		print_r($rows);
		$projectType = $this->showdatadicts(array('projectType' => 'YFXMGL'));
		$this->show->assign('projectType',$projectType);
//		$this->show->assign('list',$this->showTemplateList($rows));
		$this->show->assign('templateList',$service->showprojectlist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/*
	 * ��תҳ�淽��
	 * �鿴��̱��ƻ�ģ����ϸ����
	 */
	function c_toviewtemplate(){
		$service = $this->service;
		$templateId = $_GET['id'];
		$milestoneDao = new model_rdproject_baseinfo_rdmilestoneinfo();
		$getTempMilestone = $milestoneDao->templateView_d($templateId);
//		$auditArr = array(
//			"createId" => $_SESSION['USER_ID'],
//			"parentId" => $_GET['parentId']
//		);
//		print_r($auditArr);
		$this->show->assign('parentId',$templateId);
		$this->show->assign('viewtemplate',$service->viewtemplate_d($getTempMilestone));
//		$this->show->assign('viewtemplate',);
		$this->show->display($this->objPath . '_' . $this->objName . '-view');
	}

	/*
	 * ��ת��ģ������ҳ�棬��Ҫ�ǹ�������Ŀ����-��̱��ƻ�ģ�塱
	 * һ����Ŀ����ֻ�ܶ�Ӧһ����̱��ƻ�ģ��
	 * һ����̱��ƻ�ģ����Զ�Ӧ��������
	 * ����Ŀ������ѡ���µ���̱��ƻ�ģ�����ģ����滻��ԭ����ģ��
	 */
	function c_toSetTemplate(){
		$service = $this->service;
		$typeArr = $this->getDatadicts(array('projectType' => 'YFXMGL'));
		$this->show->assign('list',$service->showTypeAndTemplate($typeArr['YFXMGL'] ));
		$this->show->display($this->objPath . '_' . $this->objName . '-set');
	}

	/*
	 * ��ת������Ϊģ��ҳ��
	 */
	function c_toSetAsTemplate(){
//		$this->show->assign('exTemplate',$service->showExTemplate());
		$this->show->display($this->objPath . '_' . $this->objName . '-settemp');
	}

	/*
	 * ����Ϊģ��
	 */
	function c_setAsTemplate(){
		$service = $this->service;
		$prjObj = $_POST[$this->objName];
		$tempObj = $service->setAsTemplate_d($prjObj);
		if($tempObj){
			msg('����ģ��ɹ�');
		}
	}

	/*
	 * ��ת�����ģ��ҳ��
	 */
	function c_toAddTemplate(){
		//����ģ���Ψһ����
		$this->showDatadicts(array("projectType"=>"YFXMGL"));
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/*
	 * ���ģ��ı��淽��
	 */
	function c_addTemplate(){
		$service = $this->service;
		$tempObj = $_POST[$this->objName];
		$addresult = $service->addTemplate_d($tempObj);
		if($addresult){
			msg('�����̱��ƻ�ģ��ɹ�');
		}
	}


	/*
	 * ����ģ�巽��
	 */
	function c_releaseTemplate(){

	}

	/**
	 * -----------------------------------����Ϊajax����json����-----------------------------------
	 */

}
?>
