<?php
/**
 * @author Show
 * @Date 2012��6��1�� ������ 14:51:13
 * @version 1.0
 * @description:��Ƹ����-���������� Model��
 */
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_hr_recruitment_interview  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_interview";
		$this->sql_map = "hr/recruitment/interviewSql.php";
		$this->db = new mysql();
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'notview',
				'statusCName' => 'δ���',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'notsend',
				'statusCName' => 'δ����¼��֪ͨ',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'hassend',
				'statusCName' => '�ѷ���¼��֪ͨ',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'finis',
				'statusCName' => '���',
				'key' => '3'
			)
		);
		parent::__construct ();
	}

	//��Ҫ�����ֵ䴦����ֶ�
	public $datadictFieldArr = array(
		'useHireType','hrHireType','wageLevelCode','postType'
		);

	//�����Ƿ�
	function rtYN_d($val){
		if ($val == 1){
			return '��';
		}else{
			return '��';
		}
	}

	//��дadd_d
	function add_d($object){
		$datadictDao = new model_system_datadict_datadict();
		$object['formCode']='PG'.date ( "YmdHis" );//���ݱ��
		$object['formDate']=date( "Y-m-d" );//���ݱ��
		$object['deptState']=0;
		$object['hrState']=0;
		$object['ExaStatus']='δ�ύ';
		$object = $this->processDatadict($object);
		if (isset($object['useHireType'])) {
			$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
		}

		if ($object['invitationId']>0){
			$invitationDao=new model_hr_recruitment_invitation();
			$invitationDao->update(array("id"=>$object['invitationId']),array("isAddInterview"=>1));
		}
		return parent::add_d($object,true);
	}

	/*
	 * ����add_duanlh2013-04-01
	 */
	function addInterview_d ($object) {
		try{
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']  = 'PG'.date ( "YmdHis" );//���ݱ��
			$object['formDate']  = date( "Y-m-d" );//��������
			$object['deptState'] = 0;
			$object['hrState']   = 0;
			$object['ExaStatus'] = 'δ�ύ';
			$object = $this->processDatadict($object);
			if (isset($object['useHireType'])) {
				$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
			}
			$branchDao = new model_deptuser_branch_branch();
			$branchCN = $branchDao->get_d($object['sysCompanyId']);
			$object['sysCompanyName'] = $branchCN['NameCN'];
			$object['deptState'] = 1;
			if ($object['useAreaId'] != '') {
				$query = $this->db->query("select Name from area where ID = ".$object['useAreaId']);
				$get = $this->db->fetch_array($query);
				$object['useAreaName'] = $get['Name'];
			}
			$object['hrState'] = 1;
			$object['state'] = $this->statusDao->statusEtoK("noview");
			$datadict = new model_system_datadict_datadict();
			$object['hrHireTypeName']    = $datadict->getDataNameByCode($object['hrHireType']);
			$object['hrSourceType1Name'] = $datadict->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName']   = $datadict->getDataNameByCode($object['useHireType']);
			$object['addType']           = $datadict->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName']     = $datadict->getDataNameByCode($object['wageLevelCode']);
			$object['controlPost']       = $datadict->getDataNameByCode($object['controlPostCode']);

			$id = parent :: add_d($object,true);
			if (is_array($object['items'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				foreach($object['items'] as $key => $value){
					$value["interviewId"]     = $id;
					$value["interviewerType"] = '1';
					$value["resumeId"]        = $object['resumeId'];
					$value["resumeCode"]      = $object['resumeCode'];
					$interviewcomDao->add_d($value ,true);
				}
			}
			if (is_array($object['humanResources'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				foreach($object['humanResources'] as $key => $value){
					$value["interviewId"]     = $id;
					$value["interviewerType"] = '2';
					$value["resumeId"]        = $object['resumeId'];
					$value["resumeCode"]      = $object['resumeCode'];
					$interviewcomDao->add_d($value ,true);
				}
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	function change_d(){
		try{
			$this->start_d();
			$obj = $this->get_d($_POST['id']);
			$applyresume = new model_hr_recruitment_applyResume();
			$apply = new model_hr_recruitment_apply();
			$recomresume = new model_hr_recruitment_recomResume();
			$recommend = new model_hr_recruitment_recommend();
			$resume = new model_hr_recruitment_resume();
			$invitationDao=new model_hr_recruitment_invitation();
			if ($_POST['type']==1){
				$sourceresume = $applyresume -> update(array("id"=>$obj['applyResumeId']),array("state"=>3));
			}else{
				$sourceresume = $recomresume -> update(array("id"=>$obj['applyResumeId']),array("state"=>3));
				$source = $recommend -> update(array("id"=>$obj['parentId']),array("state"=>3));
			}
			$getresume = $resume -> update(array("id"=>$obj['resumeId']),array("resumeType"=>3));
			//��������֪ͨ״̬
			if ($obj['invitationId']>0){
				$invitationDao -> update(array("id"=>$obj['invitationId']),array("state"=>3));
			}
			$obj['useInterviewResult'] = 0;
			$this-> updateById ( $obj );
			$this->commit_d();
			return 1;
		}catch(exception $e){
			$this->rollBack();
			return 0;
		}
	}

	//��ȡ��������
	function getInterComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"invitationId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['interviewEva']."<br>";
			}
		}
		return $str1;
	}

	//��ȡ��������(��������)
	function getInterviewComment($intertype,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$intertype,"interviewId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['interviewEva']."<br>";
			}
		}
		return $str1;
	}

	//��ȡ��������
	function getUseComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"invitationId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				if ($commentone['useWriteEva']=="")continue;
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['useWriteEva']."<br>";
			}
		}
		return $str1;
	}

	//��ȡ��������(��������)
	function getUseInterviewComment($type,$id){
		$comment = new model_hr_recruitment_interviewComment();
		$hrcomment = $comment->findAll(array("interviewerType"=>$type,"interviewId"=>$id));
		if (is_array($hrcomment)) {
			$str1 = "";
			foreach($hrcomment as $commentone){
				if ($commentone['useWriteEva']=="")continue;
				$str1 .= $commentone['interviewer']."(".$commentone['interviewDate']."):";
				$str1 .= $commentone['useWriteEva']."<br>";
			}
		}
		return $str1;
	}

	//��дedit_d
	function edit_d($object){

		//���������ֵ��ֶ�
		$datadictDao = new model_system_datadict_datadict ();
		$object ['hrSourceType1Name'] = $datadictDao->getDataNameByCode ( $object['hrSourceType1'] );
		if (!empty($object['useHireType'])) {
			$object ['useHireTypeName'] = $datadictDao->getDataNameByCode ( $object['useHireType'] );
		}

		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

	//��дedit_d
	function managerEdit_d($object) {
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['hrSourceType1Name'] = $datadictDao->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName']   = $datadictDao->getDataNameByCode($object['useHireType']);
			$object['addType']           = $datadictDao->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName']     = $datadictDao->getDataNameByCode($object['wageLevelCode']);
			$object['controlPost']       = $datadictDao->getDataNameByCode($object['controlPostCode']);

			$object = $this->processDatadict($object);
			
			$id = parent::edit_d($object,true);

			//����ӱ�����
			if (is_array($object['items'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				$mainArr = array("interviewId"=>$object['id'],"interviewerType"=>'1');
				$itemsArr = util_arrayUtil::setArrayFn($mainArr,$object['items']);

				//�ж����˲�������ӱ�ԭ�еľͱ༭���¼ӵ������ݿ������
				foreach($object['items'] as $key => $value){
					if ($value['id']) {
						$value["interviewId"]     = $object['id'];
						$value["interviewerType"] = '1';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->edit_d($value ,true);
						}
					} else {
						$value["interviewId"]     = $object['id'];
						$value["interviewerType"] = '1';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->add_d($value ,true);
						}
					}
				}
			}

			if (is_array($object['humanResources'])) {
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				$mainArr  = array("interviewId"=>$object['id'],"interviewerType"=>'2');
				$itemsArr = util_arrayUtil::setArrayFn($mainArr,$object['humanResources']);
				//�ж�������Դ��������ӱ�ԭ�еľͱ༭���¼ӵ������ݿ������
				foreach($object['humanResources'] as $key => $value){
					if ($value['id']){
						$value["interviewId"] = $object['id'];
						$value["interviewerType"] = '2';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->edit_d($value ,true);
						}
					} else {
						$value["interviewId"] = $object['id'];
						$value["interviewerType"] = '2';
						if (!isset($value['isDelTag'])) {
							$interviewcomDao->add_d($value ,true);
						}
					}
				}
			}
			
			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ������������ҳ���������ͳ��������Ϣ
	 */
	function getDeptPer_d($id){
		$this->searchArr['id'] = $id;
		$interview = $this->list_d('select_list');
		$apply = new model_hr_recruitment_apply();
		$apply->groupBy = 'c.id';
		$apply->searchArr['ExaStatus'] = "���";
		$applyRows = $apply->list_d('select_list');
		$personnel = new model_hr_personnel_personnel();
		$beEntryNumS = 0; //�������Ŵ���ְ����
		$beEntryNumT = 0; //�������Ŵ���ְ����
		$beEntryNumF = 0; //�ļ����Ŵ���ְ����
		//��ȡ�����ļ����Ŵ���ְ����
		foreach($applyRows as $key => $val) {
			if ($interview[0]['deptNameS'] && $val['deptNameS'] == $interview[0]['deptNameS']) {
				$beEntryNumS = $beEntryNumS + $val['beEntryNum'];
			}
			if ($interview[0]['deptNameT'] && $val['deptNameT'] == $interview[0]['deptNameT']) {
				$beEntryNumT = $beEntryNumT + $val['beEntryNum'];
			}
			if ($interview[0]['deptNameF'] && $val['deptNameF'] == $interview[0]['deptNameF']) {
				$beEntryNumF = $beEntryNumF + $val['beEntryNum'];
			}
		}
		//��ȡ�����ļ�������ְ����
		$employeesStateNumS = 0; //����������ְ����
		$employeesStateNumT = 0; //����������ְ����
		$employeesStateNumF = 0; //�ļ�������ְ����
		if ($interview[0]['deptNameS']) {
			$conditionS['deptNameS'] = $interview[0]['deptNameS'];
			$conditionS['employeesStateName'] = '��ְ';
			$employeesStateNumS = $personnel->findCount($conditionS); //����������ְ����
		}
		if ($interview[0]['deptNameT']) {
			$conditionT['deptNameT'] = $interview[0]['deptNameT'];
			$conditionT['employeesStateName'] = '��ְ';
			$employeesStateNumT = $personnel->findCount($conditionT); //����������ְ����
		}
		if ($interview[0]['deptNameF']) {
			$conditionF['deptNameF'] = $interview[0]['deptNameF'];
			$conditionF['employeesStateName'] = '��ְ';
			$employeesStateNumF = $personnel->findCount($conditionF); //�ļ�������ְ����
		}
		//��ȡ�����ļ�����С��
		$numS = $beEntryNumS + $employeesStateNumS;	//������������С��
		$numT = $beEntryNumT + $employeesStateNumT;	//������������С��
		$numF = $beEntryNumF + $employeesStateNumF;	//�ļ���������С��
		$html = <<<EOT
			<tr>
				<td colspan="6">
					<fieldset><legend><b>������Ա��Ϣ</b></legend>
						<table cellpadding="2" width="100%">
							<tr>
								<td class="form_text_left_three">��������</td>
								<td class="form_text_right">
									{$interview[0]['deptNameS']}
								</td>
								<td class="form_text_left_three">��ְ����</td>
								<td class="form_text_right">
									{$employeesStateNumS}
								</td>
								<td class="form_text_left_three">����ְ����</td>
								<td class="form_text_right">
									{$beEntryNumS}
								</td>
								<td class="form_text_left_three">����С��</td>
								<td class="form_text_right">
									{$numS}
								</td>
							</tr>
							<tr>
								<td class="form_text_left_three">��������</td>
								<td class="form_text_right">
									{$interview[0]['deptNameT']}
								</td>
								<td class="form_text_left_three">��ְ����</td>
								<td class="form_text_right">
									{$employeesStateNumT}
								</td>
								<td class="form_text_left_three">����ְ����</td>
								<td class="form_text_right">
									{$beEntryNumT}
								</td>
								<td class="form_text_left_three">����С��</td>
								<td class="form_text_right">
									{$numT}
								</td>
							</tr>
							<tr>
								<td class="form_text_left_three">�ļ�����</td>
								<td class="form_text_right">
									{$interview[0]['deptNameF']}
								</td>
								<td class="form_text_left_three">��ְ����</td>
								<td class="form_text_right">
									{$employeesStateNumF}
								</td>
								<td class="form_text_left_three">����ְ����</td>
								<td class="form_text_right">
									{$beEntryNumF}
								</td>
								<td class="form_text_left_three">����С��</td>
								<td class="form_text_right">
									{$numF}
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
EOT;
		return $html;
	}

	/**
	 * ����offer������Ϣ��������
	 * @param entryNotice  ��ְ��Ϣ
	 */
	function mailToXZ_d($id ,$entryNotice) {
		$obj = $this->get_d($id);
		if (!$obj) {
			return false; //2015-01-09ӡ��˵��ʽϵͳ��˵�ᷢ�հ��ʼ�����ϧ�Ҳ�֪����ô���£�����������
		}

		try{
			$this->start_d();

			$deptDao = new model_deptuser_dept_dept();
			$deptObj = $deptDao->getSuperiorDeptById_d($obj['deptId']);
			$content = <<<EOT
					<br>
					������<font color=blue>$obj[userName]</font><br>
					�绰��$obj[phone]<br>
					���䣺$obj[email]<br>
					�������ţ�$deptObj[deptNameS]<br>
					�������ţ�$deptObj[deptNameT]<br>
					��λ��$obj[hrJobName]<br>
					��������$obj[personLevel]<br>
					��ְʱ�䣺$entryNotice[entryDate]<br>
					��ְЭ���ˣ�$entryNotice[assistManName]<br><br>
EOT;
			//�Ƿ�����ʵϰ���ķ����ʼ�֪ͨ���������绰�Ѳ�����
			if ($obj['deptId'] != 155) {
				$txbzContent = $content."�绰�Ѳ�����<font color='blue'>$obj[phoneSubsidy]</font>�������ڣ�;  <font color='blue'>$obj[phoneSubsidyFormal]</font>��ת����";
				$this->mailDeal_d('interviewAddOffer_txbz' ,null ,array('id' => $obj['id'] ,'content' => $txbzContent));
			}

			//��������豸��Ϣ��Ҫ������������
			$xqdnContent = $content."�칫���������豸���ͣ�<font color='blue'>$obj[useDemandEqu]</font>";
			$this->mailDeal_d('interviewAddOffer_xqdn' ,null ,array('id' => $obj['id'] ,'content' => $xqdnContent));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/******************* S ���뵼��ϵ�� ************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename  = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType  = $_FILES["inputExcel"]["type"];

		$resultArr    = array(); //�������
		$excelData    = array(); //excel��������
		$tempArr      = array();
		$inArr        = array(); //��������
		$userConutArr = array(); //�û�����
		$userArr      = array(); //�û�����
		$deptArr      = array(); //��������
		$datadictArr  = array(); //�����ֵ�����
		$jobsArr      = array(); //ְλ����
		$applyArr	  = array(); //��Ա��������

		$otherDataDao    = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictDao     = new model_system_datadict_datadict();
		$provinceDao     = new model_system_procity_province(); //����
		$cityDao         = new model_system_procity_city(); //ʡ��
		$branchDao       = new model_deptuser_branch_branch(); //������˾
		$area            = new includes_class_global(); //���������֧������
		$applyDao        = new model_hr_recruitment_apply(); // ��Ա����
		$resumeDao       = new model_hr_recruitment_resume(); // ����
		$employmentDao   = new model_hr_recruitment_employment(); // ְλ����
		$recommendDao    = new model_hr_recruitment_recommend(); // �ڲ��Ƽ�
		$interviewcomDao = new model_hr_recruitment_interviewComment(); // ��������

		//�����ֶα�ͷ����
		$objNameArr = array (
			//������Ϣ
			'userName', //����
			'sexy', //�Ա�
			'phone', //��ϵ�绰
			'email', //��������
			'positionsName', //ӦƸְλ
			'deptName', //���˲���
			'postTypeName', //ְλ����
			'developPositionName',//�з�ְλ
			'positionLevel', //����
			'workProvince', //�����ص�ʡ
			'workCity', //�����ص���
			'resumeCode', //�������
			'applyCode', //����ְλ����
			'parentCode', //������Ա����
			'recommendCode', //�����ڲ��Ƽ�
			'ExaStatus', //����״̬

			//���˲������
			'interviewer', //���˲������Թ�
			'interviewDate', //���˲�����������
			'useWriteEva', //���˲��ű�������
			'interviewEva', //���˲�����������
			'useInterviewResult', //���Խ��
			'useHireTypeName', //¼����ʽ
			'projectType', //��Ŀ����
			'projectGroup', //������Ŀ��
			'sysCompanyName', //������˾
			'useAreaName', //���������֧������
			'personLevel', //�����ȼ�
			'isLocal', //�Ƿ񱾵ػ�
			'useTrialWage', //�����ڻ�������
			'useFormalWage', //ת����������
			'internshipSalaryType', //ʵϰ��������
			'internshipSalary', //ʵϰ����
			'eatCarSubsidy', //�ͳ�����ʵϰ����
			'computerIntern', //���Բ�����ʵϰ����
			'mealCarSubsidy', //�ͳ��������ã�
			'mealCarSubsidyFormal', //�ͳ�����ת����
			'phoneSubsidy', //�绰�Ѳ��������ã�
			'phoneSubsidyFormal', //�绰�Ѳ�����ת����
			'tripSubsidy', //���������ֵ�����ã�
			'tripSubsidyFormal', //���������ֵ��ת����
			'computerSubsidy', //���Բ��������ã�
			'computerSubsidyFormal', //���Բ�����ת����
			'bonusLimit', //��������ֵ�����ã�
			'bonusLimitFormal', //��������ֵ��ת����
			'manageSubsidy', //������������ã�
			'manageSubsidyFormal', //���������ת����
			'accommodSubsidy', //��ʱס�޲��������ã�
			'accommodSubsidyFormal', //��ʱס�޲�����ת����
			'otherSubsidy', //�������������ã�
			'otherSubsidyFormal', //����������ת����
			'workBonus', //�����������ã�
			'workBonusFormal', //��������ת����
			'levelSubsidy', //���������������ã�
			'levelSubsidyFormal', //������������ת����
			'areaSubsidy', //�����������ã�
			'areaSubsidyFormal', //��������ת����
			'controlPost', //�����λ
			'isCompanyStandard', //�����Ƿ񰴹�˾��׼
			'useSign', //ǩ������ҵ����Э�顷
			'useDemandEqu', //�칫���������豸����
			'useManager', //ȷ����
			'useSignDate', //ȷ������
			'tutor', //ָ����ʦ

			//������Դ�������
			'interviewer2', //HR���Թ�
			'interviewDate2', //HR��������
			'interviewEva2', //HR��������
			'hrInterviewResult', //��������
			'hrSourceType1Name', //������Դ����
			'hrSourceType2Name', //������ԴС��
			'hrJobName', //¼��ְλ
			'probation', //�����ڣ��£�
			'contractYear', //��ͬ����
			'socialPlace', //�籣�����
			'hrIsMatch', //����������н�㼰н���Ƿ��Ӧ
			'wageLevelName', //���ʼ���
			'entryDate', //Ԥ����ְʱ��
			'addType', //��Ա����
			'eprovince', //���߲���ʡ��
			'ecity', //���߲�������
			'manager', //HRȷ����
			'SignDate' //ȷ������
		);

		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear($filename ,$temp_name);
			spl_autoload_register("__autoload");

			if (is_array($excelData)) {
				$objectArr = array();
				foreach ($excelData as $key => $val) {
					if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5])) {
						continue;
					} else {
						foreach ($objNameArr as $k => $v) {
							$objectArr[$key][$v] = trim($val[$k]); //��ʽ������
						}
					}
				}

				//������ѭ��
				foreach($objectArr as $key => $val) {
					if ($key === 0) {
						continue ;
					}
					$inArr = array();
					$actNum = $key + 2;

					//����
					if (!empty($val['userName'])) {
						$inArr['userName'] = $val['userName'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�Ա�
					if (!empty($val['sexy'])) {
						$inArr['sexy'] = $val['sexy'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�Ա�Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��ϵ�绰
					if (!empty($val['phone'])) {
						$inArr['phone'] = $val['phone'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��ϵ�绰Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��������
					if (!empty($val['email'])) {
						$inArr['email'] = $val['email'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���˲���
					if (!empty($val['deptName'])) {
						$rs = $otherDataDao->getDeptId_d($val['deptName']);
						if (!empty($rs)) {
							$inArr['deptName'] = $val['deptName'];
							$inArr['deptId'] = $rs;
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ����˲���</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲���Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ӦƸְλ
					if (!empty($val['positionsName'])) {
						$inArr['positionsName'] = $val['positionsName'];
						$jobsDao = new model_deptuser_jobs_jobs();
						$jobsObj = $jobsDao->find(array('dept_id' => $inArr['deptId'] ,'name' => $inArr['positionsName']));
						if (!empty($jobsObj)) {
							$inArr['positionsId'] = $jobsObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!���˲��Ų����ڸ�ӦƸְλ</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ӦƸְλΪ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ְλ����
					if (!empty($val['postTypeName'])) {
						$rs = $datadictDao->getCodeByName('YPZW' ,$val['postTypeName']);
						if (!empty($rs)) {
							$inArr['postTypeName'] = $val['postTypeName'];
							$inArr['postType'] = $rs;
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְλ����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ְλ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�з�ְλ
					if (!empty($val['developPositionName'])) {
						$inArr['developPositionName'] = $val['developPositionName'];
					}

					//����
					if (!empty($val['positionLevel'])) {
						if ($inArr['postType'] == 'YPZW-WY') {
							$levelDao = new model_hr_basicinfo_level();
							$levelObj = $levelDao->find(array('personLevel' => $val['positionLevel']));
							if (!empty($levelObj)) {
								$inArr['positionLevel'] = $levelObj['id'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ���</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							switch ($val['positionLevel']) {
								case '����' :
									$inArr['positionLevel'] = 1;
									break;
								case '�м�' :
									$inArr['positionLevel'] = 2;
									break;
								case '�߼�' :
									$inArr['positionLevel'] = 3;
									break;
								default :
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ���</span>';
									array_push($resultArr ,$tempArr);
									continue;
							}
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�����ص�ʡ��
					if(!empty($val['workProvince'])) {
						$provinceObj = $provinceDao->find(array('provinceName' => $val['workProvince']));
						if (!empty($provinceObj)) {
							$inArr['workProvince'] = $val['workProvince'];
							$inArr['workProvinceId'] = $provinceObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĹ����ص�ʡ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����ص�ʡ��Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�����ص����
					if(!empty($val['workCity'])) {
						$cityObj = $cityDao->find(array('cityName' => $val['workCity']));
						if (!empty($cityObj)) {
							$inArr['workCity'] = $val['workCity'];
							$inArr['workCityId'] = $cityObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĹ����ص����</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����ص����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�������
					if(!empty($val['resumeCode'])) {
						$resumeObj = $resumeDao->find(array('resumeCode' => $val['resumeCode']));
						if (!empty($resumeObj)) {
							$inArr['resumeCode'] = $val['resumeCode'];
							$inArr['resumeId']   = $resumeObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵļ������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//ְλ������
					if(!empty($val['applyCode'])) {
						$employmentObj = $employmentDao->find(array('employmentCode' => $val['applyCode']));
						if (!empty($employmentObj)) {
							$inArr['applyCode'] = $val['applyCode'];
							$inArr['applyId']   = $employmentObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�ְλ������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//��Ա������
					if(!empty($val['parentCode'])) {
						if ($applyArr[$val['parentCode']]) {
							$inArr['parentCode'] = $applyArr[$val['parentCode']]['parentCode'];
							$inArr['parentId']   = $applyArr[$val['parentCode']]['id'];
						} else {
							$applyObj = $applyDao->find(array('formCode' => $val['parentCode']));
							if (!empty($applyObj)) {
								if ($applyObj['needNum'] - (int)$applyObj['entryNum'] - (int)$applyObj['beEntryNum'] - (int)$applyObj['stopCancelNum'] > 0) {
									$applyArr[$val['parentCode']] = array(
										'parentCode' => $applyObj['formCode'],
										'id' => $applyObj['id']
									);
									$inArr['parentCode'] = $val['parentCode'];
									$inArr['parentId']   = $applyObj['id'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!��Ա����û������Ƹ����</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ���Ա������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��Ա������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�ڲ��Ƽ����
					if(!empty($val['recommendCode'])) {
						$recommendObj = $recommendDao->find(array('formCode' => $val['recommendCode']));
						if (!empty($recommendObj)) {
							$inArr['recommendCode'] = $val['recommendCode'];
							$inArr['recommendId']   = $recommendObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ��ڲ��Ƽ����</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//����״̬
					if(!empty($val['ExaStatus'])) {
						if ($val['ExaStatus'] == '���') {
							$inArr['state']     = 1;
							$inArr['ExaStatus'] = '���';
						} else if ($val['ExaStatus'] == 'δ�ύ') {
							$inArr['state']     = 0;
							$inArr['ExaStatus'] = 'δ�ύ';
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!����״̬��Ч</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$inArr['state']     = 0;
						$inArr['ExaStatus'] = 'δ�ύ';
					}

					/**************** S ���˲��Ų��� *******************/
					//���˲������Թ�
					if (!empty($val['interviewer'])) {
						$rs = $otherDataDao->getUserInfo($val['interviewer']);
						if (!empty($rs)) {
							$inArr['use'][0]['interviewer'] = $val['interviewer'];
							$inArr['use'][0]['interviewerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ����˲������Թ�</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲������Թ�Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���˲�����������
					if (!empty($val['interviewDate']) && $val['interviewDate'] != '0000-00-00') {
						if (!is_numeric($val['interviewDate'])) {
							$inArr['use'][0]['interviewDate'] = $val['interviewDate'];
						} else {
							$interviewDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['interviewDate'] - 1 ,1900)));
							$inArr['use'][0]['interviewDate'] = $interviewDate;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲�����������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���˲��ű�������
					if (!empty($val['useWriteEva'])) {
						$inArr['use'][0]['useWriteEva'] = $val['useWriteEva'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲��ű�������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���˲�����������
					if (!empty($val['interviewEva'])) {
						$inArr['use'][0]['interviewEva'] = $val['interviewEva'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲�����������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���Խ��
					if (!empty($val['useInterviewResult'])) {
						switch ($val['useInterviewResult']) {
							case '����¼��' :
								$inArr['useInterviewResult'] = 1;
								break;
							case '�����˲�' :
								$inArr['useInterviewResult'] = 2;
								break;
							default:
								$inArr['useInterviewResult'] = 1; //Ĭ��
								break;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���Խ��Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//¼����ʽ
					if (!empty($val['useHireTypeName'])) {
						$rs = $datadictDao->getCodeByName('HRLYXX' ,$val['useHireTypeName']);
						if (!empty($rs)) {
							$inArr['useHireType'] = $rs;
							$inArr['useHireTypeName'] = $val['useHireTypeName'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�¼����ʽ</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!¼����ʽΪ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��Ŀ����
					if (!empty($val['projectType'])) {
						switch ($val['projectType']) {
							case '�з���Ŀ' :
								$inArr['projectType'] = 'YFXM';
								break;
							case '������Ŀ' :
								$inArr['projectType'] = 'GCXM';
								break;
							default:
								$inArr['projectType'] = ''; //Ĭ��
								break;
						}
					}

					//������Ŀ��
					if (!empty($val['projectGroup'])) {
						$inArr['projectGroup'] = $val['projectGroup'];
					}

					//������˾
					if (!empty($val['sysCompanyName'])) {
						$branchObj = $branchDao->find(array('NameCN' => $val['sysCompanyName']) ,null ,'ID');
						if (!empty($branchObj)) {
							$inArr['sysCompanyName'] = $val['sysCompanyName'];
							$inArr['sysCompanyId'] = $branchObj['ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĹ�����˾</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!������˾Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���������֧������
					if (!empty($val['useAreaName'])) {
						$sqlStr = 'SELECT ID FROM area WHERE Name="'.$val['useAreaName'].'"';
						$areaObj = $this->findSql($sqlStr);
						if (!empty($areaObj[0])) {
							$inArr['useAreaName'] = $val['useAreaName'];
							$inArr['useAreaId'] = $areaObj[0]['ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĹ��������֧������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���������֧������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�����ȼ�
					if (!empty($val['personLevel'])) {
						$levelDao = new model_hr_basicinfo_level();
						$levelObj = $levelDao->find(array('personLevel' => $val['personLevel']) ,null ,'id');
						if (!empty($levelObj)) {
							$inArr['personLevel'] = $val['personLevel'];
							$inArr['personLevelId'] = $levelObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ����ȼ�</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����ȼ�Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�Ƿ񱾵ػ�
					if (!empty($val['isLocal'])) {
						switch ($val['isLocal']) {
							case '��' :
								$inArr['isLocal'] = '��';
								break;
							case '��' :
								$inArr['isLocal'] = '��';
								break;
							default:
								$inArr['isLocal'] = '��'; //Ĭ��
								break;
						}
					}

					//ʵϰ��н��
					if ($inArr['useHireType'] == 'HRLYXX-03') {
						/**************ʵϰ��û�е��ֶ�**************/
						$inArr['probation']    = 0;
						$inArr['contractYear'] = 0;
						$inArr['socialPlace']  = '������';
						$inArr['contractYear'] = 0;
						/**************ʵϰ��û�е��ֶ�**************/

						//ʵϰ����������
						if (!empty($val['internshipSalaryType'])) {
							if ($val['internshipSalaryType'] == '�չ���' || $val['internshipSalaryType'] == '�¹���') {
								$inArr['internshipSalaryType'] = $val['internshipSalaryType'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ʵϰ����������</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!ʵϰ����������Ϊ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//ʵϰ������
						if (!empty($val['internshipSalary'])) {
							if (is_numeric($val['internshipSalary'])) {
								$inArr['internshipSalary'] = $val['internshipSalary'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!ʵϰ�����ʱ�����д������С��</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!ʵϰ������Ϊ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�ͳ�����ʵϰ����
						if (!empty($val['eatCarSubsidy'])) {
							if (is_numeric($val['eatCarSubsidy'])) {
								$inArr['eatCarSubsidy'] = $val['eatCarSubsidy'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�ͳ�����ʵϰ����������д������С��</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['eatCarSubsidy'] = 0;
						}

						//���Բ�����ʵϰ����
						if (!empty($val['computerIntern'])) {
							if (is_numeric($val['computerIntern'])) {
								$inArr['computerIntern'] = $val['computerIntern'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!���Բ�����ʵϰ����������д������С��</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['computerIntern'] = 0;
						}

						$dayNum = ($inArr['internshipSalaryType'] == '�չ���') ? 30 : 1;
						//ʵϰ�����ܶ�Ԥ��
						$inArr['allInternship'] = (double)$inArr['internshipSalary'] * $dayNum
												+ (double)$inArr['eatCarSubsidy']
												+ (double)$inArr['computerIntern'];
					} else { // ��ʵϰ��н��
						//�����ڹ���
						if (!empty($val['useTrialWage'])) {
							$inArr['useTrialWage'] = $val['useTrialWage'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڻ�������Ϊ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//ת������
						if (!empty($val['useFormalWage'])) {
							$inArr['useFormalWage'] = $val['useFormalWage'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!ת����������Ϊ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�绰�Ѳ��������ã�
						if (!empty($val['phoneSubsidy'])) {
							if (is_numeric($val['phoneSubsidy'])) {
								$inArr['phoneSubsidy'] = $val['phoneSubsidy'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�绰�Ѳ��������ã�������д������С��</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['phoneSubsidy'] = 0;
						}

						//�绰�Ѳ�����ת����
						if (!empty($val['phoneSubsidyFormal'])) {
							if (is_numeric($val['phoneSubsidyFormal'])) {
								$inArr['phoneSubsidyFormal'] = $val['phoneSubsidyFormal'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�绰�Ѳ�����ת����������д������С��</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['phoneSubsidyFormal'] = 0;
						}

						//�ͳ��������ã�
						if (!empty($val['mealCarSubsidy'])) {
							if (in_array($val['mealCarSubsidy'], array(0 ,330 ,440))) {
								$inArr['mealCarSubsidy'] = $val['mealCarSubsidy'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�ͳ��������ã�������д0��330��440</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealCarSubsidy'] = 0;
						}

						//�ͳ�����ת����
						if (!empty($val['mealCarSubsidyFormal'])) {
							if (in_array($val['mealCarSubsidyFormal'], array(0 ,330 ,440))) {
								$inArr['mealCarSubsidyFormal'] = $val['mealCarSubsidyFormal'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�ͳ�����ת����������д0��330��440</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealCarSubsidyFormal'] = 0;
						}

						//�����ڣ��£�
						if (!empty($val['probation'])) {
							if (strcmp($val['probation'] ,(int)$val['probation']) === 0) {
								$inArr['probation'] = $val['probation'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڣ��£�������д����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['probation'] = 0;
						}

						//��ͬ����
						if (!empty($val['contractYear'])) {
							if (strcmp($val['contractYear'] ,(int)$val['contractYear']) === 0) {
								$inArr['contractYear'] = $val['contractYear'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!��ͬ���ޱ�����д����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['contractYear'] = 0;
						}

						//�籣�����
						if (!empty($val['socialPlace'])) {
							$socialplaceDao = new model_hr_basicinfo_socialplace();
							$socialplaceObj = $socialplaceDao->find(array('socialCity' => $val['socialPlace']) ,null ,'id');
							if (!empty($levelObj)) {
								$inArr['socialPlace'] = $val['socialPlace'];
								$inArr['socialPlaceId'] = $socialplaceObj['id'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ��籣�����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�籣�����Ϊ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					if ($inArr['postType'] == 'YPZW-WY') {
						/********* ����û�еĲ��� *********/
						$inArr['mealCarSubsidy'] = 0;
						$inArr['mealCarSubsidyFormal'] = 0;
						/********* ����û�еĲ��� *********/

						/********* ���Ź��õ� *********/
						// �����λ
						if (!empty($val['controlPost'])) {
							$rs = $datadictDao->getCodeByName('HRGLGW' ,$val['controlPost']);
							if (!empty($rs)) {
								$inArr['controlPostCode'] = $rs;
								$inArr['controlPost']     = $val['controlPost'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĹ����λ</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����λΪ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
						/********* ���Ź��õ� *********/

						$leveltype = $this->getLevelType_d($inArr['positionLevel']);
						if ($leveltype == 1) { //A��
							//���������ֵ�����ã�
							if (!empty($val['tripSubsidy'])) {
								if (is_numeric($val['tripSubsidy'])) {
									$inArr['tripSubsidy'] = $val['tripSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���������ֵ�����ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['tripSubsidy'] = 0;
							}

							//���������ֵ��ת����
							if (!empty($val['tripSubsidyFormal'])) {
								if (is_numeric($val['tripSubsidyFormal'])) {
									$inArr['tripSubsidyFormal'] = $val['tripSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���������ֵ��ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['tripSubsidyFormal'] = 0;
							}

							//���Բ��������ã�
							if (!empty($val['computerSubsidy'])) {
								if (is_numeric($val['computerSubsidy'])) {
									$inArr['computerSubsidy'] = $val['computerSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���Բ��������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidy'] = 0;
							}

							//���Բ�����ת����
							if (!empty($val['computerSubsidyFormal'])) {
								if (is_numeric($val['computerSubsidyFormal'])) {
									$inArr['computerSubsidyFormal'] = $val['computerSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���Բ�����ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidyFormal'] = 0;
							}

							//��������ֵ�����ã�
							if (!empty($val['bonusLimit'])) {
								if (is_numeric($val['bonusLimit'])) {
									$inArr['bonusLimit'] = $val['bonusLimit'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��������ֵ�����ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['bonusLimit'] = 0;
							}

							//��������ֵ��ת����
							if (!empty($val['bonusLimitFormal'])) {
								if (is_numeric($val['bonusLimitFormal'])) {
									$inArr['bonusLimitFormal'] = $val['bonusLimitFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��������ֵ��ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['bonusLimitFormal'] = 0;
							}

							//������������ã�
							if (!empty($val['manageSubsidy'])) {
								if (is_numeric($val['manageSubsidy'])) {
									$inArr['manageSubsidy'] = $val['manageSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!������������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['manageSubsidy'] = 0;
							}

							//���������ת����
							if (!empty($val['manageSubsidyFormal'])) {
								if (is_numeric($val['manageSubsidyFormal'])) {
									$inArr['manageSubsidyFormal'] = $val['manageSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���������ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['manageSubsidyFormal'] = 0;
							}

							//��ʱס�޲��������ã�
							if (!empty($val['accommodSubsidy'])) {
								if (is_numeric($val['accommodSubsidy'])) {
									$inArr['accommodSubsidy'] = $val['accommodSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��ʱס�޲��������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['accommodSubsidy'] = 0;
							}

							//��ʱס�޲�����ת����
							if (!empty($val['accommodSubsidyFormal'])) {
								if (is_numeric($val['accommodSubsidyFormal'])) {
									$inArr['accommodSubsidyFormal'] = $val['accommodSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��ʱס�޲�����ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['accommodSubsidyFormal'] = 0;
							}

							//�������������ã�
							if (!empty($val['otherSubsidy'])) {
								if (is_numeric($val['otherSubsidy'])) {
									$inArr['otherSubsidy'] = $val['otherSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�������������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['otherSubsidy'] = 0;
							}

							//����������ת����
							if (!empty($val['otherSubsidyFormal'])) {
								if (is_numeric($val['otherSubsidyFormal'])) {
									$inArr['otherSubsidyFormal'] = $val['otherSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!����������ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['otherSubsidyFormal'] = 0;
							}

							//�ܶ�Ԥ��
							$inArr['allTrialWage'] = (double)$inArr['useTrialWage']
													+ (double)$inArr['tripSubsidy']
													+ (double)$inArr['phoneSubsidy']
													+ (double)$inArr['computerSubsidy']
													+ (double)$inArr['manageSubsidy']
													+ (double)$inArr['accommodSubsidy']
													+ (double)$inArr['bonusLimit']
													+ (double)$inArr['otherSubsidy'];
							$inArr['allFormalWage'] = (double)$inArr['useFormalWage']
													+ (double)$inArr['tripSubsidyFormal']
													+ (double)$inArr['phoneSubsidyFormal']
													+ (double)$inArr['computerSubsidyFormal']
													+ (double)$inArr['manageSubsidyFormal']
													+ (double)$inArr['accommodSubsidyFormal']
													+ (double)$inArr['bonusLimitFormal']
													+ (double)$inArr['otherSubsidyFormal'];
						} else if ($leveltype == 2) {
							//���Բ��������ã�
							if (!empty($val['computerSubsidy'])) {
								if (is_numeric($val['computerSubsidy'])) {
									$inArr['computerSubsidy'] = $val['computerSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���Բ��������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidy'] = 0;
							}

							//���Բ�����ת����
							if (!empty($val['computerSubsidyFormal'])) {
								if (is_numeric($val['computerSubsidyFormal'])) {
									$inArr['computerSubsidyFormal'] = $val['computerSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���Բ�����ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['computerSubsidyFormal'] = 0;
							}

							//�����������ã�
							if (!empty($val['workBonus'])) {
								if (is_numeric($val['workBonus'])) {
									$inArr['workBonus'] = $val['workBonus'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['workBonus'] = 0;
							}

							//��������ת����
							if (!empty($val['workBonusFormal'])) {
								if (is_numeric($val['workBonusFormal'])) {
									$inArr['workBonusFormal'] = $val['workBonusFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��������ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['workBonusFormal'] = 0;
							}

							//���������������ã�
							if (!empty($val['levelSubsidy'])) {
								if (is_numeric($val['levelSubsidy'])) {
									$inArr['levelSubsidy'] = $val['levelSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!���������������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['levelSubsidy'] = 0;
							}

							//������������ת����
							if (!empty($val['levelSubsidyFormal'])) {
								if (is_numeric($val['levelSubsidyFormal'])) {
									$inArr['levelSubsidyFormal'] = $val['levelSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!������������ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['levelSubsidyFormal'] = 0;
							}

							//�����������ã�
							if (!empty($val['areaSubsidy'])) {
								if (is_numeric($val['areaSubsidy'])) {
									$inArr['areaSubsidy'] = $val['areaSubsidy'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����������ã�������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['areaSubsidy'] = 0;
							}

							//��������ת����
							if (!empty($val['areaSubsidyFormal'])) {
								if (is_numeric($val['areaSubsidyFormal'])) {
									$inArr['areaSubsidyFormal'] = $val['areaSubsidyFormal'];
								} else {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!��������ת����������д������С��</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$inArr['areaSubsidyFormal'] = 0;
							}

							//�ܶ�Ԥ��
							$inArr['allTrialWage'] = (double)$inArr['useTrialWage']
													+ (double)$inArr['workBonus']
													+ (double)$inArr['phoneSubsidy']
													+ (double)$inArr['computerSubsidy']
													+ (double)$inArr['levelSubsidy']
													+ (double)$inArr['areaSubsidy'];
							$inArr['allFormalWage'] = (double)$inArr['useFormalWage']
													+ (double)$inArr['workBonusFormal']
													+ (double)$inArr['phoneSubsidyFormal']
													+ (double)$inArr['computerSubsidyFormal']
													+ (double)$inArr['levelSubsidyFormal']
													+ (double)$inArr['areaSubsidyFormal'];
						}
					}

					//�����Ƿ񰴹�˾��׼
					if (!empty($val['isCompanyStandard'])) {
						switch ($val['isCompanyStandard']) {
							case '��' :
								$inArr['isCompanyStandard'] = 1;
								break;
							case '��' :
								$inArr['isCompanyStandard'] = 0;
								break;
							default:
								$inArr['isCompanyStandard'] = 1; //Ĭ��
								break;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����Ƿ񰴹�˾��׼Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ǩ������ҵ����Э�顷
					if (!empty($val['useSign'])) {
						switch ($val['useSign']) {
							case '��' :
								$inArr['useSign'] = '��';
								break;
							case '��' :
								$inArr['useSign'] = '��';
								break;
							default:
								$inArr['useSign'] = '��'; //Ĭ��
								break;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ǩ������ҵ����Э�顷Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�칫���������豸����
					if (!empty($val['useDemandEqu'])) {
						switch ($val['useDemandEqu']) {
							case '��˾�ṩ�ʼǱ�����' :
								$inArr['useDemandEqu'] = '��˾�ṩ�ʼǱ�����';
								break;
							case '��˾�ṩ̨ʽ����' :
								$inArr['useDemandEqu'] = '��˾�ṩ̨ʽ����';
								break;
							case '�Ա��ʼǱ�����' :
								$inArr['useDemandEqu'] = '�Ա��ʼǱ�����';
								break;
							default:
								$inArr['useDemandEqu'] = ''; //Ĭ��
								break;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�칫���������豸����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ȷ����
					if (!empty($val['useManager'])) {
						$rs = $otherDataDao->getUserInfo($val['useManager']);
						if (!empty($rs)) {
							$inArr['useManager'] = $val['useManager'];
							$inArr['useManagerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ȷ����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ȷ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ȷ������
					if (!empty($val['useSignDate']) && $val['useSignDate'] != '0000-00-00') {
						if (!is_numeric($val['useSignDate'])) {
							$inArr['useSignDate'] = $val['useSignDate'];
						} else {
							$useSignDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['useSignDate'] - 1 ,1900)));
							$inArr['useSignDate'] = $useSignDate;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ȷ������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ָ����ʦ
					if (!empty($val['tutor'])) {
						$rs = $otherDataDao->getUserInfo($val['tutor']);
						if (!empty($rs)) {
							$inArr['tutor'] = $val['tutor'];
							$inArr['tutorId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ָ����ʦ</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					/**************** ������Դ���Ų��� *******************/
					//HR���Թ�
					if (!empty($val['interviewer2'])) {
						$rs = $otherDataDao->getUserInfo($val['interviewer2']);
						if (!empty($rs)) {
							$inArr['hr'][0]['interviewer'] = $val['interviewer2'];
							$inArr['hr'][0]['interviewerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�HR�Թ�</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!HR�Թ�Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HR��������
					if (!empty($val['interviewDate2']) && $val['interviewDate2'] != '0000-00-00') {
						if (!is_numeric($val['interviewDate2'])) {
							$inArr['hr'][0]['interviewDate'] = $val['interviewDate2'];
						} else {
							$interviewDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['interviewDate2'] - 1 ,1900)));
							$inArr['hr'][0]['interviewDate'] = $interviewDate;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!HR��������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HR��������
					if (!empty($val['interviewEva2'])) {
						$inArr['hr'][0]['interviewEva'] = $val['interviewEva2'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���˲��ű�������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��������
					if (!empty($val['hrInterviewResult'])) {
						$inArr['hrInterviewResult'] = $val['hrInterviewResult'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//������Դ����
					if (!empty($val['hrSourceType1Name'])) {
						$rs = $datadictDao->getCodeByName('JLLY' ,$val['hrSourceType1Name']);
						if (!empty($rs)) {
							$inArr['hrSourceType1'] = $rs;
							$inArr['hrSourceType1Name'] = $val['hrSourceType1Name'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵļ�����Դ����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!������Դ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//������ԴС��
					if (!empty($val['hrSourceType2Name'])) {
						$inArr['hrSourceType2Name'] = $val['hrSourceType2Name'];
					}

					//¼��ְλ
					if (!empty($val['hrJobName'])) {
						$inArr['hrJobName'] = $val['hrJobName'];
						$jobsDao = new model_deptuser_jobs_jobs();
						$jobsObj = $jobsDao->find(array('dept_id' => $inArr['deptId'] ,'name' => $inArr['hrJobName']));
						if (!empty($jobsObj)) {
							$inArr['hrJobId'] = $jobsObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!���˲��Ų����ڸ�¼��ְλ</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!¼��ְλΪ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//����������н�㼰н���Ƿ��Ӧ
					if (!empty($val['hrIsMatch'])) {
						switch ($val['hrIsMatch']) {
							case '��Ӧ' :
								$inArr['hrIsMatch'] = '��Ӧ';
								break;
							case '����Ӧ' :
								$inArr['hrIsMatch'] = '����Ӧ';
								break;
							default :
								$inArr['hrIsMatch'] = '��Ӧ'; //Ĭ��
								break;
						}
					}

					//���ʼ���
					if (!empty($val['wageLevelName'])) {
						$rs = $datadictDao->getCodeByName('HRGZJB' ,$val['wageLevelName']);
						if (!empty($rs)) {
							$inArr['wageLevelCode'] = $rs;
							$inArr['wageLevelName'] = $val['wageLevelName'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĹ��ʼ���</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!���ʼ���Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//Ԥ����ְʱ��
					if (!empty($val['entryDate']) && $val['entryDate'] != '0000-00-00') {
						if (!is_numeric($val['entryDate'])) {
							$inArr['entryDate'] = $val['entryDate'];
						} else {
							$entryDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['entryDate'] - 1 ,1900)));
							$inArr['entryDate'] = $entryDate;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!Ԥ����ְʱ��Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��Ա����
					if (!empty($val['addType'])) {
						$rs = $datadictDao->getCodeByName('HRZYLX' ,$val['addType']);
						if (!empty($rs)) {
							$inArr['addTypeCode'] = $rs;
							$inArr['addType'] = $val['addType'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���Ա����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��Ա����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���߲���ʡ��
					if(!empty($val['eprovince'])) {
						$provinceObj = $provinceDao->find(array('provinceName' => $val['eprovince']));
						if (!empty($provinceObj)) {
							$inArr['eprovince'] = $val['eprovince'];
							$inArr['eprovinceId'] = $provinceObj['id'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ����߲���ʡ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//���߲�������
					if(!empty($val['ecity'])) {
						$cityObj = $cityDao->find(array('cityName' => $val['ecity']));
						if (!empty($cityObj)) {
							$inArr['ecity'] = $val['ecity'];
							$inArr['ecityId'] = $cityObj['id'];
							$inArr['ecountry'] = '�й�';
							$inArr['ecountryId'] = 1;
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ����߲�������</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}

					//HRȷ����
					if (!empty($val['manager'])) {
						$rs = $otherDataDao->getUserInfo($val['manager']);
						if (!empty($rs)) {
							$inArr['manager'] = $val['manager'];
							$inArr['managerId'] = $rs['USER_ID'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�HRȷ����</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!HRȷ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//HRȷ������
					if (!empty($val['SignDate']) && $val['SignDate'] != '0000-00-00') {
						if (!is_numeric($val['SignDate'])) {
							$inArr['SignDate'] = $val['SignDate'];
						} else {
							$SignDate = date('Y-m-d',(mktime(0 ,0 ,0 ,1 ,$val['SignDate'] - 1 ,1900)));
							$inArr['SignDate'] = $SignDate;
						}
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!HRȷ������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}
					/**************** E HR���� *******************/


					$inArr['formCode']  = $this->setFormCode_d($key - 1);//���ݱ��
					$inArr['formDate']  = date("Y-m-d"); //��������
					$inArr['deptState'] = 1;
					$inArr['hrState']   = 1;
					$newId = parent::add_d($inArr ,true);
					if ($newId) {
						foreach($inArr['use'] as $k => $v){
							$v["interviewId"] = $newId;
							$v["interviewerType"] = '1';
							$v["resumeId"] = $inArr['resumeId'];
							$v["resumeCode"] = $inArr['resumeCode'];
							$interviewcomDao->add_d($v ,true);
						}
						foreach($inArr['hr'] as $k => $v){
							$v["interviewId"] = $newId;
							$v["interviewerType"] = '2';
							$v["resumeId"] = $inArr['resumeId'];
							$v["resumeCode"] = $inArr['resumeCode'];
							$interviewcomDao->add_d($v ,true);
						}
						$tempArr['result'] = '����ɹ�';
					} else {
						$tempArr['result'] = '<span class="red">����ʧ��</span>';
					}
					$tempArr['docCode'] = '��'.$actNum.'������';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/**
	 * �������ݱ��
	 */
	function setFormCode_d($num = 0) {
		$formCode = 'PG'.date("YmdHis" ,strtotime('+'. $num .' second'));
		$count = $this->findCount(array('formCode' => $formCode));
		if ($count > 0) {
			return $this->setFormCode_d($num + 1);
		} else {
			return $formCode;
		}
	}
	/******************* E ���뵼��ϵ�� ************************/

	/**
	 * ���ݼ���ID�ж���ʲô���ͣ�A����1��B����2����������3��
	 */
	function getLevelType_d($levelId) {
		$levelDao = new model_hr_basicinfo_level();
		$levelObj = $levelDao->get_d($levelId);
		$firstStr = substr($levelObj['personLevel'] ,0 ,1);
		if (is_numeric($firstStr) || $firstStr == 'A') {
			return 1;
		} else if ($firstStr == 'B') {
			return 2;
		} else {
			return 3;
		}
	}

	/**
	 * ����֪ͨ�����ʼ�
	 */
	function thisMail_d($info){
		//�ı���������������֪ͨ ״̬
		$resumeDao=new model_hr_recruitment_resume();
		$conditions = array("id"=>$info['resumeId']);
		$resumeDao->updateField($conditions,"isInform","1");
		$emailDao = new model_common_mail();
		$emailDao->InterviewEmail($_SESSION['USERNAME'],$_SESSION['EMAIL'],$info['title'],$info['toMail'],$info['content'],$info['toccMailId'],$info['tobccMail']);

	}

	/**
	 * ����excel
	 */
	function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//����һ��Excel������
		//		$objPhpExcelFile = new PHPExcel();

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-������������ģ��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;
		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
		$i = 3;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'AB' . $i );
			for($m = 0; $m < 10; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
			}
		}
		//�������
		ob_end_clean (); //��������������������������
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "������������ģ��.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/**
	 * �������̵Ĵ���
	 */
	function dealAfterAuditIng_d($id ,$taskId) {
		$obj = $this->get_d($id);
		$serviceDeptIdArr = array('120','210','211','212','213','214','215','217','218','219'); //�����߲���id
		if (in_array($obj['deptId'] ,$serviceDeptIdArr)
				&& $obj['postType'] == 'YPZW-WY'
				&& $obj['workProvinceId'] > 0) { //�����ߺ��Ե�ר�����������͡�ʡ��
			$managerId = $this->get_table_fields('oa_esm_office_managerinfo' ,"provinceId='".$obj['workProvinceId']."'" ,'managerId');

			if (empty($managerId)) return false; //û����Ŀ�������˳�����

			$sql = "SELECT s.Item ,p.User ,u.USER_NAME ,p.Endtime ,p.Result ,p.Content
					FROM
						flow_step_partent p
					LEFT JOIN wf_task w ON p.Wf_task_ID = w.task
					LEFT JOIN flow_step s ON p.StepID = s.ID
					LEFT JOIN user u ON p.User = u.USER_ID
					WHERE
						w.code = 'oa_hr_recruitment_interview'
					AND w.task = '$taskId'
					AND w.Pid = '$id'
					ORDER BY p.ID ASC";
			$rs = $this->findSql($sql);
			$content = <<<EOT
				<table border="1px" cellspacing="0px" style="text-align:center">
					<tr style="color:blue;">
						<td width="5%">���</td>
						<td width="15%">������</td>
						<td width="10%">������</td>
						<td width="20%">��������</td>
						<td width="9%">�������</td>
						<td width="27%">�������</td>
					</tr>
EOT;
			foreach ($rs as $key => $val) {
				switch ($val['Result']) {
					case 'ok':
						$result = 'ͬ��';
						break;
					case 'no':
						$result = '��ͬ��';
						break;
					default:
						$result = '';
						break;
				}
				$rowNum = $key + 1;
				$content .= <<<EOT
					<tr>
						<td>$rowNum</td>
						<td>$val[Item]</td>
						<td>$val[USER_NAME]</td>
						<td>$val[Endtime]</td>
						<td>$result</td>
						<td style="text-align:left">$val[Content]</td>
					</tr>
EOT;
			}
			$content .= "</table>";

			$this->mailDeal_d('interviewAudit' ,$managerId ,array('userName' => $obj['userName'] ,'content' => $content));
		}
	}

	/**
	 * ����ͨ���Ĵ���
	 */
	function dealAfterAuditPass_d($id){
		try{
			$this->start_d();

			$obj = $this->get_d($id);

			//��������Ĵ���
			if ($obj['changeTip'] == 1) {
				$state = 1;
				//�����Ƿ��Ѿ�������¼��֪ͨ
				$entryId = $this->get_table_fields('oa_hr_recruitment_entrynotice' ," parentId='$obj[id]' AND state<>0 " ,'id');
				if ($entryId) {
					$state = 2;
				}
				$this->updateById(array('id' => $id ,'changeTip' => 0 ,'state' => $state)); //�޸ı����ʶ
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������صĴ���
	 */
	function dealAfterAuditFail_d($id){
		try{
			$this->start_d();

			$obj = $this->get_d($id);

			//��������Ĵ���
			if ($obj['changeTip'] == 1) {
				$state = 1;
				//�����Ƿ��Ѿ�������¼��֪ͨ
				$entryId = $this->get_table_fields('oa_hr_recruitment_entrynotice' ," parentId='$obj[id]' AND state<>0 " ,'id');
				if ($entryId) {
					$state = 2;
				}
				$this->updateById(array('id' => $id ,'changeTip' => 0 ,'ExaStatus' => '���' ,'state' => $state)); //�޸ı����ʶ
				$this->recoveryLastTime_d($id); //��ԭ���һ���޸ĵļ�¼
			}

			$this->commit_d();
			return $id;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭������ɵĵ���
	 */
	function editAuditFinish_d($object){
		try{
			$this->start_d();

			//ԭʼ��¼
			$oldObj = $this->get_d($object['id']);

			//������˾
			$branchDao = new model_deptuser_branch_branch();
			$branchCN = $branchDao->get_d($object['sysCompanyId']);
			$object['sysCompanyName'] = $branchCN['NameCN'];

			//��������
			$object['useAreaName'] = $this->get_table_fields('area' ," ID='$object[useAreaId]' " ,'Name');

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict ();
			$object['hrSourceType1Name'] = $datadictDao->getDataNameByCode($object['hrSourceType1']);
			$object['useHireTypeName'] = $datadictDao->getDataNameByCode($object['useHireType']);
			$object['addType'] = $datadictDao->getDataNameByCode($object['addTypeCode']);
			$object['wageLevelName'] = $datadictDao->getDataNameByCode($object['wageLevelCode']);
            $object['controlPost']       = $datadictDao->getDataNameByCode($object['controlPostCode']);
			$object = $this->processDatadict($object);

			$id = parent::edit_d($object);
			//�����ж��Ƿ�����ʵ���ϵĸ���
			$affectedRows = $this->_db->affected_rows();
			
			if ($id) {
				//����ӱ�����
				$interviewcomDao = new model_hr_recruitment_interviewComment();
				if (is_array($object['items'])) {
					//�ж����˲�������ӱ�ԭ�еľͱ༭���¼ӵ������ݿ������
					foreach($object['items'] as $key => $value) {
						if ($value['id']){
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '1';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->edit_d($value ,true);
							}
						} else {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '1';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->add_d($value ,true);
							}
						}
					}
				}

				if (is_array($object['humanResources'])) {
					//�ж�������Դ��������ӱ�ԭ�еľͱ༭���¼ӵ������ݿ������
					foreach($object['humanResources'] as $key => $value) {
						if ($value['id']) {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '2';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->edit_d($value ,true);
							}
						} else {
							$value["interviewId"] = $object['id'];
							$value["interviewerType"] = '2';
							if (!isset($value['isDelTag'])) {
								$interviewcomDao->add_d($value ,true);
							}
						}
					}
				}
			}
			
			$this->operationLog_d($oldObj ,$object); //������־��¼
			
			$this->commit_d();
			return $affectedRows;
		}catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������־��¼
	 */
	function operationLog_d($oldObj ,$newObj) {
		$logSettringDao = new model_syslog_setting_logsetting ();
		/******��Ҫת���Ĵ���******/
		//����
		$oldObj['positionLevelName'] = $this->getPositionLevelName_d($oldObj['postType'] ,$oldObj['positionLevel']);
		$newObj['positionLevelName'] = $this->getPositionLevelName_d($newObj['postType'] ,$newObj['positionLevel']);

		//���Խ��
		if ($oldObj['useInterviewResult'] == '0') {
			$oldObj['useInterviewResultName'] = '�����˲�';
		} else {
			$oldObj['useInterviewResultName'] = '����¼��';
		}
		if ($newObj['useInterviewResult'] == '0') {
			$newObj['useInterviewResultName'] = '�����˲�';
		} else {
			$newObj['useInterviewResultName'] = '����¼��';
		}

		//��Ŀ����
		if ($oldObj['projectType'] == 'GCXM') {
			$oldObj['projectTypeName'] = '������Ŀ';
		} else if ($oldObj['projectType'] == 'YFXM') {
			$oldObj['projectTypeName'] = '�з���Ŀ';
		} else {
			$oldObj['projectTypeName'] = '';
		}
		if ($newObj['projectType'] == 'GCXM') {
			$newObj['projectTypeName'] = '������Ŀ';
		} else if ($newObj['projectType'] == 'YFXM') {
			$newObj['projectTypeName'] = '�з���Ŀ';
		} else {
			$newObj['projectTypeName'] = '';
		}

		//�����Ƿ񰴹�˾��׼
		if ($oldObj['isCompanyStandard'] == '1') {
			$oldObj['isCompanyStandardName'] = '��';
		} else {
			$oldObj['isCompanyStandardName'] = '��';
		}
		if ($newObj['isCompanyStandard'] == '1') {
			$newObj['isCompanyStandardName'] = '��';
		} else {
			$newObj['isCompanyStandardName'] = '��';
		}

		/******���ֽ�������⴦��********/
		//�����ܶ�Ԥ��
		if ($newObj['allTrialWage'] == '') {
			$newObj['allTrialWage'] = 0.00;
		}

		//ת���ܶ�Ԥ��
		if ($newObj['allFormalWage'] == '') {
			$newObj['allFormalWage'] = 0.00;
		}

		//ʵϰ����
		if ($newObj['internshipSalary'] == '') {
			$newObj['internshipSalary'] = 0.00;
		}

		$logSettringDao->compareModelObj($this->tbl_name ,$oldObj ,$newObj);
	}

	/**
	 * ����ְλ���ͺͼ���id����ȡ�������������
	 */
	function getPositionLevelName_d($postType ,$positionLevel) {
		$levelName = '';
		if($postType == 'YPZW-WY' || $postType == '����') {
			$level = new model_hr_basicinfo_level();
			$WYlevel = $level->get_d($positionLevel);
			$levelName = $WYlevel['personLevel'];
		} else {
			switch ($positionLevel){
				case '1' : $levelName = '����'; break;
				case '2' : $levelName = '�м�'; break;
				case '3' : $levelName = '�߼�'; break;
			}
		}
		return $levelName;
	}

	/**
	 * ���ݵ���id��ԭ���һ���޸ļ�¼
	 */
	function recoveryLastTime_d($id) {
		try {
			$this->start_d();

			$logsettingDao = new model_syslog_setting_logsetting();
			$logObj = $logsettingDao->find(array('tableName' => $this->tbl_name));
			if ($logObj) {
				$operationItemDao = new model_syslog_operation_logoperationItem();
				$editObj = $lastTimeObj = $operationItemDao->findByLogAndPk($logObj['id'] ,$id);
				if (is_array($editObj)) {
					foreach ($editObj as $key => $val) {
						/****�н�������ת���������Ҫ����ת�ش���****/
						//����
						if ($val['columnCcode'] == 'positionLevelName') {
							switch ($val['oldValue']) {
								case '����' : $levelId = '1'; break;
								case '�м�' : $levelId = '2'; break;
								case '�߼�' : $levelId = '3'; break;
								default : $levelId = $this->get_table_fields('oa_hr_level' ," personLevel='$val[positionLevelName]' " ,'id');
							}
							$lastObj['positionLevel'] = $levelId;
							continue;
						}

						//���Խ��
						if ($val['columnCcode'] == 'useInterviewResultName') {
							if ($val['oldValue'] == '�����˲�') {
								$lastObj['useInterviewResult'] = 0;
							} else {
								$lastObj['useInterviewResult'] = 1;
							}
							continue;
						}

						//��Ŀ����
						if ($val['columnCcode'] == 'projectTypeName') {
							if ($val['oldValue'] == '������Ŀ') {
								$lastObj['projectType'] = 'GCXM';
							} else if ($val['oldValue'] == '�з���Ŀ') {
								$lastObj['projectType'] = 'YFXM';
							} else {
								$lastObj['projectType'] = '';
							}
							continue;
						}

						//�����Ƿ񰴹�˾��׼
						if ($val['columnCcode'] == 'isCompanyStandardName') {
							if ($val['oldValue'] == '��') {
								$lastObj['isCompanyStandard'] = 1;
							} else {
								$lastObj['isCompanyStandard'] = 0;
							}
							continue;
						}

						$lastObj[$val['columnCcode']] = $val['oldValue'];
					}
				}
				$lastObj['id'] = $id;
				$this->updateById($lastObj); //�޸�Ϊԭ���ļ�¼
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * �����ʼ�֪ͨ�����������Ա
	 * @param $id ����id
	 * @param $fieldArr ��Ҫ��֤���ֶ�����,Ĭ��Ϊ��
	 */
	function sendMailByEdit_d($id,$fieldArr = null) {
		//����id��ȡ������־����
		$logoperationDao = new model_syslog_operation_logoperation();
		$logoperationDao->searchArr = array ("tableName" => $this->tbl_name, "pkValue" => $id );
		$rows = $logoperationDao->listBySqlId ( "select_detail" );
		if($rows){
			//��ȡ���ݱ��
			$rs = $this->find(array('id' => $id),null,'formCode');
			//ֻ��ʾ���µĲ�����־
			$logContent =  $logoperationDao->showAtBusinessView(array($rows[0]));
			//ȥ����ϸ��������
			$logContent = str_replace('style="display:none"', "", $logContent);
			$logContent = str_replace('<a href="#" onclick="showTrContent(this,0)">������</a>', "", $logContent);
			$logContent = str_replace('ҵ�������ֶ�ֵ:'.$id, "���ݱ��:".$rs['formCode'], $logContent);
			if(!empty($fieldArr)){
				$fieldCount = 0;//������֤�ֶ�ʵ�ʴ��ڵĸ���
				if(in_array("socialPlace", $fieldArr) && strpos($logContent,"socialPlace")){//����������籣��������أ���֪ͨ�����������Ա
					$fieldCount ++;
					$this->mailDeal_d('interviewEditSocialPlace',null,array('mailContent' => $logContent));
					//������֤����ֶ�����,��Ҫ��ȥ��֤�ֶεĸ����Լ�������־�ж����3��<tr>
					$trNum = substr_count($logContent,"<tr") - $fieldCount - 3;
					if($trNum > 0){//������Ҫ��֤���ֶ��⣬�������������ֶΣ���Ҫ֪ͨ������Ա
						$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
					}
				}else{
					$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
				}
			}else{
				//Ĭ��֪ͨ�ʼ�
				$this->mailDeal_d('interviewEdit',null,array('mailContent' => $logContent));
			}
		}
	}
	
	/**
	 * ��ȡ��ǰ���ݵ���������
	 * @param $id ����id
	 */
	function countWorkFlow($id){
		$sql = "SELECT COUNT(*) as count FROM wf_task WHERE code = '".$this->tbl_name."' AND Pid = '".$id."'";
		$rs = $this->findSql($sql);
		return $rs[0]['count'];
	}
}