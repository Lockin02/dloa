<?php

/**
 * @author Show
 * @Date 2012��5��31�� ������ 17:39:42
 * @version 1.0
 * @description:��ְ�ʸ���Ϣ���Ʋ�
 */
class controller_hr_personnel_certifyapply extends controller_base_action {

	function __construct() {
		$this->objName = "certifyapply";
		$this->objPath = "hr_personnel";
		parent :: __construct();
	}

	/***************** �б��� **********************/

	/*
	 * ��ת����ְ�ʸ���Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * ��ת����ְ�ʸ���Ϣ�б�--����
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/**
	 * �����б�
	 */
	function c_myList(){
		$this->view('listmy');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_REQUEST['userAccount'] = $_SESSION['USER_ID'];
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
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
			$_POST['deptId'] = $sysLimit;
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
        $arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/******************** ��ɾ�Ĳ� ******************/

	/**
	 * ��ת��������ְ�ʸ���Ϣҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//���뼶��

		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),null,true);//��֤��չͨ��
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),null,true);//��֤����
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),null,true);//��֤����
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),null,true);//��֤��ν

		$this->view('add',true);
	}

	/**
	 * ��ת���༭��ְ�ʸ���Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('baseResultHidden',$obj['baseResult']);
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection'],true);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel'],true);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade'],true);//���뼶��

		$this->assign('finalResultHidden',$obj['finalResult']);
		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),$obj['finalCareer'],true);//��֤��չͨ��
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),$obj['finalLevel'],true);//��֤����
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),$obj['finalGrade'],true);//��֤����
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),$obj['finalTitle'],true);//��֤��ν
		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴��ְ�ʸ���Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if($obj['baseScore']==0){
			$this->assign('baseScore' ,'');
		}
		if($obj['finalScore']==0){
			$this->assign('finalScore' ,'');
		}
		$this->assign('baseResult',$this->service->rtIsPass_d($obj['baseResult']));
		$this->assign('finalResult',$this->service->rtIsPass_d($obj['finalResult']));
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));

		//��ʼ����֤���۱�
		$cassessArr = $this->service->getAssess_d($obj['id']);
		$this->assign('cassessId' ,$cassessArr['id']);

		//��ʼ���÷ֻ����
		$scoreArr = $this->service->getScore_d($cassessArr['id']);
		$this->assign('scoreId' ,$scoreArr['id']);

		$this->view('view');
	}

	/*********************** �������̲��� ***************************/

	/**
	 * ��ת��������ְ�ʸ���Ϣҳ��
	 */
	function c_toAddApply() {
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//���뼶��
		$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),null,true);//������֤����

		$rs = $this->service->getPersonnelInfo_d($_SESSION['USER_ID']);
		$this->assign('thisDate',day_date);
		$this->assign('thisYear',date("Y"));

