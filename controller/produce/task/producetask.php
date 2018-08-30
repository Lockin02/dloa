<?php
/**
 * @author huangzf
 * @Date 2012��5��12�� ������ 14:05:49
 * @version 1.0
 * @description:����������Ʋ�
 */
class controller_produce_task_producetask extends controller_base_action {

	function __construct() {
		$this->objName = "producetask";
		$this->objPath = "produce_task";
		parent::__construct ();
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
	
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
	
		//$service->asc = false;
		$rows = $service->page_d ();
		//�Ƿ����뷢���ƻ����Ѵ���
		$rows = $service->addOutPlanInfo($rows);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}
	
	/**
	 * ��ת����������Tab
	 */
	function c_pageTab() {
		$this->view ( 'list-tab' );
	}

	/**
	 * ��ת�����������б�
	 */
	function c_page() {
		$taskType = isset ( $_GET ['taskType'] ) ? $_GET ['taskType'] : "";
		$this->assign ( "taskType", $taskType );
		$this->assign ( "userId", $_SESSION['USER_ID'] );
		//��ִ���������� - Ȩ��
		if (isset($this->service->this_limit['��ִ����������']) && !empty($this->service->this_limit['��ִ����������'])) {
			$this->assign('allTask', "1");
		} else {
			$this->assign('allTask', "0");
		}
		$this->view ( 'list' );
	}

	/**
	 * ��ת��PMC���������б�
	 */
	function c_pagePmc() {
		$this->view ( 'pmc-list' );
	}

	/**
	 * ��ת����������δ��ɵ������б�
	 */
	function c_pageNeed() {
		$this->view ( 'need-list' );
	}
	
	/**
	 * ��ת��PMC��ⵥ�б�
	 */
	function c_pagePmcRkd() {
		$this->view ( 'pmc-rkdlist' );
	}

	/**
	 * ��ת����������Tab
	 */
	function c_toMyTab() {
		$this->view ( 'mylist-tab' );
	}

