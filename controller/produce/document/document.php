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
	 * ������������Ϣ�б�
	 */
	function c_toProInfoTypePage() {
		$this->assign('topId', isset($_GET['typeId']) ? $_GET['typeId'] : '');//�������ֲ�ͬ���ĵ�������Ŀ
		$this->view("list");
	}
	
	/**
	 * ��ת���ϴ�����
	 */
	function c_toUploadFile() {
		//��ȡ�����ƻ�������Ϣ
		$id = isset($_GET['serviceId'])? $_GET['serviceId'] : '';
		$producePlanDao = new model_produce_plan_produceplan();
		$obj = $producePlanDao->get_d ( $id );
		foreach ( $obj as $key => $val ) {
			$this->assign ( 'view_'.$key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('view_showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //��ʾ��ͬ����Դ��

		// ��֤�Ƿ�ɱ༭���� ID2195
		$serviceId= isset($_GET['serviceId']) ? $_GET['serviceId'] : '';
		$producePlan = $producePlanDao->find(array('id'=>$serviceId));
		$canEditEqu = ($producePlan['docStatus'] == 1)? 'ok' : 'no';
		$this->assign('editEqu',$canEditEqu);

		$this->assign('topId', isset($_GET['topId']) ? $_GET['topId'] : '');//�����ĵ�����id
		$this->assign('typeId', isset($_GET['typeId']) ? $_GET['typeId'] : '');//�ĵ�����id
		$this->assign('typeName', isset($_GET['typeName']) ? $_GET['typeName'] : '');//�ĵ���������
		$this->assign('serviceId', isset($_GET['serviceId']) ? $_GET['serviceId'] : '');//ҵ�񵥾�id
		$this->assign('serviceNo', isset($_GET['serviceNo']) ? $_GET['serviceNo'] : '');//ҵ�񵥾ݱ��
		$this->assign('serviceType', isset($_GET['serviceType']) ? $_GET['serviceType'] : '');//ҵ�����ͣ�һ��Ϊ����������
		$this->assign('styleOne', isset($_GET['styleOne']) ? $_GET['styleOne'] : '');//��������ͬ����Ĳ�ͬҵ��
		if(isset($_GET['title']) && !empty($_GET['title'])){
			$this->assign('title',$_GET['title']);
		}else{
			$this->assign('title','�����ĵ�');
		}
		if($_GET['title'] == '�ϴ��淶�ĵ�'){//��ָ���ļ�ҳ��
			$this->view('uploadFile1' ,true);
		}else{
			$this->view('uploadFile2' ,true);
		}
	}
	
	/**
	 * �ϴ���������
	 */
	function c_uploadFile() {
		$producePlanDao = new model_produce_plan_produceplan();
		// ���ϴ���
		if(isset($_POST['produceplan']['editEqu']) && $_POST['produceplan']['editEqu'] == 'ok'){
			$edit_result = $producePlanDao->editClassify_d($_POST['produceplan']);
			if(!$edit_result){msg('�����޸�ʧ�ܡ�');exit();}
		}
		
		// ��������
		$guideArr = isset($_POST['guideArr'])? $_POST['guideArr'] : array();
		if ($this->service->updateObjWithFile($_POST['fileuploadIds'],$_POST[$this->objName],$guideArr)) {
			msg('ȷ�ϳɹ�');
		}else{
			msg('����������');
		}
	}
	
	/**
	 * �����ĵ������ȡ�ĵ���Ϣ�б�����
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		if (isset ($service->searchArr['typeId'])) {
			$documenttypeDao = new model_produce_document_documenttype ();
			$documentTypes = $documenttypeDao->getChildrenNodes($service->searchArr['typeId']);
			$service->searchArr['typeId'] = $documentTypes ? implode(',', $documentTypes) . ',' . $service->searchArr['typeId'] : $service->searchArr['typeId'];
		}
		if($_REQUEST['typeId'] != '1'){//�ճ�������Ҫ������ؿͻ����ƣ���ͬ�ţ����۸����ˣ�����������������Ϣ
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
	 * ��ȡ�����µ��ĵ�����ָ���ĵ�
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
