<?php
/**
 * �ɹ��������������
 */
class controller_purchase_task_basic extends controller_base_action {

	function __construct() {
		$this->objName = "basic";
		$this->objPath = "purchase_task";
		parent::__construct ();
	}


/*****************************************��ʾҳ�濪ʼ********************************************/
	/**
	 * ���ʲ��ɹ��������ɲɹ�����
	 *
	 */
	 function c_toAddTask(){
	 	$applyId=isset($_GET['id'])?$_GET['id']:null;
	 	$applyItemDao=new model_asset_purchase_apply_applyItem();
	 	//��ȡ�ɹ�������Ϣ
	 	$applyItemRow=$applyItemDao->getItemByApplyId($applyId,'1');
		$this->assign('sendTime',date("Y-m-d"));
		//��ʾ����ģ��
		$taskEquDao=new model_purchase_task_equipment();
		$list=$taskEquDao->showAddTask_s($applyItemRow);
		$this->assign('list',$list);
	 	$this->display('apply-task-add');
	 }


	/**
	 * ���ʲ��ɹ��������ɲɹ�����
	 *
	 */
	 function c_toAddTaskByIds(){
	 	$idArr=isset($_GET['idArr'])?$_GET['idArr']:null;
	 	$applyItemDao=new model_asset_purchase_apply_applyItem();
	 	//��ȡ�ɹ�������Ϣ
	 	$applyItemRow=$applyItemDao->getItemByIdArr($idArr);
		$this->assign('sendTime',date("Y-m-d"));
		//��ʾ����ģ��
		$taskEquDao=new model_purchase_task_equipment();
		$list=$taskEquDao->showAddTask_s($applyItemRow);
		$this->assign('list',$list);
	 	$this->display('apply-task-add');
	 }

