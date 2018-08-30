<?php
/**
 * @author Administrator
 * @Date 2012-07-06 15:20:28
 * @version 1.0
 * @description:����������Ʋ�
 */
class controller_hr_recruitment_resume extends controller_base_action {

	function __construct() {
		$this->objName = "resume";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * ��ת�����������б�
	 */
	function c_page() {
		$this->view('list');
	}

	function c_pageJson(){
		foreach ($_REQUEST as $key=>&$val){
			$val=urldecode($val);
		}

		parent::c_pageJson();
	}

	/**
	 * ��ת���ҵļ��������б�
	 */
	function c_mypage() {
		$this->view('mylist');
	}

	/**
	 * ��ת��������������ҳ��
	 */
	function c_toAdd() {
		//��ȡ��Ƭ
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("parentId",$_GET['id']);
		if($_GET['type']=='apply')
			$this->assign("actionurl","hr_recruitment_applyResume");
		else if($_GET['type']=='recommend'){
			$recommend = new model_hr_recruitment_recomResume();
			if(($recommend->findAll(array("parentId"=>$_GET['id'])))==false)
				$this->assign("actionurl","hr_recruitment_recomResume");
			else
				msg("�Ѿ�����һ������");
		}
		else{
			$this->assign('actionurl','hr_recruitment_resume');
		}
		$this->view ('add' ,true);
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
		if ($obj ['sex'] == '��') {
			$this->assign ( 'sexM', 'checked' );
		} else if ($obj ['sex'] == 'Ů') {
			$this->assign ( 'sexW', 'checked' );
		}
		 //��ȡ��Ƭ
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		//����
		$file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);

		$this->showDatadicts ( array ('post' => 'YPZW' ), $obj ['post'] );
		$this->showDatadicts ( array ('sourceA' => 'JLLY' ), $obj ['sourceA'] );
		$this->showDatadicts ( array ('languageGrade' => 'WYSP' ), $obj ['languageGrade'] );
		$this->showDatadicts ( array ('language' => 'HRYZ' ), $obj ['language'] );
		$this->showDatadicts ( array ('computerGrade' => 'JSJSP' ), $obj ['computerGrade'] );
		$this->showDatadicts ( array ('education' => 'HRJYXL' ), $obj ['education'] );
		$this->view ('edit' ,true);
	}

	/**
    *�����鿴 TAB
    */
	function c_toViewTab(){
		$this->assign("resumeId" ,$_GET['id']);
		$this->view('view-tab');
	}