	/**
	 * ��ת���ҵ����������б�
	 */
	function c_toMyTaskList() {
		$taskType = isset ( $_GET ['taskType'] ) ? $_GET ['taskType'] : "";
		$this->assign ( "taskType", $taskType );
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'mylist' );
	}

	/**
	 * ��ת�����뵥�������������б�
	 */
	function c_toPageApply() {
		$this->assign("applyDocId" ,$_GET['applyDocId']);
		$this->view ( 'list-apply' );
	}

	/**
	 * ��ת�������ƻ��б�(��������-�����ƻ�tab)
	 */
	function c_pagePlan() {
		$this->view('list-plan');
	}

	/**
	 * ��ת��������������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת�������������ҳ��
	 */
	function c_toChange() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$taskactorDao = new model_produce_task_taskactor ();
		$actorObj = $taskactorDao->getActorsByTaskId ( $_GET ['id'] );
		$this->assign ( "actorNames", $actorObj ['actorNames'] );
		$this->assign ( "actorIds", $actorObj ['actorIds'] );
		$this->view ( 'change' );
	}

	/**
	 * �������
	 */
	function c_change() {
		if ($this->service->change_d ( $_POST [$this->objName] )) {
			msg ( "����ɹ�!" );
		}
	}

	/**
	 * ��ת���༭��������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$taskactorDao = new model_produce_task_taskactor ();
		$actorObj = $taskactorDao->getActorsByTaskId ( $_GET ['id'] );
		$this->assign ( "actorNames", $actorObj ['actorNames'] );
		$this->assign ( "actorIds", $actorObj ['actorIds'] );
		$this->view ( 'edit' );
	}

	/**
	 * ��ת���鿴��������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //��ʾ��ͬ����Դ��
		$this->assign('docStatus',$this->service->getStatusVal_d($obj['docStatus']));
		$this->assign('isFirstInspection',$obj['isFirstInspection'] == '1' ? '��' : '��');
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴����������Ϣҳ��
	 */
	function c_toViewTab() {
		$this->assign ( "id", $_GET ['id'] );
		$relDocId = $_GET ['relDocId'];
		if(!isset($relDocId)){
			$obj = $this->service->get_d($_GET ['id']);
			$relDocId = $obj['relDocId'];
		}
		$this->assign ( "relDocId", $relDocId );
		$this->view ( 'view-tab' );
	}

	/**
	 * ��ת���´�����ҳ��
	 */
	function c_toIssued() {
		$this->permCheck (); //��ȫУ��
		$applyDao = new model_produce_apply_produceapply ();
		$applyObj = $applyDao->get_d ( $_GET ['applyId'] );
		foreach ( $applyObj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtIssued ( $applyObj ) );
		$this->assign ( "docDate", date ( "Y-m-d" ) );
		$this->view ( 'issued' );
	}

	/**
	 * ��ת�����Ȼ㱨ҳ��
	 */
	function c_toReport() {
		//$this->permCheck (); //��ȫУ��
		$service = $this->service;
		$obj = $service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "docDate", date ( "Y-m-d" ) );
		$this->view ( 'report' );
	}

	/**
	 * ������Ȼ㱨��Ϣ
	 */
	function c_report() {
		if ($this->service->report ( $_POST [$this->objName] )) {
			msg ( '����ɹ���' );
		} else {
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ������������Json
	 */
	function c_personPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		// $service->searchArr ['userId'] = $_SESSION ['USER_ID'];
		$rows = $service->page_d ();

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ������������
	 */
	function c_receiveTask() {
		$service = $this->service;
		$obj = array(
			"id" => $_POST ['id']
			,"docStatus" => '1'
			,"recipient" => $_SESSION['USERNAME']
			,"recipientId" => $_SESSION['USER_ID']
			,"recipientDate" => date('Y-m-d')
		);
		if ($service->updateById($obj)) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * �ر���������
	 */
	function c_closedTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "3" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * ������������
	 */
	function c_openTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "1" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * �����������
	 */
	function c_finishTask() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "2" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * ��ת���´���������ҳ��
	 */
	function c_toAddByNeed() {
		$this->permCheck (); //��ȫУ��
		$applyDao = new model_produce_apply_produceapply();
		$applyObj = $applyDao->get_d( $_GET['applyId'] );
		$showRelDoc = $applyDao->getShowRelDoc_d($applyObj['relDocTypeCode']);
		$this->assign('showRelDoc' ,$showRelDoc); //��ʾ��ͬ����Դ��
		//��ͬ������
		if($showRelDoc == '��ͬ'){
			$contractCode = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toViewTab&id=" .
			$applyObj['relDocId'] .'",1,'.$applyObj['relDocId'].")'>" . $applyObj['relDocCode'] . "</a>";
		}
		$this->assign("contractCode" ,isset($contractCode) ? $contractCode : $applyObj['relDocCode']);
		//���뵥��ų�����
		$urlStr = 'toViewTab';//Ĭ�Ϸ���
		if($applyObj['docStatus'] == '8'){//���������
			$urlStr .= '&noSee=true';
		}
		$this->assign("applyDocCode" ,
			"<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_apply_produceapply&action=" . $urlStr . 
			"&id=" . $applyObj['id'] .'",1,'.$applyObj['id'].")'>" . $applyObj['docCode'] . "</a>");
		foreach ($applyObj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->showDatadicts(array("purposeCode" => 'SCYT')); //��;
		$this->showDatadicts(array("technologyCode" => 'SCGY')); //����
		$this->assign("docUserName" ,$_SESSION['USERNAME']); //�µ���
		$this->assign("docUserId" ,$_SESSION['USER_ID']); //�µ���ID
		$this->assign("docDate" ,date("Y-m-d")); //�µ�����
		$this->assign("proType" ,$_GET['proType']); //��������
		$this->assign("proTypeId" ,$_GET['proTypeId']); //��������id
		$this->assign("applyDocItemId" ,$_GET['applyItemIds']); //������ϸid
		$this->assign("taskTypeCode" ,isset($_GET['taskTypeCode']) ? $_GET['taskTypeCode'] : 'RWLX-SCRW'); //�������ͣ�Ĭ��Ϊ��������
		$this->assign("taskTypeName" ,isset($_GET['taskTypeName']) ? $_GET['taskTypeName'] : '��������');

		$this->view('add-need' ,true);
	}

	/**
	 * �´���������
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->add_d($_POST[$this->objName])) {
			msg ( '�´�ɹ���' );
		} else {
			msg ( '�´�ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���������񵼳�ҳ��
	 */
	function c_toExcelOut() {
		$this->view('excelout');
	}

	/**
	 * ��ת�����ϻ���ҳ��
	 */
	function c_pageProduct() {
		$this->view('list-product');
	}

	/**
	 * ��ȡ���ϻ�������ת��Json
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.productId';
		$rows = $service->page_d('select_product_config');
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach ($rows as $key => $val) {
				$rows[$key]['inventory'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���鿴���ϻ�����Ϣҳ��
	 */
	function c_toViewProduct() {
		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->get_d($_GET['productId']);
		$this->assignFunc($productObj);
		$this->view('view-product');
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonProduct() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_product_view');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ����excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['docCode'])) //���ݱ��
			$this->service->searchArr['docCode'] = $formData['docCode'];
		if(!empty($formData['fileNo'])) //�ļ����
			$this->service->searchArr['fileNo'] = $formData['fileNo'];

		if(!empty($formData['productCode'])) //���ϱ��
			$this->service->searchArr['productCode'] = $formData['productCode'];
		if(!empty($formData['productName'])) //��������
			$this->service->searchArr['productName'] = $formData['productName'];

		if(!empty($formData['customerName'])) //�ͻ�����
			$this->service->searchArr['customerName'] = $formData['customerName'];
		if(!empty($formData['relDocCode'])) //��ͬ���
			$this->service->searchArr['relDocCode'] = $formData['relDocCode'];

		if(!empty($formData['productionBatch'])) //��������
			$this->service->searchArr['productionBatch'] = $formData['productionBatch'];
		if(!empty($formData['saleUserName'])) //���۴���
			$this->service->searchArr['saleUserName'] = $formData['saleUserName'];

		if(!empty($formData['docUserName'])) //�µ���
			$this->service->searchArr['docUserName'] = $formData['docUserName'];
		if(!empty($formData['docDate'])) //�µ�����
			$this->service->searchArr['docDateSea'] = $formData['docDate'];

		if(!empty($formData['recipient'])) //������
			$this->service->searchArr['recipient'] = $formData['recipient'];
		if(!empty($formData['recipientDate'])) //��������
			$this->service->searchArr['recipientDate'] = $formData['recipientDate'];

		if(!empty($formData['docStatus'])) { //����״̬
			$docStatus = implode(',' ,$formData['docStatus']);
			$this->service->searchArr['docStatusIn'] = $docStatus;
		}

		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['docCode'] = $v['docCode'];
			$rowData[$k]['docStatus'] = $this->service->getStatusVal_d($v['docStatus']);
			$rowData[$k]['productCode'] = $v['productCode'];
			$rowData[$k]['productName'] = $v['productName'];
			$rowData[$k]['taskNum'] = $v['taskNum'];
			$rowData[$k]['purpose'] = $v['purpose'];
			$rowData[$k]['technology'] = $v['technology'];
			$rowData[$k]['fileNo'] = $v['fileNo'];
			$rowData[$k]['customerName'] = $v['customerName'];
			$rowData[$k]['relDocCode'] = $v['relDocCode'];
			$rowData[$k]['productionBatch'] = $v['productionBatch'];
			$rowData[$k]['docUserName'] = $v['docUserName'];
			$rowData[$k]['docDate'] = $v['docDate'];
			$rowData[$k]['recipient'] = $v['recipient'];
			$rowData[$k]['recipientDate'] = $v['recipientDate'];
			$rowData[$k]['saleUserName'] = $v['saleUserName'];
			$rowData[$k]['remark'] = $v['remark'];
		}

		$colArr  = array();
		$modelName = '����-����������Ϣ';
		return model_produce_basic_produceExcelUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	function c_product() {
		$datas = $this->service->get_product( $_GET['id'] , $_GET['code'] );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $datas );
		echo util_jsonUtil::encode ( $rows );
	}

	function c_templateConf(){
		$datas = $this->service->get_templateConf( $_POST['id'] );
		echo util_jsonUtil::encode ( $datas );

	}

	/**
	 * ��ת�����ϻ��ܼ���ҳ��
	 */
	function c_toStatisticsProduct() {
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,null ,true);  //���ȼ�
		$this->assign('idStr', $_GET['idStr']);
		$this->view('statistics-product' ,true);
	}

	/**
	 * ��ת�����ϼ���
	 */
	function c_statistics() {
		$this->assign('idStr', $_GET['idStr']);
		$this->assign('codeStr', $_GET['codeStr']);
		$this->view('statistics' ,true);
	}

	/**
	 * ���ϼ���ҳ��Json
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

				$produce[$val['productCode']]['JSBC'] = $numArr['JSBC']; //���豸������
				$produce[$val['productCode']]['KCSP'] = $numArr['KCSP']; //�����Ʒ������
				//				$produce[$val['productCode']]['SCC']  = $numArr['SCC']; //����������
				$stockoutNum = ( $numArr['JSBC'] + $numArr['KCSP']) - $produce[$val['productCode']]['number'];
				$produce[$val['productCode']]['stockoutNum'] = ($stockoutNum>=0) ? 0 : abs($stockoutNum);

			}
		}
		//��������
		sort($produce);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $produce );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	function c_codelistJson(){
		$datas = $this->service->get_produceTask($_REQUEST['productCode'],$_REQUEST['ids']);
		$pickDao = new model_produce_plan_picking();
		foreach($datas as $key => $val){
			$produce[$key]['id'] = $val['taskId'];//����id
			$produce[$key]['taskCode'] = $val['docCode'];//���񵥺�
			$produce[$key]['relDocCode'] = $val['relDocCode'];
			$produce[$key]['productionBatch'] = $val['productionBatch'];
			$produce[$key]['number'] = $val['num'];
			$produce[$key]['productId'] = $val['productId'];
			$produce[$key]['productCode'] = $val['productCode'];

			$numArr = $pickDao->getProductNum_d($val['productCode']);
			$produce[$key]['JSBC'] = $numArr['JSBC']; //���豸������
			$produce[$key]['KCSP'] = $numArr['KCSP']; //�����Ʒ������

			$stockoutNum = ( $numArr['JSBC'] + $numArr['KCSP']) - $produce[$key]['number'];
			$produce[$key]['stockoutNum'] = ($stockoutNum>=0)?0:abs($stockoutNum);
		}

		$arr ['collection'] = $produce;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת�����ȷ��ҳ��
	 */
	function c_toMark() {
		$service = $this->service;
		$this->assign('productCodes', $_GET['productCodes']);
		$this->assign('taskIds', $_GET['taskIds']);
		$this->view('mark');
	}

	/**
	 * ���ȷ��
	 */
	function c_mark() {
		if ($this->service->mark_d($_POST[$this->objName])) {
			msg ( '��ǳɹ���' );
		} else {
			msg ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * ���ϼ���-��������
	 */
	function c_isMeetProduction() {
		if($this->service->isMeetProduction_d($_POST['productCodes'],$_POST['taskIds'])){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * ���ϼ���-����������
	 */
	function c_isNotMeetProduction() {
		if($this->service->isNotMeetProduction_d($_POST['productCodes'],$_POST['taskIds'])){
			echo 1;
		}else{
			echo 0;
		}
	}
}