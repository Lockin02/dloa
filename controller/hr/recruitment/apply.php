<?php
/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 13:20:21
 * @version 1.0
 * @description:��Ա�������Ʋ�
 */
class controller_hr_recruitment_apply extends controller_base_action {

	function __construct() {
		$this->objName = "apply";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ա������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����Ա����(��Ƹ��)�б�
	 */
	function c_pageTeam() {
		$this->view('list-team');
	}

	/**
	 * ��ת���ҵ���Ա������б�
	 */
	function c_mypage() {
		$this->view('mylist');
	}

	/**
	 * ��ת����Э������Ա������б�
	 */
	function c_myassistpage() {
		$this->view('myassistlist');
	}

	/**
	 * ��ת���Ҹ������Ա������б�
	 */
	function c_mymainpage() {
		$this->view('mymainlist');
	}

	/**
	 * ѡ����Ա���뵯����ѡ��ҳ��
	 */
	function c_selectApply() {
		$this->view('selectapply');
	}

	/**
	 * ��ת��������Ա�����ҳ��
	 */
	function c_toAdd() {
		$area = new includes_class_global();
		$this->assign('area_select' ,$area->area_select());
		$this->assign('formManId', $_SESSION['USER_ID']);
		$this->assign('formManName', $_SESSION['USERNAME']);
		$this->assign('resumeToId', $_SESSION['USER_ID']);
		$this->assign('resumeToName', $_SESSION['USERNAME']);

		//��ȡ����
		$this->assign('deptName' ,$_SESSION["DEPT_NAME"]);
		$this->assign('deptId' , $_SESSION['DEPT_ID']);
		$sendTime = date("Y-m-d");
		$this->assign('formDate' ,$sendTime);
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX')); //��Ա����
		$this->showDatadicts(array('addModeCode' => 'HRBCFS')); //���鲹�䷽ʽ
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX')); //�ù�����
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK')); //����
		$this->showDatadicts(array('education' => 'HRZYXL')); //ѧ��
		$this->showDatadicts(array('postType' => 'YPZW')); //ְλ����
		$this->view ('add' ,true);
	}

	/**
	 * ��ת���༭��Ա�����ҳ��
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
		$this->assign('editFromApply', $_GET ['editFromApply'] ); //�ж��Ǵ��ҵ���Ƹ������޸Ļ�����Ƹ��������޸�
		$this->assign('html',$this->service->showHtm($_GET ['editFromApply'],$obj)); //�����жϣ������Ƿ���ʾ"�ؼ�Ҫ��"������
		$area = new includes_class_global();
		$useAreaId = $obj['useAreaId'];
		$this->assign('area_select',$area->area_select($useAreaId));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//��Ա����
		$this->showDatadicts(array('addModeCode' => 'HRBCFS') ,$obj['addModeCode']);//���鲹�䷽ʽ
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX') ,$obj['employmentTypeCode']);//�ù�����
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK') ,$obj['maritalStatus']);//����
		$this->showDatadicts(array('education' => 'HRZYXL') ,$obj['education']);//ѧ��
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']);//ְλ����
		$this->assign("file" ,$this->service->getFilesByObjId ( $_GET ['id']) );
		$this->view('edit' ,true);
	}

	/**
	 * ��ת���༭��Ա�����ҳ��
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
		} else {
			$this->assign ( 'otherCheck', 'checked' );
		}
		if ($obj ['isEmergency'] == 1) {
			$this->assign ( 'isEmergency', 'checked' );
		} else if ($obj ['isEmergency'] == 0) {
			$this->assign ( 'noEmergency', 'checked' );
		}
		$area = new includes_class_global();
		$useAreaId = $obj['useAreaId'];
		$this->assign('area_select' ,$area->area_select($useAreaId));
		$this->showDatadicts(array('addTypeCode' => 'HRZYLX') ,$obj['addTypeCode']);//��Ա����
		$this->showDatadicts(array('addModeCode' => 'HRBCFS') ,$obj['addModeCode']);//���鲹�䷽ʽ
		$this->showDatadicts(array('employmentTypeCode' => 'HRPYLX') ,$obj['employmentTypeCode']);//�ù�����
		$this->showDatadicts(array('maritalStatus' => 'HRHYZK') ,$obj['maritalStatus']);//����
		$this->showDatadicts(array('education' => 'HRZYXL') ,$obj['education']);//ѧ��
		$this->showDatadicts(array('postType' => 'YPZW') ,$obj['postType']);//ְλ����
		$this->assign("file" ,$this->service->getFilesByObjId( $_GET['id']) );
		if($_GET ['isAudit']=='no'){
			$this->assign ( 'postType', $obj['postType'] );
			$this->view ('noAudit-edit' ,true);
		} else {
			$this->view ('audit-edit' ,true);
		}
	}

	function c_toTabView() {
		$obj=$this->service->get_d($_GET['id']);
		$stateC=$this->service->statusDao->statusKtoC($obj['state']);
		$this->assign ( "id", $_GET['id'] );
		$this->assign('stateC', $stateC);
		$this->assign('ExaStatus', $obj['ExaStatus']);
		$this->view ( 'tabview' );
	}

	/**
	 * ��ת���鿴��Ա�����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '��' );
		} else if ($obj['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '��' );
		}
		if ($obj['projectType'] == "YFXM") {
			$this->assign ( 'projectType', '�з���Ŀ' );
		} else if ($obj['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '������Ŀ' );
		} else  {
			$this->assign ( 'projectType', '' );
		}
		if($_GET['judge'] == 1) {
				$html = <<<EOT
				<tr style="height:80px;">
					<td class="form_text_left_three">�ؼ�Ҫ��</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['keyPoint']}</textarea>
					</td>
				</tr>
				<tr style="height:80px;">
					<td class="form_text_left_three">ע������</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['attentionMatter']}</textarea>
					</td>
				</tr>
				<tr style="height:80px;">
					<td class="form_text_left_three">�����쵼ϲ��</td>
					<td class="form_text_right_three" colspan="5">
						<textarea class="textarea_read_overflow" readonly>{$obj['leaderLove']}</textarea>
					</td>
				</tr>
EOT;
			$this->assign ( 'points', $html );
		} else if ($_GET['actType'] =='audit') {
			$html = $this->service->getDeptPer_d($_GET['id']);
			$this->assign ( 'points', $html );
		} else {
			$this->assign ( 'points', '' );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴��Ա�����ҳ��
	 */
	function c_toEditView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj['isEmergency'] == "1") {
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
		if ($obj ['positionLevel'] =="1") {
			$positionLevel='����' ;
		} else if ($obj ['positionLevel'] == "2") {
			$positionLevel='�м�';
		}else if ($obj ['positionLevel'] == "3") {
			$positionLevel='�߼�';
		}else {
			$positionLevel= '';
		}
		if ($obj ['positionLevelEdit'] =="1") {
			$positionLevelEdit='����' ;
		} else if ($obj ['positionLevelEdit'] == "2") {
			$positionLevelEdit='�м�';
		} else if ($obj ['positionLevelEdit'] == "3") {
			$positionLevelEdit='�߼�';
		} else {
			$positionLevelEdit= '';
		}
		if($obj['needNum']!=$obj['needNumEdit']){
			$needNumStr="<font color=red>".$obj['needNumEdit']." --> ".$obj['needNum']."<font>";
			$this->assign('needNum',$needNumStr);
		}
		if($obj ['positionLevel'] != $obj ['positionLevelEdit']){
			$positionLevelStr = "<font color=red>".$positionLevelEdit." --> ".$positionLevel."<font>";
			$this->assign('positionLevel',$positionLevelStr);
		} else {
			$this->assign('positionLevel',$positionLevel);
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'edit-view' );
	}

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
		$this->assign("assignedManName" ,$_SESSION['USERNAME']);
		$this->assign("assignedManId" ,$_SESSION['USER_ID']);
		$this->assign("assignedDate" ,$datestr);
		$this->view ('give' ,true);
	}

	/**
	 * @author Admin
	 *�޸���Ƹ������
	 */
	function c_toEditHead() {
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
		$this->assign('editHeadTime' ,date('Y-m-d H:i:s'));
		$this->view ('edit-head' ,true);
	}

	/**
	 * �ҵ��б�Json
	 */
	function c_myListJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$service->searchArr ['formManId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$service->groupBy='c.id';
		$rows = $service->page_d ('select_list');
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
	 * ��������
	 */
	function c_add(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$obj = $_POST[$this->objName];
		$obj['actType'] = $actType;
		$id = $this->service->add_d($obj);
		if($id) {
			if('onSubmit' == $actType) {
				if($this->service->changeState($id)) {
					$obj = $this->service->get_d($id);
					$this->service->thisMail_d($obj);
					msg('�ύ�ɹ�');
				} else {
					msg('�ύʧ��');
				}
			} else {
				msg('����ɹ�');
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * �༭����
	 */
	function c_edit() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->edit_d($_POST[$this->objName]);
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if($id) {
			if('onSubmit' == $actType) { //�ύ
				$id = $_POST[$this->objName]['id'];
				if($this->service->changeState($id)) {
					$obj = $this->service->get_d($id);
					$this->service->thisMail_d($obj);
					msg('�ύ�ɹ�');
				} else {
					msg('�ύʧ��');
				}
			} else {
				msg('����ɹ�');
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * �������ύ�޸���Ա���� (���)
	 */
	function c_auditEdit(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$oldRow = $this->service->get_d($_POST[$this->objName]['id']);
		$newRow = $_POST[$this->objName];

		$dictDao = new model_system_datadict_datadict();
		$newRow['postTypeName'] = $dictDao->getDataNameByCode($newRow['postType']);

		$newRow = $this->service->fillEdit($oldRow ,$newRow);
		if($newRow != '') {
			$id = $this->service->edit_d($newRow);
			if($id) {
				$this->service->auditEditMail_d($oldRow ,$newRow);
				succ_show('controller/hr/recruitment/ewf_edit_index.php?actTo=ewfSelect&billId='.$newRow['id'].'&billDept='.$newRow['deptId']);
			} else {
				msg('�޸�ʧ��');
			}
		} else {
			$id = $this->service->edit_d($_POST[$this->objName]);
			if($id) {
				msg('�޸ĳɹ�');
			} else {
				msg('�޸�ʧ��');
			}
		}
	}

	/**
	 * �����Ա���루��������
	 */
	function c_noAuditEdit(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$oldRow = $this->service->get_d($_POST[$this->objName]['id']);
		$newRow = $_POST['apply'];
		$diff = array();
		//��ȡ�ı��˵�ֵ
		foreach($newRow as $key => $val){
			if($val != $oldRow[$key]){
				$diff[$key] = $val;
			}
		}
		if($diff){
			//����������
			$persons = $this->service->getAuditPersons_d($newRow);
			//�����ʼ�֪ͨ
			$mail = $this->service->sendEmail_d($diff ,$oldRow ,$persons);
		}
		$id = $this->service->edit_d($newRow);
		if($id) {
			msg('�޸ĳɹ���');
		} else {
			msg('�޸�ʧ�ܣ�');
		}
	}

	/**
	 * ��Ա�����޸���������
	 */
	function c_dealEditApply(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		if(! empty ( $_GET ['spid'] ))
			$this->service->dealEditApply( $_GET ['spid']);
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ��Ա������������
	 */
	function c_dealAfterAudit() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok") {  //����ͨ��
				$this->service->applyAuditMail_d( $folowInfo['objId'] ,'ͨ��');
			} else if ($folowInfo['examines'] == "no") {
				$this->service->applyAuditMail_d( $folowInfo['objId'] ,'��ͨ��');
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ���为����
	 */
	function c_assignHead(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id = $this->service->assignHead_d($_POST[$this->objName]);
		if($id){
			msg('�´�ɹ�');
		} else {
			msg('�´�ʧ��');
		}
	}

	/**
	 * �޸ĸ�����
	 */
	function c_editHead() {
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$id=$this->service->editHead_d($_POST[$this->objName]);
		if($id){
			msg('�޸ĳɹ�');
		}else{
			msg('�޸�ʧ��');
		}
	}

	/**
	 * ��ȡ�����Է�ҳ����ת��Json
	 */
	function c_myMainPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['recruitManId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$listRows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows = $service->dealRows_d($rows,$listRows );

		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡЭ�����Է�ҳ����ת��Json
	 */
	function c_myHelpPageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$this->service->searchArr['mylinkId'] = $_SESSION['USER_ID'];
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$listRows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows = $service->dealRows_d($rows ,$listRows);
		$arr = array();
		$arr ['collection'] = $newRows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ����ת��Json(����С�ƺ��ܼ�)
	 */
	function c_pageJsonList() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->groupBy='c.id';
		$rows = $service->page_d ('select_list');
		$listRows = $service->list_d ('select_list');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows);
		$newRows=$service->dealRows_d($rows,$listRows );
		$arr = array ();
		$arr ['collection'] = $newRows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
     * ��ȡ����ת��Json(������Ա����)
     */
    function c_teamPageJsonList() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->groupBy='c.id';
        //ϵͳȨ��
        $sysLimit = $this->service->this_limit['����Ȩ��'];
        $deptDao=new model_deptuser_dept_dept();
        $deptIds= $deptDao->getDeptIdsByUserId($_SESSION['USER_ID']);

        //���´� �� ȫ�� ����
        if(strstr($sysLimit,';;')){

        }else if(!empty($sysLimit)){//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
            $_POST['deptIdArr'] = $sysLimit;
        }else{
            if(!empty($deptIds )){
                $_POST['deptIdArr'] =$deptIds;
            }else{
                $_POST['deptIdArr'] ='noId';
            }
        }
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->page_d ('select_list');
        $listRows = $service->list_d ('select_list');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows);
        $newRows=$service->dealRows_d($rows,$listRows );
        $arr = array ();
        $arr ['collection'] = $newRows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($newRows ? count ( $newRows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['deptLeadFlag'] =empty($deptIds)?0:1;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * �ı������״̬(ajax)
	 */
	function c_changeState(){
		$flag = $this->service->changeState_d($_POST);
		if($flag) {
			//add chenrf 20130522
			$obj = $this->service->get_d($_POST[id]);
			$msg = $_POST['msg'];
			$this->service->thisMail_d($obj,$_POST['state'],$msg);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * �ı������״̬
	 */
	function c_tochangeState(){
		$state = isset ( $_GET ['state'] ) ? $_GET ['state'] : null;
		$stateName = $this->service->statusDao->statusKtoC( $state );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('state',$state);
		$this->assign('stateName' ,$stateName);
		$this->view('changestate' ,true);
	}

	/**
	 * �ı������״̬
	 */
	function c_changeApplyState(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$state = $obj['state'];
		$re = $this->service->editState_d($obj);
		if($re) {
			$obj = $this->service->get_d($obj['id']);
			$this->service->emailNotice_d($obj ,$state);
			msg('����ɹ�');
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��Ա���룬��ص��ݲ���
	 */
	function c_getBack(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$objId = $_POST['id'];
		$obj = $this->service->get_d($objId);
		$obj['state'] = 0;
		$re = $this->service->editState_d($obj);
		if($re){
			$mail = new model_common_mail();
			$title = '��Ա���뵥�ݴ��֪ͨ';
			$content = '����,��'.$_SESSION['USERNAME'].'���Ե��ݱ�š�'.$obj['formCode'].'�������˴�ز�����';
			$mail->mailGeneral($title,$obj['formManId'],$content,null);
			echo $re;
		}
	}

	 /*********************���뵼��*************************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '��Ա������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	function c_toExcelOut(){
		$this->showDatadicts ( array ('postType' => 'YPZW' ));
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//��Ա����
		$this->view("excelout");
	}

	function c_excelOut(){
		$object = $_POST[$this->objName];
		$this->service->searchArr['state_d'] = '0';
		if(!empty($object['formCode']))	//���ݱ��
			$this->service->searchArr['formCode'] = $object['formCode'];
		if(!empty($object['formManName']))	//�����
			$this->service->searchArr['formManName'] = $object['formManName'];
		if(!empty($object['resumeToName']))	//�ӿ���
			$this->service->searchArr['resumeToNameSearch'] = $object['resumeToName'];
	 	if(!empty($object['deptNameO'])) //ֱ������
			$this->service->searchArr['deptNameO'] = $object['deptNameO'];
		if(!empty($object['deptNameS'])) //��������
			$this->service->searchArr['deptNameS'] = $object['deptNameS'];
		if(!empty($object['deptNameT'])) //��������
			$this->service->searchArr['deptNameT'] = $object['deptNameT'];
		if(!empty($object['deptNameF'])) //�ļ�������
			$this->service->searchArr['deptNameF'] = $object['deptNameF'];
		if(!empty($object['workPlace'])) //�����ص�
			$this->service->searchArr['workPlaceSearch'] = $object['workPlace'];
		if(!empty($object['postType']))//ְλ����
			$this->service->searchArr['postType'] = $object['postType'];
		if(!empty($object['positionName'])) //����ְλ
			$this->service->searchArr['positionName'] = $object['positionName'];
		if(!empty($object['positionLevel'])) //����
			$this->service->searchArr['positionLevelSearch'] = $object['positionLevel'];
		if(!empty($object['projectGroup'])) //������Ŀ��
			$this->service->searchArr['projectGroupSearch'] = $object['projectGroup'];
		if(($object['isEmergency'])!="") //�Ƿ����
			$this->service->searchArr['isEmergency'] = $object['isEmergency'];
		if(!empty($object['formDateBef'])) //���ʱ��
			$this->service->searchArr['formDateBefSearch'] = $object['formDateBef'];
		if(!empty($object['formDateEnd'])) //���ʱ��
			$this->service->searchArr['formDateEndSearch'] = $object['formDateEnd'];
		if(!empty($object['ExaDTBef'])) //����ͨ��ʱ��
			$this->service->searchArr['ExaDTBefSearch'] = $object['ExaDTBef'];
		if(!empty($object['ExaDTEnd'])) //����ͨ��ʱ��
			$this->service->searchArr['ExaDTEndSearch'] = $object['ExaDTEnd'];
		if(!empty($object['assignedDateBef'])) //�´�����
			$this->service->searchArr['assignedDateBef'] = $object['assignedDateBef'];
		if(!empty($object['assignedDateEnd'])) //�´�����
			$this->service->searchArr['assignedDateEnd'] = $object['assignedDateEnd'];
		if(!empty($object['createTimeBef'])) //¼������
			$this->service->searchArr['createTimeBefSearch'] = $object['createTimeBef'];
		if(!empty($object['createTimeEnd'])) //¼������
			$this->service->searchArr['createTimeEndSearch'] = $object['createTimeEnd'];
		if(!empty($object['entryDateBef'])) //����ʱ��
			$this->service->searchArr['entryDateBefSearch'] = $object['entryDateBef'];
		if(!empty($object['entryDateEnd'])) //����ʱ��
			$this->service->searchArr['entryDateEndSearch'] = $object['entryDateEnd'];
		if(!empty($object['addTypeCode'])) //��Ա����
			$this->service->searchArr['addTypeCode'] = $object['addTypeCode'];
		if(!empty($object['recruitManName'])) //��Ƹ������
			$this->service->searchArr['recruitManName'] = $object['recruitManName'];
		if(!empty($object['assistManName'])) //��ƸЭ����
			$this->service->searchArr['assistManNameSearch'] = $object['assistManName'];
		if(!empty($object['applyReason'])) //����ԭ��
			$this->service->searchArr['applyReasonSearch'] = $object['applyReason'];
		if(!empty($object['workDuty'])) //����ְ��
			$this->service->searchArr['workDutySearch'] = $object['workDuty'];
		if(!empty($object['jobRequire'])) //��ְҪ��
			$this->service->searchArr['jobRequireSearch'] = $object['jobRequire'];
		if(!empty($object['keyPoint'])) //�ؼ�Ҫ��
			$this->service->searchArr['keyPoint'] = $object['keyPoint'];
		if(!empty($object['attentionMatter'])) //ע������
			$this->service->searchArr['attentionMatter'] = $object['attentionMatter'];
		if(!empty($object['leaderLove'])) //�����쵼ϲ��
			$this->service->searchArr['leaderLove'] = $object['leaderLove'];
		if(!empty($object['applyRemark'])) //���ȱ�ע
			$this->service->searchArr['applyRemarkSearch'] = $object['applyRemark'];
		if(!empty($object['state'])){ //����״̬
			$tmp_state = $object['state'];
			$state = implode(',',$tmp_state);
			$this->service->searchArr['stateArr'] = $state;
		}
		if(!empty($object['ExaStatus'])) { //����״̬
			$tmp_ExaStatus = $object['ExaStatus'];
			$ExaStatus = implode(',',$tmp_ExaStatus);
			$this->service->searchArr['ExaStatusArr'] = $ExaStatus;
		}
		set_time_limit(0);
		$this->service->groupBy = 'c.id';
		$rows = $this->service->list_d('select_list');

		$exportData = array();
		if(is_array($rows)){
			foreach ( $rows as $key => $val ){
				$val['stateC'] = $this->service->statusDao->statusKtoC( $val ['state'] );
				if ($val['isEmergency'] == "1") {
					$isEmergency = '��';
				} else if ($val['isEmergency'] == "0") {
					$isEmergency = '��';
				}

				if ($val ['projectType'] =="YFXM") {
					$projectType = '�з���Ŀ';
				} else if ($val ['projectType'] == "GCXM") {
					$projectType = '������Ŀ';
				}

				if($val['entryNum'] == '') {
					$val['entryNum'] = 0;
				}

				if($val['beEntryNum'] == '') {
					$val['beEntryNum'] = 0;
				}

				if ($val['userName'] == '') {
					$userName = $val['employName'];
				} else if ($val['employName'] == '') {
					$userName = $val['userName'];
				} else {
					$userName = $val['userName'].','.$val['employName'];
				}

				//����ͨ��ʱ�䣨���������״̬����ʾ��
				if ($val['ExaStatus'] != '���') {
					$val['ExaDT'] = '';
				}

				$exportData[$key]['formCode'] = $val['formCode'];
				$exportData[$key]['stateC'] = $val['stateC'];
				$exportData[$key]['ExaStatus'] = $val['ExaStatus'];
				$exportData[$key]['formManName'] = $val['formManName'];
				$exportData[$key]['resumeToName'] = $val['resumeToName'];
				$exportData[$key]['deptNameO'] = $val['deptNameO'];
				$exportData[$key]['deptNameS'] = $val['deptNameS'];
				$exportData[$key]['deptNameT'] = $val['deptNameT'];
				$exportData[$key]['deptNameF'] = $val['deptNameF'];
				$exportData[$key]['workPlace'] = $val['workPlace'];
				$exportData[$key]['postTypeName'] = $val['postTypeName'];
				$exportData[$key]['positionName'] = $val['positionName'];
				$exportData[$key]['developPositionName'] = $val['developPositionName'];
				$exportData[$key]['network'] = $val['network'];
				$exportData[$key]['device'] = $val['device'];
				$exportData[$key]['positionLevel'] = $val['positionLevel'];
				$exportData[$key]['projectGroup'] = $val['projectGroup'];
				$exportData[$key]['isEmergency'] = $isEmergency;
				$exportData[$key]['tutor'] = $val['tutor'];
				$exportData[$key]['computerConfiguration'] = $val['computerConfiguration'];
				$exportData[$key]['formDate'] = $val['formDate'];
				$exportData[$key]['ExaDT'] = $val['ExaDT'];
				$exportData[$key]['assignedDate'] = $val['assignedDate'];
				$exportData[$key]['createTime'] = substr($val['createTime'] ,0 ,10);
				$exportData[$key]['entryDate'] = substr($val['entryDate'] ,0 ,10);
				$exportData[$key]['firstOfferTime'] = substr($val['createTime'] ,0 ,10);
				$exportData[$key]['lastOfferTime'] = substr($val['lastOfferTime'] ,0 ,10);
				$exportData[$key]['addType'] = $val['addType'];
				$exportData[$key]['needNum'] = $val['needNum'];
				$exportData[$key]['entryNum'] = $val['entryNum'];
				$exportData[$key]['beEntryNum'] = $val['beEntryNum'];
				$exportData[$key]['stopCancelNum'] = $val['stopCancelNum'];
				$exportData[$key]['ingtryNum'] = $val['needNum'] - $val['entryNum'] - $val['beEntryNum'] - $val['stopCancelNum'];
				$exportData[$key]['recruitManName'] = $val['recruitManName'];
				$exportData[$key]['assistManName'] = $val['assistManName'];
				$exportData[$key]['userName'] = $userName;
				$exportData[$key]['applyReason'] = $val['applyReason'];
				$exportData[$key]['workDuty'] = $val['workDuty'];
				$exportData[$key]['jobRequire'] = $val['jobRequire'];
				$exportData[$key]['keyPoint'] = $val['keyPoint'];
				$exportData[$key]['attentionMatter'] = $val['attentionMatter'];
				$exportData[$key]['leaderLove'] = $val['leaderLove'];
				$exportData[$key]['applyRemark'] = $val['applyRemark'];
			}
		}
		return $this->service->excelOut ( $exportData );
	}

	/*********************���뵼��*************************************/

	/***********add chenrf 20130508*************/
	/**
	 * ����ID�ύ��Ա����,�������ʼ�
	 */
	function c_ajaxSubmit(){
		if($this->service->changeState($_POST['id'])){
			$obj = $this->service->get_d($_POST['id']);
			$this->service->thisMail_d($obj);
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ������
	 */
	function c_ewf(){
		$id = $_GET['id'];
		$obj = $this->service->get_d($id);

		$url = $this->service->ewfSelect($obj);
		if($url == '') {
			msg('�ύ���ݴ����޷�ָ����Ӧ������!');
			exit;
		}
		succ_show($url);
	}

	/**
	 * ��ȡ��������������
	 */
	function c_delewf(){
		$id=$_GET['id'];
		$obj=$this->service->get_d($id);

		$url=$this->service->ewfDelWork($obj);
		if($url == '') {
			msg('�ύ���ݴ����޷�ָ����Ӧ������!');
			exit();
		} else {
			$mail = new model_common_mail();
			$title = '��Ա���볷������֪ͨ';
			$content = '����,��'.$_SESSION['USERNAME'].'���Ե��ݱ�š�'.$obj['formCode'].'�������˳�������������';
			$mail->mailGeneral($title ,$obj['formManId'] ,$content,null);
			echo $url;
		}
	}

	/**
	 * �޸������鿴ҳ��
	 */
	function c_toAuditEditView(){
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
		} else {
			$this->assign ( 'projectType', '' );
		}
		if ($obj ['projectTypeEdit'] =="YFXM") {
			$this->assign ( 'projectTypeEdit', '�з���Ŀ' );
		} else if ($obj ['projectTypeEdit'] == "GCXM") {
			$this->assign ( 'projectTypeEdit', '������Ŀ' );
		}

		$type = $this->getDatadicts ( array ('postType' => 'YPZW' ));
		$addTypeCode=$this->getDatadicts(array ('addTypeCode' => 'HRZYLX' ));
		$employmentTypeCode=$this->getDatadicts( array ('employmentTypeCode' => 'HRPYLX' ));//�ù�����
		foreach ($type['YPZW'] as $val){
			if($val['dataCode']==$obj['postTypeEdit']){
				$this->assign ( 'postTypeEdit', $val['dataName']);
				break;
			}
		}
		foreach ($addTypeCode['HRZYLX'] as $val){
			if($val['dataCode']==$obj['addTypeCodeEdit']){
				$this->assign ( 'addTypeCodeEdit', $val['dataName']);
				break;
			}
		}

		foreach ($employmentTypeCode['HRPYLX'] as $val){
			if($val['dataCode']==$obj['employmentTypeCodeEdit']){
				$this->assign ( 'employmentTypeCodeEdit', $val['dataName']);
				break;
			}
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
		$this->assign ( 'actType', $actType );
		$this->view ( 'auditEditView' );
	}

	/**
	 * �б�߼���ѯ
	 */
	function c_toSearch(){
		$this->permCheck(); //��ȫУ��
		$this->showDatadicts ( array ('postType' => 'YPZW' ));//ְλ����
		$this->showDatadicts ( array ('addTypeCode' => 'HRZYLX' ));//��Ա����
		$this->view('search');
	}

	/*
	 * ��ת���޸Ĺؼ���ҳ��
	 */
	function c_toEditKeyPoints(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$act = isset ( $_GET ['act'] ) ? $_GET ['act'] : null;
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isEmergency'] == "1") {
			$this->assign ( 'isEmergency', '��' );
		} else if ($obj ['isEmergency'] == "0") {
			$this->assign ( 'isEmergency', '��' );
		}
		if ($obj ['projectType'] == "YFXM") {
			$this->assign ( 'projectType', '�з���Ŀ' );
		} else if ($obj ['projectType'] == "GCXM") {
			$this->assign ( 'projectType', '������Ŀ' );
		} else {
			$this->assign ( 'projectType', '' );
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'],false));
		$this->assign('actType', $actType );
		$this->assign('act', $act );
		$this->view('editPoint' ,true);
	}

	/**
	 * �༭�ؼ�Ҫ��
	 */
	function c_editKeyPoints(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST['apply'];
		$act = isset ( $_POST ['act'] ) ? $_POST ['act'] : null;
		$id = $this->service->editKeyPoints($obj);
		if($id){
			msg('�޸ĳɹ�');
		} else {
			msg('�޸�ʧ��');
		}
	}

	/*
	 * ��ת���޸�¼������ҳ��
	 */
	function c_toEditEmploy(){
		$this->permCheck (); //��ȫУ��
		$this->service->searchArr['id'] = $_GET['id'];
		$rows = $this->service->listBySqlId('select_list');

		$obj = $rows[0];
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$ingtryNum = $obj['needNum'] - $obj['beEntryNum'] - $obj['entryNum'];
		$this->assign('ingtryNum' ,$ingtryNum); //����Ƹ����

		if ($obj['employId'] != '') {
			$employNum = count(explode(',' ,$obj['employId']));
		} else {
			$employNum = 0;
		}
		$this->assign('employNum' ,$employNum); //�ֶ��༭��¼����������

		$this->view('editEmploy' ,true);
	}

	/**
	 * �޸�¼������
	 */
	function c_editEmploy(){
		$this->checkSubmit(); //����Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		if ($_GET['isFinish'] == 'true') {
			$obj['state'] = 4;
		}
		if($this->service->updateById($obj)){
			msg('�޸ĳɹ�');
		} else {
			msg('�޸�ʧ��');
		}
	}

	/**
	 * ��ת���鿴ȡ����Ƹԭ��ҳ��
	 */
	function c_toViewCancel() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['stateName'] = $this->service->statusDao->statusKtoC( $obj['state'] );
		$this->assignFunc($obj);
		$this->view('view-cancel');
	}

	/**
	 * ��ת���鿴���ø���ͣ��Ƹԭ��ҳ��
	 */
	function c_toViewStartstop() {
		$obj = $this->service->get_d($_GET['id']);
		$obj['stateName'] = $this->service->statusDao->statusKtoC( $obj['state'] );
		$this->assignFunc($obj);
		$this->view('view-startstop');
	}
 }

?>