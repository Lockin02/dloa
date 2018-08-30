<?php
/**
 * @author Michael
 * @Date 2014��8��29�� 14:40:34
 * @version 1.0
 * @description:�����ƻ����Ʋ�
 */
class controller_produce_plan_produceplan extends controller_base_action {

	function __construct() {
		$this->objName = "produceplan";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * ��ת�������ƻ��б�
	 */
	function c_page() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 * �����Ƿ���Ҫ��ʾ���ѽ���������뵫δд����ȷ�����
	 * 0����ʾ��ִ���л�δ�����ʼ죨δ��������1����ʾ����������2����ʾ������ȫ��������ʾ��
	 */
	function c_pageJsonFeedback() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();

		if (is_array($rows)) {
			$feedbackDao = new model_produce_plan_planfeedback();
			$processDao = new model_produce_plan_planprocess();
			foreach ($rows as $key => $val) {
				if ($val['docStatus'] == 1 && $val['qualityNum'] > 0) { //ִ�������������ʼ�
					$conditions = " planId='$val[id]' GROUP BY process,processName ";
					$feedbackObjs = $feedbackDao->findAll($conditions);
					$feedbackNum = count($feedbackObjs);
					$processNum = $processDao->findCount(array('planId' => $val['id']));
					$rows[$key]['feedbackNum'] = $feedbackNum;
					$rows[$key]['processNum'] = $processNum;
					if ($feedbackNum == $processNum) {
						$rows[$key]['feedbackState'] = 1;
					} else {
						$rows[$key]['feedbackState'] = 2;
					}
				} else {
					$rows[$key]['feedbackState'] = 0;
				}
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
	 * ��ת�������ƻ�tabҳ��
	 */
	function c_pageTab() {
		$this->view('list-tab');
	}

	/**
	 * ��ת�������ƻ�tabҳ��(PMC)
	 */
	function c_pmcTab() {
		$this->view('pmc-tab');
	}

	/**
	 * ��ת�������ƻ��б�(PMC)
	 */
	function c_pagePmc() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list-pmc');
	}

	/**
	 * ��ת�������ƻ�tabҳ��(��������)
	 */
	function c_manageTab() {
		$this->view('manage-tab');
	}

	/**
	 * ��ת�������ƻ��б�(��������)
	 */
	function c_pageManage() {
		$this->assign('finish' ,isset($_GET['planType']) ? 'yes' : 'no');
		$this->view('list-manage');
	}

	/**
	 * ��ת�������ƻ��б�������������ʾ��
	 */
	function c_pageTask() {
		$this->assign('taskId' ,isset($_GET['taskId']) ? $_GET['taskId'] : '');
		$this->view('list-task');
	}

	/**
	 * ��ת�������ƻ��б�
	 */
	function c_weekPage() {
		$applyDao = new model_produce_apply_produceapply();
		$weekDate = $applyDao->getWeekDate_d();
		$this->assign('startWeekDate' ,$weekDate['startDate']);
		$this->assign('endWeekDate' ,$weekDate['endDate']);
		$this->view ( 'list-week' );
	}

	/**
	 * ��ת��������������ƻ��б�
	 */
	function c_toPageApply() {
		$this->assign("applyDocId" ,$_GET['applyDocId']);
		$this->view ( 'list-apply' );
	}

	/**
	 * ��ת�����������ƻ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת�����������ƻ�ҳ��
	 */
	function c_toAddByTask() {
		$this->permCheck (); //��ȫУ��

		$taskDao = new model_produce_task_producetask();
		$taskObj = $taskDao->get_d($_GET['taskId']);
		$this->assignFunc($taskObj);

//		$planRows = $this->service->findAll(array('taskId' => $_GET['taskId']));
//		$planNum = 0;
//		if (is_array($planRows)) {
//			foreach ($planRows as $key => $val) {
//				$planNum += $val['planNum']; //�ۼ����ƶ��ƻ�������
//			}
//		}
//		$planNumNow = $taskObj['taskNum'] - $planNum;
//		$this->assign('planNum' ,$planNumNow > 0 ? $planNumNow : 0);

		$outDao = new model_stock_outplan_outplan();
		$outProDao = new model_stock_outplan_outplanProduct();
		$outProObj = $outProDao->find(
			array(
				"docType" => 'oa_contract_contract' ,
				"docId" => $taskObj["relDocId"] ,
				"productId" => $taskObj["productId"]
			),
			' mainId DESC '
		);
		if ($outProObj) {
			$outObj = $outDao->get_d($outProObj["mainId"]);
		}
		$this->assign("planEndDate" ,$outObj["shipPlanDate"]); //��д�������ڵ��ƻ�����ʱ��

		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ')); //���ȼ�
		$this->assign('today' ,date('Y-m-d'));
		$this->view('add-task' ,true);
	}

	/**
	 * �ƶ��ƻ�
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->add_d($_POST[$this->objName])) {
			msg ( '�ƶ��ɹ���' );
		} else {
			msg ( '�ƶ�ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���༭�����ƻ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,$obj['urgentLevelCode']); //���ȼ�
		$this->view('edit' ,true);
	}

	/**
	 * ����ƻ�
	 */
	function c_edit() {
//		echo "<pre>";
//		print_R($_POST[$this->objName]);
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->edit_d($_POST[$this->objName])) {
			msg ( '����ɹ���' );
		} else {
			msg ( '���ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴�����ƻ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //��ʾ��ͬ����Դ��
		$this->view ( 'view' );
	}

		/**
	 * ��ת���鿴�����ƻ�ҳ��
	 */
	function c_toCloseView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'close-view' );
	}

	/**
	 * ��ת���鿴�����ƻ�tabҳ��
	 */
	function c_toViewTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign('id' ,$_GET ['id']);
		$this->view('view-tab');
	}

