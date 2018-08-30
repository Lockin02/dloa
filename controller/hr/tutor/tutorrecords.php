<?php

/**
 * @author Show
 * @Date 2012��5��26�� ������ 11:40:48
 * @version 1.0
 * @description:��ʦ������Ϣ����Ʋ�
 */
class controller_hr_tutor_tutorrecords extends controller_base_action {

	function __construct() {
		$this->objName = "tutorrecords";
		$this->objPath = "hr_tutor";
		parent :: __construct();
	}

	/*
	 * ��ת����ʦ������Ϣ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/*
	 * ��ת����ʦ������Ϣ���б�--����
	 */
	function c_pageByPerson() {
		$this->assign( 'userAccount',$_GET['userAccount'] );
		$this->assign( 'userNo',$_GET['userNo'] );
		$this->view('listbyperson');
	}

	/**
	 * ��ʦ����----ѧԺֱ���ϼ��б�
	 */
	function c_studentSuperiorList(){
        $this->assign( 'userId', $_SESSION['USER_ID']);
		$this->view('studentSuperior');
	}

    /**
     * ��ʦ����--�����б�
     */
    function c_tutorDeptlist(){
    	$this->assign("deptId",$_SESSION['DEPT_ID']);
        $this->view('tutordeptlist');
    }

    /**
     * ��ʦ����---�����б�
     */
    function c_tutorPerson(){
    	$this->assign("userId",$_SESSION['USER_ID']);
        $this->view('tutorperson');
    }

    function c_personJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->groupBy = 'c.id';

		$rows = $service->pageBySqlId('ExaStatus');
		$coachplanDao=new model_hr_tutor_coachplan();
		$schemeinfoDao=new model_hr_tutor_schemeinfo();

