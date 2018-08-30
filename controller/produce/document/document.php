<?php
/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_produce_document_document extends controller_base_action {

	function __construct() {
		$this->objName = "document";
		$this->objPath = "produce_document";
		parent::__construct ();
	}

	/**
	 * 左树右物料信息列表
	 */
	function c_toProInfoTypePage() {
		$this->assign('topId', isset($_GET['typeId']) ? $_GET['typeId'] : '');//用来区分不同的文档分类栏目
		$this->view("list");
	}
	
	/**
	 * 跳转到上传附件
	 */
	function c_toUploadFile() {
		//获取生产计划基本信息
		$id = isset($_GET['serviceId'])? $_GET['serviceId'] : '';
		$producePlanDao = new model_produce_plan_produceplan();
		$obj = $producePlanDao->get_d ( $id );
		foreach ( $obj as $key => $val ) {
			$this->assign ( 'view_'.$key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('view_showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //显示合同或者源单

		// 验证是否可编辑物料 ID2195
		$serviceId= isset($_GET['serviceId']) ? $_GET['serviceId'] : '';
		$producePlan = $producePlanDao->find(array('id'=>$serviceId));
		$canEditEqu = ($producePlan['docStatus'] == 1)? 'ok' : 'no';
		$this->assign('editEqu',$canEditEqu);

		$this->assign('topId', isset($_GET['topId']) ? $_GET['topId'] : '');//顶级文档分类id
		$this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : '');//文档分类id
		$this->assign('typeName', isset($_GET['typeName']) ? $_GET['typeName'] : '');//文档分类名称
		$this->assign('serviceId', isset($_GET['serviceId']) ? $_GET['serviceId'] : '');//业务单据id
		$this->assign('serviceNo', isset($_GET['serviceNo']) ? $_GET['serviceNo'] : '');//业务单据编号
		$this->assign('serviceType', isset($_GET['serviceType']) ? $_GET['serviceType'] : '');//业务类型，一般为表名或类名
		$this->assign('styleOne', isset($_GET['styleOne']) ? $_GET['styleOne'] : '');//用来区分同个类的不同业务
		if(isset($_GET['title']) && !empty($_GET['title'])){
			$this->assign('title',$_GET['title']);
		}else{
			$this->assign('title','生产文档');
		}
		if($_GET['title'] == '上传规范文档'){//无指引文件页面
			$this->view('uploadFile1' ,true);
		}else{
			$this->view('uploadFile2' ,true);
		}
	}
	
	/**
	 * 上传附件处理
	 */
	function c_uploadFile() {
		$producePlanDao = new model_produce_plan_produceplan();
		// 物料处理
		if(isset($_POST['produceplan']['editEqu']) && $_POST['produceplan']['editEqu'] == 'ok'){
			$edit_result = $producePlanDao->editClassify_d($_POST['produceplan']);
			if(!$edit_result){msg('物料修改失败。');exit();}
		}
		
		// 附件处理
		$guideArr = isset($_POST['guideArr'])? $_POST['guideArr'] : array();
		if ($this->service->updateObjWithFile($_POST['fileuploadIds'],$_POST[$this->objName],$guideArr)) {
			msg('确认成功');
		}else{
			msg('无新增附件');
		}
	}
	
	/**
	 * 根据文档分类获取文档信息列表数据
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		if (isset ($service->searchArr['typeId'])) {
			$documenttypeDao = new model_produce_document_documenttype ();
			$documentTypes = $documenttypeDao->getChildrenNodes($service->searchArr['typeId']);
			$service->searchArr['typeId'] = $documentTypes ? implode(',', $documentTypes) . ',' . $service->searchArr['typeId'] : $service->searchArr['typeId'];
		}
		if($_REQUEST['typeId'] != '1'){//日常报表，需要额外加载客户名称，合同号，销售负责人，生产入库完成日期信息
			$rows = $service->page_d('select_extend');
		}else{
			$rows = $service->page_d();
		}
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}
	
	/**
	 * 获取分类下的文档，如指引文档
	 */
	function c_getDocuments() {
		$service = $this->service;
		$parentId= isset ($_GET['parentId']) ? $_GET['parentId'] : -1;
		$documenttypeDao = new model_produce_document_documenttype ();
		$documentTypes = $documenttypeDao->getChildrenNodes($parentId);
		$service->searchArr['typeId'] = $documentTypes ? implode(',', $documentTypes) . ',' . $parentId : $parentId;
		$service->asc = false;
		$rows = $service->list_d();
		echo util_jsonUtil :: encode($rows);
	}
}