	/**
	 * ��ת��ȷ������ҳ��
	 */
	function c_toSureProcess() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('proSureName' ,$_SESSION['USERNAME']);
		$this->assign('proSureId' ,$_SESSION['USER_ID']);
		$this->view('sureprocess' ,true);
	}

	/**
	 * ȷ������
	 */
	function c_sureProcess() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->sureProcess_d($_POST[$this->objName])) {
			msg ( 'ȷ���ɹ���' );
		} else {
			msg ( 'ȷ��ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת�����ȷ���ҳ��
	 */
	function c_toFeedback() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('feedbackName' ,$_SESSION['USERNAME']);
		$this->assign('feedbackId' ,$_SESSION['USER_ID']);
		$this->assign('feedbackDate' ,date('Y-m-d'));

		$this->view('feedback' ,true);
	}

	/**
	 * ���ȷ���
	 */
	function c_feedback() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->feedback_d($_POST[$this->objName])) {
			msg ( '�����ɹ���' );
		} else {
			msg ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���ʼ�����ҳ��
	 */
	function c_toAddQuality() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		$productDao = new model_stock_productinfo_productinfo();
//		$productObj = $productDao->get_d($obj['productId']);
//		$this->showDatadicts(array('checkType' => 'ZJFS') ,$productObj['checkType']);  //�ʼ췽ʽ

//		$qualityApplyNum = 0; //�ʼ���������
//		$qualityDao = new model_produce_quality_qualityapply();
//		$qualityObj = $qualityDao->findAll(array('relDocCode' => $obj['docCode'] ,'status' => '0'));
//		if (is_array($qualityObj)) {
//			$applyItemDao = new model_produce_quality_qualityapplyitem();
//			foreach ($qualityObj as $key => $val) {
//				$applyItemObj = $applyItemDao->find(array('mainId' => $val['id']));
//				if ($applyItemObj) {
//					$qualityApplyNum += $applyItemObj['qualityNum'];
//				}
//			}
//		}

		$planNum = $obj['planNum'] - $obj['qualifiedNum'] ; //����������=�ƻ�����-�ʼ�ϸ������-�ʼ���������
		$this->assign('qualifiedNumNew' ,$planNum > 0 ? $planNum : 0);
		$this->view('add-quality' ,true);
	}

	/**
	 * ��ת���ر�����ҳ��
	 */
	function c_toClose() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('close' ,true);
	}

	/**
	 * ��ת���ر�����ҳ��
	 */
	function c_close() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if($this->service->editSimple_d($_POST[$this->objName])) {
				succ_show('controller/produce/plan/ewf_close.php?actTo=ewfSelect&billId='.$_POST[$this->objName]["id"].'&billDept='.$_SESSION["DEPT_ID"]);
		} else {
				msg( '�ύʧ�ܣ�' );
		}
	}


	  /**
	   * ajax�ر������ƻ�
	   */
	   function c_toCancel(){
		   	try{
//		   		$this->permCheck($_POST['id']);
//			 	if( !isset($this->service->this_limit['�����ƻ�']) ){
//			 		echo 2;
//			 		return 0;
//			 	}
			  	$planId = $_POST['id'];
			 	$planInfo = $this->service->get_d($planId);

			  	$planArr = array(
			  		'id' => $planId,
			  		'isCancel' => '1',
			  		'docStatus' => '6'
			  	);
			  	$flag = $this->service->updateById( $planArr );
			  	if ($flag){
				  	$taskDao = new model_produce_task_producetask();
					$configDao = new model_produce_task_configproduct();
					$configDao ->updateById(array('id' => $planInfo['configId'] ,'planNum' => 0));
				  	$taskDao->dealDocStatus_d($planInfo['taskId']); //���������ƻ�����״̬

					$changeDao = new model_produce_plan_producechange();
					$changeArr = array(
						'createName'=>$_SESSION['USERNAME'],
						'createId'=>$_SESSION['USER_ID'],
						'planId'=>$planInfo['id'],
						'changeType'=>'cancel',
						'remark'=>$planInfo['remark'],
						'planCode'=>$planInfo['docCode'],
						'createTime'=>date('y-m-d h:i:s',time())
					);
					$changeDao->add_d($changeArr);
			  	}
				echo $flag;
		   	}catch( Exception $e ){
		   		echo 0;
		   	}
	   }


	/**
	 * �ʼ�����
	 */
	function c_addQuality() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$_POST[$this->objName]['qualifiedNum']=$_POST['qualifiedNumOld']+$_POST['qualifiedNumNew'];
		if ($this->service->editSimple_d($_POST[$this->objName])) {
			msg ( 'ȷ�ϳɹ���' );
		} else {
			msg ( 'ȷ��ʧ�ܣ�' );
		}
	}

	/**
	 * �����ƻ�����
	 */
	function c_toProduceplanReport(){
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		if($beginDate){//�ж��Ƿ����ѯ����
		 	$this->assign("beginDate",$beginDate);
		}else{
		 	$beginDate=date("Y-m")."-01";
		 	$this->assign("beginDate",$beginDate);
		}
		$this->display('produceplan');
	}

	/**
	 * �ر������ƻ�
	 */
	function c_closePlan() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "3" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * ��ת���������񵼳�ҳ��
	 */
	function c_toExcelOut() {
		$this->showDatadicts(array('urgentLevelCode' => 'SCJHYXJ') ,null ,true);  //���ȼ�
		$this->view('excelout' ,true);
	}

	/**
	 * ����excel
	 */
	function c_excelOut() {
		$this->checkSubmit();
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['docCode'])) //���ݱ��
			$this->service->searchArr['docCode'] = $formData['docCode'];
		if(!empty($formData['docDate'])) //��������
			$this->service->searchArr['docDate'] = $formData['docDate'];

		if(!empty($formData['productCode'])) //���ϱ��
			$this->service->searchArr['productCode'] = $formData['productCode'];
		if(!empty($formData['productName'])) //��������
			$this->service->searchArr['productName'] = $formData['productName'];

		if(!empty($formData['urgentLevelCode'])) //���ȼ�
			$this->service->searchArr['urgentLevelCode'] = $formData['urgentLevelCode'];
		if(!empty($formData['productionBatch'])) //��������
			$this->service->searchArr['productionBatch'] = $formData['productionBatch'];

		if(!empty($formData['taskCode'])) //����������
			$this->service->searchArr['taskCode'] = $formData['taskCode'];
		if(!empty($formData['relDocCode'])) //��ͬ���
			$this->service->searchArr['relDocCode'] = $formData['relDocCode'];

		if(!empty($formData['applyDocCode'])) //�������뵥��
			$this->service->searchArr['applyDocCode'] = $formData['applyDocCode'];
		if(!empty($formData['customerName'])) //�ͻ�����
			$this->service->searchArr['customerName'] = $formData['customerName'];

		if(!empty($formData['planStartDate'])) //�ƻ���ʼʱ��
			$this->service->searchArr['planStartDate'] = $formData['planStartDate'];
		if(!empty($formData['planEndDate'])) //�ƻ�����ʱ��
			$this->service->searchArr['planEndDate'] = $formData['planEndDate'];

		if(!empty($formData['chargeUserName'])) //������
			$this->service->searchArr['chargeUserName'] = $formData['chargeUserName'];
		if(!empty($formData['saleUserName'])) //���۴���
			$this->service->searchArr['saleUserName'] = $formData['saleUserName'];

		if(!empty($formData['deliveryDate'])) //��������
			$this->service->searchArr['deliveryDate'] = $formData['deliveryDate'];

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
			$rowData[$k]['urgentLevel'] = $v['urgentLevel'];
			$rowData[$k]['docDate'] = $v['docDate'];
			$rowData[$k]['proType'] = $v['proType'];
			$rowData[$k]['productName'] = $v['productName'];
			$rowData[$k]['productCode'] = $v['productCode'];
			$rowData[$k]['planNum'] = $v['planNum'];
			$rowData[$k]['qualifiedNum'] = $v['qualifiedNum'];
			$rowData[$k]['stockNum'] = $v['stockNum'];
			$rowData[$k]['taskCode'] = $v['taskCode'];
			$rowData[$k]['relDocCode'] = $v['relDocCode'];
			$rowData[$k]['applyDocCode'] = $v['applyDocCode'];
			$rowData[$k]['customerName'] = $v['customerName'];
			$rowData[$k]['productionBatch'] = $v['productionBatch'];
			$rowData[$k]['planStartDate'] = $v['planStartDate'];
			$rowData[$k]['planEndDate'] = $v['planEndDate'];
			$rowData[$k]['chargeUserName'] = $v['chargeUserName'];
			$rowData[$k]['saleUserName'] = $v['saleUserName'];
			$rowData[$k]['deliveryDate'] = $v['deliveryDate'];
			$rowData[$k]['remark'] = $v['remark'];
		}

		$colArr  = array();
		$modelName = '����-�����ƻ���Ϣ';
		return model_produce_basic_produceExcelUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * ��������excel
	 */
	function c_excelOutOne() {
		set_time_limit(0);

		$obj = $this->service->get_d($_GET['id']);

		if ($obj) {
			//����
			$taskDao = new model_produce_task_producetask();
			$taskObj = $taskDao->get_d($obj['taskId']);
			$obj['purpose'] = $taskObj['purpose']; //��;
			$obj['technology'] = $taskObj['technology']; //����
			$obj['salesExplain'] = $taskObj['salesExplain']; //����˵��

			$configDao = new model_produce_task_configproduct();
			$configObjs = $configDao->findAll(array('taskId' => $obj['taskId']));
			$obj['config'] = ''; //��������

			$taskconDao = new model_produce_task_taskconfig(); //���ñ�ͷ
			$conItemDao = new model_produce_task_taskconfigitem(); //��������

			foreach ($configObjs as $key => $val) {
				$obj['config'] .= ($key + 1)."��$val[productCode]\r\n"; //��������

				$obj['productInfo'][$key]['tableHead'] = $taskconDao->findAll(array('taskId' => $obj['taskId'] ,'configId' => $val['productId']) ,'id ASC');
				foreach ($obj['productInfo'][$key]['tableHead'] as $ke => $va) {
					$conItemObjs = $conItemDao->findAll(array('parentId' => $va['id'] ,'colCode' => $va['colCode']) ,'id ASC');
					foreach ($conItemObjs as $k => $v) {
						$obj['productInfo'][$key]['tableBody'][$k][$ke] = $v;
					}
				}
			}

			//����
			$processDao = new model_produce_plan_planprocess();
			$obj['process'] = $processDao->findAll(array('planId' => $obj['id']));

			return model_produce_basic_produceExcelUtil::exportProduce($obj);
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}
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
		$this->assign('planIdStr', $_GET['planIdStr']);
		$this->view('statistics' ,true);
	}

	function c_codelistJson(){
		$datas = $this->service->get_produceTask($_REQUEST['productCode'],$_REQUEST['ids'],$_REQUEST['planIds']);
		$pickDao = new model_produce_plan_picking();
		foreach($datas as $key => $val){
			$produce[$key]['id'] = $val['planId'];//�ƻ���id
			$produce[$key]['planCode'] = $val['planCode'];//�ƻ�����
			$produce[$key]['relDocCode'] = $val['relDocCode'];
			$produce[$key]['productionBatch'] = $val['productionBatch'];
			$produce[$key]['number'] = $val['num'];
			$produce[$key]['productId'] = $val['productId'];

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

	function c_classify(){
		$datas = $this->service->get_classify( $_POST['id'],$_POST['productId'],$_POST['productCode'] );
		echo util_jsonUtil::encode ( $datas );
	}

	/**
	 * pmc������������ʱ��ȡ�������ݷ���json(�����ƻ���)
	 */
	function c_classifyByPicking(){
		$service = $this->service;
		$taskId = $_POST['taskId'];
		$planId = $_POST['planId'];
		$productId = isset($_POST['productId']) ? $_POST['productId'] : null;
		$rs = $service->get_classify( $taskId , $productId);
		if(!empty($rs)){
			$planObj = $service->get_d($planId);
			$rows = array();
			// ֻ��ȡ��ǰ�ƻ���������
			foreach ($rs as $v){
				if($v['code'] == $planObj['productCode']){
					array_push($rows, $v);
				}
			}
			if(!empty($rows)){
				$pickingitemDao = new model_produce_plan_pickingitem();
				$rtArr = $pickingitemDao->getRowsByPlan_d($rows,$taskId,$planId,$productId);
				echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	/**
	 * pmc���Ʋ�������ʱ��ȡ�������ݷ���json
	 */
	function c_classifyByPickingPlus(){
		$service = $this->service;
		$taskId = $_POST['taskId'];
		$planId = $_POST['planId'];
		$productId = isset($_POST['productId']) ? $_POST['productId'] : null;
		$rs = $service->get_classify( $taskId , $productId);
		if(!empty($rs)){
			$planObj = $service->get_d($planId);
			$rows = array();
			// ֻ��ȡ��ǰ�ƻ���������
			foreach ($rs as $v){
				if($v['code'] == $planObj['productCode']){
					array_push($rows, $v);
					break;
				}
			}
			if(!empty($rows)){
				$pickingitemDao = new model_produce_plan_pickingitem();
				$rtArr = $pickingitemDao->getRowsByPlanPlus_d($rows,$taskId,$planId,$productId);
				echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
			}else{
				echo '';
			}
		}else{
			echo '';
		}
	}

	/**
	 * pmc������������ʱ��ȡ�������ݷ���json(����ƻ���)
	 */
	function c_classifyByPickingMulti(){
		if(empty($_POST['planId']) || empty($_POST['productId'])){
			echo '';
		}else{
			$planIdArr = explode(',', $_POST['planId']);
			$productIdArr = explode(',', $_POST['productId']);
			if(count($planIdArr) != count($productIdArr)){// �ƻ���id������id����һһ��Ӧ
				echo '';
			}else{
				$service = $this->service;
				$rows = array();
				foreach ($planIdArr as $k => $v){
					$rs = $service->find(array('id' => $v));
					if(!empty($rs)){
						$row = $service->get_classify( $rs['taskId'] , $productIdArr[$k]);
						if(!empty($row)){
							$val = $row[0];
							$val['relDocId'] = $rs['relDocId'];
							$val['relDocCode'] = $rs['relDocCode'];
							$val['relDocName'] = $rs['relDocName'];
							$val['relDocType'] = $rs['relDocType'];
							$val['relDocTypeCode'] = $rs['relDocTypeCode'];
							$val['applyDocId'] = $rs['applyDocId'];
							$val['applyDocItemId'] = $rs['applyDocItemId'];
							$val['taskId'] = $rs['taskId'];
							$val['taskCode'] = $rs['taskCode'];
							$val['planId'] = $rs['id'];
							$val['planCode'] = $rs['docCode'];
							$val['productionBatch'] = $rs['productionBatch'];
							$val['customerId'] = $rs['customerId'];
							$val['customerName'] = $rs['customerName'];
							array_push($rows, $val);
						}
					}
				}
				if(!empty($rows)){
					$pickingitemDao = new model_produce_plan_pickingitem();
					$rtArr = $pickingitemDao->getRowsByPlanMutil_d($rows);
					echo !empty($rtArr) ? util_jsonUtil::encode ( $rtArr ) : '';
				}else{
					echo '';
				}
			}
		}
	}

	/**
	 * pmc�������֪ͨ��ʱ��ȡ�������ݷ���json
	 */
	function c_listJsonInnotice() {
		$rows = $this->service->listJsonInnotice_d ($_POST['id']);
		if(!empty($rows)){
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );
			echo util_jsonUtil::encode ( $rows );
		}else{
			echo '';
		}
	}

	/**
	 * �´���������ʱ��֤
	 */
	function c_pickCheck() {
		echo util_jsonUtil::encode($this->service->pickCheck_d($_POST['productCodes'],$_POST['taskIds']));
	}

	/**
	 * �´���������ʱ����
	 */
	function c_pickDeal() {
		echo util_jsonUtil::encode($this->service->pickDeal_d($_POST['productCodes'],$_POST['taskIds']));
	}

	/**
	 * ȷ������
	 */
	function c_meetPick() {
		if($this->service->meetPick_d ( $_POST ['ids'] )){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		if(empty($_REQUEST['ids'])){
			unset($_REQUEST['ids']);
		}
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * ��ת���޸�����ģ��
	 */
	function c_toEditClassify() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$applyDao = new model_produce_apply_produceapply();
		$this->assign('showRelDoc' ,$applyDao->getShowRelDoc_d($obj['relDocTypeCode'])); //��ʾ��ͬ����Դ��
		$this->view('editclassify');
	}
	
	/**
	 * �޸�����ģ��
	 */
	function c_editClassify() {
		if ($this->service->editClassify_d($_POST[$this->objName])) {
			msg('�޸ĳɹ�');
		}else{
			msg('�޸�ʧ��');
		}
	}
	
	/**
	 * �����Ѵ�ӡ����
	 */
	function c_changePrintCountId() {
		echo $this->service->changePrintCountId_d($_POST['id']);
	}
 }