<?php

/**
 * @author Show
 * @Date 2012��5��28�� ����һ 13:38:56
 * @version 1.0
 * @description:��Ա���ü�¼���Ʋ�
 */
class controller_hr_transfer_transfer extends controller_base_action {

	function __construct() {
		$this->objName = "transfer";
		$this->objPath = "hr_transfer";
		parent :: __construct();
	}

	/*
	 * ��ת����Ա���ü�¼�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * ��ת����Ա���ü�¼�б�--����
	 */
	function c_pageByPerson() {
		$this->assign('thisUser' ,$_SESSION['USERNAME'] );
		$this->assign('thisUserId' ,$_SESSION['USER_ID'] );
		$this->assign('userNo' ,$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/*
	 * ��ת����������(����)
	 */
	function c_toTransferManage(){
		$this->assign('deptId' ,$_SESSION['DEPT_ID']);
		$this->view('manage-list');
	}


	/**
	 * ��ת��Ա����������ҳ��
	 */
	function c_toAddJobTran(){
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$personDao = new model_hr_personnel_personnel();
		$row = $personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('thisUser' ,$row['staffName']);
		$this->assign('userNo' ,$row['userNo']);
		$this->assign('prePersonClassCode' ,$row['personnelClass']);
		$this->assign('prePersonClass' ,$row['personnelClassName']);
		$otherDao = new model_common_otherdatas();     //�½�otherdatas����
		$thisUserInfo = $otherDao->getUserInfoByUserNo($otherDao->getUserCard($_SESSION['USER_ID']));

		$date = date('Y-m-d');
		$this->assign('today' ,$date);
		$arr = $otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt = "";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
		}
		$area = new includes_class_global();
		$this->show->assign('areaOpt' ,$area->area_select());  //������������ӵ�select��ǩ
		$this->assign('companyOpt' ,$companyOpt);     //�����й�˾��ӵ�select��ǩ
		$this->view('jobtran-add' ,true);
	}

	/**
	 * Ա������������������
	 */
	function c_add(){
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if ($obj['status'] == '') {
			$obj['status'] = 0;
		}
		if($obj['isCompanyChange'] == 1) {
			$obj['transferTypeName'].="��˾�䶯  ";
		}
		if($obj['isAreaChange'] == 1) {
			$obj['transferTypeName'].="����䶯  ";
		}
		if($obj['isDeptChange'] == 1) {
			$obj['transferTypeName'].="���ű䶯 ";
		}
		if($obj['isJobChange'] == 1) {
			$obj['transferTypeName'].="ְλ�䶯 ";
		}
		if($obj['isClassChange'] == 1) {
			$obj['transferTypeName'].="��Ա����䶯 ";
		}

		$id = $this->service->add_d($obj);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$addType = isset ( $_GET ['addType'] ) ? $_GET ['addType'] : null;
		if($id) {
			if ("audit" == $actType) {//�ύ������
				$preDeptId = $_POST[$this->objName]['preBelongDeptId'];
				$afterDeptId = $_POST[$this->objName]['afterBelongDeptId'];
				$deptIds = $preDeptId.",".$afterDeptId;
				succ_show ( 'controller/hr/transfer/ewf_hr_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$deptIds);
			} else {
				if ($obj['status'] != 1) {
					msg('����ɹ�');
				} else {
					msg('�ύ�ɹ�');
				}
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת�����˵��������б�(���)
	 */
	function c_toJobTranList(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view('jobtran-list');
	}

	/**
	 * ��ת�����˵��������б�
	 */
	function c_toPersonJobTranList(){
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$personDao=new model_hr_personnel_personnel();
		$row=$personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('userNo',$row['userNo']);
		$this->view('personjobtran-list');
	}

	/**
	 * �鿴ҳ�� - ����Ȩ��
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->page_d ();

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//ת��������
				$rows[$key]['stateC'] = $service->statusDao->statusKtoC($rows[$key]['status']);
				if($rows[$key]['isCompanyChange'] == 0) {
					$rows[$key]['isCompanyChangeC'] = '��';
				} else {
					$rows[$key]['isCompanyChangeC'] = '��';
				}

				if($rows[$key]['isDeptChange'] == 0) {
					$rows[$key]['isDeptChangeC'] = '��';
				} else {
					$rows[$key]['isDeptChangeC'] = '��';
				}

				if($rows[$key]['isJobChange'] == 0) {
					$rows[$key]['isJobChangeC'] = '��';
				} else {
					$rows[$key]['isJobChangeC'] = '��';
				}

				if($rows[$key]['isAreaChange'] == 0) {
					$rows[$key]['isAreaChangeC'] = '��';
				} else {
					$rows[$key]['isAreaChangeC'] = '��';
				}

				if($rows[$key]['isClassChange'] == 0) {
					$rows[$key]['isClassChangeC'] = '��';
				} else {
					$rows[$key]['isClassChangeC'] = '��';
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//ϵͳȨ��
		$sysLimit = $this->service->this_limit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if(strstr($sysLimit,';;')){

			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			$_POST['deptIds'] = $sysLimit;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת��������Ա���ü�¼ҳ��
	 */
	function c_toAdd() {
		$this->assign('thisUserId' ,$_SESSION['USER_ID']);
		$personDao = new model_hr_personnel_personnel();
		$row = $personDao->getPersonnelSimpleInfo_d($_SESSION['USER_ID']);
		$this->assign('thisUser' ,$_SESSION['USERNAME']);
		$this->assign('prePersonClassCode' ,$row['personnelClass']);
		$this->assign('prePersonClass' ,$row['personnelClassName']);
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL'));
		$otherDao = new model_common_otherdatas();     //�½�otherdatas����
		$date = date(Y."-".m."-".d);
		$this->assign('today' ,$date);
		$arr = $otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt = "";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
		}
		$area = new includes_class_global();
		$this->show->assign('areaOpt' ,$area->area_select());  //������������ӵ�select��ǩ
		$this->assign('companyOpt' ,$companyOpt);     //�����й�˾��ӵ�select��ǩ
		$this->view('add' ,true);
	}



	/**
	 * ��ת���༭��Ա���ü�¼ҳ��
	 */
	function c_toEdit() {
//		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);

		$this->view('edit');
	}

	/**
	 * ��ת���༭��Ա���ü�¼ҳ��
	 */
	function c_toEditTran() {
//		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);

		$this->view('tran-edit');
	}

	/*
	 * �������Ա�����ڷ���
	 */
	function c_approval(){
		$this->checkSubmit();
		$_POST [$this->objName]['status']=4;
		$id = $this->service->edit_d($_POST [$this->objName]) ;
		if($id){
			if($_POST['type']=='hrmanager'){
				msgGo('ȷ�ϳɹ�',"?model=hr_transfer_transfer");
			}else{
				msgGo('ȷ�ϳɹ�',"?model=hr_transfer_transfer&action=toTransferManage");
			}
		}else{
			if($_POST['type']=='hrmanager'){
				msgGo('ȷ��ʧ��',"?model=hr_transfer_transfer");
			}else{
				msgGo('ȷ��ʧ��',"?model=hr_transfer_transfer&action=toTransferManage");
			}
		}
	}

	/**
	 * ��ת���༭Ա�����ڼ�¼ҳ��
	 */
	function c_toEditJobTran(){
	    $addType = isset ( $_GET ['addType'] ) ? $_GET ['addType'] : "";
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->assign('addType',$addType);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX'),$obj['transferType']);
		$otherDao = new model_common_otherdatas();
		$areaDao = new model_common_otherdatas();
		$arr = $otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt = "";
		$areaOpt = "";
		$company = $obj['afterUnitName'];
		$area = $obj['afterUseAreaName'];
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			if($arr[$i]['NameCN'] == $company) {
				$companyOpt = $companyOpt."<option selected value='$id'>".$arr[$i]['NameCN']."</option>";
			} else {
				$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
			}
		}
		$areaArr = $areaDao->getArea();
		foreach($areaArr as $k => $v){
			$id = $v['ID'];
			if($area == $v['Name']) {
				$areaOpt = $areaOpt."<option selected value='$id'>".$v['Name']."</option>";
			} else {
				$areaOpt = $areaOpt."<option value='$id'>".$v['Name']."</option>";
			}
		}
		$this->show->assign('areaOpt',$areaOpt);  //������������ӵ�select��ǩ
		$this->assign('companyOpt',$companyOpt);     //�����й�˾��ӵ�select��ǩ
		$this->view('jobtran-edit' ,true);
	}

	/**
	 * �༭Ա�����ڷ���
	 */
	function c_editJobTran(){
		$id = $this->service->edit_d($_POST [$this->objName]) ;
		if ($id) {
			msgGo('����ɹ�',"index1.php?model=hr_transfer_transfer&action=toJobTranList");
		}
	}

	/**
	 * Ա�����ڱ༭����
	 */
	function c_edit(){
		$this->checkSubmit();
		$obj = $_POST[$this->objName];
		if($obj['isCompanyChange'] == 1) {
			$obj['transferTypeName'].="��˾�䶯  ";
		}
		if($obj['isAreaChange'] == 1) {
			$obj['transferTypeName'].="����䶯  ";
		}
		if($obj['isDeptChange'] == 1) {
			$obj['transferTypeName'].="���ű䶯 ";
		}
		if($obj['isJobChange'] == 1) {
			$obj['transferTypeName'].="ְλ�䶯 ";
		}
		if($obj['isClassChange'] == 1) {
			$obj['transferTypeName'].="��Ա����䶯 ";
		}

		$id = $this->service->edit_d( $obj );
	    $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	    if($id){
			if ("audit" == $actType) {//�ύ������
				$preDeptId=$_POST[$this->objName]['preBelongDeptId'];
				$afterDeptId=$_POST[$this->objName]['afterBelongDeptId'];
				$deptIds=$preDeptId.",".$afterDeptId;
				if($_POST['addType'] == 'addEdit') {
					succ_show ( 'controller/hr/transfer/ewf_manager_index.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&billDept='.$deptIds);
				}else{
					succ_show ( 'controller/hr/transfer/ewf_manager_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&billDept='.$deptIds);
				}
			} else {
				if($_POST['addType'] == 'addEdit') {
					msgGo('����ɹ�',"?model=hr_transfer_transfer&action=toEditJobTran&addType=addEdit&id=".$_POST[$this->objName]['id']);
				}else{
					if ($_POST[$this->objName]['status'] == 1) {
						msgGo('�ύ�ɹ�',"?model=hr_transfer_transfer&action=toJobTranList");
					}else {
						msgGo('����ɹ�',"?model=hr_transfer_transfer&action=toJobTranList");
					}
				}
			}
	    }else{
			if($_POST['addType']=='addEdit'){
			msgGo('����ʧ��',"?model=hr_transfer_transfer&action=toEditJobTran&addType=addEdit&id=".$_POST[$this->objName]['id']);
			}else{
				msgGo('����ʧ��',"?model=hr_transfer_transfer&action=toJobTranList");
			}
	    }
   }

	/**
	 * Ա��������д������Ƿ�ͬ��
	 */
	function c_opinionEdit(){
		$this->checkSubmit();
		$_POST [$this->objName]['status'] = 3;
		$id = $this->service->opinionEdit_d($_POST [$this->objName]);
		if ($id) {
			msgGo('����ɹ�',"index1.php?model=hr_transfer_transfer&action=toJobTranList");
		}
	}


	/**
	 * ��ת���鿴������Ա��¼ҳ��
	 */
	function c_toViewJobTran(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['employeeOpinion'] == 0) {
			$opt = "��ͬ��";
		}
		if($obj['employeeOpinion'] == 1) {
			$opt = "ͬ��";
		}
		if($obj['employeeOpinion'] == 2) {
			$opt = "";
		}
		$this->assign("opinion" ,$opt);
		$this->view('jobtran-view');
	}

	/**
	 * ��ת���鿴��Ա���ü�¼ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$this->assign("audit" ,$actType);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * �����쵼����Ա����������
	 */
	function c_toLeaderView(){
		$type=isset($_GET['type'])?$_GET['type']:'';
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$date=date(Y."-".m."-".d);
		$this->assign('today',$date);
		$this->assign('type',$type);
		$this->view('manage-approval' ,true);
	}

	/**
	 * ��ת��Ա���Ƿ�ͬ�����ҳ��
	 */
	function c_toOpinionView(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('personjobtran-view' ,true);
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * �жϹ�˾����
	 */
	function c_companyType(){
		$id = $_POST['id'];
		$branchDao = new  model_deptuser_branch_branch();
		$aboutinfo = $branchDao->find(array('ID'=>$id));
			if($aboutinfo['type'] == 0) {
				$typeinfo = '�ӹ�˾';
			}else if($aboutinfo['type'] == 1) {
				$typeinfo = '����';
			}
		echo $typeinfo;
	}

	/**
	 * ����Ա��������Ϣ
	 */
	function c_updatePersonInfo() {
		$idsArr = $_POST ['transferIds'];
		$flag = $this->service->updatePersonInfo_d($idsArr);
		if($flag) {
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
     * ��������
     */
     function c_dealTransfer(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok"){  //����ͨ��
				$row=$this->service->get_d($folowInfo['objId']);
				if($row['managerId'] == $row['userAccount']) {//�����Ա���������룬���Զ�����״̬Ϊͬ��
					$obj['id'] = $folowInfo['objId'];
					$obj['employeeOpinion'] = 1;
					$obj['status'] = '3';
					$this->service->edit_d($obj,true);
				}
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ����excel
	 */
	function c_toExport(){
		$this->showDatadicts(array('transferType' => 'HRDDLX'),null,true);
		$otherDao=new model_common_otherdatas(); //�½�otherdatas����
		$arr=$otherDao->getCompanyAndAreaInfo(); //������й�˾�͹�˾��������
		$companyOpt = "<option value=''></option>";
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>"; //ƴ��option��ǩ
		}
		$this->assign('companyOpt' ,$companyOpt); //�����й�˾��ӵ�select��ǩ
		$this->showDatadicts(array('prePersonClassCode' => 'HRRYFL')); //����ǰ��Ա����
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL')); //��������Ա����
		$this->view('exportview');
	}

	/**
	 * ����excel
	 */
	function c_export(){
		$object = $_POST[$this->objName];

		if(!empty($object['beginDate']))
	 		$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['endDate']))
	 		$this->service->searchArr['endDate'] = $object['endDate'];
		if(!empty($object['formCode']))
			$this->service->searchArr['formCode'] = $object['formCode'];
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['preBelongDeptId']))
			$this->service->searchArr['preBelongDeptId'] = $object['preBelongDeptId'];
		if(!empty($object['afterBelongDeptId']))
			$this->service->searchArr['afterBelongDeptId'] = $object['afterBelongDeptId'];
		if(!empty($object['preUnitId']))
			$this->service->searchArr['preUnitId'] = $object['preUnitId'];
		if(!empty($object['afterUnitId']))
			$this->service->searchArr['afterUnitId'] = $object['afterUnitId'];
		if(!empty($object['preJobId']))
			$this->service->searchArr['preJobId'] = $object['preJobId'];
		if(!empty($object['afterJobId']))
			$this->service->searchArr['afterJobId'] = $object['afterJobId'];
		if(!empty($object['prePersonClassCode']))
			$this->service->searchArr['prePersonClassCode'] = $object['prePersonClassCode'];
		if(!empty($object['afterPersonClassCode']))
			$this->service->searchArr['afterPersonClassCode'] = $object['afterPersonClassCode'];

		$this->service->searchArr['ExaStatusArr'] = '��������,���,���';

		$rows = $this->service->list_d();
		foreach($rows as $key => $val) {
			if($rows[$key]['isCompanyChange'] == 0) {
				$rows[$key]['isCompanyChangeC'] = '��';
			} else {
				$rows[$key]['isCompanyChangeC'] = '��';
			}

			if($rows[$key]['isDeptChange'] == 0) {
				$rows[$key]['isDeptChangeC'] = '��';
			} else {
				$rows[$key]['isDeptChangeC'] = '��';
			}

			if($rows[$key]['isJobChange'] == 0) {
				$rows[$key]['isJobChangeC'] = '��';
			} else {
				$rows[$key]['isJobChangeC'] = '��';
			}

			if($rows[$key]['isAreaChange'] == 0) {
				$rows[$key]['isAreaChangeC'] = '��';
			} else {
				$rows[$key]['isAreaChangeC'] = '��';
			}

			if($rows[$key]['isClassChange'] == 0) {
				$rows[$key]['isClassChangeC'] = '��';
			} else {
				$rows[$key]['isClassChangeC'] = '��';
			}
		}

		$exportData = array();
		if($rows){
			foreach ( $rows as $key => $val ){
				$exportData[$key]['userNo'] = $rows[$key]['userNo'];
				$exportData[$key]['userName'] = $rows[$key]['userName'];
				$exportData[$key]['transferDate'] = $rows[$key]['transferDate'];
				$exportData[$key]['isCompanyChangeC'] = $rows[$key]['isCompanyChangeC'];
				$exportData[$key]['isDeptChangeC'] = $rows[$key]['isDeptChangeC'];
				$exportData[$key]['isJobChangeC'] = $rows[$key]['isJobChangeC'];
				$exportData[$key]['isAreaChangeC'] = $rows[$key]['isAreaChangeC'];
				$exportData[$key]['isClassChangeC'] = $rows[$key]['isClassChangeC'];
				$exportData[$key]['preUnitTypeName'] = $rows[$key]['preUnitTypeName'];
				$exportData[$key]['preUnitName'] = $rows[$key]['preUnitName'];
				$exportData[$key]['preBelongDeptName'] = $rows[$key]['preBelongDeptName'];
				$exportData[$key]['preDeptNameS'] = $rows[$key]['preDeptNameS'];
				$exportData[$key]['preDeptNameT'] = $rows[$key]['preDeptNameT'];
				$exportData[$key]['preDeptNameF'] = $rows[$key]['preDeptNameF'];
				$exportData[$key]['afterUnitTypeName'] = $rows[$key]['afterUnitTypeName'];
				$exportData[$key]['afterUnitName'] = $rows[$key]['afterUnitName'];
				$exportData[$key]['afterBelongDeptName'] = $rows[$key]['afterBelongDeptName'];
				$exportData[$key]['afterDeptNameS'] = $rows[$key]['afterDeptNameS'];
				$exportData[$key]['afterDeptNameT'] = $rows[$key]['afterDeptNameT'];
				$exportData[$key]['afterDeptNameF'] = $rows[$key]['afterDeptNameF'];
				$exportData[$key]['preJobName'] = $rows[$key]['preJobName'];
				$exportData[$key]['afterJobName'] = $rows[$key]['afterJobName'];
				$exportData[$key]['preUseAreaName'] = $rows[$key]['preUseAreaName'];
				$exportData[$key]['afterUseAreaName'] = $rows[$key]['afterUseAreaName'];
				$exportData[$key]['prePersonClass'] = $rows[$key]['prePersonClass'];
				$exportData[$key]['afterPersonClass'] = $rows[$key]['afterPersonClass'];
				$exportData[$key]['managerName'] = $rows[$key]['managerName'];
				$exportData[$key]['fujian'] = $rows[$key]['fujian'];
				$exportData[$key]['reason'] = $rows[$key]['reason'];
				$exportData[$key]['remark'] = $rows[$key]['remark'];
			}
		}

		return $this->service->export($exportData);
	}
	/******************* E ���뵼��ϵ�� ************************/

	/**
	 * ����ʱ��������
	 */
	function c_updateData() {
		$id = $_POST['id'];
		$reportDate = $_POST['reportDate'];
		$handoverDate = $_POST['handoverDate'];
		$handoverRemark = iconv("utf-8","gb2312",$_POST['handoverRemark']);//�������
		$this->service->updateById(array('id' => $id, 'reportDate' => $reportDate, 'handoverDate' => $handoverDate, 'handoverRemark' => $handoverRemark));
	}

	/**
	 * ����ID�ύ��������
	 */
	function c_ajaxSubmit() {
		$obj['id'] = $_POST['id'];
		$obj['status'] = 1;
		if($this->service->updateById($obj)){
			$this->service->mailToHr_d($obj['id']);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ת��ȷ���ύ����ҳ��
	 */
	function c_toConfirm() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts(array('transferType' => 'HRDDLX') ,$obj['transferType']);
		$this->showDatadicts(array('afterPersonClassCode' => 'HRRYFL') ,$obj['afterPersonClassCode']);
		$otherDao = new model_common_otherdatas();
		$areaDao = new model_common_otherdatas();
		$arr = $otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt = "";
		$areaOpt = "";
		$company = $obj['afterUnitName'];
		$area = $obj['afterUseAreaName'];
		for($i = 0 ;$i < count($arr) ;$i++) {
			$id = $arr[$i]['ID'];
			if($arr[$i]['NameCN'] == $company) {
				$companyOpt = $companyOpt."<option selected value='$id'>".$arr[$i]['NameCN']."</option>";
			} else {
				$companyOpt = $companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
			}
		}
		$areaArr = $areaDao->getArea();
		foreach($areaArr as $k => $v){
			$id = $v['ID'];
			if($area == $v['Name']) {
				$areaOpt =$areaOpt."<option selected value='$id'>".$v['Name']."</option>";
			} else {
				$areaOpt =$areaOpt."<option value='$id'>".$v['Name']."</option>";
			}
		}
		$this->show->assign('areaOpt' ,$areaOpt); //������������ӵ�select��ǩ
		$this->assign('companyOpt' ,$companyOpt); //�����й�˾��ӵ�select��ǩ
		$this->view('confirm' ,true);
	}
}
?>