<?php
/**
 * @desription 对项目的“模板”进行各相关的操作
 * @param tags
 * @date 2010-10-22 上午10:05:34
 */
class controller_rdproject_template_rdprjtemplate extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-10-22 上午10:06:43
	 */
	function __construct () {
		$this->objName = "rdprjtemplate";
		$this->objPath = "rdproject_template";
		parent::__construct();
	}

	/**
	 * -----------------------------------以下为普通action方法-----------------------------------
	 */
	/*
	 * @desription 默认跳转页面
	 * @param tags
	 * @date 2010-9-26 上午09:35:49
	 */
	function c_projectlist () {
		$this->show->display($this->objPath . '_' . $this->objName . '-main-list');
	}

	/*
	 * 显示左导航栏
	 */
	function c_menulist(){
		$this->show->display($this->objPath . '_' . $this->objName . '-menu-list');
	}

	/**
	 * 模板管理列表
	 */
	function c_showTemplates(){
		$service = $this->service;
//		$service->getParam ( $_GET ); //设置前台获取的参数信息
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
	 * 显示默认右页面
	 * 默认为“项目里程碑”Tab标签下的显示列表页面
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
	 * @desription 模板管理显示列表页
	 * @param tags
	 * @date 2010-10-21 下午03:58:55
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
	 * 跳转页面方法
	 * 查看里程碑计划模板详细内容
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
	 * 跳转到模板设置页面，主要是关联“项目类型-里程碑计划模板”
	 * 一个项目类型只能对应一套里程碑计划模板
	 * 一套里程碑计划模板可以对应多种类型
	 * 当项目类型下选择新的里程碑计划模板后，新模板会替换掉原来的模板
	 */
	function c_toSetTemplate(){
		$service = $this->service;
		$typeArr = $this->getDatadicts(array('projectType' => 'YFXMGL'));
		$this->show->assign('list',$service->showTypeAndTemplate($typeArr['YFXMGL'] ));
		$this->show->display($this->objPath . '_' . $this->objName . '-set');
	}

	/*
	 * 跳转到设置为模板页面
	 */
	function c_toSetAsTemplate(){
//		$this->show->assign('exTemplate',$service->showExTemplate());
		$this->show->display($this->objPath . '_' . $this->objName . '-settemp');
	}

	/*
	 * 设置为模板
	 */
	function c_setAsTemplate(){
		$service = $this->service;
		$prjObj = $_POST[$this->objName];
		$tempObj = $service->setAsTemplate_d($prjObj);
		if($tempObj){
			msg('设置模板成功');
		}
	}

	/*
	 * 跳转到添加模板页面
	 */
	function c_toAddTemplate(){
		//生成模板的唯一编码
		$this->showDatadicts(array("projectType"=>"YFXMGL"));
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}

	/*
	 * 添加模板的保存方法
	 */
	function c_addTemplate(){
		$service = $this->service;
		$tempObj = $_POST[$this->objName];
		$addresult = $service->addTemplate_d($tempObj);
		if($addresult){
			msg('添加里程碑计划模板成功');
		}
	}


	/*
	 * 发布模板方法
	 */
	function c_releaseTemplate(){

	}

	/**
	 * -----------------------------------以下为ajax返回json方法-----------------------------------
	 */

}
?>
