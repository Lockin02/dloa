<?php
/**
 * @author Administrator
 * @Date 2012-08-07 09:38:05
 * @version 1.0
 * @description:��ְ������Ʋ�
 */
class controller_hr_leave_leave extends controller_base_action {

	function __construct() {
		$this->objName = "leave";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ְ����ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ְ�����б�
	 */
	function c_proList(){
		$this->assign("userId" ,$_SESSION['USER_ID']);
		$this->view ( 'prolist' );
	}

	/**
	 * ��ת���༭��ְ����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('parentDeptId',$arr['deptId']);
		$this->assign('thisDate',date("Y-m-d"));
		$this->view ('edit' ,true);
	}

	/**
	 * ��ת����ְ����ȷ��ҳ��
	 */
	function c_toEditType() {
		$obj = $this->service->get_d ( $_GET ['id'] );

		//��ȡ��ְԭ���������
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //û�а�������ԭ��
			$obj['quitReson'] = str_replace('^nbsp' ,"�� " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //����������ԭ��
				$quitReson .= $arr[$i]."��";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."��".$arr[$i + 1];
		}

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->showDatadicts ( array ('quitTypeCode' => 'YGZTLZ' ), $obj ['quitTypeCode'] );//HRLZLX
		$this->view ('edit-type' ,true);
	}

	/**
	 * ��ת����ְ֤����ӡҳ��
	 */
	function c_toLeaveProof() {
		$obj = $this->service->getLeaveUserInfo ( $_GET ['id'] );
		$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);

		$entryYear='-';
		$entryMon='-';
		$entryDay='-';
		$leaveYear='-';
		$leaveMon='-';
		$leaveDay='-';
		$contractYear='-';
		$contractMon='-';
		$contractDay='-';
		$contractEndYear='-';
		$contractEndMon='-';
		$contractEndDay='-';
		$leaveYears='--��';
		$userName=$obj['userName'];
		$identityCard= $obj['identityCard'];
		if($arr['beginDate']!='0000-00-00'&&$arr['beginDate']!=''){
			$contractYear= date("Y",strtotime($arr['beginDate']));
			$contractMon=date("m",strtotime($arr['beginDate']));
			$contractDay= date("d",strtotime($arr['beginDate']));
		}
		if($arr['closeDate']!='0000-00-00'&&$arr['closeDate']!=''){
			$contractEndYear= date("Y",strtotime($arr['closeDate']));
			$contractEndMon=date("m",strtotime($arr['closeDate']));
			$contractEndDay= date("d",strtotime($arr['closeDate']));
		}
		//��ְʱ��
		if($obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
			$entryYear= date("Y",strtotime($obj['entryDate']));
			$entryMon=date("m",strtotime($obj['entryDate']));
			$entryDay= date("d",strtotime($obj['entryDate']));
		}
		//��ְʱ��
		if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''){
			$leaveYear=date("Y",strtotime($obj['comfirmQuitDate']));
			$leaveMon=date("m",strtotime($obj['comfirmQuitDate']));
			$leaveDay=date("d",strtotime($obj['comfirmQuitDate']));
		}
		$leaveTypeS=array('YGZTJC','YGZTWJJC','YGZTCZ','YGZTCT');
		if (in_array($obj['quitTypeCode'] ,$leaveTypeS)) {
			$leaveType = '���';
			$leaveRemark = '��˫������Ͷ���ͬ��ϵ֮����';
		} else {
			$leaveType = '��ֹ';
			$leaveRemark = '��˫����ֹ�Ͷ���ͬ��ϵ֮����';
		}
		if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''&&$obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
			$leaveYears = $obj['leaveYears'] * 12;
			$allMonth = (int)($leaveYears + $obj['leaveMonth']);
			if($obj['leaveDay'] > 15) {		//��������15�����һ����
				$allMonth = $allMonth + 1;
			}else if($obj['leaveDay'] < -15){		//С��-15�ļ���һ����
				$allMonth = $allMonth - 1;
			}
			$remainYear = floor($allMonth / 12);	//�㹤���˶�����
			if($remainYear == 0) {
				if($allMonth == 0) {
					$leaveYears = $obj['leaveDay'].'��';		//��������һ���µ�������
				}else{
					$leaveYears = $allMonth.'����';
				}
			}else if($remainYear>0){
				$month = $allMonth%12;
				if($month!=0){
					$leaveYears=$remainYear.'��'.$month.'����';
				}else{
					$leaveYears=$remainYear.'��';
				}
			}
		}
		$leaveContent="�����ҹ�˾Ա��".$userName."�����֤�ţ�".$identityCard."���������Ͷ���ͬ��ֹ����Ϊ".$contractYear."��".$contractMon."��".$contractDay."����".$contractEndYear."��".$contractEndMon."��".$contractEndDay."��ֹ������".$entryYear."��".$entryMon."��".$entryDay."����".$leaveYear."��".$leaveMon."��".$leaveDay."�����ҹ�˾��������ְʱְ��Ϊ$obj[jobName]����˾��������Ϊ".$leaveYears."��������".$leaveYear."��".$leaveMon."��".$leaveDay."�������ԭ�����ҹ�˾".$leaveType."�Ͷ���ͬ��ϵ�����Ѱ�����ϸ�������Ӻ���ְ������";
		if($obj['NamePT']=='dl'){
			 $companyName="�麣���Ͷ����Ƽ��ɷ����޹�˾";
			 $photoName='sjdl';
		}elseif($obj['NamePT']=='br'){
			$companyName="�����б�����ӿƼ����޹�˾";
			 $photoName='gzbr';
		}elseif($obj['NamePT']=='sy'){
			$companyName="������Դ��ͨ�Ƽ����޹�˾";
			 $photoName='bjsy';
		}elseif($obj['NamePT']=='bx'){
			$companyName="�����б�Ѷ���ӿƼ����޹�˾";
			 $photoName='sjdl';
		}else{
			$companyName=$obj['NameCN']?$obj['NameCN']:'��';
			 $photoName='sjdl';
		}

		$this->assign ( 'leaveContent', $leaveContent );
		$this->assign ( 'companyName', $companyName );
		$this->assign ( 'photoName', $photoName );
		$this->assign ( 'leaveRemark', $leaveRemark );
		//��ӡʱ��
		$this->assign ( 'todayYear', date("Y") );
		$this->assign ( 'todayMon', date("m") );
		$this->assign ( 'todayDay', date("d") );
        $this->view ( 'proof');
   }

	/**
	 * �鿴ҳ��Tabҳ
	 */
	function c_toViewTab() {
		$this->assign("id",$_GET['id']);
		$row=$this->service->get_d($_GET['id']);
		$this->assign("userAccount",$row['userAccount']);
		$this->view ( 'view-tab');
	}

	/**
	 * ��ת���鿴��ְ����ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��ȡ��ְԭ���������
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //û�а�������ԭ��
			$obj['quitReson'] = str_replace('^nbsp' ,"�� " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //����������ԭ��
				$quitReson .= $arr[$i]."��";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."��".$arr[$i + 1];
		}

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		ȥ��ֻ���ڵ�һ������ʱ�޸ĵ�����
// 		$otherdatasDao = new model_common_otherdatas();
// 		$flag=$otherdatasDao->isFirstStep($_GET['id'] ,$this->service->tbl_name);
// 		if($actType && $flag){
		if($actType){
			$comfirmQuitDate = $obj['comfirmQuitDate'];
			$salaryEndDate = $obj['salaryEndDate'];
			if($comfirmQuitDate == '0000-00-00' || empty($comfirmQuitDate) || $salaryEndDate == '0000-00-00' || empty($salaryEndDate)){
				$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
				switch($obj['quitTypeCode']){
					case "YGZTHTYGBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//��ͬ����Ա������
					case "YGZTHTGSBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//��ͬ���ڹ�˾����
					case "YGZTCT":$comfirmQuitDate=$arr['becomeDate'];$salaryEndDate=$arr['becomeDate'];break;//�����ڴ���
					case "YGZTJC":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//Э�̽��
					case "YGZTCZ":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//��ְ
					case "YGZTTXGSBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//���ݹ�˾����
					case "YGZTYGBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//����Ա������
					case "YGZTWJJC":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//Υ�ͽ��
					default :$comfirmQuitDate = $arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;
				}
			}
			$this->assign ( 'comfirmQuitDate', $comfirmQuitDate );
			$this->assign ( 'salaryEndDate', $salaryEndDate );
			$this->view ( 'editview' );
		} else {
			$this->view ( 'view' );
		}
   }

	/**
	 * ��ת����ְ���ȱ�עҳ��
	 */
	function c_toEditRemark() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit-remark' ,true);
	}

	/**
	 * ��ת��HR�޸���ְ��Ϣҳ��
	 */
	function c_toEditLeaveInfo() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('hr-edit' ,true);
	}

	/**
	 * ��ת��HR����excel�޸���ְ��Ϣҳ��
	 */
	function c_toEditLeaveInfoExcel() {
		$this->permCheck (); //��ȫУ��
		$this->view('hr-editexcel');
	}

	/**
	 * ��ת�������ʼ�ҳ��
	 */
	function c_toSendEmail(){
		$this->permCheck (); //��ȫУ��

		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET ['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('mail' ,true);
	}

	/**
	 * ��ת����ְָ�������ʼ�ҳ��
	 */
	function c_toSendEmailguide(){
		$this->permCheck (); //��ȫУ��

		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET ['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('mailguide' ,true);
	}

	/**
	 * ������������ӡȷ��ҳ��
	 */
	function c_toConfirmation(){
		$this->permCheck (); //��ȫУ��
		$idStr = $_GET ['idStr'];
		$checked = $this->service->getChecked_d($idStr);
		$this->assign ( 'checked', $checked );
		if($_GET ['type'] == 'prove') {
			$this->view('confirmation');
		} else {
			$this->view('transfer');
		}
	}

	/**
	 * ��ָ���ռ��˷����ʼ�
	 */
	function c_sendEmail(){
		$this->checkSubmit();
		if($this->service->sendEmail($_POST['mail'])){
			msg('���ͳɹ�!');
		}
	}

	/**
	 * ��ָ���ռ��˷����ʼ�-��ְָ��
	 */
	function c_sendEmailguide(){
		$this->checkSubmit();
		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $_POST['mail']['id'], 'oa_hr_leave_email' );
		$obj = $_POST['mail'];
		if ($file) {
			foreach ($file as $key => $val) {
				$obj['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
			}
		}
		if($this->service->sendEmailguide($obj)){
			msg('���ͳɹ�!');
		}
	}

	/**
	 * ȷ���޸�
	 */
	function c_confirmEdit($isEditInfo = true){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * �޸�Ա����ְ��Ϣ
	 */
	function c_leaveInfoEdit($isEditInfo = true){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		$oldObj = $this->service->get_d($object['id']);
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			if($oldObj['comfirmQuitDate']!=$object['comfirmQuitDate']){
				// �޸Ľ����嵥��ְ����
				$handoverDao=new model_hr_leave_handover();
				$handoverDao->updateField('leaveId='.$object['id'],'quitDate',$object['comfirmQuitDate']);
				$obj=$this->service->get_d($object['id']);
				//���·����ʼ�֪ͨ������
				$this->service->sendMailToFinan($obj);
				//����֪ͨ��������
				$handoverDao->mailByLeaveId($object['id']);
			}
			msg ( '����ɹ���' );
		}
	}

	/*
	 * �����޸�Ա����ְ��Ϣ
	 */
	function c_editLeaveInfoExcel() {
		set_time_limit(0);
		$resultArr = $this->service->editLeaveInfoExcel_d ();

		$title = '�޸���ְ��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ajax��ȡ������Ϣ
	 */
	function c_getPersonnelInfo(){
		$userAccount = $_POST['userAccount'];
		$rows = $this->service->getPersonnelInfo_d($userAccount);
		$rows = util_jsonUtil :: encode($rows);
		echo $rows;
	}

	/**
	 * ajax��֤�Ƿ��Ѵ�����ְ�嵥
	 */
	function c_getLeaveInfo(){
		$userAccount = $_POST['userAccount'];
		$falg = $this->service->getLeaveInfo_d($userAccount);
		echo $falg;
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//ƴװ��ְԭ��
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val){
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$rows['state']=2;
		$rows['leaveApplyDate'] = date('Y-m-d',time());
		$id = $this->service->add_d ($rows , $isAddInfo );
		if($id){
			if($actType==''){
				msg ( '����ɹ���' );
			}else if($rows['quitTypeCode']=='YGZTCZ'){
				$row=$this->service->get_d($id);
				$arr = $this->service->getPersonnelInfo_d($row['userAccount']);
				switch($row['wageLevelCode']){
					case "GZJBFGL":$auditType='5';break;//�ǹ����
					case "GZJBJL":$auditType='15';break;//����
					case "GZJBZG":$auditType='25';break;//����
					case "GZJBZJ":$auditType='35';break;//�ܼ�
					case "GZJBFZ":$auditType='45';break;//����
					case "GZJBZJL":$auditType='75';break;//�ܾ���
				}
				succ_show('controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=' . $row['id'].'&billDept='.$row['deptId'].'&flowMoney='.$auditType.'&proSid='.$row['projectManagerId'].'&eUserId='.$row['userAccount']);
			} else {
				msg ( '����ɹ���' );
			}
		} else {
			msg ( '����ʧ�ܣ�' );
		}
	}

   	/**
	 * �����������
	 */
	function c_staffAdd() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//ƴװ��ְԭ��
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val){
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'staff') {
			$rows['state'] = 1;
			$rows['leaveApplyDate'] = date('Y-m-d' ,time());
		}
		$id = $this->service->add_d ($rows);
		if($id) {
			if($actType != '') {
				//�����ʼ�֪ͨHR
				$this->service->mailForSubmit($id);
				msgGo ( '�ύ�ɹ���' ,'?model=hr_leave_leave&action=toAttention');
			} else {
				msg ( '����ɹ���' );
			}
		} else {
			if($actType != '') {
				msg ( '�ύʧ�ܣ�' );
			} else {
				msg ( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * �����������
	 */
	function c_staffEdit() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//ƴװ��ְԭ��
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val) {
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'staff') {
			$rows['state'] = 1;
			$rows['leaveApplyDate'] = date('Y-m-d' ,time());
		}
		$id = $this->service->staffEdit_d ($rows);
		if($id) {
			if($actType != '') {
				//�����ʼ�֪ͨHR
				$this->service->mailForSubmit($rows['id']);
				msg ( '�ύ�ɹ���' );
			} else {
				msg ( '����ɹ���' );
			}
		} else {
			if($actType != '') {
				msg ( '�ύʧ�ܣ�' );
			} else {
				msg ( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * ȷ����ְ����
	 */
	function c_editType() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : '';
		$id = $this->service->editType_d ($rows);
		if($id){
			if($actType==''){
				msg ( 'ȷ�ϳɹ���' );
			}else if($rows['quitTypeCode']=='YGZTCZ'){
				$row=$this->service->get_d($rows['id']);
				$arr = $this->service->getPersonnelInfo_d($row['userAccount']);
				switch($row['wageLevelCode']){
					case "GZJBFGL":$auditType='5';break;//�ǹ����
					case "GZJBJL":$auditType='15';break;//����
					case "GZJBZG":$auditType='25';break;//����
					case "GZJBZJ":$auditType='35';break;//�ܼ�
					case "GZJBFZ":$auditType='45';break;//����
					case "GZJBZJL":$auditType='75';break;//�ܾ���
				}
				succ_show('controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=' . $rows['id'].'&billDept='.$rows['deptId'].'&flowMoney='.$auditType.'&proSid='.$row['projectManagerId'].'&eUserId='.$row['userAccount']);
			} else {
				msg ( 'ȷ�ϳɹ���' );
			}
		} else {
			msg ( 'ȷ��ʧ�ܣ�' );
		}
	}

	/**
	 * ��ְ���루Ա����
	 */
	function c_staffToAdd(){
		msg ( '���ã���OA�����ߣ��뵽��OA�ύ���롣лл��' );
		$userId = $_SESSION['USER_ID'];
		$arr = $this->service->getPersonnelInfo_d($userId);
		$this->assign('parentDeptId',$arr['deptId']);
		$arr['userName'] = $arr['staffName'];
		$arr['userAccount'] = $_SESSION['USER_ID'];
		$arr['deptName'] = $arr['belongDeptName'];
		$arr['deptId'] = $arr['belongDeptId'];
		$this->assign('jobName','');
		$this->assign('beginDate','');
		$this->assign('closeDate','');
		$this->assign('entryDate','');
		$this->assign('wageLevelName','');
		foreach ( $arr as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('thisDate',date("Y-m-d"));
		$this->view("stafftoadd" ,true);
	}

	/**
	 * ��ְ���������ʼ�
	 */
	function c_leaveMail(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['Result']=="ok"&&is_array($rows)){
				$rows['handitem']=array();
				$handContentArr=explode('|',$rows['handContent']);
				$recipientNameArr=explode(',',$rows['recipientName']);
				$recipientIdArr=explode(',',$rows['recipientId']);
				foreach($handContentArr as $key=>$val){
					$rows['handitem'][$key]['handContent']=$val;
					$rows['handitem'][$key]['recipientName']=$recipientNameArr[$key];
					$rows['handitem'][$key]['recipientId']=$recipientIdArr[$key];
					$rows['handitem'][$key]['mainId']=$rows['id'];
				}
				$this->service->edit_d ( $rows);
			}
			if($folowInfo['examines']=="ok"){  //����ͨ��
				$obj = $this->service->get_d ( $folowInfo['objId'] );
				$this->service->sendLeaveMail($obj,$rows);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ��дpageJason
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		$this->service->groupBy = 'c.id';
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$handoverDao=new model_hr_leave_handover();
		$handoverlistDao=new model_hr_leave_handoverlist();
		if(is_array($rows)){
			foreach($rows as $k => $v){
				$rows[$k]['nowDate'] = date('Y-m-d',time());
				$handoverRow = $handoverDao->getnfo_d($v['id']);
				if(is_array($handoverRow)){
					$rows[$k]['isHandover']='1';
					if($handoverlistDao->isAffirmAll_d($v['id'])) {
						$rows[$k]['isAffirmAll'] = '1';
					}else {
						$rows[$k]['isAffirmAll'] = '0';
					}
				}else{
					$rows[$k]['isHandover']='0';
				}
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
	 * ��ְ����
	 */
	function c_pageJsonLeave() {
		$service = $this->service;
		$rows = array();
		$service->getParam ( $_REQUEST );
		$this->service->groupBy = 'c.id';
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$comLimit = $service->this_limit['��˾Ȩ��'];
		$comLimitArr = array();
		if(!strstr($comLimit,';;')&&!empty($comLimit)){
			$comLimitArr = explode(',',$comLimit);
			foreach($comLimitArr as $key=>$val){
				$nameCN .=$this->service->get_table_fields('branch_info',"NamePt='".$val."'",'NameCN').",";
			}
			$nameCN = substr_replace($nameCN,'','-1');		//ȥ�����һ������
			$service->searchArr['companyNameI']=$nameCN;
		} else if(empty($comLimit)){
			$service->searchArr['companyNameI']='0';
		}
		$rows = $service->page_d ();
		$handoverDao=new model_hr_leave_handover();
		$handoverlistDao=new model_hr_leave_handoverlist();
		if(is_array($rows)){
			foreach($rows as $k => $v){
				$rows[$k]['nowDate'] = date('Y-m-d',time());
				$handoverRow = $handoverDao->getnfo_d($v['id']);
				if(is_array($handoverRow)){
					$rows[$k]['isHandover']='1';
					if($handoverlistDao->isAffirmAll_d($v['id'])) {
						$rows[$k]['isAffirmAll'] = '1';
					}else {
						$rows[$k]['isAffirmAll'] = '0';
					}
				}else{
					$rows[$k]['isHandover']='0';
				}
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
	 * ����ID�ύ��Ա����,�������ʼ�
	 */
	function c_ajaxSubmit(){
		$id = $_POST['id'];
		$state = $_POST['state'];
		if($this->service->changeState($id,$state)){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��������
	 */
	function c_backSubmit(){
		$id = $_POST['id'];
		$state = $_POST['state'];
		if($this->service->getState($id,$state)){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ����
	 */
	function c_toExport(){
		$this->view('exportview');
	}

	/**
	 * ������ְԱ������
	 */
	function c_updatePersonInfo(){
		$object = $_POST['id'];
		if($object){
			$result = $this->service->updatePersonInfo($object);
		}
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ְ���룬��������
	 */
	function c_updateExaStatus(){
		$object['id'] = $_POST['id'];
		$object['ExaStatus'] = 'δ�ύ';
		if($object){
			$result = $this->service->updateExaStatus_d($object);
		}
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ְδȷ����Ϣ����
	 */
	function c_toExpportunconfirm() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];
		$this->service->sort='d.recipientName';
		$rows = $this->service->listBySqlId('select_unconfirm');
		if(is_array($rows)) {
			for ($i = 0 ;$i < count($rows) ;$i++) {
				unset($rows[$i]['id']);
			}
			$colArr = array();
			$modelName = '��ְδȷ����Ϣ';
			return model_hr_basicinfo_export2ExcelUtil::export2ExcelUtil($colArr, $rows, $modelName);
		}
	}

	function c_ExamineExport(){
		$object = $_POST[$this->objName];//��ȡ��ѯ����
		$this->service->searchArr['state'] = '1,2,3,4';
		$this->service->groupBy='c.id';
		if(!empty($object['leaveCode']))//���ݱ��  �����ְ���ڲ�Ϊ�վ����ò�ѯ����
			$this->service->searchArr['djbh'] = $object['leaveCode'];

		if(!empty($object['userNo']))//Ա�����
			$this->service->searchArr['ygbh'] = $object['userNo'];

		if(!empty($object['userName']))//��ְ��Ա
			$this->service->searchArr['userNameSame'] = $object['userName'];

		if(!empty($object['state'])){//����״̬
			switch($object['state']){
				case '2.1':$this->service->searchArr['djzt'] = '2';
					$this->service->searchArr['qdti'] = date('Y-m-d',time());
					break;
				case '2.2':$this->service->searchArr['djzt'] = '2';
					$this->service->searchArr['spzt'] = '���';
					$this->service->searchArr['xlzrq'] = date('Y-m-d',time());
					break;
				default :$this->service->searchArr['djzt'] = $object['state'];
			}
		}
		if(!empty($object['userSelfCstatus']))//Ա��ȷ��״̬
			$this->service->searchArr['ygqrzt'] = $object['userSelfCstatus'];

		if(!empty($object['handoverCstatus'])){//�����嵥״̬
			switch($object['handoverCstatus']){
				case '1':$this->service->searchArr['handoverIdN']="1";
						 break;
				case 'WQR':$this->service->searchArr['handoverId']="1";
						   $this->service->searchArr['handoverCstatus'] = $object['handoverCstatus'];
						   break;
				case 'YQR':$this->service->searchArr['handoverId']="1";
						   $this->service->searchArr['handoverCstatus'] = $object['handoverCstatus'];
						   break;
			}
		}
		if(!empty($object['companyName']))//��˾
			$this->service->searchArr['gs'] = $object['companyName'];

		if(!empty($object['deptId']))//ֱ������ID
			$this->service->searchArr['deptIdSame'] = $object['deptId'];

		if(!empty($object['deptIdS']))//��������ID
			$this->service->searchArr['deptIdSameS'] = $object['deptIdS'];

		if(!empty($object['deptIdT']))//��������ID
			$this->service->searchArr['deptIdSameT'] = $object['deptIdT'];

        if(!empty($object['deptIdF']))//�ļ�����ID
            $this->service->searchArr['deptIdSameF'] = $object['deptIdF'];

		if(!empty($object['jobName']))//ְλ
			$this->service->searchArr['zw'] = $object['jobName'];

		if(!empty($object['entryDate']))//��ְ����
			$this->service->searchArr['rzrq'] = $object['entryDate'];

		if(!empty($object['entryDate2']))//��ְ����2
			$this->service->searchArr['rzrq2'] = $object['entryDate2'];

		if(!empty($object['quitTypeCode']))//��ְ����
			$this->service->searchArr['quitTypeCodeSame'] = $object['quitTypeCode'];

		if(!empty($object['requireDate']))//������ְ����
			$this->service->searchArr['qwlzrq'] = $object['requireDate'];

		if(!empty($object['requireDate2']))//������ְ����2
			$this->service->searchArr['qwlzrq2'] = $object['requireDate2'];

		if(!empty($object['leaveDate']))//ȷ����ְ����
			$this->service->searchArr['lzrq'] = $object['leaveDate'];

		if(!empty($object['leaveDate2']))//ȷ����ְ����2
			$this->service->searchArr['lzrq2'] = $object['leaveDate2'];

		if(!empty($object['salaryEndDate']))//���ʽ����ֹ����
			$this->service->searchArr['gzjsjzrq'] = $object['salaryEndDate'];

		if(!empty($object['salaryEndDate2']))//���ʽ����ֹ����2
			$this->service->searchArr['gzjsjzrq2'] = $object['salaryEndDate2'];

		if(!empty($object['salaryPayDate']))//����֧������
			$this->service->searchArr['gzzfrq'] = $object['salaryPayDate'];

		if(!empty($object['salaryPayDate2']))//����֧������2
			$this->service->searchArr['gzzfrq2'] = $object['salaryPayDate2'];

		if(!empty($object['pensionReduction']))//�籣��Ա
			$this->service->searchArr['sbjy'] = $object['pensionReduction'];

		if(!empty($object['fundReduction']))//�������Ա
			$this->service->searchArr['gjjjy'] = $object['fundReduction'];

		if(!empty($object['employmentEnd']))//�ù���ֹ
			$this->service->searchArr['ygzz'] = $object['employmentEnd'];

		if($object['softSate']!="")//�칫���״̬
			$this->service->searchArr['bgrjzt'] = $object['softSate'];

		if(!empty($object['ExaStatus']))//����״̬
			$this->service->searchArr['spzt'] = $object['ExaStatus'];

		if(!empty($object['remark']))//���ȱ�ע
			$this->service->searchArr['jdbz'] = $object['remark'];

		if(!empty($object['quitReson']))//��ְԭ��
			$this->service->searchArr['lzyy'] = $object['quitReson'];

		set_time_limit(0);// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		$examineRows = $this->service->listBySqlId("select_default");//Ĭ�ϲ�ѯ���õ���Ҫ��ֵ

		$exportData = array();//���õ�����������
		//��������
		if(is_array($examineRows)) {
			$handoverDao = new model_hr_leave_handover();
			foreach($examineRows as $key => $val){
				//��ȡ��ְԭ��ȥ�������ַ����������
				$str = substr($val['quitReson'] ,-5);
				if ($str == "^nbsp") { //û�а�������ԭ��
					$examineRows[$key]['quitReson'] = str_replace('^nbsp' ,"�� " ,$val['quitReson']);
				} else {
					$quitReson = '';
					$arr = explode("^nbsp" ,$val['quitReson']);
					for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //����������ԭ��
						$quitReson .= $arr[$i]."��";
					}
					$examineRows[$key]['quitReson'] = $quitReson.$arr[$i]."��".$arr[$i + 1];
				}

				//����״̬
				$handoverRow = $handoverDao->getnfo_d($val['id']);
				if(!is_array($handoverRow)){
					$examineRows[$key]['handoverCstatus'] = '0';
				}
				if($val['state'] == '2') {
					if($val['ExaStatus'] == '���' && $val['comfirmQuitDate'] < date('Y-m-d',time())) {
						$examineRows[$key]['state'] = '����������';
					} else{
						$examineRows[$key]['state'] = '��ȷ������';
					}
				}

				//��ʱ����ʾΪ0000-00-00Ĭ��Ϊ��ֵ
				if($val['entryDate'] == '0000-00-00') {
					$examineRows[$key]['entryDate'] = '';
				}
				if($val['leaveApplyDate'] == '0000-00-00') {
					$examineRows[$key]['leaveApplyDate'] = '';
				}
				if($val['requireDate'] == '0000-00-00') {
					$examineRows[$key]['requireDate'] = '';
				}
				if($val['comfirmQuitDate'] == '0000-00-00') {
					$examineRows[$key]['comfirmQuitDate'] = '';
				}
				if($val['salaryEndDate'] == '0000-00-00') {
					$examineRows[$key]['salaryEndDate'] = '';
				}
				if($val['salaryPayDate'] == '0000-00-00') {
					$examineRows[$key]['salaryPayDate'] = '';
				}

				//������
				if ($val['isBack'] == 1) {
					$examineRows[$key]['isBack'] = '��';
				} else {
					$examineRows[$key]['isBack'] = '��';
				}
			}
		}

		foreach ( $examineRows as $key => $val ) {//ѭ����ѯ�������
			$exportData[$key]['id'] = $key + 1;
			$exportData[$key]['leaveCode'] = $val['leaveCode'];
			$exportData[$key]['userNo'] = $val['userNo'];
			$exportData[$key]['userName'] = $val['userName'];
			$exportData[$key]['state'] = $val['state'];
			$exportData[$key]['userSelfCstatus'] = $val['userSelfCstatus'];
			$exportData[$key]['handoverCstatus'] = $val['handoverCstatus'];
			$exportData[$key]['companyName'] = $val['companyName'];
			$exportData[$key]['personnelTypeName'] = $val['personnelTypeName'];
			$exportData[$key]['deptName'] = $val['deptName'];
			$exportData[$key]['deptNameS'] = $val['deptNameS'];
			$exportData[$key]['deptNameT'] = $val['deptNameT'];
            $exportData[$key]['deptNameF'] = $val['deptNameF'];
			$exportData[$key]['jobName'] = $val['jobName'];
			$exportData[$key]['workProvince'] = $val['workProvince'];
			$exportData[$key]['entryDate'] = $val['entryDate'];
			$exportData[$key]['quitTypeName'] = $val['quitTypeName'];
			$exportData[$key]['leaveApplyDate'] = substr($val['leaveApplyDate'] ,0 ,10);
			$exportData[$key]['requireDate'] = $val['requireDate'];
			$exportData[$key]['comfirmQuitDate'] = $val['comfirmQuitDate'];
			$exportData[$key]['salaryEndDate'] = $val['salaryEndDate'];
			$exportData[$key]['salaryPayDate'] = $val['salaryPayDate'];
			$exportData[$key]['pensionReduction'] = $val['pensionReduction'];
			$exportData[$key]['fundReduction'] = $val['fundReduction'];
			$exportData[$key]['employmentEnd'] = $val['employmentEnd'];
			$exportData[$key]['softSate'] = $val['softSate'];
			$exportData[$key]['ExaStatus'] = $val['ExaStatus'];
			$exportData[$key]['createName'] = $val['createName'];
			$exportData[$key]['remark'] = $val['remark'];
			$exportData[$key]['mobile'] = $val['mobile'];
			$exportData[$key]['personEmail'] = $val['personEmail'];
			$exportData[$key]['postAddress'] = $val['postAddress'];
			$exportData[$key]['quitReson'] = $val['quitReson'];
			$exportData[$key]['isBack'] = $val['isBack'];
			$exportData[$key]['realReason'] = $val['realReason'];
		}
		return model_hr_leave_leaveExportUtil::export2ExcelUtil ( $exportData );//���������������ӵ�E��
	}

	function c_toSearch(){//��ת�߼���ѯ
		$this->view('search');
	}

	/**
	 * ������ӡ��ְ֤��
	 */
	function c_printAll(){
		//id��
		$ids = null;
		if(isset($_POST['leave'])){
			$leaveDatas = $_POST['leave'];
			$ids = explode(',',$leaveDatas['ids']);
			if($leaveDatas['ids']&&$leaveDatas['idchecked']){
				$idArr = array_merge($ids,$leaveDatas['idchecked']);
			} else if($leaveDatas['idchecked']) {
				$idArr = $leaveDatas['idchecked'];
			} else if($leaveDatas['ids']) {
				$idArr = $ids;
			}
		}
		$ids = implode(',',$idArr);
		$this->assign('allNumH',count($idArr));
		$this->display('printHead');
		foreach($idArr as $key=>$val){
			$obj = $this->service->getLeaveUserInfo ( $val );
			$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
			$entryYear='-';
			$entryMon='-';
			$entryDay='-';
			$leaveYear='-';
			$leaveMon='-';
			$leaveDay='-';
			$contractYear='-';
			$contractMon='-';
			$contractDay='-';
			$contractEndYear='-';
			$contractEndMon='-';
			$contractEndDay='-';
			$leaveYears='--��';
			$userName=$obj['userName'];
			$identityCard= $obj['identityCard'];
			if($arr['beginDate']!='0000-00-00'&&$arr['beginDate']!=''){
				$contractYear= date("Y",strtotime($arr['beginDate']));
				$contractMon=date("m",strtotime($arr['beginDate']));
				$contractDay= date("d",strtotime($arr['beginDate']));
			}
			if($arr['closeDate']!='0000-00-00'&&$arr['closeDate']!=''){
				$contractEndYear= date("Y",strtotime($arr['closeDate']));
				$contractEndMon=date("m",strtotime($arr['closeDate']));
				$contractEndDay= date("d",strtotime($arr['closeDate']));
			}
			//��ְʱ��
			if($obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
				$entryYear= date("Y",strtotime($obj['entryDate']));
				$entryMon=date("m",strtotime($obj['entryDate']));
				$entryDay= date("d",strtotime($obj['entryDate']));
			}
			//��ְʱ��
			if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''){
				$leaveYear=date("Y",strtotime($obj['comfirmQuitDate']));
				$leaveMon=date("m",strtotime($obj['comfirmQuitDate']));
				$leaveDay=date("d",strtotime($obj['comfirmQuitDate']));
			}
			$leaveTypeS=array('YGZTJC','YGZTWJJC','YGZTCZ','YGZTCT');
			if(in_array($obj['quitTypeCode'],$leaveTypeS)){
				$leaveType='���';
				$leaveRemark='��˫������Ͷ���ͬ��ϵ֮����';
			}else{
				$leaveType='��ֹ';
				$leaveRemark='��˫����ֹ�Ͷ���ͬ��ϵ֮����';
			}
			if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''&&$obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
				$leaveYears=$obj['leaveYears']*12;
				$allMonth=(int)($leaveYears+$obj['leaveMonth']);
				if($obj['leaveDay']>15){		//��������15�����һ����
					$allMonth = $allMonth + 1;
				}else if($obj['leaveDay']<-15){		//С��-15�ļ���һ����
					$allMonth = $allMonth - 1;
				}
				$remainYear = floor($allMonth/12);	//�㹤���˶�����
				if($remainYear==0){
					if($allMonth==0){
						$leaveYears = $obj['leaveDay'].'��';		//��������һ���µ�������
					}else{
						$leaveYears=$allMonth.'����';
					}
				}else if($remainYear>0){
					$month = $allMonth%12;
					if($month!=0){
						$leaveYears=$remainYear.'��'.$month.'����';
					}else{
						$leaveYears=$remainYear.'��';
					}
				}
			}
			$leaveContent="�����ҹ�˾Ա��".$userName."�����֤�ţ�".$identityCard."���������Ͷ���ͬ��ֹ����Ϊ".$contractYear."��".$contractMon."��".$contractDay."����".$contractEndYear."��".$contractEndMon."��".$contractEndDay."��ֹ������".$entryYear."��".$entryMon."��".$entryDay."����".$leaveYear."��".$leaveMon."��".$leaveDay."�����ҹ�˾��������ְʱְ��Ϊ$obj[jobName]����˾��������Ϊ".$leaveYears."��������".$leaveYear."��".$leaveMon."��".$leaveDay."�������ԭ�����ҹ�˾".$leaveType."�Ͷ���ͬ��ϵ�����Ѱ�����ϸ�������Ӻ���ְ������";
			if($obj['NamePT']=='dl'){
				$companyName="�麣���Ͷ����Ƽ��ɷ����޹�˾";
				$photoName='sjdl';
			}elseif($obj['NamePT']=='br'){
				$companyName="�����б�����ӿƼ����޹�˾";
				$photoName='gzbr';
			}elseif($obj['NamePT']=='sy'){
				$companyName="������Դ��ͨ�Ƽ����޹�˾";
				$photoName='bjsy';
			}elseif($obj['NamePT']=='bx'){
				$companyName="�����б�Ѷ���ӿƼ����޹�˾";
				$photoName='sjdl';
			}else{
				$companyName=$obj['NameCN']?$obj['NameCN']:'��';
				$photoName='sjdl';
			}

			$this->assign ( 'leaveContent', $leaveContent );
			$this->assign ( 'companyName', $companyName );
			$this->assign ( 'photoName', $photoName );
			$this->assign ( 'leaveRemark', $leaveRemark );
			//��ӡʱ��
			$this->assign ( 'todayYear', date("Y") );
			$this->assign ( 'todayMon', date("m") );
			$this->assign ( 'todayDay', date("d") );
			$this->assign ( 'id', $val );
			$this->view ( 'batchPrint' );
		}
		$this->assign('allNum',count($idArr));
		$this->assign('ids',$ids);
		$this->display("printTail");
	}

	/**
	 * ��ת����ְ������ҳ��
	 */
	function c_toBack() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view('back' ,true);
	}

	/**
	 * ��ְ������
	 */
	function c_back() {
		$this->checkSubmit();
		$rs = $this->service->back_d( $_POST[$this->objName] );
		if ($rs) {
			msg('��سɹ�');
		} else {
			msg('���ʧ��');
		}
	}

	/**
	 * ��ת����ְ����ر�ҳ��
	 */
	function c_toClose() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view('close' ,true);
	}

	/**
	 * ��ת���鿴��ְ����ر�ԭ��ҳ��
	 */
	function c_toCloseReason() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view( 'closeReason' );
	}

	/**
	 * ��ת����ְע������ĶԻ���
	 */
	function c_toAttention () {
		$this->view( 'attention' );
	}

	/**
	 * ��ת���༭��ʵ��ְԭ��ҳ��
	 */
	function c_toEditReal() {
		$obj = $this->service->get_d( $_GET['id'] );
		//��ȡ��ְԭ���������
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //û�а�������ԭ��
			$obj['quitReson'] = str_replace('^nbsp' ,"�� " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //����������ԭ��
				$quitReson .= $arr[$i]."��";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."��".$arr[$i + 1];
		}
		$this->assignFunc( $obj );
		$this->view('edit-real' ,true);
	}

	/**
	 * �༭��ʵ��ְԭ��
	 */
	function c_editReal() {
		$this->checkSubmit();
		if ($this->service->editReal_d($_POST[$this->objName])) {
			msg('����ɹ���');
		} else {
			msg('����ʧ�ܣ�');
		}
	}
 }
?>