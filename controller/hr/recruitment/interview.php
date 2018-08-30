<?php

/**
 * @author Show
 * @Date 2012��6��1�� ������ 14:51:13
 * @version 1.0
 * @description:��Ƹ����-������������Ʋ�
 */
class controller_hr_recruitment_interview extends controller_base_action {

	function __construct() {
		$this->objName = "interview";
		$this->objPath = "hr_recruitment";
		parent :: __construct();
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ����������--�����鿴tabҳ
	 */
	function c_viewList() {
		$this->assign('resumeId',$_GET['resumeId']);
		$this->view('viewList');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_applyPage() {
		$this->assign("applyid",$_GET['applyid']);
		$this->assign("interviewtype",$_GET['type']);
		$this->view('applylist');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toDeptPage() {
		$this->assign("userid",$_SESSION['DEPT_ID']);
		$this->view('deptlist');
	}

	/**
	 * ��ת����Ƹ����-�����������������б�
	 */
	function c_toManagerPage() {
		$this->assign("userid",$_SESSION['DEPT_ID']);
		$this->view('manager-list');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toLastPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('lastlist');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_recuitPage() {
		$this->assign("userid",$_SESSION['USER_ID']);
		$this->view('recuitlist');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toDeptView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('deptview');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toRecuitView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('recuitview');
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toAdd() {
		$this->permCheck(); //��ȫУ��
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//ְλ����
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ) ); //���ʼ���
		$this->view('add' ,true);
	}

	/**
	 * ����������(����)
	 */
	function c_toAddByResume() {
		$this->permCheck(); //��ȫУ��
		$resumeDao = new model_hr_recruitment_resume();
		$resumeRow = $resumeDao->get_d($_GET['resumeId']);
		foreach ($resumeRow as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$resumeRow['post']);
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName" ,$select);
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->showDatadicts(array('hrSourceType1Name' => 'HRBCFS'));
		$this->showDatadicts(array('useHireType' => 'HRLYXX') ,'HRLYXX-01');
		$this->showDatadicts(array('postType' => 'YPZW') ,$resumeRow['post']);//ְλ����
		$this->showDatadicts(array('controlPostCode' => 'HRGLGW')); // �����λ

		$this->assign("useManagerId" ,$_SESSION['USER_ID']);
		$this->assign("useManager" ,$_SESSION['USERNAME']);
		$this->assign("useSignDate" ,date('Y-m-d'));
		$this->assign("hrSourceType2Name" ,$resumeRow['sourceB']);
		$this->assign("sexy" ,$resumeRow['sex']);
		$this->showDatadicts(array('hrSourceType1' => 'JLLY' ) ,$resumeRow['sourceA']);

		$this->assign("managerId",$_SESSION['USER_ID']);
		$this->assign("manager",$_SESSION['USERNAME']);
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX'));//��Ա����
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB')); //���ʼ���
		$this->view('resume-add' ,true);
	}

	/**
	 * ����������(ְλ����)
	 */
	function c_toAddByEmployment() {
		$this->permCheck(); //��ȫУ��
		$employmentDao = new model_hr_recruitment_employment();
		$employmentRow = $employmentDao->get_d($_GET['employmentId']);
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//ְλ����
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ) ); //���ʼ���
		foreach ($employmentRow as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('employment-add' ,true);
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toDeptEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		//��ȡ����
		$str1 = $this->service->getInterComment(1,$obj['invitationId']);
		$str2 = $this->service->getUseComment(1,$obj['invitationId']);

		$this->assign("useWriteEva",$str2);
		$this->assign("useInterviewEva",$str1);
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();

		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$this->assign("useManagerId",$_SESSION['USER_ID']);
		$this->assign("useManager",$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType']);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //���ʼ���
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->view('deptedit' ,true);
	}

	/**
	 * ��ת����Ƹ����-�����������б�
	 */
	function c_toDeptAdd() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType'] ,true);
		$this->showDatadicts ( array ('hrHireType' => 'HRLYXX' ), $obj ['hrHireType'] ,true);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //���ʼ���
		$this->view('deptadd' ,true);
	}

	function c_toLastEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if(!empty($obj['hrSourceType1'])){
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$obj['hrSourceType1']);
		}else if($obj['resumeId']>0){
			$resumeDao=new model_hr_recruitment_resume();
			$resumeRow=$resumeDao->get_d($obj['resumeId']);
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$resumeRow['sourceA']);
			$this->assign("hrSourceType2Name",$resumeRow['sourceB']);
		}else{
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		}
		//��ȡ����
		if($obj['invitationId'] > 0){
			$str1 = $this->service->getInterComment(1,$obj['invitationId']);
			$str2 = $this->service->getUseComment(1,$obj['invitationId']);
			$str3 = $this->service->getInterComment(2,$obj['invitationId']);
		}else{
			$str1 = $this->service->getInterviewComment('1',$_GET['id']);
			$str2 = $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3 = $this->service->getInterviewComment('2',$_GET['id']);
		}
		if($obj['managerId']==''){
			$this->assign("managerId",$_SESSION['USER_ID']);
			$this->assign("manager",$_SESSION['USERNAME']);
		}else{
			$this->assign("managerId",$obj['managerId']);
			$this->assign("manager",$obj['manager']);
		}
		$this->assign("SignDate",date('Y-m-d'));
		$this->assign("useWriteEva",$str2);
		$this->assign("useInterviewEva",$str1);
		$this->assign("hrInterviewList",$str3);