		if($rs){
			$this->assignFunc($rs);

			$this->showDatadicts(array('nowDirection'=>'HRZYFZ'),$rs['nowDirection']);//���뷢չͨ��
			$this->showDatadicts(array('nowLevel' => 'HRRZJB'),$rs['nowLevel']);//���뼶��
			$this->showDatadicts(array('nowGrade' => 'HRRZZD'),$rs['nowGrade']);//���뼶��
			$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),null,true);//������֤����

			$this->assign('deptId',$_SESSION['DEPT_ID']);
			$this->assign('deptName',$_SESSION['DEPT_NAME']);
		}else{
			echo "û�ж�Ӧ�ĵ�����Ϣ�����֪HR�ѵ�����Ϣ��������";
			die();
		}

		$this->view('addapply',true);
	}

	/**
	 * �����������
	 */
	function c_addApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj = $_POST [$this->objName];
		if ($_GET['act'] == "app") {//������˲���act=app,��״̬Ϊ�ύ
			$obj['status']=1;
		}
		$id = $this->service->addApply_d ($obj , $isAddInfo );
		if ($id && $_GET['act'] == "app") {//������˲���act=app,��ֱ���ύ����
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
			msgRf( $msg );
		} else
		if ($id) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
			msgRf( $msg );
		}
	}

	/**
	 * ��ת��������ְ�ʸ���Ϣҳ��
	 */
	function c_toEditApply() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),$obj['careerDirection'],true);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),$obj['baseLevel'],true);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),$obj['baseGrade'],true);//���뼶��
		$this->showDatadicts(array('certifyDirection' => 'HRRZRZFX'),$obj['certifyDirection'],true);//��֤����

		$this->showDatadicts(array('nowDirection'=>'HRZYFZ'),$obj['nowDirection']);//���뷢չͨ��
		$this->showDatadicts(array('nowLevel' => 'HRRZJB'),$obj['nowLevel']);//���뼶��
		$this->showDatadicts(array('nowGrade' => 'HRRZZD'),$obj['nowGrade']);//���뼶��
		$this->assign('thisYear',date("Y"));

		$this->view('editapply',true);
	}

	/**
	 * ��ת�������ְ�ʸ���Ϣҳ��
	 */
	function c_toBackApply() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);

		$this->view('backapply',true);
	}

	/**
	 * ���
	 */
	function c_backApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj =  $_POST [$this->objName];
		$res = $this->service->backApply_d ($obj, $isAddInfo);
		if ($res) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '��سɹ���';
			msg( $msg );
		}
	}

	/**
	 * �༭
	 */
	function c_editApply($isAddInfo = false) {
		$this->checkSubmit();
		$obj =  $_POST [$this->objName];
		if ($_GET['act'] == "app") {//������˲���act=app,��״̬Ϊ�ύ
			$obj['status']=1;
			$obj['ExaStatus'] = AUDITING;
		}
		$res = $this->service->editApply_d ($obj, $isAddInfo);
		if ($res && $_GET['act'] == "app") {//������˲���act=app,��ֱ���ύ����
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
			msgRf( $msg );
		} else
		if ($res) {
			$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
			msgRf( $msg );
		}
	}

	/**
	 * ��ת���鿴��ְ�ʸ���Ϣҳ��
	 */
	function c_toViewApply() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('actType' ,$_GET['actType']);
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));
		$this->view('viewapply');
	}

		/**
	 * ��ת���鿴��ְ�ʸ���Ϣҳ�棨Ա���鿴��
	 */
	function c_toViewApplyPerson() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('actType' ,$_GET['actType']);
		$this->assign('status' ,$this->service->rtStatus_c($obj['status']));
		$this->view('person-view');
	}

	/****************** ҵ���߼� ***********************/

	/**
	 * ������ɺ�ص�����
	 */
	function c_dealAfterAudit(){
       	$this->service->dealAfterAudit_d( $_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/**
	 * ��֤�����ύ
	 */
	function c_submitApply() {
			$flag=$this->service->submitApply_d ( $_POST ['id']);
			if($flag){
				echo 1;
			}else{
				echo 0;
			}
	}

	/**
	 * Ա����֤��������ͨ��
	 **/
	function c_aduitPass(){
	 	$idsArr=$_POST ['applyIds'];
	 	$flag=$this->service->aduitPass_d($idsArr);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}
	/**
	 * ����excel����
	 */
	function c_toExcelUpdate(){
		$this->display('excel-update');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$val=$_POST['actionType'];
		if($val['value']==1){
			$resultArr = $this->service->addExecelData_d ();
		}else{
			$resultArr = $this->service->updataExecelData_d ();
		}

		$title = '��ְ�ʸ���Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ����excel
	 */
	function c_excelUpdate(){
		$resultArr = $this->service->updataCertifyapplyData_d ();
		$title = '��ְ�ʸ���Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}
	/******************* E ���뵼��ϵ�� ************************/
	//add chenrf 20130524
	/**
	 *
	 * ת��excel����ҳ��
	 */
	function c_toExcelout(){
		$this->showDatadicts(array('careerDirection'=>'HRZYFZ'),null,true);//���뷢չͨ��
		$this->showDatadicts(array('baseLevel' => 'HRRZJB'),null,true);//���뼶��
		$this->showDatadicts(array('baseGrade' => 'HRRZZD'),null,true);//���뼶��

		$this->showDatadicts(array('finalCareer' => 'HRZYFZ'),null,true);//��֤��չͨ��
		$this->showDatadicts(array('finalLevel' => 'HRRZJB'),null,true);//��֤����
		$this->showDatadicts(array('finalGrade' => 'HRRZZD'),null,true);//��֤����
		$this->showDatadicts(array('finalTitle' => 'HRRZCW'),null,true);//��֤��ν

		$this->view('excelout');
	}
	/**
	 *
	 * excel����
	 */
	function c_excelout(){
		set_time_limit(0);
		$param=array_filter($_POST[$this->objName]);
		$baseResult=$_POST[$this->objName]['baseResult'];
		$finalResult=$_POST[$this->objName]['finalResult'];
		if($baseResult!=''&&$baseResult==0)
			$param['baseResult']='0';
		if($finalResult!=''&&$finalResult==0)
			$param['finalResult']='0';
		$this->service->searchArr=$param;
		$row = $this->service->list_d('select_excelOut');
		$this->service->excelOut($row);
	}
}
?>