	/**
	 * �ҵ��ѹر�����
	 */
	function myCloseList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		//��ҳ
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('allCheckBox', '');
		$this->show->assign('list', $this->service->showMyCloselist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**
	 * @exclude ���֪ͨ�б�
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����08:46:13
	 */
	function changeNotice ($clickNumb) {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$searchArr['state'] = $this->service->stateToSta("change");
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();

		//��ҳ
		$showpage->show_page(array (
			'total' => $service->count,
			'perpage' => pagenum
		));
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('list', $this->service->showChangeNotice($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-changeNotice');
		unset($this->show);
	}

	/*
	 * @desription ��ת���ɹ������Tabҳ
	 * @param tags
	 * @author qian
	 * @date 2010-12-15 ����02:48:43
	 */
	function c_taskList () {
		$this->show->display($this->objPath . '_' .$this->objName . '-tab' );
	}

	/**
	 * ִ��������
	 */
	function c_executionList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr = array (
			"state" => $this->service->statusDao->statusEtoK('execute'),
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
//		$service->__SET('groupBy', "Id");
//		$service->__SET('sort', "id");
//		$service->__SET('asc', false);
		$service->__SET('searchArr', $searchArr);
		$service->sort = "dateReceive";
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListExecution');
		$this->show->assign('allCheckBox', '<input type="checkbox">');
		$this->show->assign('list', $this->service->showExecutionlist_s($rows));
		$this->show->assign('idsArry', $idsArry);
		$this->show->assign('btnGo', '&nbsp;<input type="button" class="txt_btn_a" value="���ɲɹ�ѯ�۵�" onclick="purchForm()"/>');
		$this->show->assign('btnOrder', '&nbsp;<input type="button" class="txt_btn_a" value="�´�ɹ�����" onclick="addOrder()"/>');
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * �ѹر�����
	 */
	function c_closeList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr = array (
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		//��ҳ
		$this->pageShowAssign();
//		$showpage->show_page(array (
//			'total' => $service->count,
//			'perpage' => pagenum
//		));
//		$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'closeList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('allCheckBox', '');
		//TODO:��չ �ѹرղɹ�����
		//$this->show->assign('list', $this->showMyCloselist($rows, $showpage));
		$this->show->assign('list',  $this->service->showExecutionlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-close-list');
		unset($this->show);
		unset($service);
	}

	/*
	 * @desription �ҷ���Ĳɹ��������-�豸�б�
	 * @param tags
	 * @author qian
	 * @date 2011-1-12 ����10:06:01
	 */
	function c_taskAssignList () {
		$this->display('tab-mytask');
	}

	/**
	 * �ҷ���Ĵ���������
	 */
	function c_waitAssignList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		//"createName" => $_SESSION['USERNAME'],
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta('begin'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'waitAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		$this->show->assign('list', $this->service->showWaitlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * �ҷ����ִ��������
	 */
	function c_executionAssignList(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"state" => $this->service->stateToSta('execute'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'executionAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		//TODO:��չ�ɹ����� �ҷ����ִ������
		$this->show->assign('list',  $this->service->showAllotExecutionlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**���ݲɹ��ƻ�ID��������صĲɹ�������ʾ�б�.
	*author can
	*2011-2-14
	*/
	function c_readTaskByPlanId() {
		$planId=isset($_GET['basicId'])?$_GET['basicId']:'';
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$planNumb = isset( $_GET['basicNum'] )?$_GET['basicNum']:"";
		//���ݼƻ�ID,���������豸
		$taskEquDao=new model_purchase_task_equipment();
		$EquRows=$taskEquDao->findByPlanId($planId);
		if(is_array($EquRows)){     //�жϸòɹ��ƻ��Ƿ�����й����Ĳɹ�����
			$taskIds=array();
			foreach($EquRows as $key=>$val){
				$taskIds[]=$val['basicId'];
			}
			//ȥ��ֵ�ظ���Ԫ��
			$taskIds=array_unique($taskIds);
			$searchArr=array(
	//			"createId" => $_SESSION['USER_ID'],
				"ids"=>$taskIds
			);
		}else{
			$searchArr=array(
				"ids"=>""
			);
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->assign('allCheckBox', '');
		$this->assign('planNum', $planNumb);
		$this->assign('list',  $this->service->showAllotExecutionlist($rows, $showpage));
		$this->display('changetask');
	}

	/**
	 * �ҷ�����ѹر�����
	 */
	function c_closeAssignList($clickNumb){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$searchArr = array (
			"createId" => $_SESSION['USER_ID'],
			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr['taskNumb'] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "Id");
		$showpage = new includes_class_page();
		$rows = $service->pageList_d();
		$this->pageShowAssign();
		//$this->show->assign('clickNumb', $clickNumb);
		$this->show->assign('action', 'closeAssignList');
		$this->show->assign('taskNumb', $taskNumb);
		$this->show->assign('allCheckBox', '');
		//TODO:��չ�ɹ����� �ҷ���Ĺر�����
		//$this->show->assign('list', $this->showMyCloselist($rows, $showpage));
		$this->show->assign('list',  $this->service->showWaitlist($rows, $showpage));
		$this->show->display($this->objPath . '_' . $this->objName . '-assign-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * �鿴����
	 */
	function c_read(){
		$this->permCheck ();//��ȫУ��
		$id = isset( $_GET['id'] )? $_GET['id'] : exit ;
		$arr = $this->service->readTask_d($id);
		$this->arrToShow($arr);
		$this->assign('skey',$_GET['skey']);
		$this->show->assign('list', $this->service->showTaskRead($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-read');
	}

	/**
	 * �����ɹ�����
	 *
	 */
	 function c_exportTask(){
		$id=isset($_GET['id'])?$_GET['id']:"";
		//��ȡ��������
		$row = $this->service->readTask_d($id);
		$dao = new model_purchase_contract_purchaseUtil ();
		return $dao->exportTask ( $row); //����Excel
	 }

	/**
	 * ���·���ɹ�����ҳ��
	 */
	function c_toAssignTask () {
		$this->permCheck ();//��ȫУ��
		$id = isset( $_GET['id'] )? $_GET['id'] : exit ;
		$arr = $this->service->readTask_d($id);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-assign');
	}

	/**
	 * ��ת�������ɹ����뵥ҳ��
	 */
	function c_toAdd() {
		$idsArry = isset($_GET['idsArry'])? substr( $_GET['idsArry'],1 ):exit;
		$this->service->getParam($_GET);
		$obj =  new model_purchase_task_equipment();
		$listEqu = $obj->getTaskEquAsEqu_d($idsArry);
		if($listEqu){
			$this->show->assign('userName', $_SESSION['USERNAME']);
			$this->show->assign('userId', $_SESSION['USER_ID']);
			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
			$this->show->assign('suppId', "0");
			$this->show->assign('list', $this->service->newApply($listEqu));

			$applyNumb = "pa-".date("YmdHis").rand(10,99);
			$this->show->assign('applyNumb', $applyNumb);
			$this->show->display($this->objPath . '_apply-new');
		}else {
			showmsg('����','temp','button');
		}
		unset($this->show);
	}

	/**
	 * @exclude ��ת����ɹ�����
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����03:52:20
	 */
	function c_toChange () {
		$this->permCheck ();//��ȫУ��
		$id = isset($_GET['id'])?$_GET['id']:exit;
		$numb = isset($_GET['numb'])?$_GET['numb']:exit;

		$arr = $this->service->toChange_d($id,$numb);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChange($arr["0"]["childArr"]) );
		$this->show->display($this->objPath . '_' . $this->objName . '-change');
	}

	/**
	 * @exclude �鿴���֪ͨ
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 ����09:54:20
	 */
	function c_readReceive () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$clickNumb = isset( $_GET['clickNumb'] )?$_GET['clickNumb']:1;
		$arrayPanel = array("numb"=>2,
								"clickNumb"=> $clickNumb,
								"name1"=>"�²ɹ�����",
								"title1"=>"�²ɹ�����",
								"url1"=>"?model=purchase_task_basic&action=readReceive&clickNumb=1&id=".$id."&numb=".$numb,
								"name2"=>"ԭ�ɹ�����",
								"title2"=>"ԭ�ɹ�����",
								"url2"=>"?model=purchase_task_basic&action=readReceive&clickNumb=2&id=".$id."&numb=".$numb
							);
		$topPlan = parent::topPlan($arrayPanel);
		$this->show->assign('topPlan', $topPlan);

		if($clickNumb == '1'){
			$this->changeReadNew($id,$numb);
		}
		else if($clickNumb == '2'){
			$this->changeReadOld($id,$numb);
		}
	}

	/**
	 * @exclude �鿴����µĲɹ�����
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����09:43:30
	 */
	function changeReadNew ($id,$numb) {
		$arr = $this->service->readTaskByBasicId_d($id);
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChangeRead($arr["0"]["childArr"]) );
		$this->show->assign('id', $id );
		$this->show->assign('numb', $numb );
		$this->show->display($this->objPath . '_' . $this->objName . '-changeRead');
	}

	/**
	 * @exclude �鿴�����ɵĲɹ�����
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����09:45:18
	 */
	function changeReadOld ($id,$numb) {
		$searchArr['state'] = $this->service->stateToSta('Locking');
		$searchArr['taskNumb'] = $numb;
		$searchArr['isUse'] = '1';
		$service = $this->service;
		$service->__SET('searchArr', $searchArr);
		$arr = $this->service->pageList_d();
		$this->arrToShow($arr);
		$this->show->assign('list', $this->service->showChangeRead($arr["0"]["childArr"]) );
		$this->show->assign('id', $id );
		$this->show->assign('numb', $numb );
		$this->show->display($this->objPath . '_' . $this->objName . '-changeRead');
	}

	/**
	 * ��ת�������ɹ�����ҳ��
	 */
	function c_toAddInquiry() {
		$idsArry = isset($_GET['idsArry'])? substr( $_GET['idsArry'],1 ):exit;
		$this->service->getParam($_GET);
		$taskEquDao =  new model_purchase_task_equipment();
		$listEqu = $taskEquDao->getTaskEquAsEqu_d($idsArry);
		if($listEqu){
			$this->show->assign('userName', $_SESSION['USERNAME']);
			$this->show->assign('userId', $_SESSION['USER_ID']);
			$this->show->assign('nowTime', date("Y-m-d H:i:s"));
			$this->show->assign('suppId', "0");
			$this->show->assign('list', $taskEquDao->newInquiry_s($listEqu));

			$applyNumb = "pa-".date("YmdHis").rand(10,99);
			$this->show->assign('applyNumb', $applyNumb);
			$this->show->display($this->objPath . '_inquiry-new');
		}else {
			showmsg('����','temp','button');
		}
		unset($this->show);
	}

	/**
	 * @desription �ҵĴ���������ҳ��
	 * @param tags
	 * @date 2010-12-23 ����02:20:15
	 */
	function c_taskMyListReceive () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:'';
		$searchArr['state'] = $this->service->statusDao->statusEtoK('begin');
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr['sendUserId'] = $_SESSION['USER_ID'];
		if($taskNumb!=''){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);

		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/
//		$service->__SET('groupBy', 'Id');
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListReceive');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showMyWaitlist_s( $rows ));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**
	 * δִ�еĲɹ������б�
	 */
	function c_waitList () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:'';
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr['state'] = $this->service->statusDao->statusEtoK('begin');
		if($taskNumb!=''){
			$searchArr[$searchCol] = $taskNumb;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', 'Id');
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListReceive');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showWaitlist_s( $rows ));
		$this->show->display($this->objPath . '_' . $this->objName . '-wait-list');
		unset($this->show);
		unset($service);
	}

	/**
	 * @desription �ҵ�ִ��������
	 * @param tags
	 * @date 2010-12-23 ����04:55:37
	 */
	function c_taskMyListExecution () {
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$idsArry = isset($_GET['idsArry'])? $_GET['idsArry']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
			"state" => $this->service->statusDao->statusEtoK('execute'),
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->sort = "dateReceive DESC,updateTime";
		$service->__SET('searchArr', $searchArr);
//		$service->__SET('groupBy', "Id");
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/

		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		//���б��ս���ʱ��͸���ʱ������������
//		if(is_array($rows)){
//			foreach($rows as $key=>$val){
//				$dateReceive[$key]=$val['dateReceive'];
//				$updateTime[$key]=$val['updateTime'];
//			}
//			array_multisort($dateReceive,SORT_DESC,$updateTime,SORT_DESC,$rows);
//		}
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListExecution');
		$this->show->assign('allCheckBox', '<input type="checkbox">');
		$this->show->assign('list', $this->service->showMyExecutionlist_s($rows));
		$this->show->assign('idsArry', $idsArry);
		$this->show->assign('btnGo', '&nbsp;<input type="button" class="txt_btn_a" value="���ɲɹ�ѯ�۵�" onclick="purchForm()"/>');
		$this->show->assign('btnOrder', '&nbsp;<input type="button" class="txt_btn_a" value="�´�ɹ�����" onclick="pushOrder()"/>');
		$this->show->display($this->objPath . '_' . $this->objName . '-list-mytask');
		unset($this->show);
		unset($service);
	}

	/**
	 * ��ת��ִ���е����ϻ���ҳ��
	 *
	 */
	 function c_toExeEquList(){
		$equNumb = isset( $_GET['equNumb'] )?$_GET['equNumb']:"";
		$equName = isset( $_GET['equName'] )?$_GET['equName']:"";
		$idsArry = isset( $_GET['idsArry'] )?$_GET['idsArry']:"";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$this->assign('purchType',$purchType);
		$searchArr = array();
		if($equNumb!=""){
			$searchArr['seachProductNumb'] = $equNumb;
		}
		if($equName!=""){
			$searchArr['productName'] = $equName;
		}
		if($purchType=="contract_sales"){
			$searchArr['purchTypeArr'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//�����òɹ�
			$searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//��ʾȫ���ɹ����͵����ϻ��ܱ�
			//$searchArr['purchTypeArr'] = "" ;
		}else{
			$searchArr['purchTypeArr']=$purchType;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.productId,p.productNumb");

//		$rows = $service->pageEqu_d();
		$this->pageShowAssign();

		$this->assign('equNumb', $equNumb);
		$this->assign('equName', $equName);
		$this->assign('idsArry', $idsArry);
		$this->assign ( 'purchType', $purchType );
//		$this->assign('list', $this->service->showEqulist_s($rows));
		$this->display('list-equ');
		unset($this->show);
		unset($service);
	 }

	/**
	 * �ҵĲɹ�����
	 */
	function c_taskMyList(){
		$this->show->display($this->objPath . '_' .$this->objName . '-myTop');
	}

	/**
	 * �ҵı���Ĳɹ������б�
	 */
	function c_taskMyListChange(){
		$this->show->display($this->objPath . '_' .$this->objName . '-my-change-list');
	}

	/**
	 * @description �ҵ��������б�
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['sendUserId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ("list_page_change");
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}


	/**�ҵ��ѹرղɹ������б�
	*author can
	*2011-1-5
	*/function c_taskMyListClose(){
		$taskNumb = isset( $_GET['taskNumb'] )?$_GET['taskNumb']:"";
		$clickNumb = isset( $_GET['clickNumb'] )?$_GET['clickNumb']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID'],
//			"stateInArr" => $this->service->stateToSta('end').",".$this->service->stateToSta('close'),
			"stateInArr" => $this->service->statusDao->statusEtoK('close').",".$this->service->statusDao->statusEtoK('end'),
//			"isUse" => '1'
		);
		if($taskNumb!=""){
			$searchArr[$searchCol] = $taskNumb;
		}

		$service = $this->service;
		$service->getParam($_GET);
		$service->sort = "dateFact";
		$service->__SET('searchArr', $searchArr);

		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/
//		$service->__SET('groupBy', "Id");
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->PageTask_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign();
		$this->show->assign('taskNumb', $taskNumb);
		$this->assign ( 'searchCol', $searchCol );
		$this->show->assign('action', 'taskMyListClose');
		$this->show->assign('allCheckBox', '');
		$this->show->assign('btnGo', '');
		$this->show->assign('btnOrder', '');
		$this->show->assign('list', $this->service->showMyCloselist($rows));
		$this->show->display($this->objPath . '_' . $this->objName . '-list-my');
		unset($this->show);
		unset($service);
	}

	/**��ת��������ҳ��
	 * @author suxc
	 *
	 */
	 function c_toFeedBack(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$row=$this->service->get_d($id);
		//��ȡ������Ϣ
		$arr = $this->service->readTask_d($id);
		//��ȡ�ɹ�������
		$applyManArr=$this->service->getApplyMan_d($arr["0"]["childArr"]);
		array_push($applyManArr['sendId'],$row['createId']);
		array_push($applyManArr['sendName'],$row['createName']);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('sendId',implode(',',array_unique($applyManArr['sendId'])));
		$this->assign('sendName',implode(',',array_unique($applyManArr['sendName'])));
		$this->show->assign('list', $this->service->showFeedbackList($arr["0"]["childArr"]) );
		$this->display('feedback');

	 }

	 /**��ת���ɹ�����ر�ҳ��
	 * @author Administrator
	 *
	 */
	 function c_toClose(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$type=isset( $_GET['type'] )?$_GET['type']:"";
		$row=$this->service->get_d($id);
		//��ȡ������Ϣ
		$arr = $this->service->readTask_d($id);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('type',$type);
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->display('close');

	 }

	 /**��ת���ɹ�����ر�ҳ��
	 * @author Administrator
	 *
	 */
	 function c_toCloseRead(){
		$id = isset( $_GET['id'] )?$_GET['id']:"";
		$readType=isset($_GET['actType'])?$_GET['actType']:"";
		$this->assign ('readType',$readType );
		$row=$this->service->get_d($id);
		//��ȡ������Ϣ
		$arr = $this->service->readTask_d($id);
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->show->assign('list', $this->service->showRead($arr["0"]["childArr"]) );
		$this->display('close-read');
	 }
	 /*
	  * �ɹ�������ʷ�۸���ϸ��Ϣ��ʾҳ��
	  */
	function c_detailPrice(){
		$productNumb = $_GET['num'] ;
		$createTime = $_GET['createTime'] ;
		$orderEquDao=new model_purchase_contract_equipment();
		$priceData=$orderEquDao->getHistoryInfo_d( $productNumb,$createTime);
		foreach($priceData as $key => $val){
			if($key =='applyPrice'){
				$val = sprintf("%.3f",$val);	//������λС��
			}
			$this->assign ( $key, $val );
		}
		$this->display('priceView');
	}
/*****************************************��ʾҳ�����********************************************/

/*****************************************ҵ����ʼ********************************************/

	/**
	 * @exclude ���ձ��
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 ����10:12:05
	 */
	function c_receiveChange () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$obj = $this->service->receiveChange_d($id,$numb);
		if($obj){
			msgGo("���ճɹ���","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
		else{
			msgGo("����ʧ�ܣ�","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
	}

	/**
	 * @exclude �˻ر��
	 * @author ouyang
	 * @param
	 * @version 2010-8-18 ����10:13:05
	 */
	function c_returnChange () {
		$id = isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = isset( $_GET['numb'] )?$_GET['numb']:exit;
		$obj = $this->service->returnChange_d($id,$numb);
		if($obj){
			msgGo("�˻سɹ���","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
		else{
			msgGo("�˻�ʧ�ܣ�","?model=purchase_task_basic&action=taskMyList&clickNumb=4");
		}
	}

	/**
	 * @desription �����ɹ�����������
	 * @param tags
	 * @return
	 * @date 2010-12-22 ����02:29:49
	 */
	function c_add() {
		$object = $this->service->add_d( $_POST [$this->objName] );
		if ($object) {
			msgBack2('�´�ɹ���');
		}else{
			msgBack2('�´�ʧ�ܣ�');
		}
	}

	/**
	 * @desription ���·���ɹ�����
	 */
	function c_edit() {
		$object = $this->service->edit_d( $_POST [$this->objName] ,true);
		if ($object) {
			msgGo("����ɹ���","?model=purchase_task_basic&action=waitList");
		}else{
			msgGo("����ɹ���","?model=purchase_task_basic&action=waitList");
		}
	}

	/**
	 * @desription ���ղɹ�����
	 * @param tags
	 * @date 2010-12-23 ����04:28:06
	 */
	function c_stateBegin(){
		$id = isset( $_GET['sid'] )?$_GET['sid']:exit;
		if( $this->service->stateBegin_d($id) ){
			msgGo('���ճɹ�');
		}else{
			msgGo('ʧ�ܣ��������Ƿ������������Ժ�����');
		}
	}

	/**
	 * @exclude �������
	 * @author ouyang
	 * @param
	 * @version 2010-8-17 ����06:23:57
	 */
	function c_change () {
		try {
			$id = $this->service->change_d ( $_POST ['basic'] );
			if($id){
				msgBack2("����ɹ���");
			}else{
				msgBack2("������ɹ���");
			}
		} catch ( Exception $e ) {
			msgBack2 ( "���ʧ�ܣ�ʧ��ԭ��" . $e->getMessage () );
		}
	}

	/**
	 * @exclude ��ɲɹ�����
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-10 ����07:40:19
	 */
	function c_end(){
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$url = "?model=purchase_task_basic&action=taskMyListExecution";
		$val = $this->service->end_d($id);
		if( $val==1 ){
			msgGo("�����ɹ�");
		}
		else if($val==2){
			msgGo("����δ��ɵ����ϣ��������");
		}
		else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

	 /**
	 * �������²ɹ������״̬Ϊ���
	 *
	 */
	 function c_updateStateEnd(){
		$flag = $this->service->updateData_d ();
		if($flag){
			echo "<script>alert('���³ɹ�');this.location='?model=purchase_task_basic&action=executionList'</script>";
		}else{
			echo "<script>alert('����ʧ��');this.location='?model=purchase_task_basic&action=executionList'</script>";
		}

	 }

	/**
	 * @exclude �رղɹ�����
	 * @param
	 * @version 2010-8-10 ����07:42:08
	 */
/*	function c_close () {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		$numb = $this->service->findNumbById_d($id);
		if( $this->service->close_d($numb) ){
			msgGo("�رճɹ�");
		}else{
			msgGo("�ر�ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}*/
	function c_close () {
		$row= $_POST [$this->objName] ;
//		echo "<pre>";
//		print_r($row);
		$flag = $this->service->edit_d($row);
		if($row['type']=="exelist"){
			echo "<script>this.location='controller/purchase/task/ewf_close2.php?actTo=ewfSelect&examCode=oa_purch_task_basic&billId=" . $row['id'] . "'</script>";
		}else{
			echo "<script>this.location='controller/purchase/task/ewf_close.php?actTo=ewfSelect&examCode=oa_purch_task_basic&billId=" . $row['id'] . "'</script>";
		}
//		if( $this->service->close_d($flag) ){
//			msgBack2("�ύ�ɹ�");
//		}else{
//			msgBack2("�ύʧ��");
//		}
	}

	/**
	 * @exclude ���������ɹ�����
	 * @param
	 */
	function c_startTask() {
		$id=isset( $_GET['id'] )?$_GET['id']:exit;
		if( $this->service->startTask_d($id) ){
			msgGo("�����ɹ�");
		}else{
			msgGo("����ʧ�ܣ��������Ƿ������������Ժ�����");
		}
	}

	/**
	 * �´�ɹ�ѯ�۵���ɹ�����ʱ���ж����ϵĲɹ������Ƿ�һ��
	 *
	 */
	 function c_isSameType(){
		$taskEquDao = new model_purchase_task_equipment();
	 	$idsArr=$_POST['ids'];
	 	$flag=$taskEquDao->isSameType_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

	 /**�ɹ�������
	 * @author Administrator
	 *
	 */
	 function c_feedback(){
		$flag = $this->service->feedback_d( $_POST [$this->objName] );
		if($flag){
			msgBack2("�ύ�ɹ���");
		}else{
			msgBack2("�ύʧ�ܣ�");
		}

	 }

	 /**
	 *�ж��Ƿ����ύ�ر�����
	 *
	 */
	 function c_isSubClose(){
	 	$id=$_POST['id'];
	 	$row=$this->service->get_d($id);
	 	if($row['ExaStatus']!='��������'){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }


/*****************************************ҵ�������********************************************/
}
?>