		if($obj['useInterviewResult'] == '1') {
			$this->assign("useInterviewResult", "����¼��");
		} else {
			$this->assign("useInterviewResult", "�����˲�");
		}
		$this->showDatadicts(array('hrHireType' => 'HRPYLX'));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX' ), $obj['addTypeCode']);//��Ա����
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //���ʼ���
		$this->view('lastedit' ,true);
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

		//������Ϣ����
		if(!empty($rows)){
			$rows = $this->sconfig->md5Rows ( $rows );
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ������֪ͨ������������
	 */
	function c_toAddByNotice() {
		$invitationId=isset($_GET['invitationId'])?$_GET['invitationId']:"";
		$invitationDao=new model_hr_recruitment_invitation();
		$invitationRow=$invitationDao->get_d($invitationId);
		foreach ($invitationRow as $key => $val) {
			$this->assign($key, $val);
		}
		if ($invitationRow ['positionLevel'] =="1") {
			$this->assign ( 'positionLevelName', '����' );
		} else if ($invitationRow ['positionLevel'] == "2") {
			$this->assign ( 'positionLevelName', '�м�' );
		}else if ($invitationRow ['positionLevel'] == "3") {
			$this->assign ( 'positionLevelName', '�߼�' );
		}else {
			$this->assign ( 'positionLevelName',$invitationRow ['positionLevel']);
		}
		$this->view('invitation-add' ,true);
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//ת��������
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
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
	function c_pageForManager() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$provinceDao=new model_system_procity_province();
		$provinceStr=$provinceDao->getProvinceByUser_d($_SESSION ['USER_ID']);
		$service->searchArr ['provinceArr'] = $provinceStr;

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$deptDao=new model_deptuser_dept_dept();
		$newRow=array();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//ת��������
				$rows[$key]['stateC'] = $service -> statusDao -> statusKtoC($rows[$key]['state']);
				$deptRow=$deptDao->getSuperiorDeptById_d($rows[$key]['deptId']);
				$rows[$key]['parentDeptId']=$deptRow['deptId'];
				if($deptRow['deptId']=='35'){
					array_push($newRow,$rows[$key]);
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $newRow;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRow ? count ( $newRow ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �޸Ķ���
	 */
	function c_addByNotice() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		if ($this->service->add_d ( $object )) {
			msg( '�ύ�ɹ���' );
		}else{
			msg( '�ύʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���༭��Ƹ����-����������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ), $obj ['useHireType'] ,true);
		$this->showDatadicts ( array ('hrHireType' => 'HRLYXX' ), $obj ['hrHireType'] ,true);
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ), $obj ['wageLevelCode'] ); //���ʼ���
		$this->view('edit' ,true);
	}

	/**
	 * ��ת���༭��Ƹ����-����������ҳ��
	 */
	function c_toManagerEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);

		foreach ($obj as $key => $val) {
			if ($key == 'isCompanyStandard') {
				if ($val == '0') {
					$this->assign("check" ,"checked");
				} else {
					$this->assign("check1" ,"checked");
				}
			} else {
				$this->assign($key, $val);
			}
		}
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']); // ӦƸְλ
		$this->showDatadicts(array('controlPostCode' => 'HRGLGW') ,$obj['controlPostCode']); // �����λ
		$branchDao = new model_deptuser_branch_branch();
		$area = new includes_class_global();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName" ,$select);
		$this->assign('area_select' ,$area->area_select());
		$this->showDatadicts(array('useHireType' => 'HRLYXX') ,$obj['useHireType']); //¼����ʽ
		if(!empty($obj['hrSourceType1'])) {
			$this->showDatadicts (array('hrSourceType1' => 'JLLY') ,$obj['hrSourceType1']);
		} else if ($obj['resumeId'] > 0) {
			$resumeDao = new model_hr_recruitment_resume();
			$resumeRow = $resumeDao->get_d($obj['resumeId']);
			$this->showDatadicts(array('hrSourceType1' => 'JLLY') ,$resumeRow['sourceA']);
			$this->assign("hrSourceType2Name" ,$resumeRow['sourceB']);
		} else {
			$this->showDatadicts(array('hrSourceType1' => 'JLLY'));
		}

		if($obj['managerId'] == '') {
			$this->assign("managerId" ,$_SESSION['USER_ID']);
			$this->assign("manager" ,$_SESSION['USERNAME']);
		} else {
			$this->assign("managerId" ,$obj['managerId']);
			$this->assign("manager" ,$obj['manager']);
		}
		$this->assign("invitationId" ,$obj['invitationId']);
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//��Ա����
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB') ,$obj ['wageLevelCode'] ); //���ʼ���

		if ($_GET['changeHire']) { //�ж��Ƿ�ҪתΪ����¼��
			$this->assign("changeHire" ,1);
		}

		if ($_GET['isCopy']) { //�ж��Ƿ�Ϊ����������
			$this->assign("isCopy" ,1);
		}

		if ($_GET['audit']) { //�ж��Ƿ�Ϊ��������޸�
			$this->assign("audit" ,1);
		}

		$this->view('manager-edit' ,true);
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object )) {
			msgRf ( '�༭�ɹ���' );
		}else{
			msgRf ( '�༭ʧ�ܣ�' );
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_deptedit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$editType=isset($_GET['editType'])?$_GET['editType']:"";
		$object = $_POST [$this->objName];
		$branchDao = new model_deptuser_branch_branch();
		$branchCN = $branchDao->get_d($object['sysCompanyId']);
		$object['sysCompanyName'] = $branchCN['NameCN'];
		if($editType!='edit'){
			$object['deptState'] = 1;
		}
		$query = $this->service->db->query("select Name from area where ID = ".$object['useAreaId']);
		$get = $this->service->db->fetch_array($query);
		$object['useAreaName'] = $get['Name'];
		if ($this->service->edit_d ( $object )) {
			if($editType!='edit'){
				msgRf ( 'ȷ�ϳɹ���' );
			}else{
				msgRf ( '����ɹ���' );
			}
		}else{
			if($editType!='edit'){
				msgRf ( 'ȷ��ʧ�ܣ�' );
			}else{
				msgRf ( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_lastedit($isEditInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$editType=isset($_GET['editType'])?$_GET['editType']:"";
		$object = $_POST [$this->objName];
		if($editType!='edit'){
			$object['hrState'] = 1;
		}
		$object['state'] = $this->service->statusDao->statusEtoK("noview");
		$datadict = new model_system_datadict_datadict();
		$object['hrHireTypeName'] = $datadict->getDataNameByCode($object['hrHireType']);
		$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1Name']);
		$object['addType'] = $datadict->getDataNameByCode($object['addTypeCode']);
		$object['wageLevelName'] = $datadict->getDataNameByCode($object['wageLevelCode']);

		if ($this->service->edit_d ( $object )) {
			if($editType!='edit'){
				msgRf ( 'ȷ�ϳɹ���' );
			}else{
				msgRf ( '����ɹ���' );
			}
		}else{
			if($editType!='edit'){
				msgRf ( 'ȷ��ʧ�ܣ�' );
			}else{
				msgRf ( '����ʧ�ܣ�' );
			}
		}
	}

	//�༭��������
	function c_managerEdit($isEditInfo = false) {
		// $this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$service = $this->service;
		//����ǲ���תΪ����¼�õı༭
		if ($_POST['changeHire'] == 1) {
			$oldObj = $service->get_d($object['id']);
		}

		if(isset($object['formCode'])) {
			unset($object['formCode']);
		}
		$branchDao = new model_deptuser_branch_branch();
		$branchCN = $branchDao->get_d($object['sysCompanyId']);
		$object['sysCompanyName'] = $branchCN['NameCN'];
		$object['deptState'] = 1;
		$query = $service->db->query("select Name from area where ID = ".$object['useAreaId']);
		$get = $service->db->fetch_array($query);
		$object['useAreaName'] = $get['Name'];
		$object['hrState'] = 1;
		$datadict = new model_system_datadict_datadict();
		$object['hrHireTypeName'] = $datadict->getDataNameByCode($object['hrHireType']);
		$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1Name']);
		$object['wageLevelName'] = $datadict->getDataNameByCode($object['wageLevelCode']);
		if ($service->managerEdit_d($object)) {
			$newObj = $service->get_d($object['id']);
			if ($this->c_isNeedExa($newObj ,$oldObj)) { //�ж��Ƿ���Ҫ�����ύ����
				$newObj['parentDeptId'] = $service->get_table_fields('department' ,'DEPT_ID='.$newObj['deptId'] ,'pdeptid');

				//������ȷ��HRδȷ�ϣ��Ե�ר��������ִ������
				if ($newObj['hrState'] == 0 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY') {
					succ_show('controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=������������'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				//������ȷ�ϲ�����HRδȷ�ϣ��Ե�ר��������ִ������
				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY' && $newObj['state'] == '') {
					succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=������������'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						|| ($newObj['parentDeptId'] == '130' && $newObj['postType'] != 'YPZW-WY')) {
					if(($newObj['parentDeptId'] == '130'|| $newObj['parentDeptId'] == '131')
							&& $newObj['postType'] != 'YPZW-WY') {
						succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=������������'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					} else {
						succ_show('controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=������������'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					}
				}

			} else {
				msgRf ( '����ɹ���' );
			}
		} else {
			msgRf ( '����ʧ�ܣ�' );
		}
	}

	/**
	 * �ж��Ƿ���Ҫ�����ύ����
	 */
	function c_isNeedExa($newObj ,$oldObj) {
		if ($newObj['ExaStatus'] != '���') { //��δ������������������ύ����
			return false;
		}
		$compare = array(
			'useTrialWage' //�����ڻ�������
			,'useFormalWage' //ת����������
			,'phoneSubsidy' //'�绰�Ѳ����������ڣ�
			,'phoneSubsidyFormal' //'�绰�Ѳ�����ת����
			,'levelSubsidy' //'�����������������ڣ�
			,'levelSubsidyFormal' //'������������ת����
			,'tripSubsidy' //'���������ֵ�������ڣ�
			,'tripSubsidyFormal' //'���������ֵ��ת����
			,'workBonus' //'�������������ڣ�
			,'workBonusFormal' //'��������ת����
			,'computerSubsidy' //'���Բ����������ڣ�
			,'computerSubsidyFormal' //'���Բ�����ת����
			,'otherSubsidy' //'���������������ڣ�
			,'otherSubsidyFormal' //'����������ת����
			,'areaSubsidy' //'�������������ڣ�
			,'areaSubsidyFormal' //'��������ת����
			,'bonusLimit' //'��������ֵ�������ڣ�
			,'bonusLimitFormal' //'��������ֵ��ת����
			,'manageSubsidy' //'��������������ڣ�
			,'manageSubsidyFormal' //'���������ת����
			,'accommodSubsidy' //'��ʱס�޲����������ڣ�
			,'accommodSubsidyFormal' //'��ʱס�޲�����ת����
			,'internshipSalaryType' //'ʵϰ��������
			,'internshipSalary' //'ʵϰ����
		);
		foreach ($compare as $key => $val) {
			if ($newObj[$val] != $oldObj[$val]) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * �������ظ�����
	 */
	function eliminate($obj){
		$data = explode("<br>",$obj);	//ת��������
		$data = array_filter($data);
		$data = array_unique($data); 	//�����ظ�
		foreach($data as $key=>$val){
			$newData .= $val."<br>";
		}
		return $newData;
	}

	/**
	 * ��ת���鿴��Ƹ����-����������ҳ��
	 */
	function c_toView() {
		if($_GET['id'] != '*') {
			$investigationDao = new model_hr_recruitment_investigation();
			$investigationArr = $investigationDao->find(array("parentId"=>$_GET['id']));
			if($investigationArr){
				$this->assign("investigationId",$investigationArr['id']);
				$this->assign("investigationFormCode",$investigationArr['formCode']);
			}else{
				$this->assign("investigationId" ,0);
			}
			$this->permCheck(); //��ȫУ��
			$obj = $this->service->get_d($_GET['id']);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			if($obj['postTypeName'] == '����'){
				$level = new model_hr_basicinfo_level();
				$WYlevel = $level->get_d($obj['positionLevel']);
				$this->assign('positionLevel', $WYlevel['personLevel']);
			}else{
				switch ($obj['positionLevel']){
					case '1' :$this->assign('positionLevel', '����');break;
					case '2' :$this->assign('positionLevel', '�м�');break;
					case '3' :$this->assign('positionLevel', '�߼�');break;
				}
			}
			//��ȡ����
			if($obj['invitationId'] > 0) {
				$str1 = $this->service->getInterComment(1,$obj['invitationId']);
				$str2 = $this->service->getUseComment(1,$obj['invitationId']);
				$str3 = $this->service->getInterComment(2,$obj['invitationId']);
			}
			$str1.= $this->service->getInterviewComment('1',$_GET['id']);
			$str2.= $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3.= $this->service->getInterviewComment('2',$_GET['id']);

			//�����ظ�����
			$str1=$this->eliminate($str1);
			$str2=$this->eliminate($str2);
			$str3=$this->eliminate($str3);

			$this->assign("useWriteEva" ,rtrim($str2,'<br>'));
			$this->assign("useInterviewEva" ,rtrim($str1,'<br>'));
			$this->assign("hrInterviewList" ,rtrim($str3,'<br>'));

			if($obj['useInterviewResult'] == '1') {
				$this->assign("useInterviewResult", "����¼��");
			} else {
				$this->assign("useInterviewResult", "�����˲�");
			}

			if($obj['isCompanyStandard'] == '1') {
				$this->assign("isCompanyStandard", "��");
			}else{
				$this->assign("isCompanyStandard", "��");
			}
			$this->view('view');
		}else{
			msg("û������������Ϣ��");
		}
	}

	/*
	 * ��ת����������ҳ��
	 */
	function c_toInvestigation(){
		$this->assign('thisUser',$_SESSION['USERNAME']);
		$this->assign('thisUserId',$_SESSION['USER_ID']);
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$today=date(Y.'-'.m.'-'.d);
		$this->assign("today", $today);
		$this->showDatadicts(array('relationshipName' => 'YZXRGX'));
		$this->view('investigation');
	}

	/**
	 * �޸�¼�ý��״̬
	 */
	function c_change() {
		$this -> permCheck();
		//��ȫУ��
		if($this->service->change_d())
			echo 1;
		else
			echo 0;
	}

	/**
	 * ��ת���鿴��Ƹ����-����������ҳ��
	 */
	function c_toRead() {
		$investigationDao = new model_hr_recruitment_investigation();
		$investigationArr = $investigationDao->find(array("parentId"=>$_GET['id']));
		if($investigationArr){
			$this->assign("investigationId" ,$investigationArr['id']);
			$this->assign("investigationFormCode" ,$investigationArr['formCode']);
		}else{
			$this->assign("investigationId" ,0);
		}
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['postTypeName'] == '����'){
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($obj['positionLevel']);
			$this->assign('positionLevel', $WYlevel['personLevel']);
		}else{
			switch ($obj['positionLevel']) {
				case '1' :$this->assign('positionLevel', '����');break;
				case '2' :$this->assign('positionLevel', '�м�');break;
				case '3' :$this->assign('positionLevel', '�߼�');break;
			}
		}
		//��ȡ����
		if($obj['invitationId'] > 0) {
			$str1 = $this->service->getInterComment(1,$obj['invitationId']);
			$str2 = $this->service->getUseComment(1,$obj['invitationId']);
			$str3 = $this->service->getInterComment(2,$obj['invitationId']);
		}else{
			$str1 = $this->service->getInterviewComment('1',$_GET['id']);
			$str2 = $this->service->getUseInterviewComment(1,$_GET['id']);
			$str3 = $this->service->getInterviewComment('2',$_GET['id']);
		}
		$this->assign("useWriteEva",rtrim($str2,'<br>'));
		$this->assign("useInterviewEva",rtrim($str1,'<br>'));
		$this->assign("hrInterviewList",rtrim($str3,'<br>'));
		if($obj['useInterviewResult'] == '1') {
			$this->assign("useInterviewResult" ,"����¼��");
		} else {
			$this->assign("useInterviewResult" ,"�����˲�");
		}

		if($obj['isCompanyStandard'] == '1') {
			$this->assign("isCompanyStandard", "��");
		}else{
			$this->assign("isCompanyStandard", "��");
		}
		//����ͳ��������Ϣ
		$html = $this->service->getDeptPer_d($_GET['id']);
		$this->assign('points' ,$html);
		$this->view('read');
	}

	/**
	 * ��Ա�����鿴����������Ϣ
	 */
	function c_toViewForPerson(){
		$obj = $this->service->find(array('userAccount' => $_GET['userAccount']));
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin' ,true);
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$resultArr = $this->service->addExecelData_d ();

		$title = '���������������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/

/*************************kindeditor*************************************************************************/

	/**
	 * ����֪ͨ
	 */
	function c_InterviewNotice(){
		//��ȡ������Ϣ
		$dao=new model_hr_recruitment_resume();
		$resumeinfo = $dao->get_d($_GET['id']);
		$this->assign("resumeId",$_GET['id']);
		$this->assign("toMail",$resumeinfo['email']);
		$this->assign("toMailName",$resumeinfo['applicantName']);
		$this->assign("user",$_SESSION['USERNAME']);
		$this->assign("userId",$_SESSION['USER_ID']);
		//�Զ���-ְλ�����
		$aa = WEB_TOR;
		$aa.="view\\template\hr\\recruitment\\resume-add.htm";
		$this->assign("jobUrl",$aa);
		$this->view("interviewNotice");
	}

	/**
	 *  ����֪ͨ�����ʼ�
	 */
	function c_interviewMail(){
		$info = $_POST ['interMail'];
		$this->service->thisMail_d($info);
	}

	/*
	 * duanlh2013-04-01
	 * �����������
	 */
	function c_toInterviewAdd() {
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select = '';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$area = new includes_class_global();
		$this->assign('area_select',$area->area_select());
		$this->showDatadicts(array('hrSourceType1Name' => 'HRBCFS'));
		$this->showDatadicts(array('useHireType'       => 'HRLYXX') ,'HRLYXX-01');
		$this->showDatadicts(array('postType'          => 'YPZW'));   // ְλ����
		$this->showDatadicts(array('controlPostCode'   => 'HRGLGW')); // �����λ
		$this->assign("useManagerId" ,$_SESSION['USER_ID']);
		$this->assign("useManager" ,$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->assign("managerId" ,$_SESSION['USER_ID']);
		$this->assign("manager" ,$_SESSION['USERNAME']);
		$this->assign("SignDate" ,date('Y-m-d'));
		$this->showDatadicts(array('addTypeCode'   => 'HRZYLX')); //��Ա����
		$this->showDatadicts(array('wageLevelCode' => 'HRGZJB')); //���ʼ���
		$this->view("interview-add" ,true);
	}

	function c_interviewAdd() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];

		if ($_POST['isCopy'] == 1) { //���Ϊ������������������ʹӱ��id��ɾ����
			unset($object['id']);
			if(is_array($object['items'])){
				foreach($object['items'] as $key => $value) {
					unset($object['items'][$key]['id']);
					if ($value['isDelTag'] == 1) { //�ѱ༭�����еļ�ɾ��Ҳɾ����
						unset($object['items'][$key]);
					}
				}
			}
			if(is_array($object['humanResources'])){
				foreach($object['humanResources'] as $key => $value){
					unset($object['humanResources'][$key]['id']);
					if ($value['isDelTag'] == 1) { //�ѱ༭�����еļ�ɾ��Ҳɾ����
						unset($object['humanResources'][$key]);
					}
				}
			}
		}

		if ($this->service->addInterview_d( $object )) {
			if ($_GET['staging'] == 'true') {
				msg( '����ɹ���' );
			} else {
				msg( '�ύ�ɹ���' );
			}
		} else {
			if ($_GET['staging'] == 'true') {
				msg( '����ʧ�ܣ�' );
			} else {
				msg( '�ύʧ�ܣ�' );
			}
		}
	}

	/**�ж��Ƿ����ύְλ����
	 *author can
	 *2010-12-29
	 */
	function c_isAdded() {
		$resumeId=isset($_POST['resumeId'])?$_POST['resumeId']:'';
		$id =$this->service->get_table_fields($this->service->tbl_name, "resumeId='".$resumeId."'", 'id');
		//���ɾ���ɹ����1���������0
		if($id > 0) {
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * ��תexcel����ҳ��
	 * add chenrf
	 * 20130517
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * excel����
	 */
	function c_excelOut(){
		set_time_limit(0);
		$param = array_filter($_POST[$this->objName]);
		$useInterviewResult = $_POST[$this->objName]['useInterviewResult'];
		if($useInterviewResult != '' && $useInterviewResult == 0) {
			$param['useInterviewResult'] = '0';
		}
		$this->service->searchArr = $param;
		$row = $this->service->list_d('select_excelOut');
		$this->service->excelOut($row);
	}

	/**
	 * ��ת�߼�����ҳ��
	 */
	function c_toSearch(){
		$this->permCheck(); //��ȫУ��
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->view('search');
	}

	/**
	 * ��������
	 */
	function c_dealAfterAuditPass() {
	 	if (!empty($_GET['spid'])) {
	 		$service = $this->service;
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
			$service->dealAfterAuditIng_d($folowInfo['objId'] ,$folowInfo['taskId']); //ÿһ������������
			if($folowInfo['examines'] == "ok") {  //����ͨ��
				$service->dealAfterAuditPass_d($folowInfo['objId']);
				$count = $service->countWorkFlow($folowInfo['objId']);
				if($count > 1){//��ȡ��ǰ���ݵ���������,����1��ʾΪ�������ٴ��޸�������,�������������󲻷��ʹ��ʼ�
					//��������ʼ�֪ͨ������
					$service->sendMailByEdit_d($folowInfo['objId']);
				}
			} else if ($folowInfo['examines'] == "no") { //������ͨ��
				$service->dealAfterAuditFail_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * �༭������ɵ���������
	 */
	function c_editAuditFinish() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST[$this->objName];
		$service = $this->service;
		$oldObj = $service->get_d($object['id']);
		$affectedRows = $service->editAuditFinish_d($object);
		if (!is_null($affectedRows)) {
			$newObj = $service->get_d($object['id']);
			if ($this->c_isNeedExa($newObj ,$oldObj)) { //�ж��Ƿ���Ҫ�����ύ����
				$newObj['parentDeptId'] = $service->get_table_fields('department' ,'DEPT_ID='.$newObj['deptId'] ,'pdeptid');

				//������ȷ��HRδȷ�ϣ��Ե�ר��������ִ������
				if ($newObj['hrState'] == 0 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY') {
					succ_show('controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=������������'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				//������ȷ�ϲ�����HRδȷ�ϣ��Ե�ר��������ִ������
				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						&& ($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
						&& $newObj['postType'] == 'YPZW-WY' && $newObj['state'] == '') {
					succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
						.'&examCode=oa_hr_recruitment_interview&formName=������������'
						.'&billId='.$newObj['id']
						.'&billDept='.$newObj['deptId']);
				}

				if ($newObj['hrState'] == 1 && $newObj['deptState'] == 1
						|| ($newObj['parentDeptId'] == '130' && $newObj['postType'] != 'YPZW-WY')) {
					if(($newObj['parentDeptId'] == '130' || $newObj['parentDeptId'] == '131')
							&& $newObj['postType'] != 'YPZW-WY') {
						succ_show('controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=������������'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					} else {
						succ_show('controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect'
							.'&examCode=oa_hr_recruitment_interview&formName=������������'
							.'&billId='.$newObj['id']
							.'&billDept='.$newObj['deptId']);
					}
				}
			} else {
				$service->updateById(array('id' => $newObj['id'] ,'changeTip' => 0)); //����Ҫ�����İѱ����ʶ�Ļ���
				if($affectedRows != 0){
					//��������ʼ�֪ͨ������
					$service->sendMailByEdit_d($object['id'],array('socialPlace'));
				}
				msgRf ( '����ɹ���' );
			}
		} else {
			msgRf ( '����ʧ�ܣ�' );
		}
	}
}