	/**
	 * ��ת���鿴��������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��ȡ��Ƭ
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		//����
		$file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//���¼������һ�������
		$this->service->updateViewer($_GET ['id']);

		$this->assign('education', $this->getDataNameByCode($obj['education']));
		$this->view ( 'view' );
	}

	function c_toRead() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->find (array("resumeCode"=>$_GET ['code']));
		//��ȡ��Ƭ
		$photo= $this->service->getFilePhoto_d ( $obj ['id']);
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		//����
		$file2 = $this->service->getFilesByObjId($obj['id'], false, 'oa_hr_recruitment_resume2');
		$this->assign("file2",$file2);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('education', $this->getDataNameByCode($obj['education']));
		$this->view ( 'view' );
	}

	/**
	* תΪԱ������
	*/
	function c_ajaxTurnType() {
		//$this->permDelCheck ();
		try {
			$this->service->turnType_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * ���������
	 */
	function c_ajaxBlacklist() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxBlacklist_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxReservelist() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxReservelist_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxCompanyResume() {
		//$this->permDelCheck ();
		try {
			$this->service->ajaxCompanyResume_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	function c_ajaxChangeResume() {
			//$this->permDelCheck ();
		$flag=$this->service->ajaxChangeResume_d ( $_POST ['id'],$_POST ['resumeType'] );
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}

	/**
	 * �߼�����
	 */
	function c_search() {
		$this->assign("gridName", $_GET['gridName']);
		$this->display('search');
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}


	/**
	 * �����������
	 */
	function c_applyadd($isAddInfo = false) {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$title = '������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		$objNameArr = array (
			0 => 'applicantName', //ӦƸ������
			1 => 'sex', //�Ա�
			2 => 'birthdate', //��������
			3 => 'phone', //��ϵ�绰
			4 => 'email', //��������
		    5 => 'politics', //������ò
		    6 => 'marital', //����״��
			7 => 'education', //ѧ��
			8 => 'postName', //ӦƸְλ
			9 => 'educationExp', //��������
			10 => 'workExp', //��������
			);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}


	/**
	 * ����excel
	 * 2013-03-30 duanlh
	 */
	function c_excelInTwo(){
		$title = '������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		$objNameArr = array (
			0 => 'applicantName', //ӦƸ������
			1 => 'sex', //�Ա�
			2 => 'birthdate', //��������
			3 => 'phone', //��ϵ�绰
			4 => 'email', //��������
		    5 => 'graduateDate', //��ҵʱ��
			6 => 'workSeniority', //��������
			7 => 'marital', //����״��
			8 => 'educationName', //ѧ��
			9 => 'postName', //ӦƸְλ
			10 => 'reserveA', //ӦƸְλС��
			11 => 'wishAdress', //���������ص�
			12 => 'computerGradeName', //�����ˮƽ
			13 => 'languageName', //����
			14 => 'languageGradeName', //����ˮƽ
		    15 => 'college', //��ҵԺУ
			16=> 'major', //��ҵרҵ
			17 => 'wishSalary', //����нˮ
			18 => 'prevCompany', //�ϼҹ�˾����
			19 => 'hillockDate', //����ʱ��
			20 => 'specialty', //�س�
			21=> 'sourceAName', //ӦƸ��������
			22 => 'sourceB', //ӦƸ����С��
			23=> 'selfAssessment', //��������
			24 => 'remark', //��ע
			25 => 'post', //ӦƸְλ����
			);
		$resultArr = $this->service->addExecelData_d ($objNameArr);
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}



	/******************* E ���뵼��ϵ�� ************************/
	/*********************************************�˲ſ�***********************************************************************/
	/**
	 * �˲ſ��б�
	 */
	function c_talentPoolList(){
		$this->view('talentPoolList');
	}

	/**
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
	 */
	function c_ajaxdeletes() {
		//$this->permDelCheck ();
		try {
			$flag = $this->service->deletes_d ( $_POST ['id'] );
			echo $flag;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * �˲ſ��б�
	 */
	function c_invitationList(){
		$this->view('invitationList');
	}


	/*******************add chenrf 20130515*********************/

 	/**
	 * ����Ƿ�������������������
	 */
	function c_checkInvitation(){
		$this->permCheck();
		$id=$_REQUEST['resumeId'];
		$re=$this->service->getInvaitation($id);
		print_r($re);
		if($re)
			echo 1;
		else
			echo 0;
	}

 	/**
	 *
	 * ������ְ֪ͨ
	 */
	function c_toSendNotifi(){
		$this->permCheck(); //��ȫУ��
		$resumeRow=$this->service->get_d($_GET['resumeId']);
		$this->assignFunc($resumeRow);
		$this->assign("SignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$resumeRow['post']);//ְλ����
		$branchDao = new model_deptuser_branch_branch();
		$branchArr = $branchDao->findAll();
		$select='';
		if(is_array($branchArr)){
			foreach ($branchArr as $branch) {
				$select .= "<option value=".$branch['ID'].">".$branch['NameCN']."</option>";
			}
		}
		$this->assign("selectName",$select);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());
		$this->showDatadicts ( array ('hrSourceType1Name' => 'HRBCFS' ));
		$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ));
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->assign("user",$_SESSION['USERNAME']);
		$this->assign("useSignDate",date('Y-m-d'));
		$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ));
		$this->assign("managerId",$_SESSION['USER_ID']);
		$this->assign("manager",$_SESSION['USERNAME']);
		$this->assign("SignDate",date('Y-m-d'));
		$entryNoticeDao=new model_hr_recruitment_entryNotice();
		$this->assign('toccMail',$entryNoticeDao->mailArr['sendName']);
		$this->assign('toccMailId',$entryNoticeDao->mailArr['sendUserId']);
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//��Ա����
		$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' )); //���ʼ���

		$interviewDao = new model_hr_recruitment_interview();//����ָ����ʦ
		$interviewObj = $interviewDao -> find(array("resumeId" => $_GET['resumeId']));
		$this->assign("tutor", $interviewObj['tutor']);
		$this->assign("tutorId", $interviewObj['tutorId']);

		$entryNoticeRow = $entryNoticeDao->find("resumeId='".$_GET['resumeId']."'");
		if($entryNoticeRow){
			unset($entryNoticeRow['id']);
			foreach($entryNoticeRow as $key=>$val){
				$this->assign($key,$val);
			}
			//��ȡ�ʼ����ݼ�����
			$content = $this->service->get_table_fields('oa_hr_recruitment_entrynotice',"resumeId='".$_GET['resumeId']."'",'content');
			    $this->show->assign("file",$this->service->getFilesByObjId($resumeRow['id'],true,'oa_hr_entryNotice_email')); //��ʾ������Ϣ
			    $this->assign('content',$content);
		 	$this->showDatadicts ( array ('postType' => 'YPZW' ),$entryNoticeRow['postType']);//ְλ����
			$this->showDatadicts ( array ('useHireType' => 'HRLYXX' ),$entryNoticeRow['useHireType']);//¼����ʽ
			$this->showDatadicts ( array ('hrSourceType1' => 'JLLY' ),$entryNoticeRow['hrSourceType1']);	//������Դ����
			$this->showDatadicts ( array ('wageLevelCode' => 'HRGZJB' ),$entryNoticeRow['wageLevelCode']); //���ʼ���
			$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$entryNoticeRow['addTypeCode']);//��Ա����
			$this->view("sendNotify-edit" ,true);
		}
		else{
			$this->view("sendNotify-add" ,true);
		}
	}

	/**
	 * ��������+����������Ƿ��ظ�
	 */
	function c_checkRepeat() {
		$applicantName = util_jsonUtil::iconvUTF2GB ( $_POST['applicantName'] );
		$email = util_jsonUtil::iconvUTF2GB ( $_POST['email'] );
		$num = $this->service->findCount(array('applicantName' => $applicantName ,'email' => $email));
		if ($num > 0) {
			$result = "false";
		} else {
			$result = "true";
		}
		echo $result;
	}
}
?>