		if(is_array($rows)){
	        //ѭ���жϽ�ɫ�ǵ�ʦ����ѧԱ
	        foreach($rows as $k => $v){
	           if($v['userAccount'] == $_SESSION['USER_ID']){
	                $rows[$k]['role'] = "��ʦ";
	           }else if($v['studentAccount'] == $_SESSION['USER_ID']){
	                $rows[$k]['role'] = "ѧԱ";
	           }
               $rows[$k]['isAddPlan'] = $coachplanDao->isAddPlan_d($v['id']);
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$v['schemeId']));
				if($schemeinfo['selfgraded']>0){
					$rows[$k]['tutorScore']=1;//��ʦ������
				}else{
					$rows[$k]['tutorScore']=0;
				}
				if($schemeinfo['staffgraded']>0){
					$rows[$k]['staffScore']=1;//ѧԱ������
				}else{
					$rows[$k]['staffScore']=0;
				}
//               $rewardDao=new model_hr_tutor_rewardinfo();
//               $rows[$k]['isPublish']=$rewardDao->getIsPublish_d($v['userAccount'],$v['studentAccount']);
	        }

		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �鿴ҳ�� - ����Ȩ��
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * ��ת���༭ �Ƿ���Ҫ�ƶ������ƻ� �Ƿ���Ҫ����HR��ģʽ�ύ�ܱ� ҳ��
	 */
	function c_toEditModel(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('editmodel' ,true);
	}

	/**
	 * �༭ �Ƿ���Ҫ�ƶ������ƻ� �Ƿ���Ҫ����HR��ģʽ�ύ�ܱ�
	 */
	function c_editModel(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$object = $_POST [$this->objName];
		if ($this->service->editModel_d($object)) {
			msg ( '�ύ�ɹ���' );
		}else{
			msg('�ύʧ�ܣ�');
		}
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
			$_POST['deptIdArr'] = $sysLimit;
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
	 * �رյ�ʦ��¼������رձ�ע
	 */
	 function c_close(){
	 	$object = $_POST [$this->objName];
		if ($this->service->close_d( $object)) {
			msg ( '�رճɹ���' );
		}else{
			msg('�ر�ʧ�ܣ�');
		}
	 }

	/**
	 * �༭���˷���
	 */
	 function c_editScore(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
	 	$object = $_POST [$this->objName];
		if ($this->service->editScore_d( $object)) {
			msg ( '�༭���˷����ɹ���' );
		}else{
			msg('�༭���˷���ʧ�ܣ�');
		}
	 }

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId('ExaStatus');
		if($_REQUEST['userNo2']){
			$personnel =new model_hr_personnel_personnel();
			$dataArr = $personnel->find(array('userNo'=>$_REQUEST['userNo2']));
			if(is_array($rows)){
		        //ѭ���жϽ�ɫ�ǵ�ʦ����ѧԱ
		        foreach($rows as $k => $v){
		           if($v['userAccount'] == $dataArr['userAccount']){
		                $rows[$k]['role'] = "��ʦ";
		           }else if($v['studentAccount'] == $dataArr['userAccount']){
		                $rows[$k]['role'] = "ѧԱ";
		           }
		        }
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$schemeDao=new model_hr_tutor_scheme();
		$schemeinfoDao=new model_hr_tutor_schemeinfo();
		for($i=0;$i<count($rows);$i++){
			$scheme=$schemeDao->find(array("tutorId"=>$rows[$i]['id']));
			if($scheme){
				$rows[$i]['sign']=1;
			}else
				$rows[$i]['sign']=0;
			$scheme="";
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$rows[$i]['schemeId']));
				if($schemeinfo['superiorgraded']>0){
					$rows[$i]['supScore']=1;//�ϼ�������
				}else{
					$rows[$i]['supScore']=0;
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
	function c_pageJsonForDept() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.id';
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//ϵͳȨ��
		$sysLimit = $this->service->this_limit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if(strstr($sysLimit,';;')){

			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();
		}

		//$service->asc = false;
		$rows = $service->pageBySqlId('ExaStatus');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$schemeDao=new model_hr_tutor_scheme();
       $schemeinfoDao=new model_hr_tutor_schemeinfo();
		for($i=0;$i<count($rows);$i++){
			$scheme=$schemeDao->find(array("tutorId"=>$rows[$i]['id']));
			if($scheme){
				$rows[$i]['sign']=1;
			}else
				$rows[$i]['sign']=0;
			$scheme="";
				$schemeinfo= $schemeinfoDao->find(array('tutorassessId'=>$rows[$i]['schemeId']));
				if($schemeinfo['assistantgraded']>0){
					$rows[$i]['assistantScore']=1;//����������
				}else{
					$rows[$i]['assistantScore']=0;
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
	 * ��ת��������ʦ������Ϣ��ҳ��
	 */
	function c_toAdd() {
		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);
		$this->view('add' ,true);
	}

	/**
	 * ��ת���༭��ʦ������Ϣ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('edit' ,true);
	}

	/**
	 * ��ת�������쵼ҳ��
	 */
	function c_toEditLeader() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('edit-leader' ,true);
	}

	/**
	 * ��ת���鿴��ʦ������Ϣ��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /**
     * ��ת���رյ�ʦ��¼��ҳ��
     */
	function c_toCloseTutorrecords(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('close' ,true);
	}

	/**
	 * ��ת���༭���˷�����ҳ��
	 */
	function c_toEditScore(){
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		if(!empty($obj)){
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
		}
		$this->view('editscore' ,true);
	}

	/**
	 * ��ָ����ʦ����������
	 */
	function c_newadd(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id=$this->service->newadd_d($_POST[$this->objName]);
		if($id){
			msg("����ɹ�");
		}
	}

	/**
	 * ���һ����ʦ��¼
	 */
	function c_complete(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		try{
			$this->service->complete_d($_POST['id']);
			echo 1;
		}catch(exception $e){
			echo 0;
		}
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}


	/****************** ����ָ����ʦ **************************/
	/**
	 * ָ����ʦ
	 */
	function c_toSetTutor(){
		//������Ϣ��Ⱦ
		$personnelInfo = $this->service->getPersonnelInfo_d($_GET['userAccount']);
		$this->assignFunc($personnelInfo);

		//�ʼ���Ϣ��Ⱦ��ͨ��ֱ���������ʳ����˲�ͬ
		if($personnelInfo['deptId']==32){
			$mailArr = $this->service->getMailInfoSpecial_d();
			$this->assignFunc($mailArr);
		}else{
			$mailArr = $this->service->getMailInfo_d();
			$this->assignFunc($mailArr);
		}
		$this->view('settutor' ,true);
	}

	/*
	 * ��ָ����ʦ�����ʼ�
	 */
	function c_toUnsetTutor(){
		//������Ϣ��Ⱦ
		$personnelInfo = $this->service->getPersonnelInfo_d($_GET['userAccount']);
		$this->assignFunc($personnelInfo);

		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);
		$this->assign("perid",$_GET['id']);
		$this->view('unsettutor' ,true);
	}


	/****************** ��ְָ֪ͨ����ʦ **************************/
	/**
	 * ָ����ʦ
	 */
	function c_toSetEntryTutor(){

		//��ְ��Ϣ��Ⱦ
		$personnelInfo = $this->service->getEntryInfo_d($_GET['entryId']);
		$this->assignFunc($personnelInfo);

		//��ʦ�����Ϣ��Ⱦ
		$recordsInfo = $this->service->find(array('userAccount'=>$personnelInfo['tutorId']));
		$this->assign("userNo",$recordsInfo['userNo']);
		$this->assign("deptName",$recordsInfo['deptName']);
		$this->assign("deptId",$recordsInfo['deptId']);
		$this->assign("jobName",$recordsInfo['jobName']);
		$this->assign("jobId",$recordsInfo['jobId']);

		//��ְ����(ҳ��������)
		$this->assign("entryDate",$_GET['entryDate']);
		//�ʼ���Ϣ��Ⱦ
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view('setentrytutor' ,true);
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

		$title = '��ʦ������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/**
	 * ����excel
	 */
	function c_export(){
		if($_GET['deptid']){
			$this->service->searchArr['deptId']=$_GET['deptid'];
		}
		print_r($this->service->searchArr);
		$planEquRows=$this->service->list_d();
		$exportData = array();
		if($planEquRows){
			foreach ( $planEquRows as $key => $val ){
				$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
				$exportData[$key]['userName']=$planEquRows[$key]['userName'];
				$exportData[$key]['jobName']=$planEquRows[$key]['jobName'];
				$exportData[$key]['studentNo']=$planEquRows[$key]['studentNo'];
				$exportData[$key]['studentName']=$planEquRows[$key]['studentName'];
				$exportData[$key]['studentDeptName']=$planEquRows[$key]['studentDeptName'];
				$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
				$exportData[$key]['becomeDate']=$planEquRows[$key]['becomeDate'];
				$exportData[$key]['status']=$this->service->statusDao->statusKtoC($planEquRows[$key]['status']);
				$exportData[$key]['assessmentScore']=$planEquRows[$key]['assessmentScore'];
				$exportData[$key]['rewardPrice']=$planEquRows[$key]['rewardPrice'];
				$exportData[$key]['closeReason']=$planEquRows[$key]['closeReason'];
				$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			}
		}
		return $this->service->export($exportData);
	}



	/******************* E ���뵼��ϵ�� ************************/
}
?>