<?php
/**
 * @author Administrator
 * @Date 2012��10��16�� ���ڶ� 9:21:33
 * @version 1.0
 * @description:��Ƹ�ƻ����Ʋ�
 */
class controller_hr_recruitplan_plan extends controller_base_action {

	function __construct() {
		$this->objName = "plan";
		$this->objPath = "hr_recruitplan";
		parent::__construct ();
	}

	/**
	 * ��ת����Ƹ�ƻ��б�
	 */
	function c_page() {
		$this->assign('type', $_GET['type']); //����type��ҳ����ʾ����
		$this->view('list');
	}

	/**
	 * �ҵ��б�Json
	 */
	function c_MyPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->searchArr ['formManId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
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
	 * ��дpageJSOn����
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//ת��������
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
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
	 * ��ת��������Ƹ�ƻ�ҳ��
	 */
	function c_toAdd() {
		$area = new includes_class_global();

		$this->show->assign('area_select',$area->area_select());
		$this->assign( 'formManId',$_SESSION['USER_ID'] );
		$this->assign( 'formManName',$_SESSION['USERNAME'] );
		$this->assign( 'resumeToId',$_SESSION['USER_ID'] );
		$this->assign( 'resumeToName',$_SESSION['USERNAME'] );

		//��ȡ����ID
		$deptDao=new model_common_otherdatas();
		$deptmentDao=new model_deptuser_dept_dept();
		$this->assign('deptName' ,$deptDao->getUserDatas( $_SESSION['USER_ID'] , 'DEPT_NAME'));
		$this->assign('deptId' , $_SESSION['DEPT_ID']);
		$sendTime = date("Y-m-d");
		$this->assign( 'formDate',$sendTime );
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//��Ա����
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ));//���鲹�䷽ʽ
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ));//�ù�����
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ));//����
		$this->showDatadicts ( array ('education' => 'HRZYXL' ));//ѧ��
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//ְλ����
		$this->view ('add' ,true);
	}

	/**
	 *���������ύ����
	 */
	function c_add(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj=$_POST[$this->objName];
		$id=$this->service->add_d($obj);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if($id){
			if ("audit" == $actType) {//�ύ������
				succ_show ( 'controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$obj['deptId']);
			} else {
				msg('����ɹ�');
			}
		}else{
			msg('����ʧ��');
		}
	}

	/**
	 * �༭����
	 */
	function c_edit(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$re=$this->service->edit_d($_POST[$this->objName]);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$id=$_POST[$this->objName]['id'];
		if($re){
			if ("audit" == $actType) {//�ύ������
				succ_show ( 'controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_POST[$this->objName]['deptId']);
			} else {
				msg('����ɹ�');
			}
		} else
			msgGo('����ʧ��');
	}

	/**
	 * ��ת���༭��Ƹ�ƻ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '��') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == 'Ů') {
			$this->assign ( 'womanCheck', 'checked' );
		} else{
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$obj['addTypeCode']);//��Ա����
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ),$obj['addModeCode']);//���鲹�䷽ʽ
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ),$obj['employmentTypeCode']);//�ù�����
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ),$obj['maritalStatus']);//����
		$this->showDatadicts ( array ('education' => 'HRZYXL' ),$obj['education']);//ѧ��
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$obj['postType']);//ְλ����
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view ('edit' ,true);
	}
	/**
	 * ��ת���鿴Ƹ�ƻ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '��' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '��' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '�з���Ŀ' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '������Ŀ' );
		} else  {
			$this->assign ( 'projectType', '' );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'view' );
	}

	/**
	 * ��ת���༭��Ƹ�ƻ�ҳ��
	 */
	function c_toAuditEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['sex'] == '��') {
			$this->assign ( 'manCheck', 'checked' );
		} else if ($obj ['sex'] == 'Ů') {
			$this->assign ( 'womanCheck', 'checked' );
		} else{
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ),$obj['addTypeCode']);//��Ա����
		$this->showDatadicts ( array ('addModeCode' => 'HRBCFS' ),$obj['addModeCode']);//���鲹�䷽ʽ
		$this->showDatadicts ( array ('employmentTypeCode' => 'HRPYLX' ),$obj['employmentTypeCode']);//�ù�����
		$this->showDatadicts ( array ('maritalStatus' => 'HRHYZK' ),$obj['maritalStatus']);//����
		$this->showDatadicts ( array ('education' => 'HRZYXL' ),$obj['education']);//ѧ��
		$this->showDatadicts ( array ('postType' => 'YPZW' ),$obj['postType']);//ְλ����
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view ('audit-edit' ,true);
	}

	/**
	 * �������޸���Ա����
	 */
	function c_auditEdit(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$oldRow=$this->service->get_d($_POST[$this->objName]['id']);
		if($oldRow['needNum']!=$_POST[$this->objName]['needNum']||strlen($oldRow['positionLevel'])!=strlen($_POST[$this->objName]['positionLevel'])){
			$_POST[$this->objName]['needNumEdit']=$oldRow['needNum'];
			$_POST[$this->objName]['positionLevelEdit']=$oldRow['positionLevel'];
			$id=$this->service->edit_d($_POST[$this->objName]);
			if($id){
				succ_show ( 'controller/hr/recruitplan/ewf_edit_index.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id']);
			}else{
				msgGo('�޸�ʧ��',"?model=hr_recruitment_apply&action=mypage");
			}
		}else{
			$id=$this->service->edit_d($_POST[$this->objName]);
			if($id){
				msgGo('�޸ĳɹ�',"?model=hr_recruitment_apply&action=mypage");
			}else{
				msgGo('�޸�ʧ��',"?model=hr_recruitment_apply&action=mypage");
			}
		}
	}

	/**
	 * ��Ƹ��������Ƹ�ƻ��б�
	 */
	function c_recruit() {
		$this->view('recruit-list');
	}

	/**
	 * ת��excel����ҳ��
	 */
	function c_toImport(){
		$this->view('excelin');
	}

	/**
	 * excel�������
	 */
	function c_excelIn(){
		set_time_limit(0);
		$actionType='';
		if($_POST['actionType']=='1')
		$actionType='����';
		$resultArr = $this->service->addExecelData_d ($actionType);
		$title = '��Ա������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * �ı�״̬
	 */
	function c_tochangeState(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id=$_GET['id'];
		$state=$_GET['state'];
		if($this->service->changeState($id,$state)){
			msg('����ɹ�');
		}else
		msg('���ʧ��');
	}

	/**
	 * �ı�״̬
	 * ajax
	 */
	function c_changeState(){
		$id=$_REQUEST['id'];
		$state=$_REQUEST['state'];
		if($this->service->changeState($id,$state)){
			echo 1;
		}else
		echo 0;
	}

	/**
	 * ���为����
	 */
	function c_toGive() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '��' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '��' );
		}
		if ($obj ['projectType'] =="YFXM") {
			$this->assign ( 'projectType', '�з���Ŀ' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '������Ŀ' );
		}
		if ($obj ['positionLevel'] =="1") {
			$this->assign ( 'positionLevel', '����' );
		} else if ($obj ['positionLevel'] == "2") {
			$this->assign ( 'positionLevel', '�м�' );
		}else if ($obj ['positionLevel'] == "3") {
			$this->assign ( 'positionLevel', '�߼�' );
		}
		$datestr = date('Y-m-d');
		$this->assign("assignedManName",$_SESSION['USERNAME']);
		$this->assign("assignedManId",$_SESSION['USER_ID']);
		$this->assign("assignedDate",$datestr);
		$this->view ('give' ,true);
	}

}
?>