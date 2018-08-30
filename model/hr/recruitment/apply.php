<?php

include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

/**
 * @author Administrator
 * @Date 2012��7��11�� ������ 13:20:21
 * @version 1.0
 * @description:��Ա����� Model��
 */
class model_hr_recruitment_apply  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recruitment_apply";
		$this->sql_map = "hr/recruitment/applySql.php";

		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'nocheck',
				'statusCName' => 'δ�´�',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'recruiting',
				'statusCName' => '��Ƹ��',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'abord',
				'statusCName' => '��ͣ',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'finish',
				'statusCName' => '���',
				'key' => '4'
			),
			// 5 => array (
			// 	'statusEName' => 'closed',
			// 	'statusCName' => '�ر�',
			// 	'key' => '5'
			// ),
			// 6 => array (
			// 	'statusEName' => 'suspend',
			// 	'statusCName' => '����',
			// 	'key' => '6'
			// ),
			7 => array (
				'statusEName' => 'cancel',
				'statusCName' => 'ȡ��',
				'key' => '7'
			),
			8 => array (
				'statusEName' => 'submit',
				'statusCName' => '�ύ',
				'key' => '8'
			)
		);

		//�����߲���id
		$this->serviceLine = array('120','210','211','212','213','214','215','217','218','219','228');

		parent::__construct ();
	}
	/*****************************************************��ʾ�ָ���**********************************************/

	/*
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		try {
			foreach ( $this->state as $key => $val ) {
				if ($val['stateVal'] == $stateVal) {
					$returnVal = $val ['stateCName'];
				}
			}
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
		return $returnVal;
	}

	/*
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateEName'] == $stateSta) {
				$returnVal = $val ['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/*****************************************************��ʾ�ָ���**********************************************/


	/**
	 * �����Ա������Ϣ
	 */
	function add_d($object){
		try{
			$this->start_d();
			$object['formCode'] = 'ZY'.date ( "YmdHis" );//���ݱ��
			$dictDao = new model_system_datadict_datadict();
			//update chenrf 20130508
			if(!empty($object['actType']) && 'state' == $object['actType']) {      //״̬Ϊ�ύ
				$object['state'] = $this->statusDao->statusEtoK ( 'submit' );
			} else {
				$object['state'] = $this->statusDao->statusEtoK ( 'save' );
			}
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			if(isset($object['education'])){
				$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			}
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$object['ExaStatus'] = 'δ�ύ';
			$object['entryNum'] = 0;//��ְ����
			$object['beEntryNum'] = 0;//����ְ����
			$object['ingtryNum'] = $object['needNum'] - $object['entryNum'] - $object['beEntryNum'];//����Ƹ����
			if($object['useAreaId'] > 0) {
				$object ['useAreaName'] = $this->get_table_fields('area', "ID='".$object['useAreaId']."'", 'Name');
			}
			$id = parent::add_d($object,true);

			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �༭��Ա������Ϣ
	 */
	function edit_d($object){
		try{
			$this->start_d();

			$needNum = $object['needNum'];//��������
			$entryNum = $object['entryNum'];//����ְ����
			$beEntryNum = $object['beEntryNum'];  //����ְ����
			$object['ingtryNum'] = $needNum - $entryNum - $beEntryNum;//����Ƹ����

			$dictDao = new model_system_datadict_datadict();
			$object['addType'] = $dictDao->getDataNameByCode($object['addTypeCode']);
			$object['employmentType'] = $dictDao->getDataNameByCode($object['employmentTypeCode']);
			$object['maritalStatusName'] = $dictDao->getDataNameByCode($object['maritalStatus']);
			$object['educationName'] = $dictDao->getDataNameByCode($object['education']);
			$object['postTypeName'] = $dictDao->getDataNameByCode($object['postType']);
			$id = parent::edit_d($object ,true);
			//���¸���������ϵ
			$this->updateObjWithFile($object['id']);
			$this->commit_d();
			return $object['id'];
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭�ؼ�Ҫ��
	 */
	function editKeyPoints($object) {
		try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �༭��Ա������Ϣ
	 */
	function editState_d($object) {
		try{
			$this->start_d();

			$oldObj = $this->get_d($object['id']);
			$stateName = $this->statusDao->statusKtoC($object['state']);
			if ($object['state'] == 2) {
				$stateName = '����';
			}
			$reason = $_SESSION['USERNAME'].'&nbsp;&nbsp;'.date('Y-m-d H:i:s').'<br>'.$stateName.'ԭ��'.$object['reasonRemark'].'<breakpoint>';

			if ($object['state'] == 3 || $object['state'] == 2) { //��ͣ������
				$object['stopStart'] = $oldObj['stopStart'].$reason;
			} else if ($object['state'] == 7) { //ȡ��
				$object['cancelContent'] = $reason;
			}

			$rs = parent::edit_d($object ,true);

			$this->commit_d();
			return $rs;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���为����
	 */
	function assignHead_d($object){
		try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			if(!empty($object['assistManId'])) {//���Э����
				$assistManName = explode(",",$object['assistManName']);
				$assistManId = explode(",",$object['assistManId']);
				$memberDao = new model_hr_recruitment_applymember();
				foreach($assistManId as $key=>$val){
					$member['parentId'] = $object['id'];
					$member['formCode'] = $object['formCode'];
					$member['assesManName'] = $assistManName[$key];
					$member['assesManId'] = $val;
					$memberDao->add_d($member);
				}
			}
			$this->passedEmail_d($object);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �޸ĸ�����
	 */
	function editHead_d($object){
		try{
			$this->start_d();
			$apply = $this -> get_d($object['id']);
			$id = parent::edit_d($object ,true);

			//Э���˴���
			$memberDao = new model_hr_recruitment_applymember();
			if ($apply['assistManName'] != $object['assistManName']) {
				$memberDao->delete(array('parentId'=>$object['id']));
				if(!empty($object['assistManId'])) {
					$assistManName = explode(",",$object['assistManName']);
					$assistManId = explode(",",$object['assistManId']);
					foreach($assistManId as $key=>$val){
						$member['parentId'] = $object['id'];
						$member['formCode'] = $object['formCode'];
						$member['assesManName'] = $assistManName[$key];
						$member['assesManId'] = $val;
						$memberDao->add_d($member);
					}
				}
			}

			if($apply['recruitManName'] != $object['recruitManName'] || $apply['assistManName'] != $object['assistManName']){
				$this->passedEmail_d($object);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * �����б�����
	 */
	function dealRows_d($rows,$sumrows){
		$sumNeedNum=0;
		$sumEntryNum=0;
		$sumBeEntryNum=0;
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				//��������������
				$sumNeedNum=bcadd($sumNeedNum,$val ['needNum']);
				$sumEntryNum=bcadd($sumEntryNum,$val ['entryNum']);
				$sumBeEntryNum=bcadd($sumBeEntryNum,$val ['beEntryNum']);
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC( $val ['state'] );
				$rows[$key]['viewType']=1;//�������������ݻ����ܼ�
			}

			//�ܼ�ͳ��
			$sumNeedNumAll=0;
			$sumEntryNumAll=0;
			$sumBeEntryNumAll=0;

			foreach($sumrows as $sumKey=>$sumVal){
				//��������������
				$sumNeedNumAll=bcadd($sumNeedNumAll,$sumVal ['needNum']);
				$sumEntryNumAll=bcadd($sumEntryNumAll,$sumVal ['entryNum']);
				$sumBeEntryNumAll=bcadd($sumBeEntryNumAll,$sumVal['beEntryNum']);

			}
		}
		return $rows;
	}

	/**
	 * �ı�״̬
	 */
	function changeState_d($object){
		try{
			$this->start_d();
			parent::edit_d($object ,true);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �������������������ʼ�
	 *@param $id �ڲ��Ƽ�ID
	 */
	function passedEmail_d($object) {
		try {
			$this->start_d();
			$apply = $this->get_d($object['id']);
			$emailArr = array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID'] = $apply['recruitManId'].",".$apply['assistManId'].",".$apply['formManId'];
			$emailArr['TO_NAME'] = $apply['recruitManName'].",".$apply['assistManName'].",".$apply['formManName'];
			if ($emailArr['TO_ID']) {
				$addmsg = "";
				$deptName = $apply['deptName'];
				$postTypeName = $apply ['postTypeName'];
				$positionName = $apply ['positionName'];
				$needNum = $apply['needNum'];
				$hopeDate = $apply['hopeDate'];
				$recruitManName = $object['recruitManName'];
				$assistManName = $object['assistManName'];
				$addmsg .=  <<<EOT
				<table width="500px">
					<thead>
						<tr align="center">
							<td><b>������</b></td>
							<td><b>ְλ����</b></td>
							<td><b>����ְλ</b></td>
							<td><b>��������</b></td>
							<td><b>ϣ������ʱ��</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$deptName</td>
							<td>$postTypeName</td>
							<td>$positionName</td>
							<td>$needNum</td>
							<td>$hopeDate</td>
						</tr>
					</tbody>
					</table>
EOT;
				$addmsg .= "<br>��˽����";
				$addmsg .= "<font color='blue'>ͨ��</font>";
				$addmsg .= "<br>�����ˣ�";
				$addmsg .= "<font color='blue'>$recruitManName</font>";
				$addmsg .= "<br>Э���ˣ�";
				$addmsg .= "<font color='blue'>$assistManName</font>";

				$emailDao = new model_common_mail();
				$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'apply_passed', '���ʼ�Ϊ��Ա����ͨ��֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);
			}

			$this -> commit_d();
			return true;
		} catch (Exception $e) {
			$this -> rollBack();
			return null;
		}

	}

	/**
	 *��������Ƹ��������
	 */
	//update chenrf 20130604 �����ְͬʱ��������Ƹ����
	function updateEntryNum($id,$entryNum){
		$sql = " update ".$this->tbl_name." set entryNum=(ifnull(entryNum,0) + $entryNum), beEntryNum=(ifnull(beEntryNum,1) - 1) where id=$id ";
		return $this->query($sql);
	}

	/**
	 * ����¼��֪ͨ�ͷ�����ְͬʱ��������Ƹ����
	 */
	function updateBeEntryNum($id,$beEntryNum){
		$sql = " update ".$this->tbl_name." set beEntryNum=(ifnull(beEntryNum,0) + $beEntryNum) where id=$id ";
		return $this->query($sql);
	}


	/*****************************��������*****************************/
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)) {
				//�����±�
				$keyArr = array(
					'formCode', //���ݱ��
					'state', //����״̬
					'ExaStatus', //����״̬
					'formManName', //�����
					'resumeToName', //�ӿ���
					'deptName', //ֱ������
					'deptNameS', //��������
					'deptNameT', //��������
					'deptNameF', //�ļ�����
					'workPlace', //�����ص�
					'postTypeName', //ְλ����
					'positionName', //����ְλ
					'developPositionName', //�з�ְλ
					'network', //����
					'device', //�豸
					'positionLevel', //����
					'projectGroup', //������Ŀ��
					'isEmergency', //�Ƿ����
					'tutor', //��ʦ
					'computerConfiguration', //��������
					'formDate', //�������
					'ExaDT', //����ͨ��ʱ��
					'assignedDate', //�´�����
					'createTime', //¼������
					'entryDate', //����ʱ��
					'firstOfferTime', //��һ��offerʱ��
					'lastOfferTime', //���һ��offerʱ��
					'addType', //��Ա����
					'needNum', //��������
					'entryNum', //����ְ����
					'beEntryNum', //����ְ����
					'stopCancelNum', //��ͣȡ������
					'ingtryNum', //����Ƹ����
					'recruitManName', //��Ƹ������
					'assistManName', //��ƸЭ����
					'userName', //¼������
					'applyReason', //����ԭ��
					'workDuty', //����ְ��
					'jobRequire', //��ְҪ��
					'keyPoint', //�ؼ�Ҫ��
					'attentionMatter', //ע������
					'leaderLove', //�����쵼ϲ��
					'applyRemark' //���ȱ�ע
				);
				//���ݸ�ʽת��
				$newData = array();
				foreach ($excelData as $key => $val) {
					if(!empty($val[3]) && !empty($val[5])){
						$tmpData = array();
						foreach ($keyArr as $k => $v) {
							$tmpData[$v] = trim($val[$k]);
						}
						array_push($newData ,$tmpData);
					}
				}
			}

			if (!empty($newData)) {
				//������ѭ��
				foreach($newData as $key => $val){
					$actNum = $key + 2; //�����к�
					$inArr = array(); //��������

					//״̬
					if(!empty($val['state'])) {
						$inArr['state'] = $this->statusDao->statusCtoK($val['state']) ;
					} else {
						$inArr['state'] = $this->statusDao->statusEtoK('nocheck');
					}

					//����״̬
					if(!empty($val['ExaStatus'])) {
						if($val['ExaStatus'] == '���' || $val['ExaStatus'] == 'δ�ύ' || $val['ExaStatus'] == '��������') {
							$inArr['ExaStatus'] = $val['ExaStatus'];//����״̬
						} else {
							$inArr['ExaStatus'] = '���'; //����״̬
						}
					}

					//�����
					if(!empty($val['formManName'])) {
						if(!isset($userArr[$val['formManName']])){
							$rs = $otherDataDao->getUserInfo($val['formManName']);
							if(!empty($rs)){
								$userArr[$val['formManName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['formManName'] = $val['formManName'];
						$inArr['formManId'] = $userArr[$val['formManName']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//���Žӿ���
					if(!empty($val['resumeToName'])) {
						if(!isset($userArr[$val['resumeToName']])) {
							$rs = $otherDataDao->getUserInfo($val['resumeToName']);
							if(!empty($rs)){
								$userArr[$val['resumeToName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĽӿ���</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['resumeToName'] = $val['resumeToName'];
						$inArr['resumeToId'] = $userArr[$val['resumeToName']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�ӿ���Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ֱ������
					if(!empty($val['deptName'])) {
						if(!isset($deptArr[$val['deptName']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptName']);
							if(!empty($rs)){
								$deptArr[$val['deptName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ֱ������</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptName'];
						$inArr['deptId'] = $deptArr[$val['deptName']];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ֱ������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��������
					if(!empty($val['deptNameS'])) {
						if(!isset($deptArr[$val['deptNameS']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameS']);
							if(!empty($rs)){
								$deptArr[$val['deptNameS']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĶ�������</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameS'];
						$inArr['deptId'] = $deptArr[$val['deptNameS']];
					}

					//��������
					if(!empty($val['deptNameT'])) {
						if(!isset($deptArr[$val['deptNameT']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameT']);
							if(!empty($rs)){
								$deptArr[$val['deptNameT']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���������</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameT'];
						$inArr['deptId'] = $deptArr[$val['deptNameT']];
					}

					//�ļ�����
					if(!empty($val['deptNameF'])) {
						if(!isset($deptArr[$val['deptNameF']])) {
							$rs = $otherDataDao->getDeptId_d($val['deptNameF']);
							if(!empty($rs)){
								$deptArr[$val['deptNameF']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ��ļ�����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['deptName'] = $val['deptNameF'];
						$inArr['deptId'] = $deptArr[$val['deptNameF']];
					}

					//�����ص�
					if(!empty($val['workPlace'])) {
						$inArr['workPlace'] = $val['workPlace'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�����ص�Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//ְλ����
					if(!empty($val['postTypeName'])) {
						if(!isset($datadictArr[$val['postTypeName']])){
							$rs = $datadictDao->getCodeByName('YPZW' ,$val['postTypeName']);
							if(!empty($rs)){
								$trainsType = $datadictArr[$val['postTypeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְλ����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$trainsType = $datadictArr[$val['postTypeName']]['code'];
						}
						$inArr['postType'] = $trainsType;
						$inArr['postTypeName'] = $val['postTypeName'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ְλ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//����ְλ
					if(!empty($val['positionName'])) {
						if(!isset($jobsArr[$val['positionName']])){
							$rs = $otherDataDao->getJobId_d($val['positionName']);
							if(!empty($rs)){
								$jobsArr[$val['positionName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְλ</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['positionName'] = $val['positionName'];
						$inArr['positionId'] = $jobsArr[$val['positionName']];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!ְλΪ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�з�ְλ
					if(!empty($val['developPositionName'])) {
						$inArr['developPositionName'] = $val['developPositionName'];
					}

					//����
					if(!empty($val['network'])) {
						$inArr['network'] = $val['network'];
					}

					//�豸
					if(!empty($val['device'])) {
						$inArr['device'] = $val['device'];
					}

					//����
					if(!empty($val['positionLevel'])) {
						$inArr['positionLevel'] = $val['positionLevel'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//������Ŀ��
					if(!empty($val['projectGroup'])) {
						$inArr['projectGroup'] = $val['projectGroup'];
					}

					//�Ƿ����
					if(!empty($val['isEmergency'])) {
						if($val['isEmergency'] == '��') {
							$inArr['isEmergency'] = 1;
						}else if($val['isEmergency'] == '��') {
							$inArr['isEmergency'] = 0;
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�Ƿ���������ǻ��</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!�Ƿ����Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��ʦ
					if(!empty($val['tutor'])) {
						if(!isset($userArr[$val['tutor']])) {
							$rs = $otherDataDao->getUserInfo($val['tutor']);
							if(!empty($rs)){
								$userArr[$val['tutor']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĵ�ʦ</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['tutor'] = $val['tutor'];
						$inArr['tutorId'] = $userArr[$val['tutor']]['USER_ID'];
					} else {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��ʦΪ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//��������
					if(!empty($val['computerConfiguration'])) {
						if ($val['computerConfiguration'] == '��˾�ṩ�ʼǱ�����'
								|| $val['computerConfiguration'] == '��˾�ṩ̨ʽ����'
								|| $val['computerConfiguration'] == '�Ա��ʼǱ�����') {
							$inArr['computerConfiguration'] = $val['computerConfiguration'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!�����ڵĵ�������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//�������
					if(!empty($val['formDate'])) {
						if (!is_numeric($val['formDate'])) {
							$inArr['formDate'] = $val['formDate'];
						} else {
							$beginDate = date('Y-m-d' ,(mktime(0, 0, 0, 1, $val['formDate'] - 1, 1900)));
							if($beginDate == '1970-01-01') {
								$quitDate = date('Y-m-d' ,strtotime($val['formDate']));
								$inArr['formDate'] = $quitDate;
							}else{
								$inArr['formDate'] = $beginDate;
							}
						}
					}
					
					//�´�����
					if(!empty($val['assignedDate'])) {
						if (!is_numeric($val['assignedDate'])) {
							$inArr['assignedDate'] = $val['assignedDate'];
						} else {
							$beginDate = date('Y-m-d' ,(mktime(0, 0, 0, 1, $val['assignedDate'] - 1, 1900)));
							if($beginDate == '1970-01-01') {
								$quitDate = date('Y-m-d' ,strtotime($val['assignedDate']));
								$inArr['assignedDate'] = $quitDate;
							}else{
								$inArr['assignedDate'] = $beginDate;
							}
						}
					}
					
					//����ͨ��ʱ��
					if(!empty($val['ExaDT'])) {
						if (!is_numeric($val['ExaDT'])) {
							$inArr['ExaDT'] = $val['ExaDT'];
						} else {
							$beginDate = date('Y-m-d', (mktime(0, 0, 0, 1, $val['ExaDT'] - 1, 1900)));
							if($beginDate=='1970-01-01'){
								$quitDate = date('Y-m-d',strtotime ($val['ExaDT']));
								$inArr['ExaDT'] = $quitDate;
							}else{
								$inArr['ExaDT'] = $beginDate;
							}
						}
					}

					//��Ա����
					if(!empty($val['addType'])) {
						if(!isset($datadictArr[$val['addType']])){
							$rs = $datadictDao->getCodeByName('HRZYLX' ,$val['addType']);
							if(!empty($rs)){
								$trainsType = $datadictArr[$val['addType']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���Ա����</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}else{
							$trainsType = $datadictArr[$val['addType']]['code'];
						}
						$inArr['addTypeCode'] = $trainsType;
						$inArr['addType'] = $val['addType'];
					}

					//��������
					if(!empty($val['needNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['needNum'])) {
							$inArr['needNum'] = $val['needNum'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<span class="red">����ʧ��!������������Ϊ������</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!��������Ϊ��</span>';
						array_push($resultArr ,$tempArr);
						continue;
					}

					//����ְ������
					if(!empty($val['entryNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['entryNum'])) {
							$inArr['entryNum'] = $val['entryNum'];
						}else{
							$inArr['entryNum'] = 0;
						}
					}else {
						$inArr['entryNum'] = 0;
					}

					//����ְ������
					if(!empty($val['beEntryNum'])) {
						if (preg_match("/^[1-9]\d*$/",$val['beEntryNum'])) {
							$inArr['beEntryNum'] = $val['beEntryNum'];
						}else{
							$inArr['beEntryNum'] = 0;
						}
					}else {
						$inArr['beEntryNum'] = 0;
					}

					//������
					if(!empty($val['recruitManName'])) {
						if(!isset($userArr[$val['recruitManName']])){
							$rs = $otherDataDao->getUserInfo($val['recruitManName']);
							if(!empty($rs)){
								$userArr[$val['recruitManName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['recruitManName'] = $val['recruitManName'];
						$inArr['recruitManId'] = $userArr[$val['recruitManName']]['USER_ID'];
					}

					//Э����
					if(!empty($val['assistManName'])) {
						if(!isset($userArr[$val['assistManName']])){
							$rs = $otherDataDao->getUserInfo($val['assistManName']);
							if(!empty($rs)){
								$userArr[$val['assistManName']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ڵ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}
						$inArr['assistManName'] = $val['assistManName'];
						$inArr['assistManId'] = $userArr[$val['assistManName']]['USER_ID'];
					}

					//����ԭ��
					if(!empty($val['applyReason'])) {
						$inArr['applyReason'] = $val['applyReason'];
					}

					//����ְ��
					if(!empty($val['workDuty'])) {
						$inArr['workDuty'] = $val['workDuty'];
					}

					//��ְҪ��
					if(!empty($val['jobRequire'])) {
						$inArr['jobRequire'] = $val['jobRequire'];
					}

					//�ؼ�Ҫ��
					if(!empty($val['keyPoint'])) {
						$inArr['keyPoint'] = $val['keyPoint'];
					}

					//ע������
					if(!empty($val['attentionMatter'])) {
						$inArr['attentionMatter'] = $val['attentionMatter'];
					}

					//�����쵼ϲ��
					if(!empty($val['leaderLove'])) {
						$inArr['leaderLove'] = $val['leaderLove'];
					}

					//���ȱ�ע
					if(!empty($val['applyRemark'])) {
						$inArr['applyRemark'] = $val['applyRemark'];
					}

					$suffix = sprintf("%03d" ,rand(0 ,999)); //3λ�������׺
					$inArr['formCode'] = 'ZY'.date( "YmdHis" ).$suffix;//���ݱ��

					$newId = parent::add_d($inArr,true);

					if($newId){
						$tempArr['result'] = '����ɹ�';
					}else{
						$tempArr['result'] = '<span class="red">����ʧ��</span>';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push($resultArr ,$tempArr);
				}
				return $resultArr;
			}
		}
	}

	/*
	 * ����excel
	 */
	function excelOut($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;

		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/����-��Ա���뵼��.xls" ); //��ȡģ��
		//Excel2003����ǰ�ĸ�ʽ
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//���ñ�����ļ����Ƽ�·��
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//�����ǶԹ������������������
		//���õ�ǰ�����������
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ա������Ϣ�б�' ) );
		//���ñ�ͷ����ʽ ����
		$i = 2;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $row + $n, iconv ( "GBK", "utf-8", $value ) );
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
		header ( 'Content-Disposition:inline;filename="' . "��Ա���뵼������.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}

	/*****************************��������*****************************/

	/************add chenrf 20130508***************/
	/**
	 * �ı�״̬
	 * @param $id
	 */
	function changeState($id ,$state = 8) {
		$object['id'] = $id;
		$object['state'] = $state;
		return $this->updateById($object);
	}

	/**
	 * ѡ��������
	 * @param $object
	 */
	function ewfSelect($object){
		$id = $object['id'];
		$deptId = $object['deptId'];
		$addTypeCode = $object['addTypeCode'];
		$rowArr = $this->findSql('select DEPT_ID,PARENT_ID from department');
		$parentId = $this->getParentID($rowArr ,$deptId);
		$selPage = '';
		if(in_array($deptId ,$this->serviceLine)) { //������
			if ($object['employmentTypeCode'] == 'PYLXSX') { //�ù�����Ϊʵϰ��
				$selPage = "ewf_serviceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_serviceLineAddPlan_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_serviceLineAdd_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_serviceLine_index.php'; //��ְ����
						break;
				}
			}
		} else { //�Ƿ�����
			if ($object['employmentTypeCode'] == 'PYLXSX') { //�ù�����Ϊʵϰ��
				$selPage = "ewf_enServiceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_enServiceLineAddPlan_index.php';  //�ƻ�����Ա
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_enServiceLineAdd_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_enServiceLine_index.php'; //��ְ����
						break;
				}
			}
		}
		if($selPage == '') {
			return '';
		}
		return 'controller/hr/recruitment/'.$selPage.'?actTo=ewfSelect&billId='.$id.'&billDept='.$deptId;
	}

	/**
	 * ѡ����������
	 * @param $object
	 */
	function ewfDelWork($object){
		$id = $object['id'];
		$deptId = $object['deptId'];
		$addTypeCode = $object['addTypeCode'];
		$rowArr = $this->findSql('select DEPT_ID,PARENT_ID from department');
		$parentId = $this->getParentID($rowArr ,$deptId);
		$selPage = '';
		if(in_array($deptId ,$this->serviceLine)) { //������
			if ($object['employmentTypeCode'] == 'PYLXSX') { //�ù�����Ϊʵϰ��
				$selPage = "ewf_serviceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_serviceLineAddPlan_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_serviceLineAdd_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_serviceLine_index.php'; //��ְ����
						break;
				}
			}
		} else { //�Ƿ�����
			if ($object['employmentTypeCode'] == 'PYLXSX') { //�ù�����Ϊʵϰ��
				$selPage = "ewf_enServiceLineAddIntern_index.php";
			} else {
				switch ($addTypeCode) {
					case 'ZYLXJHN':
						$selPage = 'ewf_enServiceLineAddPlan_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXJHW':
						$selPage = 'ewf_enServiceLineAdd_index.php'; //�ƻ�����Ա
						break;
					case 'ZYLXLZ':
						$selPage = 'ewf_enServiceLine_index.php'; //��ְ����
						break;
				}
			}
		}
		if($selPage == '') {
			return '';
		}
		return 'controller/hr/recruitment/'.$selPage.'?actTo=delWork&billId=';
	}

	/**
	 * ����ID��ȡ���Ŷ�������ID(����)
	 * @param $arr  ���� �������DEPT_ID��PARENT_ID�ֶ�
	 * @param $id   Ҫ���ҵ�ID
	 */
	function getParentID($arr ,$id) {
		$parentArr = array();
		foreach ($arr as $row){
			if($id == $row['DEPT_ID']) {
				if($row['PARENT_ID'] == 0) {
					array_push($parentArr ,$id);
				}
				$parentArr = array_merge($parentArr ,$this->getParentID($arr,$row['PARENT_ID']));
			}
		}
		return $parentArr;
	}

	/**
	 * �ʼ�����
	 * update chenrf �����Զ��巢����Ϣ����
	 */
	function thisMail_d($object ,$state=8 ,$msg=null) {
		$this->searchArr['id'] = $object['id'];
		$this->groupBy = 'c.id';
		$row = $this->list_d('select_list');
		include (WEB_TOR . "model/common/mailConfig.php");

		$emailArr = isset ($mailUser['oa_hr_recruitment_apply']) ? $mailUser['oa_hr_recruitment_apply'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);

		$nameStr = $emailArr['TO_NAME'];
		if(in_array($row[0]['deptSId'] ,$this->serviceLine) || $row[0]['postType'] == 'YPZW-WY') {
			$persons = $this->getAuditPersons_d($object);
			if($persons){
				foreach($persons as $key=>$val){
					$receivers .= $val['User'].',';
				}
			}
			$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].','.$emailArr['TO_ID'].','.$row[0]['createId'];
		} else {
			$receivers = $emailArr['TO_ID'].','.$row[0]['createId'];
		}

		if(empty($msg)) {
			$stateC = $this->statusDao->statusKtoC($state);
		} else {
			$stateC = iconv("UTF-8","GB2312//IGNORE",$msg);
		}
		$addMsg = $_SESSION['USERNAME'] .$stateC.'����Ա���룬��鿴��<br>
				���ݱ�� :��<font color="red">'.$object['formCode'].'</font>��<br>
				������ :��<font color="red">'.$object['deptName'].'</font>��<br>
				ֱ������ :��<font color="red">'.$row[0]['deptNameO'].'</font>��<br>
				�������� :��<font color="red">'.$row[0]['deptNameS'].'</font>��<br>
				�������� :��<font color="red">'.$row[0]['deptNameT'].'</font>��<br>
				�����ص� :��<font color="red">'.$row[0]['workPlace'].'</font>��<br>
				���� :��<font color="red">'.$row[0]['network'].'</font>��<br>
				�豸 :��<font color="red">'.$row[0]['device'].'</font>��<br>
				���� :��<font color="red">'.$row[0]['positionLevel'].'</font>��<br>
				�������� :��<font color="red">'.$object['needNum'].'</font> ��<br>';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('��Ա�����ύ', $receivers, $addMsg);
	}

	/**
	 * �Ƚ������飬���Ҫ�޸ĵ�����
	 * @param $oldObj
	 * @param $newObj
	 */
	function fillEdit($oldObj ,$newObj) {
		$data = array(
			// 'needNum',
			'positionLevel' // ����
			,'deptName' //����
			,'deptId'
			,'postType' //ְλ����
			,'postTypeName' //
			,'positionName' //����ְλ
			,'positionId'
			,'developPositionName' //�з�ְλ
			,'workPlace' //�����ص�
			,'wageRange' //���ʷ�Χ
			,'addTypeCode' //��Ա����
			,'addType'
			,'leaveManName' //������
			,'employmentType' //�ù�����
			,'employmentTypeCode'
			,'required'
			,'projectType' //��Ŀ����
			,'projectGroup' //������Ŀ��
			,'projectGroupId'
			,'applyReason' //����ԭ��
			,'useAreaId' //��������
			,'useAreaName'
		);
		$subData = array();
		if($oldObj != $newObj) {
			foreach($oldObj as $key => $val) {
				if($val != $newObj[$key] && in_array($key ,$data)) {
					if($val == '') {
						$val = "��";
					}
					if($newObj[$key] == '') {
						$newObj[$key]=' ';
					}
					$subData[] = $key;
					$newObj[$key.'Edit'] = $newObj[$key];
					$newObj[$key] = $val;
				}
			}
			$re = array_intersect($data ,$subData);
			if(!empty($re)) { //���ָ���ֶα��޸ģ�����������
				return $newObj;
			}
		}
		return '';
	}

	/**
	 * ���������Ա�����޸��ʼ�֪ͨ
	 */
	function auditEditMail_d( $oldObj ,$newObj) {
		$data = array(
			'needNum' => '��������'
			,'positionLevel'=>'����'
			,'deptName'=>'������'
			,'postTypeName'=>'ְλ����'
			,'positionName'=>'����ְλ'
			,'developPositionName'=>'�з�ְλ'
			,'workPlace'=>'�����ص�'
			,'wageRange'=>'���ʷ�Χ'
			,'addType'=>'��Ա����'
			,'leaveManName'=>'��ְ/����������'
			,'employmentType'=>'�ù�����'
			,'projectType'=>'��Ŀ����'
			,'projectGroup'=>'������Ŀ��'
			,'applyReason'=>'����ԭ��'
			,'useAreaName'=>'��������'
		);
		$dictDao = new model_system_datadict_datadict();
		$changeData = array();
		foreach ($data as $key => $val) {
			if (array_key_exists($key.'Edit' ,$newObj)) {
				$changeData[$key]['data'] = $newObj[$key.'Edit'];
				$changeData[$key]['name'] = $val;
			}
		}
		$emailDao = new model_common_mail();

		$mailContent = "���ã���".$_SESSION['USERNAME']."���Ե��ݡ�<span style='color:blue'>".$oldObj['formCode']."</span>�������������޸ģ�<br>"
					.'<table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td>&nbsp;</td><td width="40%">������</td><td width="40%">������</td></tr>';

		foreach ($changeData as $key => $val) {
			$mailContent .= '<tr><td>'.$val['name'].'</td><td>'.$oldObj[$key].'</td><td>'.$val['data'].'</td></tr>';
		}

		$mailContent .= '</table>';

		include_once(WEB_TOR."model/common/mailConfig.php");
		$receiverId = $newObj['resumeToId'].','.$oldObj['recruitManId'].','.$mailUser['oa_hr_recruitment_apply']['TO_ID'].','.$oldObj['createId'];
	 	$emailDao->mailGeneral("��Ա�����޸�֪ͨ" ,$receiverId ,$mailContent);
	}

	/**
	 * �����޸ĺ�ص�
	 */
	function dealEditApply($id){
		$data = array('needNum','positionLevel','deptName','deptId','postType','postTypeName','positionName','positionId','developPositionName','workPlace',
  	'wageRange','addTypeCode','addType','leaveManName','employmentType','employmentTypeCode','required','projectType','projectGroup','projectGroupId','applyReason','useAreaId','useAreaName');
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $id );
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$newRow=array();
			$rows= $this->get_d ( $objId );
			if($folowInfo['examines']!="no"){   // ����ͨ��
				//���������
				foreach ($data as $val){
					if(!empty($rows[$val.'Edit']))
						$newRow[$val]=$rows[$val.'Edit'];
				}
				//����������������������������Ƹ����
				if(isset($newRow['needNum'])){
					$needNum=$newRow['needNum'];//��������
					$entryNum=$rows['entryNum'];//����ְ����
					$beEntryNum = $rows['beEntryNum'];  //����ְ����
					$newRow['ingtryNum'] = $needNum - $entryNum - $beEntryNum;//����Ƹ����
				}
				$this->applyEditAuditMail_d($objId ,'ͨ��'); //���ʼ�֪ͨ�����
			}else {
				$this->applyEditAuditMail_d($objId ,'��ͨ��'); //���ʼ�֪ͨ�����
			}
			//��ձ������ʱ����
			foreach ($data as $val){
				$newRow[$val.'Edit']='';
			}
			$this->update(array('id'=>$objId),$newRow);
		}
	}

	/**
	 * ��Ա���룬�ж���ʾҳ�棬�Ƿ���֡��ؼ�Ҫ�㡱������
	 */
	function showHtm($val,$obj){
		if($val){
			$htm =<<<EOT
			<tr>
				<td class="form_text_left_three">�ؼ�Ҫ��</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="keyPoint" name="apply[keyPoint]" >{$obj['keyPoint']}</textarea>
				</td>
			</tr>
			<tr>
				<td class="form_text_left_three">ע������</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="attentionMatter" name="apply[attentionMatter]">{$obj['attentionMatter']}</textarea>
				</td>
			</tr>
			<tr>
				<td class="form_text_left_three">�����쵼ϲ��</td>
				<td class="form_text_right_three" colspan="5">
					<textarea class="txt_txtarea_biglong" id="leaderLove" name="apply[leaderLove]">{$obj['leaderLove']}</textarea>
				</td>
			</tr>
EOT;
		} else {
			$htm="";
		}
		return $htm;
	}

	/**
	 * ������������ʼ�֪ͨ��Ա���������
	 */
	function applyAuditMail_d( $id , $result) {
		$obj = $this->get_d( $id );
		$receiverId = $obj['createId'];
		$emailDao = new model_common_mail();
		$mailContent = '���ã����ύ�ĵ��ݱ��Ϊ<span style="color:blue">'.$obj['formCode'].
		'</span>����Ա�����������Ϊ��<span style="color:blue">'.$result.
		'</span><br>';
		$emailDao->mailGeneral("��Ա�����������" ,$receiverId ,$mailContent);
	}

	/**
	 * ������������ʼ�֪ͨ��Ա���������
	 */
	function applyEditAuditMail_d( $id , $result) {
		$obj = $this->get_d( $id );
		$receiverId = $obj['formManId'];
		$emailDao = new model_common_mail();
		$mailContent = '���ã����ύ�ĵ��ݱ��Ϊ<span style="color:blue">'.$obj['formCode'].
		'</span>����Ա�����޸��������Ϊ��<span style="color:blue">'.$result.
		'</span><br>';

		$emailDao->mailGeneral("��Ա�����޸��������" ,$receiverId ,$mailContent);
	}

	/**
	 * ��Ա��������ҳ���������ͳ��������Ϣ
	 */
	function getDeptPer_d($id){
		$this->groupBy='c.id';
		$this->searchArr['ExaStatus'] = "���";
		$rows = $this->list_d('select_list');
		$this->searchArr['id'] = $id;
		$this->searchArr['ExaStatus'] = "��������";
		$row = $this->list_d('select_list');
		$beEntryNumS = 0; //�������Ŵ���ְ����
		$beEntryNumT = 0; //�������Ŵ���ְ����
		$beEntryNumF = 0; //�ļ����Ŵ���ְ����
		//��ȡ�����ļ����Ŵ���ְ����
		foreach($rows as $key => $val){
			if($row[0]['deptNameS'] && $val['deptNameS'] == $row[0]['deptNameS']) {
				$beEntryNumS = $beEntryNumS + $val['beEntryNum'];
			}
			if($row[0]['deptNameT'] && $val['deptNameT'] == $row[0]['deptNameT']) {
				$beEntryNumT = $beEntryNumT + $val['beEntryNum'];
			}
			if($row[0]['deptNameF'] && $val['deptNameF'] == $row[0]['deptNameF']) {
				$beEntryNumF = $beEntryNumF + $val['beEntryNum'];
			}
		}
		//��ȡ�����ļ�������ְ����
		$personnel = new model_hr_personnel_personnel();
		if($row[0]['deptNameS']) {
			$conditionS['deptNameS'] = $row[0]['deptNameS'];
			$conditionS['employeesStateName'] = '��ְ';
			$employeesStateNumS = $personnel->findCount($conditionS); //����������ְ����
		}
		if($row[0]['deptNameT']) {
			$conditionT['deptNameT'] = $row[0]['deptNameT'];
			$conditionT['employeesStateName'] = '��ְ';
			$employeesStateNumT = $personnel->findCount($conditionT); //����������ְ����
		}
		if($row[0]['deptNameF']) {
			$conditionF['deptNameF'] = $row[0]['deptNameF'];
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
									{$row[0][deptNameS]}
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
									{$row[0][deptNameT]}
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
									{$row[0][deptNameF]}
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
	 * ��ȡ������
	 */
	function getAuditPersons_d($row){
		if($row){
			$task = $this->get_table_fields('wf_task',"Pid='".$row['id']."' and code='oa_hr_recruitment_apply'",'task');
			$sql = "select User from flow_step_partent where wf_task_id = '".$task."'";
			$persons = $this->findSql($sql);
		}
		return $persons;
	}

	/**
	 * ��Ա�����޸ģ��ʼ�֪ͨ
	 */
	function sendEmail_d($diff,$oldRow,$persons){
		$dictDao = new model_system_datadict_datadict();
		$title = "��Ա�����޸�֪ͨ";
		//�ʼ�������
		if($persons){
			foreach($persons as $key=>$val){
				$receivers .=$val['User'].',';
			}
		}

		include (WEB_TOR . "model/common/mailConfig.php");
		$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].','.$oldRow['formManId'].','.$oldObj['createId'];
		//�ֶζ�Ӧ������
		$field=array('deptName'=>'������','postTypeName'=>'ְλ����','positionName'=>'����ְλ','developPositionName'=>'�з�ְλ','positionLevel'=>'����','isEmergency'=>'�Ƿ����','needNum'=>'��������',
			'hopeDate'=>'ϣ������ʱ��','workPlace'=>'�����ص�','wageRange'=>'���ʷ�Χ','addMode'=>'���鲹�䷽ʽ','addType'=>'��Ա����','employmentType'=>'�ù�����',
			'useAreaName'=>'��������','projectType'=>'��Ŀ����','projectGroup'=>'������Ŀ��','sex'=>'�Ա�','age'=>'����','maritalStatus'=>'����','education'=>'ѧ��',
			'professionalRequire'=>'רҵҪ��','workExperiernce'=>'��������Ҫ��','network'=>'����','device'=>'�豸','resumeToName'=>'����������','applyReason'=>'����ԭ��','workDuty'=>'����ְ��',
			'jobRequire'=>'��ְҪ��','workArrange'=>'�������ڵĹ�������','assessmentIndex'=>'�����ڽ��������Ҫ����ָ��','changeReason'=>'���ԭ��','leaveManName'=>'��ְ/����������');
		if(array_key_exists('isEmergency',$diff)){
			if($diff['isEmergency']==1)
				$diff['isEmergency']='��';
			else
				$diff['isEmergency']='��';
		}
		if(array_key_exists('projectType',$diff)){
			if($diff['projectType']=='YFXM')
				$diff['projectType']='�з���Ŀ';
			else if($diff['projectType']=='GCXM')
				$diff['projectType']='������Ŀ';
		}
		if(array_key_exists('maritalStatus',$diff)){
			$diff['maritalStatus'] = $dictDao->getDataNameByCode($diff['maritalStatus']);
		}
		if(array_key_exists('education',$diff)){
			$diff['education'] = $dictDao->getDataNameByCode($diff['education']);
		}
		foreach($diff as $key => $val){
			if($field[$key])
				$diffName[$field[$key]] = $val;
		}
		//ƴװ�ʼ�����
		$msg ="���ã���".$_SESSION['USERNAME']."���Ե��ݡ�".$oldRow['formCode']."�������������޸ģ�<br>";
		foreach($diffName as $key => $val){
			$msg .= $key."=>".$val."<br>";
		}
		$emailDao = new model_common_mail();
		$emailDao ->mailClear($title,$receivers,$msg);
		return  true;
	}

	/**
	 * ��Ա���룬���𣬹رգ�ȡ������ʱ�ʼ�֪ͨ
	 */
	function emailNotice_d($object,$state){
		$this->searchArr['id']=$object['id'];
		$this->groupBy = 'c.id';
		$row = $this->list_d('select_list');
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailArr = isset ($mailUser['oa_hr_recruitment_apply']) ? $mailUser['oa_hr_recruitment_apply'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);
		$nameStr = $emailArr['TO_NAME'];
		if(in_array($row[0]['deptSId'] ,$this->serviceLine) || $row[0]['postType'] == 'YPZW-WY') {
			$persons = $this->getAuditPersons_d($object);
			if($persons){
				foreach($persons as $key => $val) {
					$receivers .= $val['User'].',';
				}
			}
			$receivers .= $mailUser['oa_hr_recruitment_apply_duo']['TO_ID'].$emailArr['TO_ID'].','.$object['formManId'].','.$row[0]['createId'];
		} else {
			$receivers = $emailArr['TO_ID'].','.$row[0]['createId'];
		}
		if(empty($msg)) {
			$stateC = $this->statusDao->statusKtoC($state);
		} else {
			$stateC = iconv("UTF-8","GB2312//IGNORE",$msg);
		}
		$addMsg = $_SESSION['USERNAME'] .$stateC.'����Ա���룬��鿴��<br>
				���ݱ�� :��<font color="red">'.$object['formCode'].'</font>��<br>
				������ :��<font color="red">'.$object['deptName'].'</font>��<br>
				ֱ������ :��<font color="red">'.$row[0]['deptNameO'].'</font>��<br>
				�������� :��<font color="red">'.$row[0]['deptNameS'].'</font>��<br>
				�������� :��<font color="red">'.$row[0]['deptNameT'].'</font>��<br>
				�����ص� :��<font color="red">'.$row[0]['workPlace'].'</font>��<br>
				���� :��<font color="red">'.$row[0]['network'].'</font>��<br>
				�豸 :��<font color="red">'.$row[0]['device'].'</font>��<br>
				���� :��<font color="red">'.$row[0]['positionLevel'].'</font>��<br>
				�������� :��<font color="red">'.$object['needNum'].'</font> ��<br>';
		$emailDao = new model_common_mail();
		$emailDao->mailClear('��Ա�����ύ' , $receivers ,$addMsg);
	}
}
?>